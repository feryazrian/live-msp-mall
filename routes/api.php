<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
	return $request->user();
});

/*
|--------------------------------------------------------------------------
| API Status Codes
| https://restfulapi.net/http-status-codes/
|--------------------------------------------------------------------------
|
| (2xx Success)
| 200 OK
| 201 Created
| 202 Accepted (Updated / Deleted)
| 203 Not Found
| 204 No Content
| 205 Duplicate Content
| 206 Access Denied
| 207 Validation Error
| 208 Response Error
|
| (5xx Server Error)
| 500 Server Error
| 
*/
// Shipping
Route::domain(env('SHIPPING_URL') . '.' . str_replace('http://', '', str_replace('https://', '', env('APP_URL'))))->group(function () {

	Route::group(['middleware' => 'auth.api'], function () {

		Route::post('shipping/cost', 'Shipping\PricingController@api');
		Route::post('shipping/waybill', 'Shipping\TrackingController@api');
	});
});

// Mall
Route::group(['middleware' => 'auth.api'], function () {

	// Auth //////////////////////////////////////////////////////////////////////////
	Route::post('login', 'Api\ApiAuth@login');
	Route::post('register', 'Api\ApiAuth@register');
	Route::post('social', 'Api\ApiAuth@social');
	Route::post('forgot', 'Api\ApiAuth@forgot');
	Route::post('check', 'Api\ApiAuth@check');

	// Realtime //////////////////////////////////////////////////////////////////////
	Route::post('stats', 'Api\ApiRealtime@stats');

	// Merchant //////////////////////////////////////////////////////////////////////
	Route::post('merchant/status', 'Api\ApiMerchant@status');
	Route::post('merchant/type', 'Api\ApiMerchant@type');
	Route::post('merchant/join', 'Api\ApiMerchant@join');
	Route::post('merchant/store', 'Api\ApiMerchant@store');
	Route::post('merchant/finance', 'Api\ApiMerchant@finance');

	// Area //////////////////////////////////////////////////////////////////////////
	Route::post('area/provinsi', 'Api\ApiOption@provinsi');
	Route::post('area/provkab', 'Api\ApiOption@provinsiKabupaten');
	Route::post('area/kabupaten', 'Api\ApiOption@kabupaten');
	Route::post('area/kecamatan', 'Api\ApiOption@kecamatan');
	Route::post('area/desa', 'Api\ApiOption@desa');

	// Wishlist //////////////////////////////////////////////////////////////////////
	Route::post('wishlist', 'Api\ApiWishlist@index');
	Route::post('wishlist/create', 'Api\ApiWishlist@create');
	Route::post('wishlist/delete', 'Api\ApiWishlist@delete');

	// Setting ///////////////////////////////////////////////////////////////////////
	Route::post('setting/profile', 'Api\ApiSetting@profile');
	Route::post('setting/password', 'Api\ApiSetting@password');

	// Setting Address
	Route::post('setting/address', 'Api\ApiSetting@address');
	Route::post('setting/address/create', 'Api\ApiSetting@createAddress');
	Route::post('setting/address/update', 'Api\ApiSetting@updateAddress');
	Route::post('setting/address/delete', 'Api\ApiSetting@deleteAddress');

	// Message ///////////////////////////////////////////////////////////////////////
	Route::post('message', 'Api\ApiMessage@index');
	Route::post('message/detail', 'Api\ApiMessage@detail');
	Route::post('message/create', 'Api\ApiMessage@create');
	Route::post('message/delete', 'Api\ApiMessage@delete');

	// Ads ///////////////////////////////////////////////////////////////////////////
	Route::post('ads/create', 'Api\ApiAds@create');
	Route::post('ads/position', 'Api\ApiAds@position');

	// Search ////////////////////////////////////////////////////////////////////////
	Route::post('search', 'Api\ApiSearch@index');

	// Profile ///////////////////////////////////////////////////////////////////////
	Route::post('profile/detail', 'Api\ApiProfile@detail');
	Route::post('profile/product', 'Api\ApiProfile@product');
	Route::post('profile/review', 'Api\ApiProfile@review');

	// Product Detail ////////////////////////////////////////////////////////////////
	Route::post('product/detail', 'Api\ApiProduct@detail');
	Route::post('product/review', 'Api\ApiProduct@review');
	Route::post('product/shipping', 'Api\ApiProduct@shipping');

	Route::post('product/comment', 'Api\ApiProduct@comment');
	Route::post('product/comment/create', 'Api\ApiProduct@createComment');
	Route::post('product/comment/delete', 'Api\ApiProduct@deleteComment');

	// Point /////////////////////////////////////////////////////////////////////////
	Route::post('point', 'Api\ApiPoint@point');

	Route::post('point/welcome', 'Api\ApiPoint@welcome');
	Route::post('point/welcome/create', 'Api\ApiPoint@createWelcome');

	Route::post('point/share', 'Api\ApiPoint@share');
	Route::post('point/share/create', 'Api\ApiPoint@createShare');

	Route::post('point/referral', 'Api\ApiPoint@referral');

	Route::post('point/game', 'Api\ApiPoint@game');
	Route::post('point/game/create', 'Api\ApiPoint@createGame');

	// Product Management ////////////////////////////////////////////////////////////
	Route::post('product/stocked', 'Api\ApiProduct@stocked');
	Route::post('product/stockless', 'Api\ApiProduct@stockless');

	// Product Add Photo
	Route::post('product/add/photo/create', 'Api\ApiProduct@createPhoto');
	Route::post('product/add/photo/delete', 'Api\ApiProduct@deletePhoto');

	// Product Add
	Route::post('product/add', 'Api\ApiProduct@add');
	Route::post('product/create', 'Api\ApiProduct@create');

	// Product Edit Photo
	Route::post('product/edit/photo/create', 'Api\ApiProduct@createEditPhoto');
	Route::post('product/edit/photo/delete', 'Api\ApiProduct@deleteEditPhoto');

	// Product Edit
	Route::post('product/edit', 'Api\ApiProduct@edit');
	Route::post('product/update', 'Api\ApiProduct@update');

	// Product Delete
	Route::post('product/delete', 'Api\ApiProduct@delete');

	// Category //////////////////////////////////////////////////////////////////////
	Route::post('category', 'Api\ApiCategory@index');
	Route::post('category/highlight', 'Api\ApiCategory@highlight');

	// Page //////////////////////////////////////////////////////////////////////////
	Route::post('page', 'Api\ApiPage@index');
	Route::post('page/detail', 'Api\ApiPage@detail');

	// Slides ////////////////////////////////////////////////////////////////////////
	Route::post('slide', 'Api\ApiSlide@index');

	// Others ////////////////////////////////////////////////////////////////////////
	Route::post('option', 'Api\ApiOption@index');

	// Test //////////////////////////////////////////////////////////////////////////
	Route::post('test', 'Api\ApiTest@index');
});

