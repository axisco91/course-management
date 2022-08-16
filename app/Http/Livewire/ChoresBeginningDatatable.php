<?php

namespace App\Http\Livewire;

use App\Models\Chore;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class ChoresBeginningDatatable extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $sortBy = 'courses.beginning';
    public $sortDitection = 'asc';

    public function render() {
        $chores = Chore::getChoresSendWelcome($this->sortBy, $this->sortDitection);

        return view('livewire.home.total-courses', [
            'chores' => $chores
        ]);
    }

    public function sortBy($field){
        if ($this->sortDitection == 'asc') {
            $this->sortDitection = 'desc';
        } else {
            $this->sortDitection = 'asc';
        }
        return $this->sortBy = $field;
    }
}
