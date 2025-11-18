<?php

namespace App\Http\Controllers\Api;
use App\Helpers\GeneralHelpers;
use App\Models\Module;
use App\Models\TrainingUnit;
use App\Models\TrainingUnitsModule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ModuleController extends BaseController
{
    public function modules(Request $request) {
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            return Module::getModules($mainCompanyId);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function create(Request $request){
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            $data = $request->all();
            $data['main_company_id'] = $mainCompanyId;

            $module = Module::createModule($data);

            return response()->json([
                'status' => 200,
                'module' => Module::getModule($module->id, $mainCompanyId),
            ]);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function edit($id, Request $request){
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            $module = Module::where('id', $id)
                ->where('main_company_id', $mainCompanyId)
                ->first();

            if (!$module) {
                return response()->json([
                    'status' => 400,
                    'message' => 'Module no encontrado'
                ]);
            }

            $module->updateWithService($request);

            return response()->json([
                'status' => 200,
                'module' => Module::getModule($module->id, $mainCompanyId),
            ]);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function getModule($id, Request $request){
       $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

        $module = Module::getModule($id, $mainCompanyId);
        if ($module) {
            return response()->json([
                'status' => 200,
                'module' => $module
            ]);
        }
        return response()->json([
            'status' => 400,
            'message' => 'Modulo no existe'
        ]);
    }

    public function destroy($id, Request $request){
        if ($id) {
            try {
               $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

                $module = Module::where('id', $id)
                    ->where('main_company_id', $mainCompanyId)
                    ->first();

                if (!$module) {
                    return response()->json([
                        'status' => 400,
                        'message' => 'Module no encontrado'
                    ]);
                }

                Module::destroy($id);
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

    public function getUnits($id, Request $request) {
       $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

        return response()->json([
            'units' => TrainingUnitsModule::getTrainingUnitModules($id, $mainCompanyId)
        ]);
    }

    public function getNotUsedUnits($id, Request $request) {
       $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

        return TrainingUnit::getTrainingUnitsNotInModule($id, $mainCompanyId);
    }

    public function addUnit($id, Request $request){
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            $trainingUnit = TrainingUnitsModule::createTrainingUnitModule($id, $request['unit_id'], $mainCompanyId);

            return response()->json([
                'status' => 200,
                'training_unit_module' => TrainingUnitsModule::getTrainingUnitModule($trainingUnit->id, $mainCompanyId),
                'module' => Module::getModule($id, $mainCompanyId),
            ]);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function removeUnit($id, Request $request){
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            $data = TrainingUnitsModule::deleteTrainingUnitModule($id, $mainCompanyId);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'data' => $data
        ]);
    }

    public function count(Request $request){
       $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

        return Module::FilterMainCompany($mainCompanyId)->count();
    }
}
