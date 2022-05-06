<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\CenterController;
use App\Http\Controllers\CompanyActivityController;
use App\Http\Controllers\CompanyTypeController;
use App\Http\Controllers\PopulationController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\CnaeController;
use App\Http\Controllers\ProvinceController;
use App\Http\Controllers\AdvisorController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ActionTypeController;
use App\Http\Controllers\ModalityController;
use App\Http\Controllers\ProfessionalAreaController;
use App\Http\Controllers\ProfessionalFamilyController;
use App\Http\Controllers\CourseTypeController;
use App\Http\Controllers\ProfessionalCategoryController;
use App\Http\Controllers\TrainingActionController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\WebPlatformController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\LevelStudyController;
use App\Http\Controllers\CourseProviderController;
use App\Http\Controllers\TrainingActionLevelController;
use App\Http\Controllers\TrainingActionGroupController;
use App\Http\Controllers\TutoringController;
use App\Http\Controllers\CourseStatusController;
use App\Http\Controllers\Tracingcontroller;
use App\Http\Controllers\CompanyobservationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\CommandController;

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
     * Companies Routes
     */
    Route::prefix('companies')->group(function() {
        Route::controller(CompanyController::class)->group(function(){
            Route::get('', 'index');
            Route::get('edit/{id}', 'edit');
        });
    });
    /**
     * Companies Routes
     */
    Route::prefix('advisors')->group(function() {
        Route::controller(AdvisorController::class)->group(function(){
            Route::get('', 'index');
            Route::get('edit/{id}', 'edit');
        });
    });
    /**
     * Students Routes
     */
    Route::prefix('students')->group(function() {
        Route::controller(StudentController::class)->group(function(){
            Route::get('', 'index');
            Route::get('edit/{id}', 'edit');
        });
    });
    /**
     * Teachers Routes
     */
    Route::prefix('teachers')->group(function() {
        Route::controller(TeacherController::class)->group(function(){
            Route::get('', 'index');
            Route::get('edit/{id}', 'edit');
        });
    });
    /**
     * Centers Routes
     */
    Route::prefix('centers')->group(function() {
        Route::controller(CenterController::class)->group(function(){
            Route::get('', 'index');
        });
    });
    /**
     * Training Actions Routes
     */
    Route::prefix('training-actions')->group(function() {
        Route::controller(TrainingActionController::class)->group(function(){
            Route::get('', 'index');
            Route::get('edit/{id}', 'edit');
        });
    });
    /**
     * Provider Routes
     */
    Route::prefix('providers')->group(function() {
        Route::controller(ProviderController::class)->group(function(){
            Route::get('', 'index');
            Route::get('edit/{id}', 'edit');
        });
    });
    /**
     * Courses Routes
     */
    Route::prefix('courses')->group(function() {
        Route::controller(CourseController::class)->group(function(){
            Route::get('', 'index');
            Route::get('edit/{id}', 'edit');
        });
    });
    /**
     * Training Actions Routes
     */
    Route::prefix('training_actions')->group(function() {
        Route::controller(TrainingActionController::class)->group(function(){
            Route::get('', 'index');
        });
    });
    /**
     * Users User
     */
    Route::prefix('users')->group(function() {
        Route::controller(UserController::class)->group(function(){
            Route::get('', 'index');
        });
    });
    /**
     * Roles Routes
     */
    Route::prefix('roles')->group(function() {
        Route::controller(RoleController::class)->group(function(){
            Route::get('', 'index');
        });
    });
    /**
     * Roles Routes
     */
    Route::prefix('billings')->group(function() {
        Route::controller(BillingController::class)->group(function(){
            Route::get('', 'index');
            Route::get('edit/{id}', 'edit');
        });
    });

    Route::prefix('commands')->group(function() {
        Route::controller(CommandController::class)->group(function(){
            Route::get('', 'index');
        });
    });

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
});
Route::get('/register', App\Http\Livewire\Auth\Register::class);
Auth::routes(['register' => true]);

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

//Route Hooks - Do not delete//
	Route::view('profitabilities', 'livewire.profitabilities.index')->middleware('auth');
	Route::view('training_action_levels', 'livewire.training-action-levels.index')->middleware('auth');
	Route::view('level_studies', 'livewire.level-studies.index')->middleware('auth');
	Route::view('areas_teacher_areas', 'livewire.areas-teacher-areas.index')->middleware('auth');
	Route::view('teacher_areas', 'livewire.teacher-areas.index')->middleware('auth');
	Route::view('bonuses', 'livewire.bonuses.index')->middleware('auth');
	Route::view('payments', 'livewire.payments.index')->middleware('auth');
	Route::view('registrations', 'livewire.registrations.index')->middleware('auth');
	Route::view('tracings', 'livewire.tracings.index')->middleware('auth');
	Route::view('chores', 'livewire.chores.index')->middleware('auth');
	Route::view('professional_categories', 'livewire.professional-categories.index')->middleware('auth');
	Route::view('course_types', 'livewire.course-types.index')->middleware('auth');
	Route::view('course_statuses', 'livewire.course-statuses.index')->middleware('auth');
	Route::view('tutorings', 'livewire.tutorings.index')->middleware('auth');
	Route::view('modalities', 'livewire.modalities.index')->middleware('auth');
	Route::view('professional_areas', 'livewire.professional-areas.index')->middleware('auth');
	Route::view('professional_families', 'livewire.professional-families.index')->middleware('auth');
	Route::view('action_types', 'livewire.action-types.index')->middleware('auth');
	Route::view('course_providers', 'livewire.course-providers.index')->middleware('auth');
	Route::view('tutoring', 'livewire.tutoring.index')->middleware('auth');
	Route::view('training_action_groups', 'livewire.training-action-groups.index')->middleware('auth');
	Route::view('company_observations', 'livewire.company-observations.index')->middleware('auth');
	Route::view('web_platforms', 'livewire.web-platforms.index')->middleware('auth');
	Route::view('company_activities', 'livewire.company-activities.index')->middleware('auth');
	Route::view('company_types', 'livewire.company-types.index')->middleware('auth');
	Route::view('provinces', 'livewire.provinces.index')->middleware('auth');
	Route::view('cnaes', 'livewire.cnaes.index')->middleware('auth');
