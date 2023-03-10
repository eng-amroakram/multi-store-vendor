<?php

use App\Http\Controllers\AdminPanel\AbandonedCartsController;
use App\Http\Controllers\AdminPanel\AdminsController;
use App\Http\Controllers\AdminPanel\AdminsStoreController;
use App\Http\Controllers\AdminPanel\AdvertismentsController;
use App\Http\Controllers\AdminPanel\AffiliateMarketingController;
use App\Http\Controllers\AdminPanel\CitiesController;
use App\Http\Controllers\AdminPanel\CoponsController;
use App\Http\Controllers\AdminPanel\CountriesController;
use App\Http\Controllers\AdminPanel\CurrenciesController;
use App\Http\Controllers\AdminPanel\CustomersController;
use App\Http\Controllers\AdminPanel\HomeController as AdminPanelHomeController;
use App\Http\Controllers\AdminPanel\OrdersController;
use App\Http\Controllers\AdminPanel\PackagesController;
use App\Http\Controllers\AdminPanel\PaymentTypesController;
use App\Http\Controllers\AdminPanel\ProductController;
use App\Http\Controllers\AdminPanel\ProductsCategoriesController;
use App\Http\Controllers\AdminPanel\ProductsCommentsController;
use App\Http\Controllers\AdminPanel\ProfitsController;
use App\Http\Controllers\AdminPanel\RolesController;
use App\Http\Controllers\AdminPanel\SalesController;
use App\Http\Controllers\AdminPanel\SettingsController;
use App\Http\Controllers\AdminPanel\StoresController;
use App\Http\Controllers\AdminPanel\StoreTypeController;
use App\Http\Controllers\AdminPanel\VisitorController;
use App\Http\Controllers\HomeController as ControllersHomeController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\Store\HomeController;
use App\Http\Controllers\Store\ProfileController;
use App\Http\Controllers\Store\ReportsController;
use App\Http\Controllers\Store\ReturnController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


use App\Http\Controllers\Store\AdminsController as StoreAdminsController;
use App\Http\Controllers\Store\AdvertismentsController as StoreAdvertismentsController;
use App\Http\Controllers\Store\AffiliateMarketingController as StoreAffiliateMarketingController;
use App\Http\Controllers\Store\CoponsController as StoreCoponsController;
use App\Http\Controllers\Store\CustomersController as StoreCustomersController;
use App\Http\Controllers\Store\OrdersController as StoreOrdersController;
use App\Http\Controllers\Store\ProductController as StoreProductController;
use App\Http\Controllers\Store\ProductsCommentsController as StoreProductsCommentsController;
use App\Http\Controllers\Store\RolesController as StoreRolesController;
use App\Http\Controllers\Store\SalesController as StoreSalesController;
use App\Http\Controllers\Store\SettingsController as StoreSettingsController;
use App\Http\Controllers\Store\VisitorController as StoreVisitorController;

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


Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
});


Route::get('lang/{lang}', ['as' => 'lang.switch', 'uses' => 'LanguageController@switchLang']);
Route::get('lang/{lang}', [LanguageController::class, 'switchLang'])->name('lang.switch');

Auth::routes();

