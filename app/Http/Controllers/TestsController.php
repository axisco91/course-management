<?php

namespace App\Http\Controllers;

use App\Models\Registration;
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
            var_dump($start);
            var_dump($limit);
            echo '<br><br><br>';
            $registrations = Registration::countRegistrations($start, $limit);
            $registrations_count[] = $registrations;
            $cont++;
        }
    }

}
