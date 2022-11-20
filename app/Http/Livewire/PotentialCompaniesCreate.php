<?php

namespace App\Http\Livewire;

use App\Models\Advisor;
use App\Models\Cnae;
use App\Models\CompanyActivity;
use App\Models\CompanyObservation;
use App\Models\CompanyType;
use App\Models\PotentialCompany;
use App\Models\Provider;
use App\Models\Province;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Company;

class PotentialCompaniesCreate extends Component
{
    public $name, $nif, $type_id, $activity_id, $email, $telephone, $legal_representative, $dni_legal_representative, $quote, $cnae_id, $average_template, $iban, $sepa, $b2b, $address, $post_code, $population_id, $province_id, $population, $active = 1, $advisor_name, $observation, $collaborator_id, $potential;
    public $company_types, $company_activities, $cnaes, $provinces, $company_id, $observations = null, $collaborators;

    public function render()
    {

        return view('livewire.potential-companies.create');
    }

    public function mount(){
        $this->company_types = CompanyType::all();
        $this->company_activities = CompanyActivity::all();
        $this->cnaes = Cnae::all();
        $this->provinces = Province::all();
        $this->collaborators = User::where('has_commission', 1)->get();
        $this->company_id = null;
    }

    public function hydrate(){
        $this->emit('select2');
    }

    public function cancel()
    {
        $this->resetInput();
        $this->updateMode = false;
        $this->createObservationModal = false;
        $this->updateObservationModal = false;
    }

    private function resetInput()
    {
        $this->name = null;
        $this->nif = null;
        $this->type_id = null;
        $this->activity_id = null;
        $this->email = null;
        $this->telephone = null;
        $this->legal_representative = null;
        $this->dni_legal_representative = null;
        $this->quote = null;
        $this->cnae_id = null;
        $this->average_template = null;
        $this->iban = null;
        $this->sepa = null;
        $this->b2b = null;
        $this->address = null;
        $this->post_code = null;
        $this->population_id = null;
        $this->province_id = null;
        $this->population = null;
        $this->active = 1;
        $this->available_credit = null;
        $this->consumed_credit = null;
        $this->remaining_credit = null;
        $this->advisor_id = null;
        $this->company_id = null;
        $this->observation = null;
        $this->observations = null;
        $this->collaborator_id = null;
    }

    public function resetObservation(){
        $this->observation = null;
    }

    public function store()
    {
        $this->validate([
            'activity_id' => 'required',
            'province_id' => 'required',
            'type_id' => 'required'
        ]);

        if ($this->nif){
            $nif = Company::findNif($this->nif);
            if ($nif){
                $this->emit('alreadyExists', 'nif');
                return;
            }
        }

        $data = [
            'name' => $this-> name,
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
            'active' => 1,
            'advisor_name' => $this-> advisor_name,
            'collaborator_id' => $this->collaborator_id,
            'potential' => $this->potential
        ];

        PotentialCompany::createPotentialCompany($data);

        $this->resetInput();
        $this->emit('closeModal');
        session()->flash('message', 'Company Successfully created.');
        return redirect('potential_company/ finalized');
    }
}