Route::get('/search', [SearchController::class, 'search'])->name('search');
Route::get('/home', [ControllersHomeController::class, 'index'])->name('home');

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'admin']], function () {

    Route::get('/home', [AdminPanelHomeController::class, 'index'])->name('admin.home');

    #Roles
    Route::get('/roles', [RolesController::class, 'index'])->name('admin.roles.index')->middleware('permission:roles-show');
    Route::get('/roles/create', [RolesController::class, 'create'])->name('admin.roles.create')->middleware('permission:roles-create');
    Route::post('/roles/store', [RolesController::class, 'store'])->name('admin.roles.store')->middleware('permission:roles-create');
    Route::get('/roles/edit/{id}', [RolesController::class, 'edit'])->name('admin.roles.edit')->middleware('permission:roles-edit');
    Route::post('/roles/update/{id}', [RolesController::class, 'update'])->name('admin.roles.update')->middleware('permission:roles-edit');
    Route::get('/roles/delete/{id}', [RolesController::class, 'destroy'])->name('admin.roles.delete')->middleware('permission:roles-delete');

    #Profile Settings
    Route::get('/settings/profile', [ProfileController::class, 'profile'])->name('admin.profile');
    Route::post('/settings/profile', [ProfileController::class, 'update_profile'])->name('admin.profile.update');

    #Settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('admin.settings.index');
    Route::get('/settings/privacy', [SettingsController::class, 'privacy'])->name('admin.settings.privacy')->middleware('permission:privacy-edit');
    Route::post('/settings/privacy', [SettingsController::class, 'update_privacy'])->name('admin.settings.update-privacy')->middleware('permission:privacy-edit');
    Route::get('/settings/about', [SettingsController::class, 'about'])->name('admin.settings.about')->middleware('permission:about-us-edit');
    Route::post('/settings/about', [SettingsController::class, 'update_about'])->name('admin.settings.update-about')->middleware('permission:about-us-edit');
    Route::get('/settings/faq', [SettingsController::class, 'faq'])->name('admin.settings.faq')->middleware('permission:faq-edit');
    Route::post('/settings/faq', [SettingsController::class, 'update_faq'])->name('admin.settings.update-faq')->middleware('permission:faq-edit');
    Route::get('/settings/global', [SettingsController::class, 'global'])->name('admin.settings.global')->middleware('permission:basic-settings-edit');
    Route::post('/settings/global', [SettingsController::class, 'update_global'])->name('admin.settings.update-global')->middleware('permission:basic-settings-edit');
    Route::get('/settings/social', [SettingsController::class, 'social'])->name('admin.settings.social')->middleware('permission:social-media-settings-edit');
    Route::post('/settings/social', [SettingsController::class, 'update_social'])->name('admin.settings.update-social')->middleware('permission:social-media-settings-edit');

    #Currencies
    Route::get('/currencies', [CurrenciesController::class, 'index'])->name('admin.currencies.index')->middleware('permission:currencies-show');
    Route::get('/currencies/create', [CurrenciesController::class, 'create'])->name('admin.currencies.create')->middleware('permission:currencies-create');
    Route::post('/currencies/store', [CurrenciesController::class, 'store'])->name('admin.currencies.store')->middleware('permission:currencies-create');
    Route::get('/currencies/edit/{id}', [CurrenciesController::class, 'edit'])->name('admin.currencies.edit')->middleware('permission:currencies-edit');
    Route::post('/currencies/update/{id}', [CurrenciesController::class, 'update'])->name('admin.currencies.update')->middleware('permission:currencies-edit');
    Route::get('/currencies/delete/{id}', [CurrenciesController::class, 'destroy'])->name('admin.currencies.delete')->middleware('permission:currencies-delete');

    #Products Categories
    Route::get('/products-categories', [ProductsCategoriesController::class, 'index'])->name('admin.products-categories.index')->middleware('permission:products-categories-show');
    Route::get('/products-categories/create', [ProductsCategoriesController::class, 'create'])->name('admin.products-categories.create')->middleware('permission:products-categories-create');
    Route::post('/products-categories/store', [ProductsCategoriesController::class, 'store'])->name('admin.products-categories.store')->middleware('permission:products-categories-create');
    Route::get('/products-categories/edit/{id}', [ProductsCategoriesController::class, 'edit'])->name('admin.products-categories.edit')->middleware('permission:products-categories-edit');
    Route::post('/products-categories/update/{id}', [ProductsCategoriesController::class, 'update'])->name('admin.products-categories.update')->middleware('permission:products-categories-edit');
    Route::get('/products-categories/delete/{id}', [ProductsCategoriesController::class, 'destroy'])->name('admin.products-categories.delete')->middleware('permission:products-categories-delete');
    Route::get('/products-categories/change-status/{id}', [ProductsCategoriesController::class, 'change_status'])->name('admin.products-categories.changeStatus')->middleware('permission:products-categories-show');

    #Stores Types
    Route::get('/stores-types', [StoreTypeController::class, 'index'])->name('admin.stores-types.index')->middleware('permission:stores-types-show');
    Route::get('/stores-types/create', [StoreTypeController::class, 'create'])->name('admin.stores-types.create')->middleware('permission:stores-types-create');
    Route::post('/stores-types/store', [StoreTypeController::class, 'store'])->name('admin.stores-types.store')->middleware('permission:stores-types-create');
    Route::get('/stores-types/edit/{id}', [StoreTypeController::class, 'edit'])->name('admin.stores-types.edit')->middleware('permission:stores-types-edit');
    Route::post('/stores-types/update/{id}', [StoreTypeController::class, 'update'])->name('admin.stores-types.update')->middleware('permission:stores-types-edit');
    Route::get('/stores-types/delete/{id}', [StoreTypeController::class, 'destroy'])->name('admin.stores-types.delete')->middleware('permission:stores-types-delete');
    Route::get('/stores-types/change-status/{id}', [StoreTypeController::class, 'change_status'])->name('admin.stores-types.changeStatus')->middleware('permission:stores-types-show');

    #Packages
    Route::get('/packages', [PackagesController::class, 'index'])->name('admin.packages.index')->middleware('permission:packages-show');
    Route::get('/packages/create', [PackagesController::class, 'create'])->name('admin.packages.create')->middleware('permission:packages-create');
    Route::post('/packages/store', [PackagesController::class, 'store'])->name('admin.packages.store')->middleware('permission:packages-create');
    Route::get('/packages/edit/{id}', [PackagesController::class, 'edit'])->name('admin.packages.edit')->middleware('permission:packages-edit');
    Route::post('/packages/update/{id}', [PackagesController::class, 'update'])->name('admin.packages.update')->middleware('permission:packages-edit');
    Route::get('/packages/delete/{id}', [PackagesController::class, 'destroy'])->name('admin.packages.delete')->middleware('permission:packages-delete');
    Route::get('/packages/change-status/{id}', [PackagesController::class, 'change_status'])->name('admin.packages.changeStatus')->middleware('permission:packages-show');

    #Countries
    Route::get('/countries', [CountriesController::class, 'index'])->name('admin.countries.index')->middleware('permission:countries-show');
    Route::get('/countries/create', [CountriesController::class, 'create'])->name('admin.countries.create')->middleware('permission:countries-create');
    Route::post('/countries/store', [CountriesController::class, 'store'])->name('admin.countries.store')->middleware('permission:countries-create');
    Route::get('/countries/edit/{id}', [CountriesController::class, 'edit'])->name('admin.countries.edit')->middleware('permission:countries-edit');
    Route::post('/countries/update/{id}', [CountriesController::class, 'update'])->name('admin.countries.update')->middleware('permission:countries-edit');
    Route::get('/countries/delete/{id}', [CountriesController::class, 'destroy'])->name('admin.countries.delete')->middleware('permission:countries-delete');
    Route::get('/countries/change-status/{id}', [CountriesController::class, 'change_status'])->name('admin.countries.changeStatus')->middleware('permission:countries-show');

    #Cities
    Route::get('/cities/index', [CitiesController::class, 'index'])->name('admin.cities.index')->middleware('permission:cities-show');
    Route::get('/cities/create', [CitiesController::class, 'create'])->name('admin.cities.create')->middleware('permission:cities-create');
    Route::post('/cities/store', [CitiesController::class, 'store'])->name('admin.cities.store')->middleware('permission:cities-create');
    Route::get('/cities/edit/{id}', [CitiesController::class, 'edit'])->name('admin.cities.edit')->middleware('permission:cities-edit');
    Route::post('/cities/update/{id}', [CitiesController::class, 'update'])->name('admin.cities.update')->middleware('permission:cities-edit');
    Route::get('/cities/delete/{id}', [CitiesController::class, 'destroy'])->name('admin.cities.delete')->middleware('permission:cities-delete');
    Route::get('/cities/change-status/{id}', [CitiesController::class, 'change_status'])->name('admin.cities.changeStatus')->middleware('permission:cities-show');

    #Advertisments
    Route::get('/advertisments', [AdvertismentsController::class, 'index'])->name('admin.advertisments.index')->middleware('permission:advertisments-show');
    Route::get('/advertisments/create', [AdvertismentsController::class, 'create'])->name('admin.advertisments.create')->middleware('permission:advertisments-create');
    Route::post('/advertisments/store', [AdvertismentsController::class, 'store'])->name('admin.advertisments.store')->middleware('permission:advertisments-create');
    Route::get('/advertisments/edit/{id}', [AdvertismentsController::class, 'edit'])->name('admin.advertisments.edit')->middleware('permission:advertisments-edit');
    Route::post('/advertisments/update/{id}', [AdvertismentsController::class, 'update'])->name('admin.advertisments.update')->middleware('permission:advertisments-edit');
    Route::get('/advertisments/delete/{id}', [AdvertismentsController::class, 'destroy'])->name('admin.advertisments.delete')->middleware('permission:advertisments-delete');
    Route::get('/advertisments/change-status/{id}', [AdvertismentsController::class, 'change_status'])->name('admin.advertisments.changeStatus')->middleware('permission:advertisments-show');

    #Admins
    Route::get('/admins', [AdminsController::class, 'index'])->name('admin.admins.index')->middleware('permission:admins-show');
    Route::post('/admins/store', [AdminsController::class, 'store'])->name('admin.admins.store')->middleware('permission:admins-create');
    Route::get('/admins/edit', [AdminsController::class, 'edit'])->name('admin.admins.edit')->middleware('permission:admins-edit');
    Route::post('/admins/update/{id}', [AdminsController::class, 'update'])->name('admin.admins.update')->middleware('permission:admins-edit');
    Route::get('/admins/delete/{id}', [AdminsController::class, 'destroy'])->name('admin.admins.delete')->middleware('permission:admins-delete');
    Route::get('/admins/change-status/{id}', [AdminsController::class, 'change_status'])->name('admin.admins.changeStatus')->middleware('permission:admins-show');

    #Admins Store
    Route::get('/admins-store', [AdminsStoreController::class, 'index'])->name('admin.admins-store.index')->middleware('permission:admins-store-show');
    Route::post('/admins-store/store', [AdminsStoreController::class, 'store'])->name('admin.admins-store.store')->middleware('permission:admins-store-create');
    Route::get('/admins-store/edit', [AdminsStoreController::class, 'edit'])->name('admin.admins-store.edit')->middleware('permission:admins-store-edit');
    Route::post('/admins-store/update/{id}', [AdminsStoreController::class, 'update'])->name('admin.admins-store.update')->middleware('permission:admins-store-edit');
    Route::get('/admins-store/delete/{id}', [AdminsStoreController::class, 'destroy'])->name('admin.admins-store.delete')->middleware('permission:admins-store-delete');
    Route::get('/admins-store/change-status/{id}', [AdminsStoreController::class, 'change_status'])->name('admin.admins-store.changeStatus')->middleware('permission:admins-store-show');

    #Customers
    Route::get('/customers', [CustomersController::class, 'index'])->name('admin.customers.index')->middleware('permission:customers-show');
    Route::post('/customers/store', [CustomersController::class, 'store'])->name('admin.customers.store')->middleware('permission:customers-create');
    Route::get('/customers/edit', [CustomersController::class, 'edit'])->name('admin.customers.edit')->middleware('permission:customers-edit');
    Route::post('/customers/update/{id}', [CustomersController::class, 'update'])->name('admin.customers.update')->middleware('permission:customers-edit');
    Route::get('/customers/delete/{id}', [CustomersController::class, 'destroy'])->name('admin.customers.delete')->middleware('permission:customers-delete');
    Route::get('/customers/change-status/{id}', [CustomersController::class, 'change_status'])->name('admin.customers.changeStatus')->middleware('permission:customers-show');

    #Stores
    Route::get('/stores', [StoresController::class, 'index'])->name('admin.stores.index')->middleware('permission:stores-show');
    Route::get('/stores/create', [StoresController::class, 'create'])->name('admin.stores.create')->middleware('permission:stores-create');
    Route::post('/stores/store', [StoresController::class, 'store'])->name('admin.stores.store')->middleware('permission:stores-create');
    Route::get('/stores/edit/{id}', [StoresController::class, 'edit'])->name('admin.stores.edit')->middleware('permission:stores-edit');
    Route::post('/stores/update/{id}', [StoresController::class, 'update'])->name('admin.stores.update')->middleware('permission:stores-edit');
    Route::get('/stores/delete/{id}', [StoresController::class, 'destroy'])->name('admin.stores.delete')->middleware('permission:stores-delete');
    Route::get('/stores/change-status/{id}', [StoresController::class, 'change_status'])->name('admin.stores.changeStatus')->middleware('permission:stores-show');

    Route::post('/get-cities-by-country', [StoresController::class, 'getCity'])->name('get-cities-by-country')->withoutMiddleware(['auth', 'admin']);

    Route::get('/payment-types', [PaymentTypesController::class, 'index'])->name('admin.payment-types.index')->middleware('permission:payments-type-show');
    Route::get('/payment-types/change-status/{id}', [PaymentTypesController::class, 'change_status'])->name('admin.payment-types.changeStatus')->middleware('permission:payments-type-show');

    Route::post('/settings/currency', [SettingsController::class, 'update_currency'])->name('admin.settings.update-currency')->middleware('permission:currencies-show');

    Route::get('/settings/package', [SettingsController::class, 'package'])->name('admin.settings.package')->middleware('permission:default-package-edit');
    Route::post('/settings/package', [SettingsController::class, 'update_package'])->name('admin.settings.update-package')->middleware('permission:default-package-edit');

    Route::get('/settings/package-settings', [SettingsController::class, 'package_settings'])->name('admin.settings.package-settings')->middleware('permission:package-settings-edit');
    Route::post('/settings/package-settings', [SettingsController::class, 'update_package_settings'])->name('admin.settings.update-package-settings')->middleware('permission:package-settings-edit');

    Route::get('/sales', [SalesController::class, 'index'])->name('admin.sales.index')->middleware('permission:sales-show');
    Route::get('/best-sales', [SalesController::class, 'best_sales'])->name('admin.sales.best-sales')->middleware('permission:sales-show');
    Route::get('/lowest-selling', [SalesController::class, 'lowest_selling'])->name('admin.sales.lowest-selling')->middleware('permission:sales-show');
    Route::get('/orders', [OrdersController::class, 'index'])->name('admin.orders.index')->middleware('permission:orders-show');
    Route::get('/return', [ReturnController::class, 'index'])->name('admin.return.index')->middleware('permission:returns-show');
    Route::get('/sales/search', [SalesController::class, 'search'])->name('admin.sales.search')->middleware('permission:sales-show');
    Route::get('/best-sales/search', [SalesController::class, 'best_sales_search'])->name('admin.sales.best-sales-search')->middleware('permission:sales-show');
    Route::get('/lowest-selling/search', [SalesController::class, 'lowest_selling_search'])->name('admin.sales.lowest-selling-search')->middleware('permission:sales-show');
    Route::get('/orders/search', [OrdersController::class, 'search'])->name('admin.orders.search')->middleware('permission:orders-show');
    Route::get('/return/search', [ReturnController::class, 'search'])->name('admin.return.search')->middleware('permission:returns-show');
    Route::get('/orders/delete/{id}', [OrdersController::class, 'destroy'])->name('admin.orders.delete')->middleware('permission:orders-delete');
    Route::get('/orders/create', [OrdersController::class, 'create'])->name('admin.orders.create')->middleware('permission:orders-create');
    Route::post('/orders/store', [OrdersController::class, 'store'])->name('admin.orders.store')->middleware('permission:orders-create');
    Route::get('/orders/edit/{id}', [OrdersController::class, 'edit'])->name('admin.orders.edit')->middleware('permission:orders-edit');
    Route::post('/orders/update/{id}', [OrdersController::class, 'update'])->name('admin.orders.update')->middleware('permission:orders-edit');

    Route::get('/profits', [ProfitsController::class, 'index'])->name('admin.profits.index')->middleware('permission:profits-show');
    Route::get('/profits/search', [ProfitsController::class, 'search'])->name('admin.profits.search')->middleware('permission:profits-show');

    Route::get('/copons', [CoponsController::class, 'index'])->name('admin.copons.index')->middleware('permission:admin-copons-show');
    Route::post('/copons/store', [CoponsController::class, 'store'])->name('admin.copons.store')->middleware('permission:admin-copons-create');
    Route::get('/copons/edit', [CoponsController::class, 'edit'])->name('admin.copons.edit')->middleware('permission:admin-copons-create');
    Route::post('/copons/update/{id}', [CoponsController::class, 'update'])->name('admin.copons.update')->middleware('permission:admin-copons-edit');
    Route::get('/copons/delete/{id}', [CoponsController::class, 'destroy'])->name('admin.copons.delete')->middleware('permission:admin-copons-edit');
    Route::get('/copons/change-status/{id}', [CoponsController::class, 'change_status'])->name('admin.copons.changeStatus')->middleware('permission:admin-copons-delete');


    Route::group(['prefix' => 'product', 'middleware' => ['auth', 'admin']], function () {
        Route::get('/products', [ProductController::class, 'index'])->name('admin.products.index')->middleware('permission:admin-products-show');
        Route::get('/get_products', [ProductController::class, 'get_products'])->name('admin.products.get_products')->middleware('permission:admin-products-show');
        Route::get('/products/add_custom_made', [ProductController::class, 'add_custom_made'])->name('admin.products.add.custom_made')->middleware('permission:admin-products-create');
        Route::get('/products/edit_custom_made/{id}', [ProductController::class, 'edit_product'])->name('admin.products.edit.custom_made')->middleware('permission:admin-products-edit');
        Route::get('/products/add_ready_made', [ProductController::class, 'add_ready_made'])->name('admin.products.add.ready_made')->middleware('permission:admin-products-create');
        Route::get('/products/add_service_made', [ProductController::class, 'add_service_made'])->name('admin.products.add.service_made')->middleware('permission:admin-products-create');
        Route::get('/products/get_product_colors', [ProductController::class, 'get_product_colors'])->name('admin.products.get.get_product_colors')->middleware('permission:admin-products-show');
        Route::POST('/products/add_category', [ProductController::class, 'add_category'])->name('admin.products.add.category')->middleware('permission:admin-products-create');
        Route::POST('/products/get_product', [ProductController::class, 'product_data'])->name('admin.products.get.get_product')->middleware('permission:admin-products-show');
        Route::post('/products/add_product_color', [ProductController::class, 'add_product_color'])->name('admin.products.add.add_product_color')->middleware('permission:admin-products-create');
        Route::post('/products/store_product', [ProductController::class, 'store_product'])->name('admin.products.add.add_product')->middleware('permission:admin-products-create');
        Route::post('/products/update_product/{id}', [ProductController::class, 'update_product'])->name('admin.products.update.update_product')->middleware('permission:admin-products-edit');
        Route::post('/products/delete_product', [ProductController::class, 'delete_product'])->name('admin.products.delete.delete_product')->middleware('permission:admin-products-delete');
        Route::post('/products/active_deactive_product', [ProductController::class, 'active_deactive_product'])->name('admin.products.update.active_deactive_product')->middleware('permission:admin-products-show');
    });


    Route::get('/abandoned-carts', [AbandonedCartsController::class, 'index'])->name('admin.abandoned-carts.index')->middleware('permission:admin-abandoned-carts-show');
    Route::get('/abandoned-carts/settings', [AbandonedCartsController::class, 'settings'])->name('admin.abandoned-carts.settings')->middleware('permission:admin-abandoned-carts-settings-edit');
    Route::post('/abandoned-carts/general/settings', [AbandonedCartsController::class, 'general_settings_update'])->name('admin.abandoned-carts.general.settings.update')->middleware('permission:admin-abandoned-carts-settings-edit');
    Route::post('/abandoned-carts/automail/settings', [AbandonedCartsController::class, 'automail_settings_update'])->name('admin.abandoned-carts.automail.settings.update')->middleware('permission:admin-abandoned-carts-settings-edit');
    Route::post('/abandoned-carts/remindermail/settings', [AbandonedCartsController::class, 'remindermail_settings_update'])->name('admin.abandoned-carts.remindermail.settings.update')->middleware('permission:admin-abandoned-carts-settings-edit');


    Route::get('/products-comments', [ProductsCommentsController::class, 'index'])->name('admin.products-comments.index')->middleware('permission:admin-products-comments-show');
    Route::get('/products-comments/create', [ProductsCommentsController::class, 'create'])->name('admin.products-comments.create')->middleware('permission:admin-products-comments-create');
    Route::post('/products-comments/store', [ProductsCommentsController::class, 'store'])->name('admin.products-comments.store')->middleware('permission:admin-products-comments-create');
    Route::get('/products-comments/edit/{id}', [ProductsCommentsController::class, 'edit'])->name('admin.products-comments.edit')->middleware('permission:admin-products-comments-edit');
    Route::post('/products-comments/update/{id}', [ProductsCommentsController::class, 'update'])->name('admin.products-comments.update')->middleware('permission:admin-products-comments-edit');
    Route::get('/products-comments/delete/{id}', [ProductsCommentsController::class, 'destroy'])->name('admin.products-comments.delete')->middleware('permission:admin-products-comments-delete');
    Route::get('/products-comments/change-status/{id}', [ProductsCommentsController::class, 'change_status'])->name('admin.products-comments.changeStatus')->middleware('permission:admin-products-comments-show');



    Route::get('/visitor', [VisitorController::class, 'index'])->name('admin.visitor.index')->middleware('permission:admin-visits-show');

    Route::get('/affiliates', [AffiliateMarketingController::class, 'index'])->name('admin.affiliate.index');
    Route::get('/affiliates/add_affiliate', [AffiliateMarketingController::class, 'add_affiliate'])->name('admin.affiliate.add_affiliate');
    Route::POST('/affiliates/store', [AffiliateMarketingController::class, 'storeAffilate'])->name('admin.affiliate.store_affiliate');
    Route::get('/cities', [AffiliateMarketingController::class, 'getCities'])->name('country.admin.cities');
    Route::get('/affiliates/edit/{id}', [AffiliateMarketingController::class, 'edit_affiliate'])->name('admin.affiliate.edit');
    Route::post('/affiliates/update_product/{id}', [AffiliateMarketingController::class, 'update_affiliate'])->name('admin.affiliate.update.update_affiliate');
    Route::post('/affiliates/delete_affiliate', [AffiliateMarketingController::class, 'delete_affiliate'])->name('admin.affiliate.delete.delete_affiliate');
    Route::post('/affiliates/active_deactive_affiliate', [AffiliateMarketingController::class, 'active_deactive_affiliate'])->name('admin.affiliate.update.active_deactive_affiliate');
});


