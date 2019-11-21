<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
| composer dump-autoload
| php artisan serve --host=laravel.local --port=8000
|
*/

Route::get('clear-cache', function () {
    $exitcode = Artisan::call('config:cache');
    $exitcode = Artisan::call('cache:clear');
    $exitcode = Artisan::call('view:clear');
    $exitcode = Artisan::call('config:clear');
    return 'DONE' . $exitcode; //Return anything
});

// Auth
Auth::routes();

// Shipping
Route::domain(env('SHIPPING_URL') . '.' . str_replace('http://', '', str_replace('https://', '', env('APP_URL'))))->group(function () {

    Route::get('/', 'Shipping\HomeController@index')->name('shipping.home');

    Route::get('pricing', 'Shipping\PricingController@index')->name('shipping.pricing');
    Route::post('pricing', 'Shipping\PricingController@store')->name('shipping.pricing.store');

    Route::get('tracking', 'Shipping\TrackingController@index')->name('shipping.tracking');
    Route::post('tracking', 'Shipping\TrackingController@store')->name('shipping.tracking.store');

    Route::get('page/{slug}', 'Shipping\PageController@detail')->name('shipping.page.detail');
    Route::get('page', 'Shipping\PageController@index')->name('shipping.page');
});

// Admin
Route::group(['middleware' => ['admin']], function () {

    Route::prefix('office')->group(function () {
        // Get Badges
        Route::get('get-badges', 'Admin\DashboardController@getBadges')->name('get.badges');
    });

    // Dashboard
    Route::get('/s/dashboard', 'Admin\DashboardController@index')
        ->name('admin.dashboard');

    // Shipping
    Route::get('/s/shipping', 'Admin\ShippingController@index')
        ->name('admin.shipping');
    Route::get('/s/shipping/data', 'Admin\ShippingController@data')
        ->name('admin.shipping.data');

    Route::post('/s/shipping/store', 'Admin\ShippingController@store')
        ->name('admin.shipping.store');
    Route::get('/s/shipping/create', 'Admin\ShippingController@create')
        ->name('admin.shipping.create');

    Route::post('/s/shipping/update', 'Admin\ShippingController@update')
        ->name('admin.shipping.update');
    Route::get('/s/shipping/{id}', 'Admin\ShippingController@edit')
        ->name('admin.shipping.edit');

    Route::post('/s/shipping/delete', 'Admin\ShippingController@delete')
        ->name('admin.shipping.delete');

    // Manifest
    Route::post('/s/manifest/store', 'Admin\ShippingController@storeManifest')
        ->name('admin.manifest.store');
    Route::get('/s/manifest/create/{id}', 'Admin\ShippingController@createManifest')
        ->name('admin.manifest.create');

    Route::get('/s/manifest/data/{id}', 'Admin\ShippingController@dataManifest')
        ->name('admin.manifest.data');

    Route::get('/s/manifest/delete/{id}', 'Admin\ShippingController@deleteManifest')
        ->name('admin.manifest.delete');


    // Page
    Route::get('/s/pages', 'Admin\PageController@index')
        ->name('admin.page');
    Route::get('/s/page/data', 'Admin\PageController@data')
        ->name('admin.page.data');

    Route::post('/s/page/store', 'Admin\PageController@store')
        ->name('admin.page.store');
    Route::get('/s/page/create', 'Admin\PageController@create')
        ->name('admin.page.create');

    Route::post('/s/page/update', 'Admin\PageController@update')
        ->name('admin.page.update');
    Route::get('/s/page/{id}', 'Admin\PageController@edit')
        ->name('admin.page.edit');

    Route::post('/s/page/delete', 'Admin\PageController@delete')
        ->name('admin.page.delete');


    // Loyalty Member
    Route::get('/s/loyalty', 'Admin\LoyaltyController@index')
        ->name('admin.loyalty');
    Route::get('/s/loyalty/data', 'Admin\LoyaltyController@data')
        ->name('admin.loyalty.data');

    Route::post('/s/loyalty/store', 'Admin\LoyaltyController@store')
        ->name('admin.loyalty.store');
    Route::get('/s/loyalty/create', 'Admin\LoyaltyController@create')
        ->name('admin.loyalty.create');

    Route::post('/s/loyalty/update', 'Admin\LoyaltyController@update')
        ->name('admin.loyalty.update');
    Route::get('/s/loyalty/{id}', 'Admin\LoyaltyController@edit')
        ->name('admin.loyalty.edit');

    Route::post('/s/loyalty/delete', 'Admin\LoyaltyController@delete')
        ->name('admin.loyalty.delete');


    // Point Product
    Route::get('/s/point/products', 'Admin\PointProductController@index')
        ->name('admin.point.product');
    Route::get('/s/point/product/data', 'Admin\PointProductController@data')
        ->name('admin.point.product.data');

    Route::post('/s/point/product/store', 'Admin\PointProductController@store')
        ->name('admin.point.product.store');
    Route::get('/s/point/product/create', 'Admin\PointProductController@create')
        ->name('admin.point.product.create');

    Route::post('/s/point/product/update', 'Admin\PointProductController@update')
        ->name('admin.point.product.update');
    Route::get('/s/point/product/{id}', 'Admin\PointProductController@edit')
        ->name('admin.point.product.edit');

    Route::post('/s/point/product/delete', 'Admin\PointProductController@delete')
        ->name('admin.point.product.delete');

    // Point
    Route::get('/s/points', 'Admin\PointController@index')
        ->name('admin.point');
    Route::get('/s/point/data', 'Admin\PointController@data')
        ->name('admin.point.data');

    Route::post('/s/point/store', 'Admin\PointController@store')
        ->name('admin.point.store');
    Route::get('/s/point/create', 'Admin\PointController@create')
        ->name('admin.point.create');

    Route::post('/s/point/update', 'Admin\PointController@update')
        ->name('admin.point.update');
    Route::get('/s/point/{id}', 'Admin\PointController@edit')
        ->name('admin.point.edit');

    Route::post('/s/point/delete', 'Admin\PointController@delete')
        ->name('admin.point.delete');

    // Kategori
    Route::get('/s/categories', 'Admin\CategoryController@index')
        ->name('admin.category');
    Route::get('/s/category/data', 'Admin\CategoryController@data')
        ->name('admin.category.data');

    Route::post('/s/category/store', 'Admin\CategoryController@store')
        ->name('admin.category.store');
    Route::get('/s/category/create', 'Admin\CategoryController@create')
        ->name('admin.category.create');

    Route::post('/s/category/update', 'Admin\CategoryController@update')
        ->name('admin.category.update');
    Route::get('/s/category/{id}', 'Admin\CategoryController@edit')
        ->name('admin.category.edit');

    Route::post('/s/category/delete', 'Admin\CategoryController@delete')
        ->name('admin.category.delete');

    // AutoDebet
    Route::get('/s/autodebet', 'Admin\AutoDebetController@index')
        ->name('admin.autodebet');
    Route::get('/s/autodebet/data', 'Admin\AutoDebetController@data')
        ->name('admin.autodebet.data');
    Route::get('/s/autodebet/create', 'Admin\AutoDebetController@create')
        ->name('admin.autodebet.create');
    Route::post('/s/autodebet/store', 'Admin\AutoDebetController@store')
        ->name('admin.autodebet.store');
    Route::post('/s/autodebet/update', 'Admin\AutoDebetController@update')
        ->name('admin.autodebet.update');
    Route::get('/s/autodebet/{id}/edit', 'Admin\AutoDebetController@edit')
        ->name('admin.autodebet.edit');
    Route::post('/s/autodebet/delete', 'Admin\AutoDebetController@delete')
        ->name('admin.autodebet.delete');
    Route::post('/s/autodebet/update_auto_debet', 'Admin\AutoDebetController@update_auto_debet')
        ->name('admin.autodebet.update_auto_debet');
    Route::post('/s/autodebet/autodebet_all', 'Admin\AutoDebetController@autodebet_all')
        ->name('admin.autodebet.autodebet_all');

    // Balance Deposit History
    Route::get('/s/balancedeposithistory', 'Admin\BalanceDepositHistoryController@index')
        ->name('admin.balancedeposithistory');
    Route::get('/s/balancedeposithistory/data', 'Admin\BalanceDepositHistoryController@data')
        ->name('admin.balancedeposithistory.data');
    Route::get('/s/balancedeposithistory/create', 'Admin\BalanceDepositHistoryController@create')
        ->name('admin.balancedeposithistory.create');
    Route::post('/s/balancedeposithistory/store', 'Admin\BalanceDepositHistoryController@store')
        ->name('admin.balancedeposithistory.store');

    // Promo
    Route::get('/s/promo', 'Admin\PromoController@index')
        ->name('admin.promo');
    Route::get('/s/promo/data', 'Admin\PromoController@data')
        ->name('admin.promo.data');

    Route::post('/s/promo/store', 'Admin\PromoController@store')
        ->name('admin.promo.store');
    Route::get('/s/promo/{type}/create', 'Admin\PromoController@create')
        ->name('admin.promo.create');

    Route::post('/s/promo/update', 'Admin\PromoController@update')
        ->name('admin.promo.update');
    Route::get('/s/promo/{id}/edit', 'Admin\PromoController@edit')
        ->name('admin.promo.edit');
    Route::get('/s/promo/{id}/used-list', 'Admin\PromoController@usedList')
        ->name('admin.promo.used-list');
    Route::get('/s/promo/{id}/used-list-data', 'Admin\PromoController@usedListData')
        ->name('admin.promo.used-list-data');

    Route::post('/s/promo/delete', 'Admin\PromoController@delete')
        ->name('admin.promo.delete');

    // Season
    Route::get('/s/seasons', 'Admin\SeasonController@index')
        ->name('admin.season');
    Route::get('/s/season/data', 'Admin\SeasonController@data')
        ->name('admin.season.data');

    Route::post('/s/season/store', 'Admin\SeasonController@store')
        ->name('admin.season.store');
    Route::get('/s/season/create', 'Admin\SeasonController@create')
        ->name('admin.season.create');

    Route::post('/s/season/update', 'Admin\SeasonController@update')
        ->name('admin.season.update');
    Route::get('/s/season/{id}/edit', 'Admin\SeasonController@edit')
        ->name('admin.season.edit');

    Route::post('/s/season/delete', 'Admin\SeasonController@delete')
        ->name('admin.season.delete');

    // Season Product
    Route::get('/s/season/{id}/product', 'Admin\SeasonController@product')
        ->name('admin.season.product');
    Route::get('/s/season/{id}/product/data', 'Admin\SeasonController@dataProduct')
        ->name('admin.season.product.data');

    Route::post('/s/season/product/store', 'Admin\SeasonController@storeProduct')
        ->name('admin.season.product.store');
    Route::post('/s/season/product/delete', 'Admin\SeasonController@deleteProduct')
        ->name('admin.season.product.delete');

    // Slide
    Route::get('/s/slides', 'Admin\SlideController@index')
        ->name('admin.slide');
    Route::get('/s/slide/data', 'Admin\SlideController@data')
        ->name('admin.slide.data');

    Route::post('/s/slide/store', 'Admin\SlideController@store')
        ->name('admin.slide.store');
    Route::get('/s/slide/create', 'Admin\SlideController@create')
        ->name('admin.slide.create');

    Route::post('/s/slide/update', 'Admin\SlideController@update')
        ->name('admin.slide.update');
    Route::get('/s/slide/{id}', 'Admin\SlideController@edit')
        ->name('admin.slide.edit');

    Route::post('/s/slide/delete', 'Admin\SlideController@delete')
        ->name('admin.slide.delete');

    // Option
    Route::get('/s/options', 'Admin\OptionController@index')
        ->name('admin.option');
    Route::get('/s/option/data', 'Admin\OptionController@data')
        ->name('admin.option.data');

    Route::post('/s/option/update', 'Admin\OptionController@update')
        ->name('admin.option.update');
    Route::get('/s/option/{id}', 'Admin\OptionController@edit')
        ->name('admin.option.edit');

    // Pulsa
    Route::get('/s/ppob/pulsa', 'Admin\PulsaController@index')
        ->name('admin.pulsa');
    Route::get('/s/ppob/pulsa/data', 'Admin\PulsaController@data')
        ->name('admin.pulsa.data');
    Route::post('/s/pulsa/update', 'Admin\PulsaController@update')
        ->name('admin.pulsa.update');
    Route::get('/s/ppob/pulsa/{id}', 'Admin\PulsaController@edit')
        ->name('admin.pulsa.edit');

    // Data
    Route::get('/s/ppob/data', 'Admin\DataController@index')
        ->name('admin.data');
    Route::get('/s/ppob/data/data', 'Admin\DataController@data')
        ->name('admin.data.data');
    Route::post('/s/data/update', 'Admin\DataController@update')
        ->name('admin.data.update');
    Route::get('/s/ppob/data/{id}', 'Admin\DataController@edit')
        ->name('admin.data.edit');


    //Banner
    Route::post('/s/banner/store', 'Admin\BannerController@store')
        ->name('admin.banner.store');
    Route::get('/s/banner/create', 'Admin\BannerController@create')
        ->name('admin.banner.create');
    Route::get('/s/banner', 'Admin\BannerController@index')
        ->name('admin.banner');
    Route::get('/s/banner/data', 'Admin\BannerController@data')
        ->name('admin.banner.data');
    Route::post('/s/banner/update', 'Admin\BannerController@update')
        ->name('admin.banner.update');
    Route::get('/s/banner/{id}/edit', 'Admin\BannerController@edit')
        ->name('admin.banner.edit');
    Route::post('/s/banner/delete', 'Admin\BannerController@delete')
        ->name('admin.banner.delete');

    // Ads
    Route::get('/s/ads', 'Admin\AdsController@index')
        ->name('admin.ads');
    Route::get('/s/ads/data', 'Admin\AdsController@data')
        ->name('admin.ads.data');

    Route::get('/s/ads/{id}', 'Admin\AdsController@edit')
        ->name('admin.ads.edit');

    // Penarikan
    Route::get('/s/withdraw', 'Admin\WithdrawController@index')
        ->name('admin.withdraw');
    Route::get('/s/withdraw/data', 'Admin\WithdrawController@data')
        ->name('admin.withdraw.data');

    Route::post('/s/withdraw/update', 'Admin\WithdrawController@update')
        ->name('admin.withdraw.update');
    Route::get('/s/withdraw/{id}', 'Admin\WithdrawController@edit')
        ->name('admin.withdraw.edit');


    // User List
    Route::get('/s/users', 'Admin\UserController@index')
        ->name('admin.user');
    Route::get('/s/user/data', 'Admin\UserController@data')
        ->name('admin.user.data');
    Route::get('/s/user/{id}/block', 'Admin\UserController@block')
        ->name('admin.user.block');

    // User Type
    Route::get('/s/user/type', 'Admin\UserController@type')
        ->name('admin.user.type');
    Route::get('/s/user/type/data', 'Admin\UserController@dataType')
        ->name('admin.user.type.data');

    Route::post('/s/user/type/update', 'Admin\UserController@updateType')
        ->name('admin.user.type.update');
    Route::get('/s/user/type/{id}', 'Admin\UserController@editType')
        ->name('admin.user.type.edit');


    // Approve Product
    Route::get('/s/approve/product', 'Admin\ApproveController@product')
        ->name('admin.approve.product');
    Route::get('/s/approve/product/data', 'Admin\ApproveController@dataProduct')
        ->name('admin.approve.product.data');

    Route::post('/s/approve/product/update', 'Admin\ApproveController@updateProduct')
        ->name('admin.approve.product.update');
    Route::get('/s/approve/product/{id}', 'Admin\ApproveController@editProduct')
        ->name('admin.approve.product.edit');

    // Approve Sale
    Route::get('/s/approve/sale', 'Admin\ApproveController@sale')
        ->name('admin.approve.sale');
    Route::get('/s/approve/sale/data', 'Admin\ApproveController@dataSale')
        ->name('admin.approve.sale.data');

    Route::post('/s/approve/sale/update', 'Admin\ApproveController@updateSale')
        ->name('admin.approve.sale.update');
    Route::get('/s/approve/sale/{id}', 'Admin\ApproveController@editSale')
        ->name('admin.approve.sale.edit');

    // Approve Merchant
    Route::get('/s/approve/merchant', 'Admin\ApproveController@merchant')
        ->name('admin.approve.merchant');
    Route::get('/s/approve/merchant/data', 'Admin\ApproveController@dataMerchant')
        ->name('admin.approve.merchant.data');

    Route::post('/s/approve/merchant/update', 'Admin\ApproveController@updateMerchant')
        ->name('admin.approve.merchant.update');
    Route::get('/s/approve/merchant/{id}', 'Admin\ApproveController@editMerchant')
        ->name('admin.approve.merchant.edit');


    // Merchant Update - Account
    Route::get('/s/merchant/account', 'Admin\MerchantController@account')
        ->name('admin.merchant.account');
    Route::get('/s/merchant/account/data', 'Admin\MerchantController@dataAccount')
        ->name('admin.merchant.account.data');

    Route::post('/s/merchant/account/update', 'Admin\MerchantController@updateAccount')
        ->name('admin.merchant.account.update');
    Route::get('/s/merchant/account/{id}', 'Admin\MerchantController@editAccount')
        ->name('admin.merchant.account.edit');

    // Merchant Update - Store
    Route::get('/s/merchant/store', 'Admin\MerchantController@store')
        ->name('admin.merchant.store');
    Route::get('/s/merchant/store/data', 'Admin\MerchantController@dataStore')
        ->name('admin.merchant.store.data');

    Route::post('/s/merchant/store/update', 'Admin\MerchantController@updateStore')
        ->name('admin.merchant.store.update');
    Route::get('/s/merchant/store/{id}', 'Admin\MerchantController@editStore')
        ->name('admin.merchant.store.edit');

    // Merchant Update - Finance
    Route::get('/s/merchant/finance', 'Admin\MerchantController@finance')
        ->name('admin.merchant.finance');
    Route::get('/s/merchant/finance/data', 'Admin\MerchantController@dataFinance')
        ->name('admin.merchant.finance.data');

    Route::post('/s/merchant/finance/update', 'Admin\MerchantController@updateFinance')
        ->name('admin.merchant.finance.update');
    Route::get('/s/merchant/finance/{id}', 'Admin\MerchantController@editFinance')
        ->name('admin.merchant.finance.edit');

    // Live Streaming
    Route::get('/s/streaming/live', 'Admin\LiveStreamingController@index')
        ->name('admin.streaming.live');
    Route::get('/s/streaming/live/data', 'Admin\LiveStreamingController@data')
        ->name('admin.streaming.data');

    Route::get('/s/streaming/live/create', 'Admin\LiveStreamingController@create')
        ->name('admin.streaming.create');
    Route::post('/s/streaming/live/store', 'Admin\LiveStreamingController@store')
        ->name('admin.streaming.store');

    Route::get('/s/streaming/live/{id}/edit', 'Admin\LiveStreamingController@edit')
        ->name('admin.streaming.edit');
    Route::post('/s/streaming/live/{id}/update', 'Admin\LiveStreamingController@update')
        ->name('admin.streaming.update');

    // Payment Gateway
    Route::get('/s/payment/gateway', 'Admin\PaymentGatewayController@index')
        ->name('admin.payment.index');
    Route::get('/s/payment/gateway/data', 'Admin\PaymentGatewayController@data')
        ->name('admin.payment.data');

    Route::get('/s/payment/gateway/create', 'Admin\PaymentGatewayController@create')
        ->name('admin.payment.create');
    Route::post('/s/payment/gateway/store', 'Admin\PaymentGatewayController@store')
        ->name('admin.payment.store');

    Route::get('/s/payment/gateway/{id}/edit', 'Admin\PaymentGatewayController@edit')
        ->name('admin.payment.edit');
    Route::post('/s/payment/gateway/{id}/update', 'Admin\PaymentGatewayController@update')
        ->name('admin.payment.update');


    // Debug
    Route::get('decompose', '\Lubusin\Decomposer\Controllers\DecomposerController@index')
        ->name('decompose');
    Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index')
        ->name('logs');

    // Sales Report
    Route::get('/s/salesreport', 'Admin\SalesReportController@index')
        ->name('admin.salesreport');
    Route::get('/s/salesreport/data', 'Admin\SalesReportController@data')
        ->name('admin.salesreport.data');
    Route::post('/s/salesreport/update', 'Admin\SalesReportController@update')
        ->name('admin.salesreport.update');
    Route::get('/s/salesreport/{id}', 'Admin\SalesReportController@edit')
        ->name('admin.salesreport.edit');

    // Coupon Report
    Route::get('/s/coupon', 'Admin\CouponController@index')
        ->name('admin.coupon');
    Route::get('/s/coupon/data', 'Admin\CouponController@data')
        ->name('admin.coupon.data');
    Route::post('/s/coupon/update', 'Admin\CouponController@update')
        ->name('admin.coupon.update');
    Route::get('/s/coupon/{id}', 'Admin\CouponController@edit')
        ->name('admin.coupon.edit');

    // Lottery List
    Route::get('/s/lottery', 'Admin\LotteryController@index')
        ->name('admin.lottery');
    Route::get('/s/lottery/data', 'Admin\LotteryController@data')
        ->name('admin.lottery.data');
    Route::get('/s/lottery/create', 'Admin\LotteryController@create')
        ->name('admin.lottery.create');
    Route::post('/s/lottery/store', 'Admin\LotteryController@store')
        ->name('admin.lottery.store');
    Route::post('/s/lottery/update', 'Admin\LotteryController@update')
        ->name('admin.lottery.update');
    Route::get('/s/lottery/{id}/edit', 'Admin\LotteryController@edit')
        ->name('admin.lottery.edit');
    Route::post('/s/lottery/delete', 'Admin\LotteryController@delete')
        ->name('admin.lottery.delete');

    // Voucher Transaction Report
    Route::get('/s/vouchertransaction', 'Admin\VoucherTransactionController@index')
        ->name('admin.vouchertransaction');
    Route::get('/s/vouchertransaction/data', 'Admin\VoucherTransactionController@data')
        ->name('admin.vouchertransaction.data');
    Route::post('/s/vouchertransaction/update', 'Admin\VoucherTransactionController@update')
        ->name('admin.vouchertransaction.update');
    Route::get('/s/vouchertransaction/{id}', 'Admin\VoucherTransactionController@edit')
        ->name('admin.vouchertransaction.edit');

    // Voucher Transaction Report
    Route::get('/s/monswallethistory', 'Admin\MonsWalletHistoryController@index')
        ->name('admin.monswallethistory');
    Route::get('/s/monswallethistory/data', 'Admin\MonsWalletHistoryController@data')
        ->name('admin.monswallethistory.data');
    Route::post('/s/monswallethistory/update', 'Admin\MonsWalletHistoryController@update')
        ->name('admin.monswallethistory.update');
    Route::get('/s/monswallethistory/{id}', 'Admin\MonsWalletHistoryController@edit')
        ->name('admin.monswallethistory.edit');

});

