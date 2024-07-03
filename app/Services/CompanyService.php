<?php

namespace App\Services;

use App\Models\Company;

class CompanyService
{
    /**
     * Función para crear una empresa
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return Company::create([
            'name' => $data['name'],
            'nif' => $data['nif'],
            'company_type_id' => $data['company_type_id'],
            'company_activity_id' => $data['company_activity_id'],
            'email' => $data['email'],
            'telephone' => $data['telephone'],
            'legal_representative' => $data['legal_representative'],
            'dni_legal_representative' => $data['dni_legal_representative'],
            'quote' => $data['quote'],
            'cnae_id' => $data['cnae_id'],
            'average_template' => $data['average_template'],
            'iban' => $data['iban'],
            'sepa' => $data['sepa'],
            'b2b' => $data['b2b'],
            'address' => $data['address'],
            'post_code' => $data['post_code'],
            'province_id' => $data['province_id'],
            'population' => $data['population'],
            'advisor_id' => $data['advisor_id'],
            'collaborator_id' => $data['collaborator_id'],
            'active' => $data['active'],
            'potential' => $data['potential'],
            'regimen' =>$data['regimen'],
            'population_code' => $data['population_code'],
            'agreement' => $data['agreement']

        ]);
    }

    /**
     * Función para editar una empresa
     */
    public function update(Company $company, array $data) {
        $company->update([
            'name' => $data['name'],
            'nif' => $data['nif'],
            'company_type_id' => $data['company_type_id'],
            'company_activity_id' => $data['company_activity_id'],
            'email' => $data['email'],
            'telephone' => $data['telephone'],
            'legal_representative' => $data['legal_representative'],
            'dni_legal_representative' => $data['dni_legal_representative'],
            'quote' => $data['quote'],
            'cnae_id' => $data['cnae_id'],
            'average_template' => $data['average_template'],
            'iban' => $data['iban'],
            'sepa' => $data['sepa'],
            'b2b' => $data['b2b'],
            'address' => $data['address'],
            'post_code' => $data['post_code'],
            'province_id' => $data['province_id'],
            'population' => $data['population'],
            'advisor_id' => $data['advisor_id'],
            'collaborator_id' => $data['collaborator_id'],
            'active' => $data['active'],
            'potential' => $data['potential'],
            'regimen' =>$data['regimen'],
            'population_code' => $data['population_code'],
            'agreement' => $data['agreement']
        ]);
        return $company;
    }
}
