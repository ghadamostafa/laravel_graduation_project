<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
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

Route::get('/', 'IndexController@index')->name('website.index');
// balance
Route::get('/designer/{user}/balance','BalanceController@show')->name('balance');
Route::post('/designer/{user}/balance','BalanceController@request')->name('balance.request');
// payement
Route::get('/checkout', 'CheckoutController@checkout')->name('checkoutPage');
Route::post('/checkout/credit', 'CompanyPaymentController@credit_card_checkout')->name('pay.credit.card');
Route::get('/checkout/credit', 'CompanyPaymentController@show_payment_form')->name('pay.credit.card.form');

// cart routes
Route::get('/result', 'CartController@result')->name('website.result');
Route::get('/cart', 'CartController@cart')->name('website.cart');
Route::post('add-to-cart', 'CartController@addToCart')->name('add-to-cart');
Route::post('remove-from-cart', 'CartController@removeFromCart')->name('remove-from-cart');
Route::get('load-cart-data', 'CartController@loadCartData')->name('load-cart');
Route::get('empty-cart', 'CartController@emptyCart')->name('empty-cart');


Auth::routes(['verify' => true]);

Route::get('/home', 'HomeController@index')->name('home');

//Designs
// Route::get('/notification/MarkAsRead', 'IndexController@MarkAsRead');

Route::get('/search', 'DesignsController@search')->name('search');
Route::post('/designs/vote', 'DesignsController@vote')->middleware('check-role:user');
Route::resource('designs', 'DesignsController');
// Route::get('design/category', 'DesignsController@category')->name('category');

//comments
Route::post('designs/{design:id}/comments', 'CommentsController@store');
Route::post('comments/{id}/commentReply', 'CommentsController@commentReply');

// socialite login routes
Route::get('auth/social', 'Auth\LoginController@show')->name('social.login');
Route::get('oauth/{driver}', 'Auth\LoginController@redirectToProvider')->name('social.oauth');
Route::get('oauth/{driver}/callback', 'Auth\LoginController@handleProviderCallback')->name('social.callback');

Route::get('/createAccount', 'AllUsersRegisterController@createAccount')->name('createAccount');
Route::get('/register/{role}','AllUsersRegisterController@RegistrationForm')
->where('role','admin|user|designer|company')->name('registeration.form');
Route::post('/register/user','AllUsersRegisterController@register')->name('user.registeration');
Route::post('/register/admin','AllUsersRegisterController@register')->name('admin.registeration');
Route::post('/register/company','AllUsersRegisterController@register')->name('company.registeration');
Route::post('/register/designer','AllUsersRegisterController@register')->name('designer.registeration');
Route::get('company/{user}/shop','CompanyController@shop')->name('company.shop');
Route::get('company/design/create','CompanyController@show_create_design_form')->name('company_design.create');
Route::post('company/design/create','CompanyController@create_design')->name('company_design.store');
Route::resource('company', 'CompanyController')->except([
    'create', 'store','update','edit'
]);
Route::resource('user','AllUsersUpdateController')->only([
    'update','edit'
]);
Route::post('/featuredesign','DesignerController@featuredesign')->name('featuredesign');
Route::post('/deletefeaturedesign','DesignerController@featuredesign')->name('deletefeaturedesign');
// Route::get('/featuredesign/{design}','DesignerController@featuredesign')->name('featuredesign');
Route::post('savelikes', 'DesignerController@savelikes')->name('savelikes');
Route::resource('designer', 'DesignerController')->except([
    'create','store'
]);


// Routes for both tags and material resources
Route::resource('user','ProfileController')->only([
    'create','store'
    ]);
    
    //paypal routes
    Route::get('paypal/ec-checkout', 'PayPalController@getExpressCheckout')->name('checkout');
    Route::get('paypal/ec-checkout-success', 'PayPalController@getExpressCheckoutSuccess')->name('paypal.success');
    Route::get('paypal/ec-checkout-cancel', 'PayPalController@getExpressCheckoutCancel')->name('paypal.cancel');
    
    
    // user profile 
    Route::resource('user', 'UserProfileController')->except([
        'create', 'store','update','edit'
        ]);
        
        
        // Admin dashboard
    Route::group([ 'prefix' => 'admin'], function(){
        
        Route::get('login', 'AdminAuthController@login')->name('admin-login');
        Route::post('do-login', 'AdminAuthController@adminLogin')->name('adminDologin');
        
        Route::group(['middleware' => 'admin'], function(){
            Route::get('/', 'AdminController@list')->name('dashboard');
            Route::post('logout', 'AdminAuthController@logMeOut')->name('admin.logout');
            Route::resource('tag', 'TagController');
            Route::resource('material', 'MaterialController');
            Route::get('design-chart', 'AdminController@likesChart')->name('likes');
            Route::get('{role}/{state}','AdminController@list_users')->where(['role'=>'designer|company','state'=>'accepted|rejected|pending'])->name('list_users');
            Route::get('design/{state}','AdminController@list_designs')->where(['state'=>'accepted|rejected|pending'])->name('list_designs');
            Route::post('design/{state}','AdminController@change_design_verification')->where(['state'=>'accepted|rejected|pending']);
            Route::post('{role}/{state}','AdminController@change_user_verification')->where(['role'=>'designer|company','state'=>'accepted|rejected|pending']);
            Route::get('notification/markasread','AdminController@mark_as_read');
            Route::get('user/document/{user}','AdminController@view_user_document')->name('admin.view_user_document');
            Route::get('design/{design}/document','AdminController@view_design_document')->name('admin.view_design_document');
            Route::get('charts/payment','AdminController@view_payment_chart')->name('admin.view_payment_chart');
            Route::get('designers', 'AdminController@listDesigners')->name('designers.list');
            Route::get('designers/withdraws', 'AdminController@listWithdrawRequests')->name('designers.withdraw.requests');
            Route::post('designers/withdraws', 'AdminController@withdraws_filter')->name('designers.withdraw.filter');
            Route::post('designers/withdraws/changestate', 'AdminController@change_withdraw_state')->name('designers.withdraw.changestate');
            Route::get('designers/chart/{id}', 'AdminController@designerChart')->name('designer.chart');
            Route::get('designs', 'AdminController@listDesigns')->name('designs.list');
            Route::get('designs/chart/{id}', 'AdminController@designChart')->name('design.chart');
            Route::get('livesearch','AdminController@action')->name('livesearch');

            Route::resource('admin', 'AdminController')->only([
                'index','create','store','destroy'
                ]);

    });
});
Route::view('/about', 'about');

