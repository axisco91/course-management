<?php
namespace App\Http\Controllers\Api;
use App\Helpers\GeneralHelpers;
use App\Http\Resources\CertificationElementResource;
use App\Models\CertificationElement;
use App\Models\Module;
use App\Models\TrainingUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CertificationElementController extends BaseController
{
    public function index($id, Request $request) {
       $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

        $query = CertificationElement::GetCertificationElements($id, $mainCompanyId);
        // CON PAGINACIÓN
        if ($request->filled('perPage')) {
            $perPage   = (int) $request->perPage;

            $paginator = $query->paginate($perPage);

            // Resource sobre el paginator
            $certificationElements = CertificationElementResource::collection($paginator);
            // Si no tienes Resource, podrías usar directamente:
            // $certifications = $paginator->items();

            // Datos de paginación (usar SIEMPRE el paginator, NO el builder)
            $paginationData = GeneralHelpers::generatePaginationData($paginator);

            return $this->sendResponse(
                [
                    'certification_elements' => $certificationElements,
                    'links'          => $paginationData['links'],
                    'meta'           => $paginationData['meta'],
                ],
                trans('Obtenido con éxito')
            );
        }

        // SIN PAGINACIÓN
        $certificationElements = CertificationElementResource::collection($query->get());
        // o, sin resource: $certifications = $query->get();

        return $this->sendResponse(
            [
                'certification_elements' => $certificationElements,
            ],
            trans('Obtenido con éxito')
        );
    }

    public function show($id, Request $request) {
       $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

        $certificationElement = CertificationElement::getCertificationElement($id, $mainCompanyId);

        return $this->sendResponse(
            [
                'certification_element' => $certificationElement,
            ],
            trans('Obtenido con éxito')
        );
    }

    public function getModules($id, Request $request) {
       $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

        $module = Module::AvailableForCertification($id, $mainCompanyId)->get();

        return $this->sendResponse(
            [
                'module' => $module,
            ],
            trans('Obtenido con éxito')
        );
    }

    public function getUnits($id, Request $request) {
       $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

        return TrainingUnit::getTrainingUnitsNotInCertification($id, $mainCompanyId)->get();
    }

    public function create($id, Request $request){
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            $element = CertificationElement::createCertificationElement($id, $request['id'], $request['type'], $mainCompanyId);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
        $module = null;
        $unit = null;
        if ($element->module_id) {
            $module = Module::select('modules.*', 'modules.id as value', 'modules.name as label')
                ->where('id', $element->module_id)
                ->FilterMainCompany($mainCompanyId)
                ->first();
        }
        if ($element->training_unit_id) {
            $unit = TrainingUnit::select('training_units.*', 'training_units.id as value', 'training_units.name as label')
                ->where('id', $element->training_unit_id)
                ->FilterMainCompany($mainCompanyId)
                ->first();
        }
        $element = CertificationElement::getCertificationElement($element->id, $mainCompanyId);
        return response()->json([
            'status' => 200,
            'element' => $element,
            'module' => $module,
            'unit' => $unit
        ]);
    }

    public function destroy($id, Request $request) {
        if ($id) {
            try {
               $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

                $element = CertificationElement::where('certification_elements.id', $id)
                    ->FilterMainCompany($mainCompanyId)
                    ->first();

                $module = null;
                $unit = null;
                if ($element->module_id) {
                    $module = Module::select('modules.*', 'modules.id as value', 'modules.name as label')
                        ->where('id', $element->module_id)
                        ->FilterMainCompany($mainCompanyId)
                        ->first();

                    $module = Module::getModule($module->id, $mainCompanyId)->first();
                }
                if ($element->training_unit_id) {
                    $unit = TrainingUnit::select('training_units.*', 'training_units.id as value', 'training_units.name as label')
                        ->where('id', $element->training_unit_id)
                        ->FilterMainCompany($mainCompanyId)
                        ->first();
                    $unit = TrainingUnit::getTrainingUnit($unit->id, $mainCompanyId)->first();
                }
                $certificationElement = CertificationElement::where('id', $id)
                    ->FilterMainCompany($mainCompanyId)
                    ->first();

                if ($certificationElement) {
                    CertificationElement::deleteCertificationElement($id, $mainCompanyId);
                    CertificationElement::destroy($id);
                }

                return response()->json([
                    'status' => 200,
                    'module' => $module,
                    'unit' => $unit
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 400,
                    'message' => $e->getMessage()
                ]);
            }
        }
        return response()->json([
            'status' => 404,
            'message' => 'Elemento no encontrado'
        ]);
    }
}
