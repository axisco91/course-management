<?php

namespace App\Http\Livewire;

use App\Models\Company;
use App\Models\Course;
use App\Models\Student;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Profitability;

class ProfitabilitiesUpdate extends Component
{
    public $selected_id, $keyWord, $course_id, $company_id, $student_id, $price, $license, $teacher, $management, $nebrija_title, $discount, $collaborator_commission, $advisor_commission, $total, $benefits, $observations;
    public $updateMode = false;
    public $courses, $companies, $students;
    public $route;

    public function render()
    {

        return view('livewire.profitabilities.update');
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

        $this->route = url()->previous();
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

    public function update()
    {

        if ($this->selected_id) {
            $data = [
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
            ];
            Profitability::updateProfitability($this->selected_id, $data);
            $this->resetInput();
            $this->updateMode = false;
            session()->flash('message', 'Rentabilidad actualizado con exito.');
            return redirect($this->route);
        }
    }
}
