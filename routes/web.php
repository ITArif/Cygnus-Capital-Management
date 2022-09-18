<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AdminController;
//use App\Http\Controllers\WebController;
//use App\Http\Controllers\Auth\RegisterController;
//use App\Http\Controllers\Auth\ForgotPasswordController;
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

Route::get('my_mail','WebController@send_mail');

Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('config:clear');
    $exitCode = Artisan::call('cache:clear');
    $exitCode = Artisan::call('config:cache');
    return 'DONE'; //Return anything
});

Route::get('/',[LoginController::class,'loginForm'])->name('loginForm');
Route::post('loginCheck', [LoginController::class,'loginCheck'])->name('loginCheck');
Route::post('logout', [LoginController::class,'logout'])->name('logout');

/************************ Password reset ****************************/
Route::get('/password/reset','App\Http\Controllers\WebController@passwordReset')->name('passwordReset');
Route::post('/password/send_reset_email','App\Http\Controllers\WebController@sendPasswordResetEmail');
Route::get('/change-password/{id}','App\Http\Controllers\WebController@changePassword')->name('changePassword');
Route::post('/save-new-password/{id}','App\Http\Controllers\WebController@saveNewPassword');
////********************Reset password & register user**********************////
Route::get('register/verify/{token}', 'App\Http\Controllers\Auth\RegisterController@verify');
Route::get('register/reset-password/{token}', 'App\Http\Controllers\Auth\ForgotPasswordController@resetPassword');
///***************************************pass Create*****************///
Route::get('/passCreate/{id}', 'App\Http\Controllers\WebController@passwordCreate');
Route::post('/create_user_password/{id}', 'App\Http\Controllers\WebController@createUserPassword');

