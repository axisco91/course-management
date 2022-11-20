<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PotentialStudent extends Model
{
	use HasFactory;

    public $timestamps = true;

    protected $fillable = ['name',
        'surname',
        'dni',
        'telephone',
        'email',
        'company_name',
        'date_of_birth',
        'level_study_id',
        'disabled',
        'social_security_number',
        'professional_category_id',
        'direction',
        'post_code',
        'population_id',
        'province_id',
        'population',
        'training_action_id',
        'professional_family_id',
        'professional_area_id',
        'converted',
        'comment'];

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
     * @param $id
     * @return Student
     */
    public static function getPotentialStudent($id){
        $student = PotentialStudent::find($id);

        return $student;
    }

    /**
     * Get all students
     */
    public static function getPotentialStudents($keyWord, $search_name, $search_surname, $search_email, $search_dni, $search_telephone){
        $students = PotentialStudent::select('potential_students.*', 'level_studies.name as level_study',
            'professional_categories.name as professional_category', 'provinces.name as province')
            ->leftjoin('level_studies', 'level_studies.id', '=', 'potential_students.level_study_id')
            ->leftjoin('professional_categories', 'professional_categories.id', '=', 'potential_students.professional_category_id')
            ->leftjoin('provinces', 'provinces.id', '=', 'potential_students.province_id')
            ->where('converted', 0);

        $students = $students->where(function ($query) use ($keyWord){
            $query->orWhere('potential_students.name', 'LIKE', $keyWord)
                ->orWhere('surname', 'LIKE', $keyWord)
                ->orWhere('dni', 'LIKE', $keyWord)
                ->orWhere('potential_students.telephone', 'LIKE', $keyWord)
                ->orWhere('potential_students.email', 'LIKE', $keyWord)
                ->orWhere('date_of_birth', 'LIKE', $keyWord)
                ->orWhere('level_studies.name', 'LIKE', $keyWord)
                ->orWhere('disabled', 'LIKE', $keyWord)
                ->orWhere('social_security_number', 'LIKE', $keyWord)
                ->orWhere('professional_categories.name', 'LIKE', $keyWord)
                ->orWhere('direction', 'LIKE', $keyWord)
                ->orWhere('potential_students.post_code', 'LIKE', $keyWord)
                ->orWhere('provinces.name', 'LIKE', $keyWord)
                ->orWhere('potential_students.population', 'LIKE', $keyWord);
        })->where(function ($query) use ($search_name){
            $query->orWhere('potential_students.name', 'LIKE', $search_name);
        })->where(function ($query) use ($search_surname){
            $query->orWhere('surname', 'LIKE', $search_surname);
        })->where(function ($query) use ($search_email){
            $query->orWhere('potential_students.email', 'LIKE', $search_email);
        })->where(function ($query) use ($search_dni){
            $query->orWhere('dni', 'LIKE', $search_dni);
        })->where(function ($query) use ($search_telephone){
            $query->orWhere('potential_students.telephone', 'LIKE', $search_telephone);
        });

        $students = $students->orderBy('potential_students.name','asc')
            ->paginate(10);
        return $students;
    }

    /**
     * @param $data
     * @return Create Student
     */
    public static function createPotentialStudent($data){

        $student = PotentialStudent::create($data);

        return $student;
    }

    public static function convertPotentialStudent($id){
        $student = PotentialStudent::find($id);
        $student->update([
            'converted' => 1
        ]);
        return $student;
    }

    public static function findDni($dni, $id = null){
        $student = Student::where('dni', $dni);
        if ($id){
            $student = $student->where('id', '!=', $id);
        }
        $student = $student->first();
        if (!isset($student)){
            $student = PotentialStudent::where('dni', $dni)->first();
        }

        return $student;
    }
}
