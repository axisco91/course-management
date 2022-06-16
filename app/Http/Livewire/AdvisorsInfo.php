<?php

namespace App\Http\Livewire;

use App\Models\Advisor;
use App\Models\Cnae;
use App\Models\Company;
use App\Models\CompanyActivity;
use App\Models\CompanyType;
use App\Models\Province;
use Livewire\Component;
use function redirect;
use function session;
use function url;
use function view;

class AdvisorsInfo extends Component
{

    public $selected_id, $keyWord, $inactiveFilter, $name, $company_id, $irpf, $commission, $contact_1, $contact_2, $contact_3, $nif, $type_id, $activity_id, $email, $telephone, $legal_representative, $dni_legal_representative, $quote, $cnae_id, $average_template, $iban, $sepa, $b2b, $address, $post_code, $population_id, $province_id, $population, $active, $advisor_id, $inactive;
    public $company_types, $activities, $cnaes, $provinces, $company_advisors;
    public $updateMode = false;
    public $route;

    public function render()
    {
        return view('livewire.advisors.info');
    }

    public function mount($id)
    {
        $this->company_types = CompanyType::all();
        $this->company_activities = CompanyActivity::all();
        $this->cnaes = Cnae::all();
        $this->provinces = Province::all();
        $this->company_advisors = Advisor::select('advisors.*')
            ->join('companies', 'companies.id', '=', 'advisors.company_id')
            ->where('companies.inactive', 0)->get();

        $record = Company::findOrFail($id);
        $advisor = Advisor::where('company_id', $record->id)->first();

        $this->selected_id = $advisor->id;
        $this->name = $advisor->name;
        $this->company_id = $advisor->company_id;
        $this->irpf = $advisor->irpf;
        $this->commission = $advisor->commission;
        $this->contact_1 = $advisor->contact_1;
        $this->contact_2 = $advisor->contact_2;
        $this->contact_3 = $advisor->contact_3;
        $this->nif = $record->nif;
        $this->type_id = $record->company_type_id;
        $this->activity_id = $record->company_activity_id;
        $this->email = $record->email;
        $this->telephone = $record->telephone;
        $this->legal_representative = $record->legal_representative;
        $this->dni_legal_representative = $record->dni_legal_representative;
        $this->quote = $record->quote;
        $this->cnae_id = $record->cnae_id;
        $this->average_template = $record->average_template;
        $this->iban = $record->iban;
        $this->sepa = $record->sepa;
        $this->b2b = $record->b2b;
        $this->address = $record->address;
        $this->post_code = $record->post_code;
        $this->population_id = $record->population_id;
        $this->province_id = $record->province_id;
        $this->population = $record->population;
        $this->active = $record->active;
        $this->advisor_id = $record->advisor_id;

        $this->route = url()->previous();
    }
}
