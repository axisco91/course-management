<?php

namespace App\Models;

use App\Helpers\CourseStatusHelper;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    public static function getCourses(){

        $courses = Course::select('courses.*',
            'course_types.name as course_type',
            DB::raw("CONCAT(teachers.name,' ', teachers.surname) as teacher"),
            'fc.name as formation_center',
            'dc.name as delivery_center',
            'course_statuses.name as course_status',
            DB::raw("CONCAT(training_actions.formative_action,' / ', courses.group, ' ', training_actions.name) as label"),
            'training_actions.name as training_action',
            'training_actions.formative_action as formative_action')
            ->leftjoin('course_types', 'course_types.id', '=', 'courses.course_type_id')
            ->leftjoin('teachers', 'teachers.id', '=', 'courses.teacher_id')
            ->leftjoin('centers as fc', 'fc.id', '=', 'courses.formation_center_id')
            ->leftjoin('centers as dc', 'dc.id', '=', 'courses.delivery_center_id')
            ->leftjoin('course_statuses', 'course_statuses.id', '=', 'courses.course_status_id')
            ->leftjoin('training_actions', 'training_actions.id', '=', 'courses.training_action_id')
            ->orderBy('courses.beginning', 'desc')
            ->get();

        foreach ($courses as $course){
            $registrations = Registration::select('registrations.*', 'companies.name')
                ->leftjoin('companies', 'companies.id', '=', 'registrations.company_id')
                ->where('course_id', $course->id)->get();
            $course['registrations'] = $registrations;
            $registration = Registration::where('course_id', $course->id)->first();
            if ($registration) {
                $course['used'] = true;
            } else {
                $course['used'] = false;
            }

            $course['number_registrations'] = $course->registrations->count();
        }

        return $courses;
    }

    public static function getCourse($id){

        $course = Course::select('courses.*',
            'course_types.name as course_type',
            DB::raw("CONCAT(teachers.name,' ', teachers.surname) as teacher"),
            'fc.name as formation_center',
            'dc.name as delivery_center',
            'course_statuses.name as course_status',
            'courses.id as value', 'courses.name as label',
            DB::raw("CONCAT(training_actions.formative_action,' / ', courses.group, ' ', training_actions.name) as label"),
            'training_actions.name as training_action',
            'training_actions.formative_action as formative_action')
            ->leftjoin('course_types', 'course_types.id', '=', 'courses.course_type_id')
            ->leftjoin('teachers', 'teachers.id', '=', 'courses.teacher_id')
            ->leftjoin('centers as fc', 'fc.id', '=', 'courses.formation_center_id')
            ->leftjoin('centers as dc', 'dc.id', '=', 'courses.delivery_center_id')
            ->leftjoin('course_statuses', 'course_statuses.id', '=', 'courses.course_status_id')
            ->leftjoin('training_actions', 'training_actions.id', '=', 'courses.training_action_id')
            ->where('courses.id', $id)
            ->orderBy('courses.beginning', 'asc')
            ->first();

        $registration = Registration::where('course_id', $course->id)->first();
        if ($registration) {
            $course['used'] = true;
        } else {
            $course['used'] = false;
        }
        $course['number_registrations'] = $course->registrations->count();
        return $course;
    }

    public static function createCourse($data){

        $course_info = Course::courseDates(Carbon::createFromFormat('d-m-Y', $data['beginning'])->format('Y-m-d'), Carbon::createFromFormat('d-m-Y', $data['end'])->format('Y-m-d'));
        $anulado = CourseStatus::where('name', 'ANULADO')->first();
        if ($data['canceled'] == 1) {
            $course_info['course_status_id'] = $anulado->id;
        }
        $course = Course::create([
            'name' => $data['name'],
            'training_action_id' => $data['training_action_id'],
            'group' => $data['group'],
            'course_type_id' => $data['course_type_id'],
            'teacher_id' => $data['teacher_id'],
            'beginning' => $data['beginning'] ? Carbon::createFromFormat('d-m-Y', $data['beginning'])->format('Y-m-d') : null,
            'end' => $data['end'] ? Carbon::createFromFormat('d-m-Y', $data['end'])->format('Y-m-d') : null,
            'morning_schedule' => $data['morning_schedule'],
            'afternoon_schedule' => $data['afternoon_schedule'],
            'formation_center_id' => $data['formation_center_id'],
            'delivery_center_id' => $data['delivery_center_id'],
            'course_observation' => $data['course_observation'],
            'welcome_date' => $data['beginning'] ? Carbon::createFromFormat('d-m-Y', $data['beginning'])->format('Y-m-d') : null,
            'quarter_date' => $course_info['quarter'],
            'half_date' => $course_info['half'],
            'three_quarters_date' => $course_info['three_quarters'],
            'final_date' => $data['end'] ? Carbon::createFromFormat('d-m-Y', $data['end'])->format('Y-m-d') : null,
            'course_status_id' => $course_info['course_status_id'],
            'price' => $data['price'],
            'nebrija' => $data['nebrija'],
            'monday' => $data['monday'],
            'tuesday' => $data['tuesday'],
            'wednesday' => $data['wednesday'],
            'thursday' => $data['thursday'],
            'friday' => $data['friday'],
            'saturday' => $data['saturday'],
            'sunday' => $data['sunday'],
            'outsourced' => $data['outsourced'],
            'reactivated' => $data['reactivated']
        ]);
        return $course;
    }

    public static function updateCourse($id, $data){

        $course_info = Course::courseDates(Carbon::createFromFormat('d-m-Y', $data['beginning'])->format('Y-m-d'), Carbon::createFromFormat('d-m-Y', $data['end'])->format('Y-m-d'));
        $anulado = CourseStatus::where('name', 'ANULADO')->first();
        if ($data['canceled'] == 1) {
            $course_info['course_status_id'] = $anulado->id;
        }
        $course = Course::find($id);
        $course->update([
            'name' => $data['name'],
            'training_action_id' => $data['training_action_id'],
            'group' => $data['group'],
            'course_type_id' => $data['course_type_id'],
            'teacher_id' => $data['teacher_id'],
            'beginning' => $data['beginning'] ? Carbon::createFromFormat('d-m-Y', $data['beginning'])->format('Y-m-d') : null,
            'end' => $data['end'] ? Carbon::createFromFormat('d-m-Y', $data['end'])->format('Y-m-d') : null,
            'morning_schedule' => $data['morning_schedule'],
            'afternoon_schedule' => $data['afternoon_schedule'],
            'formation_center_id' => $data['formation_center_id'],
            'delivery_center_id' => $data['delivery_center_id'],
            'course_observation' => $data['course_observation'],
            'welcome_date' => $data['beginning'] ? Carbon::createFromFormat('d-m-Y', $data['beginning'])->format('Y-m-d') : null,
            'quarter_date' => $course_info['quarter'],
            'half_date' => $course_info['half'],
            'three_quarters_date' => $course_info['three_quarters'],
            'final_date' => $data['end'] ? Carbon::createFromFormat('d-m-Y', $data['end'])->format('Y-m-d') : null,
            'course_status_id' => $course_info['course_status_id'],
            'price' => $data['price'],
            'nebrija' => $data['nebrija'],
            'monday' => $data['monday'],
            'tuesday' => $data['tuesday'],
            'wednesday' => $data['wednesday'],
            'thursday' => $data['thursday'],
            'friday' => $data['friday'],
            'saturday' => $data['saturday'],
            'sunday' => $data['sunday'],
            'outsourced' => $data['outsourced'],
            'reactivated' => $data['reactivated']
        ]);
        return $course;
    }

    public static function setName($training_action_id, $id){
        if ($training_action_id > 0){
            $training_action = TrainingAction::find($training_action_id);
            if ($training_action_id < 10){
                $name = '00'.$training_action_id;
            } else if ($training_action_id < 100){
                $name = '0'.$training_action_id;
            } else {
                $name = $training_action_id;
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
            $price = '';
            if ($id == null){
                $price = $training_action['price'];
            }
        }

        return [
            'name' => $name.' / '. $group .' - '.$training_action['name'],
            'group' => $group,
            'price' => $price
        ];
    }

    public static function numbercourses($training_action_id){
        $num = Course::where('training_action_id', $training_action_id)->get();

        return $num;
    }

    private static function courseDates($beginning_date, $end_date){
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

    public static function getTeachersCourses($id){
        $courses = Course::where('teacher_id', $id)->orderBy('beginning', 'DESC')->get();

        foreach ($courses as $course){
            $beginning = Carbon::parse($course['beginning'])->format('d/m/Y');
            $course['beginning'] = $beginning;
            $end = Carbon::parse($course['end'])->format('d/m/Y');
            $course['end'] = $end;
        }

        return $courses;
    }

    public static function messageDates($beggining, $end){

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

    public static function getTrainingActionCourse($id, $search_course_name = null, $search_course_group = null)
    {
        $courses = Course::where('training_action_id', $id)->orderBy('beginning', 'DESC')->get();
        foreach ($courses as $course){
            $beginning = Carbon::parse($course['beginning'])->format('d/m/Y');
            $course['beginning'] = $beginning;
            $end = Carbon::parse($course['end'])->format('d/m/Y');
            $course['end'] = $end;
        }

        return $courses;
    }

    public static function getCompanyCourses($id){
        $registrations = Registration::where('company_id', $id)->groupBy('course_id')->pluck('course_id')->toArray();
        $courses = Course::select('courses.*')
            ->where(function ($query) use ($registrations){
                $query->WhereIn('courses.id', $registrations);
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

    public static function getNumberCourses($year){
        $courses = Course::WhereYear('beginning', $year)->get();
        return count($courses);
    }

    public static function getNumberCoursesPerMonth($year){
        $per_month = [];
        for($i = 1; $i <= 12; $i++){
            $courses = Course::WhereYear('beginning', $year)
                    ->WhereMonth('beginning', $i)->get();
            $per_month[] = count($courses);
        }
        return $per_month;
    }

    public static function getCourseCSV($formative_action = null, $name = null, $group = null, $type = null, $status = null, $company = null){
        $courses = Course::select('courses.*',
            'course_types.name as course_type',
            DB::raw("CONCAT(teachers.name,' ', teachers.surname) as teacher"),
            'fc.name as formation_center',
            'dc.name as delivery_center',
            'course_statuses.name as course_status',
            'training_actions.formative_action as formative_action',
            'training_actions.name as training_action',
            'training_actions.formative_action as formative_action',
            DB::raw('IF(courses.nebrija = 1, "Si", "No") as nebrija'),
            DB::raw("CONCAT(IF(monday = 1, 'Lunes ', ''), IF(tuesday = 1, 'Martes ', ''), IF(wednesday = 1, 'Miercoles ', ''), IF(thursday = 1, 'Jueves ', ''), IF(friday = 1, 'Viernes ', ''), IF(saturday = 1, 'Sabado', ''), IF(sunday = 1, 'Domingo', '')) as days"),
            Db::raw('IF(outsourced = 1, "Si", "No") as outsourced'),
            Db::raw('IF(reactivated, "Si", "No") as reactivated'))
            ->leftjoin('course_types', 'course_types.id', '=', 'courses.course_type_id')
            ->leftjoin('teachers', 'teachers.id', '=', 'courses.teacher_id')
            ->leftjoin('centers as fc', 'fc.id', '=', 'courses.formation_center_id')
            ->leftjoin('centers as dc', 'dc.id', '=', 'courses.delivery_center_id')
            ->leftjoin('course_statuses', 'course_statuses.id', '=', 'courses.course_status_id')
            ->leftjoin('training_actions', 'training_actions.id', '=', 'courses.training_action_id');

        if ($formative_action) {
            $courses = $courses->where('courses.name', 'like', '%'.$formative_action.'%');
        }
        if ($name) {
            $courses = $courses->where('courses.name', 'like', '%'.$name.'&');
        }
        if ($group) {
            $courses = $courses->where('courses.group', 'like', '%'.$group.'%');
        }
        if ($type) {
            $courses = $courses->where('course_types.name', $type);
        }
        if ($status) {
            $courses = $courses->where('course_statuses.name', $status);
        }
        if ($company) {
            $registrations = Registration::where('company_id', $company)->groupBy('course_id')->pluck('course_id')->toArray();
            $courses = $courses->where(function ($query) use ($registrations){
                $query->WhereIn('courses.id', $registrations);
            });
        }

        $courses = $courses->orderBy('courses.beginning', 'asc')->get();

        $data = [];
        foreach ($courses as $course) {
            $beginning = \Carbon\Carbon::parse($course['beginning'])->format('d/m/Y');
            $end = Carbon::parse($course['end'])->format('d/m/Y');
            $element = [
                'Acción Formativa' => $course['formative_action'],
                'Grupo' => $course['group'],
                'Nombre' => $course['name'],
                'Tipo curso' => $course['course_type'],
                'Fecha Inicio' => $beginning,
                'Fecha Fin' => $end,
                'Docente' => $course['teacher'],
                'Nebrija' => $course['nebrija'],
                'Centro Formativo' => $course['formation_center'],
                'Centro impartición' => $course['delivery_center'],
                'Estado' => $course['course_status'],
                'Horario Mañana' => $course['morning_schedule'],
                'Horario Tarde' => $course['afternoon_schedule'],
                'Días Impartición' => $course['days'],
                'Subcontratado' => $course['outsourced'],
                'Precio' => $course['price'],
                'Reactivado' => $course['reactivated'],
                'Observaciones' => $course['course_observation'],
            ];
            $data[] = $element;
        }
        return $data;
    }

}
