<?php

namespace App\Http\Controllers\Api;
use App\Helpers\GeneralHelpers;
use App\Http\Resources\CourseTypeResource;
use App\Models\CourseType;
use Illuminate\Http\Request;

class CourseTypeController extends BaseController
{
    public function getCourseTypes(Request $request) {
        try {
            $query = CourseType::getCourseType();

            if ($request->filled('perPage')) {
                $perPage = (int) $request->perPage;

                $paginator = $query->paginate($perPage);

                // Resource sobre el paginator
                $courseTypes = CourseTypeResource::collection($paginator);
                // Si no tienes Resource, podrías usar directamente:
                // $certifications = $paginator->items();

                // Datos de paginación (usar SIEMPRE el paginator, NO el builder)
                $paginationData = GeneralHelpers::generatePaginationData($paginator);

                return $this->sendResponse(
                    [
                        'course_types' => $courseTypes,
                        'links'          => $paginationData['links'],
                        'meta'           => $paginationData['meta'],
                    ],
                    trans('Obtenido con éxito')
                );
            }

            // SIN PAGINACIÓN
            $courseTypes = CourseTypeResource::collection($query->get());
            // o, sin resource: $certifications = $query->get();

            return $this->sendResponse(
                [
                    'course_types' => $courseTypes,
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
            $type = CourseType::createWithService($request->all());
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return $this->sendResponse(
            [
                'course_type' => CourseType::getCourseType()->where('id', $type->id)->first(),
            ],
            trans('Creado con éxito')
        );
    }

    public function edit($id, Request $request){
        try {
            $type = CourseType::find($id);
            $type->updateWithService($request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return $this->sendResponse(
            [
                'course_type' => CourseType::getCourseType()->where('id', $type->id)->first(),
            ],
            trans('Guardado con éxito')
        );
    }

    public function getCourseType($id){
        $type = CourseType::getCourseType()->where('id', $id)->first();
        if ($type) {
            return $this->sendResponse(
                [
                    'course_type' => $type,
                ],
                trans('Obtenido con éxito')
            );
        }
        return response()->json([
            'status' => 400,
            'message' => 'Tipo no existe'
        ]);
    }

    public function destroy($id){
        if ($id) {
            try {
                CourseType::destroy($id);
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
        return CourseType::count();
    }
}
