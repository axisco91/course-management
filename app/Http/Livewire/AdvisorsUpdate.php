<?php

namespace App\Http\Livewire;

use App\Models\Advisor;
use App\Models\Cnae;
use App\Models\Company;
use App\Models\CompanyActivity;
use App\Models\CompanyType;
use App\Models\Province;
use Livewire\Component;
use Livewire\WithPagination;
use function session;
use function url;
use function view;

class AdvisorsUpdate extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $selected_id, $name, $company_id, $irpf, $commission, $contact_1, $contact_2, $contact_3, $nif, $type_id, $activity_id, $email, $telephone, $legal_representative, $dni_legal_representative, $quote, $cnae_id, $average_template, $iban, $sepa, $b2b, $address, $post_code, $population_id, $province_id, $population, $active, $advisor_id, $inactive;
    public $company_types, $company_activities, $cnaes, $provinces, $company_advisors, $route;

    public function render()
    {

        return view('livewire.advisors.update');
    }

    public function mount($id){
        $this->company_types = CompanyType::all();
        $this->company_activities = CompanyActivity::all();
        $this->cnaes = Cnae::all();
        $this->provinces = Province::all();
        $this->company_advisors = Advisor::select('advisors.*')
            ->join('companies', 'companies.id', '=', 'advisors.company_id')
            ->where('companies.inactive', 0)->get();

        $advisor = Advisor::find($id);
        $record = Company::findOrFail($advisor->id);

        $this->selected_id = $advisor->id;
        $this->name = $advisor-> name;
        $this->company_id = $advisor-> company_id;
        $this->irpf = $advisor-> irpf;
        $this->commission = $advisor-> commission;
        $this->contact_1 = $advisor-> contact_1;
        $this->contact_2 = $advisor-> contact_2;
        $this->contact_3 = $advisor-> contact_3;
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

        $this->route = url()->previous();
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

            $advisor = Advisor::updateAdvisor($this->selected_id, $data);

            $company = Company::updateCompany($advisor->company_id, $data);

            session()->flash('message', 'Asesoria creado con exito.');
            return $this->redirect($this->route);
        }
    }
}
