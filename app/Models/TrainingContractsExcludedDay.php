<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TrainingContractsExcludedDay extends Model
{
	use HasFactory;

    public $timestamps = true;

    protected $fillable = ['training_contract_id','excluded_day_type_id', 'day', 'description', 'group', 'valid'];

    public static function createExcludedDay($data){
        $training_contract = TrainingContract::where('id', $data['training_contract_id'])->first();
        // vemos otros grupos para obtener el número de grupo más alto
        $other_groups = TrainingContractsExcludedDay::where('training_contract_id', $training_contract->id)
            ->orderBy('group', 'desc')
            ->first();
        $start = Carbon::parse($data['beginning']);
        $end = Carbon::parse($data['end']);
        $group = 1;
        if ($other_groups) {
            $group = $other_groups->group + 1;
        }
        $count = 0;
        while ($start <= $end) {
            $festival = TrainingContractFestival::existDay($start, $training_contract->id)->first();
            if (!$festival) {
                $working_day = false;
                switch($start->dayOfWeek){
                    case 0:
                        if ($training_contract->sunday == 1){
                            $working_day = true;
                        }
                        break;
                    case 1:
                        if ($training_contract->monday == 1){
                            $working_day = true;
                        }
                        break;
                    case 2:
                        if ($training_contract->tuesday == 1){
                            $working_day = true;
                        }
                        break;
                    case 3:
                        if ($training_contract->wednesday == 1){
                            $working_day = true;
                        }
                        break;
                    case 4:
                        if ($training_contract->thursday == 1){
                            $working_day = true;
                        }
                        break;
                    case 5:
                        if ($training_contract->friday == 1){
                            $working_day = true;
                        }
                        break;
                    case 6:
                        if ($training_contract->saturday == 1){
                            $working_day = true;
                        }
                        break;
                }
            }
            if ($working_day) {
                $excluded_day = TrainingContractsExcludedDay::where('training_contract_id', $training_contract->id)
                    ->where('day', $start)->first();
                if ($excluded_day) {
                    $excluded_day->update([
                        'excluded_day_type_id' => $data['excluded_day_type_id'],
                        'group' => $group
                    ]);
                } else {
                    TrainingContractsExcludedDay::create([
                        'day' => $start->toDateString(),
                        'training_contract_id' => $training_contract->id,
                        'excluded_day_type_id' => $data['excluded_day_type_id'],
                        'group' => $group,
                        'valid' => 1
                    ]);
                }
            } else if ($start->toDateString() === Carbon::parse($data['beginning'])->toDateString() || $start->toDateString() === $end->toDateString()) {
                TrainingContractsExcludedDay::create([
                    'day' => $start->toDateString(),
                    'training_contract_id' => $training_contract->id,
                    'excluded_day_type_id' => $data['excluded_day_type_id'],
                    'group' => $group,
                    'valid' => 0
                ]);
            }
            $count++;
            $start->addDay();
        }

        return true;
    }

    public static function nonWorkingDay($training_contract_id, $date){
        $training_contract_excluded = TrainingContractsExcludedDay::where('training_contract_id', $training_contract_id)->where('day', $date)
        ->first();
        if ($training_contract_excluded){
            return true;
        }
        return false;
    }

    public function scopeSameGroup($query, $trainingContractId) {
        return $query->select('group', DB::raw("CONCAT(
                excluded_day_types.name, ' ',
                DATE_FORMAT(MIN(training_contracts_excluded_days.day), '%e/%c/%Y'), ' - ',
                DATE_FORMAT(MAX(training_contracts_excluded_days.day), '%e/%c/%Y'),
                ' Número de dias: ',
                SUM(CASE WHEN training_contracts_excluded_days.valid = 1 THEN 1 ELSE 0 END),
                ' / ',
                DATEDIFF(MAX(training_contracts_excluded_days.day), MIN(training_contracts_excluded_days.day)) + 1
            ) as name"))
            ->join('excluded_day_types', 'excluded_day_types.id', '=', 'training_contracts_excluded_days.excluded_day_type_id')
            ->where('training_contract_id', $trainingContractId)
            ->groupBy('group');
    }
}