// Member
Route::group(['middleware' => ['auth']], function () {

    // Dashboard //////////////////////////////////////////////////////////////////////////////////////////////////////
    Route::get('logout', 'HomeController@logout')->name('user.logout');

    // Ads ////////////////////////////////////////////////////////////////////////////////////////////////////////////
    Route::get('ads', 'AdsController@request')->name('ads.request');
    Route::post('ads/store', 'AdsController@store')->name('ads.store');

    // Wishlist ///////////////////////////////////////////////////////////////////////////////////////////////////////
    Route::get('wishlist', 'WishlistController@index')->name('wishlist');
    Route::post('wishlist/store', 'WishlistController@store')->name('wishlist.store');
    Route::post('wishlist/delete', 'WishlistController@delete')->name('wishlist.delete');

    // Setting ////////////////////////////////////////////////////////////////////////////////////////////////////////
    // Setting Profile
    Route::get('setting', 'SettingController@profile')->name('setting');
    Route::post('setting/profile/update', 'SettingController@updateProfile')->name('setting.profile.update');

    // Setting Password
    Route::get('setting/password', 'SettingController@password')->name('setting.password');
    Route::post('setting/password/update', 'SettingController@updatePassword')->name('setting.password.update');

    // Setting Address
    Route::get('setting/address', 'SettingController@address')->name('setting.address');

    Route::get('setting/address/add', 'SettingController@addAddress')->name('setting.address.add');
    Route::post('setting/address/store', 'SettingController@storeAddress')->name('setting.address.store');

    Route::get('setting/address/{id}/edit', 'SettingController@editAddress')->name('setting.address.edit');
    Route::post('setting/address/edit', 'SettingController@updateAddress')->name('setting.address.update');

    Route::post('setting/address/delete', 'SettingController@deleteAddress')->name('setting.address.delete');

    // Message ////////////////////////////////////////////////////////////////////////////////////////////////////////
    Route::get('message/{username}', 'MessageController@detail')->name('message.detail');
    Route::get('message', 'MessageController@index')->name('message');

    Route::post('message/store', 'MessageController@store')->name('message.store');
    Route::post('message/delete', 'MessageController@delete')->name('message.delete');

    Route::get('json/message/{username}', 'MessageController@json')->name('json.message'); // Message

    // Contact ////////////////////////////////////////////////////////////////////////////////////////////////////////
    Route::get('contact', 'MessageController@contact')->name('contact');

    // Coupon /////////////////////////////////////////////////////////////////////////////////////////////////////////
    Route::get('coupon/transaction', 'CouponController@index')->name('coupon');

    // Route::get('coupon/point', 'PointController@coupon')->name('coupon.point');

    Route::get('coupon/create', 'PointController@createCoupon')->name('coupon.create');
    Route::post('coupon/store', 'PointController@storeCoupon')->name('coupon.store');

    // Merchant ///////////////////////////////////////////////////////////////////////////////////////////////////////
    Route::get('loyalty/request', 'LoyaltyController@request')->name('loyalty.request');
    Route::post('loyalty/submit', 'LoyaltyController@submit')->name('loyalty.submit');
    Route::get('loyalty/complete', 'LoyaltyController@complete')->name('loyalty.complete');

    // Merchant ///////////////////////////////////////////////////////////////////////////////////////////////////////
    Route::get('merchant/join', 'MerchantController@join')->name('merchant.join');
    Route::get('merchant/store', 'MerchantController@store')->name('merchant.store');
    Route::get('merchant/finance', 'MerchantController@finance')->name('merchant.finance');
    Route::get('merchant/complete', 'MerchantController@complete')->name('merchant.complete');

    Route::post('merchant/one', 'MerchantController@one')->name('merchant.one');
    Route::post('merchant/two', 'MerchantController@two')->name('merchant.two');
    Route::post('merchant/three', 'MerchantController@three')->name('merchant.three');

    Route::post('merchant/type', 'MerchantController@type')->name('merchant.type');

    // Merchant Settings //////////////////////////////////////////////////////////////////////////////////////////////
    Route::get('merchant/account/edit', 'MerchantController@editAccount')->name('merchant.account.edit');
    Route::post('merchant/account/update', 'MerchantController@updateAccount')->name('merchant.account.update');

    Route::get('merchant/store/edit', 'MerchantController@editStore')->name('merchant.store.edit');
    Route::post('merchant/store/update', 'MerchantController@updateStore')->name('merchant.store.update');

    Route::get('merchant/finance/edit', 'MerchantController@editFinance')->name('merchant.finance.edit');
    Route::post('merchant/finance/update', 'MerchantController@updateFinance')->name('merchant.finance.update');

    Route::get('merchant/shipping/edit', 'MerchantController@editShipping')->name('merchant.shipping.edit');
    Route::post('merchant/shipping/update', 'MerchantController@updateShipping')->name('merchant.shipping.update');

    // Product Management /////////////////////////////////////////////////////////////////////////////////////////////
    Route::get('product', 'ProductController@product')->name('product');
    Route::get('product/stocked', 'ProductController@product')->name('product.stocked');
    Route::get('product/stockless', 'ProductController@productStockless')->name('product.stockless');

    // Product Add
    Route::get('product/{type}/add', 'ProductController@add')->name('product.add');
    Route::post('product/store', 'ProductController@store')->name('product.store');

    Route::post('product/add-photo', 'ProductController@addPhoto')->name('product.photo.add');
    Route::post('product/delete-photo', 'ProductController@deletePhoto')->name('product.photo.delete');

    // Product Delete
    Route::post('product/delete', 'ProductController@delete')->name('product.delete');

    // Product Photo Edit
    Route::post('product/add-edit-photo', 'ProductController@addEditPhoto')->name('product.photo.edit.add');
    Route::post('product/delete-edit-photo', 'ProductController@deleteEditPhoto')->name('product.photo.edit.delete');

    // Product Edit
    Route::post('product/update', 'ProductController@update')->name('product.update');
    Route::get('product/{slug}/edit', 'ProductController@edit')->name('product.edit');

    // Voucher Edit
    Route::post('voucher/update', 'ProductController@updateVoucher')->name('voucher.update');

    // Voucher Add
    Route::post('voucher/store', 'ProductController@storeVoucher')->name('voucher.store');

    // Product Detail
    Route::post('product/{slug}', 'ProductController@detail')->name('product.detail');

    // Product Comment
    Route::post('product/comment/store', 'ProductController@storeComment')->name('product.comment.store');
    Route::post('product/comment/delete', 'ProductController@deleteComment')->name('product.comment.delete');

    // Product Comment Reply
    //Route::post('/product/add-reply', 'ProductController@addReply')->name('product.reply.add');
    //Route::post('/product/delete-reply', 'ProductController@deleteReply')->name('product.reply.delete');

    // Point //////////////////////////////////////////////////////////////////////////////////////////////////////////
    Route::get('point', 'PointController@index')->name('point');
    Route::get('point/topup', 'PointController@topup')->name('point.topup');
    Route::post('point/topup/store', 'PointController@storeTopup')->name('point.topup.store');

    Route::get('gift/welcome', 'PointController@welcome')->name('point.welcome');
    Route::post('gift/welcome/store', 'PointController@storeWelcome')->name('point.welcome.store');

    Route::get('gift/share', 'PointController@share')->name('point.share');
    Route::post('gift/share/store', 'PointController@storeShare')->name('point.share.store');

    Route::get('gift/referral', 'PointController@referral')->name('point.referral');

    Route::get('gift/game', 'PointController@game')->name('point.game');
    Route::post('gift/game/store', 'PointController@storeGame')->name('point.game.store');

    // Balance ////////////////////////////////////////////////////////////////////////////////////////////////////////
    Route::get('balance', 'BalanceController@index')->name('balance');
    Route::get('balance/deposit', 'BalanceController@deposit')->name('balance.deposit');
    Route::get('balance/withdraw', 'BalanceController@withdraw')->name('balance.withdraw');
    Route::post('balance/withdraw/store', 'BalanceController@store')->name('balance.withdraw.store');

    // Transaction ////////////////////////////////////////////////////////////////////////////////////////////////////
    // Step 1 /////////////////////////////////////////////////////////////////////////////////////////////////////////
    Route::get('cart', 'TransactionController@cart')->name('cart'); // Order Info / Step 1 - Cart
    // Transaction Cart Information
    Route::post('cart/summary', 'TransactionController@summaryCart')->name('cart.summary'); // Order Summary / HTML
    // Transaction Cart - Action
    Route::post('cart/add', 'TransactionController@addCart')->name('cart.add'); // Add Order
    Route::post('cart/buy', 'TransactionController@buyCart')->name('cart.buy'); // Buy Order
    Route::post('cart/preorder', 'TransactionController@preorderCart')->name('cart.preorder'); // Add Order
    Route::post('cart/delete', 'TransactionController@deleteCart')->name('cart.delete'); // Order Delete
    // Transaction Cart - Update
    Route::post('cart/unit', 'TransactionController@unit')->name('cart.unit'); // Order Unit
    Route::post('cart/notes', 'TransactionController@notes')->name('cart.notes'); // Order Notes

    // Step 2 /////////////////////////////////////////////////////////////////////////////////////////////////////////
    Route::get('checkout', 'TransactionController@checkout')->name('checkout'); // Checkout / Step 2 - Checkout
    // Transaction Checkout
    Route::post('checkout/summary', 'TransactionController@summaryCheckout')->name('checkout.summary'); // Order Shipping / HTML
    // Transaction Checkout - Update
    Route::post('checkout/point', 'TransactionController@point')->name('checkout.point'); // Order Unit
    Route::post('checkout/shipping', 'TransactionController@shipping')->name('checkout.shipping'); // Order Shipping
    Route::post('checkout/address', 'TransactionController@address')->name('checkout.address'); // Order Address
    Route::post('checkout/promo', 'TransactionController@promo')->name('checkout.promo'); // Promo Code

    // Step 3 /////////////////////////////////////////////////////////////////////////////////////////////////////////
    Route::get('gateway', 'TransactionController@gateway')->name('gateway'); // Checkout / Step 3 - Gateway
    Route::post('gateway/choose', 'TransactionController@chooseGateway')->name('gateway.choose'); // Checkout / Step 3 - Gateway
    Route::post('balance/payment', 'TransactionController@balancePayment')->name('balance.payment'); // Balance Payment

    // Kredivo ///////////////////////////////////////////////////////////////////////////////////////////////////////
    Route::post('checkout-url', 'KredivoController@checkoutUrl')->name('kredivo.checkout'); // Checkout URL / Step 3 - Payment

    // Kredivo ///////////////////////////////////////////////////////////////////////////////////////////////////////
    Route::post('checkout-life-point', 'LifePointController@checkout')->name('lifepoint.checkout'); // Checkout URL / Step 3 - Payment

    // Others /////////////////////////////////////////////////////////////////////////////////////////////////////////
    // Check Checkout Timeout
    Route::get('cart/refresh', 'TransactionController@refresh')->name('cart.refresh');

    // Cancel Checkout - When After Checkout, Go Back to Cart Again
    Route::get('cart/timeout', 'TransactionController@timeout')->name('cart.timeout');

    // Tracking ///////////////////////////////////////////////////////////////////////////////////////////////////////
    Route::get('transaction/sell', 'TrackingController@listSell')->name('transaction.sell');
    Route::get('transaction/buy', 'TrackingController@listBuy')->name('transaction.buy');

    Route::get('transaction/sell/voucher', 'TrackingController@listSellVoucher')->name('transaction.sell.voucher');
    Route::get('transaction/buy/voucher', 'TrackingController@listBuyVoucher')->name('transaction.buy.voucher');
    Route::get('transaction/buy/digital', 'TrackingController@listBuyDigital')->name('transaction.buy.digital');

    Route::get('transaction/{id}', 'TrackingController@tracking')->name('transaction.detail'); // Order Detail
    Route::get('transaction/{id}/invoice', 'TrackingController@invoice')->name('transaction.invoice'); // Order Detail

    Route::get('balance/{id}', 'TrackingController@balance')->name('balance.transaction'); // Order Detail
    Route::get('point/topup/{id}', 'TrackingController@point')->name('point.transaction'); // Order Detail
    Route::get('transaction/{id}/voucher', 'TrackingController@voucher')->name('voucher.transaction'); // Order Detail

    Route::post('voucher/claim', 'TrackingController@claimVoucher')
        ->name('voucher.claim'); // Voucher Claim

    Route::post('transaction/approve', 'TrackingController@approveTransaction')
        ->name('transaction.approve'); // Transaction Approve
    Route::post('transaction/cancel', 'TrackingController@cancelTransaction')
        ->name('transaction.cancel'); // Transaction Cancel

    /*Route::get('transaction/cancel/{transaction}', 'TrackingController@cancelTransaction')
        ->name('transaction.cancel.redirect'); // Transaction Cancel Redirect
    /*Route::post('transaction/complain', 'TrackingController@complainTransaction')
        ->name('transaction.complain'); // Transaction Complain*/

    Route::post('transaction/confirm', 'TrackingController@confirmTransaction')
        ->name('transaction.confirm'); // Transaction Confirm
    Route::post('transaction/complete', 'TrackingController@completeTransaction')
        ->name('transaction.complete'); // Transaction Complete

    // Midtrans ///////////////////////////////////////////////////////////////////////////////////////////////////////
    Route::post('snaptoken', 'SnapController@token')->name('snap.token'); // SNAP Token / Step 3 - Payment
    Route::post('payment/success', 'SnapController@finish')->name('payment.success'); // SNAP Payment - Success
    Route::post('payment/pending', 'SnapController@finish')->name('payment.pending'); // SNAP Payment - Pending
    Route::post('payment/error', 'SnapController@finish')->name('payment.error'); // SNAP Payment - Error
    Route::post('payment', 'SnapController@finish')->name('payment'); // SNAP Payment

    // Digital ///////////////////////////////
    Route::post('digital/inquiry', "DigitalController@inquiry")->name("digital.inquiry");
    Route::get('digital/ppobtype', "DigitalController@ppob_type")->name("digital.ppob_type");
    Route::get('digital/{type}/checkout', 'DigitalController@checkout');
    Route::get('digital/{type}/thank-you/{inv}', 'DigitalController@thankYou')->name('digital.thank_you');
    Route::get('digital/{type}/invoice/{inv}', 'DigitalController@invoice')->name('digital.invoice');

    Route::get('promo/checkpromo', 'PromoController@check_promo');

    Route::get('payment-method', 'JsonController@paymentMethod');
});

