<?php

use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Website\CartController;
use App\Http\Controllers\Website\CustomerController;
use App\Http\Controllers\Website\ProductCategoryController;
use App\Http\Controllers\Website\UserFavoritesController;
use Illuminate\Support\Facades\Route;

//Website Pages
Route::group(['as' => 'customer.',], function () {

    //We Use this for redirecting guards routes
    Route::controller(HomeController::class)->group(function () {
        Route::get('/home',  'index')->name('home');
    });

    Route::controller(CustomerController::class)->group(function () {

        //Main Pages
        Route::get('/', 'index');
        Route::post('/create-store', 'createStore')->name('create-store');
        Route::get('/landing-page', 'loandingHome')->name('loanding-home');
        Route::get('/create-store-page/{package_id?}', 'createStorePage')->name('create-store-page');

        Route::get('/home-page', 'index')->name('home-page');
        Route::get('/stores/{store_type_slug}', 'storeType')->name('store-type-category');
        Route::get('/stores/{store_type_slug}/details/{store_name_slug}', 'storeDetails')->name('store-details');
        Route::get('/stores/{store_type_slug}/details/{store_name_slug}/{product_id}', 'storeProductDetails')->name('store-product-details');

        Route::get('store-your-designer-details', 'storeYourDesignerDetails')->name('store.your.designer.details');
        Route::get('size-details-1', 'sizeDe1')->name('size.details.1');
        Route::get('size-details-2', 'sizeDe2')->name('size.details.2');
        Route::get('size-details-3', 'sizeDe3')->name('size.details.3');
        Route::get('size-details-4', 'sizeDe4')->name('size.details.4');
        Route::get('size-details-5', 'sizeDe5')->name('size.details.5');
        Route::get('size-details-6', 'sizeDe6')->name('size.details.6');
        Route::get('size-details-7', 'sizeDe7')->name('size.details.7');
    });


    Route::controller(CartController::class)->group(function () {
        Route::get('/cart',  'index')->name('cart');
        Route::post('/add-to-cart',  'addToCart')->name('cart.store');
        Route::post('/update-cart',  'updateCart')->name('cart.update');
        Route::get('/remove/{rowId}',  'removeCart')->name('cart.remove');
        Route::post('/clear', 'clearAllCart')->name('cart.clear');
        Route::get('/cart-list', 'cartList')->name('cart.list');
        // Route::get('/', [ProductController::class, 'productList'])->name('products.list');
    });


    Route::controller(CheckoutController::class)->group(function () {
        Route::get('/checkout', 'index')->name('checkout.index');
    });


    Route::controller(ProductCategoryController::class)->group(function () {
        Route::get('/category/{category_id}', [ProductCategoryController::class, 'category'])->name('category');
        Route::get('/single-product/{product_id}', [ProductCategoryController::class, 'singleProduct'])->name('single-product');
        Route::get('/category-slider-price/{from}/{to}/{category_id}', [ProductCategoryController::class, 'updateProducts'])->name('category-slider-price');

        Route::post('/add-comment/{product_id}', [ProductCategoryController::class, 'addComment'])->name('add-comment');
    });


    Route::controller(UserFavoritesController::class)->group(function () {
        Route::post('/add-favorite',  'addFavorite')->name('add.favorite');
    });
});


#Socail Media Login and Register ^_^
Route::get('auth/{provider}/redirect', [SocialiteLoginController::class, 'redirect'])->name('auth.socialite.redirect');
Route::get('auth/{provider}/callback', [SocialiteLoginController::class, 'callback'])->name('auth.socialite.callback');
