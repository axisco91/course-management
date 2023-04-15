<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StudentsExport implements FromCollection, WithHeadings
{
    use Exportable;

    public function __construct($name, $surname, $email, $dni, $telephone, $company_id, $active){
        $this->name = $name;
        $this->surname = $surname;
        $this->email = $email;
        $this->dni = $dni;
        $this->telephone = $telephone;
        $this->company_id = $company_id;
        $this->active = $active;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $students = Student::select('students.name', 'surname', 'dni', 'students.email', 'students.telephone', 'companies.name as company_name', 'user', 'password',
            'date_of_birth', 'level_studies.name as level_study_name',
            \DB::raw('(CASE
                        WHEN students.disabled = "0" THEN "No"
                        WHEN students.disabled = "1" THEN "Si"
                        END) AS disabled'), 'social_security_number', 'c_quote', 'quote_groups.name as quote_group_name',
            'professional_categories.name as professional_category_name', 'annual_gross_salary', 'annual_hours', 'hourly_cost_worker_gross',
            'students.direction', 'students.post_code', 'provinces.name as province_name', 'students.population', 'students.iban','students.observation',
                        \DB::raw('(CASE
                        WHEN students.active = "0" THEN "Inactivo"
                        WHEN students.active = "1" THEN "Activo"
                        END) AS status'))
            ->leftjoin('provinces', 'provinces.id', '=', 'students.province_id')
            ->leftjoin('companies', 'companies.id', '=', 'students.company_id')
            ->leftjoin('level_studies', 'level_studies.id', '=', 'students.level_study_id')
            ->leftjoin('quote_groups', 'quote_groups.id', '=', 'students.quote_group_id')
            ->leftjoin('professional_categories', 'professional_categories.id', '=', 'students.professional_category_id');

        if ($this->name){
            $name = '%'.$this->name.'%';
            $students = $students->where('students.name', 'LIKE', $name);
        }
        if ($this->surname){
            $surname = '%'.$this->surname.'%';
            $students = $students->where('surname', 'LIKE', $surname);
        }
        if ($this->email){
            $email = '%'.$this->email.'%';
            $students = $students->where('email', 'LIKE', $email);
        }
        if ($this->dni){
            $dni = '%'.$this->dni.'%';
            $students = $students->where('dni', 'LIKE', $dni);
        }
        if ($this->telephone){
            $telephone = '%'.$this->telephone.'%';
            $students = $students->where('telephone', 'LIKE', $telephone);
        }
        if ($this->company_id){
            $students = $students->where('company_id', $this->company_id);
        }
        if ($this->active != 1){
            $students = $students->where('students.active', 1);
        }
        $students = $students->orderby('name')->get();

        return collect($students);
    }

    public function headings(): array
    {
        return [
            'Nombre',
            'Apellidos',
            'DNI',
            'Correo',
            'Telefono',
            'Empresa',
            'Usuario',
            'Contraseña',
            'Fecha Nacimiento',
            'nivel Estudio',
            'Descapacitado',
            'Nº Seguridad Social',
            'C. Cotización',
            'Grupo Cotización',
            'Categoria Profesional',
            'Salario Bruto Anual',
            'Horas Anuales',
            'Coste Hora Bruto del Trabajador',
            'Dirección',
            'Código postal',
            'Provincia',
            'Población',
            'Iban',
            'Observaciones',
            'Estado'
        ];
    }
}
