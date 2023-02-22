<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
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

Auth::routes(['verify' => true]);

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web', 'auth']], function () {
    \UniSharp\LaravelFilemanager\Lfm::routes();
});

#=====================================PHÍA CLLIENT===========================================#
// HOME
Route::get("/", "HomeController@index")->name("client.home.index");
Route::get("/trang-chu.html", "HomeController@index")->name("client.home.index");
Route::get("/tim-kiem-san-pham", "HomeController@search")->name("client.home.search");

// PAGE
Route::get('/gioi-thieu.html', 'PageController@introduce')->name('client.page.introduce');
Route::get('/lien-he.html', 'PageController@contact')->name('client.page.contact');
//POST
Route::get('/bai-viet', 'PostCatController@index')->name('client.post.index');
Route::get('/bai-viet/{slug}', 'PostController@index')->name('client.post.index');
//PRODUCT
Route::get('/bai-viet.html', 'PostCatController@index')->name('client.post.index');
Route::get('/bai-viet/{slug}', 'PostController@index')->name('client.post.index');
#INHERIT PROJECT ISMART
Route::get('/san-pham.html', 'ProductCatController@index')->name('client.product.cat.index');

Route::get('/san-pham/{slug}', 'ProductCatController@index')->name('client.product.cat.index');
Route::get('/san-pham/{name_cat}/{slug}', 'ProductController@index')->name('client.product.index');

// SHOPPING CART
Route::get('/gio-hang.html', 'OrderController@cart')->name("client.order.cart");

Route::get('/them-san-pham/{slug}', 'OrderController@add')->name("client.order.add");
Route::get('/them-san-pham-sl/{slug}', 'ProductController@add')->name("client.product.add");

Route::post('/cap-nhat-gio-hang', 'OrderController@update')->name("client.order.update");

Route::get('/xoa-san-pham/{rowId}', 'OrderController@remove')->name("client.order.remove");
Route::get('/xoa-gio-hang', 'OrderController@destroy')->name("client.order.destroy");

Route::get('/thanh-toan.html', 'OrderController@checkout')->name("client.order.checkout");
Route::post('/kiem-tra-thanh-toan', 'OrderController@store')->name("client.order.store");

Route::get('/mua-ngay/{slug}', 'OrderController@buynow')->name("client.order.buynow");

Route::get('/gui-email', 'OrderController@sendmail')->name("client.order.email");

Route::get('/thanh-toan-thanh-cong.html', 'OrderController@checkoutSuccess')->name("client.order.checkoutSuccess");
Route::get('/thanh-toan-that-bai.html', 'OrderController@checkoutFail')->name("client.order.checkoutFail");

// FILTER PRODUCT SIDEBAR
Route::get("/loc-san-pham", "ProductCatController@filter")->name("client.product.cat.filter");

