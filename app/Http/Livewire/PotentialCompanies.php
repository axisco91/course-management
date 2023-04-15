<?php

namespace App\Http\Livewire;

use App\Models\Cnae;
use App\Models\CompanyActivity;
use App\Models\CompanyType;
use App\Models\PotentialCompany;
use App\Models\Province;
use Livewire\Component;
use Livewire\WithPagination;
use App\Mail\PotentialCompany as PotentialEmail;
use Mail;

class PotentialCompanies extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $inactiveFilter, $name, $nif, $type_id, $activity_id, $email, $telephone, $legal_representative,
        $dni_legal_representative, $quote, $cnae_id, $average_template, $iban, $sepa, $b2b, $address, $post_code, $population_id,
        $province_id, $population, $active, $advisor_name, $converted, $send_form;
    public $updateMode = false;
    public $company_types, $company_activities, $cnaes, $provinces, $company_id, $tab = 'info';
    public $search_name, $search_nif, $search_type_id, $search_activity_id, $search_province_id;
    protected $listeners = [
        'destroy' => 'destroy'
    ];
    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        $companies = PotentialCompany::getPotentialCompanies($keyWord, $this->search_name, $this->search_nif, $this->search_type_id, $this->search_activity_id, $this->search_province_id);

        return view('livewire.potential-companies.list', [
            'companies' => $companies
        ]);
    }

    public function mount(){
        $this->company_types = CompanyType::all();
        $this->company_activities = CompanyActivity::all();
        $this->cnaes = Cnae::all();
        $this->provinces = Province::all();
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
		$this->activity_name = null;
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
        $this->converted = null;
    }

    public function resetObservation(){
        $this->observation = null;
    }

    public function general($id)
    {
        if ($id){
            $record = NewPotentialCompany::findOrFail($id);

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
            $this->advisor_name = $record-> advisor_id;
        }
    }

    public function sendEmail(){
        Mail::to($this->send_form)->send(new PotentialEmail());
        $this->resetInput();
        $this->emit('closeModal');
        session()->flash('message', 'Correo enviado con exito.');
        $this->emit('toastr', 'success');
    }

    public function destroy($id)
    {
        if ($id) {
            NewPotentialCompany::destroy($id);
        }
    }
}
