<?php

namespace App\Http\Controllers;

use App\Helpers\MoodleHelpers;
use App\Models\Registration;
use App\Models\WebPlatform;
use Carbon\Carbon;

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
        $webPlatform = WebPlatform::whereNotNull('url')
            ->whereNotNull('token')
            ->first();

        if (!$webPlatform) {
            return ['error' => 'No web platform with Moodle credentials found'];
        }

        $data = MoodleHelpers::getCourseByShortname('030/0005', $webPlatform->url, $webPlatform->token);
        if (empty($data) || !isset($data['id'])) {
            return ['error' => 'Moodle course not found'];
        }

        $activities = MoodleHelpers::getActivityCount($data['id'], $webPlatform->url, $webPlatform->token);
        $details = MoodleHelpers::getStudentCourseDetails($data['id'], '50625787g', $webPlatform->url, $webPlatform->token);

        return [
            'course' => $data,
            'activities' => $activities,
            'details' => $details,
        ];
    }

}
