<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Home Page
Route::get('/', 'IndexController@index')->name('indexpage');

// Category Listing Page
Route::get('/products/{url}', 'ProductsController@products')->name('products');

//Products Filter Page
Route::match(['get', 'post'], '/products-filter','ProductsController@filter')->name('filter');


// Products Details Page
Route::get('/product/{id}', 'ProductsController@product')->name('product');

Route::post('/product/get-product-price', 'ProductsController@getProductPrice');

Route::match(['get', 'post'],'/add-cart', 'ProductsController@addToCart')->name('addToCart');
Route::match(['get', 'post'], '/cart', 'ProductsController@cart')->name('viewCart');
Route::get('/cart/delete-product/{id}', 'ProductsController@deleteCartProduct')->name('cart.delete');
Route::get('/cart/update-quantity/{id}/{quantity}', 'ProductsController@updateCartQuantity')->name('cartupdate.quantity');

Route::post('/cart/apply-coupon', 'ProductsController@applyCoupon')->name('apply.coupon');

Route::get('/login-register', 'UsersController@userLoginregister')->name('login-register');
Route::post('/user-register', 'UsersController@register')->name('loginregister');

//remote email check
Route::match(['get', 'post'], '/check-email', 'UsersController@checkEmail');

Route::post('/user-login', 'UsersController@login');

Route::get('/user-logout', 'UsersController@logout');

Route::get('/confirm/{code}', 'UsersController@confirmAccount');

Route::post('/search-products', 'ProductsController@searchProducts')->name('search-products');

Route::match(['get', 'post'], '/forget-password' ,'UsersController@forgetPassword')->name('forgetPassword');



Route::group(['middleware' => ['frontlogin']], function(){
    Route::match(['get', 'post'], '/account', 'UsersController@account');

    Route::post('check-user-pwd', 'UsersController@chkUserPassword');

    Route::post('/update-user-pwd','UsersController@updatePassword');

    Route::match(['get', 'post'], '/checkout', 'ProductsController@checkout')->name('checkout');

    Route::match(['get','post'],'/order-review','ProductsController@orderReview')->name('orderreview');

    Route::match(['get','post'],'/place-order','ProductsController@placeOrder');

    Route::get('/thanks','ProductsController@thanks')->name('thanks');

    Route::get('/orders','ProductsController@userOrders')->name('orders');

    Route::get('/orders/{id}','ProductsController@userOrderDetails');




});




Route::match(['get', 'post'], '/admin', 'AdminController@login');

