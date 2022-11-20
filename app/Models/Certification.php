<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certification extends Model
{
	use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name', 'total_hours', 'code', 'professional_family_id', 'professional_area_id', 'level'];

        public function trainingContracts()
    {
        return $this->belongsToMany(TrainingContract::class,'training_contract_elements');
    }

    public static function getCertifications($keyWord){
        $certifications = Certification::select('*');
        $certifications = $certifications->where(function ($query) use ($keyWord){
            $query->orWhere('name', 'LIKE', $keyWord)
                ->orWhere('total_hours', 'LIKE', $keyWord);
        });
        $certifications = $certifications->paginate(10);
        return $certifications;
    }

    public static function createCertification($data, $id){
        $certification = Certification::find($id);
        if ($certification){
            $certification->update([
                'code' => $data['code'],
                'name' => $data['name'],
                'professional_family_id' => $data['professional_family_id'],
                'professional_area_id' => $data['professional_area_id'],
                'level' => $data['level'],
            ]);
        }
        return $certification;
    }

    public static function updateCertification($id, $data){
        $certification = Certification::find($id);
        if ($certification){
            $certification->update([
                'code' => $data['code'],
                'name' => $data['name'],
                'professional_family_id' => $data['professional_family_id'],
                'professional_area_id' => $data['professional_area_id'],
                'level' => $data['level'],
            ]);
        }
        return $certification;
    }

    public static function getCertificationsNotinTrainingContract($id){
        $certifications = Certification::where('active', 1)->get();
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
