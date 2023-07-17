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
use App\Http\Controllers\API\TrainingActionGroupController;
use App\Http\Controllers\API\StudentController;
use App\Http\Controllers\API\CompanyController;
use App\Http\Controllers\API\LevelStudyController;
use App\Http\Controllers\API\QuoteGroupController;
use App\Http\Controllers\API\ProfessionalCategoryController;
use App\Http\Controllers\API\ProvinceController;
use App\Http\Controllers\API\TeacherAreaController;
use App\Http\Controllers\API\CompanyActivityController;
use App\Http\Controllers\API\CompanyTypeController;
use App\Http\Controllers\API\CnaeController;
use App\Http\Controllers\API\CollaboratorController;
use App\Http\Controllers\API\TrainingActionController;
use App\Http\Controllers\API\ProfessionalFamilyController;
use App\Http\Controllers\API\ModalityController;
use App\Http\Controllers\API\TutoringController;
use App\Http\Controllers\API\WebPlatformController;
use App\Http\Controllers\API\ProfessionalAreaController;
use App\Http\Controllers\API\ProviderController;
use App\Http\Controllers\API\CompanyObservationController;
use App\Http\Controllers\API\CertificationController;
use App\Http\Controllers\API\ModuleController;
use App\Http\Controllers\API\TrainingUnitController;
use App\Http\Controllers\API\CourseStatusController;
use App\Http\Controllers\API\CourseTypeController;
use App\Http\Controllers\API\ExcludedDayTypeController;
use App\Http\Controllers\API\IncidenceTypeController;
use App\Http\Controllers\API\OccupationController;
use App\Http\Controllers\API\OnLeaveController;
use App\Http\Controllers\API\PaymentController;
use App\Http\Controllers\API\PotentialCompanyController;
use App\Http\Controllers\API\PotentialStudentController;
use App\Http\Controllers\API\ProfitabilityController;
use App\Http\Controllers\API\TracingController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\TrainingContractController;
use App\Http\Controllers\API\CenterController;
use App\Http\Controllers\API\CourseController;
use App\Http\Controllers\API\ChoreController;
use App\Http\Controllers\API\BillController;
use App\Http\Controllers\Api\CompanyIncidenceController;
use App\Http\Controllers\API\CreditController;
use App\Http\Controllers\API\TrainingContractStatusController;
use App\Http\Controllers\API\TrainingContractIncidenceController;
use App\Http\Controllers\API\RegistrationController;
use App\Http\Controllers\API\TrainingContractElementController;
use App\Http\Controllers\API\ExamTutorialController;
use App\Http\Controllers\API\BankHolidayGroupController;
use App\Http\Controllers\API\TrainingContractExcludedDayController;
use App\Http\Controllers\API\TrainingContractBonusController;
use App\Http\Controllers\API\CertificationElementController;
use App\Http\Controllers\API\StatisticController;
use App\Http\Controllers\API\TrainingContractBillController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\CourseOriginController;
use App\Http\Controllers\API\NacionalFestivalController;
use App\Http\Controllers\API\ProvinceFestivalController;
use App\Http\Controllers\API\PopulationFestivalController;
use App\Http\Controllers\API\PopulationController;
use App\Http\Controllers\API\TrainingContractFestivalController;
use App\Http\Controllers\API\PermissionController;
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
Route::post('logout', [AuthController::class, 'logout']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();

});

