<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Coupon;

use Illuminate\Http\Request;
use Surfsidemedia\Shoppingcart\Facades\Cart; 
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\OrderItem;
use App\Models\Transaction;
use Carbon\Carbon;



class CartController extends Controller
{
    public function index(){
        $items = Cart::instance('cart')->content();
        return view('cart', compact('items'));
    }

    public function add_to_cart(Request $request)
    {
        Cart::instance('cart')->add(
            $request->id,
            $request->name,
            $request->quantity,
            $request->price,
            [
            'color' => $request->color,
            'size' => $request->size
        ]

       
        )->associate('App\Models\Product');
         
        return redirect()->back();
       
    }
    // Thêm method buy_now mới
    public function buy_now(Request $request)
    {
        // Xóa giỏ hàng hiện tại
        Cart::instance('cart')->destroy();
       
        // Thêm sản phẩm vào giỏ hàng
        Cart::instance('cart')->add(
            $request->id,
            $request->name,
            $request->quantity,
            $request->price,
            [
                'color' => $request->color,
                'size' => $request->size
            ]
        )->associate('App\Models\Product');
       
        // Chuyển hướng đến trang checkout
        return redirect()->route('cart.checkout');
    }


    public function remove($rowId)
    {
        Cart::instance('cart')->remove($rowId);


        // Chuyển hướng lại trang giỏ hàng với thông báo thành công
        return redirect()->back()->with('success', 'Product removed from cart!');
    }


    public function increase_cart_quantity($rowId)
    {
        $product = Cart::instance('cart')->get($rowId);
        $qty = $product->qty + 1;
        Cart::instance('cart')->update($rowId, $qty);
        return redirect()->back();
    }


    public function decrease_cart_quantity($rowId)
    {
        $product = Cart::instance('cart')->get($rowId);
        $qty = $product->qty - 1;
        if ($qty > 0) {
            Cart::instance('cart')->update($rowId, $qty);
        } else {
            Cart::instance('cart')->remove($rowId);
        }
        return redirect()->back();
    }


    public function checkout()
    {
        if(!Auth::check()){
            return redirect()->route('login');
        }
       
        $this->setAmountForCheckout();
       
        return view('checkout');
    }


    public function place_an_order(Request $request)
    {
        $request->validate([
            'name' => 'required|max:100',
            'phone' => 'required|numeric|digits:10',
            'address' => 'required|max:255',
            'mode' => 'required',
        ]);


        $user_id = Auth::user()->id;


        $this->setAmountForCheckout();


        if ($request->mode == 'vnpay') {
            Session::put('order_data', [
                'name' => $request->name,
                'phone' => $request->phone,
                'address' => $request->address,
            ]);
           
            return $this->vnpay_payment();
        }
        $order = new Order();
        $order->user_id = $user_id;
        $order->total = Session::get('checkout')['total']+20000;
        $order->name = $request->name;
        $order->phone = $request->phone;
        $order->address = $request->address;
        $order->save();


        foreach (Cart::instance('cart')->content() as $item) {
            $orderItem = new OrderItem();
            $orderItem->product_id = $item->id;
            $orderItem->order_id = $order->id;
            $orderItem->price = $item->price;
            $orderItem->quantity = $item->qty;
            $orderItem->save();
        }


        $transaction = new Transaction();
        $transaction->user_id = $user_id;
        $transaction->order_id = $order->id;
        $transaction->mode = $request->mode;
        $transaction->save();


        // Dọn dẹp giỏ hàng và session
        Cart::instance('cart')->destroy();
        Session::forget('checkout');
        Session::forget('coupon');
        Session::forget('discounts');


        Session::put('order_id', $order->id);


        return redirect()->route('cart.order.confirmation');
    }


    public function setAmountForCheckout()
    {
        if(!Cart::instance('cart')->count() > 0)
        {
            Session::forget('checkout');
            return;
        }    
        if(Session::has('coupon'))
        {
            Session::put('checkout',[
                'discount' => Session::get('discounts')['discount'],
                'subtotal' =>  Session::get('discounts')['subtotal'],
                'tax' =>  0,
                'total' =>  Session::get('discounts')['total']
            ]);
        }
        else
        {
            Session::put('checkout',[
                'discount' => 0,
                'subtotal' => Cart::instance('cart')->subtotal(),
                'tax' => Cart::instance('cart')->tax(), 
          
            ]);
        }
    }

    public function order_confirmation()
    {
        if (Session::has('order_id')) {
            $order = Order::find(Session::get('order_id'));
            if ($order) {
                return view('order_confirmation', compact('order'));
            }
        }
        
        return redirect()->route('cart.index')->with('error', 'Order not found!');
    }
    
