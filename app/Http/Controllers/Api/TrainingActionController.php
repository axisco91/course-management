<?php

namespace App\Http\Controllers\Api;
use App\Http\Requests\TrainingActionRequests;
use App\Models\Course;
use App\Models\TrainingAction;
use App\Services\TrainingActionService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class TrainingActionController extends BaseController
{
    private $trainingActionService;

    public function __construct(TrainingActionService $trainingActionService)
    {
        $this->trainingActionService = $trainingActionService;
    }

    /**
     * Obtenemos las acciones formativas
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTrainingActions() {
        try {
            $trainingActions = TrainingAction::trainingAction()
                ->orderby('id', 'asc')
                ->get();
            if (count($trainingActions) > 0) {
                foreach($trainingActions as $trainingAction) {
                    $course = Course::where('training_action_id', $trainingAction->id)->first();
                    if ($course) {
                        $trainingAction['used'] = true;
                    } else {
                        $trainingAction['used'] = false;
                    }
                }
            }
            return $trainingActions;
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Obtenemos las acciones formativas activas
     * @return \Illuminate\Http\JsonResponse
     */
    public function getActiveTrainingActions() {
        try {
            return TrainingAction::active()
                ->get();
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Creamos las acciones formativas
     * @param TrainingActionRequests $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(TrainingActionRequests $request){
        try {
            $data = $request->all();
            $element = $this->trainingActionService->create($data);
            $trainingAction = TrainingAction::trainingAction()
                ->where('training_actions.id', $element->id)
                ->first();
            $course = Course::where('training_action_id', $trainingAction->id)->first();
            if ($course) {
                $trainingAction['used'] = true;
            } else {
                $trainingAction['used'] = false;
            }
          return response()->json([
              'status' => 200,
              'training_action' => $trainingAction
          ]);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function edit($id, TrainingActionRequests $request){
        try {
            $data = $request->all();
            $trainingAction = TrainingAction::find($id);
            $element = $this->trainingActionService->update($trainingAction, $data);
            $trainingAction = TrainingAction::trainingAction()
                ->where('training_actions.id', $element->id)
                ->first();
            $course = Course::where('training_action_id', $trainingAction->id)->first();
            if ($course) {
                $trainingAction['used'] = true;
            } else {
                $trainingAction['used'] = false;
            }
            return response()->json([
                'status' => 200,
                'training_action' => $trainingAction
            ]);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Obtenemos la acción formativa
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTrainingAction($id){
        $trainingAction = TrainingAction::trainingAction()
            ->where('training_actions.id', $id)
            ->first();
        $course = Course::where('training_action_id', $trainingAction->id)->first();
        if ($course) {
            $trainingAction['used'] = true;
        } else {
            $trainingAction['used'] = false;
        }
        if ($trainingAction) {
            return response()->json([
                'status' => 200,
                'training_action' => $trainingAction
            ]);
        }
        return response()->json([
            'status' => 400,
            'message' => 'Acción Formativa no existe'
        ]);
    }

    /**
     * Eliminar acciones formativas
     * @param $id
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function destroy($id){
        if ($id) {
            try {
                TrainingAction::destroy($id);
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

    /**
     * Obtenemos el siguiente número de la acción formativa
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFormativeAction(){
        $training = TrainingAction::orderBy('id', 'desc')->first();
        $id = $training['id']+1;
        if ($id < 10) {
            $formativeAction = '00'.$id;
        }
        else if ($id < 100) {
            $formativeAction = '0'.$id;
        } else {
            $formativeAction = $id;
        }
        return response()->json([
            'formative_action' => $formativeAction
        ]);
    }

    /**
     * Obtener cursos de una acción formativa.
     * @param $id
     * @return mixed
     */
    public function getCourses($id) {
        $courses =  Course::trainingActionCourses($id)
            ->get();
        if (count($courses) > 0) {
            foreach ($courses as $course){
                $beginning = Carbon::parse($course['beginning'])->format('d/m/Y');
                $course['beginning'] = $beginning;
                $end = Carbon::parse($course['end'])->format('d/m/Y');
                $course['end'] = $end;
            }
        }
        return $courses;
    }

    /**
     * Obtener CSV de acciones formativas
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function trainingActionsCSV(Request $request){
        try {
            $trainingActions = TrainingAction::trainingAction();
            if ($request->formative_actions) {
                $trainingActions = $trainingActions->where('training_actions.formative_action', 'like', '%'.$request->formative_actions.'%');
            }
            if ($request->name) {
                $trainingActions = $trainingActions->where('training_actions.name', 'like', '%'.$request->name.'&');
            }
            if ($request->professional_family) {
                $trainingActions = $trainingActions->where('professional_families.name', 'like', '%'.$request->professional_family.'%');
            }
            if ($request->professional_area) {
                $trainingActions = $trainingActions->where('professional_areas.name', 'like', '%'.$request->professional_area.'%');
            }
            if ($request->modality) {
                $trainingActions = $trainingActions->where('modalities.name', 'like', '%'.$request->modality.'%');
            }
            if ($request->provider) {
                $trainingActions = $trainingActions->where('providers.name', 'like', '%'.$request->provider.'%');
            }
            if ($request->inactive == 'false') {
                $trainingActions = $trainingActions->where('training_actions.active', 1);
            }
            $trainingActions = $trainingActions->orderBy('training_actions.id','asc')->get();
            $data = [];
            if (count($trainingActions) > 0) {
                foreach ($trainingActions as $trainingAction) {
                    $element = [
                        'Acción Formativa' => $trainingAction['formative_action'],
                        'Nombre' => $trainingAction['name'],
                        'Tipo Acción' => $trainingAction['action_type'],
                        'Familia Professional' => $trainingAction['professional_family'],
                        'Área Professional' => $trainingAction['professional_area'],
                        'Modalidad' => $trainingAction['modality'],
                        'Nivel' => $trainingAction['training_action_level'],
                        'Grupo' => $trainingAction['training_action_group'],
                        'Tutorización' => $trainingAction['tutoring'],
                        'En Catalogo' => $trainingAction['in_catalog'],
                        'Horas Presenciales' => $trainingAction['face_to_face_hours'],
                        'Horas Teleformación' => $trainingAction['teletraining_hours'],
                        'Horas totales' => $trainingAction['total_hours'],
                        'Precio' => $trainingAction['price'],
                        'Objetivos' => $trainingAction['objectives'],
                        'Contenido' => $trainingAction['content'],
                        'Usuario' => $trainingAction['user'],
                        'Contraseña' => $trainingAction['password'],
                        'Plataforma' => $trainingAction['web_platform'],
                        'Observaciones' => $trainingAction['observations'],
                        'Número Actividades' => $trainingAction['number_activities'],
                        'Número Unidades' => $trainingAction['number_units'],
                        'Proveedor' => $trainingAction['provider'],
                        'Estado' => $trainingAction['active']
                    ];
                    $data[] = $element;
                }
            } else {
                $element = [
                    'Acción Formativa' => '',
                    'Nombre' => '',
                    'Tipo Acción' => '',
                    'Familia Professional' => '',
                    'Área Professional' => '',
                    'Modalidad' => '',
                    'Nivel' => '',
                    'Grupo' => '',
                    'Tutorización' => '',
                    'En Catalogo' => '',
                    'Horas Presenciales' => '',
                    'Horas Teleformación' => '',
                    'Horas totales' => '',
                    'Precio' => '',
                    'Objetivos' => '',
                    'Contenido' => '',
                    'Usuario' => '',
                    'Contraseña' => '',
                    'Plataforma' => '',
                    'Observaciones' => '',
                    'Número Actividades' => '',
                    'Número Unidades' => '',
                    'Proveedor' => '',
                    'Estado' => ''
                ];
                $data[] = $element;
            }
            return $data;
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }
}
