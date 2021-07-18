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
Route::get('/test', function (){
    return "ok teste";
});
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/company/get/user-company', [\App\Http\Controllers\CompanyController::class, 'getUserCompanyDetails']);

Route::get('/company/search', [\App\Http\Controllers\CompanyController::class, 'filterSearch']);
Route::get('company/search/count', [\App\Http\Controllers\CompanyController::class, 'filterCount']);
Route::resource('company', \App\Http\Controllers\CompanyController::class);


Route::post('vehicle/{id}', [\App\Http\Controllers\VehicleController::class, 'update']);
Route::resource('vehicle', \App\Http\Controllers\VehicleController::class);

Route::get('load/search/count', [\App\Http\Controllers\LoadController::class, 'filterCount']);
Route::get('/load/global/search', [\App\Http\Controllers\LoadController::class, 'filterSearch']);
Route::post('load/{id}', [\App\Http\Controllers\LoadController::class, 'update']);
Route::resource('load', \App\Http\Controllers\LoadController::class);
Route::resource('service', \App\Http\Controllers\ServiceController::class);

Route::post('/freight-journey/global/close-journey', [\App\Http\Controllers\FreightJourneyController::class, 'closeFreightJourney']);
Route::get('/freight-journey/global/search', [\App\Http\Controllers\FreightJourneyController::class, 'filterSearch']);
Route::get('freight-journey/search/count', [\App\Http\Controllers\FreightJourneyController::class, 'filterCount']);
Route::post('freight-journey/{id}', [\App\Http\Controllers\FreightJourneyController::class, 'update']);


Route::resource('freight-journey', \App\Http\Controllers\FreightJourneyController::class);
