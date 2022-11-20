<?php

namespace App\Http\Livewire;

use App\Exports\ProfitabilitiesExport;
use App\Models\Company;
use App\Models\Course;
use App\Models\CourseStatus;
use App\Models\Student;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Profitability;

class Profitabilities extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $course_id, $company_id, $student_id, $price, $license, $teacher, $management, $nebrija_title, $discount, $collaborator_commission, $advisor_commission, $total, $benefits, $observations, $advisor_percentage, $collaborator_percentage;
    public $updateMode = false;
    public $courses, $companies, $students, $tab = 'info', $course_statuses, $group, $company_name, $beginning, $end;
    public $course_search = -1, $company_search = -1, $student_search = -1, $student_name, $course_name, $status_search = -1;

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';

       $profitabilities = Profitability::getProfitabilities($keyWord, $this->course_search, $this->company_search, $this->student_search, $this->status_search);
        return view('livewire.profitabilities.list', [
            'profitabilities' => $profitabilities,
        ]);
    }

    public function mount(){
        $this->courses = Course::all();
        $this->companies = Company::all();
        $this->students = Student::all();
        $this->course_statuses = CourseStatus::all();
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
        $this->advisor_percentage = null;
        $this->collaborator_percentage = null;
    }

    public function general($id){
       if ($id){
           $record = Profitability::find($id);

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
           $course = Course::find($this->course_id);
           $this->group = $course->group;
           $this->course_name = $course->name;
           $company = Company::find($record->company_id);
           $this->advisor_percentage = $record->advisor_percentage;
           $this->collaborator_percentage = $record->collaborator_percentage;

           $this->company_name = $company->name;
           $this->beginning = Carbon::parse($course->beginning)->format('d/m/Y');
           $this->end = Carbon::parse($course->end)->format('d/m/Y');
       }
    }

    public function downloadExcel(){
        return (new ProfitabilitiesExport($this->course_search, $this->company_search, $this->student_search))->download('rentabilidad.xlsx');
    }
}
