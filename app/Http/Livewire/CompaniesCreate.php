<?php

namespace App\Http\Livewire;

use App\Models\Advisor;
use App\Models\Cnae;
use App\Models\CompanyActivity;
use App\Models\CompanyObservation;
use App\Models\CompanyType;
use App\Models\Provider;
use App\Models\Province;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Company;

class CompaniesCreate extends Component
{
    public $name, $nif, $type_id, $activity_id, $email, $telephone, $legal_representative, $dni_legal_representative, $quote, $cnae_id, $average_template, $iban, $sepa, $b2b, $address, $post_code, $population_id, $province_id, $population, $active, $advisor_id, $observation;
    public $company_types, $company_activities, $cnaes, $provinces, $advisors, $company_id, $observations = null;
    public $route;

    public function render()
    {

        return view('livewire.companies.create');
    }

    public function mount(){
        $this->company_types = CompanyType::all();
        $this->company_activities = CompanyActivity::all();
        $this->cnaes = Cnae::all();
        $this->provinces = Province::all();
        $this->advisors = Advisor::select('advisors.*')
            ->join('companies', 'companies.id', '=', 'advisors.company_id')
            ->where('companies.active', 0)->get();
        $this->company_id = null;

        $this->route = url()->previous();
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
        $this->active = null;
        $this->available_credit = null;
        $this->consumed_credit = null;
        $this->remaining_credit = null;
        $this->advisor_id = null;
        $this->company_id = null;
        $this->observation = null;
        $this->observations = null;
    }

    public function resetObservation(){
        $this->observation = null;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required',
            'type_id' => 'required',
            'activity_id' => 'required',
            'province_id' => 'required',
        ]);

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
            'active' => $this-> active == true ? 1 : 0,
            'advisor_id' => $this-> advisor_id
        ];

        Company::createCompany($data);

        $this->resetInput();
        $this->emit('closeModal');
        session()->flash('message', 'Company Successfully created.');
        return $this->redirect($this->route);
    }
}
