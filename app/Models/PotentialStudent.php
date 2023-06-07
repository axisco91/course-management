<?php

namespace App\Models;

use Carbon\Carbon;
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
     * Get all students
     */
    public static function getPotentialStudents(){
        $students = PotentialStudent::select('potential_students.*', 'level_studies.name as level_study',
            'professional_categories.name as professional_category', 'provinces.name as province')
            ->leftjoin('level_studies', 'level_studies.id', '=', 'potential_students.level_study_id')
            ->leftjoin('professional_categories', 'professional_categories.id', '=', 'potential_students.professional_category_id')
            ->leftjoin('provinces', 'provinces.id', '=', 'potential_students.province_id')
            ->where('converted', 0)
            ->orderBy('potential_students.name','asc')
            ->get();
        return $students;
    }

    public static function getPotentialStudent($id){
        $student = PotentialStudent::select('potential_students.*', 'level_studies.name as level_study',
            'professional_categories.name as professional_category', 'provinces.name as province')
            ->leftjoin('level_studies', 'level_studies.id', '=', 'potential_students.level_study_id')
            ->leftjoin('professional_categories', 'professional_categories.id', '=', 'potential_students.professional_category_id')
            ->leftjoin('provinces', 'provinces.id', '=', 'potential_students.province_id')
            ->where('potential_students.id', $id)
            ->first();
        return $student;
    }

    /**
     * @param $data
     * @return Create Student
     */
    public static function createPotentialStudent($data){

        $student = PotentialStudent::create([
            'name' => $data['name'],
            'surname' => $data['surname'],
            'dni' => $data['dni'],
            'telephone' => $data['telephone'],
            'email' => $data['email'],
            'company_name' => $data['company_name'],
            'date_of_birth' => $data['date_of_birth'] ? Carbon::parse($data['date_of_birth']) : null,
            'level_study_id' => $data['level_study_id'],
            'disabled' => $data['disabled'],
            'social_security_number' => $data['social_security_number'],
            'professional_category_id' => $data['professional_category_id'],
            'direction' => $data['direction'],
            'post_code' => $data['post_code'],
            'province_id' => $data['province_id'],
            'population' => $data['population'],
            'training_action_id' => $data['training_action_id'],
            'professional_family_id' => $data['professional_family_id'],
            'professional_area_id' => $data['professional_area_id'],
            'converted' => 0,
            'comment' => $data['comment']
        ]);

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
