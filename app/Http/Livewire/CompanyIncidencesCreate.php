<?php

namespace App\Http\Livewire;

use App\Models\Company;
use App\Models\CompanyIncidence;
use App\Models\IncidenceType;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use function session;
use function url;
use function view;

class CompanyIncidencesCreate extends Component
{

    public $user_id, $affair, $notes, $incidence_type_id, $company_id;
    public $incidence_types, $companies, $users;
    public $route;

    public function render()
    {
        return view('livewire.company-incidences.create');
    }

    public function mount($company_id){
        if ($company_id) {
            $this->company_id = $company_id;
        }
        $this->companies = Company::all();
        $this->incidence_types = IncidenceType::all();
        $this->users = User::all();
        $this->user_id = Auth::user()->id;
    }

    public function store()
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

        $incidence = CompanyIncidence::createCompanyIncidence($data);

        session()->flash('message', 'Incidencia creado con exito.');
        $this->emit('toastr', 'success');
        return redirect('company_incidences/edit/'.$incidence->id);
    }
}