Route::middleware('admin')->group(function () {
    Route::get('/admin-dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::any('/user_analytics', [AdminController::class, 'user_analytics'])->name('user_analytics');
    Route::get('/delete-user-analytics/{id}', [AdminController::class,'delete_user_analytics'])->name('delete_user_analytics');
    Route::match(['get','post'],'/market_data_news', [AdminController::class, 'market_data_news'])->name('market_data_news');
    Route::any('/add_news_data', [AdminController::class, 'add_news_data'])->name('add_news_data');
    ////*************Market Events*******************
    Route::any('/market_data_events', [AdminController::class, 'market_data_events'])->name('market_data_events');
    Route::any('/create_market_data_events', [AdminController::class,'create_market_data_events'])->name('create_market_data_events');
    Route::any('/edit_market_data_events/{id}', [AdminController::class,'edit_market_data_events'])->name('edit_market_data_events');
    Route::get('/delete_events/{id}', [AdminController::class, 'delete_events'])->name('delete_events');
    ////*****************End Market Events********************************

    ///******************Market Category***********************************////
    Route::any('/market_category', [AdminController::class, 'market_category'])->name('market_category');
    Route::any('/create_market_category', [AdminController::class,'create_market_category'])->name('create_market_category');
    Route::any('/edit_market_category', [AdminController::class,'edit_market_category'])->name('edit_market_category');
    Route::any('/delete_market_category/{id}', [AdminController::class,'delete_market_category'])->name('delete_market_category');
    ///******************End Market Category********************************////

    ////*******************Industry Data********************************/////
    Route::any('/industry_data', [AdminController::class,'industry_data'])->name('industry_data');
    Route::any('/edit_industry_data/{id}', [AdminController::class,'edit_industry_data'])->name('edit_industry_data');
    Route::any('/create_industry_data', [AdminController::class,'create_industry_data'])->name('create_industry_data');
    Route::any('/delete_industry_data/{id}', [AdminController::class,'delete_industry_data'])->name('delete_industry_data');
    /////******************End industry*******************************/////

    ////****************Company Data*********************************////
    Route::any('/company_data', [AdminController::class,'company_data'])->name('company_data');
    Route::any('/create_company_data', [AdminController::class,'create_company_data'])->name('create_company_data');
    Route::any('edit_company_data/{id}', [AdminController::class,'edit_company_data'])->name('edit_company_data');
    Route::any('/delete_company_data/{id}', [AdminController::class,'delete_company_data'])->name('delete_company_data');
   /////****************End Company Data*********************************////
    
    ////*******************Group Account**************************////
    Route::any('/create_group_account', [AdminController::class,'create_group_account'])->name('create_group_account');
    Route::any('/manage_group_account', [AdminController::class,'manage_group_account'])->name('manage_group_account');
    Route::any('/edit_group_account/{id}', [AdminController::class,'edit_group_account'])->name('edit_group_account');
    Route::any('/delete_group_account/{id}', [AdminController::class,'delete_group_account'])->name('delete_group_account');
    ///********************End Group Account***********************////

    ////*******************Circuit Breker****************************///////
    Route::any('/circuit_breaker_data', [AdminController::class,'circuit_breaker_data'])->name('circuit_breaker_data');
    Route::any('/delete_circuit_breaker_data/{id}', [AdminController::class,'delete_circuit_breaker_data'])->name('delete_circuit_breaker_data');
    Route::any('/circuit_breaker_data_cse', [AdminController::class,'circuit_breaker_data_cse'])->name('circuit_breaker_data_cse');
    Route::any('/delete_circuit_breaker_data_cse/{id}', [AdminController::class,'delete_circuit_breaker_data_cse'])->name('delete_circuit_breaker_data_cse');
    ////*******************End Circuit Breker****************************///////

    ////*******************Stock Order***********************************///////
    Route::any('/all_stock_order', [AdminController::class,'all_stock_order'])->name('all_stock_order');
    Route::any('/all_stock_order_data', [AdminController::class,'all_stock_order_data'])->name('all_stock_order_data');
    ////*****************End Stock Order*********************************/////

    ////*********************Stock Order Report*****************************/////
    Route::any('/stock_order_report', [AdminController::class,'stock_order_report'])->name('stock_order_report');
    ////*********************End Stock Order Report*****************************/////
    ////**********************Update Cash Limit*********************************////
    Route::any('/update_cash_limit', [AdminController::class,'update_cash_limit'])->name('update_cash_limit');
    ///********************Withdraw Request***********************************////
    Route::any('/all_user_withdrawal', [AdminController::class,'all_user_withdrawal'])->name('all_user_withdrawal');
    Route::any('/view_withdraw_print/{id}', [AdminController::class,'view_withdraw_print'])->name('view_withdraw_print');
    Route::any('/view_withdraw_print/{id}', [AdminController::class,'view_withdraw_print'])->name('view_withdraw_print');
    ///********************End Withdraw Request***********************************//
    ////*********************Deposite Request********************************/////
    Route::any('/all_user_deposit', [AdminController::class,'all_user_deposit'])->name('all_user_deposit');
    ////*********************End Deposite Request********************************/////

    ////********************New Bo Account***************************************////
    Route::any('/new_bo_account', [AdminController::class,'new_bo_account'])->name('new_bo_account');
    Route::any('/edit_bo_account/{id}', [AdminController::class,'edit_bo_account'])->name('edit_bo_account');
    Route::any('/delete_bo_account/{id}', [AdminController::class,'delete_bo_account'])->name('delete_bo_account');
    Route::any('/view_bo_account/{id}', [AdminController::class,'view_bo_account'])->name('view_bo_account');
    Route::any('/export_bo_account/{id}', [AdminController::class,'export_bo_account'])->name('export_bo_account');
    ////********************End New Bo Account**********************************////

    ////*********************** ipo for Admin ****************************///////
    Route::get('/IPO', [AdminController::class,'IPO'])->name('IPO');
    Route::get('/ipo_setting',[AdminController::class,'ipo_setting'])->name('ipo_setting');
    Route::post('/save_ipo_data',[AdminController::class,'saveIpoData'])->name('save_ipo_data');
    Route::any('/ipo_status_change/{id}',[AdminController::class,'ipoStatusChange']);

    Route::any('/ipo_application',[AdminController::class,'ipo_application'])->name('ipo_application');
    Route::POST('/save_change_ipo_status',[AdminController::class,'save_change_ipo_status'])->name('save_change_ipo_status');

   ///*************Manage bo Account***********************
    Route::any('/manage_bo_account', [AdminController::class,'manage_bo_account'])->name('manage_bo_account');
    Route::any('/edit_bo_account/{id}', [AdminController::class,'edit_bo_account'])->name('edit_bo_account');
    ///*****************End Bo Account****************************///
    ////*****************User Account*************************///
    Route::any('/manage_user_account', [AdminController::class,'manage_user_account'])->name('manage_user_account');
    Route::any('/edit_user/{id}', [AdminController::class,'edit_user'])->name('edit_user');
    Route::get('ban_user/{id}', [AdminController::class,'ban_user']);
    Route::get('unban_user/{id}', [AdminController::class,'unban_user']);
    /********************* Send new Email for create Password *************/
   Route::get('/send-new-email/{id}',[AdminController::class,'sendNewEmail']);
  //////**************************Subscriber list********************************/////
   Route::any('subscribers_list', [AdminController::class,'subscribers_list'])->name('subscribers_list');
});

Route::middleware('client')->group(function () {
    Route::get('/client-dashboard', [App\Http\Controllers\Client\DashboardController::class, 'index'])->name('client.dashboard');
});

////***********************User & Client **************************////
Route::post('user_registration', 'App\Http\Controllers\WebController@user_registration');
Route::post('save_registration', 'App\Http\Controllers\WebController@save_registration');
    