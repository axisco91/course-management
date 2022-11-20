<?php

namespace App\Exports;

use App\Models\Profitability;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProfitabilitiesExport implements FromCollection, WithHeadings
{
    use Exportable;

    public function __construct($course, $company, $student){
        $this->course = $course;
        $this->company = $company;
        $this->student = $student;
    }

    public function collection()
    {

        $profitabilities = Profitability::select( DB::raw("CONCAT( courses.training_action_id, '/', courses.group, ' ',training_actions.name) as course"),
            DB::raw('year(courses.beginning)'),
            'companies.name as company_name',
            DB::raw("CONCAT( students.name, ' ', students.surname) as student_name"),
            DB::raw("CONCAT(profitabilities.price, ' €')"),
            'license',
            DB::raw("CONCAT(teacher, ' €')"),
            DB::raw("CONCAT(management, ' €')"),
            DB::raw("CONCAT(nebrija_title, ' €')"),
            DB::raw("CONCAT(discount, ' €')"),
            DB::raw("CONCAT(collaborator_commission, ' €')"),
            DB::raw("CONCAT(advisor_commission, ' €')"),
            DB::raw("CONCAT(total, ' €')"),
            DB::raw('CONCAT(benefits, " €")'),
            DB::raw("CONCAT(ROUND((benefits*benefits)/profitabilities.price, 2), ' €') as rentabilidad"))
            ->leftjoin('companies', 'companies.id', '=', 'profitabilities.company_id')
            ->leftjoin('courses', 'courses.id', '=', 'profitabilities.course_id')
            ->leftjoin('students', 'students.id', '=', 'profitabilities.student_id')
            ->leftjoin('training_actions', 'training_actions.id', '=', 'courses.training_action_id');

        if ($this->course != -1){
            $profitabilities = $profitabilities->where('profitabilities.course_id', $this->course);
        }
        if ($this->company != -1){
            $profitabilities = $profitabilities->where('profitabilities.company_id', $this->company);
        }
        if ($this->student != -1){
            $profitabilities = $profitabilities->where('profitabilities.student_id', $this->student);
        }

       $profitabilities = $profitabilities->orderBy('courses.beginning')->get();

        return collect($profitabilities);
    }

    public function headings(): array {
        return [
            'Curso',
            'Año',
            'Empresa',
            'Alumnos',
            'Precio',
            'Licencia',
            'Docente',
            'Gestión',
            'Titulo Nebrija',
            'Descuento',
            'Comisión Colaborador',
            'Comisión Asesoria',
            'Total',
            'Beneficio',
            'Rentabilidad',
        ];
    }
}
