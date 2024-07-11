<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('api_user_auth')->group(function(){
    Route::get('/processing_logger',[\App\Http\Controllers\ApiControllers\FirstVisitForm\FirstVisitFormController::class, 'processing_logger']);
    #region first visit form
    Route::get('/get_sub_dealer_information',[\App\Http\Controllers\ApiControllers\FirstVisitForm\FirstVisitFormController::class, 'get_sub_dealer_information']);
    Route::get('/get_first_visit_list',[\App\Http\Controllers\ApiControllers\FirstVisitForm\FirstVisitFormController::class, 'get_first_visit_list']);
    Route::get('/get_first_visit_form_data',[\App\Http\Controllers\ApiControllers\FirstVisitForm\FirstVisitFormController::class, 'get_first_visit_form_data']);
    Route::get('/get_device_form_data',[\App\Http\Controllers\ApiControllers\FirstVisitForm\FirstVisitFormController::class, 'get_device_form_data']);
    Route::get('/check_activation_line_validity',[\App\Http\Controllers\ApiControllers\FirstVisitForm\FirstVisitFormController::class, 'check_activation_line_validity']);
    Route::post('/save_first_visit_form',[\App\Http\Controllers\ApiControllers\FirstVisitForm\FirstVisitFormController::class, 'save_first_visit_form']);
    Route::post('/save_device_form',[\App\Http\Controllers\ApiControllers\FirstVisitForm\FirstVisitFormController::class, 'save_device_form']);
    #endregion

    #region follow up visit
    Route::get('/get_follow_up_visits_list',[\App\Http\Controllers\ApiControllers\FollowUpVisit\FollowUpVisitController::class, 'get_follow_up_visits_list']);
    Route::get('/verify_start_visit_request',[\App\Http\Controllers\ApiControllers\FollowUpVisit\FollowUpVisitController::class, 'verify_start_visit_request']);
    Route::get('/get_follow_up_visit_form_data',[\App\Http\Controllers\ApiControllers\FollowUpVisit\FollowUpVisitController::class, 'get_follow_up_visit_form_data']);
    Route::post('/save_follow_up_visit_form',[\App\Http\Controllers\ApiControllers\FollowUpVisit\FollowUpVisitController::class, 'save_follow_up_visit_form']);
    #endregion

});
Route::middleware('guest')->group(function(){
    Route::get('/generate_sub_dealer_report',[\App\Http\Controllers\ApiControllers\Util\UtilController::class, 'generate_sub_dealer_report']);
    Route::get('/login',[\App\Http\Controllers\ApiControllers\Auth\LoginController::class, 'login']);
    Route::get('/test',[\App\Http\Controllers\ApiControllers\FirstVisitForm\FirstVisitFormController::class, 'test']);
});

Route::POST('/testApi', function (Request $request){
    return $request->all();
});

