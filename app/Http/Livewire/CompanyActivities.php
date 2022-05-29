<?php

namespace App\Http\Livewire;

use App\Models\Company;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\CompanyActivity;

class CompanyActivities extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $name;
    public $updateMode = false;

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        $companyActivities = CompanyActivity::getCompanyActivities($keyWord);

        return view('livewire.company-activities.view', [
            'companyActivities' => $companyActivities,
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

        CompanyActivity::createCompanyActivity($data);

        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Actividad creado con exito.');
    }

    public function edit($id)
    {
        $record = CompanyActivity::findOrFail($id);

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

            CompanyActivity::updateCompanyActivity($this->selected_id, $data);

            $this->resetInput();
            $this->updateMode = false;
			session()->flash('message', 'Actividad editado con exito.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            CompanyActivity::destroy($id);
        }
    }
}
