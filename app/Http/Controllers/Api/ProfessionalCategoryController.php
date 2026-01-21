<?php

namespace App\Http\Controllers\Api;
use App\Helpers\GeneralHelpers;
use App\Http\Resources\ProfessionalAreaResource;
use App\Models\ProfessionalCategory;
use Illuminate\Http\Request;

class ProfessionalCategoryController extends BaseController
{
    public function professionalCategories(Request $request) {
        try {
            $query = ProfessionalCategory::getProfessionalCategory();

            if ($request->filled('perPage')) {
                $perPage = (int) $request->perPage;

                $paginator = $query->paginate($perPage);

                // Resource sobre el paginator
                $professionalCategories = ProfessionalAreaResource::collection($paginator);
                // Si no tienes Resource, podrías usar directamente:
                // $certifications = $paginator->items();

                // Datos de paginación (usar SIEMPRE el paginator, NO el builder)
                $paginationData = GeneralHelpers::generatePaginationData($paginator);

                return $this->sendResponse(
                    [
                        'professional_categories' => $professionalCategories,
                        'links'          => $paginationData['links'],
                        'meta'           => $paginationData['meta'],
                    ],
                    trans('Obtenido con éxito')
                );
            }

            // SIN PAGINACIÓN
            $professionalCategories = ProfessionalAreaResource::collection($query->get());
            // o, sin resource: $certifications = $query->get();

            return $this->sendResponse(
                [
                    'professional_categories' => $professionalCategories,
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
            $category = ProfessionalCategory::createWithService($request->all());

        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return $this->sendResponse(
            [
                'professional_category' => $category,
            ],
            trans('Creado con éxito')
        );
    }

    public function edit($id, Request $request){

        try {
            $category = ProfessionalCategory::find($id);
            $category->updateWithService($request->all());
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return $this->sendResponse(
            [
                'professional_category' => $category,
            ],
            trans('Guardado con éxito')
        );
    }

    public function show($id){
        $category = ProfessionalCategory::getProfessionalCategory()->where('professional_categories.id', $id)->first();
        if ($category) {
            return $this->sendResponse(
                [
                    'professional_category' => $category,
                ],
                trans('Obtenido con éxito')
            );
        }
        return response()->json([
            'status' => 400,
            'message' => 'Categoria no existe'
        ]);
    }

    public function destroy($id){
        if ($id) {
            try {
                ProfessionalCategory::destroy($id);
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
        return ProfessionalCategory::count();
    }
}
