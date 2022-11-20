<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ActionTypeController;
use App\Http\Controllers\API\AdvisorController;
use App\Http\Controllers\API\AdvisorIncidenceController;
use App\Http\Controllers\API\PruebaController;
use App\Http\Controllers\API\TeacherController;
use App\Http\Controllers\API\TrainingActionLevelController;

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

Route::post('login', [AuthController::class, 'signin']);
Route::post('register', [AuthController::class, 'signup']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();

});

Route::middleware('auth:sanctum')->group( function () {

    Route::get('pruebas', [PruebaController::class, 'index']);
});

/**
 * Action Types
 */
Route::prefix('action-types')->group(function() {
    Route::controller(ActionTypeController::class)->group(function(){
        Route::get('', 'getActionTypes');
        Route::get('create', 'create');
        Route::get('edit/{id}', 'edit');
        Route::get('destroy/{id}', 'destroy');
        Route::get('get/{id}', 'getActionType');
    });
});

/**
 * Advisors
 */
Route::prefix('advisors')->group(function() {
    Route::controller(AdvisorController::class)->group(function(){
        Route::get('', 'getAdvisors');
        Route::get('create', 'create');
        Route::get('edit/{id}', 'edit');
        Route::get('destroy/{id}', 'destroy');
        Route::get('get/{id}', 'getAdvisor');
        Route::get('convert-advisor/{id}', 'convertAdvisor');
        Route::get('check-nif/{nif}', 'checkNif');
    });
});

/**
 * Advisor Incidence
 */
Route::prefix('advisor-incidences')->group(function() {
    Route::controller(AdvisorIncidenceController::class)->group(function(){
        Route::get('', 'getAdvisorIncidences');
        Route::get('create', 'create');
        Route::get('edit/{id}', 'edit');
        Route::get('destroy/{id}', 'destroy');
    });
});

/**
 * Training Action Levels
 */
Route::prefix('training-action-levels')->group(function() {
    Route::controller(TrainingActionLevelController::class)->group(function(){
        Route::get('', 'getTrainingActionLevels');
        Route::get('create', 'create');
        Route::get('edit/{id}', 'edit');
        Route::get('destroy/{id}', 'destroy');
        Route::get('get/{id}', 'getTrainingActionLevel');
    });
});


