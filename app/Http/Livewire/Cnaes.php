<?php

namespace App\Http\Livewire;

use App\Models\Company;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Cnae;

class Cnaes extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $name;
    public $updateMode = false;
    public function render()
    {

		$keyWord = '%'.$this->keyWord .'%';
        $cnaes = Cnae::orWhere('name', 'LIKE', $keyWord)
            ->paginate(10);

        foreach ($cnaes as $cnae){
            $company = Company::where('cnae_id', $cnae['id'])->first();
            if ($company){
                $company['used'] = true;
            } else{
                $company['used'] = false;
            }
        }
        return view('livewire.cnaes.view', [
            'cnaes' => $cnaes,
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

        Cnae::create([
			'name' => $this-> name
        ]);

        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Cnae creado con exito.');
    }

    public function edit($id)
    {
        $record = Cnae::findOrFail($id);

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
			$record = Cnae::find($this->selected_id);
            $record->update([
			'name' => $this-> name
            ]);

            $this->resetInput();
            $this->updateMode = false;
			session()->flash('message', 'Cnae editado con exito.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = Cnae::where('id', $id);
            $record->delete();
        }
    }
}
