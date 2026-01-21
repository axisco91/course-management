<?php

use App\Http\Controllers\Api\LiquidationController;
use App\Http\Controllers\Api\MainCompanyController;
use App\Http\Controllers\API\PotentialTrainingContractController;
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
            Route::get('{id}', 'getRole');
            Route::post('', 'create');
            Route::put('{id}', 'edit');
        });
    });

    /**
     * Alumnos
     */
    Route::prefix('students')->group(function() {
        Route::controller(StudentController::class)->group(function(){
            Route::get('', 'index');
            Route::post('', 'store');
            Route::post('import', 'import');
            Route::put('{id}', 'update');
            Route::delete('{id}', 'destroy');
            Route::get('check_dni', 'checkDni');
            Route::get('courses/{id}', 'getStudentsCourses');
            Route::get('export-excel', 'studentsExportExcel');
            Route::get('{id}', 'show');
        });
    });

    /**
     * Documentos
     */
    Route::prefix('documents')->group(function() {
        Route::controller(DocumentController::class)->group(function(){
            Route::get('', 'index');
            Route::get('get_student_documents', 'getStudentDocuments');
            Route::get('{id}', 'show');
            Route::post('', 'store');
            Route::put('{id}', 'update');
            Route::delete('{id}', 'destroy');
        });
    });



    /**
     * Tipos Acciones
     */
    Route::prefix('action-types')->group(function() {
        Route::controller(ActionTypeController::class)->group(function(){
            Route::get('', 'getActionTypes');
            Route::get('{id}', 'getActionType');
            Route::post('', 'create');
            Route::put('{id}', 'edit');
            Route::delete('{id}', 'destroy');
        });
    });

    /**
     * Asesorías
     */
    Route::prefix('advisors')->group(function() {
        Route::controller(AdvisorController::class)->group(function(){
            Route::get('', 'index');
            Route::get('export-excel', 'exportExcel');
            Route::post('', 'store');
            Route::put('{id}', 'update');
            Route::delete('{id}', 'destroy');
            Route::get('send-email/{id}', 'sendEmail');
            Route::get('convert-advisor/{id}', 'convertAdvisor');
            Route::get('check-nif', 'checkNif');
            Route::get('active', 'getActiveAdvisors');
            Route::get('courses/{id}', 'getAdvisorCourses');
            Route::get('companies/{id}', 'getAdvisorCompanies');
            Route::get('training-contracts/{id}', 'getAdvisorTrainingContracts');
            Route::get('commissions', 'indexWithCommissions');
            Route::post('create-advisor-user/{id}', 'createAdvisorUser');
            Route::get('{id}', 'show');
        });
    });

    /**
     * Incidencias de asesorías
     */
    Route::prefix('advisor-incidences')->group(function() {
        Route::controller(AdvisorIncidenceController::class)->group(function(){
            Route::get('', 'getAdvisorIncidences');
            Route::post('', 'create');
            Route::put('{id}', 'edit');
            Route::delete('{id}', 'destroy');
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
            Route::post('', 'create');
            Route::put('{id}', 'edit');
            Route::delete('{id}', 'destroy');
            Route::get('{id}', 'getCourseType');
        });
    });

    /**
     * Estado del curso
     */
    Route::prefix('course-statuses')->group(function() {
        Route::controller(CourseStatusController::class)->group(function(){
            Route::get('', 'getCourseStatuses');
            Route::post('', 'create');
            Route::put('{id}', 'edit');
            Route::delete('{id}', 'destroy');
        });
    });

    /**
     * Cursos
     */
    Route::prefix('courses')->group(function() {
        Route::get('', [CourseController::class, 'index']);
        Route::get('export-excel', [CourseController::class, 'exportExcel']);
        Route::post('', [CourseController::class, 'store']);
        Route::put('{id}', [CourseController::class, 'update']);
        Route::delete('{id}', [CourseController::class, 'destroy']);
        Route::get('set-data', [CourseController::class, 'setData']);
        Route::get('students/{id}', [CourseController::class, 'getStudents']);
        Route::put('reset-tracings/{id}', [CourseController::class, 'resetTracingsIfCancelled']);
        Route::get('{id}', [CourseController::class, 'show']);
    });

    /**
     * Tipo incidencias
     */
    Route::prefix('incidence-types')->group(function() {
        Route::controller(IncidenceTypeController::class)->group(function(){
            Route::get('', 'getIncidenceTypes');
            Route::get('{id}', 'getIncidenceType');
            Route::post('', 'create');
            Route::put('{id}', 'edit');
            Route::delete('{id}', 'destroy');
        });
    });

    /**
     * Niveles de acciones formativas
     */
    Route::prefix('training-action-levels')->group(function() {
        Route::controller(TrainingActionLevelController::class)->group(function(){
            Route::get('', 'getTrainingActionLevels');
            Route::get('{id}', 'getTrainingActionLevel');
            Route::post('', 'create');
            Route::put('{id}', 'edit');
            Route::delete('{id}', 'destroy');
        });
    });

    /**
     * Grupos acciones formativas
     */
    Route::prefix('training-action-groups')->group(function() {
        Route::controller(TrainingActionGroupController::class)->group(function(){
            Route::get('', 'trainingActionGroups');
            Route::get('{id}', 'getTrainingActionGroup');
            Route::post('', 'create');
            Route::put('{id}', 'edit');
            Route::delete('{id}', 'destroy');
        });
    });

    /**
     * Estado de CFA
     */
    Route::prefix('training-contract-statuses')->group(function() {
        Route::controller(TrainingContractStatusController::class)->group(function(){
            Route::get('', 'getTrainingContractStatuses');
            Route::post('', 'create');
            Route::put('{id}', 'edit');
            Route::delete('{id}', 'destroy');
            Route::get('register', 'registers');//?? Creo que esto no existe en el controlador
            Route::get('{id}', 'getTrainingContractStatus');
        });
    });

    /**
     * Tipos de
     */
    Route::prefix('on-leave-types')->group(function() {
        Route::controller(OnLeaveController::class)->group(function(){
            Route::get('', 'getOnLeaveTypes');
            Route::get('{id}', 'getOnLeaveType');
            Route::post('', 'create');
            Route::put('{id}', 'edit');
            Route::delete('{id}', 'destroy');
        });
    });

    /**
     * Nivel de estudios
     */
    Route::prefix('level-studies')->group(function() {
        Route::controller(LevelStudyController::class)->group(function(){
            Route::post('', 'create');
            Route::put('{id}', 'edit');
            Route::delete('{id}', 'destroy');
            Route::get('{id}', 'show');
        });
    });

    /**
     * Grupo de
     */
    Route::prefix('quote-groups')->group(function() {
        Route::controller(QuoteGroupController::class)->group(function(){
            Route::get('', 'quoteGroups');
            Route::post('', 'create');
            Route::put('{id}', 'edit');
            Route::delete('{id}', 'destroy');
            Route::get('{id}', 'getTrainingActionLevel');
        });
    });

    /**
     * Áreas de docentes
     */
    Route::prefix('teacher-areas')->group(function() {
        Route::controller(TeacherAreaController::class)->group(function(){
            Route::get('', 'teacherAreas');
            Route::get('{id}', 'show');
            Route::post('', 'create');
            Route::put('{id}', 'edit');
            Route::delete('{id}', 'destroy');
        });
    });

    /**
     * Áreas de profesionales
     */
    Route::prefix('professional-areas')->group(function() {
        Route::controller(ProfessionalAreaController::class)->group(function(){
            Route::get('', 'professionalAreas');
            Route::post('', 'create');
            Route::put('{id}', 'edit');
            Route::delete('{id}', 'destroy');
            Route::get('{id}', 'getProfessionalArea');
        });
    });

    // /**
    //  * Categoría de profesional
    //  */
    // Route::prefix('professional-categories')->group(function() {
    //     Route::controller(ProfessionalCategoryController::class)->group(function(){
    //         Route::post('create', 'create');
    //         Route::post('edit/{id}', 'edit');
    //         Route::get('destroy/{id}', 'destroy');
    //     });
    // });

    /**
     * Familia profesional
     */
    Route::prefix('professional-families')->group(function() {
        Route::controller(ProfessionalFamilyController::class)->group(function(){
            Route::get('', 'professionalFamilies');
            Route::get('{id}', 'getProfessionalFamily');
            Route::post('', 'create');
            Route::put('{id}', 'edit');
            Route::delete('{id}', 'destroy');
        });
    });

    /**
     * Modalidad
     */
    Route::prefix('modalities')->group(function() {
        Route::controller(ModalityController::class)->group(function(){
            Route::get('', 'modalities');
            Route::get('{id}', 'getModality');
            Route::post('', 'create');
            Route::put('{id}', 'edit');
            Route::delete('{id}', 'destroy');
        });
    });

    /**
     * Pagos
     */
    Route::prefix('payments')->group(function() {
        Route::controller(PaymentController::class)->group(function(){
            Route::get('', 'getPayments');
            Route::get('{id}', 'getPayment');
            Route::post('', 'create');
            Route::put('{id}', 'edit');
            Route::delete('{id}', 'destroy');
        });
    });

    /**
     * Proveedor
     */
    Route::prefix('providers')->group(function() {
        Route::controller(ProviderController::class)->group(function(){
            Route::get('', 'providers');
            Route::post('', 'create');
            Route::put('{id}', 'edit');
            Route::delete('{id}', 'destroy');
            Route::get('training-actions/{id}', 'getTrainingActions');
            Route::get('{id}', 'getProvider');
        });
    });

    /**
     * Centro
     */
    Route::prefix('centers')->group(function() {
        Route::controller(CenterController::class)->group(function(){
            Route::get('', 'getCenters');
            Route::get('{id}', 'getCenter');
            Route::post('', 'create');
            Route::put('{id}', 'edit');
            Route::delete('{id}', 'destroy');
        });
    });

    /**
     * Tutorías
     */
    Route::prefix('tutorings')->group(function() {
        Route::controller(TutoringController::class)->group(function(){
            Route::get('', 'tutorings');
            Route::get('{id}', 'tutoring');
            Route::post('', 'create');
            Route::put('{id}', 'edit');
            Route::delete('{id}', 'destroy');
        });
    });

    /**
     * Actividades de empresas
     */
    Route::prefix('company-activities')->group(function() {
        Route::controller(CompanyActivityController::class)->group(function(){
            Route::post('', 'create');
            Route::put('/{id}', 'edit');
            Route::delete('{id}', 'destroy');
            Route::get('{id}', 'getCompanyActivity');
        });
    });

    /**
     * Tipos de empresas
     */
    Route::prefix('company-types')->group(function() {
        Route::controller(CompanyTypeController::class)->group(function(){
            Route::get('{id}', 'getCompanyType');
            Route::post('', 'create');
            Route::put('{id}', 'edit');
            Route::delete('{id}', 'destroy');
        });
    });

    /**
     * Observaciones de empresa
     */
    Route::prefix('company-observations')->group(function() {
        Route::controller(CompanyObservationController::class)->group(function(){
            Route::post('', 'create');
            Route::put('{id}', 'edit');
            Route::delete('{id}', 'destroy');
            Route::get('incidence/{id}', 'getCompanyIncidence');
            Route::get('get/{id}', 'show');
            Route::get('{id}', 'companyObservations');
        });
    });

    /**
     * Observaciones de asesoría
     */
    Route::prefix('advisor-observations')->group(function() {
        Route::controller(AdvisorObservationController::class)->group(function(){
            Route::post('', 'store');
            Route::put('{id}', 'update');
            Route::delete('{id}', 'destroy');
            Route::get('get/{id}', 'show');
            Route::get('{id}', 'index');
        });
    });

    /**
     * Incidencias
     */
    Route::prefix('company-incidences')->group(function() {
        Route::controller(CompanyIncidenceController::class)->group(function(){
            Route::post('', 'create');
            Route::put('{id}', 'edit');
            Route::delete('{id}', 'destroy');
            Route::get('get/{id}', 'show');
            Route::get('{id}', 'companyIncidences');
        });
    });

    /**
     * Créditos
     */
    Route::prefix('credits')->group(function() {
        Route::controller(CreditController::class)->group(function(){
            Route::post('', 'create');
            Route::put('{id}', 'edit');
            Route::delete('{id}', 'destroy');
            Route::get('', 'index');
            Route::get('{id}', 'show');
        });
    });

    /**
     * Docentes
     */
    Route::prefix('teachers')->group(function() {
        Route::controller(TeacherController::class)->group(function(){
            Route::get('', 'index');
            Route::get('export-excel', 'teachersExportExcel');
            Route::post('', 'store');
            Route::put('{id}', 'update');
            Route::delete('{id}', 'destroy');
            Route::get('check_dni', 'checkDni');
            Route::get('courses/{id}', 'getTeachersCourses');
            Route::get('{id}', 'show');
        });
    });

    /**
     * Acciones formativas
     */
    Route::prefix('training-actions')->group(function() {
        Route::controller(TrainingActionController::class)->group(function(){
            Route::get('', 'index');
            Route::get('export-excel', 'exportExcel');
            Route::post('', 'store');
            Route::put('{id}', 'update');
            Route::delete('{id}', 'destroy');
            Route::get('formative-action', 'getFormativeAction');
            Route::get('active', 'getActiveTrainingActions');
            Route::get('courses/{id}', 'getCourses');
            Route::get('{id}', 'show');
        });
    });

    /**
     * Certificados
     */
    Route::prefix('certifications')->group(function() {
        Route::controller(CertificationController::class)->group(function(){
            Route::get('', 'certifications');
            Route::post('', 'create');
            Route::put('{id}', 'edit');
            Route::delete('{id}', 'destroy');
            //ESTAS RUTAS NO ESTAN EN EL CONTROLADOR ?!
            Route::get('elements/{id}', 'getElements');
            Route::post('add-Element/{id}', 'addElement');
            Route::post('remove-element/{id}', 'removeElement');
            Route::get('not-used-units/{id}', 'getNotUsedUnits');
            Route::get('not-used-modules/{id}', 'getNotUsedModules');
            Route::get('{id}', 'getCertification');
        });
    });

    /**
     * Elementos de certificados
     */
    Route::prefix('certification-elements')->group(function() {
        Route::controller(CertificationElementController::class)->group(function(){
            Route::post('{id}', 'create');
            Route::delete('{id}', 'destroy');
            Route::get('elements/{id}', 'index');
            Route::get('element/{id}', 'show');
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
            Route::post('', 'create');
            Route::put('{id}', 'edit');
            Route::delete('{id}', 'destroy');
            Route::get('units/{id}', 'getUnits');
            Route::post('add-unit/{id}', 'addUnit');
            Route::delete('remove-unit/{id}', 'removeUnit');
            Route::get('not-used-units/{id}', 'getNotUsedUnits');
            Route::get('{id}', 'getModule');
        });
    });

    /**
     * Training Units
     */
    Route::prefix('training-units')->group(function() {
        Route::controller(TrainingUnitController::class)->group(function(){
            Route::get('', 'trainingUnits');
            Route::get('{id}', 'getTrainingUnit');
            Route::post('', 'create');
            Route::put('{id}', 'edit');
            Route::delete('{id}', 'destroy');
        });
    });

    /**
     * Contracts
     */
    Route::prefix('training-contract-elements')->group(function() {
        Route::controller(TrainingContractElementController::class)->group(function(){
            Route::get('', 'index');
            Route::delete('{id}', 'destroy');
            Route::post('order', 'orderTrainingContractElements');
            Route::post('{id}', 'store');
            Route::get('get/{id}', 'show');
            Route::put('edit-date/{id}', 'editDate');
            Route::get('display/all', 'getAll');
            Route::get('display/active', 'getActive');
            Route::put('edit-tutor-info/{id}', 'editTutorInfo');
            Route::get('{id}', 'getTrainingContractElements');
        });
    });

    /**
     * Training Contract Incidences
     */
    Route::prefix('training-contract-incidences')->group(function() {
        Route::controller(TrainingContractIncidenceController::class)->group(function(){
            Route::post('', 'create');
            Route::post('{id}', 'edit');
            Route::delete('{id}', 'destroy');
            Route::get('show/{id}', 'show');
            Route::get('{id}', 'trainingContractIncidences');
        });
    });

    /**
     * Training Contract Bonuses
     */
    Route::prefix('training-contract-bonuses')->group(function() {
        Route::controller(TrainingContractBonusController::class)->group(function(){
            Route::get('generate/{id}', 'generate');
            Route::post('', 'create');
            Route::put('{id}', 'edit');
            Route::delete('{id}', 'destroy');
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
            Route::get('export-excel', 'exportExcel');
            Route::put('{id}', 'update');
            Route::delete('{id}', 'destroy');
            Route::get('{id}', 'show');
        });
    });

    /**
     * Tracing
     */
    Route::prefix('tracings')->group(function() {
        Route::controller(TracingController::class)->group(function(){
            Route::get('', 'index');
            Route::get('export-excel', 'exportExcel');
            Route::put('{id}', 'update');
            Route::delete('{id}', 'destroy');
            Route::get('{id}', 'show');
        });
    });

    /**
     * Calendario
     */
    Route::prefix('calendar')->group(function() {
        Route::controller(\App\Http\Controllers\Api\CalendarController::class)->group(function(){
            Route::get('', 'index');
        });
    });

    /**
     * CFA
//     */
    Route::prefix('training-contracts')->group(function() {
        Route::controller(TrainingContractController::class)->group(function(){
            Route::get('', 'index');
            Route::get('export-excel', 'exportExcel');
            Route::post('', 'store');
            Route::put('update-additional-clause/{id}', 'updateAdditionalClause');
            Route::delete('{id}', 'destroy');
            Route::get('cfa-number', 'getCFANumber');
            Route::get('specialties/{id}', 'getSpecialties');
            Route::get('certifications/{id}', 'getCertifications');
            Route::get('calculate-hours/{id}', 'calculateHours');
            Route::get('training-actions/{id}', 'getTrainingContractActions');
            Route::put('register/{id}', 'register');
            Route::put('calculate-end-dates/{id}/{daily_hours_1}/{daily_hours_2}', 'calculateEndDates');
            Route::get('monthly-formation-hours/{id}', 'getMonthlyFormationHours');
            Route::put('{id}', 'update');
            Route::get('{id}', 'show');
        });
    });

    /**
     * Profitabilities
     */
    Route::prefix('profitabilities')->group(function() {
        Route::controller(ProfitabilityController::class)->group(function(){
            Route::get('', 'index');
            Route::get('export-excel', 'exportExcel');
            Route::put('{id}', 'update');
            Route::delete('{id}', 'destroy');
            Route::get('students/{id}', 'getStudents');
            Route::get('{id}', 'show');
        });
    });

    /**
     * Registrations
     */
    Route::prefix('registrations')->group(function() {
        Route::controller(RegistrationController::class)->group(function(){
            Route::post('', 'create');
            Route::put('{id}', 'update');
            Route::delete('{id}', 'destroy');
            Route::get('get-registered/{id}', 'getRegistrations');
            Route::get('get-not-registered/{id}', 'getNotRegistered');
            Route::get('get-all', 'getAllRegistrations');
            Route::get('{id}', 'getRegistration');
        });
    });

    /**
     * Users
     */
    Route::prefix('users')->group(function() {
        Route::controller(UserController::class)->group(function(){
            Route::get('', 'getUsers');
            Route::get('basic-user', 'basicUser');
            Route::post('', 'create');
            Route::post('{id}', 'edit');
            Route::delete('{id}', 'destroy');
            Route::post('upload-image/{id}', 'uploadImage');
            Route::post('change-password/{id}', 'changePassword');
            Route::get('commissions', 'indexWithCommissions');
            Route::get('{id}', 'getUser');
        });
    });

    /**
     * Cnaes
     */
    Route::prefix('cnaes')->group(function() {
        Route::controller(CnaeController::class)->group(function(){
            Route::get('{id}', 'getCnae');
            Route::post('', 'create');
            Route::put('{id}', 'edit');
            Route::delete('{id}', 'destroy');
        });
    });

    /**
     * Occupations
     */
    Route::prefix('occupations')->group(function() {
        Route::controller(OccupationController::class)->group(function(){
            Route::get('', 'getOccupations');
            Route::get('{id}', 'getOccupation');
            Route::post('', 'create');
            Route::put('{id}', 'edit');
            Route::delete('{id}', 'destroy');
        });
    });

    /**
     * companies
     */
    Route::prefix('companies')->group(function() {
        Route::controller(CompanyController::class)->group(function(){
            Route::get('', 'index');
            Route::get('export-excel', 'exportExcel');
            Route::get('courses-export-excel/{id}', 'coursesExportExcel');
            Route::post('', 'store');
            Route::put('{id}', 'update');
            Route::delete('{id}', 'destroy');
            Route::get('courses/{id}', 'getCompanyCourses');
            Route::get('students/{id}', 'getCompanyStudents');
            Route::get('convert-client/{id}', 'convertClient');
            Route::get('convert-advisor/{id}', 'convertAdvisor');
            Route::get('convert-provider/{id}', 'convertProvider');
            Route::get('{id}', 'show');
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
            Route::get('check-dni', 'checkDni');
            Route::delete('{id}', 'destroy');
            Route::get('{id}', 'getPotentialStudent');
            Route::put('convert/{id}', 'convertStudent');
            Route::put('{id}', 'edit');
        });
    });

    /**
     * Companies
     */
    Route::prefix('potential-companies')->group(function() {
        Route::controller(PotentialCompanyController::class)->group(function(){
            Route::get('', 'getPotentialCompanies');
            Route::post('send-email', 'sendEmail');
            Route::delete('{id}', 'destroy');
            Route::get('{id}', 'getPotentialCompany');
            Route::put('convert/{id}', 'convertCompany');
            Route::put('{id}', 'edit');
        });
    });

    /**
     * Training Contracts
     */
    Route::prefix('potential-training-contract')->group(function() {
        Route::controller(PotentialTrainingContractController::class)->group(function(){
            Route::get('send-email', 'sendEmail');
            Route::put('{id}', 'edit');
            Route::delete('{id}', 'destroy');
        });
    });

    /**
     * Bills
     */
    Route::prefix('bills')->group(function() {
        Route::controller(BillController::class)->group(function(){
            Route::get('', 'index');
            Route::get('export-excel', 'exportExcel');
            Route::put('{id}', 'update');
            Route::delete('{id}', 'destroy');
            Route::get('students/{id}', 'getBillStudents');
            Route::get('min-year', 'minYear');
            Route::get('{id}', 'show');
        });
    });

    /**
     * Plataformas
     */
    Route::prefix('web-platforms')->group(function() {
        Route::controller(WebPlatformController::class)->group(function(){
            Route::get('', 'webPlatforms');
            Route::get('{id}', 'getWebPlatform');
            Route::post('', 'create');
            Route::put('{id}', 'edit');
            Route::delete('{id}', 'destroy');
        });
    });

    /**
     * Poblaciones
     */
    Route::prefix('populations')->group(function() {
        Route::controller(PopulationController::class)->group(function(){
            Route::get('', 'populations');
            Route::get('populations-with-festivals', 'populationsWithFestivals');
            Route::post('', 'create');
            Route::put('{id}', 'edit');
            Route::delete('{id}', 'destroy');
            Route::get('{id}', 'getPopulation');
        });
    });

    /**
     * Comunidades
     */
    Route::prefix('communities')->group(function() {
        Route::controller(CommunityController::class)->group(function(){
            Route::get('', 'index');
            Route::get('communities-with-festivals', 'communitiesWithFestivals');
            Route::post('', 'store');
            Route::put('{id}', 'update');
            Route::delete('{id}', 'destroy');
            Route::get('{id}', 'show');
        });
    });

    /**
     * Origen cursos
     */
    Route::prefix('course-origins')->group(function() {
        Route::controller(CourseOriginController::class)->group(function(){
            Route::get('', 'getCourseOrigins');
            Route::post('', 'create');
            Route::put('{id}', 'edit');
            Route::delete('{id}', 'destroy');
            Route::get('{id}', 'getCourseOrigin');
        });
    });

    Route::prefix('bank-holiday-groups')->group(function() {
        Route::controller(BankHolidayGroupController::class)->group(function(){
            Route::get('', 'getBankHolidayGroups');
            Route::post('', 'create');
            Route::put('{id}', 'edit');
            Route::delete('{id}', 'destroy');
        });
    });

    /**
     * Training contract excluded days
     */
    Route::prefix('training-contract-excluded-days')->group(function() {
        Route::controller(TrainingContractExcludedDayController::class)->group(function(){
            Route::get('', 'getTrainingContractExcludedDays');
            Route::post('', 'create');
            Route::post('createGroup', 'createGroup');
            Route::delete('{group}', 'destroy');
        });
    });

    /**
     * Training contract festivals
     */
    Route::prefix('training-contract-festivals')->group(function() {
        Route::controller(TrainingContractFestivalController::class)->group(function(){
            Route::get('', 'getTrainingContractFestivals');
            Route::post('', 'create');
            Route::post('createGroup', 'createGroup');
            Route::delete('{id}', 'destroy');
        });
    });

    /**
     * Festivos nacionales
     */
    Route::prefix('nacional-festivals')->group(function() {
        Route::controller(NacionalFestivalController::class)->group(function(){
            Route::get('', 'getNacionalFestivals');
            Route::post('', 'create');
            Route::put('{id}', 'edit');
            Route::delete('{id}', 'destroy');
        });
    });

    /**
     * Festivos provincias
     */
    Route::prefix('province-festivals')->group(function() {
        Route::controller(ProvinceFestivalController::class)->group(function(){
            Route::get('', 'getProvinceFestivals');
            Route::post('', 'create');
            Route::post('{id}', 'edit');
            Route::delete('{id}', 'destroy');
        });
    });

    /**
     * Festivos poblaciones
     */
    Route::prefix('population-festivals')->group(function() {
        Route::controller(PopulationFestivalController::class)->group(function(){
            Route::get('', 'getPopulationFestivals');
            Route::post('', 'create');
            Route::put('{id}', 'edit');
            Route::delete('{id}', 'destroy');
        });
    });

    /**
     * Festivos comunidades
     */
    Route::prefix('community-festivals')->group(function() {
        Route::controller(CommunityFestivalController::class)->group(function(){
            Route::get('', 'index');
            Route::post('', 'store');
            Route::put('{id}', 'update');
            Route::delete('{id}', 'destroy');
        });
    });

    /**
     * Examen - tutorías
     */
    Route::prefix('exams-tutorials')->group(function() {
        Route::controller(ExamTutorialController::class)->group(function(){
            Route::post('', 'create');
            Route::put('{id}', 'edit');
            Route::delete('{id}', 'destroy');
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
            Route::get('export-excel', 'exportExcel');
            Route::post('', 'store');
            Route::put('{id}', 'update');
            Route::delete('{id}', 'delete');
            Route::get('years', 'years');
            Route::get('{id}', 'show');
        });
    });

    /**
     * Series Facturas CFA
     */

     Route::prefix('training-contract-series')->group(function() {
        Route::controller(TrainingContractSeriesController::class)->group(function(){
            Route::get('', 'index');
            Route::get('{id}', 'get');
            Route::post('', 'create');
            Route::put('{id}', 'edit');
            Route::delete('{id}', 'destroy');
        });
    });

    /**
     * Comisiones asesorías
     */
    Route::prefix('advisor-commissions')->group(function() {
        Route::controller(AdvisorCommissionController::class)->group(function(){
            Route::get('get/{id}', 'show');
            Route::put('{id}', 'update');
            Route::delete('{id}', 'destroy');
            Route::get('{id}', 'index');
        });
    });

    /**
     * Comisiones usuarios
     */
    Route::prefix('user-commissions')->group(function() {
        Route::controller(UserCommissionController::class)->group(function(){
            Route::get('get/{id}', 'show');
            Route::post('{id}', 'update');
            Route::delete('{id}', 'destroy');
            Route::get('{id}', 'index');
        });
    });

    /**
     * Tipo comisiones
     */
    Route::prefix('commission-types')->group(function() {
        Route::controller(CommissionTypeController::class)->group(function(){
            Route::get('', 'index');
            Route::get('{id}', 'show');
            Route::post('', 'create');
            Route::put('{id}', 'update');
            Route::delete('{id}', 'destroy');
        });
    });

    /**
     * Tipo comisiones de usuario
     */
    Route::prefix('user-commission-types')->group(function() {
        Route::controller(UserCommissionTypeController::class)->group(function(){
            Route::post('', 'update');
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

    /**
     * Documentos
     */
    Route::prefix('document-students')->group(function() {
        Route::controller(DocumentStudentController::class)->group(function(){
            Route::get('', 'index');
            Route::get('/test-pdf/{viewName}/{trainingContract}/{orientation?}', 'testPDF');
        });
    });

    Route::prefix('liquidations')->group(function() {
        Route::controller(LiquidationController::class)->group(function(){
            Route::get('', 'index');
            Route::get('{id}', 'show');
            Route::post('', 'create');
            Route::put('{id}', 'edit');
            Route::delete('{id}', 'destroy');
        });
    });
});


/**
 * Categoria Profesional
 */
Route::prefix('professional-categories')->group(function() {
    Route::controller(ProfessionalCategoryController::class)->group(function(){
        Route::get('', 'professionalCategories');
        Route::get('{id}', 'show');
        Route::post('', 'create');
        Route::put('{id}', 'edit');
        Route::delete('{id}', 'destroy');
    });
});

/**
 * Alumnos
 */
Route::prefix('potential-students')->group(function() {
    Route::controller(PotentialStudentController::class)->group(function(){
        Route::post('', 'create');
        Route::get('check_dni', 'checkDni');
    });
});

/**
 * Empresas
 */
Route::prefix('potential-companies')->group(function() {
    Route::controller(PotentialCompanyController::class)->group(function(){
        Route::post('', 'create');
    });
});

/**
 * Provincias
 */
Route::prefix('provinces')->group(function() {
    Route::controller(ProvinceController::class)->group(function(){
        Route::get('', 'provinces');
        Route::get('provinces-with-festivals', 'provincesWithFestivals');
        Route::get('{id}', 'province');
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
        Route::post('', 'store');
        Route::delete('{id}', 'destroy');
        Route::get('send', 'send');
        Route::get('/studentViewPdf/{key}/{viewName}/{trainingContract}', 'DocumentStudentController@studentViewPdf');
        Route::post('sign-pdf', 'signPDF');
        Route::get('/test-pdf-factura/{viewName}/{trainingContractBill}/{orientation?}', 'testPdfFactura');
        Route::post('generate-invoices', 'generateInvoices');
        Route::post('{id}', 'update');
       // Route::get('{id}', 'show');
    });
});

Route::prefix('document-types')->group(function() {
    Route::controller(DocumentTypeController::class)->group(function(){
        Route::get('', 'index');
    });
});

Route::prefix('training-actions')->group(function() {
    Route::controller(TrainingActionController::class)->group(function(){

        // Nueva ruta GET para información pública
        Route::get('public-info', 'indexPublic');
    });
});

Route::prefix('main-companies')->group(function() {
    Route::controller(MainCompanyController::class)->group(function(){
        Route::get('basic', 'basic');
    });
});

Route::prefix('test')->group(function() {
    Route::controller(\App\Http\Controllers\TestsController::class)->group(function(){
        Route::get('moodle', 'testMoodle');
    });
});
