<?php

namespace App\Http\Livewire;

use App\Models\Billing;
use App\Models\Chore;
use App\Models\Course;
use App\Models\CourseType;
use App\Models\Profitability;
use App\Models\Student;
use App\Models\Tracing;
use App\Models\TrainingAction;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Registration;

class Registrations extends Component
{
    use WithPagination;

    protected $listeners = ['courseRegistrations'];
	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $course_id, $company_id, $student_id, $tracing_id, $chore_id, $price, $search_name_unregisterd,
        $search_surname_unregisterd, $search_name, $search_surname, $is_bonus, $is_bonus_actual, $price_actual, $name, $surname;
    public $updateMode = false;

    public function render()
    {

        $registrations = null;
        $students = null;

        $search_name_unregisterd = '%'.$this->search_name_unregisterd.'%';
        $search_surname_unregisterd = '%'.$this->search_surname_unregisterd.'%';
        $search_name = '%'.$this->search_name.'%';
        $search_surname = '%'.$this->search_surname.'%';

        if ($this->selected_id) {
            $this->registrations = Registration::getRegistrated($this->selected_id, $search_name, $search_surname);

            $this->students = Registration::getUnregistrated($this->selected_id, $search_name_unregisterd, $search_surname_unregisterd);
        }

        return view('livewire.registrations.view');
    }

    public function mount(){
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
		$this->tracing_id = null;
		$this->chore_id = null;
		$this->price = null;
        $this->search_name_unregisterd = null;
        $this->search_surname_unregisterd = null;
        $this->search_name = null;
        $this->search_surname = null;
        $this->is_bonus = null;
        $this->name = null;
        $this->surname = null;
    }

    public function register(){
        if ($this->student_id){
            $student = Student::findOrFail($this->student_id);
            $tracing_data = [
                'course_id' => $this->selected_id,
                'company_id' => $student['company_id'],
                'student_id' => $student['id'],
            ];
            $tracing = Tracing::createTracing($tracing_data);

            if ($tracing) {
                $chore_data = [
                    'course_id' => $this->selected_id,
                    'company_id' => $student['company_id'],
                    'student_id' => $student['id']
                ];
                $chore = Chore::createChore($chore_data);
                if ($chore){

                    $profitability_data =[
                        'course_id' =>$this->selected_id,
                        'company_id' => $student['company_id'],
                        'student_id' => $student['id'],
                        'price' => $this->price,
                        'total' => $this->price,
                    ];
                    $profitability = Profitability::createProfitability($profitability_data);
                    $registration_data = [
                        'course_id' => $this->selected_id,
                        'company_id' => $student['company_id'],
                        'student_id' => $student['id'],
                        'tracing_id' => $tracing['id'],
                        'chore_id' => $chore['id'],
                        'price' => $this->price,
                        'profitability_id' => $profitability['id'],
                        'is_bonus' => $this->is_bonus
                    ];
                    $registration = Registration::createRegistration($registration_data);
                }
                $billing_data = [
                    'course_id' => $this->selected_id,
                    'company_id' => $student['company_id'],
                    'is_bonus' => $this->is_bonus,
                    'price' => $this->price,

                ];
                $billing = Billing::updateBillingRegistrations($billing_data);
            }
        }
        $this->student_id = null;
        $this->price = $this->price_actual;
        $this->is_bonus = $this->is_bonus_actual;
        $this->name = null;
        $this->surname = null;
    }

    public function edit($id)
    {
		$this->student_id = $id;
        $student = Student::findOrFail($this->student_id);
        $this->name = $student['name'];
        $this->surname = $student['surname'];

        $this->updateMode = true;
    }

    public function unregister($id){
        if ($id){
            Registration::unregistration($id);
        }
    }

    public function courseRegistrations($id){
        $this->selected_id = $id;
        $course = Course::find($id);
        $course_type = CourseType::where('name', 'Bonificado')->first();
        $training_action = TrainingAction::findOrFail($course['training_action_id']);
        $this->is_bonus = 0;
        if ($course['course_type_id'] == $course_type['id']){
            $this->is_bonus = 1;
        }
        if ($course->price){
            $this->price = $course->price;
        } else if($training_action->price) {
            $this->price = $training_action->price;
        } else{
            $this->price = 0.00;
        }
        $this->is_bonus_actual = $this->is_bonus;
        $this->price_actual = $this->price;
    }
}
