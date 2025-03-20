<?php

namespace App\Http\Controllers;

use App\Helpers\MoodleHelpers;
use App\Models\Registration;
use Carbon\Carbon;
use GuzzleHttp\Client;

class TestsController extends Controller
{
    public function index() {
        $registrations_count = [];
        $now = Carbon::now();
        $cont = 1;
        $date = Carbon::parse($now->year.'-01-01');
        while($cont <= $now->month){
            $date->addMonth();
            $start = Carbon::parse($now->year.'-'.$date->month.'-01')->toDateString();
            $limit = Carbon::parse($now->year.'-'.$date->month.'-01')->endOfMonth()->toDateString();

            echo '<br><br><br>';
            $registrations = Registration::countRegistrations($start, $limit);
            $registrations_count[] = $registrations;
            $cont++;
        }
    }

    public function testMoodle() {
        $data = MoodleHelpers::getCourseByShortname('030/0005');

        if (!empty($data)) {
            $dara = MoodleHelpers::getActivityCount($data[0]['id']);

            $da = MoodleHelpers::getStudentCourseDetails($data[0]['id'], '50625787g');
        }

        return $da;
    }

}
