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

class Courses extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $name, $training_action_id, $group, $course_type_id, $teacher_id, $nebrija, $beginning,
        $end, $morning_schedule, $afternoon_schedule, $monday, $tuesday, $wednesday, $thursday, $friday, $saturday, $sunday,
        $formation_center_id, $delivery_center_id, $outsourced, $course_observation, $reactivated, $welcome_date, $quater_date,
        $half_date, $three_quarters_date, $final_date, $course_status_id,$price;
    public $create_training_action_id, $create_course_type_id, $create_teacher_id, $create_formation_center_id, $create_delivery_center_id;
    public $performed_activities, $performed_hours, $performed_units, $follow_up_date, $final_test, $questionnaire, $welcome_message, $quarter_message, $half_message, $three_quarters_message, $final_message, $observation;
    public $updateMode = false, $updateTracingMode = false;
    public $training_actions, $course_types, $teachers, $formation_centers, $delivery_centers, $course_statuses, $registrations = null, $students = null, $tracings = null, $chores = null;

    public function render()
    {
        if ($this->selected_id){
            $this->registrations = Student::leftJoin('registrations', 'students.id', '=', 'registrations.student_id')
                ->where('registrations.course_id', $this->selected_id)->get();
            $this->students = Student::all();
            $this->students = $this->students->whereNotIn('id', $this->registrations->pluck('student_id'));
        }

		$keyWord = '%'.$this->keyWord .'%';
        return view('livewire.courses.view', [
            'courses' => Course::latest()
                        ->select('courses.*',
                            'course_types.name as course_type', 'teachers.name as teacher',
                            'fc.name as formation_center',
                            'dc.name as delivery_center',
                            'course_statuses.name as course_status')
                        ->leftjoin('course_types', 'course_types.id', '=', 'courses.course_type_id')
                        ->leftjoin('teachers', 'teachers.id', '=', 'courses.teacher_id')
                        ->leftjoin('centers as fc', 'fc.id', '=', 'courses.formation_center_id')
                        ->leftjoin('centers as dc', 'dc.id', '=', 'courses.delivery_center_id')
                        ->leftjoin('course_statuses', 'course_statuses.id', '=', 'courses.course_status_id')
						->orWhere('courses.name', 'LIKE', $keyWord)
						->orWhere('group', 'LIKE', $keyWord)
						->orWhere('course_types.name', 'LIKE', $keyWord)
						->orWhere('teachers.name', 'LIKE', $keyWord)
						->orWhere('nebrija', 'LIKE', $keyWord)
						->orWhere('beginning', 'LIKE', $keyWord)
						->orWhere('end', 'LIKE', $keyWord)
						->orWhere('morning_schedule', 'LIKE', $keyWord)
						->orWhere('afternoon_schedule', 'LIKE', $keyWord)
						->orWhere('monday', 'LIKE', $keyWord)
						->orWhere('tuesday', 'LIKE', $keyWord)
						->orWhere('wednesday', 'LIKE', $keyWord)
						->orWhere('thursday', 'LIKE', $keyWord)
						->orWhere('friday', 'LIKE', $keyWord)
						->orWhere('saturday', 'LIKE', $keyWord)
						->orWhere('sunday', 'LIKE', $keyWord)
						->orWhere('fc.name', 'LIKE', $keyWord)
						->orWhere('dc.name', 'LIKE', $keyWord)
						->orWhere('outsourced', 'LIKE', $keyWord)
						->orWhere('course_observation', 'LIKE', $keyWord)
						->orWhere('reactivated', 'LIKE', $keyWord)
						->orWhere('welcome_date', 'LIKE', $keyWord)
						->orWhere('quater_date', 'LIKE', $keyWord)
						->orWhere('half_date', 'LIKE', $keyWord)
						->orWhere('three_quarters_date', 'LIKE', $keyWord)
						->orWhere('final_date', 'LIKE', $keyWord)
						->orWhere('course_statuses.name', 'LIKE', $keyWord)
						->paginate(10),
        ]);
    }

    public function cancel()
    {
        $this->resetInput();
        $this->updateMode = false;
    }

    public function mount(){
        $this->training_actions = TrainingAction::all();
        $this->course_types = CourseType::all();
        $this->teachers = Teacher::all();
        $this->formation_centers = Center::all();
        $this->delivery_centers = Center::all();
        $this->course_statuses = CourseStatus::all();
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

    private function resetInput()
    {
		$this->name = null;
		$this->training_action_id = null;
		$this->group = null;
		$this->course_type_id = null;
		$this->teacher_id = null;
		$this->nebrija = null;
		$this->beginning = null;
		$this->end = null;
		$this->morning_schedule = null;
		$this->afternoon_schedule = null;
		$this->monday = null;
		$this->tuesday = null;
		$this->wednesday = null;
		$this->thursday = null;
		$this->friday = null;
		$this->saturday = null;
		$this->sunday = null;
		$this->formation_center_id = null;
		$this->delivery_center_id = null;
		$this->outsourced = null;
		$this->course_observation = null;
		$this->reactivated = null;
		$this->welcome_date = null;
		$this->quater_date = null;
		$this->half_date = null;
		$this->three_quarters_date = null;
		$this->course_status_id = null;
        $this->registrations = null;
        $this->students = null;
        $this->tracings = null;
        $this->chores = null;
        $this->performed_activities = null;
        $this->performed_hours = null;
        $this->performed_units = null;
        $this->follow_up_date = null;
        $this->final_date = null;
        $this->questionnaire = null;
        $this->welcome_message = null;
        $this->quarter_message = null;
        $this->half_message = null;
        $this->three_quarters_message = null;
        $this->final_message = null;
        $this->observation = null;
        $this->price = null;
        $this->create_training_action_id = null;
        $this->create_course_type_id = null;
        $this->create_delivery_center_id = null;
        $this->create_formation_center_id = null;
        $this->create_teacher_id = null;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required',
            'create_training_action_id' => 'required',
            'group' => 'required',
            'create_course_type_id' => 'required',
            'create_teacher_id' => 'required',
        ]);

        $data = $this->course_data($this-> beginning, $this-> end);

        Course::create([
			'name' => $this-> name,
			'training_action_id' => $this-> create_training_action_id,
			'group' => $this-> group,
			'course_type_id' => $this-> create_course_type_id,
			'teacher_id' => $this-> create_teacher_id,
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
			'formation_center_id' => $this-> create_formation_center_id,
			'delivery_center_id' => $this-> create_delivery_center_id,
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

        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Course Successfully created.');
    }

    public function edit($id)
    {
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
		$this->outsourced = $record-> outsourced;
		$this->course_observation = $record-> course_observation;
		$this->reactivated = $record-> reactivated;
		$this->welcome_date = $record-> welcome_date;
		$this->quater_date = $record-> quater_date;
		$this->half_date = $record-> half_date;
		$this->three_quarters_date = $record-> three_quarters_date;
		$this->final_date = $record-> final_date;
		$this->course_status_id = $record-> course_status_id;
        $this->price = $record->price;

        $this->updateMode = true;
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

            $this->resetInput();
            $this->updateMode = false;
			session()->flash('message', 'Course Successfully updated.');
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
            $course_status_id = $course_status->firstWhere('name', 'Pendiente')['id'];
        } else{
            $course_status_id = $course_status->firstWhere('name', 'Impartición')['id'];
        }
        if ($now->gt($end)){
            $course_status_id = $course_status->firstWhere('name', 'Finalizado')['id'];
        }
        return [
            'quater' => $quater,
            'half' => $half,
            'three_quaters' => $three_quarters,
            'course_status_id' => $course_status_id
        ];
    }

    public function registrations($id) {
        if ($id) {
            $this->selected_id = $id;
            $this->registrations = Student::leftJoin('registrations', 'students.id', '=', 'registrations.student_id')
                ->where('registrations.course_id', $this->selected_id)->get();
            $this->students = Student::all();
            $this->students = $this->students->whereNotIn('id', $this->registrations->pluck('student_id'));
        }
    }

    public function register($id){
        if ($id){
            $course = Course::findOrFail($this->selected_id);
            $student = Student::findOrFail($id);
            $training_action = TrainingAction::findOrFail($course['training_action_id']);

            $tracing = Tracing::create([
                'course_id' => $course['id'],
                'company_id' => $student['company_id'],
                'student_id' => $student['id'],
            ]);

            if ($tracing) {
                $chore = Chore::create([
                    'course_id' => $course['id'],
                    'company_id' => $student['company_id'],
                    'student_id' => $student['id']
                ]);

                if ($chore){
                    $price = 0.00;
                    if ($course['price']){
                        $price = $course['price'];
                    } else if ($training_action['price']){
                        $price = $training_action['price'];
                    }

                    $profitability = Profitability::create([
                        'course_id' => $course['id'],
                        'company_id' => $student['company_id'],
                        'student_id' => $student['id'],
                        'price' => $price,
                    ]);

                    $registration = Registration::create([
                        'course_id' => $course['id'],
                        'company_id' => $student['company_id'],
                        'student_id' => $student['id'],
                        'tracing_id' => $tracing['id'],
                        'chore_id' => $chore['id'],
                        'price' => $price,
                        'profitability_id' => $profitability['id'],
                    ]);

                }
                $billing = Billing::where('course_id', $course['id'])
                    ->where('company_id', $student['company_id'])->first();
                if ($billing){
                    $billing->update([
                        'number_students' => $billing['number_students']+1,
                        'billing' => $price+ $billing['billing'],
                    ]);
                } else {
                    $course_type = CourseType::where('name', 'Bonificado')->first();
                    $type = 0;
                    if ($course['course_type_id'] == $course_type['id']){
                        $type = 1;
                    }
                    Billing::create([
                        'course_id' => $course['id'],
                        'company_id' => $student['company_id'],
                        'number_students' => 1,
                        'is_bonus' => $type,
                        'billing' => $price,
                    ]);
                }
            }
        }
    }

    public function unregister($id){
        if ($id){
            $registration = Registration::where('course_id', $this->selected_id)
                ->where('student_id', $id)->first();
            if ($registration) {
                $billing = Billing::where('course_id',$registration['course_id'])
                    ->where('company_id', $registration['company_id'])->first();
                if ($billing){
                    if ($billing['number_students']-1 == 0) {
                        $billing->delete();
                    } else {
                        $billing->update([
                            'number_students' => $billing['number_students']-1,
                            'billing' => $billing['billing'] - $registration['price']
                        ]);
                    }

                }
                $tracing = Tracing::find($registration['tracing_id']);
                if ($tracing){
                    $tracing->delete();
                }
                $chore = Chore::find($registration['chore_id']);
                if ($chore){
                    $chore->delete();
                }
                $profitability = Profitability::find($registration['profitability_id']);
                if ($profitability){
                    $profitability->delete();
                }
                $registration->delete();
            }
        }
    }

    public function tracings($id){
        if ($id){
            $this->tracings = Tracing::select('tracings.*', DB::raw("CONCAT(students.name,' ',students.surname) as student"),
                'companies.name as company')
                ->leftjoin('companies', 'companies.id', '=', 'tracings.company_id')
                ->leftjoin('students', 'students.id', '=', 'tracings.student_id')
                ->where('course_id', $id)->get();
        }
    }

    public function editTracing($id) {
        if ($id) {
            $record = Tracing::findOrFail($id);
            $this->selected_id = $record-> id;
            $this->performed_activities = $record-> performed_activities;
            $this->performed_hours = $record-> performed_hours;
            $this->performed_units = $record-> performed_units;
            $this->final_test = $record-> final_test;
            $this->questionnaire = $record-> questionnaire;
            $this->welcome_message = $record-> welcome_message;
            $this->quarter_message = $record-> quarter_message;
            $this->half_message = $record-> half_message;
            $this->three_quarters_message = $record-> three_quarters_message;
            $this->final_message = $record-> final_message;
            $this->observation = $record->observation;

            $this->updateTracingMode = true;
        }
    }

    public function updateTracing() {
        $this->validate([
            'observation' => 'required',
        ]);
        if ($this->selected_id) {
            $record = Tracing::find($this->selected_id);
            $record->update([
                'observation' => $this->observation
            ]);

            $this->resetInput();
            $this->updateObservationModal = false;
            session()->flash('message', 'Obseervación actualizado con exito.');
        }
    }

    public function chores($id){
        if ($id){
            $this->chores = Chore::select('chores.*', DB::raw("CONCAT(students.name,' ',students.surname) as student"),
                'companies.name as company')
                ->leftjoin('companies', 'companies.id', '=', 'chores.company_id')
                ->leftjoin('students', 'students.id', '=', 'chores.student_id')
                ->where('course_id', $id)->get();
        }

    }
}
