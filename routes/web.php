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
    Route::any('/market_data_events', [AdminController::class, 'market_data_events'])->name('market_data_events');
    Route::any('/create_market_data_events', [AdminController::class,'create_market_data_events'])->name('create_market_data_events');
    Route::any('/edit_market_data_events/{id}', [AdminController::class,'edit_market_data_events'])->name('edit_market_data_events');
    Route::get('/delete_events/{id}', [AdminController::class, 'delete_events'])->name('delete_events');

  Route::any('/about_management_profile', [AdminController::class,'about_management_profile'])->name('about_management_profile');
});
