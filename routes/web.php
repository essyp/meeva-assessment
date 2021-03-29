<?php

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

Route::view('/','index');

Route::group(['middleware' => ['web'], 'namespace' => 'App\Http\Controllers', 'prefix' => 'admin/'], function () {
    Route::post('login', 'AuthController@authenticate');
    Route::view('login','admin/login');
    Route::get('logout', 'AuthController@logout');
});

Route::group(['middleware' => ['admin'], 'namespace' => 'App\Http\Controllers', 'prefix' => 'admin/'], function () {
    Route::get('', 'AdminController@index');
    Route::get('dashboard', 'AdminController@index');
    Route::get('admin-users', 'AdminController@allAdminUsers');
    Route::post('create-admin-user', 'AdminController@createAdmin');
    Route::post('update-admin-user', 'AdminController@updateAdmin');
    Route::post('update-admin-role', 'AdminController@updateAdminRole');
    Route::post('admin-status', 'AdminController@adminStatus');
    Route::get('newsletter-subscriptions', 'AdminController@getNewsletterSubscriptions');
    Route::get('account-update', 'AdminController@getProfile');
    Route::get('change-password', 'AdminController@getChangePassword');
    Route::post('update-password', 'AdminController@changePassword');
    Route::post('reset-admin-password', 'AdminController@resetPassword');
    Route::get('log', 'AdminController@getLog');
});

Route::group(['middleware' => ['web'], 'namespace' => 'App\Http\Controllers'], function () {
    Route::get('unsubscribe/{email}', 'HomeController@unSubscribe');
});