<?php

namespace App\Http\Livewire;

use App\Models\ExcludedDay;
use Livewire\Component;
use Livewire\WithPagination;

class ExcludedDays extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $day;
    public $updateMode = false;
    protected $listeners = [
        'destroy' => 'destroy'
    ];
    public function render()
    {

		$keyWord = '%'.$this->keyWord .'%';
       $excluded_days = ExcludedDay::getExcludedDays($keyWord);
        return view('livewire.excluded-days.view', [
            'excluded_days' => $excluded_days,
        ]);
    }

    public function cancel()
    {
        $this->resetInput();
        $this->updateMode = false;
    }

    private function resetInput()
    {
		$this->day = null;
    }

    public function store()
    {
        $this->validate([
		'day' => 'required',
        ]);

        $data = [
            'day' => $this-> day
        ];

        ExcludedDay::createExcludedDay($data);

        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Dia Excluido creado con exito.');
        $this->emit('toastr', 'success');
    }

    public function edit($id)
    {
        $record = ExcludedDay::findOrFail($id);

        $this->selected_id = $id;
		$this->day = $record-> day;

        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
		'day' => 'required',
        ]);

        if ($this->selected_id) {
            $data = [
                'name' => $this-> day
            ];
            ExcludedDay::updateExcludedDay($this->selected_id, $data);

            $this->resetInput();
            $this->emit('closeUpdateModal');
            $this->updateMode = false;
			session()->flash('message', 'Dia excluido editado con exito.');
            $this->emit('toastr', 'success');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $value = ExcludedDay
                ::destroy($id);


            $this->dispatchBrowserEvent('eliminated', ['value' => $value]);
        }
    }
}
