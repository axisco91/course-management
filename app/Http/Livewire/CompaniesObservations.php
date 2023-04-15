<?php

namespace App\Http\Livewire;

use App\Models\Certification;
use App\Models\CompanyObservation;
use Livewire\WithPagination;
use Livewire\Component;

class CompaniesObservations extends Component
{
    protected $paginationTheme = 'bootstrap';
    public $selected_id, $observations, $observation, $observation_id;
    protected $listeners = ['destroy' => 'destroy'];

    public function render()
    {
        if ($this->selected_id){
            $this->observations = CompanyObservation::where('company_id', $this->selected_id)->get();
        }
        return view('livewire.companies.companies-observations');
    }

    public function mount($id){
        $this->selected_id = $id;
    }

    public function update()
    {
        $this->validate([
            'observation' => 'required',
        ]);

        $observation = CompanyObservation::find($this->observation_id);
        $observation->update([
            'observation' => $this->observation
        ]);
    }

    private function resetInput(){
        $this->observation_id = null;
        $this->observation = null;
    }

    public function new(){
        $this->resetInput();
    }

    public function edit($id){
        $this->observation_id = $id;
        $observation = CompanyObservation::find($id);
        $this->observation = $observation->observation;
    }

    public function store(){
        $this->validate([
            'observation' => 'required',
        ]);
        CompanyObservation::create([
            'company_id' => $this->selected_id,
            'observation' => $this->observation
        ]);
        $this->resetInput();
    }

    public function destroy($id) {
        if ($id) {
            CompanyObservation::destroy($id);
            $this->observation_id = null;
            $this->observation = null;
        }
    }
}
