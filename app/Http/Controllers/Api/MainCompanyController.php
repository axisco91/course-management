<?php

namespace App\Http\Controllers\Api;
use App\Helpers\GeneralHelpers;
use App\Http\Requests\CompanyRequests;
use App\Http\Resources\CompanyResource;
use App\Models\Advisor;
use App\Models\Company;
use App\Models\Course;
use App\Models\MainCompany;
use App\Models\Provider;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MainCompanyController extends BaseController
{

    /**
     * Obtenemos empresas
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request) {
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
            $query = Company::company($mainCompanyId);

            $user = User::find(Auth::id());
            if ($user->teacher_id) {
                $query->leftjoin('registrations', 'registrations.company_id', '=', 'companies.id')
                    ->leftjoin('courses', 'courses.id', '=', 'registrations.course_id')
                    ->where('courses.teacher_id', $user->teacher_id);
            }

            if ($request->name) {
                $query = $query->where('companies.name', 'like', '%'.$request->name.'%');
            }
            if ($request->nif) {
                $query = $query->where('companies.nif', 'like', '%'.$request->nif.'&');
            }
            if ($request->type) {
                $query = $query->where('company_types.name', 'like', '%'.$request->type.'%');
            }
            if ($request->activity) {
                $query = $query->where('company_activities.name', 'like', '%'.$request->activity.'%');
            }
            if ($request->advisor) {
                $query = $query->where('advisor.name', 'like', '%'.$request->advisor.'%');
            }
            if ($request->province) {
                $query = $query->where('provinces.name', 'like', '%'.$request->province.'%');
            }
            if ($request->status) {
                if ($request->status == 'Potential') {
                    $query = $query->where('companies.potential', 1);
                }else if ($request->status == 'Inactivo'){
                    $query = $query->where('companies.active', 0)->where('companies.potential', 0);
                } else if ($request->status == 'Activo'){
                    $query = $query->where('companies.active', 1)->where('companies.potential', 0);
                }
            }
            if ($request->collaborator) {
                //$companies = $companies->where('users.name', 'like', '%'.$province.'%');
            }

            $query = $query->groupBy('companies.id', 'companies.name');

            if ($request->filled('perPage')) {
                $perPage = (int) $request->perPage;

                $paginator = $query->paginate($perPage);

                // Resource sobre el paginator
                $companies = CompanyResource::collection($paginator);
                // Si no tienes Resource, podrías usar directamente:
                // $certifications = $paginator->items();

                // Datos de paginación (usar SIEMPRE el paginator, NO el builder)
                $paginationData = GeneralHelpers::generatePaginationData($paginator);

                return $this->sendResponse(
                    [
                        'companies' => $companies,
                        'links'          => $paginationData['links'],
                        'meta'           => $paginationData['meta'],
                    ],
                    trans('Obtenido con éxito')
                );
            }

            // SIN PAGINACIÓN
            $companies = CompanyResource::collection($query->get());
            // o, sin resource: $certifications = $query->get();

            return $this->sendResponse(
                [
                    'companies' => $companies,
                ],
                trans('Obtenido con éxito')
            );
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function basic(Request $request) {
        $mainCompany = null;
         if ($request->hostname) {
            $mainCompany = MainCompany::where('url', $request->hostname)->first();
        }

         if (!$mainCompany) {
             $mainCompany = MainCompany::find(1);
         }

        return $this->sendResponse(
            ['main_company' => $mainCompany],
            trans('success.Retrieved successfully')
        );
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

            return $this->sendResponse(
                [
                    'company' => $company,
                ],
                trans('Creado con éxito')
            );
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
                $advisor->updateAdvisorCompany($data);
            }

            return $this->sendResponse(
                [
                    'company' => $company,
                ],
                trans('Guardado con éxito')
            );
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
                return $this->sendResponse([]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 400,
                    'message' => $e->getMessage()
                ]);
            }
        }
    }
}
