<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingUnit extends Model
{
	use HasFactory;

    protected $fillable = ['name', 'total_hours', 'active', 'face_to_face_hours', 'tutoring_hours', 'teletraining_hours', 'exam_hours', 'formative_unit'];

    public function modules()
    {
        return $this->belongsToMany(Modules::class,'training_units_modules');
    }

    public function certifications()
    {
        return $this->belongsToMany(Certification::class,'certification_elements');
    }

    public function getTrainingUnits($keyWord,$inactiveFilter){
            $training_units = TrainingUnit::select('*');
        $training_units = $training_units->where(function ($query) use ($keyWord){
            $query->orWhere('name', 'LIKE', $keyWord)
                ->orWhere('total_hours', 'LIKE', $keyWord);
        });
        $training_units = $training_units->paginate(10);
        return $training_units;
    }

    public function createTrainingUnit($data){

    }

    public function updateTrainingUnit($id, $data){
        $training_unit = TrainingUnit::find($id);

        $face_to_face_hours = $data['exam_hours'] + $data['tutoring_hours'];
        $total_hours = $face_to_face_hours + $data['teletraining_hours'];
        if ($training_unit){
            if ($total_hours != $training_unit->total_hours){
                $models = $training_unit->models()->get();
                $exam_difference = $data['exam_hours'] - $training_unit->exam_hours;
                $tutoring_difference = $data['tutoring_hours'] - $training_unit->tutoring_hours;
                $teletraining_difference = $data['teletraining_hours'] - $training_unit->teletraining_hours;
                foreach ($models as $model){
                    $model->updateHours($exam_difference, $tutoring_difference, $teletraining_difference);
                }
                $certifications = $training_unit->certifications()->get();
                foreach ($certifications as $certification){
                    $certification->updateHours($exam_difference, $tutoring_difference, $teletraining_difference);
                }
            }
            $training_unit->update([
                'formative_unit' => $data['formative_unit'],
                'name' => $data['name'],
                'active' => $data['active'],
                'exam_hours' => $data['exam_hours'],
                'tutoring_hours' => $data['tutoring_hours'],
                'teletraining_hours' => $data['teletraining_hours'],
                'face_to_face_hours' => $face_to_face_hours,
                'total_hours' => $total_hours
            ]);
        }
        return $training_unit;
    }

    public function getProviderTrainingUnit($id, $search_training_units_name, $search_training_units_action)
    {
        $training_units = TrainingUnit::where('provider_id', $id)
            ->where(function ($query) use ($search_training_units_name) {
                $query->orWhere('name', 'LIKE', $search_training_units_name);
            })->where(function ($query) use ($search_training_units_action) {
                $query->orWhere('formative_unit', 'LIKE', $search_training_units_action);
            })->get();

        return $training_units;
    }

    public static function getTrainingUnitsNotInModule($module_id){
        $training_units = TrainingUnit::leftjoin('training_units_modules', 'training_units_modules.training_unit_id', 'training_units.id')
            ->where('training_units_modules.module_id', $module_id)->get();
        $not_in_module = TrainingUnit::where('active', 1)->get();
        $not_in_module = $not_in_module->whereNotIn('id', $training_units->pluck('training_units.id'));
        return $not_in_module;
    }

    public static function getTrainingUnitsNotInCertification($certification_id){
        $training_units = TrainingUnit::leftjoin('certification_elements', 'certification_elements.training_unit_id', 'training_units.id')
            ->where('certification_elements.certification_id', $certification_id)->get();
        $not_in_certification = TrainingUnit::where('active', 1)->get();
        $not_in_certification = $not_in_certification->whereNotIn('id', $training_units->pluck('modules.id'));
        return $not_in_certification;
    }
}
