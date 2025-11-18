<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TrainingContractsExcludedDay extends Model
{
	use HasFactory;

    public $timestamps = true;

    protected $fillable = ['training_contract_id','excluded_day_type_id', 'day', 'description', 'group', 'valid', 'main_company_id'];

    public static function createExcludedDay($data){
        $trainingContract = TrainingContract::where('id', $data['training_contract_id'])->first();
        // vemos otros grupos para obtener el número de grupo más alto
        $other_groups = TrainingContractsExcludedDay::where('training_contract_id', $trainingContract->id)
            ->where('main_company_id', $data['main_company_id'])
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
            $festival = TrainingContractFestival::existDay($start, $trainingContract->id, $data['main_company_id'])->first();
            if (!$festival) {
                $working_day = false;
                switch($start->dayOfWeek){
                    case 0:
                        if ($trainingContract->sunday == 1){
                            $working_day = true;
                        }
                        break;
                    case 1:
                        if ($trainingContract->monday == 1){
                            $working_day = true;
                        }
                        break;
                    case 2:
                        if ($trainingContract->tuesday == 1){
                            $working_day = true;
                        }
                        break;
                    case 3:
                        if ($trainingContract->wednesday == 1){
                            $working_day = true;
                        }
                        break;
                    case 4:
                        if ($trainingContract->thursday == 1){
                            $working_day = true;
                        }
                        break;
                    case 5:
                        if ($trainingContract->friday == 1){
                            $working_day = true;
                        }
                        break;
                    case 6:
                        if ($trainingContract->saturday == 1){
                            $working_day = true;
                        }
                        break;
                }
            }
            if ($working_day) {
                $excluded_day = TrainingContractsExcludedDay::where('training_contract_id', $trainingContract->id)
                    ->where('main_company_id', $data['main_company_id'])
                    ->where('day', $start)->first();
                if ($excluded_day) {
                    $excluded_day->update([
                        'excluded_day_type_id' => $data['excluded_day_type_id'],
                        'group' => $group
                    ]);
                } else {
                    TrainingContractsExcludedDay::create([
                        'day' => $start->toDateString(),
                        'training_contract_id' => $trainingContract->id,
                        'excluded_day_type_id' => $data['excluded_day_type_id'],
                        'group' => $group,
                        'valid' => 1,
                        'main_company_id' => $data['main_company_id'],
                    ]);
                }
            } else if ($start->toDateString() === Carbon::parse($data['beginning'])->toDateString() || $start->toDateString() === $end->toDateString()) {
                TrainingContractsExcludedDay::create([
                    'day' => $start->toDateString(),
                    'training_contract_id' => $trainingContract->id,
                    'excluded_day_type_id' => $data['excluded_day_type_id'],
                    'group' => $group,
                    'valid' => 0,
                    'main_company_id' => $data['main_company_id'],
                ]);
            }
            $count++;
            $start->addDay();
        }

        return true;
    }

    public static function nonWorkingDay($trainingContractId, $date, $mainCompanyId){
        $trainingContract_excluded = TrainingContractsExcludedDay::where('training_contract_id', $trainingContractId)
            ->where('day', $date)
            ->where('valid', 1)
            ->where('main_company_id', $mainCompanyId)
            ->first();

        // Usar el método existDay del modelo TrainingContractFestival
        $trainingContract_festival = TrainingContractFestival::existDay($date, $trainingContractId, $mainCompanyId)->first();



        if ($trainingContract_excluded || $trainingContract_festival){
            return true;
        }

        $trainingContract = TrainingContract::where('id', $trainingContractId)
            ->FilterMainCompany($mainCompanyId)
            ->first();



        // Verificar si el día es un día laborable según el contrato de formación
        $dayOfWeek = Carbon::parse($date)->dayOfWeek;
        $workingDays = [
            $trainingContract->sunday, // 0: Sunday
            $trainingContract->monday, // 1: Monday
            $trainingContract->tuesday, // 2: Tuesday
            $trainingContract->wednesday, // 3: Wednesday
            $trainingContract->thursday, // 4: Thursday
            $trainingContract->friday, // 5: Friday
            $trainingContract->saturday, // 6: Saturday
        ];

        return $workingDays[$dayOfWeek] == 0; // Devuelve true si el día no es un día laborable
    }

    public function scopeSameGroup($query, $trainingContractId, $mainCompanyId) {
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
            ->where('training_contracts_excluded_days.main_company_id', $mainCompanyId)
            ->groupBy('group');
    }

    public function scopeFilterMainCompany($query, $mainCompanyId) {
        return $query->where('training_contracts_excluded_days.main_company_id', $mainCompanyId);
    }
}
