<?php

namespace App\Http\Controllers\Api;
use App\Http\Requests\TrainingActionRequests;
use App\Models\Course;
use App\Models\TrainingAction;
use App\Models\User;
use App\Services\TrainingActionService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

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
    public function index(Request $request) {
        try {
            $trainingActions = TrainingAction::trainingAction();
            $user = User::find(Auth::id());
            if ($user->teacher_id) {
                $trainingActions = $trainingActions->leftjoin('courses', 'courses.training_action_id', '=', 'training_actions.id')
                    ->where('courses.teacher_id', $user->teacher_id);
            }

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

            $trainingActions = $trainingActions->groupBy('training_actions.id', 'training_actions.name')
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
    public function store(TrainingActionRequests $request){
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

    public function update($id, TrainingActionRequests $request){
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
    public function show($id){
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
        $courses =  Course::trainingActionCourses($id);
        $user = User::find(Auth::id());
        if ($user->teacher_id) {
            $courses = $courses->where('courses.teacher_id', $user->teacher_id);
        }
        $courses =  $courses->get();
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

    
public static function indexPublic()
{
    // Primero, obtén todas las instancias de TrainingAction.
    $trainingActions = TrainingAction::all();

    // Luego, carga las relaciones en cada instancia usando el método `load`.
    $trainingActions->load([
        'actionType',
        'provider',
        'modality',
        'professionalArea',
        'professionalFamily',
        'trainingActionGroup',
        'trainingActionLevel',
        'tutoring',
        'webPlatform'
    ]);

    return $trainingActions;
}
}
