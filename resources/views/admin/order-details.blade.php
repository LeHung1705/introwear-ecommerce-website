@extends('layouts.admin')
@push('styles')
 <link
    rel="stylesheet"
    href="{{asset('css/order-details.css')}}"/>
@endpush
@section('content')
  <div class="order-details-wrapper">
        <div class="main-content">
            <div class="main-content-inner">
                <div class="main-content-wrap">
                    <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                        <h3>Order Details</h3>
                        <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10" role="navigation">
                            <li>
                                <a href="{{route('admin.dashboard')}}">
                                    <div class="text-tiny">Dashboard</div>
                                </a>
                            </li>
                            <li>
                                <i class="fas fa-chevron-right"></i>
                            </li>
                            <li>
                                 <a href="{{route('admin.order.details', ['order_id' => $order->id])}}">
                                <div class="text-tiny">Order Items</div>
                                </a>
                            </li>
                        </ul>
                    </div>
<div class="order-items-box">
                    <div class="wg-box">
                        <div class="flex items-center justify-between gap10 flex-wrap">
                            <div class="wg-filter flex-grow">
                                <h5>Ordered Items</h5>
                            </div>
                            <a class="tf-button style-1 w208" href="{{route('admin.orders')}}">Back</a>
                        </div>
                        <div class="table-responsive">
                        @if (session()->has('status'))
    <p class="alert alert-success">
        {{ session()->get('status') }}
    </p>
@endif
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th class="text-center">Price</th>
                                        <th class="text-center">Quantity</th>
                                        <th class="text-center">Action</th> 
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($orderItems as $item)
                                    <tr>
                                        <td class="pname">
                                            <div class="image">
                                                <img src="{{asset('uploads/products')}}/{{$item->product->image}}" alt="{{$item->product->product_name}}" class="image">
                                            </div>
                                            <div class="name">
                                                <p>{{$item->product->product_name}}</p>
                                            </div>
                                        </td>
                                        <td class="text-center">{{$item->price}}</td>
                                        <td class="text-center">{{$item->quantity}}</td>
                                    <td class="text-center">  
    <a href="{{ route('shop.product.details', ['id' => $item->product->id]) }}" title="View Product" class="list-icon-function view-icon">
        <div class="item eye">
            <i class="fas fa-eye"></i>
        </div>
    </a>
</td>
                                    </tr>
                            @endforeach
                             </tbody>
                            </table>
                        </div>
                    </div>
                      <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                {{$orderItems->links('pagination::Bootstrap-5')}}
                                    </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="update-order-status-container">
            <div class="order-status-select">
                <label for="orderStatus">Update Order Status:</label>
            <form action="{{route('admin.order.status.update')}}" method="POST">
                   @csrf
                   @method('PUT')
                    <input type="hidden" name="order_id" value="{{ $order->id }}" />
                <div class="select">
                <select id="orderStatus" name ="order_status" >
                    <option value ="ordered" {{ $order->status == 'ordered' ? 'selected' : '' }}>Ordered</option>
                    <option value ="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                    <option value ="canceled" {{ $order->status == 'canceled' ? 'selected' : '' }}>Canceled</option>
                </select>
                </div>
             <button type ="submit" class="update-status-button">Update Status</button>
                </form>
            </div>
        
        </div>
    </div>
@endsection