<?php

namespace App\Http\Livewire;

use App\Models\IncidenceType;
use App\Models\Tracing;
use App\Models\TracingCommunication;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use function redirect;
use function session;
use function url;
use function view;

class TracingCommunicationsUpdate extends Component
{

    public $selected_id, $user_id, $affair, $notes, $incidence_type_id, $tracing_id;
    public $incidence_types, $tracings, $users;
    public $route;

    public function render()
    {
        return view('livewire.tracing-communications.create');
    }

    public function mount($id){
        if ($id) {
            $this->selected_id = $id;
            $tracing_communication = TracingCommunication::find($id);
            $this->affair = $tracing_communication['affair'];
            $this->user_id = $tracing_communication['user_id'];
            $this->notes = $tracing_communication['notes'];
            $this->incidence_type_id = $tracing_communication['incidence_type_id'];
            $this->tracing_id = $tracing_communication['tracing_id'];
        }
        $this->tracings = Tracing::select('*', 'courses.name as course_name', 'students.name as student_name', 'students.surname as student_surname')
            ->leftjoin('students', 'students.id', '=', 'tracings.student_id')
            ->leftjoin('courses', 'courses.id', '=', 'tracings.course_id')->get();
        $this->incidence_types = IncidenceType::all();
        $this->users = User::all();
        $this->user_id = Auth::user()->id;
    }

    private function resetInput()
    {

        $this->route = url()->previous();
    }

    public function update()
    {
        $this->validate([
            'affair' => 'required',
            'tracing_id' => 'required',
            'user_id' => 'required',
            'incidence_type_id' => 'required'
        ]);

        $data = [
            'tracing_id' => $this->tracing_id,
            'affair' => $this->affair,
            'notes' => $this->notes,
            'user_id' => $this->user_id,
            'incidence_type_id' => $this->incidence_type_id,
        ];

        TracingCommunication::updateTracingCommunication($this->selected_id, $data);

        $this->resetInput();
        $this->emit('closeModal');
        session()->flash('message', 'Comunicación creado con exito.');
        return redirect($this->route);
    }
}