Route::middleware('auth:sanctum')->group( function () {

    Route::get('pruebas', [PruebaController::class, 'index']);

    /**
     * Estadisticas
     */
    Route::prefix('statistics')->group(function() {
        Route::controller(StatisticController::class)->group(function(){
            Route::get('total_registrations', 'totalRegistrations');
            Route::get('get_chores_welcome_messages', 'getChoresWelcomeMessages');
            Route::get('get_number_courses', 'getNumberCourses');
            Route::get('get_number_courses_per_month', 'getNumberCoursesPerMonth');
        });
    });

    /**
     * Permisos
     */
    Route::prefix('permissions')->group(function() {
        Route::controller(PermissionController::class)->group(function(){
            Route::get('', 'permissions');
        });
    });

    /**
     * Roles
     */
    Route::prefix('roles')->group(function() {
        Route::controller(RoleController::class)->group(function(){
            Route::get('', 'getRoles');
            Route::get('get/{id}', 'getRole');
            Route::post('create', 'create');
            Route::post('edit/{id}', 'edit');
        });
    });

    /**
     * Alumnos
     */
    Route::prefix('students')->group(function() {
        Route::controller(StudentController::class)->group(function(){
            Route::get('', 'getStudents');
            Route::post('create', 'create');
            Route::post('edit/{id}', 'edit');
            Route::get('destroy/{id}', 'destroy');
            Route::get('get/{id}', 'getStudent');
            Route::post('check_dni', 'checkDni');
            Route::get('courses/{id}', 'getStudentsCourses');
            Route::get('count', 'countStudents');
            Route::get('csv', 'studentsCSV');
            Route::get('active', 'getActiveStudents');
        });
    });

    /**
     * Tipos Acciones
     */
    Route::prefix('action-types')->group(function() {
        Route::controller(ActionTypeController::class)->group(function(){
            Route::get('', 'getActionTypes');
            Route::post('create', 'create');
            Route::post('edit/{id}', 'edit');
            Route::get('destroy/{id}', 'destroy');
            Route::get('get/{id}', 'getActionType');
            Route::get('count', 'count');
        });
    });

    /**
     * Asesorías
     */
    Route::prefix('advisors')->group(function() {
        Route::controller(AdvisorController::class)->group(function(){
            Route::get('', 'advisors');
            Route::post('create', 'create');
            Route::post('edit/{id}', 'edit');
            Route::get('destroy/{id}', 'destroy');
            Route::get('get/{id}', 'getAdvisor');
            Route::get('convert-advisor/{id}', 'convertAdvisor');
            Route::get('check-nif/{nif}', 'checkNif');
            Route::get('active', 'getActiveAdvisors');
            Route::get('count', 'count');
            Route::get('courses/{id}', 'getAdvisorCourses');
            Route::get('companies/{id}', 'getAdvisorCompanies');
            Route::get('csv', 'advisorsCSV');
        });
    });

    /**
     * Incidencias de asesorías
     */
    Route::prefix('advisor-incidences')->group(function() {
        Route::controller(AdvisorIncidenceController::class)->group(function(){
            Route::get('', 'getAdvisorIncidences');
            Route::post('create', 'create');
            Route::post('edit/{id}', 'edit');
            Route::get('destroy/{id}', 'destroy');
        });
    });

    /**
     * Colaboradores
     */
    Route::prefix('collaborators')->group(function() {
        Route::controller(CollaboratorController::class)->group(function(){
            Route::get('', 'collaborators');
        });
    });

    /**
     * Tipo cursos
     */
    Route::prefix('course-types')->group(function() {
        Route::controller(CourseTypeController::class)->group(function(){
            Route::get('', 'getCourseTypes');
            Route::post('create', 'create');
            Route::post('edit/{id}', 'edit');
            Route::get('destroy/{id}', 'destroy');
            Route::get('get/{id}', 'getCourseType');
            Route::get('count', 'count');
        });
    });

    /**
     * Estado del curso
     */
    Route::prefix('course-statuses')->group(function() {
        Route::controller(CourseStatusController::class)->group(function(){
            Route::get('', 'getCourseStatuses');
            Route::post('create', 'create');
            Route::post('edit/{id}', 'edit');
            Route::get('destroy/{id}', 'destroy');
            Route::get('count', 'count');
        });
    });

    /**
     * Cursos
     */
    Route::prefix('courses')->group(function() {
        Route::controller(CourseController::class)->group(function(){
            Route::get('', 'getCourses');
            Route::post('create', 'create');
            Route::post('edit/{id}', 'edit');
            Route::get('destroy/{id}', 'destroy');
            Route::get('get/{id}', 'getCourse');
            Route::get('set-data', 'setData');
            Route::get('students/{id}', 'getStudents');
            Route::get('count', 'count');
            Route::get('csv', 'coursesCSV');
        });
    });

    /**
     * Tipo incidencias
     */
    Route::prefix('incidence-types')->group(function() {
        Route::controller(IncidenceTypeController::class)->group(function(){
            Route::get('', 'getIncidenceTypes');
            Route::post('create', 'create');
            Route::post('edit/{id}', 'edit');
            Route::get('destroy/{id}', 'destroy');
            Route::get('get/{id}', 'getIncidenceType');
            Route::get('count', 'count');
        });
    });

    /**
     * Niveles de acciones formativas
     */
    Route::prefix('training-action-levels')->group(function() {
        Route::controller(TrainingActionLevelController::class)->group(function(){
            Route::get('', 'getTrainingActionLevels');
            Route::post('create', 'create');
            Route::post('edit/{id}', 'edit');
            Route::get('destroy/{id}', 'destroy');
            Route::get('get/{id}', 'getTrainingActionLevel');
            Route::get('count', 'count');
        });
    });

    /**
     * Grupos acciones formativas
     */
    Route::prefix('training-action-groups')->group(function() {
        Route::controller(TrainingActionGroupController::class)->group(function(){
            Route::get('', 'trainingActionGroups');
            Route::post('create', 'create');
            Route::post('edit/{id}', 'edit');
            Route::get('destroy/{id}', 'destroy');
            Route::get('get/{id}', 'getTrainingActionGroup');
            Route::get('count', 'count');
        });
    });

    /**
     * Estado de CFA
     */
    Route::prefix('training-contract-statuses')->group(function() {
        Route::controller(TrainingContractStatusController::class)->group(function(){
            Route::get('', 'getTrainingContractStatuses');
            Route::post('create', 'create');
            Route::post('edit/{id}', 'edit');
            Route::get('destroy/{id}', 'destroy');
            Route::get('get/{id}', 'getTrainingContractStatus');
            Route::get('register', 'registers');
            Route::get('count', 'count');
        });
    });

    /**
     * Tipos de
     */
    Route::prefix('on-leave-types')->group(function() {
        Route::controller(OnLeaveController::class)->group(function(){
            Route::get('', 'getOnLeaveTypes');
            Route::post('create', 'create');
            Route::post('edit/{id}', 'edit');
            Route::get('destroy/{id}', 'destroy');
            Route::get('get/{id}', 'getOnLeaveType');
            Route::get('count', 'count');
        });
    });

    /**
     * Nivel de estudios
     */
    Route::prefix('level-studies')->group(function() {
        Route::controller(LevelStudyController::class)->group(function(){
            Route::post('create', 'create');
            Route::post('edit/{id}', 'edit');
            Route::get('destroy/{id}', 'destroy');
            Route::get('get/{id}', 'getLevelStudy');
            Route::get('count', 'count');
        });
    });

    /**
     * Grupo de
     */
    Route::prefix('quote-groups')->group(function() {
        Route::controller(QuoteGroupController::class)->group(function(){
            Route::get('', 'quoteGroups');
            Route::post('create', 'create');
            Route::post('edit/{id}', 'edit');
            Route::get('destroy/{id}', 'destroy');
            Route::get('get/{id}', 'getTrainingActionLevel');
            Route::get('count', 'count');
        });
    });

    /**
     * Áreas de docentes
     */
    Route::prefix('teacher-areas')->group(function() {
        Route::controller(TeacherAreaController::class)->group(function(){
            Route::get('', 'teacherAreas');
            Route::post('create', 'create');
            Route::post('edit/{id}', 'edit');
            Route::get('destroy/{id}', 'destroy');
            Route::get('get/{id}', 'getTrainingActionLevel');
            Route::get('count', 'count');
        });
    });

    /**
     * Áreas de profesionales
     */
    Route::prefix('professional-areas')->group(function() {
        Route::controller(ProfessionalAreaController::class)->group(function(){
            Route::get('', 'professionalAreas');
            Route::post('create', 'create');
            Route::post('edit/{id}', 'edit');
            Route::get('destroy/{id}', 'destroy');
            Route::get('get/{id}', 'getProfessionalArea');
            Route::get('count', 'count');
        });
    });

    /**
     * Categoría de profesional
     */
    Route::prefix('professional-categories')->group(function() {
        Route::controller(ProfessionalCategoryController::class)->group(function(){
            Route::post('create', 'create');
            Route::post('edit/{id}', 'edit');
            Route::get('destroy/{id}', 'destroy');
        });
    });

    /**
     * Familia profesional
     */
    Route::prefix('professional-families')->group(function() {
        Route::controller(ProfessionalFamilyController::class)->group(function(){
            Route::get('', 'professionalFamilies');
            Route::post('create', 'create');
            Route::post('edit/{id}', 'edit');
            Route::get('destroy/{id}', 'destroy');
            Route::get('get/{id}', 'getProfessionalFamily');
            Route::get('count', 'count');
        });
    });

    /**
     * Modalidad
     */
    Route::prefix('modalities')->group(function() {
        Route::controller(ModalityController::class)->group(function(){
            Route::get('', 'modalities');
            Route::post('create', 'create');
            Route::post('edit/{id}', 'edit');
            Route::get('destroy/{id}', 'destroy');
            Route::get('get/{id}', 'getModality');
            Route::get('count', 'count');
        });
    });

    /**
     * Pagos
     */
    Route::prefix('payments')->group(function() {
        Route::controller(PaymentController::class)->group(function(){
            Route::get('', 'getPayments');
            Route::post('create', 'create');
            Route::post('edit/{id}', 'edit');
            Route::get('destroy/{id}', 'destroy');
            Route::get('get/{id}', 'getPayment');
            Route::get('count', 'count');
        });
    });

    /**
     * Proveedor
     */
    Route::prefix('providers')->group(function() {
        Route::controller(ProviderController::class)->group(function(){
            Route::get('', 'providers');
            Route::post('create', 'create');
            Route::post('edit/{id}', 'edit');
            Route::get('destroy/{id}', 'destroy');
            Route::get('get/{id}', 'getProvider');
            Route::get('training-actions/{id}', 'getTrainingActions');
            Route::get('count', 'count');
        });
    });

    /**
     * Centro
     */
    Route::prefix('centers')->group(function() {
        Route::controller(CenterController::class)->group(function(){
            Route::get('', 'getCenters');
            Route::post('create', 'create');
            Route::post('edit/{id}', 'edit');
            Route::get('destroy/{id}', 'destroy');
            Route::get('get/{id}', 'getCenter');
            Route::get('count', 'count');
        });
    });

    /**
     * Tutorías
     */
    Route::prefix('tutorings')->group(function() {
        Route::controller(TutoringController::class)->group(function(){
            Route::get('', 'tutorings');
            Route::post('create', 'create');
            Route::post('edit/{id}', 'edit');
            Route::get('destroy/{id}', 'destroy');
            Route::get('get/{id}', 'tutoring');
            Route::get('count', 'count');
        });
    });

    /**
     * Actividades de empresas
     */
    Route::prefix('company-activities')->group(function() {
        Route::controller(CompanyActivityController::class)->group(function(){
            Route::post('create', 'create');
            Route::post('edit/{id}', 'edit');
            Route::get('destroy/{id}', 'destroy');
            Route::get('get/{id}', 'getCompanyActivity');
            Route::get('count', 'count');
        });
    });

    /**
     * Tipos de empresas
     */
    Route::prefix('company-types')->group(function() {
        Route::controller(CompanyTypeController::class)->group(function(){
            Route::post('create', 'create');
            Route::post('edit/{id}', 'edit');
            Route::get('destroy/{id}', 'destroy');
            Route::get('get/{id}', 'getCompanyType');
            Route::get('count', 'count');
        });
    });

    /**
     * Observaciones de empresa
     */
    Route::prefix('company-observations')->group(function() {
        Route::controller(CompanyObservationController::class)->group(function(){
            Route::post('create', 'create');
            Route::post('edit/{id}', 'edit');
            Route::get('destroy/{id}', 'destroy');
            Route::get('get/{id}', 'getCompanyIncidence');
            Route::get('{id}', 'companyObservations');
        });
    });

    /**
     * Incidencias
     */
    Route::prefix('company-incidences')->group(function() {
        Route::controller(CompanyIncidenceController::class)->group(function(){
            Route::post('create', 'create');
            Route::post('edit/{id}', 'edit');
            Route::get('destroy/{id}', 'destroy');
            Route::get('get/{id}', 'getCompanyIncidence');
            Route::get('{id}', 'companyIncidences');
        });
    });

    /**
     * Créditos
     */
    Route::prefix('credits')->group(function() {
        Route::controller(CreditController::class)->group(function(){
            Route::post('create', 'create');
            Route::post('edit/{id}', 'edit');
            Route::get('destroy/{id}', 'destroy');
            Route::get('get/{id}', 'getCredit');
            Route::get('{id}', 'getCredits');
            Route::get('count', 'count');
        });
    });

    /**
     * Docentes
     */
    Route::prefix('teachers')->group(function() {
        Route::controller(TeacherController::class)->group(function(){
            Route::get('', 'getTeachers');
            Route::post('create', 'create');
            Route::post('edit/{id}', 'edit');
            Route::get('destroy/{id}', 'destroy');
            Route::get('get/{id}', 'getTeacher');
            Route::post('check_dni', 'checkDni');
            Route::get('courses/{id}', 'getTeachersCourses');
            Route::get('count', 'count');
            Route::get('csv', 'teachersCSV');
        });
    });

    /**
     * Acciones formativas
     */
    Route::prefix('training-actions')->group(function() {
        Route::controller(TrainingActionController::class)->group(function(){
            Route::get('', 'getTrainingActions');
            Route::post('create', 'create');
            Route::post('edit/{id}', 'edit');
            Route::get('destroy/{id}', 'destroy');
            Route::get('get/{id}', 'getTrainingAction');
            Route::get('formative-action', 'getFormativeAction');
            Route::get('active', 'getActiveTrainingActions');
            Route::get('courses/{id}', 'getCourses');
            Route::get('count', 'count');
            Route::get('csv', 'trainingActionsCSV');
        });
    });

    /**
     * Certificados
     */
    Route::prefix('certifications')->group(function() {
        Route::controller(CertificationController::class)->group(function(){
            Route::get('', 'certifications');
            Route::post('create', 'create');
            Route::post('edit/{id}', 'edit');
            Route::get('destroy/{id}', 'destroy');
            Route::get('get/{id}', 'getCertification');
            Route::get('elements/{id}', 'getElements');
            Route::post('add-Element/{id}', 'addElement');
            Route::post('remove-element/{id}', 'removeElement');
            Route::get('not-used-units/{id}', 'getNotUsedUnits');
            Route::get('not-used-modules/{id}', 'getNotUsedModules');
            Route::get('count', 'count');
        });
    });

    /**
     * Elementos de certificados
     */
    Route::prefix('certification-elements')->group(function() {
        Route::controller(CertificationElementController::class)->group(function(){
            Route::post('create/{id}', 'create');
            Route::get('destroy/{id}', 'destroy');
            Route::get('elements/{id}', 'getElements');
            Route::get('units/{id}', 'getUnits');
            Route::get('modules/{id}', 'getModules');
        });
    });

    /**
     * Modulos
     */
    Route::prefix('modules')->group(function() {
        Route::controller(ModuleController::class)->group(function(){
            Route::get('', 'modules');
            Route::post('create', 'create');
            Route::post('edit/{id}', 'edit');
            Route::get('destroy/{id}', 'destroy');
            Route::get('get/{id}', 'getModule');
            Route::get('units/{id}', 'getUnits');
            Route::post('add-unit/{id}', 'addUnit');
            Route::post('remove-unit/{id}', 'removeUnit');
            Route::get('not-used-units/{id}', 'getNotUsedUnits');
            Route::get('count', 'count');
        });
    });

    /**
     * Training Units
     */
    Route::prefix('training-units')->group(function() {
        Route::controller(TrainingUnitController::class)->group(function(){
            Route::get('', 'trainingUnits');
            Route::post('create', 'create');
            Route::post('edit/{id}', 'edit');
            Route::get('destroy/{id}', 'destroy');
            Route::get('get/{id}', 'getTrainingUnit');
            Route::get('count', 'count');
        });
    });

    /**
     * Contracts
     */
    Route::prefix('training-contract-elements')->group(function() {
        Route::controller(TrainingContractElementController::class)->group(function(){
            Route::get('', 'getElements');
            Route::post('create/{id}', 'create');
            Route::get('destroy/{id}', 'destroy');
            Route::get('csv', 'trainingContractElementsCSV');
            Route::post('order', 'orderTrainingContractElements');
            Route::get('{id}', 'getTrainingContractElements');
            Route::get('get/{id}', 'getElement');
            Route::post('edit-date/{id}', 'editDate');
        });
    });

    /**
     * Training Contract Incidences
     */
    Route::prefix('training-contract-incidences')->group(function() {
        Route::controller(TrainingContractIncidenceController::class)->group(function(){
            Route::post('create', 'create');
            Route::post('edit/{id}', 'edit');
            Route::get('destroy/{id}', 'destroy');
            Route::get('get/{id}', 'getTrainingContractIncidence');
            Route::get('{id}', 'trainingContractIncidences');
        });
    });

    /**
     * Training Contract Bonuses
     */
    Route::prefix('training-contract-bonuses')->group(function() {
        Route::controller(TrainingContractBonusController::class)->group(function(){
            Route::get('generate/{id}', 'generate');
            Route::post('create', 'create');
            Route::post('edit/{id}', 'edit');
            Route::get('destroy/{id}', 'destroy');
            Route::get('get/{id}', 'getTrainingContractBonus');
            Route::get('{id}', 'trainingContractBonuses');
        });
    });

    /**
     * Chore
     */
    Route::prefix('chores')->group(function() {
        Route::controller(ChoreController::class)->group(function(){
            Route::get('', 'getChores');
            Route::post('edit/{id}', 'edit');
            Route::get('destroy/{id}', 'destroy');
            Route::get('get/{id}', 'getChore');
            Route::get('count', 'count');
            Route::get('csv', 'choresCSV');
        });
    });

    /**
     * Tracing
     */
    Route::prefix('tracings')->group(function() {
        Route::controller(TracingController::class)->group(function(){
            Route::get('', 'getTracings');
            Route::post('edit/{id}', 'edit');
            Route::get('destroy/{id}', 'destroy');
            Route::get('get/{id}', 'getTracing');
            Route::get('count', 'count');
            Route::get('csv', 'tracingsCSV');
        });
    });

    /**
     * Bills
     */
    Route::prefix('bills')->group(function() {
        Route::controller(BillController::class)->group(function(){
            Route::get('', 'getBills');
            Route::post('edit/{id}', 'edit');
            Route::get('destroy/{id}', 'destroy');
            Route::get('get/{id}', 'getBill');
            Route::get('students/{id}', 'getBillStudents');
            Route::get('count', 'count');
            Route::get('csv', 'billsCSV');
        });
    });


    /**
     * Profitabilities
     */
    Route::prefix('profitabilities')->group(function() {
        Route::controller(ProfitabilityController::class)->group(function(){
            Route::get('', 'getProfitabilities');
            Route::post('edit/{id}', 'edit');
            Route::get('destroy/{id}', 'destroy');
            Route::get('get/{id}', 'getProfitability');
            Route::get('students/{id}', 'getStudents');
            Route::get('count', 'count');
            Route::get('csv', 'profitsCSV');
        });
    });

    /**
     * Registrations
     */
    Route::prefix('registrations')->group(function() {
        Route::controller(RegistrationController::class)->group(function(){
            Route::post('create', 'create');
            Route::post('edit/{id}', 'edit');
            Route::get('destroy/{id}', 'destroy');
            Route::get('get/{id}', 'getRegistration');
            Route::get('get-registered/{id}', 'getRegistrations');
            Route::get('get-not-registered/{id}', 'getNotRegistered');
        });
    });

    /**
     * Users
     */
    Route::prefix('users')->group(function() {
        Route::controller(UserController::class)->group(function(){
            Route::get('', 'getUsers');
            Route::post('create', 'create');
            Route::post('edit/{id}', 'edit');
            Route::get('destroy/{id}', 'destroy');
            Route::get('get/{id}', 'getUser');
            Route::get('count', 'count');
            Route::post('upload-image/{id}', 'uploadImage');
        });
    });

    /**
     * Cnaes
     */
    Route::prefix('cnaes')->group(function() {
        Route::controller(CnaeController::class)->group(function(){
            Route::post('create', 'create');
            Route::post('edit/{id}', 'edit');
            Route::get('destroy/{id}', 'destroy');
            Route::get('get/{id}', 'getCnae');
            Route::get('count', 'count');
        });
    });

    /**
     * Occupations
     */
    Route::prefix('occupations')->group(function() {
        Route::controller(OccupationController::class)->group(function(){
            Route::get('', 'getOccupations');
            Route::post('create', 'create');
            Route::post('edit/{id}', 'edit');
            Route::get('destroy/{id}', 'destroy');
            Route::get('get/{id}', 'getOccupation');
            Route::get('count', 'count');
        });
    });

    /**
     * companies
     */
    Route::prefix('companies')->group(function() {
        Route::controller(CompanyController::class)->group(function(){
            Route::get('', 'companies');
            Route::get('active', 'getActiveCompanies');
            Route::post('create', 'create');
            Route::post('edit/{id}', 'edit');
            Route::get('destroy/{id}', 'destroy');
            Route::get('get/{id}', 'getCompany');
            Route::get('courses/{id}', 'getCompanyCourses');
            Route::get('students/{id}', 'getCompanyStudents');
            Route::get('convert-client/{id}', 'convertClient');
            Route::get('convert-advisor/{id}', 'convertAdvisor');
            Route::get('convert-provider/{id}', 'convertProvider');
            Route::get('count', 'count');
            Route::get('csv', 'companiesCSV');
        });
    });

    /**
     * Students
     */
    Route::prefix('potential-students')->group(function() {
        Route::controller(PotentialStudentController::class)->group(function(){
            Route::get('', 'getPotentialStudents');
            Route::post('send-email', 'sendEmail');
            Route::post('send-bonus-email', 'sendBonusEmail');
            Route::post('edit/{id}', 'edit');
            Route::get('destroy/{id}', 'destroy');
            Route::get('get/{id}', 'getPotentialStudent');
            Route::get('count', 'count');
            Route::post('convert/{id}', 'convertStudent');
        });
    });

    /**
     * Companies
     */
    Route::prefix('potential-companies')->group(function() {
        Route::controller(PotentialCompanyController::class)->group(function(){
            Route::get('', 'getPotentialCompanies');
            Route::post('send-email', 'sendEmail');
            Route::post('edit/{id}', 'edit');
            Route::get('destroy/{id}', 'destroy');
            Route::get('get/{id}', 'getPotentialCompany');
            Route::get('count', 'count');
            Route::post('convert/{id}', 'convertCompany');
        });
    });

    /**
     * Plataformas
     */
    Route::prefix('web-platforms')->group(function() {
        Route::controller(WebPlatformController::class)->group(function(){
            Route::get('', 'webPlatforms');
            Route::post('create', 'create');
            Route::post('edit/{id}', 'edit');
            Route::get('destroy/{id}', 'destroy');
            Route::get('get/{id}', 'getWebPlatform');
            Route::get('count', 'count');
        });
    });

    /**
     * Poblaciones
     */
    Route::prefix('populations')->group(function() {
        Route::controller(PopulationController::class)->group(function(){
            Route::get('', 'populations');
            Route::post('create', 'create');
            Route::post('edit/{id}', 'edit');
            Route::get('destroy/{id}', 'destroy');
            Route::get('get/{id}', 'getPopulation');
            Route::get('populations-with-festivals', 'populationsWithFestivals');
        });
    });

    /**
     * Origen cursos
     */
    Route::prefix('course-origins')->group(function() {
        Route::controller(CourseOriginController::class)->group(function(){
            Route::get('', 'getCourseOrigins');
            Route::post('create', 'create');
            Route::post('edit/{id}', 'edit');
            Route::get('destroy/{id}', 'destroy');
            Route::get('get/{id}', 'getCourseOrigin');
        });
    });

    Route::prefix('bank-holiday-groups')->group(function() {
        Route::controller(BankHolidayGroupController::class)->group(function(){
            Route::get('', 'getBankHolidayGroups');
            Route::post('create', 'create');
            Route::post('edit/{id}', 'edit');
            Route::get('destroy/{id}', 'destroy');
            Route::get('count', 'count');
        });
    });

    /**
     * Training contract excluded days
     */
    Route::prefix('training-contract-excluded-days')->group(function() {
        Route::controller(TrainingContractExcludedDayController::class)->group(function(){
            Route::get('', 'getTrainingContractExcludedDays');
            Route::post('create', 'create');
            Route::post('createGroup', 'createGroup');
            Route::get('destroy/{group}', 'destroy');
        });
    });

    /**
     * Training contract festivals
     */
    Route::prefix('training-contract-festivals')->group(function() {
        Route::controller(TrainingContractFestivalController::class)->group(function(){
            Route::get('', 'getTrainingContractFestivals');
            Route::post('create', 'create');
            Route::post('createGroup', 'createGroup');
            Route::get('destroy/{id}', 'destroy');
        });
    });

    /**
     * Festivos nacionales
     */
    Route::prefix('nacional-festivals')->group(function() {
        Route::controller(NacionalFestivalController::class)->group(function(){
            Route::get('', 'getNacionalFestivals');
            Route::post('create', 'create');
            Route::post('edit/{id}', 'edit');
            Route::get('destroy/{id}', 'destroy');
        });
    });

    /**
     * Festivos provincias
     */
    Route::prefix('province-festivals')->group(function() {
        Route::controller(ProvinceFestivalController::class)->group(function(){
            Route::get('', 'getProvinceFestivals');
            Route::post('create', 'create');
            Route::post('edit/{id}', 'edit');
            Route::get('destroy/{id}', 'destroy');
        });
    });

    /**
     * Festivos poblaciones
     */
    Route::prefix('population-festivals')->group(function() {
        Route::controller(PopulationFestivalController::class)->group(function(){
            Route::get('', 'getPopulationFestivals');
            Route::post('create', 'create');
            Route::post('edit/{id}', 'edit');
            Route::get('destroy/{id}', 'destroy');
        });
    });

    /**
     * Examen - tutorías
     */
    Route::prefix('exams-tutorials')->group(function() {
        Route::controller(ExamTutorialController::class)->group(function(){
            Route::post('create', 'create');
            Route::post('edit/{id}', 'edit');
            Route::get('destroy/{id}', 'destroy');
            Route::get('{id}', 'getExamTutorial');
        });
    });

    /**
     * Dias excluidos
     */
    Route::prefix('excluded-day-types')->group(function() {
        Route::controller(ExcludedDayTypeController::class)->group(function(){
            Route::get('', 'getExcludedDayTypes');
        });
    });

    /**
     * Facturas de CFA
     */
    Route::prefix('training-contract-bills')->group(function() {
        Route::controller(TrainingContractBillController::class)->group(function(){
            Route::get('', 'getBills');
            Route::get('get/{id}', 'getBill');
            Route::post('create', 'create');
            Route::post('edit/{id}', 'edit');
            Route::get('years', 'years');
            Route::get('csv', 'billsCSV');
        });
    });

    /**
     * CFA
     */
    Route::prefix('training-contracts')->group(function() {
        Route::controller(TrainingContractController::class)->group(function(){
            Route::get('', 'getTrainingContracts');
            Route::post('create', 'create');
            Route::post('edit/{id}', 'edit');
            Route::get('destroy/{id}', 'destroy');
            Route::get('get/{id}', 'getTrainingContract');
            Route::get('cfa-number', 'getCFANumber');
            Route::get('specialties/{id}', 'getSpecialties');
            Route::get('certifications/{id}', 'getCertifications');
            Route::get('count', 'countTrainingContracts');
            Route::post('calculate-hours/{id}', 'calculateHours');
            Route::get('csv', 'trainingContractCSV');
            Route::post('register/{id}', 'register');
        });
    });

});

