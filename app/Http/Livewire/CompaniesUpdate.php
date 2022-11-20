<?php

namespace App\Http\Livewire;

use App\Models\Advisor;
use App\Models\Cnae;
use App\Models\CompanyActivity;
use App\Models\CompanyType;
use App\Models\Provider;
use App\Models\Province;
use App\Models\User;
use Livewire\Component;
use App\Models\Company;

class CompaniesUpdate extends Component
{
    public $selected_id, $name, $nif, $type_id, $activity_id, $email, $telephone, $legal_representative, $dni_legal_representative, $quote, $cnae_id, $average_template, $iban, $sepa, $b2b, $address, $post_code, $population_id, $province_id, $population, $active, $advisor_id, $observation, $collaborator_id, $potential;
    public $updateMode = false, $createObservationModal = false, $collaborators;
    public $company_types, $company_activities, $cnaes, $provinces, $advisors, $company_id, $observations = null;

    public function render()
    {
        return view('livewire.companies.update');
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
        $this->potential = $record->potential;
    }

    public function hydrate(){
        $this->emit('select2');
    }

    public function update()
    {
        $this->validate([
            'name' => 'required',
            'type_id' => 'required|numeric|min:1',
            'activity_id' => 'required|numeric|min:1',
            'province_id' => 'required|numeric|min:1'
        ]);

        if ($this->selected_id) {
            if ($this->nif){
                $nif = Company::findNif($this->nif, $this->selected_id);
                if ($nif){
                    $this->emit('alreadyExists', 'nif');
                    return;
                }
            }

            $data = [
                'name' => $this-> name,
                'nif' => $this-> nif,
                'company_type_id' => $this-> type_id != -1 ? $this-> type_id : null,
                'company_activity_id' => $this-> activity_id != -1 ? $this-> activity_id : null,
                'email' => $this-> email,
                'telephone' => $this-> telephone,
                'legal_representative' => $this-> legal_representative,
                'dni_legal_representative' => $this-> dni_legal_representative,
                'quote' => $this-> quote,
                'cnae_id' => $this-> cnae_id != -1 ? $this-> cnae_id : null,
                'average_template' => $this-> average_template,
                'iban' => $this-> iban,
                'sepa' => $this-> sepa,
                'b2b' => $this-> b2b,
                'address' => $this-> address,
                'post_code' => $this-> post_code,
                'province_id' => $this-> province_id != -1 ? $this-> province_id : null,
                'population' => $this-> population,
                'active' => $this-> active == true ? 1 : 0,
                'advisor_id' => $this-> advisor_id != -1 ? $this-> advisor_id : null,
                'collaborator_id' => $this->collaborator_id != -1 ? $this->collaborator_id : null,
                'potential' => 0,
            ];

            Company::updateCompany($this->selected_id, $data);

            $advisor = Advisor::where('company_id', $this->selected_id)->first();
            if ($advisor){
                $data['company_id'] = $this->selected_id;
                $advisor->updateAdvisorCompany($advisor->id, $data);
            }
            $provider = Provider::where('company_id', $this->selected_id)->first();
            if ($provider){
                $provider->update([
                    'name' => $this-> name,
                ]);
            }
            session()->flash('message', 'Empresa Actulizado con exito.');
        }
    }
    public function convertClient()
    {
        $this->validate([
            'name' => 'required',
            'type_id' => 'required|numeric|min:1',
            'activity_id' => 'required|numeric|min:1',
            'province_id' => 'required|numeric|min:1',
        ]);

        if ($this->selected_id) {
            if ($this->nif){
                $nif = Company::findNif($this->nif, $this->selected_id);
                if ($nif){
                    $this->emit('alreadyExists', 'nif');
                    return;
                }
            }

            $data = [
                'name' => $this-> name,
                'nif' => $this-> nif,
                'company_type_id' => $this-> type_id != -1 ? $this-> type_id : null,
                'company_activity_id' => $this-> activity_id != -1 ? $this-> activity_id : null,
                'email' => $this-> email,
                'telephone' => $this-> telephone,
                'legal_representative' => $this-> legal_representative,
                'dni_legal_representative' => $this-> dni_legal_representative,
                'quote' => $this-> quote,
                'cnae_id' => $this-> cnae_id != -1 ? $this-> cnae_id : null,
                'average_template' => $this-> average_template,
                'iban' => $this-> iban,
                'sepa' => $this-> sepa,
                'b2b' => $this-> b2b,
                'address' => $this-> address,
                'post_code' => $this-> post_code,
                'province_id' => $this-> province_id != -1 ? $this-> province_id : null,
                'population' => $this-> population,
                'active' => $this-> active == true ? 1 : 0,
                'advisor_id' => $this-> advisor_id != -1 ? $this-> advisor_id : null,
                'collaborator_id' => $this->collaborator_id != -1 ? $this->collaborator_id : null,
                'potential' => 1,
            ];

            $company = Company::updateCompany($this->selected_id, $data);

            $advisor = Advisor::where('company_id', $this->selected_id)->first();
            if ($advisor){
                $data['company_id'] = $this->selected_id;
                $advisor->updateAdvisorCompany($advisor->id, $data);
            }
            $provider = Provider::where('company_id', $this->selected_id)->first();
            if ($provider){
                $provider->update([
                    'name' => $this-> name,
                ]);
            }

            session()->flash('message', 'Empresa Actulizado con exito.');
            $this->emit('toastr', 'success');
            return redirect('/companies/edit/'.$company->id);
        }
    }
}
