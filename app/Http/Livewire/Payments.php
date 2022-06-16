<?php

namespace App\Http\Livewire;

use App\Models\Billing;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Payment;

class Payments extends Component
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
        $payments = Payment::getPayments($keyWord);
        return view('livewire.payments.view', [
            'payments' => $payments,
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
        Payment::createPayment($data);
        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Payment Successfully created.');
    }

    public function edit($id)
    {
        $record = Payment::findOrFail($id);

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
            Payment::updatePayment($data);

            $this->resetInput();
            $this->updateMode = false;
			session()->flash('message', 'Payment Successfully updated.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $value = Payment::destroy($id);
            $this->dispatchBrowserEvent('eliminated', ['value' => $value]);
        }
    }
}