Route::group(['prefix' => 'store', 'middleware' => ['auth', 'store']], function () {
    Route::get('/home', [HomeController::class, 'index'])->name('store.home');

    Route::get('/visitor', [StoreVisitorController::class, 'index'])->name('store.visitor.index')->middleware('permission:visits-show');


    Route::get('/roles', [StoreRolesController::class, 'index'])->name('store.roles.index')->middleware('permission:store-roles-show');
    Route::get('/roles/create', [RolesController::class, 'create'])->name('store.roles.create')->middleware('permission:store-roles-create');
    Route::post('/roles/store', [RolesController::class, 'store'])->name('store.roles.store')->middleware('permission:store-roles-create');
    Route::get('/roles/edit/{id}', [RolesController::class, 'edit'])->name('store.roles.edit')->middleware('permission:store-roles-edit');
    Route::post('/roles/update/{id}', [RolesController::class, 'update'])->name('store.roles.update')->middleware('permission:store-roles-edit');
    Route::get('/roles/delete/{id}', [RolesController::class, 'destroy'])->name('store.roles.delete')->middleware('permission:store-roles-delete');

    Route::get('/settings/profile', [ProfileController::class, 'profile'])->name('store.profile');
    Route::post('/settings/profile', [ProfileController::class, 'update_profile'])->name('store.profile.update');

    Route::get('/advertisments', [StoreAdvertismentsController::class, 'index'])->name('store.advertisments.index')->middleware('permission:store-advertisments-show');
    Route::get('/advertisments/create', [StoreAdvertismentsController::class, 'create'])->name('store.advertisments.create')->middleware('permission:store-advertisments-create');
    Route::post('/advertisments/store', [StoreAdvertismentsController::class, 'store'])->name('store.advertisments.store')->middleware('permission:store-advertisments-create');
    Route::get('/advertisments/edit/{id}', [StoreAdvertismentsController::class, 'edit'])->name('store.advertisments.edit')->middleware('permission:store-advertisments-edit');
    Route::post('/advertisments/update/{id}', [StoreAdvertismentsController::class, 'update'])->name('store.advertisments.update')->middleware('permission:store-advertisments-edit');
    Route::get('/advertisments/delete/{id}', [StoreAdvertismentsController::class, 'destroy'])->name('store.advertisments.delete')->middleware('permission:store-advertisments-delete');
    Route::get('/advertisments/change-status/{id}', [StoreAdvertismentsController::class, 'change_status'])->name('store.advertisments.changeStatus')->middleware('permission:store-advertisments-show');

    Route::get('/customers', [StoreCustomersController::class, 'index'])->name('store.customers.index')->middleware('permission:customers-show');
    Route::post('/customers/store', [StoreCustomersController::class, 'store'])->name('store.customers.store')->middleware('permission:customers-create');

    Route::get('/settings', [StoreSettingsController::class, 'index'])->name('store.settings.index');
    Route::get('/settings/general', [StoreSettingsController::class, 'general'])->name('store.settings.general')->middleware('permission:store-basic-settings-edit');
    Route::post('/settings/general', [StoreSettingsController::class, 'update_general'])->name('store.settings.update-general')->middleware('permission:store-basic-settings-edit');
    Route::get('/settings/seo', [StoreSettingsController::class, 'seo'])->name('store.settings.seo')->middleware('permission:store-seo-edit');
    Route::post('/settings/seo-ar', [StoreSettingsController::class, 'update_seo_ar'])->name('store.settings.seo-ar')->middleware('permission:store-seo-edit');
    Route::post('/settings/seo-en', [StoreSettingsController::class, 'update_seo_en'])->name('store.settings.seo-en')->middleware('permission:store-seo-edit');
    Route::get('/settings/currency', [StoreSettingsController::class, 'currency'])->name('store.settings.currency')->middleware('permission:store-default-currency-edit');
    Route::post('/settings/currency', [StoreSettingsController::class, 'update_currency'])->name('store.settings.update-currency')->middleware('permission:store-default-currency-edit');
    Route::get('/settings/domain', [StoreSettingsController::class, 'domain'])->name('store.settings.domain');
    Route::get('/settings/notification', [StoreSettingsController::class, 'notification'])->name('store.settings.notification');
    Route::post('/settings/notification', [StoreSettingsController::class, 'update_notification'])->name('store.settings.update-notification');

    Route::get('/admins', [StoreAdminsController::class, 'index'])->name('store.admins.index')->middleware('permission:store-employees-show');
    Route::post('/admins/store', [StoreAdminsController::class, 'store'])->name('store.admins.store')->middleware('permission:store-employees-create');
    Route::get('/admins/edit', [StoreAdminsController::class, 'edit'])->name('store.admins.edit')->middleware('permission:store-employees-edit');
    Route::post('/admins/update/{id}', [StoreAdminsController::class, 'update'])->name('store.admins.update')->middleware('permission:store-employees-edit');
    Route::get('/admins/delete/{id}', [StoreAdminsController::class, 'destroy'])->name('store.admins.delete')->middleware('permission:store-employees-delete');
    Route::get('/admins/change-status/{id}', [StoreAdminsController::class, 'change_status'])->name('store.admins.changeStatus')->middleware('permission:store-employees-show');

    Route::get('/copons', [StoreCoponsController::class, 'index'])->name('store.copons.index')->middleware('permission:copons-show');
    Route::post('/copons/store', [StoreCoponsController::class, 'store'])->name('store.copons.store')->middleware('permission:copons-create');
    Route::get('/copons/edit', [StoreCoponsController::class, 'edit'])->name('store.copons.edit')->middleware('permission:copons-edit');
    Route::post('/copons/update/{id}', [StoreCoponsController::class, 'update'])->name('store.copons.update')->middleware('permission:copons-edit');
    Route::get('/copons/delete/{id}', [StoreCoponsController::class, 'destroy'])->name('store.copons.delete')->middleware('permission:copons-delete');
    Route::get('/copons/change-status/{id}', [StoreCoponsController::class, 'change_status'])->name('store.copons.changeStatus')->middleware('permission:copons-show');

    Route::get('/sales', [StoreSalesController::class, 'index'])->name('store.sales.index')->middleware('permission:sales-show');
    Route::get('/best-sales', [StoreSalesController::class, 'best_sales'])->name('store.sales.best-sales')->middleware('permission:sales-show');
    Route::get('/lowest-selling', [StoreSalesController::class, 'lowest_selling'])->name('store.sales.lowest-selling')->middleware('permission:sales-show');
    Route::get('/orders', [StoreOrdersController::class, 'index'])->name('store.orders.index')->middleware('permission:store-orders-show');
    Route::get('/orders/create', [StoreOrdersController::class, 'create'])->name('store.orders.create')->middleware('permission:store-orders-create');
    Route::post('/orders/store', [StoreOrdersController::class, 'store'])->name('store.orders.store')->middleware('permission:store-orders-create');
    Route::get('/orders/edit/{id}', [StoreOrdersController::class, 'edit'])->name('store.orders.edit')->middleware('permission:store-orders-edit');
    Route::post('/orders/update/{id}', [StoreOrdersController::class, 'update'])->name('store.orders.update')->middleware('permission:store-orders-edit');
    Route::get('/orders/delete/{id}', [StoreOrdersController::class, 'destroy'])->name('store.orders.delete')->middleware('permission:store-orders-delete');
    Route::get('/orders/change-status/{id}', [StoreOrdersController::class, 'change_status'])->name('store.orders.changeStatus')->middleware('permission:store-orders-show');

    Route::get('/orders-services', [StoreOrdersController::class, 'services'])->name('store.orders.services')->middleware('permission:store-orders-show');
    Route::get('/return', [ReturnController::class, 'index'])->name('store.return.index')->middleware('permission:returns-show');
    Route::get('/sales/search', [StoreSalesController::class, 'search'])->name('store.sales.search')->middleware('permission:sales-show');
    Route::get('/best-sales/search', [StoreSalesController::class, 'best_sales_search'])->name('store.sales.best-sales-search')->middleware('permission:sales-show');
    Route::get('/lowest-selling/search', [StoreSalesController::class, 'lowest_selling_search'])->name('store.sales.lowest-selling-search')->middleware('permission:sales-show');
    Route::get('/orders-details/{id}', [StoreOrdersController::class, 'details'])->name('store.orders.details')->middleware('permission:store-orders-show');
    Route::get('/return/search', [ReturnController::class, 'search'])->name('store.return.search')->middleware('permission:returns-show');
    Route::get('/reports', [ReportsController::class, 'index'])->name('store.reports.index')->middleware('permission:reports-show');


    Route::get('/abandoned-carts', [AbandonedCartsController::class, 'index'])->name('store.abandoned-carts.index')->middleware('permission:store-abandoned-carts-show');

    Route::get('/products-comments', [StoreProductsCommentsController::class, 'index'])->name('store.products-comments.index')->middleware('permission:store-products-comments-show');
    Route::get('/products-comments/create', [StoreProductsCommentsController::class, 'create'])->name('store.products-comments.create')->middleware('permission:store-products-comments-create');
    Route::post('/products-comments/store', [StoreProductsCommentsController::class, 'store'])->name('store.products-comments.store')->middleware('permission:store-products-comments-create');
    Route::get('/products-comments/edit/{id}', [StoreProductsCommentsController::class, 'edit'])->name('store.products-comments.edit')->middleware('permission:store-products-comments-edit');
    Route::post('/products-comments/update/{id}', [StoreProductsCommentsController::class, 'update'])->name('store.products-comments.update')->middleware('permission:store-products-comments-edit');
    Route::get('/products-comments/delete/{id}', [StoreProductsCommentsController::class, 'destroy'])->name('store.products-comments.delete')->middleware('permission:store-products-comments-delete');
    Route::get('/products-comments/change-status/{id}', [StoreProductsCommentsController::class, 'change_status'])->name('store.products-comments.changeStatus')->middleware('permission:store-products-comments-show');
});

