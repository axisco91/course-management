<?php

namespace App\Http\Controllers\Api;
use App\Http\Requests\CompanyRequests;
use App\Models\Advisor;
use App\Models\Company;
use App\Models\Course;
use App\Models\Provider;
use App\Models\Student;
use App\Services\CompanyService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class CompanyController extends BaseController
{
    private $companyService;

    public function __construct(CompanyService $companyService)
    {
        $this->companyService = $companyService;
    }

    /**
     * Obtenemos empresas
     * @return \Illuminate\Http\JsonResponse
     */
    public function companies() {
        try {
            $companies = Company::company()
                ->orderBy('companies.name', 'asc')
                ->get();
            foreach ($companies as $company) {
                $company['used'] = false;
                $student = Student::where('company_id', $company['id'])->first();
                if ($student) {
                    $company['used'] = true;
                }
                $advisor = Advisor::where('company_id', $company['id'])->first();
                if ($advisor) {
                    $company['is_advisor'] = true;
                    $company['used'] = true;
                } else {
                    $company['is_advisor'] = false;
                }
                $provider = Provider::where('company_id', $company['id'])->first();
                if ($provider) {
                    $company['is_provider'] = true;
                    $company['used'] = true;
                } else {
                    $company['is_provider'] = false;
                }
                if ($company['potential'] === 1) {
                    $company['status'] = 'Potencial';
                } else if ($company['active'] === 0) {
                    $company['status'] = 'Inactivo';
                } else {
                    $company['status'] = 'Activo';
                }
            }
            return $companies;
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function getActiveCompanies(){
        return Company::select('companies.*', 'id as value', 'name as label')
            ->where('active', 1)->get();
    }

    /**
     * Obtener empresa
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCompany($id){
        $company = Company::company()
            ->where('companies.id', $id)
            ->first();
        $student = Student::where('company_id', $company['id'])->first();
        if ($student) {
            $company['used'] = true;
        } else {
            $advisor = Advisor::where('company_id', $company['id'])->first();
            if ($advisor){
                $company['used'] = true;
            } else {
                $provider = Provider::where('company_id', $company['id'])->first();
                if ($provider) {
                    $company['used'] = true;
                } else {
                    $company['used'] = false;
                }
            }
        }
        $advisor = Advisor::where('company_id', $company['id'])->first();
        if ($advisor) {
            $company['is_advisor'] = true;
        } else {
            $company['is_advisor'] = false;
        }
        $provider = Provider::where('company_id', $company['id'])->first();
        if ($provider) {
            $company['is_provider'] = true;
        } else {
            $company['is_provider'] = false;
        }
        if ($company['potential'] === 1) {
            $company['status'] = 'Potencial';
        } else if ($company['active'] === 0) {
            $company['status'] = 'Inactivo';
        } else {
            $company['status'] = 'Activo';
        }
        if ($company) {
            return response()->json([
                'status' => 200,
                'company' => $company
            ]);
        }
        return response()->json([
            'status' => 400,
            'message' => 'Empresa no existe'
        ]);
    }

    /**
     * Crear empresa
     * @param CompanyRequests $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(CompanyRequests $request){
        try {
            $data = $request->all();
            $element = $this->companyService->create($data);
            $company = Company::company()
                ->where('companies.id', $element->id)
                ->first();
            $student = Student::where('company_id', $company['id'])->first();
            if ($student) {
                $company['used'] = true;
            } else {
                $advisor = Advisor::where('company_id', $company['id'])->first();
                if ($advisor){
                    $company['used'] = true;
                } else {
                    $provider = Provider::where('company_id', $company['id'])->first();
                    if ($provider) {
                        $company['used'] = true;
                    } else {
                        $company['used'] = false;
                    }
                }
            }
            $advisor = Advisor::where('company_id', $company['id'])->first();
            if ($advisor) {
                $company['is_advisor'] = true;
            } else {
                $company['is_advisor'] = false;
            }
            $provider = Provider::where('company_id', $company['id'])->first();
            if ($provider) {
                $company['is_provider'] = true;
            } else {
                $company['is_provider'] = false;
            }
            if ($company['potential'] === 1) {
                $company['status'] = 'Potencial';
            } else if ($company['active'] === 0) {
                $company['status'] = 'Inactivo';
            } else {
                $company['status'] = 'Activo';
            }
            return response()->json([
                'status' => 200,
                'company' => $company
            ]);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Editar empresa
     * @param $id
     * @param CompanyRequests $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id, CompanyRequests $request){
        try {
            $data = $request->all();
            $company = Company::find($id);
            $element = $this->companyService->update($company, $data);
            $company = Company::company()
                ->where('companies.id', $element->id)
                ->first();
            $student = Student::where('company_id', $company['id'])->first();
            if ($student) {
                $company['used'] = true;
            } else {
                $advisor = Advisor::where('company_id', $company['id'])->first();
                if ($advisor){
                    $company['used'] = true;
                } else {
                    $provider = Provider::where('company_id', $company['id'])->first();
                    if ($provider) {
                        $company['used'] = true;
                    } else {
                        $company['used'] = false;
                    }
                }
            }
            $advisor = Advisor::where('company_id', $company['id'])->first();
            if ($advisor) {
                $company['is_advisor'] = true;
            } else {
                $company['is_advisor'] = false;
            }
            $provider = Provider::where('company_id', $company['id'])->first();
            if ($provider) {
                $company['is_provider'] = true;
            } else {
                $company['is_provider'] = false;
            }
            if ($company['potential'] === 1) {
                $company['status'] = 'Potencial';
            } else if ($company['active'] === 0) {
                $company['status'] = 'Inactivo';
            } else {
                $company['status'] = 'Activo';
            }
            return response()->json([
                'status' => 200,
                'company' => $company
            ]);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Eliminar empresa
     * @param $id
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function destroy($id){
        if ($id) {
            try {
                Company::destroy($id);
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
     * Convertir a cliente
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function convertClient($id){
        if ($id) {
            try {
                $company = Company::find($id);
                $company->update([
                    'potential' => 0
                ]);
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
        return response()->json([
            'status' => 400,
            'error' => 'No has pasado empresa'
        ]);
    }

    /**
     * Convertir a asesoria
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function convertAdvisor($id){
        if ($id) {
            try {
                Advisor::convertAdvisor($id);
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
        return response()->json([
            'status' => 400,
            'error' => 'No has pasado empresa'
        ]);
    }

    /**
     * Convertir a proveedor
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function convertProvider($id){
        if ($id) {
            try {
                Provider::convertProvider($id);
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
        return response()->json([
            'status' => 400,
            'error' => 'No has pasado empresa'
        ]);
    }

    /**
     * Obtenemos los cursos de las empresas
     * @param $id
     * @return mixed
     */
    public function getCompanyCourses($id) {
        $courses = Course::companyCourses($id)
            ->orderBy('beginning', 'DESC')
            ->get();
        if (count($courses)) {
            foreach ($courses as $course){
                $beginning = Carbon::parse($course['beginning'])->format('d/m/Y');
                $course['beginning'] = $beginning;
                $end = Carbon::parse($course['end'])->format('d/m/Y');
                $course['end'] = $end;
            }
        }
        return $courses;
    }


    public function getCompanyStudents($id) {
        return Student::companyStudents($id)->get();
    }

    public function companiesCSV(Request $request){
        try {
            $companies = Company::company();
            if ($request->name) {
                $companies = $companies->where('companies.name', 'like', '%'.$request->name.'%');
            }
            if ($request->nif) {
                $companies = $companies->where('companies.nif', 'like', '%'.$request->nif.'&');
            }
            if ($request->type) {
                $companies = $companies->where('company_types.name', 'like', '%'.$request->type.'%');
            }
            if ($request->activity) {
                $companies = $companies->where('company_activities.name', 'like', '%'.$request->activity.'%');
            }
            if ($request->advisor) {
                $companies = $companies->where('advisor.name', 'like', '%'.$request->advisor.'%');
            }
            if ($request->province) {
                $companies = $companies->where('provinces.name', 'like', '%'.$request->province.'%');
            }
            if ($request->status) {
                if ($request->status == 'Potential') {
                    $companies = $companies->where('companies.potential', 1);
                }else if ($request->status == 'Inactivo'){
                    $companies = $companies->where('companies.active', 0)->where('companies.potential', 0);
                } else if ($request->status == 'Activo'){
                    $companies = $companies->where('companies.active', 1)->where('companies.potential', 0);
                }
            }
            if ($request->collaborator) {
                //$companies = $companies->where('users.name', 'like', '%'.$province.'%');
            }

            $companies = $companies->orderBy('companies.name', 'asc')->get();

            $data = [];
            if (count($companies) > 0) {
                foreach ($companies as $company) {
                    $status = $company['potential'] == 1 ? 'Potential' : ($company['active'] == 0 ? 'Inactivo' : 'Active');
                    $element = [
                        'Nombre' => $company['name'],
                        'CIF' => $company['nif'],
                        'Tipo empresa' => $company['type'],
                        'Actividad empresa' => $company['activity'],
                        'Correo' => $company['email'],
                        'Teléfono' => $company['telephone'],
                        'Representante legal' => $company['legal_representative'],
                        'Dni representante legal' => $company['dni_legal_representative'],
                        'C. cotización' => $company['quote'],
                        'Colaborador' => $company['collaborator'],
                        'CNAE' => $company['cnae'],
                        'Plantilla media' => $company['average_template'],
                        'Iban' => $company['iban'],
                        'Sepa' => $company['sepa'],
                        'B2B' => $company['b2b'],
                        'Dirección' => $company['address'],
                        'Código postal' => $company['post_code'],
                        'Provincia' => $company['province'],
                        'Población' => $company['population'],
                        'Asesoría' => $company['advisor'],
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
