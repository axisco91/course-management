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

class CompaniesCreate extends Component
{
    public $name, $nif, $type_id, $activity_id, $email, $telephone, $legal_representative, $dni_legal_representative, $quote, $cnae_id, $average_template, $iban, $sepa, $b2b, $address, $post_code, $population_id, $province_id, $population, $active = 1, $advisor_id, $observation, $collaborator_id, $potential;
    public $company_types, $company_activities, $cnaes, $provinces, $advisors, $company_id, $observations = null, $collaborators;

    public function render()
    {
        return view('livewire.companies.create');
    }

    public function mount(){
        $this->company_types = CompanyType::all();
        $this->company_activities = CompanyActivity::all();
        $this->cnaes = Cnae::all();
        $this->provinces = Province::all();
        $this->advisors = Advisor::all();
        $this->collaborators = User::where('has_commission', 1)->get();
        $this->company_id = null;
    }

    public function hydrate(){
        $this->emit('select2');
    }

    public function store()
    {
        $this->validate([
            'name' => 'required',
            'type_id' => 'required',
            'activity_id' => 'required',
            'province_id' => 'required'
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
            'advisor_id' => $this-> advisor_id,
            'collaborator_id' => $this->collaborator_id,
            'potential' => $this->potential == true ? 1 : 0,
            'active' => $this->potential == true ? 0 : ($this-> active == true ? 1 : 0),
        ];

        $company = Company::createCompany($data);

        session()->flash('message', 'Company Successfully created.');
        $this->emit('toastr', 'success');
        return redirect('/companies/edit/'.$company->id);

    }
}
