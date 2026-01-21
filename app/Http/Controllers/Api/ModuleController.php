<?php

namespace App\Http\Controllers\Api;
use App\Helpers\GeneralHelpers;
use App\Http\Resources\ModuleResource;
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

            $query = Module::getModules($mainCompanyId);

            if ($request->filled('perPage')) {
                $perPage = (int) $request->perPage;

                $paginator = $query->paginate($perPage);

                // Resource sobre el paginator
                $modules = ModuleResource::collection($paginator);
                // Si no tienes Resource, podrías usar directamente:
                // $certifications = $paginator->items();

                // Datos de paginación (usar SIEMPRE el paginator, NO el builder)
                $paginationData = GeneralHelpers::generatePaginationData($paginator);

                return $this->sendResponse(
                    [
                        'modules' => $modules,
                        'links'          => $paginationData['links'],
                        'meta'           => $paginationData['meta'],
                    ],
                    trans('Obtenido con éxito')
                );
            }

            // SIN PAGINACIÓN
            $modules = ModuleResource::collection($query->get());
            // o, sin resource: $certifications = $query->get();

            return $this->sendResponse(
                [
                    'modules' => $modules,
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
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            $data = $request->all();
            $data['main_company_id'] = $mainCompanyId;

            $module = Module::createModule($data);

            return $this->sendResponse(
                [
                    'module' => Module::getModule($module->id, $mainCompanyId)->first(),
                ],
                trans('Creado con éxito')
            );
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

            return $this->sendResponse(
                [
                    'module' => Module::getModule($module->id, $mainCompanyId)->first(),
                ],
                trans('Guardado con éxito')
            );
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function getModule($id, Request $request){
       $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

        $module = Module::getModule($id, $mainCompanyId)->first();
        if ($module) {
            return $this->sendResponse(
                [
                    'module' => $module,
                ],
                trans('Obtenido con éxito')
            );
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

    public function getUnits($id, Request $request) {
       $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

        return $this->sendResponse(
            [
                'training_units' => TrainingUnitsModule::getTrainingUnitModules($id, $mainCompanyId)->get(),
            ],
            trans('Obtenido con éxito')
        );
    }

    public function getNotUsedUnits($id, Request $request) {
       $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

        return $this->sendResponse(
            [
                'not_used_units' => TrainingUnit::getTrainingUnitsNotInModule($id, $mainCompanyId)->get(),
            ],
            trans('Obtenido con éxito')
        );
    }

    public function addUnit($id, Request $request){
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            $trainingUnit = TrainingUnitsModule::createTrainingUnitModule($id, $request['unit_id'], $mainCompanyId);

            return $this->sendResponse(
                [
                    'training_unit_module' => TrainingUnitsModule::getTrainingUnitModule($trainingUnit->id, $mainCompanyId),
                    'module' => Module::getModule($id, $mainCompanyId)->first(),
                ],
                trans('Obtenido con éxito')
            );
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

        return $this->sendResponse(
            [
                'data' => $data,
            ],
            trans('Obtenido con éxito')
        );
    }

    public function count(Request $request){
       $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

        return Module::FilterMainCompany($mainCompanyId)->count();
    }
}
