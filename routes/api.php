<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ActionTypeController;
use App\Http\Controllers\Api\AdvisorController;
use App\Http\Controllers\Api\AdvisorIncidenceController;
use App\Http\Controllers\Api\PruebaController;
use App\Http\Controllers\Api\TeacherController;
use App\Http\Controllers\Api\TrainingActionLevelController;
use App\Http\Controllers\Api\TrainingActionGroupController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\LevelStudyController;
use App\Http\Controllers\Api\QuoteGroupController;
use App\Http\Controllers\Api\ProfessionalCategoryController;
use App\Http\Controllers\Api\ProvinceController;
use App\Http\Controllers\Api\TeacherAreaController;
use App\Http\Controllers\Api\CompanyActivityController;
use App\Http\Controllers\Api\CompanyTypeController;
use App\Http\Controllers\Api\CnaeController;
use App\Http\Controllers\Api\CollaboratorController;
use App\Http\Controllers\Api\TrainingActionController;
use App\Http\Controllers\Api\ProfessionalFamilyController;
use App\Http\Controllers\Api\ModalityController;
use App\Http\Controllers\Api\TutoringController;
use App\Http\Controllers\Api\WebPlatformController;
use App\Http\Controllers\Api\ProfessionalAreaController;
use App\Http\Controllers\Api\ProviderController;
use App\Http\Controllers\Api\CompanyObservationController;
use App\Http\Controllers\Api\CertificationController;
use App\Http\Controllers\Api\ModuleController;
use App\Http\Controllers\Api\TrainingUnitController;
use App\Http\Controllers\Api\CourseStatusController;
use App\Http\Controllers\Api\CourseTypeController;
use App\Http\Controllers\Api\ExcludedDayTypeController;
use App\Http\Controllers\Api\IncidenceTypeController;
use App\Http\Controllers\Api\OccupationController;
use App\Http\Controllers\Api\OnLeaveController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\PotentialCompanyController;
use App\Http\Controllers\Api\PotentialStudentController;
use App\Http\Controllers\Api\ProfitabilityController;
use App\Http\Controllers\Api\TracingController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\TrainingContractController;
use App\Http\Controllers\Api\CenterController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\ChoreController;
use App\Http\Controllers\Api\BillController;
use App\Http\Controllers\Api\CompanyIncidenceController;
use App\Http\Controllers\Api\CreditController;
use App\Http\Controllers\Api\TrainingContractStatusController;
use App\Http\Controllers\Api\TrainingContractIncidenceController;
use App\Http\Controllers\Api\RegistrationController;
use App\Http\Controllers\Api\TrainingContractElementController;
use App\Http\Controllers\Api\ExamTutorialController;
use App\Http\Controllers\Api\BankHolidayGroupController;
use App\Http\Controllers\Api\TrainingContractExcludedDayController;
use App\Http\Controllers\Api\TrainingContractBonusController;
use App\Http\Controllers\Api\CertificationElementController;
use App\Http\Controllers\Api\StatisticController;
use App\Http\Controllers\Api\TrainingContractBillController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\CourseOriginController;
use App\Http\Controllers\Api\NacionalFestivalController;
use App\Http\Controllers\Api\ProvinceFestivalController;
use App\Http\Controllers\Api\PopulationFestivalController;
use App\Http\Controllers\Api\PopulationController;
use App\Http\Controllers\Api\TrainingContractFestivalController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\AdvisorCommissionController;
use App\Http\Controllers\Api\CommissionTypeController;
use App\Http\Controllers\Api\AdvisorObservationController;
use App\Http\Controllers\Api\CommunityController;
use App\Http\Controllers\Api\CommunityFestivalController;
use App\Http\Controllers\Api\UserCommissionTypeController;
use App\Http\Controllers\Api\AdvisorCommissionTypeController;
use App\Http\Controllers\Api\UserCommissionController;
use App\Http\Controllers\Api\DocumentController;
use App\Http\Controllers\Api\DocumentStudentController;
use App\Http\Controllers\Api\DocumentTypeController;
use App\Http\Controllers\Api\TrainingContractSeriesController;

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
            Route::get('', 'index');
            Route::post('create', 'store');
            Route::post('edit/{id}', 'update');
            Route::get('destroy/{id}', 'destroy');
            Route::get('get/{id}', 'show');
            Route::post('check_dni', 'checkDni');
            Route::get('courses/{id}', 'getStudentsCourses');
            Route::get('active', 'getActiveStudents');
        });
    });

    /**
     * Documentos
     */
    Route::prefix('documents')->group(function() {
        Route::controller(DocumentController::class)->group(function(){
            Route::get('', 'index');
            Route::post('create', 'store');
            Route::post('edit/{id}', 'update');
            Route::get('destroy/{id}', 'destroy');
            Route::get('get/{id}', 'show');
            Route::get('get_student_documents', 'getStudentDocuments');
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
        });
    });

    /**
     * Asesorías
     */
    Route::prefix('advisors')->group(function() {
        Route::controller(AdvisorController::class)->group(function(){
            Route::get('', 'index');
            Route::post('create', 'store');
            Route::post('edit/{id}', 'update');
            Route::get('destroy/{id}', 'destroy');
            Route::get('get/{id}', 'show');
            Route::get('convert-advisor/{id}', 'convertAdvisor');
            Route::get('check-nif/{nif}', 'checkNif');
            Route::get('active', 'getActiveAdvisors');
            Route::get('courses/{id}', 'getAdvisorCourses');
            Route::get('companies/{id}', 'getAdvisorCompanies');
            Route::get('training-contracts/{id}', 'getAdvisorTrainingContracts');
            Route::get('commissions', 'indexWithCommissions');
            
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
            Route::get('advisor/{advisor_id}', 'getIncidencesForAdvisor');

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
        });
    });

    /**
     * Cursos
     */
    Route::prefix('courses')->group(function() {
        Route::get('', [CourseController::class, 'index']);
        Route::post('create', [CourseController::class, 'store']);
        Route::post('edit/{id}', [CourseController::class, 'update']);
        Route::get('destroy/{id}', [CourseController::class, 'destroy']);
        Route::get('get/{id}', [CourseController::class, 'show']);
        Route::get('set-data', [CourseController::class, 'setData']);
        Route::get('students/{id}', [CourseController::class, 'getStudents']);
        Route::post('reset-tracings/{id}', [CourseController::class, 'resetTracingsIfCancelled']);
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
     * Observaciones de asesoría
     */
    Route::prefix('advisor-observations')->group(function() {
        Route::controller(AdvisorObservationController::class)->group(function(){
            Route::post('create', 'store');
            Route::post('edit/{id}', 'update');
            Route::get('destroy/{id}', 'destroy');
            Route::get('get/{id}', 'show');
            Route::get('{id}', 'index');
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
        });
    });

    /**
     * Docentes
     */
    Route::prefix('teachers')->group(function() {
        Route::controller(TeacherController::class)->group(function(){
            Route::get('', 'index');
            Route::get('active', 'activeTeachers');
            Route::post('create', 'store');
            Route::post('edit/{id}', 'update');
            Route::get('destroy/{id}', 'destroy');
            Route::get('get/{id}', 'show');
            Route::post('check_dni', 'checkDni');
            Route::get('courses/{id}', 'getTeachersCourses');
        });
    });

    /**
     * Acciones formativas
     */
    Route::prefix('training-actions')->group(function() {
        Route::controller(TrainingActionController::class)->group(function(){
            Route::get('', 'index');
            Route::post('create', 'store');
            Route::post('edit/{id}', 'update');
            Route::get('destroy/{id}', 'destroy');
            Route::get('get/{id}', 'show');
            Route::get('formative-action', 'getFormativeAction');
            Route::get('active', 'getActiveTrainingActions');
            Route::get('courses/{id}', 'getCourses');
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
        });
    });

    /**
     * Contracts
     */
    Route::prefix('training-contract-elements')->group(function() {
        Route::controller(TrainingContractElementController::class)->group(function(){
            Route::get('', 'index');
            Route::post('create/{id}', 'store');
            Route::get('destroy/{id}', 'destroy');
            Route::post('order', 'orderTrainingContractElements');
            Route::get('{id}', 'getTrainingContractElements');
            Route::get('get/{id}', 'show');
            Route::post('edit-date/{id}', 'editDate');
            Route::get('display/all', 'getAll');
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
            Route::get('', 'index');
            Route::post('edit/{id}', 'update');
            Route::get('destroy/{id}', 'destroy');
            Route::get('get/{id}', 'show');
        });
    });

    /**
     * Tracing
     */
    Route::prefix('tracings')->group(function() {
        Route::controller(TracingController::class)->group(function(){
            Route::get('', 'index');
            Route::post('edit/{id}', 'update');
            Route::get('destroy/{id}', 'destroy');
            Route::get('get/{id}', 'show');
        });
    });

    /**
     * CFA
     */
    Route::prefix('training-contracts')->group(function() {
        Route::controller(TrainingContractController::class)->group(function(){
            Route::get('', 'index');
            Route::post('create', 'store');
            Route::post('edit/{id}', 'update');
            Route::get('destroy/{id}', 'destroy');
            Route::get('get/{id}', 'show');
            Route::get('cfa-number', 'getCFANumber');
            Route::get('specialties/{id}', 'getSpecialties');
            Route::get('certifications/{id}', 'getCertifications');
            Route::get('calculate-hours/{id}', 'calculateHours');
            Route::post('register/{id}', 'register');
            Route::post('calculate-end-dates/{id}/{daily_hours_1}/{daily_hours_2}', 'calculateEndDates');
            Route::get('monthly-formation-hours/{id}', 'getMonthlyFormationHours');
           
        });
    });

    /**
     * Profitabilities
     */
    Route::prefix('profitabilities')->group(function() {
        Route::controller(ProfitabilityController::class)->group(function(){
            Route::get('', 'index');
            Route::post('edit/{id}', 'update');
            Route::get('destroy/{id}', 'destroy');
            Route::get('get/{id}', 'show');
            Route::get('students/{id}', 'getStudents');
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
            Route::get('get-all', 'getAllRegistrations');
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
            Route::post('upload-image/{id}', 'uploadImage');
            Route::post('change-password/{id}', 'changePassword');
            Route::get('commissions', 'indexWithCommissions');
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
        });
    });

    /**
     * companies
     */
    Route::prefix('companies')->group(function() {
        Route::controller(CompanyController::class)->group(function(){
            Route::get('', 'index');
            Route::get('active', 'getActiveCompanies');
            Route::post('create', 'store');
            Route::post('edit/{id}', 'update');
            Route::get('destroy/{id}', 'destroy');
            Route::get('get/{id}', 'show');
            Route::get('courses/{id}', 'getCompanyCourses');
            Route::get('students/{id}', 'getCompanyStudents');
            Route::get('convert-client/{id}', 'convertClient');
            Route::get('convert-advisor/{id}', 'convertAdvisor');
            Route::get('convert-provider/{id}', 'convertProvider');
        });
    });

    /**
     * Students
     */
    Route::prefix('potential-students')->group(function() {
        Route::controller(PotentialStudentController::class)->group(function(){
            Route::get('', 'getPotentialStudents');
            Route::post('send-email', 'sendEmail');
            Route::get('send-bonus-email', 'sendBonusEmail');
            Route::post('edit/{id}', 'edit');
            Route::get('destroy/{id}', 'destroy');
            Route::get('get/{id}', 'getPotentialStudent');
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
            Route::post('convert/{id}', 'convertCompany');
        });
    });

    /**
     * Bills
     */
    Route::prefix('bills')->group(function() {
        Route::controller(BillController::class)->group(function(){
            Route::get('', 'index');
            Route::post('edit/{id}', 'update');
            Route::get('destroy/{id}', 'destroy');
            Route::get('get/{id}', 'show');
            Route::get('students/{id}', 'getBillStudents');
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
     * Comunidades
     */
    Route::prefix('communities')->group(function() {
        Route::controller(CommunityController::class)->group(function(){
            Route::get('', 'index');
            Route::post('create', 'store');
            Route::post('edit/{id}', 'update');
            Route::get('destroy/{id}', 'destroy');
            Route::get('get/{id}', 'show');
            Route::get('communities-with-festivals', 'communitiesWithFestivals');
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
     * Festivos comunidades
     */
    Route::prefix('community-festivals')->group(function() {
        Route::controller(CommunityFestivalController::class)->group(function(){
            Route::get('', 'index');
            Route::post('create', 'store');
            Route::post('edit/{id}', 'update');
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
            Route::get('', 'index');
            Route::get('get/{id}', 'show');
            Route::get('create', 'store');
            Route::post('edit/{id}', 'update');
            Route::delete('delete/{id}', 'delete');
            Route::get('years', 'years');
        });
    });

    /**
     * Series Facturas CFA
     */

    Route::prefix('training-contract-series')->group(function() {
        Route::get('/', [TrainingContractSeriesController::class, 'index']);
        Route::post('create', [TrainingContractSeriesController::class, 'store']);
        Route::get('show/{id}', [TrainingContractSeriesController::class, 'show']);
        Route::post('update/{id}', [TrainingContractSeriesController::class, 'update']);
        Route::delete('destroy/{id}', [TrainingContractSeriesController::class, 'destroy']);
    });

    /**
     * Comisiones asesorías
     */
    Route::prefix('advisor-commissions')->group(function() {
        Route::controller(AdvisorCommissionController::class)->group(function(){
            Route::get('get/{id}', 'show');
            Route::post('edit/{id}', 'update');
            Route::get('destroy/{id}', 'destroy');
            Route::get('{id}', 'index');
        });
    });

    /**
     * Comisiones usuarios
     */
    Route::prefix('user-commissions')->group(function() {
        Route::controller(UserCommissionController::class)->group(function(){
            Route::get('get/{id}', 'show');
            Route::post('edit/{id}', 'update');
            Route::get('destroy/{id}', 'destroy');
            Route::get('{id}', 'index');
        });
    });

    /**
     * Tipo comisiones
     */
    Route::prefix('commission-types')->group(function() {
        Route::controller(CommissionTypeController::class)->group(function(){
            Route::get('', 'index');
            Route::get('get/{id}', 'show');
            Route::post('create', 'create');
            Route::post('edit/{id}', 'update');
            Route::get('destroy/{id}', 'destroy');
        });
    });

    /**
     * Tipo comisiones de usuario
     */
    Route::prefix('user-commission-types')->group(function() {
        Route::controller(UserCommissionTypeController::class)->group(function(){
            Route::post('update', 'update');
            Route::get('{id}', 'index');
        });
    });

    /**
     * Tipo comisiones de asesoria
     */
    Route::prefix('advisor-commission-types')->group(function() {
        Route::controller(AdvisorCommissionTypeController::class)->group(function(){
            Route::post('update', 'update');
            Route::get('{id}', 'index');
        });
    });

    /**
     * Document Type
     */
    Route::prefix('document-types')->group(function() {
        Route::controller(DocumentTypeController::class)->group(function(){
            Route::get('', 'index');
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

/**
 * Documentos
 */
Route::prefix('document-students')->group(function() {
    Route::controller(DocumentStudentController::class)->group(function(){
        Route::get('', 'index');
        Route::post('create', 'store');
        Route::post('edit/{id}', 'update');
        Route::get('destroy/{id}', 'destroy');
        Route::get('get/{id}', 'show');
        Route::get('send', 'send');
        Route::get('student-view-pdf/{key}/{viewName}', 'studentViewPdf');
        Route::post('sign-pdf', 'signPDF');
        Route::get('/test-pdf/{viewName}', 'testPDF');
    });
});

Route::prefix('document-types')->group(function() {
    Route::controller(DocumentTypeController::class)->group(function(){
        Route::get('', 'index');
    });
});
