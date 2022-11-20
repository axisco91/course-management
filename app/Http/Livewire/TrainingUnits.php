<?php

namespace App\Http\Livewire;

use App\Exports\TrainingUnitsExport;
use App\Models\ActionType;
use App\Models\Course;
use App\Models\CourseOrigin;
use App\Models\Modality;
use App\Models\Module;
use App\Models\ProfessionalArea;
use App\Models\ProfessionalFamily;
use App\Models\Provider;
use App\Models\TrainingActionGroup;
use App\Models\TrainingActionLevel;
use App\Models\TrainingUnit;
use App\Models\TrainingUnitsModule;
use App\Models\Tutoring;
use App\Models\WebPlatform;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\TrainingAction;

class TrainingUnits extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $formative_unit, $name, $total_hours, $face_to_face_hours, $exam_hours, $teletraining_hours, $tutoring_hours, $active, $inactiveFilter;
    public $updateMode = false;
    public $tab = 'info';
    protected $listeners = [
        'changeState' => 'changeState',
        'destroy' => 'destroy'
    ];

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        $training_units = TrainingUnit::getTrainingUnits($keyWord, $this->inactiveFilter);

        foreach ($training_units as $training_unit) {
            $used = TrainingUnitsModule::where('training_unit_id', $training_unit->id)->first();
            if ($used) {
                $training_unit['used'] = true;
            } else {
                $training_unit['used'] = false;
            }
        }

        return view('livewire.training-units.list', [
            'training_units' => $training_units,
        ]);
    }

    public function cancel()
    {
        $this->resetInput();
        $this->updateMode = false;
    }

    public function mount(){

    }

    private function resetInput()
    {
        $this->name = null;
        $this->total_hours = null;
        $this->active = null;
        $this->face_to_face_hours = null;
        $this->exam_hours = null;
        $this->teletraining_hours = null;
        $this->tutoring_hours = null;
        $this->formative_unit = null;
    }

    public function changeState($id){
        $trainingUnit = TrainingUnit::find($id);
        if ($trainingUnit->active == 0){
            $trainingUnit->update([
                'active' => 1
            ]);
            session()->flash('message', 'Unidad formativa activado con exito.');
            $value = 'activated';
        } else {
            $trainingUnit->update([
                'active' => 0
            ]);
            session()->flash('message', 'Unidad formativa desactivado con exito.');
            $value = 'desactivated';
        }
        $this->dispatchBrowserEvent('status-update', ['value' => $value]);
    }
    public function general($id)
    {
        $this->emit('getModuleInfo', $id);
        $training_unit = TrainingUnit::find($id);
        $this->name = $training_unit->name;
        $this->active = $training_unit->active;
        $this->selected_id = $id;
        $this->total_hours = $training_unit->total_hours;
        $this->face_to_face_hours = $training_unit->face_to_face_hours;
        $this->exam_hours = $training_unit->exam_hours;
        $this->teletraining_hours = $training_unit->teletraining_hours;
        $this->tutoring_hours = $training_unit->tutoring_hours;
        $this->formative_unit = $training_unit->formative_unit;
    }

    public function getInfo($id){
        $this->emit('getTrainingUnitInfo', $id);
        $training_unit = TrainingUnit::find($id);
        $this->formative_unit = $training_unit->formative_unit;
        $this->name = $training_unit->name;
        $this->selected_id = $id;
    }

    public function editUnit($id){
        $this->emit('editTrainingUnit', $id);
    }

     public function downloadExcel(){
         $this->excelModal = false;
         return (new TrainingUnitsExport($this->search_formative_units, $this->search_name, $this->search_professional_family_id,  $this->search_professional_area_id, $this->search_modality_id, $this->search_provider_id, $this->inactiveFilter))->download('unidades_formativas.xlsx');
     }

    public function destroy($id){
        if ($id) {
            TrainingUnit::destroy($id);
            return 1;
        }
    }
}
