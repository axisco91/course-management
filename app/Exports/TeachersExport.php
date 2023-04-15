<?php

namespace App\Exports;

use App\Models\Teacher;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TeachersExport implements FromCollection, WithHeadings
{
    use Exportable;

    public function __construct($name, $surname, $email, $dni, $telephone, $active){
        $this->name = $name;
        $this->surname = $surname;
        $this->email = $email;
        $this->dni = $dni;
        $this->telephone = $telephone;
        $this->active = $active;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
       $teachers = Teacher::select('teachers.name', 'surname', 'dni', 'email', 'telephone', 'user', 'password', 'observations', 'iban',
           'address', 'post_code', 'provinces.name as province_name', 'population', \DB::raw('(CASE
                        WHEN teachers.active = "0" THEN "Inactivo"
                        WHEN teachers.active = "1" THEN "Activo"
                        END) AS status'))
            ->leftjoin('provinces', 'provinces.id', '=', 'teachers.province_id');

       if ($this->name){
           $name = '%'.$this->name.'%';
           $teachers = $teachers->where('teachers.name', 'LIKE', $name);
       }
       if ($this->surname){
           $surname = '%'.$this->surname.'%';
           $teachers = $teachers->where('surname', 'LIKE', $surname);
       }
       if ($this->email){
           $email = '%'.$this->email.'%';
           $teachers = $teachers->where('email', 'LIKE', $email);
       }
       if ($this->dni){
           $dni = '%'.$this->dni.'%';
           $teachers = $teachers->where('dni', 'LIKE', $dni);
       }
       if ($this->telephone){
           $telephone = '%'.$this->telephone.'%';
           $teachers = $teachers->where('telephone', 'LIKE', $telephone);
       }
       if ($this->active != 1){
           $teachers = $teachers->where('active', 1);
       }
       $teachers = $teachers->orderby('name')->get();

       return collect($teachers);
    }

    public function headings(): array
    {
        return [
            'Nombre',
            'Apellidos',
            'DNI',
            'Correo',
            'Telefono',
            'Usuario',
            'Contraseña',
            'Observaciones',
            'Iban',
            'Dirección',
            'Código postal',
            'Provincia',
            'Población',
            'Estado'
        ];
    }
}
