<?php

namespace App\Http\Livewire;

use App\Models\Advisor;
use App\Models\Cnae;
use App\Models\Company;
use App\Models\CompanyActivity;
use App\Models\CompanyType;
use App\Models\Province;
use Livewire\Component;
use App\Models\Provider;

class ProvidersUpdate extends Component
{
    public $selected_id, $keyWord, $inactiveFilter, $name, $company_id, $irpf, $commission, $contact_1, $contact_2, $contact_3, $nif, $type_id, $activity_id, $email, $telephone, $legal_representative, $dni_legal_representative, $quote, $cnae_id, $average_template, $iban, $sepa, $b2b, $address, $post_code, $population_id, $province_id, $population, $active, $advisor_id;
    public $company_types, $company_activities, $cnaes, $provinces, $advisors;

    public function render()
    {
        return view('livewire.providers.update');
    }

    public function mount($id){
        $this->company_types = CompanyType::all();
        $this->company_activities = CompanyActivity::all();
        $this->cnaes = Cnae::all();
        $this->provinces = Province::all();
        $this->advisors = Advisor::select('advisors.*')
            ->join('companies', 'companies.id', '=', 'advisors.company_id')
            ->where('companies.active', 0)->get();

        $record = Provider::findOrFail($id);
        $company = Company::findOrFail($record-> company_id);

        $this->selected_id = $id;
        $this->name = $record-> name;
        $this->company_id = $record-> company_id;
        $this->irpf = $record-> irpf;
        $this->commission = $record-> commission;
        $this->contact_1 = $record-> contact_1;
        $this->contact_2 = $record-> contact_2;
        $this->contact_3 = $record-> contact_3;
        $this->nif = $company-> nif;
        $this->type_id = $company-> company_type_id;
        $this->activity_id = $company-> company_activity_id;
        $this->email = $company-> email;
        $this->telephone = $company-> telephone;
        $this->legal_representative = $company-> legal_representative;
        $this->dni_legal_representative = $company-> dni_legal_representative;
        $this->quote = $company-> quote;
        $this->cnae_id = $company-> cnae_id;
        $this->average_template = $company-> average_template;
        $this->iban = $company-> iban;
        $this->sepa = $company-> sepa;
        $this->b2b = $company-> b2b;
        $this->address = $company-> address;
        $this->post_code = $company-> post_code;
        $this->population_id = $company-> population_id;
        $this->province_id = $company-> province_id;
        $this->population = $company-> population;
        $this->active = $company-> active;
        $this->advisor_id = $company-> advisor_id;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required',
            'type_id' => 'required',
            'activity_id' => 'required',
            'province_id' => 'required',
        ]);

        if ($this->selected_id) {
            if ($this->nif){
                $nif = Provider::findNif($this->nif);
                if ($nif){
                    $this->emit('alreadyExists', 'nif');
                    return;
                }
            }

            $data = [
                'name' => $this-> name,
                'company_id' => $this->company_id,
                'irpf' => $this-> irpf,
                'commission' => $this-> commission,
                'contact_1' => $this-> contact_1,
                'contact_2' => $this-> contact_2,
                'contact_3' => $this-> contact_3,
                'nif' => $this-> nif,
                'company_type_id' => $this-> type_id,
                'company_activity_id' => $this-> activity_id,
                'email' => $this-> email,
                'telephone' => $this-> telephone,
                'legal_representative' => $this-> legal_representative,
                'dni_legal_representative' => $this-> dni_legal_representative,
                'quote' => $this-> quote,
                'cnae_id' => $this-> cnae_id,
                'average_template' => $this-> average_template,
                'iban' => $this-> iban,
                'sepa' => $this-> sepa,
                'b2b' => $this-> b2b,
                'address' => $this-> address,
                'post_code' => $this-> post_code,
                'province_id' => $this-> province_id,
                'population' => $this-> population,
                'active' => $this-> active == true ? 1 : 0,
                'advisor_id' => $this-> advisor_id
            ];
            $provider = Provider::updateProvider($this->selected_id, $data);

            Company::createCompany($provider->company_id, $data);
            $this->emit('toastr', 'success');
            session()->flash('message', 'Proveedor actualizado con exito.');
        }
    }
}
