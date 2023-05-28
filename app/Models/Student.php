<?php

namespace App\Models;

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
        'active'
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

    /**
     * @param $data
     * @return Create Student
     */
    public static function createStudent($data){

        $student = Student::create([
            'name' => $data['name'],
            'surname' => $data['surname'],
            'dni' => $data['dni'],
            'telephone' => $data['telephone'],
            'email' => $data['email'],
            'company_id' => $data['company_id'],
            'user' => $data['user'],
            'password' => $data['password'],
            'disabled' => $data['disabled'] == true ? 1 : 0,
            'date_of_birth' => $data['date_of_birth'] ? Carbon::createFromFormat('d-m-Y', $data['date_of_birth'])->format('Y-m-d') : null,
            'level_study_id' => $data['level_study_id'],
            'social_security_number' => $data['social_security_number'] ? $data['social_security_number'] : null,
            'c_quote' => $data['c_quote'] ? $data['c_quote'] : null,
            'quote_group_id' => $data['quote_group_id'] ? $data['quote_group_id'] : null,
            'professional_category_id' => $data['professional_category_id'] ? $data['professional_category_id'] : null,
            'annual_gross_salary' => $data['annual_gross_salary'] ? $data['annual_gross_salary'] : null,
            'annual_hours' => $data['annual_hours'] ? $data['annual_hours'] : null,
            'hourly_cost_worker_gross' => $data['hourly_cost_worker_gross'] ? $data['hourly_cost_worker_gross'] : null,
            'direction' => $data['direction'] ? $data['direction'] : null,
            'post_code' => $data['post_code'] ? $data['post_code'] : null,
            'province_id' => $data['province_id'] ? $data['province_id'] : null,
            'population' => $data['population'] ? $data['population'] : null,
            'observation' => $data['observation'] ? $data['observation'] : null,
            'iban' => $data['iban'] ? $data['iban'] : null,
            'active' => $data['active'],
            'disabled' => $data['disabled']
        ]);

        return $student;
    }

    /**
     * Update Student
     */
    public static function updateStudent($id, $data){
        $student = Student::find($id);
        $student->update([
            'name' => $data['name'],
            'surname' => $data['surname'],
            'dni' => $data['dni'],
            'telephone' => $data['telephone'],
            'email' => $data['email'],
            'company_id' => $data['company_id'],
            'user' => $data['user'],
            'password' => $data['password'],
            'date_of_birth' => $data['date_of_birth'] ? Carbon::createFromFormat('d-m-Y', $data['date_of_birth'])->format('Y-m-d') : null,
            'level_study_id' => $data['level_study_id'],
            'social_security_number' => $data['social_security_number'] ? $data['social_security_number'] : null,
            'c_quote' => $data['c_quote'] ? $data['c_quote'] : null,
            'quote_group_id' => $data['quote_group_id'] ? $data['quote_group_id'] : null,
            'professional_category_id' => $data['professional_category_id'] ? $data['professional_category_id'] : null,
            'annual_gross_salary' => $data['annual_gross_salary'] ? $data['annual_gross_salary'] : null,
            'annual_hours' => $data['annual_hours'] ? $data['annual_hours'] : null,
            'hourly_cost_worker_gross' => $data['hourly_cost_worker_gross'] ? $data['hourly_cost_worker_gross'] : null,
            'direction' => $data['direction'] ? $data['direction'] : null,
            'post_code' => $data['post_code'] ? $data['post_code'] : null,
            'province_id' => $data['province_id'] ? $data['province_id'] : null,
            'population' => $data['population'] ? $data['population'] : null,
            'observation' => $data['observation'] ? $data['observation'] : null,
            'iban' => $data['iban'] ? $data['iban'] : null,
            'disabled' => $data['disabled'],
            'active' => $data['active']
        ]);

        return $student;
    }

    /**
     * @param $id
     * @return Student
     */
    public static function getStudent($id){
        $student = Student::select('students.*',
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
            ->leftjoin('quote_groups', 'quote_groups.id', '=', 'students.quote_group_id')
            ->where('students.id', $id)->first();
        $registered = Registration::where('student_id', $student->id)->first();
        if ($registered) {
            $student['used'] = true;
        } else {
            $student['used'] = false;
        }
        return $student;
    }

    /**
     * Update active or inactive
     */
    public static function activeInactive($id, $state){
        $student = Student::findOrFail($id);
        $student->update([
            'active' => $state
        ]);
        return $student;
    }

    /**
     * Get all students
     */
    public static function getStudents(){
        $students = Student::select('students.*',
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
            ->leftjoin('quote_groups', 'quote_groups.id', '=', 'students.quote_group_id')->orderBy('students.name','asc')->get();

        foreach($students as $student) {
            $registered = Registration::where('student_id', $student->id)->first();
            if ($registered) {
                $student['used'] = true;
            } else {
                $student['used'] = false;
            }
        }

        return $students;
    }

    /**
     * Get all students
     */
    public static function getActiveStudents(){
        $students = Student::select('students.*',
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
            ->leftjoin('quote_groups', 'quote_groups.id', '=', 'students.quote_group_id')
            ->where('active', 1)
            ->orderBy('students.name','asc')->get();

        foreach($students as $student) {
            $registered = Registration::where('student_id', $student->id)->first();
            if ($registered) {
                $student['used'] = true;
            } else {
                $student['used'] = false;
            }
        }

        return $students;
    }

    public static function getCompanyStudents($id){
        $students = Student::where('company_id', $id)->where('active', 1)->get();

        return $students;
    }

    public static function getBilledStudent($id){
        $students = Student::select('students.*')
            ->join('registrations', 'registrations.student_id', '=', 'students.id')
            ->where('registrations.billing_id', $id)->get();

        return $students;
    }

    public static function findDni($dni, $id = null){
        $student = Student::where('dni', $dni);
        if ($id){
            $student = $student->where('id', '!=', $id);
        }
        $student = $student->first();

        return $student;
    }

    public static function findUser($user, $id = null){
        $student = Student::where('user', $user);
        if ($id){
            $student = $student->where('id', '!=', $id);
        }
        $student = $student->first();

        return $student;
    }

    /**
     * @param $id
     * @return Student
     */
    public static function getStudentCSV($name = null, $surname = null, $dni = null, $telephone = null, $email = null, $company = null){
        $students = Student::select('students.*',
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

        if ($name) {
            $students = $students->where('students.name', 'like', '%'.$name.'%');
        }
        if ($surname) {
            $students = $students->where('students.surname', 'like', '%'.$surname.'&');
        }
        if ($dni) {
            $students = $students->where('students.dni', 'like', '%'.$dni.'%');
        }
        if ($telephone) {
            $students = $students->where('students.telephone', 'like', '%'.$dni.'%');
        }
        if ($email) {
            $students = $students->where('students.email', 'like', '%'.$email.'%');
        }
        if ($company) {
            $students = $students->where('companies.name', 'like', '%'.$company.'%');
        }

        $students = $students->get();

        $data = [];
        foreach ($students as $student) {
            $element = [
                'Nombre' => $student['name'],
                'Apellidos' => $student['label'],
                'DNI' => $student['dni']
            ];
            $data[] = $element;
        }
        return $data;
    }
}
