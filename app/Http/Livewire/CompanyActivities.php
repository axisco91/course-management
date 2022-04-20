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
        $companyActivities = CompanyActivity::
        orWhere('name', 'LIKE', $keyWord)
            ->paginate(10);
        foreach ($companyActivities as $companyActivity) {
            $company = Company::where('company_activity_id', $companyActivity['id'])->first();
            if ($company) {
                $companyActivity['used'] = true;
            } else {
                $companyActivity['used'] = false;
            }
        }

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

        CompanyActivity::create([
			'name' => $this-> name
        ]);

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
			$record = CompanyActivity::find($this->selected_id);
            $record->update([
			'name' => $this-> name
            ]);

            $this->resetInput();
            $this->updateMode = false;
			session()->flash('message', 'Actividad editado con exito.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = CompanyActivity::where('id', $id);
            $record->delete();
        }
    }
}
