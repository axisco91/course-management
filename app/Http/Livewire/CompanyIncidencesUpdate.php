<?php

namespace App\Http\Livewire;

use App\Models\Company;
use App\Models\CompanyIncidence;
use App\Models\IncidenceType;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use function redirect;
use function session;
use function url;
use function view;

class CompanyIncidencesUpdate extends Component
{

    public $selected_id, $user_id, $affair, $notes, $incidence_type_id, $company_id;
    public $incidence_types, $companies, $users;

    public function render()
    {
        return view('livewire.company-incidences.update');
    }

    public function mount($id){
        if ($id) {
            $this->selected_id = $id;
            $company_incidence = CompanyIncidence::find($id);
            $this->affair = $company_incidence['affair'];
            $this->user_id = $company_incidence['user_id'];
            $this->notes = $company_incidence['notes'];
            $this->incidence_type_id = $company_incidence['incidence_type_id'];
            $this->company_id = $company_incidence['company_id'];
        }
        $this->companies = Company::all();
        $this->incidence_types = IncidenceType::all();
        $this->users = User::all();
        $this->user_id = Auth::user()->id;
    }

    public function update()
    {
        $this->validate([
            'affair' => 'required',
            'company_id' => 'required',
            'user_id' => 'required',
            'incidence_type_id' => 'required'
        ]);

        $data = [
            'company_id' => $this->company_id,
            'affair' => $this->affair,
            'notes' => $this->notes,
            'user_id' => $this->user_id,
            'incidence_type_id' => $this->incidence_type_id,
        ];

        CompanyIncidence::updateCompanyIncidence($this->selected_id, $data);

        session()->flash('message', 'Historico actualizado con exito.');
        $this->emit('toastr', 'success');
    }
}
