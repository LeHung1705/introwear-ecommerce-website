@extends('layouts.app')


@push('styles')
  <link rel="stylesheet" href="{{ asset('assets/css/giohang.css') }}" />
@endpush


@section('content')
<style>
.text-success
{
  color: green !important;
}
</style>
  <!-- Breadcrumb -->
  <div class="breadcrumb-bar">
    <div class="breadcrumb-text">
      <span id="breadcrumb-home" style="cursor: pointer;">Trang chủ</span> / <span>Giỏ hàng</span>
    </div>
  </div>
    <!-- Giỏ hàng -->
    <div class="cart-container">
        @if($items->count() > 0)
        <div class="cart-table__wrapper">
          <h1 style="padding-bottom:20px;">Giỏ hàng</h1>
          <table class="cart-table">
          <thead>
            <tr>
              <th>Product</th>
              <th></th>
              <th>Price</th>
              <th>Quantity</th>
              <th>Subtotal</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            @foreach($items as $item)
            <tr>
              <td>
                <div class="shopping-cart__product-item">
                  <img loading="lazy" src="{{asset('uploads/products')}}/{{$item->model->image}}" width="120" height="120" alt="" />
                </div>
              </td>
              <td>
                <div class="shopping-cart__product-item__detail">
                  <h4>{{$item->name}}</h4>
                  <ul class="shopping-cart__product-item__options">
                    <li>Color: {{ $item->options->color}}</li>
                    <li>Size: {{ $item->options->size }}</li>
                  </ul>
                </div>
              </td>
              <td>
                <span class="shopping-cart__product-price">{{number_format($item->price,0,',',',')}}VND</span>
              </td>
              <td>
                <div class="qty-control position-relative">
                  <input type="number" name="quantity" value="{{$item->qty}}" min="1" class="qty-control__number text-center">
                  <form method="POST" action="{{route('cart.qty.decrease', ['rowId'=>$item->rowId])}}">
                    @csrf
                    @method('PUT')
                    <div class="qty-control__reduce">-</div>
                  </form>
                  <form method="POST" action="{{route('cart.qty.increase', ['rowId'=>$item->rowId])}}">
                    @csrf
                    @method('PUT')
                    <div class="qty-control__increase">+</div>
                  </form>
                </div>
              </td>
              <td>
                <span class="shopping-cart__subtotal">{{number_format($item->subTotal(),0,',',',')}}VND</span>
              </td>
              <td>
                <a href="{{ route('cart.remove', $item->rowId) }}" class="remove-cart">
                    <svg width="10" height="10" viewBox="0 0 10 10" fill="#767676" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0.259435 8.85506L9.11449 0L10 0.885506L1.14494 9.74056L0.259435 8.85506Z" />
                        <path d="M0.885506 0.0889838L9.74057 8.94404L8.85506 9.82955L0 0.97449L0.885506 0.0889838Z" />
                    </svg>
                </a>
            </td>
            </tr>
            @endforeach
          </tbody>
          </table>
        </div>
       
      <div class="shopping-cart__totals-wrapper">
        <div class="sticky-content">
          <div class="shopping-cart__totals">
            <h3>Cart Totals</h3>
            @if(Session::has('discounts'))
  <table class="cart-totals">
    <tbody>
      <tr>
        <th>Subtotal</th>
        <td>{{ number_format((float) str_replace(',', '', Cart::instance('cart')->subtotal()), 0, ',', ',') }} VND</td>
      </tr>
      <tr>
        <th>Discount <span style="color: red">{{ Session::get('coupon')['coupon_code'] }}</span></th>
        <td>{{ number_format((float) Session::get('discounts')['discount'], 0,',', ',') }} VND</td>
      </tr>
      <tr>
        <th>Subtotal After Discount</th>
        <td>{{ number_format((float) Session::get('discounts')['subtotal'], 0,',', ',') }} VND</td>
      </tr>
      <tr>
        <th>Shipping</th>
        <td>{{ number_format(20000, 0, ',', ',') }} VND</td>
      </tr>
      <tr>
        <th>Total</th>
<td>
  {{ number_format((float) str_replace(',', '', Session::get('discounts')['subtotal']) + 20000, 0, ',', ',') }} VND
</td>      </tr>
    </tbody>
  </table>
            @else
              <table class="cart-totals">
                <tbody>
                  <tr>
                    <th>Subtotal</th>
                    <td>{{ number_format((float) str_replace(',', '', Cart::instance('cart')->subtotal()), 0, ',', ',') }}VND</td>
                  </tr>
                  <tr>
                    <th>Shipping</th>
                    <td>{{ number_format(20000, 0, ',', ',') }}VND</td>
                  </tr>
                  <tr>
                    <th>Total</th>
                    <td>{{ number_format((float) str_replace('.', '', Cart::instance('cart')->subtotal()) + 20000, 0, ',', ',') }}VND</td>
                  </tr>
                </tbody>
              </table>
            @endif


          </div>
          <div>
          @if(Session::has('success'))
          <p class="text-success">{{Session::get('success')}}</p>
          @elseif(Session::has('error'))
         <p class="text-error">{{Session::get('error')}}</p>
         @endif


          </div>
          <div class="mobile_fixed-btn_wrapper">
            <div class="button-wrapper container">
              <a href="{{route('cart.checkout')}}" class="btn btn-primary btn-checkout">PROCEED TO CHECKOUT</a>
            </div>
          </div>
      </div>
    </div>
      @else
        <div class="empty-cart">
            <h2>Giỏ hàng của bạn đang trống</h2>
            <br>
            <p>Hãy thêm sản phẩm vào giỏ hàng để bắt đầu mua sắm!</p>
            <br>
            <a href="{{ route('shop.index') }}" style="display: inline-block;margin-bottom: 100px; padding: 10px 20px; background-color: black; color: white; text-decoration: none; text-align: center;">TIẾP TỤC MUA SẮM</a>
        </div>
       @endif
    </div>
@endsection

@push('scripts')
  <script>
    $(function(){
      $(".qty-control__increase").on('click', function(){
        $(this).closest('form').submit();
      });

      $(".qty-control__reduce").on('click', function(){
        $(this).closest('form').submit();
      });
    })
  </script>
@endpush