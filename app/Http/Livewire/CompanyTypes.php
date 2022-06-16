<?php

namespace App\Http\Livewire;

use App\Models\Company;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\CompanyType;

class CompanyTypes extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $name;
    public $updateMode = false;
    protected $listeners = [
        'destroy' => 'destroy'
    ];

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
       $companyTypes = CompanyType::getCompanyTypes($keyWord);
        return view('livewire.company-types.view', [
            'companyTypes' => $companyTypes,
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

        CompanyType::createCompanyType($data);

        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Tipo creado con exito.');
    }

    public function edit($id)
    {
        $record = CompanyType::findOrFail($id);

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
            CompanyType::updateCompanyType($this->selected_id, $data);
            $this->resetInput();
            $this->updateMode = false;
			session()->flash('message', 'Tipo actualizado con exito.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $value = CompanyType::destroy($id);
            $this->dispatchBrowserEvent('eliminated', ['value' => $value]);
        }
    }
}
