<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\CenterController;
use App\Http\Controllers\CompanyActivityController;
use App\Http\Controllers\CompanyTypeController;
use App\Http\Controllers\PopulationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CnaeController;
use App\Http\Controllers\ProvinceController;
use App\Http\Controllers\AdvisorController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\AccionTypeController;
use App\Http\Controllers\ModalityController;
use App\Http\Controllers\ProfecionalAreaController;
use App\Http\Controllers\ProfecionalFamilyController;
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
     * Company Type Routes
     */
    Route::prefix('accion_types')->group(function() {
        Route::controller(AccionTypeController::class)->group(function(){
            Route::get('', 'index');
            Route::get('rest', 'restAccionTypes');
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
        Route::controller(ProvinceController::class)->group(function(){
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
        Route::controller(CnaeController::class)->group(function(){
            Route::get('', 'index');
            Route::get('rest', 'restCnaes');
            Route::get('store', 'store');
            Route::get('update', 'update');
            Route::get('edit/{id}', 'edit');
            Route::get('delete', 'destroy');
        });
    });
    /**
     * Users Routes
     */
    Route::prefix('users')->group(function() {
        Route::controller(UserController::class)->group(function(){
            Route::get('', 'index');
            Route::get('create', 'create');
            Route::get('rest', 'restUsers');
            Route::get('store', 'store');
            Route::get('update', 'updateUser');
            Route::get('edit/{id}', 'editUser');
            Route::get('delete', 'destroy');
        });
    });
    /**
     * Company Routes
     */
    Route::prefix('companies')->group(function() {
        Route::controller(CompanyController::class)->group(function(){
            Route::get('', 'index');
            Route::get('create', 'create');
            Route::get('rest', 'restCompany');
            Route::get('store', 'store');
            Route::get('update', 'updateCompany');
            Route::get('edit/{id}', 'editCompany');
            Route::get('delete', 'destroy');
        });
    });
    /**
     * Advisors Routes
     */
    Route::prefix('advisors')->group(function() {
        Route::controller(AdvisorController::class)->group(function(){
            Route::get('', 'index');
            Route::get('create', 'create');
            Route::get('rest', 'restAdvisor');
            Route::get('store', 'store');
            Route::get('update', 'updateAdvisor');
            Route::get('edit/{id}', 'editAdvisor');
            Route::get('delete', 'destroy');
        });
    });
    /**
     * Modalities Routes
     */
    Route::prefix('modalities')->group(function() {
        Route::controller(ModalityController::class)->group(function(){
            Route::get('', 'index');
            Route::get('rest', 'restModalities');
            Route::get('store', 'store');
            Route::get('update', 'update');
            Route::get('edit/{id}', 'edit');
            Route::get('delete', 'destroy');
        });
    });
    /**
     * Profecional Areas Routes
     */
    Route::prefix('profecional_areas')->group(function() {
        Route::controller(ProfecionalAreaController::class)->group(function(){
            Route::get('', 'index');
            Route::get('rest', 'restProfecionalAreas');
            Route::get('store', 'store');
            Route::get('update', 'update');
            Route::get('edit/{id}', 'edit');
            Route::get('delete', 'destroy');
        });
    });
    /**
     * Profecional Families Routes
     */
    Route::prefix('profecional_families')->group(function() {
        Route::controller(ProfecionalFamilyController::class)->group(function(){
            Route::get('', 'index');
            Route::get('rest', 'restProfecionalFamilies');
            Route::get('store', 'store');
            Route::get('update', 'update');
            Route::get('edit/{id}', 'edit');
            Route::get('delete', 'destroy');
        });
    });

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
});

Auth::routes(['register' => true]);
