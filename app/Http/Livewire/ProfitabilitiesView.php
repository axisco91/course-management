<?php

namespace App\Http\Livewire;

use App\Models\Company;
use App\Models\Course;
use App\Models\Student;
use Livewire\Component;
use App\Models\Profitability;

class ProfitabilitiesView extends Component
{
    public $selected_id, $keyWord, $course_id, $company_id, $student_id, $price, $license, $teacher, $management, $nebrija_title, $discount, $collaborator_commission, $advisor_commission, $total, $benefits, $observations, $advisor_percentage, $collaborator_percentage;
    public $courses, $companies, $students;

    public function render()
    {
        return view('livewire.profitabilities.view');
    }

    public function mount($id){
        $this->courses = Course::all();
        $this->companies = Company::all();
        $this->students = Student::all();

        // Obtain student
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
        $this->advisor_percentage = $record->advisor_percentage;
        $this->collaborator_percentage = $record->collaborator_percentage;
    }
}
