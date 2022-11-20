<?php

namespace App\Http\Livewire;

use App\Models\TrainingContractIncidence;
use Livewire\Component;
use Livewire\WithPagination;

class TrainingContractIncidences extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord;
    public $search_company_id, $search_student_id, $search_training_contract_status_id;
    public $updateMode = false;
    protected $listeners = [
        'destroy' => 'destroy',
    ];

    public function render()
    {
        $keyWord = '%'.$this->keyWord .'%';
        $training_contract_incidences = TrainingContractIncidence::getTrainingContractIncidences($keyWord, $this->selected_id);
        return view('livewire.training-contract-incidences.list', [
            'training_contract_incidences' => $training_contract_incidences
        ]);
    }

    public function mount($training_contract_id){
        $this->selected_id = $training_contract_id;
    }

    public function destroy($id)
    {
        if ($id) {
            $value = TrainingContractIncidence::destroy($id);
            $this->dispatchBrowserEvent('eliminated', ['value' => $value]);
        }
    }
}
