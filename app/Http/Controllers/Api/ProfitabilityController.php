<?php

namespace App\Http\Controllers\Api;
use App\Helpers\GeneralHelpers;
use App\Models\Course;
use App\Models\Profitability;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfitabilityController extends BaseController
{

    /**
 * Obtener rentabilidad
 * @return \Illuminate\Http\JsonResponse
 */
public function index(Request $request) {
    try {
       $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

        $profits = Profitability::with(['registrations.student', 'course.courseStatuses', 'company'])
            ->profitability($mainCompanyId);

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

        $profits = $profits->FilterMainCompany($mainCompanyId)
            ->orderBy('courses.beginning', 'desc')->get();

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
    public function show($id, Request $request) {
       $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

        $profitability = Profitability::profitability($mainCompanyId)
            ->where('profitabilities.id', $id)
            ->first();
        if ($profitability) {
            $course = Course::where('id', $profitability->course_id)
                ->FilterMainCompany($mainCompanyId)
                ->first();
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
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            $data = $request->all();
            $data['main_company_id'] = $mainCompanyId;

            $profitability = Profitability::createWithService($data);

            return response()->json([
                'status' => 200,
                'profitability' => Profitability::profitability($mainCompanyId)
                    ->where('profitabilities.id', $profitability->id)
                    ->first()
            ]);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Editar rentabbilidad
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, Request $request){
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            $profitability = Profitability::where('id', $id)
                ->FilterMainCompany($mainCompanyId)
                ->first();

            if (!$profitability) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Profitabilidad no existe'
                ]);
            }

            $data = $request->all();

            $profitability->updateWithService($data);

            return response()->json([
                'status' => 200,
                'profitability' => Profitability::profitability($mainCompanyId)
                    ->where('profitabilities.id', $profitability->id)
                    ->first()
            ]);

        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Eliminar rentabilidad
     * @param $id
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function destroy($id, Request $request){
        if ($id) {
            try {
               $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

                $profitability = Profitability::where('id', $id)
                    ->FilterMainCompany($mainCompanyId)
                    ->first();

                if (!$profitability) {
                    return response()->json([
                        'status' => 404,
                        'message' => 'Profitabilidad no existe'
                    ]);
                }

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
    public function getStudents($id, Request $request){
       $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
        $profitability = Profitability::where('id', $id)
            ->FilterMainCompany($mainCompanyId)
            ->first();

        if (!$profitability) {
            return response()->json([
                'status' => 404,
                'message' => 'Profitabilidad no existe'
            ]);
        }

        $registrations = $profitability->registrations()->get()->pluck('student_id')->toArray();

        $students = Student::select('students.*', 'companies.name as company_name')
            ->leftjoin('companies', 'companies.id', '=', 'students.company_id')
            ->whereIn('students.id', $registrations)
            ->FilterMainCompany($mainCompanyId)
            ->get();
        return response()->json($students);
    }
}
