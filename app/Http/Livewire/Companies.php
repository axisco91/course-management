<?php

namespace App\Http\Livewire;

use App\Models\Advisor;
use App\Models\Cnae;
use App\Models\CompanyActivity;
use App\Models\CompanyObservation;
use App\Models\CompanyType;
use App\Models\Provider;
use App\Models\Province;
use App\Models\Teacher;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Company;

class Companies extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $inactiveFilter, $name, $nif, $type_id, $activity_id, $email, $telephone, $legal_representative, $dni_legal_representative, $quote, $cnae_id, $average_template, $iban, $sepa, $b2b, $address, $post_code, $population_id, $province_id, $population, $active, $advisor_id, $observation;
    public $updateMode = false, $createObservationModal = false, $updateObservationModal = false;
    public $company_types, $company_activities, $cnaes, $provinces, $advisors, $company_id, $observations = null;
    public $search_name, $search_nif;
    public function render()

    {
		$keyWord = '%'.$this->keyWord .'%';
        $search_name = '%'.$this->search_name.'%';
        $search_nif = '%'.$this->search_nif.'%';
        $companies = Company::getCompanies($keyWord, $search_name, $search_nif);

        if ($this->observations) {
            foreach ($this->observations as $observation){
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
        $this->advisors = Advisor::select('advisors.*')
            ->join('companies', 'companies.id', '=', 'advisors.company_id')
            ->where('companies.active', 1)->get();
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
        $this->observations = null;
    }

    public function resetObservation(){
        $this->observation = null;
    }

    public function general($id)
    {
        if ($id){
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

            $this-> observations = CompanyObservation::where('company_id', $id)->get();
        }
    }

    public function convertAdvisor($id){
        if ($id) {
            Advisor::convertAdvisor($id);

            session()->flash('message', 'Empresa convertido a asesoria con exito');
        }
    }

    public function convertProvider($id){
        if ($id) {
            Provider::convertProvider($id);
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

        $data = [
            'company_id' => $this->company_id,
            'observation' => $this->observation
        ];

        CompanyObservation::createCompabyObservation($data);

        $this->resetInput();
        $this->createObservationModal = false;
        session()->flash('message', 'Obseervación creado con exito.');
    }

    public function observations($id){
        if ($id){
            $this-> observations = CompanyObservation::getCompanyObservations($id);
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
            $data = [
                'observation' => $this->observation
            ];

            CompanyObservation::updateCompanyObservation($this->selected_id, $data);

            $this->resetObservation();
            $this->updateObservationModal = false;
            session()->flash('message', 'Obseervación actualizado con exito.');
        }
    }
    public function destroyObservation($id) {
        if ($id) {
            CompanyObservation::destroy($id);
        }
    }

    public function changeState($id){
        $active = Company::changeState($id);
        if ($active == 1){
            session()->flash('message', 'Empresa activado con exito.');
        } else {
            session()->flash('message', 'Empresa desactivado con exito.');
        }
    }
}
