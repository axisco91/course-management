<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Certification extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name', 'total_hours', 'code', 'professional_family_id', 'professional_area_id', 'level', 'face_to_face_hours', 'teletraining_hours', 'main_company_id'];

    public function trainingContracts()
    {
        return $this->belongsToMany(TrainingContract::class,'training_contract_elements');
    }

    public function scopeFilterMainCompany($query, $mainCompanyId) {
        return $query->where('certifications.main_company_id', $mainCompanyId);
    }

    public static function getCertifications($mainCompanyId){
        $certifications = Certification::select('certifications.*',
            'professional_families.name as family',
            'professional_areas.name as area'
        )
            ->leftjoin('professional_families', 'professional_families.id', '=', 'certifications.professional_family_id')
            ->leftjoin('professional_areas', 'professional_areas.id', '=', 'certifications.professional_area_id')
            ->where('certifications.main_company_id', $mainCompanyId)
            ->get();
        foreach ($certifications as $certification) {
            $element = TrainingContractElement::where('certification_id', $certification->id)->first();
            if ($element) {
                $certification['used'] = true;
            } else {
                $certification['used'] = false;
            }
        }
        return $certifications;
    }

    public static function getCertification($id, $mainCompanyId){
        $certification = Certification::select('certifications.*',
            'professional_families.name as family',
            'professional_areas.name as area'
            )
            ->leftjoin('professional_families', 'professional_families.id', '=', 'certifications.professional_family_id')
            ->leftjoin('professional_areas', 'professional_areas.id', '=', 'certifications.professional_area_id')
            ->where('certifications.id', $id)
            ->where('certifications.main_company_id', $mainCompanyId)
            ->first();
        $element = TrainingContractElement::where('certification_id', $certification->id)->first();
        if ($element) {
            $certification['used'] = true;
        } else {
            $certification['used'] = false;
        }
        return $certification;
    }

    public static function createCertification($data){
        $certification = Certification::create([
            'code' => $data['code'],
            'name' => $data['name'],
            'professional_family_id' => $data['professional_family_id'],
            'professional_area_id' => $data['professional_area_id'],
            'level' => $data['level'],
            'active' => $data['active'],
        ]);
        return $certification;
    }

    public static function updateCertification($id, $data){
        $certification = Certification::where('id', $id)
            ->FilterMainCompany($data['main_company_id'])
            ->first();

        if ($certification){
            $certification->update([
                'code' => $data['code'],
                'name' => $data['name'],
                'professional_family_id' => $data['professional_family_id'],
                'professional_area_id' => $data['professional_area_id'],
                'level' => $data['level'],
                'active' => $data['active'],
            ]);
        }
        return $certification;
    }

    public static function getCertificationsNotinTrainingContract($id, $mainCompanyId){
        $trainingContracts_certifications = TrainingContractElement::where('training_contract_elements.training_contract_id', $id)
            ->whereNotNull('certification_id')
            ->where('training_contract_elements.main_company_id', $mainCompanyId)
            ->pluck('certification_id');
        $certifications = Certification::select('certifications.*', 'certifications.id as value', DB::raw("CONCAT(certifications.name,' (', certifications.total_hours,' horas)') as label"))
            ->whereNotIn('id', $trainingContracts_certifications)
            ->where('active', 1)
            ->where('certifications.main_company_id', $mainCompanyId)
            ->get();
        return $certifications;
    }

    public function updateHours($exam_difference, $tutoring_difference, $teletraining_difference){
        $total_hours = $this->total_hours + $exam_difference + $tutoring_difference + $teletraining_difference;
        $certifications = $this->certifications()->get();
        foreach ($certifications as $certification){
            $certification->updateHours($exam_difference, $tutoring_difference, $teletraining_difference);
        }
        $this->update([
            'total_hours' => $total_hours
        ]);
    }
}
