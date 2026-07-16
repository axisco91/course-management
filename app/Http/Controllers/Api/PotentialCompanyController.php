<?php

namespace App\Http\Controllers\Api;
use App\Helpers\GeneralHelpers;
use App\Http\Resources\PotentialCompanyResource;
use App\Models\Advisor;
use App\Models\Company;
use App\Models\MainCompany;
use App\Models\PotentialCompany;
use App\Services\EmailDeliveryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class PotentialCompanyController extends BaseController
{
    public function __construct(private EmailDeliveryService $emailDeliveryService)
    {
    }

    public function getPotentialCompanies(Request $request) {
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
            $query = PotentialCompany::getPotentialCompanies($mainCompanyId);

            if ($request->filled('name')) {
                $query = $query->where('potential_companies.name', 'like', '%' . $request->name . '%');
            }
            if ($request->filled('nif')) {
                $query = $query->where('potential_companies.nif', 'like', '%' . $request->nif . '%');
            }
            if ($request->filled('email')) {
                $query = $query->where('potential_companies.email', 'like', '%' . $request->email . '%');
            }
            if ($request->filled('telephone')) {
                $query = $query->where('potential_companies.telephone', 'like', '%' . $request->telephone . '%');
            }
            $typeId = $request->filled('company_type_id') ? (int) $request->company_type_id : (int) $request->get('type', 0);
            if ($typeId > 0) {
                $query = $query->where('potential_companies.company_type_id', $typeId);
            }
            $activityId = $request->filled('company_activity_id') ? (int) $request->company_activity_id : (int) $request->get('activity', 0);
            if ($activityId > 0) {
                $query = $query->where('potential_companies.company_activity_id', $activityId);
            }
            $provinceId = (int) $request->get('province', 0);
            if ($provinceId > 0) {
                $query = $query->where('potential_companies.province_id', $provinceId);
            }

            // Front sends advisor id; potential_companies stores advisor_name (string).
            $advisorId = (int) $request->get('advisor', 0);
            if ($advisorId > 0) {
                $advisor = Advisor::select('name')->where('id', $advisorId)->first();
                if ($advisor && !empty($advisor->name)) {
                    $query = $query->where('potential_companies.advisor_name', 'like', '%' . $advisor->name . '%');
                } else {
                    $query = $query->whereRaw('1 = 0');
                }
            }

            // collaborator filter only if the column exists in this installation.
            $collaboratorId = (int) $request->get('collaborator', 0);
            if ($collaboratorId > 0 && Schema::hasColumn('potential_companies', 'collaborator_id')) {
                $query = $query->where('potential_companies.collaborator_id', $collaboratorId);
            }

            $status = strtolower((string) $request->get('status', ''));
            if ($status !== '') {
                if ($status === 'potential') {
                    if (Schema::hasColumn('potential_companies', 'potential')) {
                        $query = $query->where('potential_companies.potential', 1);
                    } else {
                        $query = $query->where('potential_companies.converted', 0);
                    }
                } elseif ($status === 'active' && Schema::hasColumn('potential_companies', 'active')) {
                    $query = $query->where('potential_companies.active', 1);
                } elseif ($status === 'inactive' && Schema::hasColumn('potential_companies', 'active')) {
                    $query = $query->where('potential_companies.active', 0);
                }
            }

            $sortParam = (string) $request->get('sort', 'name');
            $direction = str_starts_with($sortParam, '-') ? 'desc' : 'asc';
            $sortField = ltrim($sortParam, '-');

            $sortMap = [
                'name' => 'potential_companies.name',
                'nif' => 'potential_companies.nif',
                'email' => 'potential_companies.email',
                'type' => 'company_types.name',
                'activity' => 'company_activities.name',
                // Potential companies table has no `active`; closest status-like field is `converted`.
                'status' => 'potential_companies.converted',
            ];

            $sortColumn = $sortMap[$sortField] ?? 'potential_companies.name';
            $query = $query->reorder($sortColumn, $direction);

            if ($request->filled('perPage')) {
                $perPage = (int) $request->perPage;

                $paginator = $query->paginate($perPage);

                // Resource sobre el paginator
                $potentialCompanies = PotentialCompanyResource::collection($paginator);
                // Si no tienes Resource, podrías usar directamente:
                // $certifications = $paginator->items();

                // Datos de paginación (usar SIEMPRE el paginator, NO el builder)
                $paginationData = GeneralHelpers::generatePaginationData($paginator);

                return $this->sendResponse(
                    [
                        'potential_companies' => $potentialCompanies,
                        'links'          => $paginationData['links'],
                        'meta'           => $paginationData['meta'],
                    ],
                    trans('Obtenido con éxito')
                );
            }

            // SIN PAGINACIÓN
            $potentialCompanies = PotentialCompanyResource::collection($query->get());
            // o, sin resource: $certifications = $query->get();

            return $this->sendResponse(
                [
                    'potential_companies' => $potentialCompanies,
                ],
                trans('Obtenido con éxito')
            );
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function create(Request $request){
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            $data = $request->all();
            $data['main_company_id'] = $mainCompanyId;

            $company = PotentialCompany::createWithService($data);

            return $this->sendResponse(
                [
                    'potential_company' => $company,
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

    public function edit($id, Request $request){
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            $company = PotentialCompany::updatePotentialCompany($id, $request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return $this->sendResponse(
            [
                'potential_company' => $company,
            ],
            trans('Guardado con éxito')
        );
    }

    public function getPotentialCompany($id, Request $request){
       $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

        $company = PotentialCompany::where('id', $id)
            ->where('main_company_id', $mainCompanyId)
            ->first();

        if ($company) {
            return $this->sendResponse(
                [
                    'potential_company' => $company,
                ],
                trans('Obtenido con éxito')
            );
        }
        return response()->json([
            'status' => 404,
            'message' => 'Empresa no existe'
        ]);
    }

    public function destroy($id, Request $request){
        if ($id) {
            try {
               $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

                $company = PotentialCompany::where('id', $id)
                    ->where('main_company_id', $mainCompanyId)
                    ->first();

                if (!$company) {
                    return response()->json([
                        'status' => 404,
                        'message' => 'Empresa no existe'
                    ]);
                }

                PotentialCompany::destroy($id);
                return $this->sendResponse(
                    [],
                    trans('Eliminado con éxito')
                );
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 400,
                    'message' => $e->getMessage()
                ]);
            }
        }
    }

    public function count(Request $request){
       $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
        return PotentialCompany::FilterMainCompany($mainCompanyId)->count();
    }

    public function convertCompany($id, Request $request) {
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            $potentialCompany = PotentialCompany::where('id', $id)
                ->where('main_company_id', $mainCompanyId)
                ->first();

            if (!$potentialCompany) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Empresa no existe'
                ]);
            }

            Company::createWithService($request->all());

            $potentialCompany->updateWithService();

            return $this->sendResponse(
                [
                    'company' => $potentialCompany,
                ],
                trans('Generado con éxito')
            );
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }


    }

    public function sendEmail(Request $request){
        if ($request['email']){
            try {
               $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

                $mainCompany = MainCompany::find($mainCompanyId);
                $this->emailDeliveryService->sendTo(
                    $request['email'],
                    new \App\Mail\PotentialCompany($mainCompany->url, $mainCompany->name),
                    [
                        'mail_type' => 'potential_company',
                        'main_company_id' => $mainCompanyId,
                    ],
                    config('mail.default', 'smtp')
                );
                return $this->sendResponse(
                    [],
                    trans('Enviado con éxito')
                );
            } catch(\Exception $e) {
                return response()->json([
                    'status' => 400,
                    'message' => $e->getMessage()
                ]);
            }
        }
        return response()->json([
            'status' => 400,
            'message' => 'Error al enviar correo'
        ]);
    }
}
