<?php

namespace App\Http\Livewire;

use App\Models\Advisor;
use App\Models\Cnae;
use App\Models\CompanyActivity;
use App\Models\CompanyObservation;
use App\Models\CompanyType;
use App\Models\Provider;
use App\Models\Province;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Company;

class Companies extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $name, $nif, $type_id, $activity_id, $email, $telephone, $legal_representative, $dni_legal_representative, $quote, $cnae_id, $average_template, $iban, $sepa, $b2b, $address, $post_code, $population_id, $province_id, $population, $active, $advisor_id, $observation;
    public $updateMode = false, $createObservationModal = false, $updateObservationModal = false;
    public $company_types, $company_activities, $cnaes, $provinces, $advisors, $company_id, $observations = null;
    public $create_type_id, $create_activity_id, $create_cnae_id, $create_province_id, $create_advisor_id;
    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';

        $companies = Company::select('companies.*', 'company_types.name as type',
            'company_activities.name as activity', 'cnaes.name as cnae',
            'provinces.name as province',
            'advisors.name as advisor')
            ->leftjoin('company_types', 'company_types.id', '=', 'companies.company_type_id')
            ->leftjoin('company_activities', 'company_activities.id', '=', 'companies.company_activity_id')
            ->leftjoin('cnaes', 'cnaes.id', '=', 'companies.cnae_id')
            ->leftjoin('provinces', 'provinces.id', '=', 'companies.province_id')
            ->leftjoin('advisors', 'advisors.id', '=', 'companies.advisor_id')
            ->orWhere('companies.name', 'LIKE', $keyWord)
            ->orWhere('nif', 'LIKE', $keyWord)
            ->orWhere('company_types.name', 'LIKE', $keyWord)
            ->orWhere('company_activities.name', 'LIKE', $keyWord)
            ->orWhere('email', 'LIKE', $keyWord)
            ->orWhere('telephone', 'LIKE', $keyWord)
            ->orWhere('legal_representative', 'LIKE', $keyWord)
            ->orWhere('dni_legal_representative', 'LIKE', $keyWord)
            ->orWhere('quote', 'LIKE', $keyWord)
            ->orWhere('cnaes.name', 'LIKE', $keyWord)
            ->orWhere('average_template', 'LIKE', $keyWord)
            ->orWhere('iban', 'LIKE', $keyWord)
            ->orWhere('sepa', 'LIKE', $keyWord)
            ->orWhere('b2b', 'LIKE', $keyWord)
            ->orWhere('address', 'LIKE', $keyWord)
            ->orWhere('post_code', 'LIKE', $keyWord)
            ->orWhere('provinces.name', 'LIKE', $keyWord)
            ->orWhere('population', 'LIKE', $keyWord)
            ->orWhere('active', 'LIKE', $keyWord)
            ->orWhere('advisors.name', 'LIKE', $keyWord)
            ->paginate(10);

        foreach ($companies as $company) {
            $advisor = Advisor::where('company_id', $company['id'])->first();
            if (!$advisor) {
                $company['is_advisor'] = true;
            }
            $provider = Provider::where('company_id', $company['id'])->first();
            if (!$provider) {
                $company['is_provider'] = true;
            }
        }

        if ($this->observations) {
            foreach ($this-> observations as $observation){
                $observation['date'] = Carbon::createFromFormat('Y-m-d H:i:s', $observation['created_at'])->format('d/m/Y');
            }
        }

        return view('livewire.companies.view', [
            'companies' => $companies
        ]);
    }

    public function mount(){
        $this->company_types = CompanyType::all();
        $this->company_activities = CompanyActivity::all();
        $this->cnaes = Cnae::all();
        $this->provinces = Province::all();
        $this->advisors = Advisor::all();
        $this->company_id = null;
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
    }

    public function store()
    {
        $this->validate([
		'name' => 'required',
		'create_type_id' => 'required',
		'create_activity_id' => 'required',
		'create_province_id' => 'required',
        ]);

        Company::create([
			'name' => $this-> name,
			'nif' => $this-> nif,
			'company_type_id' => $this-> create_type_id,
			'company_activity_id' => $this-> create_activity_id,
			'email' => $this-> email,
			'telephone' => $this-> telephone,
			'legal_representative' => $this-> legal_representative,
			'dni_legal_representative' => $this-> dni_legal_representative,
			'quote' => $this-> quote,
			'cnae_id' => $this-> create_cnae_id,
			'average_template' => $this-> average_template,
			'iban' => $this-> iban,
			'sepa' => $this-> sepa,
			'b2b' => $this-> b2b,
			'address' => $this-> address,
			'post_code' => $this-> post_code,
			'province_id' => $this-> create_province_id,
			'population' => $this-> population,
			'active' => $this-> active == true ? 1 : 0,
			'advisor_id' => $this-> create_advisor_id
        ]);

        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Company Successfully created.');
    }

    public function edit($id)
    {
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

        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
		'name' => 'required',
        'type_id' => 'required|numeric|min:1',
        'activity_id' => 'required|numeric|min:1',
        'province_id' => 'required|numeric|min:1',
        ]);

        if ($this->selected_id) {
			$record = Company::find($this->selected_id);
            $record->update([
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
                'advisor_id' => $this-> advisor_id != -1 ? $this-> advisor_id : null
            ]);

            $advisor = Advisor::where('company_id', $this->selected_id)->first();
            if ($advisor){
                $advisor->update([
                    'name' => $this-> name,
                ]);
            }
            $provider = Provider::where('company_id', $this->selected_id)->first();
            if ($provider){
                $provider->update([
                    'name' => $this-> name,
                ]);
            }

            $this->resetInput();
            $this->updateMode = false;
			session()->flash('message', 'Company Successfully updated.');
        }
    }

    public function convertAdvisor($id){
        if ($id) {
            $record = Company::find($id);
            Advisor::create([
                'name' => $record['name'],
                'company_id' => $record['id']
            ]);
            session()->flash('message', 'Empresa convertido a asesoria con exito');
        }
    }

    public function convertProvider($id){
        if ($id) {
            $record = Company::find($id);
            Provider::create([
                'name' => $record['name'],
                'company_id' => $record['id']
            ]);
            session()->flash('message', 'Empresa convertido a proveedor con exito');
        }
    }
    public function newObservation($id) {
        if ($id) {
            $this-> company_id = $id;

            $this->createObservationModal = true;
        }
    }

    public function createObservation() {
        $this->validate([
            'observation' => 'required',
        ]);

        CompanyObservation::create([
            'company_id' => $this->company_id,
            'observation' => $this->observation
        ]);

        $this->resetInput();
        $this->createObservationModal = false;
        session()->flash('message', 'Obseervación creado con exito.');
    }

    public function observations($id){
        if ($id){
            $this-> observations = CompanyObservation::where('company_id', $id)->get();

            foreach ($this-> observations as $observation){
                $observation['date'] = Carbon::createFromFormat('Y-m-d H:i:s', $observation['created_at'])->format('d/m/Y');
            }
        }
    }

    public function editObservation($id) {
        if ($id) {
            $record = CompanyObservation::findOrFail($id);
            $this->selected_id = $record-> id;
            $this->observation = $record-> observation;

            $this->updateObservationModal = true;
        }
    }

    public function updateObservation() {
        $this->validate([
            'observation' => 'required',
        ]);
        if ($this->selected_id) {
            $record = CompanyObservation::find($this->selected_id);
            $record->update([
                'observation' => $this->observation
            ]);

            $this->resetInput();
            $this->updateObservationModal = false;
            session()->flash('message', 'Obseervación actualizado con exito.');
        }
    }
    public function destroyObservation($id) {
        if ($id) {
            $record = CompanyObservation::where('id', $id);
            $record->delete();
        }
    }
}
