<?php

namespace App\Models;

use App\Helpers\CourseStatusHelper;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Bill;

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
    public function profitabilities()
    {
        return $this->hasMany('App\Models\Profitability', 'course_id', 'id');
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function trainingAction()
    {
        return $this->hasOne('App\Models\TrainingAction', 'id', 'training_action_id');
    }

    public function scopeWithCourseData($query)
{
    return $query
        ->select('courses.*',
            'course_types.name as course_type',
            DB::raw("CONCAT(teachers.name,' ', teachers.surname) as teacher"),
            'fc.name as formation_center',
            'dc.name as delivery_center',
            'course_statuses.name as course_status',
            DB::raw("CONCAT(training_actions.formative_action,' / ', courses.group, ' ', training_actions.name) as label"),
            DB::raw("CONCAT(training_actions.formative_action,' - ',training_actions.name) as training_action"),
            'training_actions.formative_action as formative_action',
            'training_actions.total_hours as total_hours', // Agregado total_hours
            DB::raw("(SELECT GROUP_CONCAT(registrations.company_id) FROM registrations
               WHERE registrations.course_id = courses.id) as company_ids")
        )
        ->leftjoin('course_types', 'course_types.id', '=', 'courses.course_type_id')
        ->leftjoin('teachers', 'teachers.id', '=', 'courses.teacher_id')
        ->leftjoin('centers as fc', 'fc.id', '=', 'courses.formation_center_id')
        ->leftjoin('centers as dc', 'dc.id', '=', 'courses.delivery_center_id')
        ->leftjoin('course_statuses', 'course_statuses.id', '=', 'courses.course_status_id')
        ->leftjoin('training_actions', 'training_actions.id', '=', 'courses.training_action_id')
        ->orderBy('courses.beginning', 'desc');
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
        \Log::info('Updating course: ' . $id);
        \Log::info('Received data: ' . json_encode($data));
    
        $course_info = Course::courseDates(
            Carbon::createFromFormat('d-m-Y', $data['beginning'])->format('Y-m-d'),
            Carbon::createFromFormat('d-m-Y', $data['end'])->format('Y-m-d')
        );
    
        // Si se proporciona course_status_id en la solicitud, úsalo
        if (isset($data['course_status_id'])) {
            $course_info['course_status_id'] = $data['course_status_id'];
            \Log::info('Using provided course_status_id: ' . $data['course_status_id']);
        } elseif (isset($data['canceled']) && $data['canceled'] == 1) {
            $anulado = CourseStatus::where('name', 'ANULADO')->first();
            $course_info['course_status_id'] = $anulado->id;
            \Log::info('Course marked as canceled. Setting status to ANULADO (ID: ' . $anulado->id . ')');
        }
    
        $course = Course::find($id);
        $updatedData = [
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
        ];
    
        $course->update($updatedData);
    
        \Log::info('Course updated. New data: ' . json_encode($course->fresh()));
    
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
            $num_courses = Course::where( 'training_action_id',$training_action['id'])
                ->orderby('group', 'desc')->first();
            if ($num_courses) {
                $cont = intval($num_courses->group);
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
            } else {
                $group = '0001';
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

    public function scopeTeacherCourses($query, $id) {
        return $query->where('teacher_id', $id)
            ->orderBy('beginning', 'DESC');
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

    public function scopeTrainingActionCourses($query, $id) {
        return $query->where('training_action_id', $id)
            ->orderBy('beginning', 'DESC');
    }

    public function scopeCompanyCourses($query, $id) {
        $registrations = Registration::where('company_id', $id)->groupBy('course_id')->pluck('course_id')->toArray();
        return $query->select('courses.*')
            ->where(function ($query) use ($registrations){
                $query->WhereIn('courses.id', $registrations);
            });
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

    /**
     * Este método restablece las fechas de seguimiento futuras (tracings) y elimina las tareas (chores) y facturas (bills) asociadas al curso.
     * 
     * - Las fechas de seguimiento futuras se restablecen a null si son mayores o iguales a la fecha actual.
     * - Las facturas asociadas al curso se eliminan si la fecha de inicio del curso es posterior a la fecha actual.
     * - Todas las tareas asociadas al curso se eliminan, independientemente de la fecha.
     * 
     * Después de realizar estas operaciones, el curso se guarda en la base de datos con las nuevas fechas y sin las tareas y facturas asociadas.
     * 
     * @return void
     */
    public function resetChoresAndFutureTracings()
    {
        $today = Carbon::today();
        Log::info("Resetting chores and future tracings for course ID: {$this->id}");
        Log::info("Course beginning date: {$this->beginning}, Today: {$today}");

        if ($this->beginning > $today) {
            Log::info("Course beginning is in the future. Deleting bills.");
            $deletedBills = Bill::where('course_id', $this->id)->delete();
            Log::info("Deleted {$deletedBills} bills for course {$this->id}");
        } else {
            Log::info("Course beginning is not in the future. No bills deleted.");
        }

        // Log the current tracing dates
        Log::info("Current tracing dates: welcome_date: {$this->welcome_date}, quarter_date: {$this->quarter_date}, half_date: {$this->half_date}, three_quarters_date: {$this->three_quarters_date}, final_date: {$this->final_date}");

        $datesToReset = ['welcome_date', 'quarter_date', 'half_date', 'three_quarters_date', 'final_date'];
        foreach ($datesToReset as $dateField) {
            if ($this->$dateField >= $today) {
                Log::info("Resetting {$dateField} from {$this->$dateField} to null");
                $this->$dateField = null;
            } else {
                Log::info("{$dateField} is in the past ({$this->$dateField}). Not resetting.");
            }
        }

        // Delete chores
        $deletedChores = $this->chores()->delete();
        Log::info("Deleted {$deletedChores} chores for course {$this->id}");

        // Delete profits
        $deletedProfits = $this->profitabilities()->delete();
        Log::info("Deleted {$deletedProfits} profitabilities for course {$this->id}");

        $this->save();
        Log::info("Course {$this->id} saved with updated tracing dates");

        // Log the updated tracing dates
        Log::info("Updated tracing dates: welcome_date: {$this->welcome_date}, quarter_date: {$this->quarter_date}, half_date: {$this->half_date}, three_quarters_date: {$this->three_quarters_date}, final_date: {$this->final_date}");
    }
}