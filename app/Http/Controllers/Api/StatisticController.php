<?php

namespace App\Http\Controllers\Api;
use App\Helpers\GeneralHelpers;
use App\Models\Chore;
use App\Models\Course;
use App\Models\Registration;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StatisticController extends BaseController
{
    public function totalRegistrations(Request $request) {
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            $data = [];
            $now = Carbon::now();
            $cont = 1;
            $date = Carbon::parse($now->year.'-01-01');
            while($cont <= $now->month) {
                $date->addMonth();
                $start = Carbon::parse($now->year . '-' . $date->month . '-01')->toDateString();
                $limit = Carbon::parse($now->year . '-' . $date->month . '-01')->endOfMonth()->toDateString();

                $registrations = Registration::countRegistrations($start, $limit, $mainCompanyId);
                $data[] = $registrations;
                $cont++;
            }
            return response()->json([
                'registrations' => Registration::totalRegistrations($mainCompanyId),
                'series' => [['data' => $data,
                'name' => 'Matriculaciones']]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function getChoresWelcomeMessages(Request $request) {
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            return Chore::getChoresSendWelcome($mainCompanyId);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function getNumberCourses(Request $request) {
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            return Course::getNumberCourses(Carbon::now()->year, $mainCompanyId);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function getNumberCoursesPerMonth(Request $request) {
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            return Course::getNumberCoursesPerMonth(Carbon::now()->year, $mainCompanyId);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }
}
