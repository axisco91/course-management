<?php

namespace App\Exports;

use App\Models\Advisor;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AdvisorsExport implements FromCollection, WithHeadings
{
    use Exportable;

    public function __construct($name, $nif, $type_id, $activity_id, $province_id, $active){
        $this->name = $name;
        $this->nif = $nif;
        $this->type_id = $type_id;
        $this->activity_id = $activity_id;
        $this->province_id = $province_id;
        $this->active = $active;
    }

    public function collection()
    {
        $data = [];

        $advisors = Advisor::select('advisors.name', 'advisors.nif', 'advisors.email', 'advisors.telephone', 'advisors.legal_representative',
            'advisors.dni_legal_representative', 'advisors.irpf', 'advisors.commission', 'advisors.contact_1', 'advisors.contact_2', 'advisors.contact_3', 'companies.quote', 'companies.average_template', 'advisors.iban', 'advisors.sepa', 'advisors.b2b',
            'advisors.address', 'advisors.post_code', 'advisors.population', 'advisors.active',
            'advisors.population', 'company_types.name as type_name', 'company_activities.name as activity_name',
            'advisors.name as advisor_name', 'cnaes.name as cnae_name', 'provinces.name as province_name')
            ->leftjoin('company_types', 'company_types.id', '=', 'advisors.company_type_id')
            ->leftjoin('company_activities', 'company_activities.id', '=', 'advisors.company_activity_id')
            ->leftjoin('provinces', 'provinces.id', '=', 'advisors.province_id')
            ->leftjoin('cnaes', 'cnaes.id', '=', 'advisors.cnae_id')
            ->leftjoin('companies', 'companies.id', '=', 'advisors.company_id');

        if ($this->name){
            $name = '%'.$this->name.'%';
            $advisors = $advisors->where('companies.name', 'LIKE', $name);
        }
        if ($this->nif){
            $nif = '%'.$this->nif.'%';
            $advisors = $advisors ->where('companies.nif', 'LIKE', $nif);
        }
        if ($this->type_id){
            $advisors = $advisors->where('companies.company_type_id', $this->type_id);
        }
        if ($this->activity_id){
            $advisors = $advisors->where('companies.company_activity_id', $this->activity_id);
        }
        if ($this->province_id){
            $advisors = $advisors ->where('companies.province_id', $this->province_id);
        }
        if ($this->active != 1){
            $advisors = $advisors->where('companies.active',1);
        }

        $advisors = $advisors->orderby('name')->get();

        foreach($advisors as $advisor){
            $data[] = [
                'name' => $advisor->name,
                'nif' => $advisor->nif,
                'type_name' => $advisor->type_name,
                'activity_name' => $advisor->activity_name,
                'email' => $advisor->email,
                'telephone' => $advisor->telephone,
                'legal_representative' => $advisor->legal_representative,
                'dni_legal_representative' => $advisor->dni_legal_representative,
                'irpf' => $advisor->irpf,
                'commission' => $advisor->commission,
                'contact_1' => $advisor->contact_1,
                'contact_2' => $advisor->contact_2,
                'contact_3' => $advisor->contact_3,
                'quote' => $advisor->quote,
                'cnae_name' => $advisor->cnae_name,
                'average_template' => $advisor->average_template,
                'iban' => $advisor->iban,
                'sepa' => $advisor->sepa,
                'b2b' => $advisor->b2b,
                'address' => $advisor->address,
                'post_code' => $advisor->post_code,
                'province_name' => $advisor->province_name,
                'population' => $advisor->population,
                'advisor_name' => $advisor->advisor_name,
                'active' => $advisor->active == 0 ? 'Inactivo' : 'Activo',
            ];
        }

        return collect($data);
    }

    public function headings(): array {
        return [
            'Nombre',
            'CIF',
            'Tipo empresa',
            'Actividad empresa',
            'Correo',
            'Telefono',
            'Representante legal',
            'Dni representante legal',
            'IRPF',
            'Commisiones',
            'Contacto 1',
            'Contacto 2',
            'Contacto 3',
            'C. cotización',
            'CNAE',
            'Plantilla media',
            'Iban',
            'Sepa',
            'B2B',
            'Dirección',
            'Código postal',
            'Provincia',
            'Población',
            'Asesoria',
            'Estado'
        ];
    }
}