Route::post('ppob_transaction', 'Api\ApiPpob@ppob_transaction');
Route::post('check_balance', 'Api\ApiPpob@checkBalance');
Route::post('digital/callback', 'Api\ApiPpob@callbackPulsa');
Route::post('digital/pulsa', 'Api\ApiPpob@buyPulsa');

Route::get('get_banner', 'Api\ApiBanner@get_all_banner');
Route::get('get_banner/{id}', 'Api\ApiBanner@getBannerById');
Route::post('add_banner', 'Api\ApiBanner@add_banner');
Route::post('delete_banner', 'Api\ApiBanner@delete_banner');
Route::post('update_banner', 'Api\ApiBanner@update_banner');

Route::post('notification/kredivo', 'Api\ApiNotification@kredivoNotification')->name('notification.kredivo');
Route::get('update/kredivo', 'Api\ApiNotification@kredivoUpdate')->name('update.kredivo');

// New Api V2 Using JWT //////////////////////////////////////////////////////////////////////////

// Grouping API V2
Route::prefix('v2')->namespace('Api\V2')->group(function () {
	// Group Authenticate user
	Route::prefix('authenticate')->group(function () {
		// OTP
		Route::post('otp/request', 'AuthController@authOTPRequest');
		Route::post('otp/verify', 'AuthController@authOTPVerify');
		// Login
		Route::post('login', 'AuthController@authLogin');
		// Register
		Route::post('register', 'AuthController@authRegister');
		// Provider
		Route::get('provider', 'AuthController@authProvider');
		Route::get('provider/facebook/callback', 'AuthController@authProviderFacebookCallback');
		Route::get('provider/google/callback', 'AuthController@authProviderGoogleCallback');
		// Reset Password
		Route::post('password/reset', 'AuthController@resetPassword');
	});

	// Group Banners
	Route::prefix('banner')->group(function () {
		// Banner List
		Route::get('list', 'BannerController@list');
		// Banner Digital
		Route::get('digital', 'BannerController@digital');
	});

	// Group Digital
	Route::prefix('digital')->group(function () {
		// Digital List
		Route::get('pricelist', 'DigitalController@priceList');
		// Digital List Detail
		Route::post('pricelistdetail', 'DigitalController@pricelistDetail');
	});

	// Group Product List
	Route::prefix('product')->group(function () {
		// Flash Sale List
		Route::get('flash-sale', 'ProductController@flashSale');
		// Group Buy Promo
		Route::get('group-buy-promo', 'ProductController@groupBuyPromo');
		// Category Highlight
		Route::get('category-highlight', 'ProductController@categoryHighlight');
		Route::get('category/{slug}', 'ProductController@categoryProductSlug');
		// Seasonal Product
		Route::get('seasonal-promo', 'ProductController@seasonalPromo');
		Route::get('seasonal-promo/{slug}', 'ProductController@seasonalPromoSlug');
		// Recomemendation
		Route::get('recommendation', 'ProductController@recommendProduct');
	});

	// Group Miscellaneous List
	Route::prefix('list')->group(function () {
		Route::get('category', 'MiscellaneousController@categoryList');
		Route::get('footer', 'MiscellaneousController@footerList');
	});

	// Protected with APIToken Middleware
	Route::middleware('APIToken')->group(function () {
		// Group Authenticate user in middleware
		Route::prefix('authenticate')->group(function () {
			// Refresh Token
			Route::post('refresh', 'AuthController@authRefresh');
			// Logout
			Route::post('logout', 'AuthController@authLogout');
			// Change Password
			Route::post('password/change', 'AuthController@changePassword');
		});
	});

	// Group Lottery
	Route::prefix('lottery')->group(function () {
		// Lottery List
		Route::get('list', 'LotteryController@list');
		Route::post('update_lottery', 'LotteryController@update_lottery');
	});
});
// Group Lottery