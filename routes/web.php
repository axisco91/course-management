<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\CenterController;
use App\Http\Controllers\CompanyActivityController;
use App\Http\Controllers\CompanyTypeController;
use App\Http\Controllers\PopulationController;
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

Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return view('welcome');
    });

    /**
     * Teachers Routes
     */
    Route::prefix('teachers')->group(function() {
        Route::controller(TeacherController::class)->group(function(){
            Route::get('', 'index');
            Route::get('rest', 'restTeachers');
            Route::get('create', 'create');
            Route::get('store', 'store');
            Route::get('update_teacher', 'updateTeacher');
            Route::get('edit_teacher/{id}', 'editTeacher');
            Route::get('delete', 'destroy');
        });
    });
    /**
     * Center Routes
     */
    Route::prefix('centers')->group(function() {
        Route::controller(CenterController::class)->group(function(){
            Route::get('', 'index');
            Route::get('rest', 'restCenters');
            Route::get('create', 'create');
            Route::get('store', 'store');
            Route::get('update', 'update');
            Route::get('edit/{id}', 'edit');
            Route::get('delete', 'destroy');
        });
    });
    /**
     * Company Activities Routes
     */
    Route::prefix('companies_activities')->group(function() {
        Route::controller(CompanyActivityController::class)->group(function(){
            Route::get('', 'index');
            Route::get('rest', 'restCompaniesActivities');
            Route::get('store', 'store');
            Route::get('update', 'update');
            Route::get('edit/{id}', 'edit');
            Route::get('delete', 'destroy');
        });
    });
    /**
     * Company Type Routes
     */
    Route::prefix('company_types')->group(function() {
        Route::controller(CompanyTypeController::class)->group(function(){
            Route::get('', 'index');
            Route::get('rest', 'restCompanyTypes');
            Route::get('store', 'store');
            Route::get('update', 'update');
            Route::get('edit/{id}', 'edit');
            Route::get('delete', 'destroy');
        });
    });
    /**
     * Population Routes
     */
    Route::prefix('populations')->group(function() {
        Route::controller(PopulationController::class)->group(function(){
            Route::get('', 'index');
            Route::get('rest', 'restPopulations');
            Route::get('store', 'store');
            Route::get('update', 'update');
            Route::get('edit/{id}', 'edit');
            Route::get('delete', 'destroy');
        });
    });
    /**
     * Province Routes
     */
    Route::prefix('provinces')->group(function() {
        Route::controller(PopulationController::class)->group(function(){
            Route::get('', 'index');
            Route::get('rest', 'restProvinces');
            Route::get('store', 'store');
            Route::get('update', 'update');
            Route::get('edit/{id}', 'edit');
            Route::get('delete', 'destroy');
        });
    });
    /**
     * Cnae Routes
     */
    Route::prefix('cnaes')->group(function() {
        Route::controller(PopulationController::class)->group(function(){
            Route::get('', 'index');
            Route::get('rest', 'restCnaes');
            Route::get('store', 'store');
            Route::get('update', 'update');
            Route::get('edit/{id}', 'edit');
            Route::get('delete', 'destroy');
        });
    });

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
});

Auth::routes(['register' => true]);
