<?php

namespace App\Models;

use App\Helpers\CourseStatusHelper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
	use HasFactory;

    public $timestamps = true;

    protected $fillable = ['name','training_action_id','group','course_type_id','teacher_id','nebrija','beginning','end','morning_schedule','afternoon_schedule','monday','tuesday','wednesday','thursday','friday','saturday','sunday','formation_center_id','delivery_center_id','outsourced','course_observation','reactivated','welcome_date','quarter_date','half_date','three_quarters_date','final_date','course_status_id','price'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bonuses()
    {
        return $this->hasMany('App\Models\Bonus', 'course_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function center()
    {
        return $this->hasOne('App\Models\Center', 'id', 'formation_center_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function courseStatuses()
    {
        return $this->hasOne('App\Models\CourseStatus', 'id', 'course_status_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function deliveryCenter()
    {
        return $this->hasOne('App\Models\Center', 'id', 'delivery_center_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function chores()
    {
        return $this->hasMany('App\Models\Chore', 'course_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function courseType()
    {
        return $this->hasOne('App\Models\CourseType', 'id', 'course_type_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function registrations()
    {
        return $this->hasMany('App\Models\Registration', 'course_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function teacher()
    {
        return $this->hasOne('App\Models\Teacher', 'id', 'teacher_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tracings()
    {
        return $this->hasMany('App\Models\Tracing', 'course_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function trainingAction()
    {
        return $this->hasOne('App\Models\TrainingAction', 'id', 'training_action_id');
    }

    public function getCourses($keyWord, $search_formative_action, $search_name, $search_group, $search_type, $search_status, $search_company){
        $courses = Course::select('courses.*',
            'course_types.name as course_type', 'teachers.name as teacher_name', 'teachers.surname as teacher_surname',
            'fc.name as formation_center',
            'dc.name as delivery_center',
            'course_statuses.name as course_status')
            ->leftjoin('course_types', 'course_types.id', '=', 'courses.course_type_id')
            ->leftjoin('teachers', 'teachers.id', '=', 'courses.teacher_id')
            ->leftjoin('centers as fc', 'fc.id', '=', 'courses.formation_center_id')
            ->leftjoin('centers as dc', 'dc.id', '=', 'courses.delivery_center_id')
            ->leftjoin('course_statuses', 'course_statuses.id', '=', 'courses.course_status_id')
            ->leftjoin('registrations', 'registrations.course_id', '=', 'courses.id')
            ->leftjoin('companies', 'companies.id', '=', 'registrations.company_id')
            ->where(function ($query) use ($keyWord){
                $query->orWhere('courses.name', 'LIKE', $keyWord)
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
                    ->orWhere('quarter_date', 'LIKE', $keyWord)
                    ->orWhere('half_date', 'LIKE', $keyWord)
                    ->orWhere('three_quarters_date', 'LIKE', $keyWord)
                    ->orWhere('final_date', 'LIKE', $keyWord)
                    ->orWhere('course_statuses.name', 'LIKE', $keyWord);
            })->where(function ($query) use ($search_formative_action){
                $query->orWhere('courses.name', 'LIKE', $search_formative_action);
            })->where(function ($query) use ($search_name){
                $query->orWhere('courses.name', 'LIKE', $search_name);
            })->where(function ($query) use ($search_group){
                $query->orWhere('courses.name', 'LIKE', $search_group);
            })->where(function ($query) use ($search_company){
                $query->orWhere('companies.name', 'LIKE', $search_company);
            });
        if ($search_type){
            $courses = $courses->Where('courses.course_type_id', $search_type);
        }
        if ($search_status){
            $courses = $courses->Where('courses.course_status_id', $search_status);
        }
        $courses = $courses->orderBy('courses.beginning', 'desc')
            ->paginate(10);

        foreach ($courses as $course){
            $registrations = Registration::where('course_id', $course->id)->get();
            $course['registration'] = $registrations;

            $beginning = Carbon::parse($course['beginning'])->format('d/m/Y');
            $course['beginning'] = $beginning;
            $end = Carbon::parse($course['end'])->format('d/m/Y');
            $course['end'] = $end;
        }

        return $courses;
    }

    public function createCourse($data){
        $course = Course::create([
            'name' => $data['name'],
            'training_action_id' => $data['training_action_id'],
            'group' => $data['group'],
            'course_type_id' => $data['course_type_id'],
            'teacher_id' => $data['teacher_id'],
            'nebrija' => $data['nebrija'],
            'beginning' => $data['beginning'],
            'end' => $data['end'],
            'morning_schedule' => $data['morning_schedule'],
            'afternoon_schedule' => $data['afternoon_schedule'],
            'monday' => $data['monday'],
            'tuesday' => $data['tuesday'],
            'wednesday' => $data['wednesday'],
            'thursday' => $data['thursday'],
            'friday' => $data['friday'],
            'saturday' => $data['saturday'],
            'sunday' => $data['sunday'],
            'formation_center_id' => $data['formation_center_id'],
            'delivery_center_id' => $data['delivery_center_id'],
            'outsourced' => $data['outsourced'],
            'course_observation' => $data['course_observation'],
            'reactivated' => $data['reactivated'],
            'welcome_date' => $data['beginning'],
            'quarter_date' => $data['quarter_date'],
            'half_date' => $data['half_date'],
            'three_quarters_date' => $data['three_quarters_date'],
            'final_date' => $data['final_date'],
            'course_status_id' => $data['course_status_id'],
            'price' => $data['price'],
        ]);

        return $course;
    }

    public function updateCourse($id, $data){
        $course = Course::find($id);
        $course->update([
            'name' => $data['name'],
            'training_action_id' => $data['training_action_id'],
            'group' => $data['group'],
            'course_type_id' => $data['course_type_id'],
            'teacher_id' => $data['teacher_id'],
            'nebrija' => $data['nebrija'],
            'beginning' => $data['beginning'],
            'end' => $data['end'],
            'morning_schedule' => $data['morning_schedule'],
            'afternoon_schedule' => $data['afternoon_schedule'],
            'monday' => $data['monday'],
            'tuesday' => $data['tuesday'],
            'wednesday' => $data['wednesday'],
            'thursday' => $data['thursday'],
            'friday' => $data['friday'],
            'saturday' => $data['saturday'],
            'sunday' => $data['sunday'],
            'formation_center_id' => $data['formation_center_id'],
            'delivery_center_id' => $data['delivery_center_id'],
            'outsourced' => $data['outsourced'],
            'course_observation' => $data['course_observation'],
            'reactivated' => $data['reactivated'],
            'welcome_date' => $data['beginning'],
            'quarter_date' => $data['quarter_date'],
            'half_date' => $data['half_date'],
            'three_quarters_date' => $data['three_quarters_date'],
            'final_date' => $data['final_date'],
            'course_status_id' => $data['course_status_id'],
            'price' => $data['price'],
        ]);

        return $course;
    }

    public function setName($training_action_id, $selected_id){
        if ($training_action_id > 0){
            $training_action = TrainingAction::find($training_action_id);
            if ($training_action_id < 10){
                $name = '00'.$training_action_id.' - '.$training_action['name'];
            } else if ($training_action_id < 100){
                $name = '0'.$training_action_id.' - '.$training_action['name'];
            } else {
                $name = $training_action_id.' - '.$training_action['name'];
            }
            $num_courses = Course::numbercourses($training_action['id']);
            $cont = $num_courses->count();
            $cont = $cont+1;
            if ($cont < 10){
                $group = '000'.$cont;
            } else if ($cont < 100){
                $group = '00'.$cont;
            } else if ($cont < 1000){
                $group = '0'.$cont;
            } else {
                $group = $cont;
            }
            if ($selected_id == null){
                $price = $training_action['price'];
            }
        }

        return [
            'name' => $name,
            'group' => $group,
            'price' => $price
        ];
    }

    public function numbercourses($training_action_id){
        $num = Course::where('training_action_id', $training_action_id)->get();

        return $num;
    }

    public function course_data($beginning_date, $end_date){
        $quarter = null;
        $half = null;
        $three_quarters = null;
        if ($beginning_date && $end_date) {
            $dates = Course::messageDates($beginning_date, $end_date);
            $quarter = $dates['quarter'];
            $half = $dates['half'];
            $three_quarters = $dates['three_quarters'];
        }

        $course_status_id = CourseStatusHelper::updateCourseStatus($beginning_date, $end_date);

        return [
            'quarter' => $quarter,
            'half' => $half,
            'three_quarters' => $three_quarters,
            'course_status_id' => $course_status_id
        ];
    }

    public function getTeachersCourses($id, $search_course_name, $search_course_group){
        $courses = Course::where('teacher_id', $id)
            ->where(function ($query) use ($search_course_name) {
                $query->orWhere('name', 'LIKE', $search_course_name);
            })->where(function ($query) use ($search_course_group) {
                $query->orWhere('group', 'LIKE', $search_course_group);
            })->orderBy('beginning', 'DESC')->get();

        foreach ($courses as $course){
            $beginning = Carbon::parse($course['beginning'])->format('d/m/Y');
            $course['beginning'] = $beginning;
            $end = Carbon::parse($course['end'])->format('d/m/Y');
            $course['end'] = $end;
        }

        return $courses;
    }

    public function messageDates($beggining, $end){

        $beggining = Carbon::createFromFormat('Y-m-d', $beggining);
        $end = Carbon::createFromFormat('Y-m-d', $end);

        $difrence = $beggining->diffInDays($end);
        $quarter_days = ($difrence/2)/2;

        $quarter = Carbon::createFromFormat('Y-m-d', $beggining->toDateString());

        $quarter = $quarter->addDays($quarter_days);
        $half = Carbon::createFromFormat('Y-m-d', $beggining->toDateString());

        $half = $half->addDays($difrence/2);
        $three_quarters = Carbon::createFromFormat('Y-m-d', $end->toDateString());

        $three_quarters = $three_quarters->subDays($quarter_days);

        return [
            'quarter' => $quarter->toDateString(),
            'half' => $half->toDateString(),
            'three_quarters' => $three_quarters->toDateString()
        ];
    }

    public function getTrainingActionCourse($id, $search_course_name, $search_course_group)
    {
        $courses = Course::where('training_action_id', $id)
            ->where(function ($query) use ($search_course_name) {
                $query->orWhere('name', 'LIKE', $search_course_name);
            })->where(function ($query) use ($search_course_group) {
                $query->orWhere('group', 'LIKE', $search_course_group);
            })->orderBy('beginning', 'DESC')->get();
        foreach ($courses as $course){
            $beginning = Carbon::parse($course['beginning'])->format('d/m/Y');
            $course['beginning'] = $beginning;
            $end = Carbon::parse($course['end'])->format('d/m/Y');
            $course['end'] = $end;
        }

        return $courses;
    }

    public function getCompanyCourses($id, $search_course_name, $search_course_group){
        $courses = Course::select('courses.*')->leftjoin('registrations', 'registrations.course_id', '=', 'courses.id')
            ->where('company_id', $id)
            ->where(function ($query) use ($search_course_name) {
                $query->orWhere('name', 'LIKE', $search_course_name);
            })->where(function ($query) use ($search_course_group) {
                $query->orWhere('group', 'LIKE', $search_course_group);
            })
            ->orderBy('beginning', 'DESC')
            ->get();
        foreach ($courses as $course){
            $beginning = Carbon::parse($course['beginning'])->format('d/m/Y');
            $course['beginning'] = $beginning;
            $end = Carbon::parse($course['end'])->format('d/m/Y');
            $course['end'] = $end;
        }
        return $courses;
    }

}
