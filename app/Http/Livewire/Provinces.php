<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Province;

class Provinces extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $name;
    public $updateMode = false;

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        $provinces = Province::getProvinces($keyWord);
        return view('livewire.provinces.view', [
            'provinces' => $provinces,
        ]);
    }

    public function cancel()
    {
        $this->resetInput();
        $this->updateMode = false;
    }

    private function resetInput()
    {
		$this->name = null;
    }

    public function store()
    {
        $this->validate([
		'name' => 'required',
        ]);

        $data = [
			'name' => $this-> name
        ];
        Province::createProvince($data);
        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Province Successfully created.');
    }

    public function edit($id)
    {
        $record = Province::findOrFail($id);

        $this->selected_id = $id;
		$this->name = $record-> name;

        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
		'name' => 'required',
        ]);

        if ($this->selected_id) {
            $data = [
                'name' => $this-> name
            ];
            Province::updateProvince($this->selected_id, $data);

            $this->resetInput();
            $this->updateMode = false;
			session()->flash('message', 'Province Successfully updated.');
        }
    }
}
