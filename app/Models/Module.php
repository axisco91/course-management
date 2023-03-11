<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name', 'total_hours', 'active', 'face_to_face_hours', 'tutoring_hours', 'teletraining_hours', 'exam_hours', 'formative_module'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function trainingUnits()
    {
        return $this->belongsToMany(TrainingUnit::class,'training_units_modules', 'module_id', 'training_unit_id');
    }

    public function certifications()
    {
        return $this->belongsToMany(Certification::class,'certification_elements');
    }

    public static function getModules(){
        $modules = Module::select('*')->get();
        return $modules;
    }

    public static function createModule($data){
        $face_to_face_hours = $data['exam_hours'] + $data['tutoring_hours'];
        $total_hours = $face_to_face_hours + $data['teletraining_hours'];
        $module = Module::create([
            'formative_module' => $data['formative_module'],
            'name' => $data['name'],
            'exam_hours' => $data['exam_hours'],
            'tutoring_hours' => $data['tutoring_hours'],
            'face_to_face_hours' => $face_to_face_hours,
            'teletraining_hours' => $data['teletraining_hours'],
            'total_hours' => $total_hours
        ]);

        if ($data['active'] != '') {
            $module->update([
                'active' => $data['active'],
            ]);
        }

        return $module;
    }

    public static function updateModule($id, $data){
        $module = Module::find($id);

        if ($module){
            $face_to_face_hours = $data['exam_hours'] + $data['tutoring_hours'];
            $total_hours = $face_to_face_hours + $data['teletraining_hours'];
            if ($total_hours != $module->total_hours){

            }
            $module->update([
                'formative_module' => $data['formative_module'],
                'name' => $data['name'],
                'exam_hours' => $data['exam_hours'],
                'tutoring_hours' => $data['tutoring_hours'],
                'face_to_face_hours' => $face_to_face_hours,
                'teletraining_hours' => $data['teletraining_hours'],
                'total_hours' => $total_hours
            ]);

            if ($data['active'] != '') {
                $module->update([
                    'active' => $data['active'],
                ]);
            }

        }
        return $module;
    }

    public static function getModulesNotInCertification($certification_id){
        $module = Module::leftjoin('certification_elements', 'certification_elements.module_id', 'modules.id')
            ->where('certification_elements.certification_id', $certification_id)->get();
        $not_in_certification = Module::where('active', 1)->get();
        $not_in_certification = $not_in_certification->whereNotIn('id', $module->pluck('modules.id'));
        return $not_in_certification;
    }

    public function updateHours($exam_difference, $tutoring_difference, $teletraining_difference){
        $exam_hours = $this->exam_hours + $exam_difference;
        $tutoring_hours = $this->tutoring_hours + $tutoring_difference;
        $face_to_face_hours = $this->face_to_face_hours + $exam_difference + $tutoring_difference;
        $teletraining_hours = $this->teletraining_hours + $teletraining_difference;
        $total_hours = $this->total_hours + $exam_difference + $tutoring_difference + $teletraining_difference;
        $certifications = $this->certifications()->get();
        foreach ($certifications as $certification){
            $certification->updateHours($exam_difference, $tutoring_difference, $teletraining_difference);
        }
        $this->update([
            'exam_hours' => $exam_hours,
            'tutoring_hours' => $tutoring_hours,
            'face_to_face_hours' => $face_to_face_hours,
            'teletraining_hours' => $teletraining_hours,
            'total_hours' => $total_hours
        ]);
    }
}
