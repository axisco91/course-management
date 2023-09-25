<?php

namespace App\Http\Controllers\Api;
use App\Models\Advisor;
use App\Models\Company;
use App\Models\Course;
use App\Models\TrainingContract;
use App\Models\User;
use App\Services\AdvisorService;
use App\Services\CompanyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdvisorController extends BaseController
{
    private $advisorService;
    private $companyService;

    public function __construct(AdvisorService $advisorService, CompanyService $companyService)
    {
        $this->advisorService = $advisorService;
        $this->companyService = $companyService;
    }

    /**
     * Obtener asesorías
     * @return mixed
     */
    public function advisors(Request $request) {
        try {
            $advisors = Advisor::getAdvisor();
            $user = User::find(Auth::id());
            if ($user->teacher_id) {
                $advisors = $advisors->leftjoin('billings', 'billings.advisor_id', '=', 'advisors.id')
                    ->leftjoin('courses', 'courses.id', '=', 'billings.course_id')
                    ->where('courses.teacher_id', $user->teacher_id);
            }
            $advisors = $advisors
                ->groupBy('advisors.id', 'advisors.name')
                ->get();
            foreach ($advisors as $advisor) {
                $company = Company::where('advisor_id', $advisor->id)->first();
                if ($company) {
                    $advisor['used'] = true;
                } else {
                    $advisor['used'] = false;
                }
                $company_info = Company::select('companies.*', 'advisors.name as advisor')
                    ->leftjoin('advisors', 'advisors.id', '=', 'companies.advisor_id')
                    ->where('companies.id', $advisor->company_id)->first();
                $advisor['advisor_id'] = $company_info->advisor_id;
                $advisor['advisor'] = $company_info->advisor;
            }
            return $advisors;
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    } // end method

    /**
     * Obtener asesorías activas
     * @return mixed
     */
    public function getActiveAdvisors(){
        return Advisor::select('advisors.*', 'id as value', 'name as label')
            ->where('active', 1)
            ->get();
    }

    /**
     * Crear asesoría
     * @param Request $request
     * @return void
     */
    public function create(Request $request){
        try {
            $data = $request->all();
            if (!$request['company_id']){
                $company = $this->companyService->create($data);
                if ($company){
                    $data['company_id'] = $company->id;
                }
            }
            $advisor = $this->advisorService->create($data);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'advisor' => Advisor::getAdvisor($advisor->id)
        ]);
    } // end method

    /**
     * Editar asesoría
     * @param $id
     * @param Request $request
     * @return int
     */
    public function edit($id, Request $request){
        try {
            $data = $request->all();
            $company = Company::find($request->company_id);
            $this->companyService->update($company, $data);
            $advisor = Advisor::find($id);
            $advisor = $this->advisorService->update($advisor, $data);

            return response()->json([
                'status' => 200,
                'advisor' => Advisor::getAdvisor($advisor->id)
            ]);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Obtener asesoría
     * @param $id
     * @return mixed
     */
    public function getAdvisor($id){
        $advisor = Advisor::getAdvisor()
            ->where('advisors.id', $id)
            ->first();
        if ($advisor) {
            $company = Company::where('advisor_id', $advisor->id)->first();
            if ($company) {
                $advisor['used'] = true;
            } else {
                $advisor['used'] = false;
            }
            $company_info = Company::select('companies.*', 'advisors.name as advisor')
                ->leftjoin('advisors', 'advisors.id', '=', 'companies.advisor_id')
                ->where('companies.id', $advisor->company_id)->first();
            $advisor['advisor_id'] = $company_info->advisor_id;
            $advisor['advisor'] = $company_info->advisor;
            return response()->json([
                'status' => 200,
                'advisor' => $advisor
            ]);
        }
        return response()->json([
            'status' => 400,
            'message' => 'Asesoría no existe'
        ]);
    }

    /**
     * Eliminar asesoría
     * @param $id
     * @return int|void
     */
    public function destroy($id){
        if ($id) {
            try {
                Advisor::destroy($id);
                return response()->json([
                    'status' => 200
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 400,
                    'message' => $e->getMessage()
                ]);
            }
        }
    }

    /**
     * Comprueba si el nif existe
     * @param Request $request
     * @return int
     */
    public function checkNif(Request $request){
        if ($request->nif){
            $nif = Advisor::findNif($request->nif);
            if ($nif){
                return 1;
            }
        }
    }

    /**
     * Convert Company to Advisor
     * @param $id
     * @return int
     */
    public function convertAdvisor($id){
        $advisor = Advisor::find($id);
        $advisor = $this->advisorService->convertAdvisor($advisor, $id);
        if ($advisor){
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * Obtenemos los cursos de la asesoría
     * @param $id
     * @return mixed
     */
    public function getAdvisorCourses($id) {
        $courses = Course::select('courses.*')
            ->leftjoin('registrations', 'registrations.course_id', '=', 'courses.id')
            ->leftjoin('billings', 'billings.id', '=', 'registrations.billing_id')
            ->where('billings.advisor_id', $id);

        $user = User::find(Auth::id());
        if ($user->teacher_id) {
            $courses = $courses->where('courses.teacher_id', $user->teacher_id);
        }

        return $courses
            ->groupBy('courses.id', 'courses.name')->get();
    }

    /**
     * Obtenemos los CFA de la asesoría
     * @param $id
     * @return mixed
     */
    public function getAdvisorTrainingContracts($id) {
        $trainingActions = TrainingContract::select('training_contracts.*', 'companies.name as company_name', 'students.name as student_name', 'students.surname as student_surname')
            ->leftjoin('companies', 'companies.id', '=', 'training_contracts.company_id')
            ->leftjoin('students', 'students.id', '=', 'training_contracts.student_id')
            ->where('training_contracts.advisor_id', $id);

        $user = User::find(Auth::id());
        if ($user->teacher_id) {
            $trainingActions->leftjoin('training_contract_elements', 'training_contract_elements.training_contract_id'. '='. 'training_contracts.id')
                ->leftjoin('courses', 'courses.id', '=', 'training_contract_elements.course_id')
                ->where('courses.teacher_id', $user->teacher_id);
        }

        return $trainingActions->groupBy('training_contracts.id', 'training_contracts.number_cfa')->get();
    }

    /**
     * Obtenemos las empresas de la asesoría
     * @param $id
     * @return mixed
     */
    public function getAdvisorCompanies($id) {

        $companies = Company::select('companies.*')->where('advisor_id', $id);

        $user = User::find(Auth::id());
        if ($user->teacher_id) {
            $companies = $companies->leftjoin('registrations', 'registrations.company_id', '=', 'companies.id')
                ->leftjoin('courses', 'courses.id', '=', 'registrations.course_id')
                ->where('courses.teacher_id', $user->teacher_id);
        }

        return $companies
            ->groupBy('companies.id', 'companies.name')
            ->get();
    }

    /**
     * Obtenemos csv
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function advisorsCSV(Request $request){
        try {
            $advisors = Advisor::getAdvisor();

            if ($request->name) {
                $advisors = $advisors->where('advisors.name', 'like', '%'.$request->name.'%');
            }
            if ($request->nif) {
                $advisors = $advisors->where('advisors.nif', 'like', '%'.$request->nif.'&');
            }
            if ($request->type) {
                $advisors = $advisors->where('company_types.name', 'like', '%'.$request->type.'%');
            }
            if ($request->activity) {
                $advisors = $advisors->where('company_activities.name', 'like', '%'.$request->activity.'%');
            }
            if ($request->province) {
                $advisors = $advisors->where('provinces.name', 'like', '%'.$request->province.'%');
            }
            if ($request->inactive == 'false') {
                $advisors = $advisors->where('advisors.active', 1);
            }

            $advisors = $advisors->orderBy('advisors.name', 'desc')->get();

            $data = [];
            if (count($advisors) > 0) {
                foreach ($advisors as $advisor) {
                    $status = $advisor['potential'] == 1 ? 'Potential' : ($advisor['active'] == 0 ? 'Inactivo' : 'Active');
                    $element = [
                        'Nombre' => $advisor['name'],
                        'CIF' => $advisor['nif'],
                        'Tipo empresa' => $advisor['type'],
                        'Actividad empresa' => $advisor['activity'],
                        'Correo' => $advisor['email'],
                        'Teléfono' => $advisor['telephone'],
                        'Representante legal' => $advisor['legal_representative'],
                        'Dni representante legal' => $advisor['dni_legal_representative'],
                        'IRPF' => $advisor['irpf'],
                        'Commisiones' => $advisor['commission'],
                        'Contacto 1' => $advisor['contact_1'],
                        'Contacto 2' => $advisor['contact_2'],
                        'Contacto 3' => $advisor['contact_3'],
                        'C. cotización' => $advisor['quote'],
                        'Colaborador' => $advisor['collaborator'],
                        'CNAE' => $advisor['cnae'],
                        'Plantilla media' => $advisor['average_template'],
                        'Iban' => $advisor['iban'],
                        'Sepa' => $advisor['sepa'],
                        'B2B' => $advisor['b2b'],
                        'Dirección' => $advisor['address'],
                        'Código postal' => $advisor['post_code'],
                        'Provincia' => $advisor['province'],
                        'Población' => $advisor['population'],
                        'Asesoría' => $advisor['advisor'],
                        'Estado' => $status
                    ];
                    $data[] = $element;
                }
            } else {
                $element = [
                    'Nombre' => '',
                    'CIF' => '',
                    'Tipo empresa' => '',
                    'Actividad empresa' => '',
                    'Correo' => '',
                    'Teléfono' => '',
                    'Representante legal' => '',
                    'Dni representante legal' => '',
                    'IRPF' => '',
                    'Commisiones' => '',
                    'Contacto 1' => '',
                    'Contacto 2' => '',
                    'Contacto 3' => '',
                    'C. cotización' => '',
                    'Colaborador' => '',
                    'CNAE' => '',
                    'Plantilla media' => '',
                    'Iban' => '',
                    'Sepa' => '',
                    'B2B' => '',
                    'Dirección' => '',
                    'Código postal' => '',
                    'Provincia' => '',
                    'Población' => '',
                    'Asesoría' => '',
                    'Estado' => ''
                ];
                $data[] = $element;
            }
            return $data;
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }
}
