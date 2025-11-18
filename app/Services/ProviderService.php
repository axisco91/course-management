<?php

namespace App\Services;

use App\Models\Provider;

class ProviderService
{
    /**
     * Función para crear proveedor
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        Provider::create([
            'name' => $data['name'],
            'company_id' => $data['company_id'],
            'irpf' => $data['irpf'],
            'commission' => $data['commission'],
            'contact_1' => $data['contact_1'],
            'contact_2' => $data['contact_2'],
            'contact_3' => $data['contact_3'],
            'nif' => $data['nif'],
            'company_type_id' => $data['company_type_id'],
            'company_activity_id' => $data['company_activity_id'],
            'email' => $data['email'],
            'telephone' => $data['telephone'],
            'legal_representative' => $data['legal_representative'],
            'dni_legal_representative' => $data['dni_legal_representative'],
            'cnae_id' => $data['cnae_id'],
            'iban' => $data['iban'],
            'sepa' => $data['sepa'],
            'b2b' => $data['b2b'],
            'address' => $data['address'],
            'post_code' => $data['post_code'],
            'province_id' => $data['province_id'],
            'population' => $data['population'],
            'active' => $data['active'],
            'main_company_id' => $data['main_company_id'],
        ]);
    }

    /**
     * Función para editar proveedor
     */
    public function update(Provider $provider, array $data) {
        $provider->update([
            'name' => $data['name'],
            'irpf' => $data['irpf'],
            'commission' => $data['commission'],
            'contact_1' => $data['contact_1'],
            'contact_2' => $data['contact_2'],
            'contact_3' => $data['contact_3'],
            'nif' => $data['nif'],
            'company_type_id' => $data['company_type_id'],
            'company_activity_id' => $data['company_activity_id'],
            'email' => $data['email'],
            'telephone' => $data['telephone'],
            'legal_representative' => $data['legal_representative'],
            'dni_legal_representative' => $data['dni_legal_representative'],
            'cnae_id' => $data['cnae_id'],
            'iban' => $data['iban'],
            'sepa' => $data['sepa'],
            'b2b' => $data['b2b'],
            'address' => $data['address'],
            'post_code' => $data['post_code'],
            'province_id' => $data['province_id'],
            'population' => $data['population'],
            'active' => $data['active']
        ]);
        return $provider;
    }
}
