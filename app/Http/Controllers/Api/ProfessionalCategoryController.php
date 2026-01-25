<?php

namespace App\Http\Controllers\Api;
use App\Helpers\GeneralHelpers;
use App\Http\Resources\ProfessionalAreaResource;
use App\Http\Resources\ProfessionalCategoryResource;
use App\Models\ProfessionalCategory;
use Illuminate\Http\Request;

class ProfessionalCategoryController extends BaseController
{
    public function professionalCategories(Request $request)
    {
        try {
            $query = ProfessionalCategory::getProfessionalCategory();

            // ✅ FILTRO POR NOMBRE
            // Ej: ?search=administracion
            if ($request->filled('search')) {
                $search = trim($request->input('search'));
                $query->where('name', 'LIKE', '%' . $search . '%');
            }

            // ✅ SORT (por defecto name asc)
            // ?sort=name   -> asc
            // ?sort=-name  -> desc
            $sort = $request->input('sort', 'name');
            $direction = 'asc';

            if (is_string($sort) && strlen($sort) > 0 && $sort[0] === '-') {
                $direction = 'desc';
                $sort = substr($sort, 1);
            }

            // (opcional) whitelist de columnas ordenables
            $allowedSorts = ['id', 'name', 'created_at', 'updated_at'];
            if (!in_array($sort, $allowedSorts, true)) {
                $sort = 'name';
                $direction = 'asc';
            }

            $query->orderBy($sort, $direction);

            // ✅ PAGINACIÓN
            if ($request->filled('perPage')) {
                $perPage = (int) $request->perPage;

                $paginator = $query->paginate($perPage);

                // ✅ Resource correcto para categorías
                $professionalCategories = ProfessionalCategoryResource::collection($paginator);

                $paginationData = GeneralHelpers::generatePaginationData($paginator);

                return $this->sendResponse(
                    [
                        'professional_categories' => $professionalCategories,
                        'links' => $paginationData['links'],
                        'meta'  => $paginationData['meta'],
                    ],
                    trans('Obtenido con éxito')
                );
            }

            // ✅ SIN PAGINACIÓN
            $professionalCategories = ProfessionalCategoryResource::collection($query->get());

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
