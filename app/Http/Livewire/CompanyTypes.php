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

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        $companyTypes = CompanyType::
        orWhere('name', 'LIKE', $keyWord)
            ->paginate(10);
        foreach ($companyTypes as $companyType){
            $company = Company::where('company_type_id', $companyType['id'])->first();
            if ($company){
                $companyType['used'] = true;
            } else {
                $companyType['used'] = false;
            }
        }
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

        CompanyType::create([
			'name' => $this-> name
        ]);

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
			$record = CompanyType::find($this->selected_id);
            $record->update([
			'name' => $this-> name
            ]);

            $this->resetInput();
            $this->updateMode = false;
			session()->flash('message', 'Tipo actualizado con exito.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = CompanyType::where('id', $id);
            $record->delete();
        }
    }
}
