<?php

namespace App\Http\Livewire;

use App\Models\Cnae;
use App\Models\Company;
use App\Models\CompanyActivity;
use App\Models\CompanyType;
use App\Models\Province;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Advisor;

class Advisors extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $name, $company_id, $irpf, $commission, $contact_1, $contact_2, $contact_3, $nif, $type_id, $activity_id, $email, $telephone, $legal_representative, $dni_legal_representative, $quote, $cnae_id, $average_template, $iban, $sepa, $b2b, $address, $post_code, $population_id, $province_id, $population, $active, $advisor_id;
    public $company_types, $company_activities, $cnaes, $provinces, $company_advisors;
    public $create_type_id, $create_activity_id, $create_cnae_id, $create_province_id, $create_advisor_id;
    public $updateMode = false;

    public function render()
    {
        $keyWord = '%'.$this->keyWord .'%';
        $advisors = Company::select('advisors.id as advisor_id','advisors.irpf', 'advisors.commission',
            'advisors.contact_1', 'advisors.contact_2', 'advisors.contact_3', 'companies.*',
            'company_types.name as type', 'company_activities.name as activity', 'cnaes.name as cnae',
            'provinces.name as province',
            'a.name as advisor')
            ->join('advisors', 'advisors.company_id', '=', 'companies.id')
            ->leftjoin('company_types', 'company_types.id', '=', 'companies.company_type_id')
            ->leftjoin('company_activities', 'company_activities.id', '=', 'companies.company_activity_id')
            ->leftjoin('cnaes', 'cnaes.id', '=', 'companies.cnae_id')
            ->leftjoin('provinces', 'provinces.id', '=', 'companies.province_id')
            ->leftjoin('advisors as a', 'advisors.id', '=', 'companies.advisor_id')
            ->orWhere('advisors.name', 'LIKE', $keyWord)
            ->orWhere('advisors.irpf', 'LIKE', $keyWord)
            ->orWhere('advisors.commission', 'LIKE', $keyWord)
            ->orWhere('advisors.contact_1', 'LIKE', $keyWord)
            ->orWhere('advisors.contact_2', 'LIKE', $keyWord)
            ->orWhere('advisors.contact_3', 'LIKE', $keyWord)
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

        return view('livewire.advisors.view', [
            'advisors' => $advisors,
        ]);
    }

    public function mount(){
        $this->company_types = CompanyType::all();
        $this->company_activities = CompanyActivity::all();
        $this->cnaes = Cnae::all();
        $this->provinces = Province::all();
        $this->company_advisors = Advisor::all();
    }

    public function cancel()
    {
        $this->resetInput();
        $this->updateMode = false;
    }

    private function resetInput()
    {
        $this->name = null;
        $this->company_id = null;
        $this->irpf = null;
        $this->commission = null;
        $this->contact_1 = null;
        $this->contact_2 = null;
        $this->contact_3 = null;
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
    }

    public function store()
    {
        $this->validate([
            'name' => 'required',
            'create_type_id' => 'required',
            'create_activity_id' => 'required',
            'create_province_id' => 'required',
        ]);

        $company = Company::create([
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

        Advisor::create([
			'name' => $this-> name,
            'company_id' => $company['id'],
			'irpf' => $this-> irpf,
			'commission' => $this-> commission,
			'contact_1' => $this-> contact_1,
			'contact_2' => $this-> contact_2,
			'contact_3' => $this-> contact_3
        ]);

        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Asesoria creado con exito.');
    }

    public function edit($id)
    {
        $record = Advisor::findOrFail($id);
        $company = Company::where('id', $record-> company_id)->first();

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

        $this->updateMode = true;
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
			$record = Advisor::find($this->selected_id);
            $record->update([
			'name' => $this-> name,
			'company_id' => $this-> company_id,
			'irpf' => $this-> irpf,
			'commission' => $this-> commission,
			'contact_1' => $this-> contact_1,
			'contact_2' => $this-> contact_2,
			'contact_3' => $this-> contact_3
            ]);

            $company = Company::find($record->company_id);
            $company->update([
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
            ]);

            $this->resetInput();
            $this->updateMode = false;
			session()->flash('message', 'Asesoria creado con exito.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = Advisor::where('id', $id);
            $record->delete();
        }
    }
}
