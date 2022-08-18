<?php

namespace App\Exports;

use App\Models\Company;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CompaniesExport implements  FromCollection, WithHeadings
{
    use Exportable;

    public function __construct($name, $nif, $type_id, $activity_id, $advisor_id, $province_id, $active){
        $this->name = $name;
        $this->nif = $nif;
        $this->type_id = $type_id;
        $this->activity_id = $activity_id;
        $this->advisor_id = $advisor_id;
        $this->province_id = $province_id;
        $this->active = $active;
    }


    public function collection()
    {
        $data = [];

        $companies = Company::select('companies.name', 'companies.nif', 'companies.email', 'companies.telephone', 'companies.legal_representative',
            'companies.dni_legal_representative', 'companies.quote', 'companies.average_template', 'companies.iban', 'companies.sepa', 'companies.b2b',
            'companies.address', 'companies.post_code', 'companies.population', 'companies.active',
            'companies.population', 'company_types.name as type_name', 'company_activities.name as activity_name',
            'advisors.name as advisor_name', 'cnaes.name as cnae_name', 'provinces.name as province_name')
            ->leftjoin('company_types', 'company_types.id', '=', 'companies.company_type_id')
            ->leftjoin('company_activities', 'company_activities.id', '=', 'companies.company_activity_id')
            ->leftjoin('advisors', 'advisors.id', '=', 'companies.advisor_id')
            ->leftjoin('provinces', 'provinces.id', '=', 'companies.province_id')
            ->leftjoin('cnaes', 'cnaes.id', '=', 'companies.cnae_id');

        if ($this->name){
            $name = '%'.$this->name.'%';
            $companies = $companies->where('companies.name', 'LIKE', $name);
        }
        if ($this->nif){
            $nif = '%'.$this->nif.'%';
            $companies = $companies ->where('companies.nif', 'LIKE', $nif);
        }
        if ($this->type_id){
            $companies = $companies->where('companies.company_type_id', $this->type_id);
        }
        if ($this->activity_id){
            $companies = $companies->where('companies.company_activity_id', $this->activity_id);
        }
        if ($this->advisor_id){
            $companies = $companies->where('advisor_id', $this->advisor_id);
        }
        if ($this->province_id){
            $companies = $companies ->where('companies.province_id', $this->province_id);
        }
        if ($this->active != 1){
            $companies = $companies->where('companies.active',1);
        }

        $companies = $companies->orderby('name')->get();

        foreach($companies as $company){
            $data[] = [
                'name' => $company->name,
                'nif' => $company->nif,
                'type_name' => $company->type_name,
                'activity_name' => $company->activity_name,
                'email' => $company->email,
                'telephone' => $company->telephone,
                'legal_representative' => $company->legal_representative,
                'dni_legal_representative' => $company->dni_legal_representative,
                'quote' => $company->quote,
                'cnae_name' => $company->cnae_name,
                'average_template' => $company->average_template,
                'iban' => $company->iban,
                'sepa' => $company->sepa,
                'b2b' => $company->b2b,
                'address' => $company->address,
                'post_code' => $company->post_code,
                'province_name' => $company->province_name,
                'population' => $company->population,
                'advisor_name' => $company->advisor_name,
                'active' => $company->active == 0 ? 'Inactivo' : 'Activo',
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
