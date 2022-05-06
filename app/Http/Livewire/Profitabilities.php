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

        $profitabilities = Profitability::select('profitabilities.*', 'companies.name as company_name', 'courses.name as course_name',
            'students.name as student_name', 'students.surname as student_surname')
            ->leftjoin('companies', 'companies.id', '=', 'profitabilities.company_id')
            ->leftjoin('courses', 'courses.id', '=', 'profitabilities.course_id')
            ->leftjoin('students', 'students.id', '=', 'profitabilities.student_id')
            ->orWhere('courses.name', 'LIKE', $keyWord)
            ->orWhere('companies.name', 'LIKE', $keyWord)
            ->orWhere('students.name', 'LIKE', $keyWord)
            ->orWhere('students.surname', 'LIKE', $keyWord)
            ->orWhere('profitabilities.price', 'LIKE', $keyWord)
            ->orWhere('license', 'LIKE', $keyWord)
            ->orWhere('teacher', 'LIKE', $keyWord)
            ->orWhere('management', 'LIKE', $keyWord)
            ->orWhere('nebrija_title', 'LIKE', $keyWord)
            ->orWhere('discount', 'LIKE', $keyWord)
            ->orWhere('collaborator_commission', 'LIKE', $keyWord)
            ->orWhere('advisor_commission', 'LIKE', $keyWord)
            ->orWhere('total', 'LIKE', $keyWord)
            ->orWhere('benefits', 'LIKE', $keyWord)
            ->orWhere('observations', 'LIKE', $keyWord)
            ->paginate(10);

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

    public function store()
    {
        $this->validate([
		'course_id' => 'required',
		'company_id' => 'required',
		'student_id' => 'required',
		'price' => 'required',
		'license' => 'required',
		'teacher' => 'required',
		'management' => 'required',
		'nebrija_title' => 'required',
		'discount' => 'required',
		'collaborator_commission' => 'required',
		'advisor_commission' => 'required',
		'total' => 'required',
		'benefits' => 'required',
		'observations' => 'required',
        ]);

        Profitability::create([
			'course_id' => $this-> course_id,
			'company_id' => $this-> company_id,
			'student_id' => $this-> student_id,
			'price' => $this-> price,
			'license' => $this-> license,
			'teacher' => $this-> teacher,
			'management' => $this-> management,
			'nebrija_title' => $this-> nebrija_title,
			'discount' => $this-> discount,
			'collaborator_commission' => $this-> collaborator_commission,
			'advisor_commission' => $this-> advisor_commission,
			'total' => $this-> total,
			'benefits' => $this-> benefits,
			'observations' => $this-> observations
        ]);

        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Profitability Successfully created.');
    }

    public function edit($id)
    {
        $record = Profitability::findOrFail($id);

        $this->selected_id = $id;
		$this->course_id = $record-> course_id;
		$this->company_id = $record-> company_id;
		$this->student_id = $record-> student_id;
		$this->price = $record-> price;
		$this->license = $record-> license;
		$this->teacher = $record-> teacher;
		$this->management = $record-> management;
		$this->nebrija_title = $record-> nebrija_title;
		$this->discount = $record-> discount;
		$this->collaborator_commission = $record-> collaborator_commission;
		$this->advisor_commission = $record-> advisor_commission;
		$this->total = $record-> total;
		$this->benefits = $record-> benefits;
		$this->observations = $record-> observations;

        $this->updateMode = true;
    }

    public function update()
    {

        if ($this->selected_id) {
			$record = Profitability::find($this->selected_id);
            $record->update([
			'price' => $this-> price,
			'license' => $this-> license,
			'teacher' => $this-> teacher,
			'management' => $this-> management,
			'nebrija_title' => $this-> nebrija_title,
			'discount' => $this-> discount,
			'collaborator_commission' => $this-> collaborator_commission,
			'advisor_commission' => $this-> advisor_commission,
			'total' => $this-> total,
			'benefits' => $this-> benefits,
			'observations' => $this-> observations
            ]);

            $this->resetInput();
            $this->updateMode = false;
			session()->flash('message', 'Rentabilidad actualizado con exito.');
        }
    }
}
