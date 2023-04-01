<?php

namespace App\Http\Controllers\API;
use App\Models\Module;
use App\Models\TrainingUnit;
use App\Models\TrainingUnitsModule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ModuleController extends BaseController
{
    public function modules() {
        try {
            return Module::getModules();
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e.message
            ]);
        }
    }

    public function create(Request $request){
        $data = json_decode($request->getContent(), true);
        try {
            $module = Module::createModule($data);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'module' => Module::getModule($module->id)
        ]);
    }

    public function edit($id, Request $request){
        $data = json_decode($request->getContent(), true);
        try {
            $module = Module::updateModule($id, $data);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'error' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'module' => Module::getModule($module->id)
        ]);
    }

    public function getModule($id){
        $module = Module::getModule($id);
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

    public function destroy($id){
        if ($id) {
            try {
                Module::destroy($id);
                return response()->json([
                    'status' => 200
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 400,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }

    public function getUnits($id) {
        return response()->json([
            'units' => TrainingUnitsModule::getTrainingUnitModules($id)
        ]);
    }

    public function getNotUsedUnits($id) {
        return TrainingUnit::getTrainingUnitsNotInModule($id);
    }

    public function addUnit($id, Request $request){
        $data = json_decode($request->getContent(), true);
        try {
            $training_unit = TrainingUnitsModule::createTrainingUnitModule($id, $data['unit_id']);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'training_unit_module' => TrainingUnitsModule::getTrainingUnitModule($training_unit->id),
            'module' => Module::getModule($id)
        ]);
    }

    public function removeUnit($id){
        try {
            $data = TrainingUnitsModule::deleteTrainingUnitModule($id);
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

    public function count(){
        return Module::count();
    }
}