// SNAP Notification
Route::post('payment/notification', 'SnapController@notification');

// Test ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
//Route::get('test', 'TestController@mail');
//Route::get('test', 'TestController@distance');
//Route::get('test/{id}', 'SnapController@test');

// Inhouse ////////////////////////////////////////////////////////////////////////////////////////////////////////////
Route::get('streaming/live', 'InhouseController@live')->name('streaming.live');
Route::get('streaming/listener', 'InhouseController@listener')->name('streaming.listener');

// CRON ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
Route::get('cron/transaction', 'TrackingController@cronTransaction')->name('cron.transaction');

// Profile ////////////////////////////////////////////////////////////////////////////////////////////////////////////
Route::get('u/{username}', 'ProfileController@index')->name('user.detail');

// Json Data //////////////////////////////////////////////////////////////////////////////////////////////////////////
Route::get('json/provinsi', 'JsonController@provinsi')->name('json.provinsi'); // Provinsi
Route::get('json/kabupaten', 'JsonController@kabupaten')->name('json.kabupaten'); // Kabupaten
Route::get('json/kecamatan', 'JsonController@kecamatan')->name('json.kecamatan'); // Kecamatan
Route::get('json/desa', 'JsonController@desa')->name('json.desa'); // Desa
Route::get('json/stats', 'JsonController@stats')->name('json.stats'); // Stats

