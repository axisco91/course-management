<?php

namespace App\Http\Controllers\Api;
use App\Helpers\GeneralHelpers;
use App\Http\Resources\ProfessionalFamilyResource;
use App\Models\ProfessionalFamily;
use Illuminate\Http\Request;

class ProfessionalFamilyController extends BaseController
{
    public function professionalFamilies(Request $request) {
        try {
            $query = ProfessionalFamily::getProfessionalFamily();

            if ($request->filled('perPage')) {
                $perPage = (int) $request->perPage;

                $paginator = $query->paginate($perPage);

                // Resource sobre el paginator
                $professionalFamilies = ProfessionalFamilyResource::collection($paginator);
                // Si no tienes Resource, podrías usar directamente:
                // $certifications = $paginator->items();

                // Datos de paginación (usar SIEMPRE el paginator, NO el builder)
                $paginationData = GeneralHelpers::generatePaginationData($paginator);

                return $this->sendResponse(
                    [
                        'professional_families' => $professionalFamilies,
                        'links'          => $paginationData['links'],
                        'meta'           => $paginationData['meta'],
                    ],
                    trans('Obtenido con éxito')
                );
            }

            // SIN PAGINACIÓN
            $professionalFamilies = ProfessionalFamilyResource::collection($query->get());
            // o, sin resource: $certifications = $query->get();

            return $this->sendResponse(
                [
                    'professional_families' => $professionalFamilies,
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
            $family = ProfessionalFamily::createWithService($request->all());
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return $this->sendResponse(
            [
                'professional_family' => ProfessionalFamily::getProfessionalFamily()->where('professional_families.id', $family->id)->first(),
            ],
            trans('Creado con éxito')
        );
    }

    public function edit($id, Request $request){
        try {
            $family = ProfessionalFamily::find($id);
            $family->updateWithService($request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return $this->sendResponse(
            [
                'professional_family' => ProfessionalFamily::getProfessionalFamily()->where('professional_families.id', $family->id)->first(),
            ],
            trans('Editado con éxito')
        );
    }

    public function getProfessionalFamily($id){
        $family = ProfessionalFamily::getProfessionalFamily()->where('professional_families.id', $id)->first();
        if ($family) {
            return $this->sendResponse(
                [
                    'professional_family' => ProfessionalFamily::getProfessionalFamily()->where('professional_families.id', $family->id)->first(),
                ],
                trans('Obtenido con éxito')
            );
        }
        return response()->json([
            'status' => 400,
            'message' => 'Familia no existe'
        ]);
    }

    public function destroy($id){
        if ($id) {
            try {
                ProfessionalFamily::destroy($id);
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

    public function count(){
        return ProfessionalFamily::count();
    }
}
