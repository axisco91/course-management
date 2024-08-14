<?php

namespace App\Http\Controllers\Api;

use App\Models\LevelStudy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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
    public function levelStudies() {
        try {
            return LevelStudy::getLevelStudies();
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
                'code' => 'required|string|size:2|unique:level_studies,code'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 400,
                    'message' => $validator->errors()
                ]);
            }

            $level_study = LevelStudy::createLevelStudy($request->all());
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
        return response()->json([
            'status' => 200,
            'level_study' => LevelStudy::getLevelStudy($level_study->id)
        ]);
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
            $levelStudy = LevelStudy::getLevelStudy($id);
            return response()->json([
                'status' => 200,
                'level_study' => $levelStudy
            ]);
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
                'code' => 'required|string|size:2|unique:level_studies,code,'.$id
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 400,
                    'message' => $validator->errors()
                ]);
            }

            $level_study = LevelStudy::updateLevelStudy($id, $request->all());
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
        return response()->json([
            'status' => 200,
            'level_study' => LevelStudy::getLevelStudy($level_study->id)
        ]);
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
