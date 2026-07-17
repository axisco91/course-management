<?php

namespace App\Models;

use App\Helpers\CourseStatusHelper;
use App\Services\CourseService;
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

    protected $fillable = ['name','training_action_id','group','course_type_id','teacher_id','web_platform_id','nebrija','beginning','end','morning_schedule','afternoon_schedule','monday','tuesday','wednesday','thursday','friday','saturday','sunday','formation_center_id','delivery_center_id','outsourced','course_observation','reactivated','welcome_date','quarter_date','half_date','three_quarters_date','final_date','course_status_id','price', 'main_company_id'];

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

    public function courseStatus()
    {
        return $this->belongsTo(CourseStatus::class, 'course_status_id');
    }

    public function courseType()
    {
        return $this->belongsTo(CourseType::class, 'course_type_id');
    }

    public function trainingAction()
    {
        return $this->belongsTo(TrainingAction::class, 'training_action_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function registrations()
    {
        return $this->hasMany(Registration::class, 'course_id', 'id');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function teacher()
    {
        return $this->hasOne('App\Models\Teacher', 'id', 'teacher_id');
    }

    public function webPlatform()
    {
        return $this->belongsTo(WebPlatform::class, 'web_platform_id');
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

    public function scopeWithCourseData($query, $mainCompanyId)
    {
        return $query
            ->select('courses.*')
            ->with([
                'trainingAction:id,formative_action,name,total_hours',
                'courseType:id,name',
                'teacher:id,name,surname',
                'webPlatform:id,name',
                'courseStatus:id,name',
            ])
            ->withCount('registrations') // 👈 esto crea registrations_count
            ->where('courses.main_company_id', $mainCompanyId);
    }

    public function scopeFilterMainCompany($query, $mainCompanyId) {
        return $query->where('courses.main_company_id', $mainCompanyId);
    }

    public static function createWithService($data)
    {
        $service = app(CourseService::class);
        return $service->create($data);
    }

    public function updateWithService($data){
        $service = app(CourseService::class);
        return $service->update($this, $data);
    }

    public function updateDatesWithService($data){
        $service = app(CourseService::class);
        return $service->updateDates($this, $data);
    }

    public static function setName($id, $trainingActionId, $mainCompanyId){
        $service = app(CourseService::class);
        return $service->setName($id, $trainingActionId, $mainCompanyId);
    }

    public static function numbercourses($trainingAction_id){
        $num = Course::where('training_action_id', $trainingAction_id)->get();

        return $num;
    }

    public static function courseDates($beginning_date, $end_date){
        $quarter = null;
        $half = null;
        $three_quarters = null;
        if ($beginning_date && $end_date) {
            $dates = Course::messageDates($beginning_date, $end_date);
            $quarter = $dates['quarter'];
            $half = $dates['half'];
            $three_quarters = $dates['three_quarters'];
        }

        $course_status_id = $beginning_date && $end_date
            ? CourseStatusHelper::updateCourseStatus($beginning_date, $end_date)
            : null;

        return [
            'quarter' => $quarter,
            'half' => $half,
            'three_quarters' => $three_quarters,
            'course_status_id' => $course_status_id
        ];
    }

    public function scopeTeacherCourses($query, $id, $mainCompanyId) {
        return $query->where('teacher_id', $id)
            ->where('main_company_id', $mainCompanyId)
            ->orderBy('beginning', 'DESC');
    }

    public static function messageDates($beginning, $end){

        $beginning = Carbon::createFromFormat('Y-m-d', $beginning);
        $end = Carbon::createFromFormat('Y-m-d', $end);

        $difference = $beginning->diffInDays($end);
        $quarter_days = ($difference/2)/2;

        $quarter = Carbon::createFromFormat('Y-m-d', $beginning->toDateString());

        $quarter = $quarter->addDays($quarter_days);
        $half = Carbon::createFromFormat('Y-m-d', $beginning->toDateString());

        $half = $half->addDays($difference/2);
        $three_quarters = Carbon::createFromFormat('Y-m-d', $end->toDateString());

        $three_quarters = $three_quarters->subDays($quarter_days);

        return [
            'quarter' => $quarter->toDateString(),
            'half' => $half->toDateString(),
            'three_quarters' => $three_quarters->toDateString()
        ];
    }

    public function scopeCompanyCourses($query, $id, $mainCompanyId) {
        $registrations = Registration::where('company_id', $id)->groupBy('course_id')->pluck('course_id')->toArray();
        return $query->select('courses.*')
            ->where(function ($query) use ($registrations){
                $query->WhereIn('courses.id', $registrations);
            })
            ->with(['courseType:id,name'])
            ->where('courses.main_company_id', $mainCompanyId);
    }

    public static function getNumberCourses($year, $mainCompanyId){
        $courses = Course::WhereYear('beginning', $year)
            ->where('main_company_id', $mainCompanyId)
            ->get();
        return count($courses);
    }

    public static function getNumberCoursesPerMonth($year, $mainCompanyId){
        $per_month = [];
        for($i = 1; $i <= 12; $i++){
            $courses = Course::WhereYear('beginning', $year)
                ->WhereMonth('beginning', $i)
                ->where('main_company_id', $mainCompanyId)
                ->get();
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
    public function resetChoresAndFutureTracings($mainCompanyId)
    {
        $today = Carbon::today();
        Log::info("Resetting chores and future tracings for course ID: {$this->id}");
        Log::info("Course beginning date: {$this->beginning}, Today: {$today}");

        if ($this->beginning > $today) {
            Log::info("Course beginning is in the future. Deleting bills.");
            $deletedBills = Bill::where('course_id', $this->id)
                ->FilterMainCompany($mainCompanyId)
                ->delete();
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
