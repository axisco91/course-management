<?php

namespace App\Http\Controllers\Api;
use App\Helpers\GeneralHelpers;
use App\Http\Resources\PotentialCompanyResource;
use App\Models\Company;
use App\Models\MainCompany;
use App\Models\PotentialCompany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class PotentialCompanyController extends BaseController
{
    public function getPotentialCompanies(Request $request) {
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
            $query = PotentialCompany::getPotentialCompanies($mainCompanyId);

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

                Mail::getSwiftMailer()
                    ->getTransport()
                    ->setUsername('zona@avzformacion.com')
                    ->setPassword('Avz.2021');
                Mail::to($request['email'])->send(new \App\Mail\PotentialCompany());
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
