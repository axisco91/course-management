<?php

namespace App\Http\Controllers\Api;
use App\Models\Course;
use App\Models\CourseType;
use App\Models\Profitability;
use App\Models\Student;
use App\Services\ProfitabilityService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProfitabilityController extends BaseController
{
    private $profitabilityService;

    public function __construct(ProfitabilityService $profitabilityService)
    {
        $this->profitabilityService = $profitabilityService;
    }

    /**
 * Obtener rentabilidad
 * @return \Illuminate\Http\JsonResponse
 */
public function index(Request $request) {
    try {
        $profits = Profitability::with(['registrations.student', 'course.courseStatuses', 'company'])
            ->profitability();

        // Apply necessary filters
        if ($request->course) {
            $profits = $profits->whereHas('course', function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->course . '%');
            });
        }
        if ($request->company) {
            $profits = $profits->whereHas('company', function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->company . '%');
            });
        }
        if ($request->status) {
            $profits = $profits->whereHas('course.status', function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->status . '%');
            });
        }

        $profits = $profits->orderBy('courses.beginning', 'desc')->get();
        return response()->json($profits);
    } catch (\Exception $e) {
        return response()->json([
            'message' => $e->getMessage()
        ]);
    }
}


    /**
     * Obtener rentabilidad
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id){
        $profitability = Profitability::profitability()
            ->where('profitabilities.id', $id)
            ->first();
        if ($profitability) {
            $course = Course::where('id', $profitability->course_id)->first();
            if ($course) {
                $beginning = Carbon::parse($course->beginning)->format('d/m/Y');
                $end = Carbon::parse($course->end)->format('d/m/Y');
                $profitability['name'] = $course->group.'/'. $course->name .' '.$beginning.' - '.$end;
                return response()->json([
                    'status' => 200,
                    'profitability' => $profitability
                ]);
            }
        }
        return response()->json([
            'status' => 400,
            'message' => 'Rentabilidad no existe'
        ]);
    }

    /**
     * Crear rentabilidad
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request){
        try {
            $data = $request->all();
            $profitability = $this->profitabilityService->create($data);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'profitability' => Profitability::profitability()
                ->where('profitabilities.id', $profitability->id)
                ->first()
        ]);
    }

    /**
     * Editar rentabbilidad
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, Request $request){
        try {
            $data = $request->all();
            $profitability = Profitability::find($id);
            $profitability = $this->profitabilityService->update($profitability, $data);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'profitability' => Profitability::profitability()
                ->where('profitabilities.id', $profitability->id)
                ->first()
        ]);
    }

    /**
     * Eliminar rentabilidad
     * @param $id
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function destroy($id){
        if ($id) {
            try {
                Profitability::destroy($id);
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
     * Obtenemos los alumnos que están en esa rentabilidad
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStudents($id){
        $profitabilities = Profitability::find($id);
        $registrations = $profitabilities->registrations()->get()->pluck('student_id')->toArray();
        $students = Student::select('students.*', 'companies.name as company_name')
            ->leftjoin('companies', 'companies.id', '=', 'students.company_id')
            ->whereIn('students.id', $registrations)->get();
        return response()->json($students);
    }
}
