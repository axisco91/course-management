<?php

namespace App\Exports;

use App\Models\Course;
use App\Models\Registration;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CoursesExport implements FromCollection, WithHeadings
{
    use Exportable;

    public function __construct($formative_actions, $name, $group, $type_id, $status_id, $company_name){
        $this->formative_actions = $formative_actions;
        $this->name = $name;
        $this->group = $group;
        $this->type_id = $type_id;
        $this->status_id = $status_id;
        $this->company_name = $company_name;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $courses = Course::select('courses.*',
            'course_types.name as course_type', 'teachers.name as teacher_name', 'teachers.surname as teacher_surname',
            'fc.name as formation_center',
            'dc.name as delivery_center',
            'course_statuses.name as course_status')
            ->leftjoin('course_types', 'course_types.id', '=', 'courses.course_type_id')
            ->leftjoin('teachers', 'teachers.id', '=', 'courses.teacher_id')
            ->leftjoin('centers as fc', 'fc.id', '=', 'courses.formation_center_id')
            ->leftjoin('centers as dc', 'dc.id', '=', 'courses.delivery_center_id')
            ->leftjoin('course_statuses', 'course_statuses.id', '=', 'courses.course_status_id')
            ->leftjoin('registrations', 'registrations.course_id', '=', 'courses.id')
            ->leftjoin('companies', 'companies.id', '=', 'registrations.company_id');

        if ($this->formative_actions){
            $formative_actions = '%'.$this->formative_actions.'%';
            $courses = $courses->orWhere('courses.name', 'LIKE', $formative_actions);
        }
        if ($this->name) {
            $name = '%'.$this->name.'%';
            $courses = $courses->orWhere('courses.name', 'LIKE', $name);
        }
        if ($this->group){
            $group = '%'.$this->group.'%';
            $courses = $courses->orWhere('courses.group', 'LIKE', $group);
        }
        if ($this->company_name){
            $company_name = '%'.$this->company_name.'%';
            $courses = $courses->orWhere('companies.name', 'LIKE', $company_name);
        }
        if ($this->type_id){
            $courses = $courses->Where('courses.course_type_id', $this->type_id);
        }
        if ($this->status_id){
            $courses = $courses->Where('courses.course_status_id', $this->status_id);
        }
        $courses = $courses->orderBy('courses.beginning', 'desc')->get();

        foreach ($courses as $course){
            $registrations = Registration::where('course_id', $course->id)->get();
            $course['registration'] = $registrations;

            $beginning = Carbon::parse($course['beginning'])->format('d/m/Y');
            $course['beginning'] = $beginning;
            $end = Carbon::parse($course['end'])->format('d/m/Y');
            $course['end'] = $end;
        }

        return collect($courses);
    }

    public function headings(): array
    {
        return [
            'Acción Formativa',
            'Nombre'
        ];
    }
}
