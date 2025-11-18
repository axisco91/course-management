<?php

namespace App\Models;

use App\Services\PotentialStudentService;
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
        'comment',
        'main_company_id'];

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

    public function scopeFilterMainCompany($query, $mainCompanyId) {
        return $query->where('potential_students.main_company_id', $mainCompanyId);
    }

    /**
     * Get all students
     */
    public static function getPotentialStudents($mainCompanyId){
        return PotentialStudent::select('potential_students.*', 'level_studies.name as level_study',
            'professional_categories.name as professional_category', 'provinces.name as province')
            ->leftjoin('level_studies', 'level_studies.id', '=', 'potential_students.level_study_id')
            ->leftjoin('professional_categories', 'professional_categories.id', '=', 'potential_students.professional_category_id')
            ->leftjoin('provinces', 'provinces.id', '=', 'potential_students.province_id')
            ->where('converted', 0)
            ->orderBy('potential_students.name','asc')
            ->where('potential_students.main_company_id', $mainCompanyId)
            ->get();
    }

    public static function getPotentialStudent($id, $mainCompanyId){
        return PotentialStudent::select('potential_students.*', 'level_studies.name as level_study',
            'professional_categories.name as professional_category', 'provinces.name as province')
            ->leftjoin('level_studies', 'level_studies.id', '=', 'potential_students.level_study_id')
            ->leftjoin('professional_categories', 'professional_categories.id', '=', 'potential_students.professional_category_id')
            ->leftjoin('provinces', 'provinces.id', '=', 'potential_students.province_id')
            ->where('potential_students.id', $id)
            ->where('potential_students.main_company_id', $mainCompanyId)
            ->first();
    }

    public static function findDni($dni, $mainCompanyId, $id = null){
        $student = Student::where('dni', $dni)
            ->where('main_company_id', $mainCompanyId);
        if ($id){
            $student = $student->where('id', '!=', $id);
        }
        $student = $student->first();
        if (!isset($student)){
            $student = PotentialStudent::where('dni', $dni)
                ->where('main_company_id', $mainCompanyId)
                ->first();
        }

        return $student;
    }

    public static function createWithService($data)
    {
        $service = app(PotentialStudentService::class);
        return $service->create($data);
    }

    public function convertPotentialStudent(){
        $service = app(PotentialStudentService::class);
        return $service->converted($this);
    }
}
