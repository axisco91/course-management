<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\QuoteGroup;

class Quotegroups extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $name;
    public $updateMode = false;

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        $quote_groups = QuoteGroup::getQuoteGroups($keyWord);
        return view('livewire.quote_groups.view', [
            'quote_groups' => $quote_groups,
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
        QuoteGroup::createQuoteGroup($data);
        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Grupo Cotización creado con exito.');
        $this->emit('toastr', 'success');
    }

    public function edit($id)
    {
        $record = QuoteGroup::findOrFail($id);

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
            QuoteGroup::updateQuoteGroup($this->selected_id, $data);

            $this->resetInput();
            $this->emit('closeUpdateModal');
            $this->updateMode = false;
			session()->flash('message', 'Grupo Cotización actualizado con exito.');
            $this->emit('toastr', 'success');
        }
    }
}
