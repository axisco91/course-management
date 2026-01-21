<?php

namespace App\Http\Controllers\Api;
use App\Helpers\GeneralHelpers;
use App\Http\Resources\TeacherAreaResource;
use App\Models\TeacherArea;
use Illuminate\Http\Request;

class TeacherAreaController extends BaseController
{
    public function teacherAreas(Request $request) {
        try {
            $query = TeacherArea::getTeacherArea();
            if ($request->filled('perPage')) {
                $perPage = (int) $request->perPage;

                $paginator = $query->paginate($perPage);

                // Resource sobre el paginator
                $teacherAreas = TeacherAreaResource::collection($paginator);
                // Si no tienes Resource, podrías usar directamente:
                // $certifications = $paginator->items();

                // Datos de paginación (usar SIEMPRE el paginator, NO el builder)
                $paginationData = GeneralHelpers::generatePaginationData($paginator);

                return $this->sendResponse(
                    [
                        'teacher_areas' => $teacherAreas,
                        'links'          => $paginationData['links'],
                        'meta'           => $paginationData['meta'],
                    ],
                    trans('Obtenido con éxito')
                );
            }

            // SIN PAGINACIÓN
            $teacherAreas = TeacherAreaResource::collection($query->get());
            // o, sin resource: $certifications = $query->get();

            return $this->sendResponse(
                [
                    'teacher_areas' => $teacherAreas,
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

    public function create(Request $request){
        try {
            $teacherArea = TeacherArea::createTeacherArea($request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return $this->sendResponse(
            [
                'teacher_area' => $teacherArea,
            ],
            trans('Creado con éxito')
        );
    }

    public function edit($id, Request $request){
        try {
            $teacherArea = TeacherArea::updateTeacherArea($id, $request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return $this->sendResponse(
            [
                'teacher_area' => $teacherArea,
            ],
            trans('Guardado con éxito')
        );
    }

    public function show($id){
        $teacherArea = TeacherArea::getTeacherArea()->where('teacher_areas.id', $id)->first();
        if ($teacherArea) {
            return $this->sendResponse(
                [
                    'teacher_area' => $teacherArea,
                ],
                trans('Obtenido con éxito')
            );
        }
        return response()->json([
            'status' => 400,
            'message' => 'Área no existe'
        ]);
    }

    public function destroy($id){
        if ($id) {
            try {
                TeacherArea::destroy($id);
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
        return TeacherArea::count();
    }
}
