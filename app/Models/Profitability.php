<?php

namespace App\Models;

use App\Helpers\GeneralHelpers;
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
        'advisor_percentage', 'collaborator_percentage', 'number_students'
    ];

    // Define the relationship with the Course model
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    // Define the relationship with the Company model
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function registrations()
    {
        return $this->hasMany('App\Models\Registration', 'profitability_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function students()
    {
        return $this->hasOne('App\Models\Student', 'id', 'student_id');
    }

    public function scopeProfitability($query) {
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
            ->groupBy('profitabilities.id', 'companies.name', 'courses.group', 'training_actions.formative_action', 'training_actions.name', 'courses.beginning', 'students.name', 'students.surname')
            ->orderBy('courses.group'); // Added orderBy for better control
    }

    public static function getProfitabilityYear($year)
    {
        $profitabilities = Profitability::whereYear('created_at', $year)->get();
        $total = 0;
        foreach ($profitabilities as $profitability) {
            $total += $profitability['benefits'];
        }
        return $total;
    }

    public static function getBenefitsPerMonth($year)
    {
        $total_months = [];
        for ($i = 1; $i <= 12; $i++) {
            $total = 0;
            $profitabilities = Profitability::whereYear('created_at', $year)
                ->whereMonth('created_at', $i)->get();
            foreach ($profitabilities as $profitability) {
                $total += $profitability['benefits'];
            }
            $total_months[] = $total;
        }
        return $total_months;
    }

    public static function getExpensesPerMonth($year)
    {
        $total_months = [];
        for ($i = 1; $i <= 12; $i++) {
            $total = 0;
            $profitabilities = Profitability::whereYear('created_at', $year)
                ->whereMonth('created_at', $i)->get();
            foreach ($profitabilities as $profitability) {
                $total += $profitability['total'];
            }
            $total_months[] = -$total;
        }
        return $total_months;
    }
}
