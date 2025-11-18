<?php

namespace App\Http\Controllers\Api;
use App\Helpers\GeneralHelpers;
use App\Models\Center;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CenterController extends BaseController
{

    /**
     * Obtener centros
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCenters(Request $request) {
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            $centers = Center::getCenter($mainCompanyId)->get();
            foreach ($centers as $center) {
                 $course = Course::orWhere('delivery_center_id', $center['id'])
                     ->orWhere('formation_center_id', $center['id'])
                     ->FilterMainCompany($mainCompanyId)
                     ->first();

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

    public function getCenter($id, Request $request) {
       $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

        $center = Center::getCenter($mainCompanyId)
            ->where('id', $id)
            ->first();

        if ($center) {
            $course = Course::orWhere('delivery_center_id', $center['id'])
                ->orWhere('formation_center_id', $center['id'])
                ->FilterMainCompany($mainCompanyId)
                ->first();

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
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            $data = $request->all();
            $data['main_company_id'] = $mainCompanyId;

            $element = Center::createWithService($data);
            $center = Center::getCenter($mainCompanyId)
                ->where('id', $element->id)
                ->first();

            if ($center) {
                $course = Course::orWhere('delivery_center_id', $center['id'])
                    ->orWhere('formation_center_id', $center['id'])
                    ->FilterMainCompany($mainCompanyId)
                    ->first();

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
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
            $data = $request->all();

            $center = Center::where('centers.id', $id)
                ->FilterMainCompany($mainCompanyId)
                ->first();

            $element = $center->updateWithService($data);
            $center = Center::getCenter($mainCompanyId)
                ->where('id', $element->id)
                ->first();

            if ($center) {
                $course = Course::orWhere('delivery_center_id', $center['id'])
                    ->orWhere('formation_center_id', $center['id'])
                    ->FilterMainCompany($mainCompanyId)
                    ->first();
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
    public function destroy($id, Request $request){
        if ($id) {
            try {
               $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
                $center = Center::where('id', $id)
                    ->FilterMainCompany($mainCompanyId);

                if (!$center) {
                    return response()->json([
                        'status' => 404,
                        'message' => 'Centro no existe'
                    ]);
                }

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
