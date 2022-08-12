<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AdminController;
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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/',[LoginController::class,'loginForm'])->name('loginForm');
Route::post('loginCheck', [LoginController::class,'loginCheck'])->name('loginCheck');
Route::post('logout', [LoginController::class,'logout'])->name('logout');

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
    Route::any('/manage_group_account', [AdminController::class,'manage_group_account'])->name('manage_group_account');
    Route::any('/edit_group_account/{id}', [AdminController::class,'edit_group_account'])->name('edit_group_account');
    Route::any('/delete_group_account/{id}', [AdminController::class,'delete_group_account'])->name('delete_group_account');

    ///********************End Group Account***********************////
    Route::any('/about_management_profile', [AdminController::class,'about_management_profile'])->name('about_management_profile');
});