/**
 * Categoria Profesional
 */
Route::prefix('professional-categories')->group(function() {
    Route::controller(ProfessionalCategoryController::class)->group(function(){
        Route::get('', 'professionalCategories');
        Route::get('get/{id}', 'getProfessionalCategories');
        Route::get('count', 'count');
    });
});

/**
 * Alumnos
 */
Route::prefix('potential-students')->group(function() {
    Route::controller(PotentialStudentController::class)->group(function(){
        Route::post('create', 'create');
        Route::post('check_dni', 'checkDni');
    });
});

/**
 * Empresas
 */
Route::prefix('potential-companies')->group(function() {
    Route::controller(PotentialCompanyController::class)->group(function(){
        Route::post('create', 'create');
    });
});

/**
 * Provincias
 */
Route::prefix('provinces')->group(function() {
    Route::controller(ProvinceController::class)->group(function(){
        Route::get('', 'provinces');
        Route::get('get/{id}', 'province');
        Route::get('provinces-with-festivals', 'provincesWithFestivals');
    });
});

/**
 * Nivel de alumnos
 */
Route::prefix('level-studies')->group(function() {
    Route::controller(LevelStudyController::class)->group(function(){
        Route::get('', 'levelStudies');
    });
});

/**
 * Tipos empresas
 */
Route::prefix('company-types')->group(function() {
    Route::controller(CompanyTypeController::class)->group(function(){
        Route::get('', 'companyTypes');
    });
});

/**
 * Actividades de empresas
 */
Route::prefix('company-activities')->group(function() {
    Route::controller(CompanyActivityController::class)->group(function(){
        Route::get('', 'companyActivities');
    });
});

/**
 * Cnaes
 */
Route::prefix('cnaes')->group(function() {
    Route::controller(CnaeController::class)->group(function(){
        Route::get('', 'cnaes');
    });
});
