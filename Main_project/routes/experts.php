<?php

use App\Http\Controllers\Expert\Expert_appiontments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Search_and_get_experts;
use App\Http\Controllers\Expert\Expert_Auth;
use App\Http\Controllers\Expert\Expert_info;

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

Route::post('expert/register', [Expert_Auth::class, 'Expert_register']);
Route::post('expert/login', [Expert_Auth::class, 'Expert_login']);


Route::group(['prefix' => 'expert', 'middleware' => ['auth:expert-api', 'scopes:expert']], function () {
    // authenticated staff routes here 
    Route::post('/complete_info', [Expert_Auth::class, 'Complet_expert_info']);
    Route::get('/logout', [Expert_Auth::class, 'Expert_logout']);
    Route::get('/get_wallet_info', [Expert_info::class, 'get_wallet_info']);
    Route::get('/get_profile_information', [Expert_info::class, 'show_profile_of_expert']);
    Route::post('/update_profile_information', [Expert_info::class, 'update_profile_info']);
    Route::post('/complete_update_profile_information', [Expert_info::class, 'complete_update_profile_info']);
    Route::get('/show_all_Reservations', [Expert_appiontments::class, 'getAllReservations']);
    Route::post('add_reservation_by_expert', [Expert_appiontments::class, 'Add_Appointments']);
});
