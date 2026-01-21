<?php

namespace App\Http\Controllers\Api;
use App\Helpers\GeneralHelpers;
use App\Http\Resources\CourseOriginResource;
use App\Models\CourseOrigin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CourseOriginController extends BaseController
{
    public function getCourseOrigins(Request $request) {
        try {
            $query = CourseOrigin::getCourseOrigin();

            if ($request->filled('perPage')) {
                $perPage = (int) $request->perPage;

                $paginator = $query->paginate($perPage);

                // Resource sobre el paginator
                $courseOrigins = CourseOriginResource::collection($paginator);
                // Si no tienes Resource, podrías usar directamente:
                // $certifications = $paginator->items();

                // Datos de paginación (usar SIEMPRE el paginator, NO el builder)
                $paginationData = GeneralHelpers::generatePaginationData($paginator);

                return $this->sendResponse(
                    [
                        'course_origins' => $courseOrigins,
                        'links'          => $paginationData['links'],
                        'meta'           => $paginationData['meta'],
                    ],
                    trans('Obtenido con éxito')
                );
            }

            // SIN PAGINACIÓN
            $courseOrigins = CourseOriginResource::collection($query->get());
            // o, sin resource: $certifications = $query->get();

            return $this->sendResponse(
                [
                    'course_origins' => $courseOrigins,
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
            $origin = CourseOrigin::createWithService($request->all());
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return $this->sendResponse(
            [
                'course_origins' => CourseOrigin::getCourseOrigin()->where('id', $origin->id)->first(),
            ],
            trans('Creado con éxito')
        );
    }

    public function edit($id, Request $request){
        try {
            $origin = CourseOrigin::find($id);

            $origin->updateWithService($request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return $this->sendResponse(
            [
                'course_origins' => CourseOrigin::getCourseOrigin()->where('id', $origin->id)->first(),
            ],
            trans('Guardado con éxito')
        );
    }

    public function getCourseOrigin($id){
        $type = CourseOrigin::getCourseType()->where('id', $id)->first();
        if ($type) {
            return $this->sendResponse(
                [
                    'course_origins' => CourseOrigin::getCourseOrigin()->where('id', $id)->first(),
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
                CourseOrigin::destroy($id);
                return $this->sendResponse(
                    [],
                    trans('Elimiando con éxito')
                );
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 400,
                    'message' => $e->getMessage()
                ]);
            }
        }
    }
}