Route::group(['middleware' => ['adminlogin']], function (){
    Route::get('/admin/dashboard', 'AdminController@dashboard')->name('admin.dashboard');
    Route::get('/admin/settings', 'AdminController@settings')->name('admin.settings');
    Route::get('/admin/check-pwd', 'AdminController@chkPassword')->name('chkPassword');
    Route::match(['get', 'post'], '/admin/update-pwd', 'AdminController@updatePassword');

    // Category Routes
    Route::match(['get', 'post'], '/admin/add-category', 'CategoryController@addCategory')->name('addCategory');
    Route::match(['get', 'post'], '/admin/edit-category/{id}', 'CategoryController@editCategory')->name('editCategory');
    Route::get('/admin/view-categories', 'CategoryController@viewCategories')->name('viewCategories');
    Route::match(['get', 'post'], '/admin/delete-category/{id}', 'CategoryController@deleteCategory')->name('deleteCategory');

    // Product Routes
    Route::match(['get', 'post'], '/admin/add-product', 'ProductsController@addProduct')->name('addProduct');
    Route::match(['get', 'post'], '/admin/edit-product/{id}', 'ProductsController@editProduct')->name('editProduct');
    Route::get('/admin/view-products', 'ProductsController@viewProducts')->name('viewProducts');
    Route::get('/admin/delete-product-image/{id}', 'ProductsController@deleteProductImage')->name('deleteProductImage');
    Route::get('/admin/delete-product/{id}', 'ProductsController@deleteProduct')->name('deleteProduct');
    Route::get('/admin/delete-product-video/{id}', 'ProductsController@deleteProductVideo')->name('deleteProductVideo');

    // Product Attributes
    Route::match(['get', 'post'], '/admin/add-attributes/{id}', 'ProductsController@addAttributes')->name('addAttributes');
    Route::get('/admin/delete-attribute/{id}', 'ProductsController@deleteAttribute')->name('deleteAttribute');
    Route::match(['get', 'post'], '/admin/add-images/{id}', 'ProductsController@addImages')->name('addImages');
    Route::get('/admin/delete-alt-image/{id}', 'ProductsController@deleteAltImage');
    Route::match(['get', 'post'], '/admin/edit-attribute/{id}', 'ProductsController@editAttributes');

    // Coupons
    Route::match(['get', 'post'], '/admin/add-coupon', 'CouponsController@addCoupon')->name('addCoupon');
    Route::get('/admin/view-coupons', 'CouponsController@viewCoupons')->name('view.coupon');
    Route::match(['get', 'post'], '/admin/edit-coupon/{id}', 'CouponsController@editCoupon')->name('edit.coupon');
    Route::get('/admin/delete-coupon/{id}', 'CouponsController@deleteCoupon')->name('delete.coupon');

    //    Banner Routes
    Route::match(['get', 'post'] , '/admin/add-banner', 'BannersController@addBanner')->name('add.banner');
    Route::get('/admin/view-banners', 'BannersController@viewBanner')->name('view.banner');
    Route::match(['get', 'post'], '/admin/edit-banner/{id}', 'BannersController@editBanner')->name('edit.banner');
    Route::get('/admin/delete-banner/{id}', 'BannersController@deleteBanner')->name('delete.banner');

    // Orders
    Route::get('/admin/view-orders', 'ProductsController@viewOrders')->name('view.order');
    Route::get('/admn/view-order/{id}', 'ProductsController@viewOrderDetails')->name('viewOrderDetails');
    Route::post('/admin/update-order-status', 'ProductsController@updateOrderStatus')->name('updateOrderStatus');

    Route::get('/admin/view-order-invoice/{id}', 'ProductsController@viewOrderInvoice')->name('viewOrderInvoice');

    // Admin User Routes
    Route::get('/admin/view-user', 'UsersController@viewUsers')->name('viewUsers');

    // CMS Pages
    Route::match(['get', 'post'], '/admin/add-cms-page', 'CmsController@addCmsPage')->name('add-cms-page');
    Route::get('/admin/view-cms-pages', 'CmsController@viewCmsPages')->name('viewCmsPages');
    Route::match(['get', 'post'], '/admin/edit-cms-page/{id}', 'CmsController@editCmsPage')->name('edit-cms-page');
    Route::get('/admin/delete-cms-page/{id}', 'CmsController@deleteCmsPage')->name('deleteCmsPage');

    // Currency
    Route::match(['get', 'post'], '/admin/add-currency', 'CurrencyController@addCurrency')->name('add-currency');
    Route::get('/admin/view-currencies', 'CurrencyController@viewCurrency')->name('viewCurrency');
    Route::match(['get', 'post'], '/admin/edit-currency/{id}', 'CurrencyController@editCurrency')->name('edit-currency');

    // Shipping Charges
    Route::get('/admin/view-shipping', 'ShippingController@viewShipping')->name('viewShipping');
    Route::match(['get', 'post'], '/admin/edit-shipping/{id}', 'ShippingController@editShipping')->name('editShipping');

    // Admin / Sub Admin Routes
    Route::match(['get', 'post'], '/admin/view-admins', 'AdminController@viewAdmins')->name('viewAdmins');
    Route::match(['get', 'post'], '/admin/add-admin', 'AdminController@addAdmin')->name('addAdmin');

});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/logout', 'AdminController@logout')->name('admin.logout');

Route::match(['get', 'post'], '/page/contact_us', 'CmsController@contact')->name('contact');

// Route for CMS Pages
Route::match(['get', 'post'], '/page/{url}', 'CmsController@cmsPage')->name('cmsPage');

Route::post('/check-pincode', 'ProductsController@checkPinCode');

