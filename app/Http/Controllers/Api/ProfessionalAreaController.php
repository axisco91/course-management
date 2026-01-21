<?php

namespace App\Http\Controllers\Api;
use App\Helpers\GeneralHelpers;
use App\Http\Resources\ProfessionalAreaResource;
use App\Models\ProfessionalArea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfessionalAreaController extends BaseController
{
    public function professionalAreas(Request $request) {
        try {
            $query = ProfessionalArea::getProfessionalArea();

            if ($request->filled('perPage')) {
                $perPage = (int) $request->perPage;

                $paginator = $query->paginate($perPage);

                // Resource sobre el paginator
                    $professionalAreas = ProfessionalAreaResource::collection($paginator);
                // Si no tienes Resource, podrías usar directamente:
                // $certifications = $paginator->items();

                // Datos de paginación (usar SIEMPRE el paginator, NO el builder)
                $paginationData = GeneralHelpers::generatePaginationData($paginator);

                return $this->sendResponse(
                    [
                        'professional_areas' => $professionalAreas,
                        'links'          => $paginationData['links'],
                        'meta'           => $paginationData['meta'],
                    ],
                    trans('Obtenido con éxito')
                );
            }

            // SIN PAGINACIÓN
            $professionalAreas = ProfessionalAreaResource::collection($query->get());
            // o, sin resource: $certifications = $query->get();

            return $this->sendResponse(
                [
                    'professional_areas' => $professionalAreas,
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
            $area = ProfessionalArea::createWithService($request->all());
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return $this->sendResponse(
            [
                'professional_area' => ProfessionalArea::getProfessionalArea()->where('professional_area.id', $area->id)->first(),
            ],
            trans('Created con éxito')
        );
    }

    public function edit($id, Request $request){
        try {
            $area = ProfessionalArea::find($id);
            $area->updateWithService($request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return $this->sendResponse(
            [
                'professional_area' => ProfessionalArea::getProfessionalArea()->where('professional_area.id', $area->id)->first(),
            ],
            trans('Obtenido con éxito')
        );
    }

    public function getProfessionalArea($id){
        $area = ProfessionalArea::getProfessionalArea()->where('professional_area.id', $id)->first();
        if ($area) {
            return $this->sendResponse(
                [
                    'professional_area' => ProfessionalArea::getProfessionalArea()->where('professional_area.id', $area->id)->first(),
                ],
                trans('Obtenido con éxito')
            );
        }
        return response()->json([
            'status' => 400,
            'message' => 'Area no existe'
        ]);
    }

    public function destroy($id){
        if ($id) {
            try {
                ProfessionalArea::destroy($id);
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
        return ProfessionalArea::count();
    }
}
