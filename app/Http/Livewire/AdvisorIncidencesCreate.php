<?php

namespace App\Http\Livewire;

use App\Models\Advisor;
use App\Models\AdvisorIncidence;
use App\Models\Company;
use App\Models\IncidenceType;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use function redirect;
use function session;
use function url;
use function view;

class AdvisorIncidencesCreate extends Component
{

    public $user_id, $affair, $notes, $incidence_type_id, $advisor_id;
    public $incidence_types, $advisors, $users;
    public $route;

    public function render()
    {
        return view('livewire.advisor-incidences.create');
    }

    public function mount($advisor_id){
        if ($advisor_id) {
            $this->advisor_id = $advisor_id;
        }
        $this->advisors = Advisor::all();
        $this->incidence_types = IncidenceType::all();
        $this->users = User::all();
        $this->user_id = Auth::user()->id;
    }

    private function resetInput()
    {

        $this->route = url()->previous();
    }

    public function store()
    {
        $this->validate([
            'affair' => 'required',
            'advisor_id' => 'required',
            'user_id' => 'required',
            'incidence_type_id' => 'required'
        ]);

        $data = [
            'advisor_id' => $this->advisor_id,
            'affair' => $this->affair,
            'notes' => $this->notes,
            'user_id' => $this->user_id,
            'incidence_type_id' => $this->incidence_type_id,
        ];

        AdvisorIncidence::createAdvisorIncidence($data);

        $this->resetInput();
        session()->flash('message', 'Asesoria creado con exito.');
        $this->emit('toastr', 'success');
        return redirect($this->route);
    }
}
