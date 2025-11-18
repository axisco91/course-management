<?php

namespace App\Services;

use App\Models\PotentialCompany;

class PotentialCompanyService
{
    /**
     * Función para crear una empresa potencial
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return PotentialCompany::create([
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
            'active' => $data['active'],
            'advisor_name' => $data['advisor_name'],
            'main_company_id' => $data['main_company_id']
        ]);
    }

    /**
     * Función para editar una empresa potencial
     */
    public function converted(PotentialCompany $potentialCompany) {
        $potentialCompany->update([
            'converted' => 1
        ]);

        return $potentialCompany;
    }
}
