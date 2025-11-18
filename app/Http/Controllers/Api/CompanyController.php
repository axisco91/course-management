<?php

namespace App\Http\Controllers\Api;
use App\Helpers\GeneralHelpers;
use App\Http\Requests\CompanyRequests;
use App\Models\Advisor;
use App\Models\Company;
use App\Models\Course;
use App\Models\Provider;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CompanyController extends BaseController
{

    /**
     * Obtenemos empresas
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request) {
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
            $companies = Company::company($mainCompanyId);

            $user = User::find(Auth::id());
            if ($user->teacher_id) {
                $companies->leftjoin('registrations', 'registrations.company_id', '=', 'companies.id')
                    ->leftjoin('courses', 'courses.id', '=', 'registrations.course_id')
                    ->where('courses.teacher_id', $user->teacher_id);
            }

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

            $companies = $companies
        //        ->included()
        //        ->filter()
        //        ->sort()
                ->groupBy('companies.id', 'companies.name')
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
            if ($request->perPage) {
                return [
                    'companies' => $companies->paginate(intval(request('perPage'))),
                    'links' => $companies->links(),
                    'meta' => [
                        'current_page' => $companies->currentPage(),
                        'from' => $companies->firstItem(),
                        'last_page' => $companies->lastPage(),
                        'links' => $companies->getUrlRange(1, $companies->lastPage()),
                        'path' => $companies->resolveCurrentPath(),
                        'per_page' => $companies->perPage(),
                        'to' => $companies->lastItem(),
                        'total' => $companies->total(),
                    ]
                ];
            }
            return $companies;
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function getActiveCompanies(Request $request) {
       $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
        return Company::select('companies.*', 'id as value', 'name as label')
            ->FilterMainCompany($mainCompanyId)
            ->where('active', 1)->get();
    }

    /**
     * Obtener empresa
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id, Request $request) {
       $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
        $company = Company::company($mainCompanyId)
            ->where('companies.id', $id)
            ->first();
        $student = Student::where('company_id', $company['id'])
            ->FilterMainCompany($mainCompanyId)
            ->first();
        if ($student) {
            $company['used'] = true;
        } else {
            $advisor = Advisor::where('company_id', $company['id'])
                ->FilterMainCompany($mainCompanyId)
                ->first();
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
        $advisor = Advisor::where('company_id', $company['id'])
            ->FilterMainCompany($mainCompanyId)
            ->first();
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
    public function store(CompanyRequests $request){
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
            $data = $request->all();
            $data['main_company_id'] = $mainCompanyId;

            // Verifica que el campo 'agreement' está presente en los datos recibidos
            if (isset($data['agreement'])) {
                Log::info('Agreement received: ' . $data['agreement']);
            } else {
                Log::warning('Agreement not received');
            }

            $element = Company::createWithService($data);
            $company = Company::company($mainCompanyId)
                ->where('companies.id', $element->id)
                ->first();

            $student = Student::where('company_id', $company['id'])
                ->FilterMainCompany($mainCompanyId)
                ->first();
            if ($student) {
                $company['used'] = true;
            } else {
                $advisor = Advisor::where('company_id', $company['id'])
                    ->FilterMainCompany($mainCompanyId)
                    ->first();
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

            $advisor = Advisor::where('company_id', $company['id'])
                ->FilterMainCompany($mainCompanyId)
                ->first();
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
    public function update($id, CompanyRequests $request){
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
            $data = $request->all();

            // Verifica que el campo 'agreement' está presente en los datos recibidos
            if (isset($data['agreement'])) {
                Log::info('Agreement received: ' . $data['agreement']);
            } else {
                Log::warning('Agreement not received');
            }

            $company = Company::where('id', $id)
                ->FilterMainCompany($mainCompanyId)
                ->first();

            if (!$company) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Empresa no existe'
                ]);
            }
            $element = $company->updateWithService($data);
            $company = Company::where('companies.id', $element->id)
                ->first();

            $advisor = Advisor::where('company_id', $company->id)->first();
            if ($advisor) {
                $data['company_id'] = $company->id;
                $advisor->updateAdvisorCompany($advisor, $data);
            }

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
    public function destroy($id, Request $request){
        if ($id) {
            try {
               $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

                $company = Company::where('id', $id)
                    ->FilterMainCompany($mainCompanyId)
                    ->first();
                if (!$company) {
                    return response()->json([
                        'status' => 404,
                        'message' => 'Empresa no existe'
                    ]);
                }
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
    public function convertClient($id, Request $request){
        if ($id) {
            try {
               $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

                $company = Company::where('id', $id)
                    ->FilterMainCompany($mainCompanyId)
                    ->first();
                if (!$company) {
                    return response()->json([
                        'status' => 404,
                        'message' => 'Empresa no existe'
                    ]);
                }
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
    public function convertAdvisor($id, Request $request){
        if ($id) {
            try {
               $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

                $company = Company::where('id', $id)
                    ->FilterMainCompany($mainCompanyId)
                    ->first();
                if (!$company) {
                    return response()->json([
                        'status' => 404,
                        'message' => 'Empresa no existe'
                    ]);
                }

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
    public function convertProvider($id, Request $request){
        if ($id) {
            try {
               $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

                $company = Company::where('id', $id)
                    ->FilterMainCompany($mainCompanyId)
                    ->first();
                if (!$company) {
                    return response()->json([
                        'status' => 404,
                        'message' => 'Empresa no existe'
                    ]);
                }

                Provider::convertProvider($id, $company);
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
    public function getCompanyCourses($id, Request $request) {
       $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

        $company = Company::where('id', $id)
            ->FilterMainCompany($mainCompanyId)
            ->first();
        if (!$company) {
            return response()->json([
                'status' => 404,
                'message' => 'Empresa no existe'
            ]);
        }

        $courses = Course::companyCourses($id, $mainCompanyId)
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


    public function getCompanyStudents($id, Request $request) {
       $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

        $company = Company::where('id', $id)
            ->FilterMainCompany($mainCompanyId)
            ->first();
        if (!$company) {
            return response()->json([
                'status' => 404,
                'message' => 'Empresa no existe'
            ]);
        }

        return Student::companyStudents($id, $mainCompanyId)->get();
    }
}
