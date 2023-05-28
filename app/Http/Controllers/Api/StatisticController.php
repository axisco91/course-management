<?php

namespace App\Http\Controllers\API;
use App\Models\ActionType;
use App\Models\Chore;
use App\Models\Course;
use App\Models\Registration;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class StatisticController extends BaseController
{
    public function totalRegistrations() {
        try {
            $data = [];
            $now = Carbon::now();
            $cont = 1;
            $date = Carbon::parse($now->year.'-01-01');
            while($cont <= $now->month) {
                $date->addMonth();
                $start = Carbon::parse($now->year . '-' . $date->month . '-01')->toDateString();
                $limit = Carbon::parse($now->year . '-' . $date->month . '-01')->endOfMonth()->toDateString();

                $registrations = Registration::countRegistrations($start, $limit);
                $data[] = $registrations;
                $cont++;
            }
            return response()->json([
                'registrations' => Registration::totalRegistrations(),
                'series' => [['data' => $data,
                'name' => 'Matriculaciones']]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function getChoresWelcomeMessages(){
        try {
            return Chore::getChoresSendWelcome();
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function getNumberCourses(){
        try {
            return Course::getNumberCourses(Carbon::now()->year);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function getNumberCoursesPerMonth(){
        try {
            return Course::getNumberCoursesPerMonth(Carbon::now()->year);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }
}
