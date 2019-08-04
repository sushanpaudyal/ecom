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

// Products Details Page
Route::get('/product/{id}', 'ProductsController@product')->name('product');

Route::post('/product/get-product-price', 'ProductsController@getProductPrice');


Route::match(['get', 'post'], '/admin', 'AdminController@login');

Route::group(['middleware' => ['auth']], function (){
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

    // Product Attributes
    Route::match(['get', 'post'], '/admin/add-attributes/{id}', 'ProductsController@addAttributes')->name('addAttributes');
    Route::get('/admin/delete-attribute/{id}', 'ProductsController@deleteAttribute')->name('deleteAttribute');
    Route::match(['get', 'post'], '/admin/add-images/{id}', 'ProductsController@addImages')->name('addImages');

});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/logout', 'AdminController@logout')->name('admin.logout');
