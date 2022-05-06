<?php

namespace App\Http\Livewire;

use App\Models\Billing;
use App\Models\Center;
use App\Models\Chore;
use App\Models\CourseStatus;
use App\Models\CourseType;
use App\Models\Profitability;
use App\Models\Registration;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Tracing;
use App\Models\TrainingAction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Course;

class CoursesUpdate extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $selected_id, $name, $training_action_id, $group, $course_type_id, $teacher_id, $nebrija, $beginning,
        $end, $morning_schedule, $afternoon_schedule, $monday, $tuesday, $wednesday, $thursday, $friday, $saturday, $sunday,
        $formation_center_id, $delivery_center_id, $outsourced, $course_observation, $reactivated, $welcome_date, $quater_date,
        $half_date, $three_quarters_date, $final_date, $course_status_id,$price;
    public $performed_activities, $performed_hours, $performed_units, $follow_up_date, $final_test, $questionnaire, $welcome_message, $quarter_message, $half_message, $three_quarters_message, $final_message, $observation;
    public $training_actions, $course_types, $teachers, $formation_centers, $delivery_centers, $course_statuses, $registrations = null, $students = null, $tracings = null, $chores = null, $route;

    public function render()
    {
        return view('livewire.courses.edit');
    }

    public function mount($id){
        $this->training_actions = TrainingAction::where('inactive', 0)->get();
        $this->course_types = CourseType::all();
        $this->teachers = Teacher::where('inactive', 0)->get();
        $this->formation_centers = Center::all();
        $this->delivery_centers = Center::all();
        $this->course_statuses = CourseStatus::all();

        $record = Course::findOrFail($id);

        $this->selected_id = $id;
        $this->name = $record-> name;
        $this->training_action_id = $record-> training_action_id;
        $this->group = $record-> group;
        $this->course_type_id = $record-> course_type_id;
        $this->teacher_id = $record-> teacher_id;
        $this->nebrija = $record-> nebrija;
        $this->beginning = $record-> beginning;
        $this->end = $record-> end;
        $this->morning_schedule = $record-> morning_schedule;
        $this->afternoon_schedule = $record-> afternoon_schedule;
        $this->monday = $record-> monday == 1 ? $record-> monday : null;
        $this->tuesday = $record-> tuesday == 1 ? $record-> tuesday : null;
        $this->wednesday = $record-> wednesday == 1 ? $record-> wednesday : null;
        $this->thursday = $record-> thursday == 1 ? $record-> thursday : null;
        $this->friday = $record-> friday == 1 ? $record-> friday : null;
        $this->saturday = $record-> saturday == 1 ? $record-> saturday : null;
        $this->sunday = $record-> sunday == 1 ? $record-> sunday : null;
        $this->formation_center_id = $record-> formation_center_id;
        $this->delivery_center_id = $record-> delivery_center_id;
        $this->outsourced = $record-> outsourced == 1 ? $record->outsourced : null;
        $this->course_observation = $record-> course_observation;
        $this->reactivated = $record-> reactivated;
        $this->welcome_date = $record-> welcome_date;
        $this->quater_date = $record-> quater_date;
        $this->half_date = $record-> half_date;
        $this->three_quarters_date = $record-> three_quarters_date;
        $this->final_date = $record-> final_date;
        $this->course_status_id = $record-> course_status_id;
        $this->price = $record->price;

        $this->route = url()->previous();
    }

    public function setName(){
        if ($this->training_action_id > 0){
            $training_action = TrainingAction::find($this->training_action_id);
            if ($this->training_action_id < 10){
                $this->name = '00'.$this->training_action_id.' - '.$training_action['name'];
            } else if ($this->training_action_id < 100){
                $this->name = '0'.$this->training_action_id.' - '.$training_action['name'];
            } else {
                $this->name = $this->training_action_id.' - '.$training_action['name'];
            }
            $num_courses = Course::where('training_action_id', $training_action['id'])->get();
            $cont = $num_courses->count();
            $cont = $cont+1;
            if ($cont < 10){
                $this->group = '000'.$cont;
            } else if ($cont < 100){
                $this->group = '00'.$cont;
            } else if ($cont < 1000){
                $this->group = '0'.$cont;
            } else {
                $this->group = $cont;
            }
            if ($this->selected_id == null){
                $this->price = $training_action['price'];
            }
        }
        if ($this->create_training_action_id > 0){
            $training_action = TrainingAction::find($this->create_training_action_id);
            if ($this->create_training_action_id < 10){
                $this->name = '00'.$this->create_training_action_id.' - '.$training_action['name'];
            } else if ($this->create_training_action_id < 100){
                $this->name = '0'.$this->create_training_action_id.' - '.$training_action['name'];
            } else {
                $this->name = $this->create_training_action_id.' - '.$training_action['name'];
            }
            $num_courses = Course::where('training_action_id', $training_action['id'])->get();
            $cont = $num_courses->count();
            $cont = $cont+1;
            if ($cont < 10){
                $this->group = '000'.$cont;
            } else if ($cont < 100){
                $this->group = '00'.$cont;
            } else if ($cont < 1000){
                $this->group = '0'.$cont;
            } else {
                $this->group = $cont;
            }
            if ($this->selected_id == null){
                $this->price = $training_action['price'];
            }
        }
    }
    public function update()
    {
        $this->validate([
            'name' => 'required',
            'training_action_id' => 'required',
            'group' => 'required',
            'course_type_id' => 'required',
            'teacher_id' => 'required',
        ]);

        $data = $this->course_data($this-> beginning, $this-> end);

        if ($this->selected_id) {
            $record = Course::find($this->selected_id);
            $record->update([
                'name' => $this-> name,
                'training_action_id' => $this-> training_action_id,
                'group' => $this-> group,
                'course_type_id' => $this-> course_type_id,
                'teacher_id' => $this-> teacher_id,
                'nebrija' => $this-> nebrija == true ? 1 : 0,
                'beginning' => $this-> beginning,
                'end' => $this-> end,
                'morning_schedule' => $this-> morning_schedule,
                'afternoon_schedule' => $this-> afternoon_schedule,
                'monday' => $this-> monday == true ? 1 : 0,
                'tuesday' => $this-> tuesday == true ? 1 : 0,
                'wednesday' => $this-> wednesday == true ? 1 : 0,
                'thursday' => $this-> thursday == true ? 1 : 0,
                'friday' => $this-> friday == true ? 1 : 0,
                'saturday' => $this-> saturday == true ? 1 : 0,
                'sunday' => $this-> sunday == true ? 1 : 0,
                'formation_center_id' => $this-> formation_center_id,
                'delivery_center_id' => $this-> delivery_center_id,
                'outsourced' => $this-> outsourced == true ? 1 : 0,
                'course_observation' => $this-> course_observation,
                'reactivated' => $this-> reactivated,
                'welcome_date' => $this-> beginning,
                'quater_date' => $data['quater'],
                'half_date' => $data['half'],
                'three_quarters_date' => $data['three_quaters'],
                'final_date' => $this-> end,
                'course_status_id' => $data['course_status_id'],
                'price' => $this-> price,
            ]);
            session()->flash('message', 'Course Successfully updated.');
            return $this->redirect($this->route);
        }
    }

    public function course_data($beginning_date, $end_date){
        $quater = null;
        $half = null;
        $three_quarters = null;
        if ($beginning_date && $end_date) {
            $dates = Course::messageDates($beginning_date, $end_date);
            $quater = $dates['quater'];
            $half = $dates['half'];
            $three_quarters = $dates['three_quaters'];
        }

        $now = Carbon::now();
        $beginning = Carbon::createFromFormat('Y-m-d', $beginning_date);
        $end = Carbon::createFromFormat('Y-m-d', $end_date);
        $course_status = CourseStatus::all();
        if ($beginning->gt($now)){
            $course_status_id = $course_status->firstWhere('name', 'PENDIENTE')['id'];
        } else{
            $course_status_id = $course_status->firstWhere('name', 'IMPARTICIÓN')['id'];
        }
        if ($now->gt($end)){
            $course_status_id = $course_status->firstWhere('name', 'FINALIZADO')['id'];
        }
        return [
            'quater' => $quater,
            'half' => $half,
            'three_quaters' => $three_quarters,
            'course_status_id' => $course_status_id
        ];
    }
}
