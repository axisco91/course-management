<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
	use HasFactory;

    public $timestamps = true;

    protected $fillable = ['name','surname','dni','telephone','email','company_id','user', 'password','date_of_birth','level_study_id','disabled','social_security_number','c_quote','quote_group_id','professional_category_id','annual_gross_salary','annual_hours','hourly_cost_worker_gross','direction','post_code','population_id','province_id','population','observation','iban', 'active'];

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
    public function createStudent($data){

        $student = Student::create([
            'name' => $data['name'],
            'surname' => $data['surname'],
            'dni' => $data['dni'],
            'telephone' => $data['telephone'],
            'email' => $data['email'],
            'company_id' => $data['company_id'],
            'user' => $data['user'],
            'password' => $data['password'],
            'date_of_birth' => $data['date_of_birth'],
            'level_study_id' => $data['level_study_id'],
            'disabled' => $data['disabled'] == true ? 1 : 0,
            'social_security_number' => $data['social_security_number'],
            'c_quote' => $data['c_quote'],
            'quote_group_id' => $data['quote_group_id'],
            'professional_category_id' => $data['professional_category_id'],
            'annual_gross_salary' => $data['annual_gross_salary'],
            'annual_hours' => $data['annual_hours'],
            'hourly_cost_worker_gross' => $data['hourly_cost_worker_gross'],
            'direction' => $data['direction'],
            'post_code' => $data['post_code'],
            'province_id' => $data['province_id'],
            'population' => $data['population'],
            'observation' => $data['observation'],
            'iban' => $data['iban']
        ]);

        return $student;
    }

    /**
     * Update Student
     */
    public function updateStudent($id, $data){

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
            'date_of_birth' => $data['date_of_birth'],
            'level_study_id' => $data['level_study_id'],
            'disabled' => $data['disabled'] == true ? 1 : 0,
            'social_security_number' => $data['social_security_number'],
            'c_quote' => $data['c_quote'],
            'quote_group_id' => $data['quote_group_id'],
            'professional_category_id' => $data['professional_category_id'],
            'annual_gross_salary' => $data['annual_gross_salary'],
            'annual_hours' => $data['annual_hours'],
            'hourly_cost_worker_gross' => $data['hourly_cost_worker_gross'],
            'direction' => $data['direction'],
            'post_code' => $data['post_code'],
            'province_id' => $data['province_id'],
            'population' => $data['population'],
            'observation' => $data['observation'],
            'iban' => $data['iban']
        ]);

        return $student;
    }

    /**
     * @param $id
     * @return Student
     */
    public function getStudent($id){
        $student = Student::findOrFail($id);

        return $student;
    }

    /**
     * Update active or inactive
     */
    public function activeInactive($id, $state){
        $student = Student::findOrFail($id);
        $student->update([
            'active' => $state
        ]);
        return $student;
    }

    /**
     * Get all students
     */
    public function getStudents($keyWord, $active, $search_name, $search_surname, $search_email, $search_dni, $search_telephone, $search_company){
        $students = Student::select('students.*', 'companies.name as company', 'level_studies.name as level_study',
            'professional_categories.name as professional_category', 'provinces.name as province', 'quote_groups.name as quote_group')
            ->leftjoin('companies', 'companies.id', '=', 'students.company_id')
            ->leftjoin('level_studies', 'level_studies.id', '=', 'students.level_study_id')
            ->leftjoin('professional_categories', 'professional_categories.id', '=', 'students.professional_category_id')
            ->leftjoin('provinces', 'provinces.id', '=', 'students.province_id')
            ->leftjoin('quote_groups', 'quote_groups.id', '=', 'students.quote_group_id');
        if ($active != 1) {
            $students = $students->where('students.active', 1);
        }
        $students = $students->where(function ($query) use ($keyWord){
            $query->orWhere('students.name', 'LIKE', $keyWord)
                ->orWhere('surname', 'LIKE', $keyWord)
                ->orWhere('dni', 'LIKE', $keyWord)
                ->orWhere('companies.name', 'LIKE', $keyWord)
                ->orWhere('students.telephone', 'LIKE', $keyWord)
                ->orWhere('students.email', 'LIKE', $keyWord)
                ->orWhere('user', 'LIKE', $keyWord)
                ->orWhere('date_of_birth', 'LIKE', $keyWord)
                ->orWhere('level_studies.name', 'LIKE', $keyWord)
                ->orWhere('disabled', 'LIKE', $keyWord)
                ->orWhere('social_security_number', 'LIKE', $keyWord)
                ->orWhere('c_quote', 'LIKE', $keyWord)
                ->orWhere('quote_groups.name', 'LIKE', $keyWord)
                ->orWhere('professional_categories.name', 'LIKE', $keyWord)
                ->orWhere('annual_gross_salary', 'LIKE', $keyWord)
                ->orWhere('annual_hours', 'LIKE', $keyWord)
                ->orWhere('hourly_cost_worker_gross', 'LIKE', $keyWord)
                ->orWhere('direction', 'LIKE', $keyWord)
                ->orWhere('students.post_code', 'LIKE', $keyWord)
                ->orWhere('provinces.name', 'LIKE', $keyWord)
                ->orWhere('students.population', 'LIKE', $keyWord)
                ->orWhere('students.iban', 'LIKE', $keyWord);
        })->where(function ($query) use ($search_name){
            $query->orWhere('students.name', 'LIKE', $search_name);
        })->where(function ($query) use ($search_surname){
            $query->orWhere('surname', 'LIKE', $search_surname);
        })->where(function ($query) use ($search_email){
            $query->orWhere('students.email', 'LIKE', $search_email);
        })->where(function ($query) use ($search_dni){
            $query->orWhere('dni', 'LIKE', $search_dni);
        })->where(function ($query) use ($search_telephone){
            $query->orWhere('students.telephone', 'LIKE', $search_telephone);
        })->where(function ($query) use ($search_company){
                $query->orWhere('companies.name', 'LIKE', $search_company);
        })->orderBy('students.name','asc')
            ->paginate(10);
        return $students;
    }

    public function getCompanyStudents($id, $search_student_name, $search_student_surname){
        $students = Student::where('company_id', $id)
            ->where(function ($query) use ($search_student_name){
                $query->orWhere('students.name', 'LIKE', $search_student_name);
            })->where(function ($query) use ($search_student_surname){
                $query->orWhere('students.surname', 'LIKE', $search_student_surname);
            })->get();

        return $students;
    }

    public function getBilledStudent($id, $search_student_name, $search_student_surname){
        $students = Student::select('students.*')
            ->join('registrations', 'registrations.student_id', '=', 'students.id')
            ->where('registrations.billing_id', $id)
            ->where(function ($query) use ($search_student_name){
                $query->orWhere('students.name', 'LIKE', $search_student_name);
            })->where(function ($query) use ($search_student_surname){
                $query->orWhere('students.surname', 'LIKE', $search_student_surname);
            })->get();

        return $students;
    }

    public function findDni($dni, $id = null){
        $student = Student::where('dni', $dni);
        if ($id){
            $student = $student->where('id', '!=', $id);
        }
        $student = $student->first();

        return $student;
    }

    public function findUser($user, $id = null){
        $student = Student::where('user', $user);
        if ($id){
            $student = $student->where('id', '!=', $id);
        }
        $student = $student->first();

        return $student;
    }

}
