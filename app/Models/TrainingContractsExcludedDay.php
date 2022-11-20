<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingContractsExcludedDay extends Model
{
	use HasFactory;

    public $timestamps = true;

    protected $fillable = ['training_contract_id','excluded_day_id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function teacher()
    {
        return $this->hasOne('App\Models\Teacher', 'id', 'teacher_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function teacherArea()
    {
        return $this->hasOne('App\Models\TeacherArea', 'id', 'teacher_area_id');
    }

    public static function addGeneralDays($id, $start, $end){
        $excluded_days = ExcludedDay::select('excluded_days.*')->where('general', 1)
            ->whereBetween('day', [$start, $end])->get();
        if ($excluded_days){
            foreach ($excluded_days as $excluded_day){
                $excluded = TrainingContractsExcludedDay::where('training_contract_id', $id)
                    ->where('excluded_day_id', $excluded_day->id)->first();
                if (!$excluded){
                    TrainingContractsExcludedDay::create([
                        'training_contract_id' => $id,
                        'excluded_day_id' => $excluded_day->id
                    ]);
                }
            }
        }
        return true;
    }

    public static function createTrainingContractExcludedDay($training_contract, $id, $type, $start, $end){
        if ($type == 'province'){
            $provinces = Province::find($id);
            $excluded_days = $provinces->excludedDays();
        } else if ($type == 'group'){
            $groups = BankHolidayGroup::find($id);
            $excluded_days = $groups->excludedDays();
        }
        if ($start && $end){
            $excluded_days = $excluded_days->whereBetween('day', [$start, $end]);
        } else if($start){
            $excluded_days = $excluded_days->whereDate('day', '>=', $start);
        } else if ($end){
            $excluded_days = $excluded_days->whereDate('day', '<=', $end);
        }
        $excluded_days = $excluded_days->get();
        if ($excluded_days){
            foreach ($excluded_days as $excluded_day){
                $training_contract_excluded = TrainingContractsExcludedDay::where('training_contract_id', $training_contract)
                    ->where('excluded_day_id', $excluded_day->id)->first();
                if (!$training_contract_excluded){
                    TrainingContractsExcludedDay::create([
                        'training_contract_id' => $training_contract,
                        'excluded_day_id' => $excluded_day->id
                    ]);
                }
            }
        }
        return true;
    }
}
