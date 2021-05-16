<?php

use Illuminate\Support\Facades\Route;
use Wefabric\TokenLogin\Http\Controllers\LoginController;
use Wefabric\TokenLogin\TokenLogin;

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

Route::match(['get', 'post'],
    TokenLogin::make()->loginPath(),
    [
        LoginController::class,
        'login'
    ]
)->name('token-login.login');
