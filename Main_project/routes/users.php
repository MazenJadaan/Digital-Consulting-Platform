<?php

use App\Http\Controllers\User\Addfavoraite_and_rate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\Search_and_get_experts;
use App\Http\Controllers\User\User__appiontments;
use App\Http\Controllers\User\User_Auth;
use App\Http\Controllers\User\User_info;
use App\Models\User;

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

Route::post('user/register', [User_Auth::class, 'User_register']);
Route::post('user/login', [User_Auth::class, 'User_login']);

Route::group(['prefix' => 'user', 'middleware' => ['auth:user-api', 'scopes:user']], function () {
    Route::get('/logout', [User_Auth::class, 'User_logout']);
    Route::get('get_expert_by_consult/{consult_type}', [Search_and_get_experts::class, 'get_Experts_by_consulting']);
    Route::get('search_by_expert_name/{name}', [Search_and_get_experts::class, 'searchByExpertName']);
    Route::get('get_all_info_expert/{id}', [Search_and_get_experts::class, 'get_all_info_expert']);
    Route::get('add_to_fav_list/{id_expert}', [Addfavoraite_and_rate::class, 'Add_to_favorite']);
    Route::post('rate_expert/{id_expert}', [Addfavoraite_and_rate::class, 'rate_expert_by_user']);
    Route::get('get_wallet', [User_info::class, 'get_wallet_info']);
    Route::post('add_balance', [User_info::class, 'add_balance_to_wallet']);
    Route::get('get_favorite_list', [User_info::class, 'get_fav_list_of_user']);
    Route::get('Show_Avaliable_Researvations/{id_expert}', [User__appiontments::class, 'ShowAvaliableResearvations']);
    Route::get('Booked_Researvation/{id_researvations}', [User__appiontments::class, 'Booked_Researvation']);
});
