<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\admin\LoginController as AdminLoginController;
use App\Http\Controllers\admin\DashboardController as AdminDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

//user routes
Route::group(['prefix' => 'account'],function(){
    //guset middleware
    Route::group(['middleware'=>'guest'],function(){
        Route::get('login',[LoginController::class, 'index'])->name('account.login');
        Route::post('login',[LoginController::class, 'authenticate'])->name('account.logins');
        Route::get('register',[LoginController::class, 'register'])->name('account.register');
        Route::post('register',[LoginController::class, 'registerAuthenticate'])->name('account.registers');
    });

    //authenticated Middleware
    Route::group(['middleware'=>'auth'],function(){
        Route::get('logout',[LoginController::class,'logout'])->name('account.logout');
        Route::get('dashboard',[DashboardController::class,'index'])->name('account.dashboard');
    });
});

//Admin routes
Route::group(['prefix'=>'admin'],function(){
    Route::group(['middleware'=>'admin.guest'],function(){
        Route::get('login',[AdminLoginController::class,'index'])->name('admin.login');
        Route::post('login',[AdminLoginController::class,'adminLogin'])->name('admin.logins');
    });
    Route::group(['middleware'=>'admin.auth'],function(){
        Route::get('dashboard',[AdminDashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('logout',[AdminLoginController::class,'logout'])->name('admin.logout');
    });
});





