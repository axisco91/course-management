<?php

namespace App\Http\Livewire;

use App\Models\Company;
use App\Models\Course;
use App\Models\Student;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Profitability;

class Profitabilities extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $course_id, $company_id, $student_id, $price, $license, $teacher, $management, $nebrija_title, $discount, $collaborator_commission, $advisor_commission, $total, $benefits, $observations;
    public $updateMode = false;
    public $courses, $companies, $students;

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';

       $profitabilities = Profitability::getProfitabilities($keyWord);
        return view('livewire.profitabilities.view', [
            'profitabilities' => $profitabilities,
        ]);
    }

    public function mount(){
        $this->courses = Course::all();
        $this->companies = Company::all();
        $this->students = Student::all();
    }

    public function cancel()
    {
        $this->resetInput();
        $this->updateMode = false;
    }

    private function resetInput()
    {
		$this->course_id = null;
		$this->company_id = null;
		$this->student_id = null;
		$this->price = null;
		$this->license = null;
		$this->teacher = null;
		$this->management = null;
		$this->nebrija_title = null;
		$this->discount = null;
		$this->collaborator_commission = null;
		$this->advisor_commission = null;
		$this->total = null;
		$this->benefits = null;
		$this->observations = null;
    }
}