Route::group(['prefix' => 'product', 'middleware' => ['auth', 'store']], function () {
    Route::get('/products', [StoreProductController::class, 'index'])->name('products.index')->middleware('permission:products-show');
    Route::get('/get_products', [StoreProductController::class, 'get_products'])->name('products.get_products')->middleware('permission:products-show');
    Route::get('/products/add_custom_made', [StoreProductController::class, 'add_custom_made'])->name('products.add.custom_made')->middleware('permission:products-create');
    Route::get('/products/edit_custom_made/{id}', [StoreProductController::class, 'edit_product'])->name('products.edit.custom_made')->middleware('permission:products-edit');
    Route::get('/products/add_ready_made', [StoreProductController::class, 'add_ready_made'])->name('products.add.ready_made')->middleware('permission:products-create');
    Route::get('/products/add_service_made', [StoreProductController::class, 'add_service_made'])->name('products.add.service_made')->middleware('permission:products-create');
    Route::get('/products/get_product_colors', [StoreProductController::class, 'get_product_colors'])->name('products.get.get_product_colors')->middleware('permission:products-show');
    Route::POST('/products/add_category', [StoreProductController::class, 'add_category'])->name('products.add.category')->middleware('permission:products-create');
    Route::POST('/products/get_product', [StoreProductController::class, 'product_data'])->name('products.get.get_product')->middleware('permission:products-show');
    Route::post('/products/add_product_color', [StoreProductController::class, 'add_product_color'])->name('products.add.add_product_color')->middleware('permission:products-create');
    Route::post('/products/store_product', [StoreProductController::class, 'store_product'])->name('products.add.add_product')->middleware('permission:products-create');
    Route::post('/products/update_product/{id}', [StoreProductController::class, 'update_product'])->name('products.update.update_product')->middleware('permission:products-edit');
    Route::post('/products/delete_product', [StoreProductController::class, 'delete_product'])->name('products.delete.delete_product')->middleware('permission:products-delete');
    Route::post('/products/active_deactive_product', [StoreProductController::class, 'active_deactive_product'])->name('products.update.active_deactive_product')->middleware('permission:products-show');
});

