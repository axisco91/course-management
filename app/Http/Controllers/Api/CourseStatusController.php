<?php

namespace App\Http\Controllers\Api;
use App\Helpers\GeneralHelpers;
use App\Http\Resources\CourseStatusResource;
use App\Models\CourseStatus;
use Illuminate\Http\Request;

class CourseStatusController extends BaseController
{
    public function getCourseStatuses(Request $request) {
        try {
            $query = CourseStatus::select('*');

            if ($request->filled('perPage')) {
                $perPage = (int) $request->perPage;

                $paginator = $query->paginate($perPage);

                // Resource sobre el paginator
                $courseStatuses = CourseStatusResource::collection($paginator);
                // Si no tienes Resource, podrías usar directamente:
                // $certifications = $paginator->items();

                // Datos de paginación (usar SIEMPRE el paginator, NO el builder)
                $paginationData = GeneralHelpers::generatePaginationData($paginator);

                return $this->sendResponse(
                    [
                        'course_statuses' => $courseStatuses,
                        'links'          => $paginationData['links'],
                        'meta'           => $paginationData['meta'],
                    ],
                    trans('Obtenido con éxito')
                );
            }

            // SIN PAGINACIÓN
            $courseStatuses = CourseStatusResource::collection($query->get());
            // o, sin resource: $certifications = $query->get();

            return $this->sendResponse(
                [
                    'course_statuses' => $courseStatuses,
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
            $course = CourseStatus::createWithService($request->all());
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return $this->sendResponse(
            [],
            trans('Creado con éxito')
        );
    }

    public function edit($id, Request $request){
        try {
            $course = CourseStatus::find($id);
            $course->updateWithService($request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return $this->sendResponse(
            [],
            trans('Guardado con éxito')
        );
    }

    public function getCourseStatus($id){
        $status = CourseStatus::find($id);
        if ($status) {
            return $this->sendResponse(
                [
                    'course_status' => $status,
                ],
                trans('Obtenido con éxito')
            );
        }
        return response()->json([
            'status' => 400,
            'message' => 'Estado no existe'
        ]);
    }

    public function destroy($id){
        if ($id) {
            try {
                CourseStatus::destroy($id);
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
        return CourseStatus::count();
    }
}
