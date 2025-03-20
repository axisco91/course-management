<?php

namespace App\Models;

use App\Services\StudentService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class Student extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'name',
        'surname',
        'dni',
        'telephone',
        'email',
        'company_id',
        'user',
        'password',
        'date_of_birth',
        'level_study_id',
        'disabled',
        'social_security_number',
        'c_quote',
        'quote_group_id',
        'professional_category_id',
        'annual_gross_salary',
        'annual_hours',
        'hourly_cost_worker_gross',
        'direction',
        'post_code',
        'population_id',
        'province_id',
        'population',
        'observation',
        'iban',
        'active',
        'nationality',
        'legal_guardian_name',
        'legal_guardian_dni',
        'population_code',
        'nationality_code',
        'regimen'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function chores()
    {
        return $this->hasMany('App\Models\Chore', 'student_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function company()
    {
        return $this->hasOne('App\Models\Company', 'id', 'company_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function levelStudy()
    {
        return $this->hasOne('App\Models\LevelStudy', 'id', 'level_study_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function population()
    {
        return $this->hasOne('App\Models\Population', 'id', 'population_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function professionalCategory()
    {
        return $this->hasOne('App\Models\ProfessionalCategory', 'id', 'professional_category_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function province()
    {
        return $this->hasOne('App\Models\Province', 'id', 'province_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function registrations()
    {
        return $this->hasMany('App\Models\Registration', 'student_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tracings()
    {
        return $this->hasMany('App\Models\Tracing', 'student_id', 'id');
    }

    public function scopeStudent($query){
        return $query->select('students.*',
            'companies.name as company',
            'level_studies.name as level_study',
            'professional_categories.name as professional_category',
            'provinces.name as province',
            'quote_groups.name as quote_group',
            'students.id as value',
            DB::raw("CONCAT(students.name,' ',students.surname) as label"))
            ->leftjoin('companies', 'companies.id', '=', 'students.company_id')
            ->leftjoin('level_studies', 'level_studies.id', '=', 'students.level_study_id')
            ->leftjoin('professional_categories', 'professional_categories.id', '=', 'students.professional_category_id')
            ->leftjoin('provinces', 'provinces.id', '=', 'students.province_id')
            ->leftjoin('quote_groups', 'quote_groups.id', '=', 'students.quote_group_id');
    }

    public function scopeCompanyStudents($query, $id) {
        return $query->where('company_id', $id)
            ->where('active', 1);
    }

    public function scopeBilledStudent($query, $id) {
        return $query->select('students.*', 'companies.name as company_name')
            ->join('registrations', 'registrations.student_id', '=', 'students.id')
            ->leftJoin('companies', 'registrations.company_id', '=', 'companies.id')
            ->where('registrations.billing_id', $id);
    }


    public function scopeGetRegistrated($query, $courseId){
    return $query->select('students.*', 'registrations.is_bonus', 'companies.name as company_name',
        'registrations.id as registration_id', 'registrations.price',
        DB::raw("CONCAT(students.name,' ',students.surname) as student"))
        ->leftJoin('registrations', 'students.id', '=', 'registrations.student_id')
        ->leftJoin('companies', 'registrations.company_id', '=', 'companies.id')
        ->where('registrations.course_id', $courseId);
    }


    public function scopeGetUnregistrated($query, $courseId){
        $registations = Student::leftJoin('registrations', 'students.id', '=', 'registrations.student_id')
            ->where('registrations.course_id', '=', $courseId)->pluck('student_id');
        return $query->select('students.*', 'students.id as value', DB::raw("CONCAT(students.name,' ',students.surname) as label"))
            ->where('active', 1)
            ->whereNotIn('id', $registations);
    }

    public static function import($data)
    {
        $students = app(StudentService::class);
        return $students->import($data);
    }
}
