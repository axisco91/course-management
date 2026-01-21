<?php

namespace App\Http\Controllers\Api;

use App\Helpers\GeneralHelpers;
use App\Http\Resources\LevelStudyResource;
use App\Models\LevelStudy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LevelStudyController extends BaseController
{
    /**
     * Obtiene todos los niveles de estudio.
     *
     * Este método devuelve una lista de todos los niveles de estudio existentes en el sistema.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function levelStudies(Request $request) {
        try {
            $query = LevelStudy::select('*');

            if ($request->filled('perPage')) {
                $perPage = (int) $request->perPage;

                $paginator = $query->paginate($perPage);

                // Resource sobre el paginator
                $levelStudies = LevelStudyResource::collection($paginator);
                // Si no tienes Resource, podrías usar directamente:
                // $certifications = $paginator->items();

                // Datos de paginación (usar SIEMPRE el paginator, NO el builder)
                $paginationData = GeneralHelpers::generatePaginationData($paginator);

                return $this->sendResponse(
                    [
                        'level_studies' => $levelStudies,
                        'links'          => $paginationData['links'],
                        'meta'           => $paginationData['meta'],
                    ],
                    trans('Obtenido con éxito')
                );
            }

            // SIN PAGINACIÓN
            $levelStudies = LevelStudyResource::collection($query->get());
            // o, sin resource: $certifications = $query->get();

            return $this->sendResponse(
                [
                    'level_studies' => $levelStudies,
                ],
                trans('Obtenido con éxito')
            );
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Crea un nuevo nivel de estudio.
     *
     * Valida los datos de entrada y crea un nuevo registro en la tabla 'level_studies'.
     * Retorna el nivel de estudio recién creado.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 400,
                    'message' => $validator->errors()
                ]);
            }

            $levelStudy = LevelStudy::createWithService($request->all());
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return $this->sendResponse(
            [
                'level_study' => LevelStudy::where('level_studies.id', $levelStudy->id)->first(),
            ],
            trans('Creado con éxito')
        );
    }

    /**
     * Obtiene un nivel de estudio por su ID.
     *
     * Este método devuelve un nivel de estudio específico basado en el ID proporcionado.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     *
     */
    public function show($id)
    {
        try {
            $levelStudy = LevelStudy::where('level_studies.id', $id)->first();
            return $this->sendResponse(
                [
                    'level_study' => $levelStudy,
                ],
                trans('Obtenido con éxito')
            );
        } catch (\Exception $e) {
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }
    /**
     * Edita un nivel de estudio existente.
     *
     * Valida los datos de entrada y actualiza un nivel de estudio con el ID proporcionado.
     * Retorna el nivel de estudio actualizado.
     *
     * @param int $id
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id, Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 400,
                    'message' => $validator->errors()
                ]);
            }

            $levelStudy = LevelStudy::find($id);
            $levelStudy->updateWithService($request->all());

        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
        return $this->sendResponse(
            [
                'level_study' => $levelStudy,
            ],
            trans('Guardado con éxito')
        );
    }

    /**
     * Elimina un nivel de estudio.
     *
     * Elimina un registro de la tabla 'level_studies' basado en el ID proporcionado.
     * Retorna una respuesta indicando el éxito o fracaso de la operación.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id){
        if ($id) {
            try {
                LevelStudy::destroy($id);
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

    /**
     * Cuenta el número total de niveles de estudio.
     *
     * Devuelve el conteo total de registros en la tabla 'level_studies'.
     *
     * @return int
     */
    public function count(){
        return LevelStudy::count();
    }
}
