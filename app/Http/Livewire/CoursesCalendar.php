<?php

namespace App\Http\Livewire;

use App\Models\Course;
use Asantibanez\LivewireCalendar\LivewireCalendar;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class CoursesCalendar extends LivewireCalendar
{
    public function events(): Collection
    {

        $courses = Course::all();
        $data = collect();
        foreach ($courses as $course) {
            $data->add([
                'title' => $course->name,
                'date' => $course->beginning
            ]);
        }
        return $data;
    }
}