// Referral ///////////////////////////////////////////////////////////////////////////////////////////////////////////
Route::get('referral/{username}', 'ReferralController@index')->name('referral');

Route::get('install/{username}', 'ReferralController@install')->name('referral.install');

// Product ////////////////////////////////////////////////////////////////////////////////////////////////////////////
Route::get('product/{slug}', 'ProductController@detail')->name('product.detail');

// Json Ongkir
Route::post('json/ongkir', 'ProductController@json')->name('json.ongkir');

// Search /////////////////////////////////////////////////////////////////////////////////////////////////////////////
Route::get('search', 'SearchController@index')->name('search');
Route::get('season/{slug}', 'SeasonController@index')->name('season');

// Load Locations
Route::get('kabupaten', 'SearchController@kabupaten');

// Load More Product
Route::get('load-more/product', 'SearchController@loadMoreProduct')->name('loadmore.load_data_product');
// Load More Shop
Route::get('load-more/shop', 'SearchController@loadMoreShop')->name('loadmore.load_data_shop');

// Activation /////////////////////////////////////////////////////////////////////////////////////////////////////////
Route::get('activation/{token}', 'HomeController@verify')->name('activation');

// Page ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
Route::get('page/{slug}', 'PageController@detail')->name('page.detail');
Route::get('page', 'PageController@index')->name('page');

