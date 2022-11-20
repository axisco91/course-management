<?php

namespace App\Exports;

use App\Models\Billing;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BillingsExport implements FromCollection, WithHeadings
{
    use Exportable;

    public function __construct($course, $company, $student, $is_bonus){
        $this->course = $course;
        $this->company = $company;
        $this->student = $student;
        $this->is_bonus = $is_bonus;
    }

    public function collection()
    {
        $billings = Billing::select('billings.billing_number',
                DB::raw("CONCAT( courses.training_action_id, '/', courses.group, ' ',training_actions.name) as course"),
                DB::raw('year(billings.billing_date)'),
                DB::raw('IF(is_bonus = 0, "No bonificada", "Bonificada") as is_bonus'),
                'companies.name as company',
                'advisors.name as advisor',
                DB::raw("CONCAT( users.name, ' ', users.surname) as collaborator"),
                'billings.number_students',
                'billing',
                'billing_date',
                'collection_date',
                DB::raw('IF(charged = 0, "No", "Si") as cobrado'))
            ->leftjoin('courses', 'courses.id', '=', 'billings.course_id')
            ->leftjoin('companies', 'companies.id', '=', 'billings.company_id')
            ->leftjoin('payments', 'payments.id', '=', 'billings.payment_id')
            ->leftjoin('students', 'students.id', '=', 'billings.student_id')
            ->leftjoin('training_actions', 'training_actions.id', '=', 'courses.training_action_id')
            ->leftjoin('advisors', 'advisors.id', '=', 'billings.advisor_id')
            ->leftjoin('users', 'users.id', '=', 'billings.collaborator_id');

        if ($this->course != -1){
            $billings = $billings->where('billings.course_id', $this->course);
        }
        if ($this->company != -1){
            $billings = $billings->where('billings.company_id', $this->company);
        }
        if ($this->student != -1){
            $billings = $billings->where('billings.student_id', $this->student);
        }
        if ($this->is_bonus != -1){
            $billings = $billings->where('is_bonus', $this->is_bonus);
        }
       $billings = $billings->orderBy('courses.beginning', 'desc')->get();

        return collect($billings);
    }

    public function headings(): array {
        return [
            'Nº Factura',
            'Curso',
            'Año',
            'Tipo',
            'Empresa',
            'Asesoría',
            'Collaborador',
            'Numero Alumnos',
            'Factura',
            'Fecha Factura',
            'Fecha Cobro',
            'Cobrado'
        ];
    }
}
