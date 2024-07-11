<?php

use App\Helper;
use App\Http\Controllers\CardsController;
use App\Http\Controllers\feedbackController;
use App\Http\Controllers\ReviewController;
use App\Models\User;
use App\program_array;
use App\Scheduler;
use App\Utilities\egsrequeststructure;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Main\transactionController;

use \App\Http\Controllers\Forms\formsController;
use \App\Http\Controllers\Forms\requestsController;
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
Route::middleware(['guest'])->group(function () {
    Route::get('/', function () {
        return view('auth.login');
    })->name('login');
    Route::post('/login', 'App\Http\Controllers\Auth\LoginController@login')->name('postLogin');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/logout', 'App\Http\Controllers\Auth\LoginController@logout')->name('logout');
    Route::get('/home', 'App\Http\Controllers\Base\BaseController@index')->name('base');

    Route::Resource("/reviews",ReviewController::class);
    Route::Resource("/feedbacks",FeedbackController::class);
    Route::post("/file-upload",[ReviewController::class,'file_upload']);

    Route::resource('/cards',CardsController::class);

    /////////////////////////////////////////////////////////////////////////////////////
    #users region
    Route::get('/users', 'App\Http\Controllers\Main\userController@index')->name('users');
    Route::post('/users', 'App\Http\Controllers\Main\userController@store')->name('save_user');
    Route::delete('/users/{id}', 'App\Http\Controllers\Main\userController@destroy')->name('delete_user');
    Route::put('users/{id}', 'App\Http\Controllers\Main\userController@update')->name('updateUserStatus');
    Route::get('/get_users', 'App\Http\Controllers\Main\userController@get_users')->name('get_users');
    Route::get('/get_user_by_id/{id}', 'App\Http\Controllers\Main\userController@get_user_by_id')->name('get_user_by_id');
    Route::get('/get_modules', 'App\Http\Controllers\Main\userController@get_modules')->name('get_modules');
    Route::get('/export_users', 'App\Http\Controllers\Main\userController@export_users')->name('export_users');
    #endregion

});

