<?php

namespace App\Http\Controllers\Api;
use App\Models\Center;
use App\Models\Course;
use App\Services\CenterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CenterController extends BaseController
{
    private $centerService;

    public function __construct(CenterService $centerService)
    {
        $this->centerService = $centerService;
    }

    /**
     * Obtener centros
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCenters() {
        try {
            $centers = Center::getCenters()->get();
            foreach ($centers as $center) {
                 $course = Course::orWhere('delivery_center_id', $center['id'])
                     ->orWhere('formation_center_id', $center['id'])->first();
                 if ($course){
                     $center['used'] = true;
                 } else {
                     $center['used'] = false;
                 }
            }
            return $centers;
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function getCenter($id){
        $center = Center::getCenters()
            ->where('id', $id)
            ->first();
        if ($center) {
            $course = Course::orWhere('delivery_center_id', $center['id'])
                ->orWhere('formation_center_id', $center['id'])->first();
            if ($course){
                $center['used'] = true;
            } else {
                $center['used'] = false;
            }
            return response()->json([
                'status' => 200,
                'center' => $center
            ]);
        }
        return response()->json([
            'status' => 400,
            'message' => 'Centro no existe'
        ]);
    }

    /**
     * Crear
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request){
        try {
            $data = $request->all();
            $element = $this->centerService->create($data);
            $center = Center::getCenters()
                ->where('id', $element->id)
                ->first();
            if ($center) {
                $course = Course::orWhere('delivery_center_id', $center['id'])
                    ->orWhere('formation_center_id', $center['id'])->first();
                if ($course){
                    $center['used'] = true;
                } else {
                    $center['used'] = false;
                }
            }
            return response()->json([
                'status' => 200,
                'center' => $center
            ]);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Editar
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id, Request $request){
        try {
            $data = $request->all();
            $center = Center::find($id);
            $element = $this->centerService->update($center, $data);
            $center = Center::getCenters()
                ->where('id', $element->id)
                ->first();
            if ($center) {
                $course = Course::orWhere('delivery_center_id', $center['id'])
                    ->orWhere('formation_center_id', $center['id'])->first();
                if ($course){
                    $center['used'] = true;
                } else {
                    $center['used'] = false;
                }
            }
            return response()->json([
                'status' => 200,
                'center' => $center
            ]);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Eliminar
     * @param $id
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function destroy($id){
        if ($id) {
            try {
                Center::destroy($id);
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
}