// Category ///////////////////////////////////////////////////////////////////////////////////////////////////////////
Route::get('category/{category}', 'SearchController@index')->name('category.detail');
Route::get('category', 'CategoryController@index')->name('category');

// Social Auth ////////////////////////////////////////////////////////////////////////////////////////////////////////
// Facebook Auth
Route::get('facebook', 'Auth\RegisterController@redirectToProvider');

Route::get('facebook/callback', 'Auth\RegisterController@handleProviderCallback');

Route::get('facebook/{tokenprovider}', 'Auth\RegisterController@handleProviderCallback');

Route::post('facebook/{tokenprovider}/submit', 'Auth\RegisterController@submitProvider');

// Google Auth
Route::get('google', 'Auth\RegisterController@redirectToProvider');

Route::get('google/callback', 'Auth\RegisterController@handleProviderCallback');

Route::get('google/{tokenprovider}', 'Auth\RegisterController@handleProviderCallback');

Route::post('google/{tokenprovider}/submit', 'Auth\RegisterController@submitProvider');

// Home ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Route::get('/', 'HomeController@index')->name('home');
Route::get('/', 'HomeController@vue')->name('home');
Route::get('/luckydraw', 'LuckyDrawController@index');
// Digital
Route::get('digital', 'DigitalController@index')->name('digital');
Route::get('digital/pricelist', "DigitalController@priceList")->name("digital.pricelist");
Route::get('digital/banner/{slug}', 'DigitalController@bannerDetail');
Route::get('digital/{type}', 'DigitalController@index');