Route::group(['prefix' => 'affiliate', 'middleware' => ['auth', 'store']], function () {
    Route::get('/affiliates', [StoreAffiliateMarketingController::class, 'index'])->name('affiliate.index')->middleware('permission:affiliates-show');
    Route::get('/affiliates/add_affiliate', [StoreAffiliateMarketingController::class, 'add_affiliate'])->name('affiliate.add_affiliate')->middleware('permission:affiliates-create');
    Route::POST('/affiliates/store', [StoreAffiliateMarketingController::class, 'storeAffilate'])->name('affiliate.store_affiliate')->middleware('permission:affiliates-create');
    Route::get('/cities', [StoreAffiliateMarketingController::class, 'getCities'])->name('country.cities');
    Route::get('/affiliates/edit/{id}', [StoreAffiliateMarketingController::class, 'edit_affiliate'])->name('affiliate.edit')->middleware('permission:affiliates-edit');
    Route::post('/affiliates/update_product/{id}', [StoreAffiliateMarketingController::class, 'update_affiliate'])->name('affiliates.update.update_affiliate')->middleware('permission:affiliates-edit');
    Route::post('/affiliates/delete_affiliate', [StoreAffiliateMarketingController::class, 'delete_affiliate'])->name('affiliates.delete.delete_affiliate')->middleware('permission:affiliates-delete');
    Route::post('/affiliates/active_deactive_affiliate', [StoreAffiliateMarketingController::class, 'active_deactive_affiliate'])->name('affiliates.update.active_deactive_affiliate')->middleware('permission:affiliates-show');
});


require __DIR__ . '/website/website.php';
