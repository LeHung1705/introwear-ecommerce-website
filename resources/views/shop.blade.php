@extends('layouts.app')

@push('styles')
  <link rel="stylesheet" href="{{ asset('assets/css/danhmucsanpham.css') }}" />
@endpush

@section('content')
<style>
    .filter-section ul li a.active {
    font-weight: bold; 
    color: black; 
    text-decoration: underline; 
}
</style>
<div class="breadcrumb-bar">
    <div class="breadcrumb-text">
        <a href="{{route('home.index')}}" class="subnav">
        <span id="breadcrumb-home" style="cursor: pointer"
            >Trang chủ</span
        >
        </a>
        /
        <span id="breadcrumb-category" style="cursor: pointer"
            >Danh mục</span>

        / 
         <a href="{{route('shop.index')}}" class="subnav">
            <span>ALL PRODUCTS</span>
          </a>
    </div>
</div>
<!-- Main container -->
<div class="container">
    <!-- Filter section -->
    <div class="filter-section">
        <h3>SẮP XẾP</h3>
        <ul>
            <li>
                <a href="{{ route('shop.index', ['sort' => 'best_selling']) }}" 
                   class="{{ request('sort') == 'best_selling' ? 'active' : '' }}">
                   Sản phẩm bán chạy
                </a>
            </li>
            <li>
                <a href="{{ route('shop.index', ['sort' => 'price_low_high']) }}" 
                   class="{{ request('sort') == 'price_low_high' ? 'active' : '' }}">
                   Giá từ thấp đến cao
                </a>
            </li>
            <li>
                <a href="{{ route('shop.index', ['sort' => 'price_high_low']) }}" 
                   class="{{ request('sort') == 'price_high_low' ? 'active' : '' }}">
                   Giá từ cao đến thấp
                </a>
            </li>
            <li>
                <a href="{{ route('shop.index', ['sort' => 'newest']) }}" 
                   class="{{ request('sort') == 'newest' ? 'active' : '' }}">
                   Sản phẩm mới nhất
                </a>
            </li>
        </ul>
    </div>
    <!-- Product list -->
    <div class="product-list">
        @foreach ($products as $product)
        <!-- Product 1 -->
        <div class="product-item">
            <div class="image-container">
                <img
                    class="default-img"
                    src="{{ asset('uploads/products')}}/{{$product->image}}"
                />
               
                <a  class="quick-view" style="text-decoration: none;" href="{{route('shop.product.details',['id'=>$product->id])}}">Quick View</a>
               
                @if(Cart::instance('cart')->content()->where('id', $product->id)->count() > 0)
                <a href="{{ route('cart.index') }}" class="add-to-cart">Go to cart</a>
                @else
                <form name="addtocart-form" method="post" action="{{route('cart.add')}}">
                    @csrf
                    <input type="hidden" name="id" value="{{$product->id}}">
                    <input type="hidden" name="quantity" value="1">
                    <input type="hidden" name="name" value="{{$product->product_name}}">
                    <input type="hidden" name="price" value="{{$product->price_sale == '' ? $product->price : $product->price_sale}}">
                    <input type="hidden" name="size" value="{{ $product->size }}">
                    <input type="hidden" name="color" value="{{ $product->color }}">
                    <button type="submit" class="add-to-cart">Add to cart</button>
                </form>
                @endif
            </div>
            <h4>{{$product->product_name}}</h4>
            <div class="price">{{number_format($product->price_sale,0,',',',')}}₫ <del>{{number_format($product->price,0,',',',')}}₫</del></div>
        </div>
        @endforeach
    </div>
</div>
<!-- Pagination -->
@if ($products->hasPages())
    <div class="pagination">
        {{-- Page Links --}}
        @foreach ($products->getUrlRange(1, $products->lastPage()) as $page => $url)
            @if ($page == $products->currentPage())
                <a href="{{ $url }}" class="active">{{ $page }}</a>
            @else
                <a href="{{ $url }}">{{ $page }}</a>
            @endif
        @endforeach

        {{-- Next Arrow --}}
        @if ($products->hasMorePages())
            <a href="{{ $products->nextPageUrl() }}" class="arrow">→</a>
        @else
            <span class="arrow disabled">→</span>
        @endif
    </div>
@endif

@endsection