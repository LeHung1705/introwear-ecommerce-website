<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\FeedbackVulnerableController;
use App\Http\Controllers\FeedbackSecureController;
use App\Models\Feedback;
use App\Http\Middleware\AuthAdmin;
use App\Http\Controllers\OrderController; // Import controller-K
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\OrderItemController;// Import controller-K
use App\Http\Controllers\ShopController;
use App\Http\Controllers\Auth\GoogleController;

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home.index');
//Login
Route::middleware(['auth'])->group(function(){
    Route::get('/account-dashboard',[UserController::class, 'index'])->name('user.index');
    Route::put('/account-dashboard', [UserController::class, 'edit_details'])->name('user.update'); // Xử lý cập nhật thông tin

});

Route::middleware(['auth', AuthAdmin::class])->group(function() {
    Route::get('/admin', [AdminController::class,'index'])->name('admin.index');

    //Admin quan ly don hang
    Route::get('/admin/order',[OrderController::class,'orders'])->name('admin.orders');
    Route::get('/admin/order/{order_id}/details' ,[OrderItemController::class,'order_details'])->name('admin.order.details');
    Route::put('/admin/order/update-status',[OrderItemController::class,'update_order_status'])->name('admin.order.status.update');
    Route::get('/admin/orders/export', [OrderController::class, 'export'])->name('admin.orders.export');
    //Admin dashboard
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    //Admin quan ly coupon
    Route::get('/admin/coupon',[AdminController::class,'coupons'])->name('admin.coupon');
    Route::get('/admin/addcoupon',[AdminController::class,'add_coupon'])->name('admin.addcoupon');
    Route::post('/admin/coupon_store',[AdminController::class,'coupon_store'])->name('admin.coupon.store');

    //Admin quan ly san pham
    Route::get('/admin',[AdminController::class,'index'])->name('admin.index');
    Route::get('/admin/manage-product',[AdminController::class,'products'])->name('admin.products');
    Route::get('/admin/addproduct',[AdminController::class,'product_add'])->name('admin.product-add');
    Route::post('/admin/store',[AdminController::class,'product_store'])->name('admin.store');
    Route::get('/admin/{id}/update',[AdminController::class,'update_product'])->name('admin.update');
    Route::put('/admin/edit',[AdminController::class,'edit_product'])->name('admin.edit');
    Route::delete('/admin/{id}/delete',[AdminController::class,'delete_product'])->name('admin.delete');

});

// Register
Route::post('/register', [RegisterController::class, 'register'])->name('register');

//Xử lý giỏ hàng
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add_to_cart'])->name('cart.add');
Route::get('/cart/remove/{rowId}', [CartController::class, 'remove'])->name('cart.remove');
Route::put('/cart/increase-quantity/{rowId}', [CartController::class, 'increase_cart_quantity'])->name('cart.qty.increase');
Route::put('/cart/decrease-quantity/{rowId}', [CartController::class, 'decrease_cart_quantity'])->name('cart.qty.decrease');
Route::get('/cart/coupon/remove', [CartController::class, 'remove_coupon'])->name('cart.coupon.remove');

//Xử lý đơn hàng
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::get('/order-confirmation', [CartController::class, 'order_confirmation'])->name('cart.order.confirmation');


//Route thanh toán
Route::get('/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
Route::post('/apply-coupon',[CartController::class,'apply_coupon_code'])->name('cart.coupon.apply');
Route::post('/place-an-order', [CartController::class, 'place_an_order'])->name('cart.place.an.order');
Route::post('/vnpay-payment', [CartController::class, 'vnpay_payment'])->name('cart.vnpay.payment');
Route::get('/vnpay-callback', [CartController::class, 'vnpay_callback'])->name('cart.vnpay.callback');


//Xử lý shop
Route::get('/shop', [App\Http\Controllers\ShopController::class, 'index'])->name('shop.index');
Route::get('/about-us', [App\Http\Controllers\HomeController::class, 'aboutus'])->name('aboutus.index');
Route::get('/product/{id}', [ShopController::class, 'details'])->name('shop.product.details');
Auth::routes();
Route::get('/shop/{id}', [ShopController::class,'product_details'])->name('shop.product.details');
// Route::get('/feedback', [FeedbackController::class, 'index'])->name('feedback.form');
// Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');

// Feedback page
Route::get('/feedback', [FeedbackController::class, 'index'])->name('feedback.form');
// Test bản lỗi
Route::post('/feedback-vuln', [FeedbackVulnerableController::class, 'store'])->name('feedback.vuln.store');
// Test bản vá
Route::post('/feedback-secure', [FeedbackSecureController::class, 'store'])->name('feedback.secure.store');

//Tìm kiếm sản phẩm
Route::get('/search', [HomeController::class, 'search'])->name('home.search');

//Xử lý đơn hàng
Route::get('/order',[OrderController::class,'orders'])->name('orders');

//Google
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('redirect.google');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);
