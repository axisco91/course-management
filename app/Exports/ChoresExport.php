<?php

namespace App\Exports;

use App\Models\Chore;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ChoresExport implements FromCollection, WithHeadings
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
        $chores = Chore::select('courses.name as course',
            'companies.name as company',
            'students.name as student_name',
            'students.surname as student_surname',
            'course_statuses.name as status',
            'chores.membership_tab_status',
            'chores.economic_proposal_status',
            'chores.student_tab_status',
            'chores.welcome_guid_status',
            'chores.registration_status',
            'chores.diploma_status',
            'chores.start_communication_status',
            'chores.close_communication_status',
            'chores.invoiced_status',
            'chores.bonus_sent_status',
            'courses.group as course_group')
            ->leftjoin('courses', 'courses.id', '=', 'chores.course_id')
            ->leftjoin('course_statuses', 'course_statuses.id', '=', 'courses.course_status_id')
            ->leftjoin('companies', 'companies.id', '=', 'chores.company_id')
            ->leftjoin('students', 'students.id', '=', 'chores.student_id');

        if ($this->course != -1){
            $chores = $chores->where('courses.id', $this->course);
        }
        if ($this->company != -1){
            $chores = $chores->where('companies.id', $this->company);
        }
        if ($this->student != -1){
            $chores = $chores->where('students.id', 'LIKE', $this->student);
        }
        if ($this->status != -1){
            $chores = $chores->where('courses.course_status_id', 'LIKE', $this->status);
        }
        if ($this->beginning){
            $chores = $chores->where('courses.beginning', '>=', $this->beginning);
        }
        if ($this->end){
            $chores = $chores->where('courses.beginning', '<=', $this->end);
        }

        $chores = $chores->get();

        foreach ($chores as $chore) {
            $chore['course'] = str_replace( ' -', '/'.$chore->course_group.' -', $chore->course);
            $chore['student_name'] = $chore->student_name.' '.$chore->student_surname;
            $chore['membership_tab_status'] = $chore['membership_tab_status'] == 0 ? 'Pendiente' : ($chore['membership_tab_status'] == 1 ? 'Enviado' : ($chore['membership_tab_status'] == 2 ? 'Recibido' : 'No procede'));
            $chore['economic_proposal_status'] = $chore['economic_proposal_status'] == 0 ? 'Pendiente' : ($chore['economic_proposal_status'] == 1 ? 'Enviado' : 'Recibido');
            $chore['student_tab_status'] = $chore['student_tab_status'] == 0 ? 'Pendiente' : ($chore['student_tab_status'] == 1 ? 'Enviado' : 'Recibido');
            $chore['welcome_guid_status'] = $chore['welcome_guid_status'] == 0 ? 'Pendiente' : ($chore['welcome_guid_status'] == 1 ? 'Enviado' : 'Recibido');
            $chore['registration_status'] = $chore['registration_status'] == 0 ? 'Pendiente' : 'Realizada';
            $chore['diploma_status'] = $chore['diploma_status'] == 0 ? 'Pendiente' : ($chore['diploma_status'] == 1 ? 'Enviada' : 'No procede');
            $chore['start_communication_status'] = $chore['start_communication_status'] == 0 ? 'Pendiente' : ($chore['start_communication_status'] == 1 ? 'Realizada' : 'No procede');
            $chore['close_communication_status'] = $chore['close_communication_status'] == 0 ? 'Pendiente' : ($chore['close_communication_status'] == 1 ? 'Realizada' : 'No procede');
            $chore['invoiced_status'] = $chore['invoiced_status'] == 0 ? 'Pendiente' : ($chore['invoiced_status'] == 1 ? 'Realizada' : 'No procede');
            $chore['bonus_sent_status'] = $chore['bonus_sent_status'] == 0 ? 'Pendiente' : ($chore['bonus_sent_status'] == 1 ? 'Realizada' : 'No procede');
            unset($chore['course_group']);
            unset($chore['student_surname']);
        }

        return collect($chores);
    }

    public function headings(): array
    {
        return [
            'Curso',
            'Empresa',
            'Alumno',
            'Estado',
            'Ficha Adhesión',
            'Propuesta Económica',
            'Ficha Alumno',
            'Guia Bienvenida',
            'Matriculación',
            'Diploma',
            'Comunicación Inicio',
            'Comunicación Cierre',
            'Facturado',
            'Bonificacion Enviada'
        ];
    }
}
