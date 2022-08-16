<?php

namespace App\Http\Livewire;

use App\Exports\AdvisorsExport;
use App\Models\Advisor;
use App\Models\Cnae;
use App\Models\Company;
use App\Models\CompanyActivity;
use App\Models\CompanyType;
use App\Models\Province;
use Livewire\Component;
use Livewire\WithPagination;
use function view;

class Advisors extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $inactiveFilter, $name, $company_id, $irpf, $commission, $contact_1, $contact_2, $contact_3, $nif, $type_id, $activity_id, $email, $telephone, $legal_representative, $dni_legal_representative, $quote, $cnae_id, $average_template, $iban, $sepa, $b2b, $address, $post_code, $population_id, $province_id, $population, $active, $advisor_id, $inactive;
    public $company_types, $company_activities, $cnaes, $provinces, $company_advisors, $tab = 'info';
    public $search_name, $companies, $search_nif, $search_type_id, $search_activity_id, $search_province_id, $search_company_name;
    public $updateMode = false;

    protected $listeners = [
        'destroy' => 'destroy'
    ];

    public function render()
    {
        $keyWord = '%'.$this->keyWord .'%';
        $search_name = '%'.$this->search_name.'%';
        $search_nif = '%'.$this->search_nif.'%';

        $advisors = Advisor::getAdvisors($keyWord, $this->inactiveFilter, $search_name, $search_nif, $this->search_type_id, $this->search_activity_id, $this->search_province_id);
        $search_company_name = '%'.$this->search_company_name.'%';
        if ($this->selected_id){
            $this->companies = Company::getAdvisorsCompanies($this->selected_id, $search_company_name);
        }

        foreach ($advisors as $advisor){
            $company = Company::where('advisor_id', $advisor->id)->first();
            if ($company){
                $advisor['used'] = true;
            } else{
                $advisor['used'] = false;
            }
        }

        return view('livewire.advisors.list', [
            'advisors' => $advisors,
        ]);
    }

    public function mount(){
        $this->company_types = CompanyType::all();
        $this->company_activities = CompanyActivity::all();
        $this->cnaes = Cnae::all();
        $this->provinces = Province::all();
        $this->company_advisors = Advisor::select('advisors.*')
            ->join('companies', 'companies.id', '=', 'advisors.company_id')
            ->where('companies.inactive', 0)->get();
    }

    public function hydrate(){
        $this->emit('select2');
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

    public function destroy($id)
    {
        if ($id){
            $company = Company::where('advisor_id', $id)->first();
            if ($company){

            } else{
                Advisor::destroy($id);
            }
        }
    }

    public function general($id){
        if ($id){
            $advisor = Advisor::find($id);
            $record = Company::find($advisor->company_id);
            $this->selected_id = $id;
            $this->name = $advisor->name;
            $this->company_id = $advisor->company_id;
            $this->irpf = $advisor->irpf;
            $this->commission = $advisor->commission;
            $this->contact_1 = $advisor->contact_1;
            $this->contact_2 = $advisor->contact_2;
            $this->contact_3 = $advisor->contact_3;
            $this->nif = $record->nif;
            $this->type_id = $record->company_type_id;
            $this->activity_id = $record->company_activity_id;
            $this->email = $record->email;
            $this->telephone = $record->telephone;
            $this->legal_representative = $record->legal_representative;
            $this->dni_legal_representative = $record->dni_legal_representative;
            $this->quote = $record->quote;
            $this->cnae_id = $record->cnae_id;
            $this->average_template = $record->average_template;
            $this->iban = $record->iban;
            $this->sepa = $record->sepa;
            $this->b2b = $record->b2b;
            $this->address = $record->address;
            $this->post_code = $record->post_code;
            $this->population_id = $record->population_id;
            $this->province_id = $record->province_id;
            $this->population = $record->population;
            $this->active = $record->active;
            $this->advisor_id = $record->advisor_id;
        }
    }

    public function downloadExcel(){
        $this->excelModal = false;
        return (new AdvisorsExport($this->search_name, $this->search_nif, $this->search_type_id, $this->search_activity_id, $this->search_province_id, $this->inactiveFilter))->download('asesorias.xlsx');
    }
}