#=====================================PHÍA ADMIN===========================================#
Route::middleware('auth', "verified")->group(function () {

    // ========================DASHBOARD===========================
    Route::get("/admin", "DashboardController@show");

    Route::get("/dashboard", "DashboardController@show")->name("dashboard.show");

    // ========================PAGES===========================
    Route::get("/admin/page/list", "AdminPageController@list")->name("admin.page.list");

    Route::get("/admin/page/add", "AdminPageController@add")->name("admin.page.add");
    Route::post("/admin/page/store", "AdminPageController@store")->name("admin.page.store");

    Route::get("/admin/page/edit/{id}", "AdminPageController@edit")->name("admin.page.edit");
    Route::post("/admin/page/update/{id}", "AdminPageController@update")->name("admin.page.update");

    Route::get("/admin/page/delete/{id}", "AdminPageController@delete")->name("admin.page.delete");

    Route::get("/admin/page/restore/{id}", "AdminPageController@restore")->name("admin.page.restore");

    Route::get("/admin/page/action", "AdminPageController@action")->name("admin.page.action");

    // ========================POSTS===========================
    Route::get("/admin/post/list", "AdminPostController@list")->name("admin.post.list");

    Route::get("/admin/post/add", "AdminPostController@add")->name("admin.post.add");
    Route::post("/admin/post/store", "AdminPostController@store")->name("admin.post.store");

    Route::get("/admin/post/edit/{id}", "AdminPostController@edit")->name("admin.post.edit");
    Route::post("/admin/post/update/{id}", "AdminPostController@update")->name("admin.post.update");

    Route::get("/admin/post/delete/{id}", "AdminPostController@delete")->name("admin.post.delete");

    Route::get("/admin/post/restore/{id}", "AdminPostController@restore")->name("admin.post.restore");

    Route::get("/admin/post/action", "AdminPostController@action")->name("admin.post.action");

    // ========================POST_CATS===========================
    Route::get("/admin/post/cat/list", "AdminPostCatController@list")->name("admin.post.cat.list");

    Route::get("/admin/post/cat/add", "AdminPostCatController@add")->name("admin.post.cat.add");
    Route::post("/admin/post/cat/store", "AdminPostCatController@store")->name("admin.post.cat.store");

    Route::get("/admin/post/cat/edit/{id}", "AdminPostCatController@edit")->name("admin.post.cat.edit");
    Route::post("/admin/post/cat/update/{id}", "AdminPostCatController@update")->name("admin.post.cat.update");

    Route::get("/admin/post/cat/delete/{id}", "AdminPostCatController@delete")->name("admin.post.cat.delete");

    Route::get("/admin/post/cat/restore/{id}", "AdminPostCatController@restore")->name("admin.post.cat.restore");

    Route::get("/admin/post/cat/action", "AdminPostCatController@action")->name("admin.post.cat.action");

    // ========================PRODUCT===========================
    Route::get("/admin/product/list", "AdminProductController@list")->name("admin.product.list");

    Route::get("/admin/product/add", "AdminProductController@add")->name("admin.product.add");
    Route::post("/admin/product/store", "AdminProductController@store")->name("admin.product.store");

    Route::get("/admin/product/edit/{id}", "AdminProductController@edit")->name("admin.product.edit");
    Route::post("/admin/product/update/{id}", "AdminProductController@update")->name("admin.product.update");

    Route::get("/admin/product/delete/{id}", "AdminProductController@delete")->name("admin.product.delete");

    Route::get("/admin/product/restore/{id}", "AdminProductController@restore")->name("admin.product.restore");

    Route::get("/admin/product/action", "AdminProductController@action")->name("admin.product.cat.action");

    // ========================PRODUCT_CATS===========================
    Route::get("/admin/product/cat/list", "AdminProductCatController@list")->name("admin.product.cat.list");

    Route::get("/admin/product/cat/add", "AdminProductCatController@add")->name("admin.product.cat.add");
    Route::post("/admin/product/cat/store", "AdminProductCatController@store")->name("admin.product.cat.store");

    Route::get("/admin/product/cat/edit/{id}", "AdminProductCatController@edit")->name("admin.product.cat.edit");
    Route::post("/admin/product/cat/update/{id}", "AdminProductCatController@update")->name("admin.product.cat.update");

    Route::get("/admin/product/cat/delete/{id}", "AdminProductCatController@delete")->name("admin.product.cat.delete");

    Route::get("/admin/product/cat/restore/{id}", "AdminProductCatController@restore")->name("admin.product.cat.restore");

    Route::get("/admin/product/cat/action", "AdminProductCatController@action")->name("admin.product.cat.action");

    // ========================ORDERS===========================
    Route::get("/admin/order/list", "AdminOrderController@list")->name("admin.order.list");

    Route::get("/admin/order/edit/{id}", "AdminOrderController@edit")->name("admin.order.edit");
    Route::post("/admin/order/update/{id}", "AdminOrderController@update")->name("admin.order.update");

    Route::get("/admin/order/detail/{id}", "AdminOrderController@detail")->name("admin.order.detail");
    Route::post("/admin/order/detail/update/{id}", "AdminOrderController@detailUpdate")->name("admin.order.detail.update");

    Route::get("/admin/order/delete/{id}", "AdminOrderController@delete")->name("admin.order.delete");

    Route::get("/admin/order/restore/{id}", "AdminOrderController@restore")->name("admin.order.restore");

    Route::get("/admin/order/action", "AdminOrderController@action")->name("admin.order.action");

    // ========================CUSTOMERS===========================
    Route::get("/admin/customer/list", "AdminCustomerController@list")->name("admin.customer.list");

    Route::get("/admin/customer/edit/{id}", "AdminCustomerController@edit")->name("admin.customer.edit");
    Route::post("/admin/customer/update/{id}", "AdminCustomerController@update")->name("admin.customer.update");

    Route::get("/admin/customer/delete/{id}", "AdminCustomerController@delete")->name("admin.customer.delete");

    Route::get("/admin/customer/restore/{id}", "AdminCustomerController@restore")->name("admin.customer.restore");

    Route::get("/admin/customer/action", "AdminCustomerController@action")->name("admin.customer.action");

    // ========================SLIDERS===========================
    Route::get("/admin/slider/list", "AdminSliderController@list")->name("admin.slider.list");

    Route::get("/admin/slider/add", "AdminSliderController@add")->name("admin.slider.add");
    Route::post("/admin/slider/store", "AdminSliderController@store")->name("admin.slider.store");

    Route::get("/admin/slider/edit/{id}", "AdminSliderController@edit")->name("admin.slider.edit");
    Route::post("/admin/slider/update/{id}", "AdminSliderController@update")->name("admin.slider.update");

    Route::get("/admin/slider/delete/{id}", "AdminSliderController@delete")->name("admin.slider.delete");

    Route::get("/admin/slider/restore/{id}", "AdminSliderController@restore")->name("admin.slider.restore");

    Route::get("/admin/slider/action", "AdminSliderController@action")->name("admin.slider.action");

    // ========================BANNERS===========================
    Route::get("/admin/banner/list", "AdminBannerController@list")->name("admin.banner.list");

    Route::get("/admin/banner/add", "AdminBannerController@add")->name("admin.banner.add");
    Route::post("/admin/banner/store", "AdminBannerController@store")->name("admin.banner.store");

    Route::get("/admin/banner/edit/{id}", "AdminBannerController@edit")->name("admin.banner.edit");
    Route::post("/admin/banner/update/{id}", "AdminBannerController@update")->name("admin.banner.update");

    Route::get("/admin/banner/delete/{id}", "AdminBannerController@delete")->name("admin.banner.delete");

    Route::get("/admin/banner/restore/{id}", "AdminBannerController@restore")->name("admin.banner.restore");

    Route::get("/admin/banner/action", "AdminBannerController@action")->name("admin.banner.action");

    // ========================USER===========================
    Route::get("/admin/user/list", "AdminUserController@list")->name("admin.user.list");

    Route::get("/admin/user/add", "AdminUserController@add")->name("admin.user.add");
    Route::post("/admin/user/store", "AdminUserController@store")->name("admin.user.store");

    Route::get("/admin/user/edit/{id}", "AdminUserController@edit")->name("admin.user.edit");
    Route::post("/admin/user/update/{id}", "AdminUserController@update")->name("admin.user.update");

    Route::get("/admin/user/delete/{id}", "AdminUserController@delete")->name("admin.user.delete");

    Route::get("/admin/user/restore/{id}", "AdminUserController@restore")->name("admin.user.restore");

    Route::get("/admin/user/action", "AdminUserController@action")->name("admin.user.action");

    // ========================ROLES===========================
    Route::get("/admin/role/list", "AdminRoleController@list")->name("admin.role.list");

    Route::get("/admin/role/add", "AdminRoleController@add")->name("admin.role.add");
    Route::post("/admin/role/store", "AdminRoleController@store")->name("admin.role.store");

    Route::get("/admin/role/edit/{id}", "AdminRoleController@edit")->name("admin.role.edit");
    Route::post("/admin/role/update/{id}", "AdminRoleController@update")->name("admin.role.update");

    Route::get("/admin/role/delete/{id}", "AdminRoleController@delete")->name("admin.role.delete");

    Route::get("/admin/role/restore/{id}", "AdminRoleController@restore")->name("admin.role.restore");

    Route::get("/admin/role/action", "AdminRoleController@action")->name("admin.role.action");

    // ========================IMAGES===========================
    Route::get("/admin/image/list", "AdminImageController@list")->name("admin.image.list");

    Route::get("/admin/image/add", "AdminImageController@add")->name("admin.image.add");
    Route::post("/admin/image/store", "AdminImageController@store")->name("admin.image.store");

    Route::get("/admin/image/addMulti/{id}", "AdminImageController@addMulti")->name("admin.image.addMulti");
    Route::post("/admin/image/storeMulti/{id}", "AdminImageController@storeMulti")->name("admin.image.storeMulti");

    Route::get("/admin/image/edit/{id}", "AdminImageController@edit")->name("admin.image.edit");
    Route::post("/admin/image/update/{id}", "AdminImageController@update")->name("admin.image.update");

    Route::get("/admin/image/delete/{id}", "AdminImageController@delete")->name("admin.image.delete");

    Route::get("/admin/image/restore/{id}", "AdminImageController@restore")->name("admin.image.restore");

    Route::get("/admin/image/action", "AdminImageController@action")->name("admin.image.action");
});
