<?php

namespace App\Http\Livewire;

use App\Models\Advisor;
use App\Models\Cnae;
use App\Models\CompanyActivity;
use App\Models\CompanyType;
use App\Models\Province;
use App\Models\User;
use Livewire\Component;
use App\Models\Company;

class CompaniesView extends Component
{
    public $selected_id, $name, $nif, $type_id, $activity_id, $email, $telephone, $legal_representative, $dni_legal_representative, $quote, $cnae_id, $average_template, $iban, $sepa, $b2b, $address, $post_code, $population_id, $province_id, $population, $active, $advisor_id, $observation, $collaborator_id, $potential;
    public $updateMode = false, $createObservationModal = false;
    public $company_types, $company_activities, $cnaes, $provinces, $advisors, $company_id, $observations = null, $collaborators;
    public function render()
    {
        return view('livewire.companies.view');
    }

    public function mount($id){
        $this->company_types = CompanyType::all();
        $this->company_activities = CompanyActivity::all();
        $this->cnaes = Cnae::all();
        $this->provinces = Province::all();
        $this->advisors = Advisor::all();
        $this->collaborators = User::where('has_commission', 1)->get();
        $this->company_id = null;

        $record = Company::findOrFail($id);

        $this->selected_id = $id;
        $this->name = $record-> name;
        $this->nif = $record-> nif;
        $this->type_id = $record-> company_type_id;
        $this->activity_id = $record-> company_activity_id;
        $this->email = $record-> email;
        $this->telephone = $record-> telephone;
        $this->legal_representative = $record-> legal_representative;
        $this->dni_legal_representative = $record-> dni_legal_representative;
        $this->quote = $record-> quote;
        $this->cnae_id = $record-> cnae_id;
        $this->average_template = $record-> average_template;
        $this->iban = $record-> iban;
        $this->sepa = $record-> sepa;
        $this->b2b = $record-> b2b;
        $this->address = $record-> address;
        $this->post_code = $record-> post_code;
        $this->population_id = $record-> population_id;
        $this->province_id = $record-> province_id;
        $this->population = $record-> population;
        $this->active = $record-> active;
        $this->advisor_id = $record-> advisor_id;
        $this->collaborator_id = $record->collaborator_id;
        $this->potential = $this->potential;
    }

    public function hydrate(){
        $this->emit('select2');
    }
}
