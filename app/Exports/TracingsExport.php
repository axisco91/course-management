<?php

namespace App\Exports;

use App\Models\Tracing;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TracingsExport implements FromCollection, WithHeadings
{
    use Exportable;

    public function __construct($course, $company, $student,  $status, $beginning, $end){
        $this->course = $course;
        $this->company = $company;
        $this->student = $student;
        $this->status = $status;
        $this->beginning = $beginning;
        $this->end = $end;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $tracings = Tracing::select('courses.name as course',
            'companies.name as company',
            'students.name as student_name',
            'students.surname as student_surname',
            'course_statuses.name as status',
            'tracings.performed_hours',
            'training_actions.total_hours',
            'tracings.performed_activities',
            'training_actions.number_activities',
            'tracings.performed_units',
            'training_actions.number_units',
            'tracings.follow_up_date',
            'tracings.final_test',
            'tracings.questionnaire',
            'tracings.welcome_message',
            'tracings.quarter_message',
            'tracings.half_message',
            'tracings.three_quarters_message',
            'tracings.final_message',
        'courses.group as course_group')
        ->leftjoin('courses', 'courses.id', '=', 'tracings.course_id')
        ->leftjoin('course_statuses', 'course_statuses.id', 'courses.course_status_id')
        ->leftjoin('companies', 'companies.id', '=', 'tracings.company_id')
        ->leftjoin('students', 'students.id', '=', 'tracings.student_id')
        ->leftjoin('training_actions', 'training_actions.id', '=', 'courses.training_action_id');

        if ($this->course != -1){
            $tracings = $tracings->where('courses.id', $this->course);
        }
        if ($this->company != -1){
            $tracings = $tracings->where('companies.id', $this->company);
        }
        if ($this->student != -1){
            $tracings = $tracings->where('students.id', 'LIKE', $this->student);
        }
        if ($this->status != -1){
            $tracings = $tracings->where('courses.course_status_id', 'LIKE', $this->status);
        }
        if ($this->beginning){
            $tracings = $tracings->where('courses.beginning', '>=', $this->beginning);
        }
        if ($this->end){
            $tracings = $tracings->where('courses.beginning', '<=', $this->end);
        }

        $tracings = $tracings->get();

        foreach ($tracings as $tracing) {
            $tracing['course'] = str_replace( ' -', '/'.$tracing->course_group.' -', $tracing->course);
            $tracing['student_name'] = $tracing->student_name.' '.$tracing->student_surname;
            $tracing['follow_up_date'] = $tracing->follow_up_date ? Carbon::parse($tracing->follow_up_date)->format('d/m/Y') : '';
            $tracing['welcome_message'] = $tracing['welcome_message'] == 1 ? 'Si' : 'No';
            $tracing['quarter_message'] = $tracing['quarter_message'] == 1 ? 'Si' : 'No';
            $tracing['half_message'] = $tracing['half_message'] == 1 ? 'Si' : 'No';
            $tracing['three_quarters_message'] = $tracing['three_quarters_message'] == 1 ? 'Si' : 'No';
            $tracing['final_message'] = $tracing['final_message'] == 1 ? 'Si' : 'No';
            unset($tracing['course_group']);
            unset($tracing['student_surname']);
        }

        return collect($tracings);
    }

    public function headings(): array
    {
        return [
            'Curso',
            'Empresa',
            'Alumno',
            'Estado',
            'Horas Realizadas',
            'Horas Totales',
            'Actividades Realizadas',
            'Actividades Totales',
            'Unidades Realizadas',
            'Unidades Totales',
            'Fecha Seguimiento',
            'Test Final',
            'Cuestionario',
            'Bienvenida',
            'Mensaje 25%',
            'Mensaje 50%',
            'Mensaje 75%',
            'Finalización'
        ];
    }
}
