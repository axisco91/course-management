<?php

namespace App\Http\Controllers\Api;
use App\Helpers\GeneralHelpers;
use App\Http\Resources\ProfessionalFamilyResource;
use App\Models\ProfessionalFamily;
use Illuminate\Http\Request;

class ProfessionalFamilyController extends BaseController
{
    public function professionalFamilies(Request $request)
    {
        try {
            $query = ProfessionalFamily::getProfessionalFamily();

            // ✅ FILTRO POR NOMBRE
            // Ej: ?search=industria
            if ($request->filled('search')) {
                $search = trim($request->input('search'));
                $query->where('name', 'LIKE', '%' . $search . '%');
            }

            // ✅ SORT
            // ?sort=name      -> asc
            // ?sort=-name     -> desc
            $sort = $request->input('sort', 'name');
            $direction = 'asc';

            if (is_string($sort) && strlen($sort) > 0 && $sort[0] === '-') {
                $direction = 'desc';
                $sort = substr($sort, 1);
            }

            // whitelist de columnas ordenables
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

                $professionalFamilies = ProfessionalFamilyResource::collection($paginator);

                $paginationData = GeneralHelpers::generatePaginationData($paginator);

                return $this->sendResponse(
                    [
                        'professional_families' => $professionalFamilies,
                        'links' => $paginationData['links'],
                        'meta'  => $paginationData['meta'],
                    ],
                    trans('Obtenido con éxito')
                );
            }

            // ✅ SIN PAGINACIÓN
            $professionalFamilies = ProfessionalFamilyResource::collection($query->get());

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
            $family->updateWithService($request->all());
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
