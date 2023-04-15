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

class ProvidersView extends Component
{
    public $selected_id, $keyWord, $inactiveFilter, $name, $company_id, $irpf, $commission, $contact_1, $contact_2, $contact_3, $nif, $type_id, $activity_id, $email, $telephone, $legal_representative, $dni_legal_representative, $quote, $cnae_id, $average_template, $iban, $sepa, $b2b, $address, $post_code, $population_id, $province_id, $population, $active, $advisor_id;
    public $company_types, $company_activities, $cnaes, $provinces, $advisors;

    public function render()
    {
        return view('livewire.providers.view');
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
}
