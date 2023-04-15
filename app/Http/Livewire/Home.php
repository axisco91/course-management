<?php

namespace App\Http\Livewire;

use App\Models\Course;
use App\Models\Registration;
use Carbon\Carbon;
use Livewire\Component;

class Home extends Component
{

    public $total_registrations, $registrations_count, $years = [], $total_course_year, $total_courses, $courses_per_month = [];
    protected $listeners = [
        'getCoursesPerMonth' => 'getCoursesPerMonth'
    ];

    public function render()
    {
        return view('livewire.home.view');
    }

    public function mount(){
        $this->total_course_year = Carbon::now()->year;
        $date = Carbon::now();
        $years = [];
        for ($i=0; $i <= 5; $i++){
            $years[] = $date->year;
            $date = $date->subYear();
        }
        $this->years = $years;
        $this->total_registrations = Registration::totalRegistrations();
        $this->registrations_count = $this->countRegistrations();
        $this->getCoursesPerMonth();
    }

    public function countRegistrations(){
        $data = [];
        $now = Carbon::now();
        $cont = 1;
        $date = Carbon::parse($now->year.'-01-01');
        while($cont <= $now->month){
            $date->addMonth();
            $start = Carbon::parse($now->year.'-'.$date->month.'-01')->toDateString();
            $limit = Carbon::parse($now->year.'-'.$date->month.'-01')->endOfMonth()->toDateString();

            $registrations = Registration::countRegistrations($start, $limit);
            $data[] = $registrations;
            $cont++;
        }
       return $data;
    }

    public function getCoursesPerMonth(){
        $this->total_courses_year = Course::getNumberCourses($this->total_course_year);
        $this->courses_per_month = Course::getNumberCoursesPerMonth($this->total_course_year);
    }

}
