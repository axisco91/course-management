<?php

namespace App\Http\Livewire;

use App\Models\Company;
use App\Models\CompanyIncidence;
use App\Models\IncidenceType;
use App\Models\TrainingContract;
use App\Models\TrainingContractIncidence;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use function session;
use function url;
use function view;

class TrainingContractIncidencesCreate extends Component
{

    public $user_id, $affair, $notes, $incidence_type_id, $training_contract_id;
    public $incidence_types, $training_contracts, $users;
    public $route;

    public function render()
    {
        return view('livewire.training-contract-incidences.create');
    }

    public function mount($training_contract_id){
        if ($training_contract_id) {
            $this->training_contract_id = $training_contract_id;
        }
        $this->training_contracts = TrainingContract::all();
        $this->incidence_types = IncidenceType::all();
        $this->users = User::all();
        $this->user_id = Auth::user()->id;
    }

    public function store()
    {
        $this->validate([
            'affair' => 'required',
            'training_contract_id' => 'required',
            'user_id' => 'required',
            'incidence_type_id' => 'required'
        ]);

        $data = [
            'training_contract_id' => $this->training_contract_id,
            'affair' => $this->affair,
            'notes' => $this->notes,
            'user_id' => $this->user_id,
            'incidence_type_id' => $this->incidence_type_id,
        ];

        $incidence = TrainingContractIncidence::createTrainingContractIncidence($data);

        session()->flash('message', 'Incidencia creado con exito.');
        $this->emit('toastr', 'success');
        return redirect('training_contract_incidences/edit/'.$incidence->id);
    }
}
