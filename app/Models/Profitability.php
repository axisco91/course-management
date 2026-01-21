<?php

namespace App\Models;

use App\Services\ProfitabilityService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Profitability extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $table = 'profitabilities';

    protected $fillable = [
        'course_id', 'company_id', 'student_id', 'price', 'license', 'teacher',
        'management', 'nebrija_title', 'discount', 'collaborator_commission',
        'advisor_commission', 'total', 'benefits', 'observations',
        'advisor_percentage', 'collaborator_percentage', 'number_students', 'main_company_id'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class, 'profitability_id', 'id');
    }

    public function trainingAction()
    {
        return $this->belongsTo(TrainingAction::class, 'training_action_id');
    }

    public function courseStatus()
    {
        return $this->belongsTo(CourseStatus::class, 'course_status_id');
    }



    public function scopeProfitability($query, $mainCompanyId) {
        return $query->select('profitabilities.*',
            'companies.name as company_name',
            DB::raw("CONCAT(training_actions.formative_action,' / ', courses.group, ' ', training_actions.name) as course"),
            'courses.beginning as beginning',
            'students.name as student_name',
            'students.surname as student_surname',
            DB::raw("YEAR(courses.beginning) as year"),
            DB::raw("CONCAT(students.name,' ',students.surname) as student"))
            ->leftJoin('companies', 'companies.id', '=', 'profitabilities.company_id')
            ->leftJoin('courses', 'courses.id', '=', 'profitabilities.course_id')
            ->leftJoin('students', 'students.id', '=', 'profitabilities.student_id')
            ->leftJoin('training_actions', 'training_actions.id', '=', 'courses.training_action_id')
            ->leftJoin('course_statuses', 'course_statuses.id', '=', 'courses.course_status_id')
            ->where('profitabilities.main_company_id', $mainCompanyId)
            ->groupBy('profitabilities.id', 'companies.name', 'courses.group', 'training_actions.formative_action', 'training_actions.name', 'courses.beginning', 'students.name', 'students.surname')
            ->orderBy('courses.group'); // Added orderBy for better control
    }

    public function scopeFilterMainCompany($query, $mainCompanyId) {
        return $query->where('profitabilities.main_company_id', $mainCompanyId);
    }

    public function scopeGetProfitabilityYear($query, $year, $mainCompanyId)
    {
        return $query
            ->selectRaw('SUM(benefits) as total')
            ->whereYear('created_at', $year)
            ->where('profitabilities.main_company_id', $mainCompanyId);
    }

    public function scopeGetBenefitsPerMonth($query, $year, $mainCompanyId)
    {
        return $query
            ->selectRaw('MONTH(created_at) as month, SUM(benefits) as total')
            ->whereYear('created_at', $year)
            ->where('profitabilities.main_company_id', $mainCompanyId)
            ->groupBy('month')
            ->orderBy('month');
    }

    public function scopeGetExpensesPerMonth($query, $year, $mainCompanyId)
    {
        return $query
            ->selectRaw('MONTH(created_at) as month, SUM(total) as total')
            ->whereYear('created_at', $year)
            ->where('profitabilities.main_company_id', $mainCompanyId)
            ->groupBy('month')
            ->orderBy('month');
    }

    public static function createWithService($data)
    {
        $service = app(ProfitabilityService::class);
        return $service->create($data);
    }

    public function updateWithService($data){
        $service = app(ProfitabilityService::class);
        return $service->update($this, $data);
    }

    public function updateRegistration($data){
        $service = app(ProfitabilityService::class);
        return $service->updateRegistration($this, $data);
    }
}
