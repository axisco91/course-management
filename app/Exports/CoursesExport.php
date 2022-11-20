<?php

namespace App\Exports;

use App\Models\Course;
use App\Models\Registration;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;

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
        $courses = Course::select('courses.training_action_id as training_action', 'courses.group', 'courses.name',
            'course_types.name as course_type', 'courses.beginning', 'courses.end', DB::raw("CONCAT(teachers.name,' ',teachers.surname) as teacher"),
            DB::raw('IF(courses.nebrija = 1, "Si", "No") as Nebrija'),
            'fc.name as formation_center',
            'dc.name as delivery_center',
            'course_statuses.name as course_status',
            'morning_schedule',
            'afternoon_schedule',
            DB::raw("CONCAT(IF(monday = 1, 'Lunes ', ''), IF(tuesday = 1, 'Martes ', ''), IF(wednesday = 1, 'Miercoles ', ''), IF(thursday = 1, 'Jueves ', ''), IF(friday = 1, 'Viernes ', ''), IF(saturday = 1, 'Sabado', ''), IF(sunday = 1, 'Domingo', '')) as days"),
            Db::raw('IF(outsourced = 1, "Si", "No") as outsourced'),
            'price',
            Db::raw('IF(reactivated, "Si", "No") as reactivated'),
            'course_observation'
        )
            ->leftjoin('course_types', 'course_types.id', '=', 'courses.course_type_id')
            ->leftjoin('teachers', 'teachers.id', '=', 'courses.teacher_id')
            ->leftjoin('centers as fc', 'fc.id', '=', 'courses.formation_center_id')
            ->leftjoin('centers as dc', 'dc.id', '=', 'courses.delivery_center_id')
            ->leftjoin('course_statuses', 'course_statuses.id', '=', 'courses.course_status_id');

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

        if ($this->company_name != ''){
            $registrations = Registration::where('company_id', $this->company_name)->groupBy('course_id')->pluck('course_id')->toArray();
            $courses = $courses->where(function ($query) use ($registrations){
                $query->WhereIn('courses.id', $registrations);
            });
        }

        if ($this->type_id){
            $courses = $courses->Where('courses.course_type_id', $this->type_id);
        }
        if ($this->status_id){
            $courses = $courses->Where('courses.course_status_id', $this->status_id);
        }
        $courses = $courses->orderBy('courses.beginning', 'desc')->get();

        foreach ($courses as $course){
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
            'Grupo',
            'Nombre',
            'Tipo curso',
            'Fecha Inicio',
            'Fecha Fin',
            'Docente',
            'Nebrija',
            'Centro Formativo',
            'Centro impartición',
            'Estado',
            'Horario Mañana',
            'Horario Tarde',
            'Días Impartición',
            'Subcontratado',
            'Precio',
            'Reactivado',
            'Observaciones'
        ];
    }
}
