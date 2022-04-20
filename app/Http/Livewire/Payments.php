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

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        $payments = Payment::latest()
            ->orWhere('name', 'LIKE', $keyWord)
            ->paginate(10);
        foreach ($payments as $payment){
            $billing = Billing::where('payment_id', $payment['id'])->first();
            if ($billing){
                $payment['used'] = true;
            } else{
                $payment['used'] = false;
            }
        }
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

        Payment::create([
			'name' => $this-> name
        ]);

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
			$record = Payment::find($this->selected_id);
            $record->update([
			'name' => $this-> name
            ]);

            $this->resetInput();
            $this->updateMode = false;
			session()->flash('message', 'Payment Successfully updated.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = Payment::where('id', $id);
            $record->delete();
        }
    }
}