    public function apply_coupon_code(Request $request)
    {
        $coupon_code = trim($request->coupon_code);
        
        if(empty($coupon_code)){
            return redirect()->back()->with('error','Please enter coupon code!');
        }
        
        $coupon = Coupon::where('coupon_code', $coupon_code)
                        ->where('end_date', '>=', Carbon::today())
                        ->first();
                        
        if (!$coupon) {
            return redirect()->back()->with('error','Invalid or expired coupon code!');
        } 
        
        // Lưu thông tin coupon vào session
        Session::put('coupon', [
            'coupon_code' => $coupon->coupon_code,
            'discount_percentage' => $coupon->discount_percentage
        ]);

        // Tính toán giảm giá
        $this->calculatorDiscount();
        
        // Cập nhật lại checkout
        $this->setAmountForCheckout();
        
        return redirect()->back()->with('success', 'Coupon has been applied successfully!');
    }

    public function remove_coupon()
    {
        Session::forget('coupon');
        Session::forget('discounts');
        $this->setAmountForCheckout();
        
        return redirect()->back()->with('success', 'Coupon removed successfully!');
    }

    public function calculatorDiscount()
    {
        // Lấy subtotal từ cart và chuyển đổi thành số
        $cartSubtotal = Cart::instance('cart')->subtotal();
        // Loại bỏ dấu phẩy và chuyển thành float
        $subtotal = (float) str_replace([',', '.'], ['', ''], $cartSubtotal);
    
        $discount = 0;
        if (Session::has('coupon')) {
            $discountPercentage = Session::get('coupon')['discount_percentage'];
            $discount = ($subtotal * $discountPercentage) / 100;
        }
    
        $subtotalAfterDiscount = $subtotal - $discount;
    
        // Lưu vào session
        Session::put('discounts', [
            'discount' => $discount,
            'subtotal' => $subtotalAfterDiscount,
            'total' => $subtotalAfterDiscount
        ]);
    }
    

    public function vnpay_payment()
    {
        $code_cart = rand(00, 9999);
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = route('cart.vnpay.callback');
        $vnp_TmnCode = "1VYBIYQP"; //Mã website tại VNPAY 
        $vnp_HashSecret = "NOH6MBGNLQL9O9OMMFMZ2AX8NIEP50W1"; //Chuỗi bí mật

        // Lấy tổng tiền từ session
        $total = 0;
        if (Session::has('discounts')) {
            $total = (float) str_replace(['.',','], '', Session::get('discounts')['total']) + 20000;
        } else {
            $total = (float) str_replace(',', '', Cart::instance('cart')->subtotal()) + 20000;
        }

        $vnp_TxnRef = $code_cart; //Mã đơn hàng
        $vnp_OrderInfo = 'Thanh toán đơn hàng';
        $vnp_OrderType = 'billpayment';
        $vnp_Amount = $total * 100;
        $vnp_Locale = 'vn';
        $vnp_IpAddr = request()->ip();
        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
        );

        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }
        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret); //  
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }

        // Chuyển hướng đến trang thanh toán VNPay
        return redirect($vnp_Url);
    }

    public function vnpay_callback(Request $request)
    {
        // Kiểm tra trạng thái thanh toán
        if ($request->vnp_ResponseCode == '00') {
            // Thanh toán thành công
            $user_id = Auth::user()->id;

            // Lấy thông tin đơn hàng từ session
            $orderData = Session::get('order_data');

            // Tạo đơn hàng
            $order = new Order();
            $order->user_id = $user_id;
            $order->total = Session::get('checkout')['total']+20000;
            $order->name = $orderData['name'];
            $order->phone = $orderData['phone'];
            $order->address = $orderData['address'];
            $order->save();

            // Lưu các sản phẩm trong đơn hàng
            foreach (Cart::instance('cart')->content() as $item) {
                $orderItem = new OrderItem();
                $orderItem->product_id = $item->id;
                $orderItem->order_id = $order->id;
                $orderItem->price = $item->price;
                $orderItem->quantity = $item->qty;
                $orderItem->save();
            }

            // Tạo giao dịch thanh toán
            $transaction = new Transaction();
            $transaction->user_id = $user_id;
            $transaction->order_id = $order->id;
            $transaction->mode = 'vnpay';
            // $transaction->status = 'paid';
            $transaction->save();

            // Dọn dẹp giỏ hàng và session
            Cart::instance('cart')->destroy();
            Session::forget('checkout');
            Session::forget('coupon');
            Session::forget('discounts');
            Session::forget('order_data');

            // Lưu order_id vào session để hiển thị trang xác nhận
            Session::put('order_id', $order->id);

            return redirect()->route('cart.order.confirmation')->with('success', 'Payment successful!');
        } else {
            // Thanh toán thất bại
            return redirect()->route('cart.checkout')->with('error', 'Payment failed. Please try again!');
        }
    }
}