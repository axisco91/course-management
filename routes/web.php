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
use App\Http\Controllers\TracingController;
use App\Http\Controllers\CompanyobservationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\CommandController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfitabilityController;
use App\Http\Controllers\TestsController;
use App\Http\Controllers\ChoreController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdvisorIncidenceController;
use App\Http\Controllers\CompanyIncidenceController;
use App\Http\Controllers\PotentialStudentController;
use App\Http\Controllers\PotentialCompanyController;
use App\Http\Controllers\TrainingContractController;
use App\Http\Controllers\TracingCommunicationController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\CertificationController;
use App\Http\Controllers\TrainingUnitController;
use App\Http\Controllers\TrainingContractIncidenceController;
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

Route::get('/', 'HomeController@index')->name('home');
