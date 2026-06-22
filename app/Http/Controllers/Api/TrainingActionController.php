<?php

namespace App\Http\Controllers\Api;
use App\Exports\TrainingActionsExport;
use App\Helpers\GeneralHelpers;
use App\Http\Requests\TrainingActionRequests;
use App\Http\Resources\TrainingActionResource;
use App\Models\Course;
use App\Models\TrainingAction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class TrainingActionController extends BaseController
{
    /**
     * Obtenemos las acciones formativas
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request) {
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
            $query = TrainingAction::trainingAction($mainCompanyId);
            $user = User::where('id', Auth::id())
                ->where('main_company_id', $mainCompanyId)
                ->first();

            if ($user->teacher_id) {
                $query = $query->leftjoin('courses', 'courses.training_action_id', '=', 'training_actions.id')
                    ->where('courses.teacher_id', $user->teacher_id);
            }

            if ($request->formative_action) {
                $query = $query->where('training_actions.formative_action', 'like', '%'.$request->formative_action.'%');
            }
            if ($request->name) {
                $query = $query->where('training_actions.name', 'like', '%'.$request->name.'%');
            }
            if ($request->professional_family) {
                $query = $query->where('professional_family_id', $request->professional_family);
            }
            if ($request->professional_area) {
                $query = $query->where('professional_area_id', $request->professional_area);
            }
            if ($request->modality) {
                $query = $query->where('modality_id', $request->modality);
            }
            if ($request->provider) {
                $query = $query->where('provider_id', $request->provider);
            }
            if ($request->course_origin) {
                $query = $query->where('course_origin_id', $request->course_origin);
            }
            if ($request->show_inactive == 'false') {
                $query = $query->where('training_actions.active', 1);
            }

            $query = $query->groupBy('training_actions.id', 'training_actions.name');

            if ($request->filled('perPage')) {
                $perPage = (int) $request->perPage;

                $paginator = $query->paginate($perPage);

                // Resource sobre el paginator
                $trainingActions = TrainingActionResource::collection($paginator);
                // Si no tienes Resource, podrías usar directamente:
                // $certifications = $paginator->items();

                // Datos de paginación (usar SIEMPRE el paginator, NO el builder)
                $paginationData = GeneralHelpers::generatePaginationData($paginator);

                return $this->sendResponse(
                    [
                        'training_actions' => $trainingActions,
                        'links'          => $paginationData['links'],
                        'meta'           => $paginationData['meta'],
                    ],
                    trans('Obtenido con éxito')
                );
            }

            // SIN PAGINACIÓN
            $trainingActions = TrainingActionResource::collection($query->get());
            // o, sin resource: $certifications = $query->get();

            return $this->sendResponse(
                [
                    'training_actions' => $trainingActions,
                ],
                trans('Obtenido con éxito')
            );
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
    public function getActiveTrainingActions(Request $request) {
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
            $trainingActions = TrainingAction::active($mainCompanyId);

            return $this->sendResponse(
                [
                    'training_actions' => $trainingActions->get(),
                ],
                trans('Obtenido con éxito')
            );
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
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
            $data = $request->all();
            $data['main_company_id'] = $mainCompanyId;

            $element = TrainingAction::createWithService($data);
            $trainingAction = TrainingAction::trainingAction($mainCompanyId)
                ->where('training_actions.id', $element->id)
                ->first();

            $course = Course::where('training_action_id', $trainingAction->id)
                ->FilterMainCompany($mainCompanyId)
                ->first();

            if ($course) {
                $trainingAction['used'] = true;
            } else {
                $trainingAction['used'] = false;
            }
            return $this->sendResponse(
                [
                    'training_action' => $trainingAction,
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

    public function update($id, TrainingActionRequests $request){
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
            $data = $request->all();
            $trainingAction = TrainingAction::where('id', $id)
                ->FilterMainCompany($mainCompanyId)
                ->first();

            if (!$trainingAction) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Acción Formativa no encontrada'
                ]);
            }

            $element = $trainingAction->updateWithService($data);
            $trainingAction = TrainingAction::trainingAction($mainCompanyId)
                ->where('training_actions.id', $element->id)
                ->first();
            $course = Course::where('training_action_id', $trainingAction->id)
                ->FilterMainCompany($mainCompanyId)
                ->first();
            if ($course) {
                $trainingAction['used'] = true;
            } else {
                $trainingAction['used'] = false;
            }
            return $this->sendResponse(
                [
                    'training_action' => $trainingAction,
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

    /**
     * Obtenemos la acción formativa
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id, Request $request){
       $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
        $trainingAction = TrainingAction::trainingAction($mainCompanyId)
            ->where('training_actions.id', $id)
            ->first();
        $course = Course::where('training_action_id', $trainingAction->id)
            ->FilterMainCompany($mainCompanyId)
            ->first();
        if ($course) {
            $trainingAction['used'] = true;
        } else {
            $trainingAction['used'] = false;
        }
        if ($trainingAction) {
            return $this->sendResponse(
                [
                    'training_action' => $trainingAction,
                ],
                trans('Obtenido con éxito')
            );
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
    public function destroy($id, Request $request){
        if ($id) {
            try {
               $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
                $trainingAction = TrainingAction::where('id', $id)
                    ->FilterMainCompany($mainCompanyId)
                    ->first();

                if (!$trainingAction) {
                    return response()->json([
                        'status' => 404,
                        'message' => 'Acción Formativa no encontrada'
                    ]);
                }

                TrainingAction::destroy($id);
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

    /**
     * Obtenemos el siguiente número de la acción formativa
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFormativeAction(Request $request)
    {
        $mainCompanyId = GeneralHelpers::urlObtainCompanyId(
            $request->headers->get('origin'),
            Auth::id()
        );

        // Si formative_action es "001", "002", etc.
        $max = TrainingAction::where('main_company_id', $mainCompanyId)
            ->selectRaw('MAX(CAST(formative_action AS UNSIGNED)) as max_value')
            ->value('max_value');

        $next = ((int) $max) + 1;

        // 3 dígitos: 1 -> 001, 10 -> 010
        $formativeAction = str_pad((string) $next, 3, '0', STR_PAD_LEFT);

        return $this->sendResponse(
            ['formative_action' => $formativeAction],
            trans('Obtenido con éxito')
        );
    }

    /**
     * Obtener cursos de una acción formativa.
     * @param $id
     * @return mixed
     */
    public function getCourses($id, Request $request)
    {
        try {
            $mainCompanyId = GeneralHelpers::urlObtainCompanyId(
                $request->headers->get('origin'),
                Auth::id()
            );

            $coursesQuery = Course::WithCourseData($mainCompanyId)
                ->where('training_action_id', $id)
                ->orderBy('beginning', 'DESC');

            $user = User::where('id', Auth::id())
                ->where('main_company_id', $mainCompanyId)
                ->first();

            if ($user && $user->teacher_id) {
                $coursesQuery = $coursesQuery->where('courses.teacher_id', $user->teacher_id);
            }

            // ✅ PAGINACIÓN
            if ($request->filled('perPage')) {
                $perPage = (int) $request->perPage;

                $paginator = $coursesQuery->paginate($perPage);

                // ✅ formatear SOLO la página actual
                $collection = $paginator->getCollection()->map(function ($course) {
                    if (!empty($course->beginning)) {
                        $course->beginning = Carbon::parse($course->beginning)->format('d/m/Y');
                    }
                    if (!empty($course->end)) {
                        $course->end = Carbon::parse($course->end)->format('d/m/Y');
                    }
                    return $course;
                });

                $paginator->setCollection($collection);

                $paginationData = GeneralHelpers::generatePaginationData($paginator);

                return $this->sendResponse(
                    [
                        // Si tienes CourseResource, mejor:
                        // 'courses' => CourseResource::collection($paginator),
                        // Si no, devuelves el paginator ya transformado:
                        'courses' => $paginator->items(),
                        'links'   => $paginationData['links'],
                        'meta'    => $paginationData['meta'],
                    ],
                    trans('Obtenido con éxito')
                );
            }

            // ✅ SIN PAGINACIÓN (como antes)
            $courses = $coursesQuery->get();

            if ($courses->count() > 0) {
                foreach ($courses as $course) {
                    if (!empty($course->beginning)) {
                        $course->beginning = Carbon::parse($course->beginning)->format('d/m/Y');
                    }
                    if (!empty($course->end)) {
                        $course->end = Carbon::parse($course->end)->format('d/m/Y');
                    }
                }
            }

            return $this->sendResponse(
                [
                    'courses' => $courses,
                ],
                trans('Obtenido con éxito')
            );
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public static function indexPublic(Request $request)
{
   $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
    // Primero, obtén todas las instancias de TrainingAction.
    $trainingActions = TrainingAction::FilterMainCompany($mainCompanyId)
        ->get();

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

    public function exportExcel(Request $request)
    {
        $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

        $query = TrainingAction::trainingAction($mainCompanyId);

        $user = User::where('id', Auth::id())
            ->where('main_company_id', $mainCompanyId)
            ->first();

        if ($user && $user->teacher_id) {
            $query = $query->leftJoin('courses', 'courses.training_action_id', '=', 'training_actions.id')
                ->where('courses.teacher_id', $user->teacher_id);
        }

        // filtros iguales a tu index
        if ($request->formative_action) {
            $query->where('training_actions.formative_action', 'like', '%' . $request->formative_action . '%');
        }
        if ($request->name) {
            $query->where('training_actions.name', 'like', '%' . $request->name . '%');
        }
        if ($request->professional_family) {
            $query->where('professional_family_id', $request->professional_family);
        }
        if ($request->professional_area) {
            $query->where('professional_area_id', $request->professional_area);
        }
        if ($request->modality) {
            $query->where('modality_id', $request->modality);
        }
        if ($request->provider) {
            $query->where('provider_id', $request->provider);
        }
        if ($request->course_origin) {
            $query->where('course_origin_id', $request->course_origin);
        }
        if ($request->show_inactive == 'false') {
            $query->where('training_actions.active', 1);
        }

        $query->groupBy('training_actions.id', 'training_actions.name');

        // ✅ para que salgan los nombres (ajusta relaciones si se llaman distinto)
        $query->with([
            'professionalFamily:id,name',
            'professionalArea:id,name',
            'modality:id,name',
            'provider:id,name',
            'trainingActionLevel:id,name',
            'courseOrigin:id,name',
        ]);

        $items = $query->get();

        $yesNo = fn($v) => ((string) $v === '1' || $v === true) ? 'Sí' : 'No';

        $num = function ($v) {
            if ($v === null || $v === '') return '';
            $n = (float) $v;
            return rtrim(rtrim(number_format($n, 2, '.', ''), '0'), '.');
        };

        // 👇 mapeo EXACTO a las columnas del Excel
        $rows = $items->map(function ($ta) use ($yesNo, $num) {
            $pres = $ta->hours_presence ?? $ta->presential_hours ?? 0;
            $tele = $ta->hours_teletraining ?? $ta->teleformation_hours ?? 0;

            return [
                'Acción Formativa'       => $ta->formative_action ?? '',
                'Nombre'                 => $ta->name ?? '',
                'Tipo Acción'            => $ta->type_action ?? $ta->type ?? '',

                'Familia Professional'   => data_get($ta, 'professionalFamily.name', ''),
                'Área Professional'      => data_get($ta, 'professionalArea.name', ''),
                'Modalidad'              => data_get($ta, 'modality.name', ''),
                'Nivel'                  => data_get($ta, 'level.name', ''),

                'Grupo'                  => $ta->group ?? '',

                'Tutorización'           => $yesNo($ta->tutoring ?? $ta->tutorizacion ?? 0),
                'En Catalogo'            => $yesNo($ta->in_catalog ?? $ta->catalog ?? 0),

                'Horas Presenciales'     => $num($pres),
                'Horas Teleformación'    => $num($tele),
                'Horas Totales'          => $num(((float)$pres) + ((float)$tele)),

                'Precio'                 => $num($ta->price ?? 0),
            ];
        });

        return Excel::download(new TrainingActionsExport($rows), 'Acciones Formativas.xlsx');
    }
}
