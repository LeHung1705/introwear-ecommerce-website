<?php

namespace App\Http\Controllers;



use App\Models\Product;
use App\Models\Order;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;


class AdminController extends Controller
{
   
        
    public function index()
    {
        $orderedCount = Order::where('status','ordered')->count();
        $deliveredCount = Order::where('status','delivered')->count();
        $canceledCount = Order::where('status','canceled')->count();
        $orderedTotal = Order::where('status', 'ordered')->sum('total');
        $deliveredTotal = Order::where('status', 'delivered')->sum('total');
        $canceledTotal = Order::where('status', 'canceled')->sum('total');
        $totalAmount =  $orderedTotal + $deliveredTotal + $canceledTotal;
        $totalAmount = number_format($totalAmount, 2, '.', '');
        return view('admin/dashboard', compact('canceledTotal','deliveredTotal','orderedTotal','orderedCount', 'deliveredCount', 'canceledCount','totalAmount'));
    }
   

   
    public function products()
    {
        $products=Product::orderBy('id', 'DESC')->paginate(5);;
        return view('admin.manage-product',compact('products'));    }
    public function product_add()
    { $products = Product::all();
        return view('admin.product-add', compact('products'));
    }
    public function product_store(Request $request)
{
    $request->validate([
        'product_name'=>'required',
        'category_id'=>'required',
        'color'=>'required',
        'size'=>'required',
        'price'=>'required',
        'price_sale'=>'required',
        'description'=>'required',
        'stock_quantity'=>'required',
        'status_product'=>'nullable',
       'supplier_id'=>'required',
       'image'=>'required|mimes:png,jpg,jpeg|max:2048'
    ]);

    $product = new Product();
    $product->product_name = $request->product_name;
    $product->category_id = $request->category_id;
    $product->color = $request->color;
    $product->size = $request->size;
    $product->price = $request->price;
    $product->price_sale = $request->price_sale;
    $product->description = $request->description;
    $product->stock_quantity = $request->stock_quantity;
    $product->status_product = $request->status_product ?? 'Còn hàng';
    $product->supplier_id = $request->supplier_id;

  /* if ($request->hasFile('image')) {
        $image = $request->file('image');
        $imageName = time() . '.' . $image->extension();
        $this->GenerateProductThumbnailImage($image, $imageName);
        $product->image = $imageName;
    }*/

if ($request->hasFile('image')) {
    $image = $request->file('image');
    $imageName = time() . '_' . $image->getClientOriginalName();
    $image->move(public_path('uploads/products'), $imageName); // Lưu trực tiếp vào public/uploads
    $product->image = $imageName;
}
    $product->save();
    return redirect()->route('admin.products')->with('status','Thêm sản phẩm thành công!');
 
}

    /*public function GenerateProductThumbnailImage($image, $imageName)
{
    $destinationPath = public_path('uploads/products');

    if (!file_exists($destinationPath)) {
        mkdir($destinationPath, 0775, true);
    }

    $img = Image::read($image->path());
    $img->cover(540, 689, 'top');
    $img->resize(540, 689, function ($constraint) {
        $constraint->aspectRatio();
    })->save($destinationPath . '/' . $imageName);
}*/

// Cập nhật sản phẩm
public function update_product($id)
{
    $product = Product::find($id);
    return view('admin.update-product',compact('product'));
}


public function edit_product(Request $request)
{ $request->validate([
        'product_name'=>'required',
        'category_id'=>'nullable',
        'color'=>'required',
        'size'=>'required',
        'price'=>'required',
        'price_sale'=>'required',
        'description'=>'required',
        'stock_quantity'=>'required',
        'status_product'=>'nullable',

       'image'=>'mimes:png,jpg,jpeg|max:2048'
    ]);
    $product = Product::find($request->id);
    $product->product_name = $request->product_name;
    $product->category_id = $request->category_id;
    $product->color = $request->color;
    $product->size = $request->size;
    $product->price = $request->price;
    $product->price_sale = $request->price_sale;
    $product->description = $request->description;
    $product->stock_quantity = $request->stock_quantity;
    $product->status_product = $request->status_product;
    $product->supplier_id = $request->supplier_id;

    if ($request->hasFile('image')) {
    $image = $request->file('image');
    $imageName = time() . '_' . $image->getClientOriginalName();
    $image->move(public_path('uploads/products'), $imageName); // Lưu trực tiếp vào public/uploads
     if ($product->image && file_exists(public_path('uploads/products' . $product->image))) {
            unlink(public_path('uploads/products' . $product->image));
        }
    $product->image = $imageName;
}
    $product->save();
    return redirect()->route('admin.products')->with('status','Cập nhật sản phẩm thành công!');
 
}

//Xóa sản phẩm 
public function delete_product($id)
{
  $product=Product::find($id);
  if ($product->image && file_exists(public_path('uploads/' . $product->image))) {
            unlink(public_path('uploads/' . $product->image));
        }
    $product->delete();
    return redirect()->route('admin.products')->with('status','Xóa sản phẩm thành công!');
 

}

public function coupons()
{
    $coupons = Coupon::orderBy('end_date','desc')->get();
    return view('admin.coupon',compact('coupons'));
}
public function add_coupon()
{
        $coupons = Coupon::orderBy('end_date','desc')->get();
        return view('admin.coupon-add', compact('coupons'));
}

public function coupon_store(Request $request)
{
    $request->validate([
        'coupon_code'=>'required',
        'discount_percentage'=>'required',
        'description'=>'required',
        'start_date'=> 'required',
        'end_date'=> 'required'
    ]);

    $coupon = new Coupon();
    $coupon->coupon_code = $request->coupon_code;
     $coupon->discount_percentage = $request->discount_percentage;
      $coupon->description = $request->description;
   $coupon->start_date = $request->start_date;
    $coupon->end_date = $request->end_date;
  $coupon->save();
    return redirect()->route('admin.coupon')->with('status','Thêm mã giảm giá thành công!');
 
}

}
