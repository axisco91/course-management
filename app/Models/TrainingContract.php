<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Student;
use App\Models\TrainingContractExcludedDay;
use App\Models\TrainingContractElements;
use Illuminate\Support\Facades\Log;

class TrainingContract extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded = [];

    public function excludedDays(){
        return $this->belongsToMany(ExcludedDayType::class, 'training_contracts_excluded_days', 'training_contract_id', 'excluded_day_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function trainingContractElements()
    {
        return $this->hasMany(TrainingContractElement::class);
    }

    public function trainingContractFestivals()
    {
        return $this->hasMany(TrainingContractFestival::class);
    }

    public function trainingContractExcludedDays()
    {
        return $this->hasMany(TrainingContractsExcludedDay::class);
    }
    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }
    public function scopeGetTrainingContracts($query){
        return $query->select('training_contracts.*',
            'companies.name as company_name',
            'students.name as student_name',
            'students.surname as student_surname',
            'training_contract_statuses.name as training_contract_status',
            'providers.name as provider',
            'provinces.name as province',
            'on_leave_types.name as on_leave_type',
            'advisors.name as advisor',
            DB::raw("CONCAT(users.name,' ',users.surname) as collaborator"),
            DB::raw("CONCAT(students.name,' ',students.surname) as student"),
            'occupations.name as occupation')
            ->leftjoin('companies', 'companies.id', '=', 'training_contracts.company_id')
            ->leftjoin('students', 'students.id', '=', 'training_contracts.student_id')
            ->leftjoin('provinces', 'provinces.id', '=', 'training_contracts.province_id')
            ->leftjoin('providers', 'providers.id', '=', 'training_contracts.provider_id')
            ->leftjoin('training_contract_statuses', 'training_contract_statuses.id', '=', 'training_contracts.training_contract_status_id')
            ->leftjoin('on_leave_types', 'on_leave_types.id', '=', 'training_contracts.on_leave_type_id')
            ->leftjoin('advisors', 'advisors.id', '=', 'training_contracts.advisor_id')
            ->leftjoin('users', 'users.id', '=', 'training_contracts.collaborator_id')
            ->leftjoin('occupations', 'occupations.id', '=', 'training_contracts.occupation_id');
    }

    public static function createTrainingContract($data){
        $training = TrainingContract::orderBy('id', 'desc')->first();
        $id = $training['id']+1;
        if ($id < 10) {
            $number_cfa = '000'.$id;
        }
        else if ($id < 100) {
            $number_cfa = '00'.$id;
        }
        else if ($id < 1000) {
            $number_cfa = '0'.$id;
        } else {
            $number_cfa = $id;
        }

        $bonusYearOne =  $data['bonus_hours_first_year'] ? $data['bonus_hours_first_year'] : 0;
        $bonusYearTwo = $data['bonus_hours_second_year'] ? $data['bonus_hours_second_year'] : 0;

        $training_contract = TrainingContract::create([
            'number_cfa' => $number_cfa,
            'company_id' => $data['company_id'],
            'student_id' => $data['student_id'],
            'company_tutor' => $data['company_tutor'],
            'company_tutor_dni' => $data['company_tutor_dni'],
            'occupation_id' => $data['occupation_id'],
            'center_of_work' => $data['center_of_work'],
            'province_id' => $data['province_id'],
            'beginning' => $data['beginning'] ? Carbon::createFromFormat('d-m-Y', $data['beginning'])->format('Y-m-d') : null,
            'end' => $data['end'] ? Carbon::createFromFormat('d-m-Y', $data['end'])->format('Y-m-d') : null,
            'beginning_formation' => $data['beginning_formation'] ? Carbon::createFromFormat('d-m-Y', $data['beginning_formation'])->format('Y-m-d') : null,
            'end_formation' => $data['end_formation'] ? Carbon::createFromFormat('d-m-Y', $data['end_formation'])->format('Y-m-d') : null,
            'annually_day_hours' => $data['annually_day_hours'],
            'bonus_hours_first_year' =>  $bonusYearOne,
            'bonus_hours_second_year' => $bonusYearTwo,
            'training_schedule' => $data['training_schedule'],
            'working_hours' => $data['working_hours'],
            'complete_schedule' => $data['complete_schedule'],
            'training_contract_status_id' => $data['training_contract_status_id'],
            'on_leave_type_id' => $data['on_leave_type_id'],
            'on_leave_date' => $data['on_leave_date'] ? Carbon::createFromFormat('d-m-Y', $data['on_leave_date'])->format('Y-m-d') : null,
            'advisor_id' => $data['advisor_id'],
            'collaborator_id' => $data['collaborator_id'],
            'percentage_first_year' => $data['percentage_first_year'],
            'percentage_second_year' => $data['percentage_second_year'],
            'formative_hours_first_year' => $data['formative_hours_first_year'] ? $data['formative_hours_first_year'] : 0,
            'formative_hours_second_year' => $data['formative_hours_second_year'] ? $data['formative_hours_second_year'] : 0,
            'provider_id' => $data['provider_id'],
            'disabled' => $data['disabled'],
            'youth_guarantee' => $data['youth_guarantee'],
            'social_exclusion' => $data['social_exclusion'],
            'specialty' => $data['specialty'],
            'professional_certificate' => $data['professional_certificate'],
            'monday' => $data['monday'],
            'tuesday' => $data['tuesday'],
            'wednesday' => $data['wednesday'],
            'thursday' => $data['thursday'],
            'friday' => $data['friday'],
            'saturday' => $data['saturday'],
            'sunday' => $data['sunday'],
            'total_hours' => $bonusYearOne + $bonusYearTwo,
            'observations' => $data['observations'],
            'daily_hours_1' => $data['daily_hours_1'],
            'daily_hours_2' => $data['daily_hours_2'],
        ]);
        $training_contract->excludedDays()->sync($data['excluded_day_id']);
        return $training_contract;
    }

    /**
     * Actualizamos los contratos de formación
     * @param $id
     * @param $data
     * @return mixed
     */
    public static function updateTrainingContract($id, $data){

        $bonusYearOne =  $data['bonus_hours_first_year'] ? $data['bonus_hours_first_year'] : 0;
        $bonusYearTwo = $data['bonus_hours_second_year'] ? $data['bonus_hours_second_year'] : 0;

        $training_contract = TrainingContract::find($id);
        $training_contract->update([
            'company_id' => $data['company_id'],
            'student_id' => $data['student_id'],
            'company_tutor' => $data['company_tutor'],
            'company_tutor_dni' => $data['company_tutor_dni'],
            'occupation_id' => $data['occupation_id'],
            'center_of_work' => $data['center_of_work'],
            'province_id' => $data['province_id'],
            'beginning' => $data['beginning'] ? Carbon::createFromFormat('d-m-Y', $data['beginning'])->format('Y-m-d') : null,
            'end' => $data['end'] ? Carbon::createFromFormat('d-m-Y', $data['end'])->format('Y-m-d') : null,
            'beginning_formation' => $data['beginning_formation'] ? Carbon::createFromFormat('d-m-Y', $data['beginning_formation'])->format('Y-m-d') : null,
            'end_formation' => $data['end_formation'] ? Carbon::createFromFormat('d-m-Y', $data['end_formation'])->format('Y-m-d') : null,
            'formation_hours' => $data['formation_hours'],
            'annually_day_hours' => $data['annually_day_hours'],
            'bonus_hours_first_year' =>  $bonusYearOne,
            'bonus_hours_second_year' => $bonusYearTwo,
            'training_schedule' => $data['training_schedule'],
            'working_hours' => $data['working_hours'],
            'complete_schedule' => $data['complete_schedule'],
            'training_contract_status_id' => $data['training_contract_status_id'],
            'on_leave_type_id' => $data['on_leave_type_id'],
            'on_leave_date' => $data['on_leave_date'] ? Carbon::createFromFormat('d-m-Y', $data['on_leave_date'])->format('Y-m-d') : null,
            'advisor_id' => $data['advisor_id'],
            'collaborator_id' => $data['collaborator_id'],
            'percentage_first_year' => $data['percentage_first_year'],
            'percentage_second_year' => $data['percentage_second_year'],
            'formative_hours_first_year' => $data['formative_hours_first_year'] ? $data['formative_hours_first_year'] : 0,
            'formative_hours_second_year' => $data['formative_hours_second_year'] ? $data['formative_hours_second_year'] : 0,
            'provider_id' => $data['provider_id'],
            'disabled' => $data['disabled'],
            'youth_guarantee' => $data['youth_guarantee'],
            'social_exclusion' => $data['social_exclusion'],
            'specialty' => $data['specialty'],
            'professional_certificate' => $data['professional_certificate'],
            'monday' => $data['monday'],
            'tuesday' => $data['tuesday'],
            'wednesday' => $data['wednesday'],
            'thursday' => $data['thursday'],
            'friday' => $data['friday'],
            'saturday' => $data['saturday'],
            'sunday' => $data['sunday'],
            'total_hours' => $bonusYearOne + $bonusYearTwo,
            'observations' => $data['observations'],
            'daily_hours_1' => $data['daily_hours_1'],
            'daily_hours_2' => $data['daily_hours_2'],
        ]);
        return $training_contract;
    }

    public function calculateFormationHours()
    {
        $formation_hours = $this->formative_hours_first_year;

        if ($this->formative_hours_second_year) {
            $formation_hours += $this->formative_hours_second_year;
        }

        return $formation_hours;
    }

    public function calculateHours($training_contract_id)
    {
    
        $record = TrainingContract::findOrFail($training_contract_id);
    
        $formative_hours_first_year = $record->formative_hours_first_year;
        $formative_hours_second_year = $record->formative_hours_second_year;
    
        $cont_days = 0;
        $date = Carbon::parse($record->beginning_formation);
        $end_date = Carbon::parse($record->end_formation);
        $total_hours = 0;
        $total = 0;
        $vacations = 0;
        $banckholiday = 0;
        $fin_semana = 0;
        $total_days = 0;
        $cont_days_first_year = 0;
        $cont_days_second_year = 0;
        $daily_hours_1 = 0;
        $daily_hours_2 = 0;
        $formation_hours = $this->calculateFormationHours();
        $beginning_formation_carbon = Carbon::parse($record->beginning_formation);
    
        do {
            $excluded = TrainingContractsExcludedDay::nonWorkingDay($training_contract_id, $date);
            if ($excluded != true){
                $excluded = TrainingContractFestival::existDay($date, $record->id)->first();
                if (!$excluded){
                    switch($date->dayOfWeek){
                        case 0:
                            if ($record->sunday === 0){
                                $fin_semana++;
                            }
                            break;
                        case 1:
                            if ($record->monday === 1){
                                $cont_days++;
                                if ($date->lt($beginning_formation_carbon->copy()->addYear())) {
                                    $cont_days_first_year++;
    
                                } else {
                                    $cont_days_second_year++;
    
                                }
                            }
                            break;
                        case 2:
                            if ($record->tuesday === 1){
                                $cont_days++;
                                if ($date->lt($beginning_formation_carbon->copy()->addYear())) {
                                    $cont_days_first_year++;
    
                                } else {
                                    $cont_days_second_year++;
    
                                }
                            }
                            break;
                        case 3:
                            if ($record->wednesday === 1){
                                $cont_days++;
                                if ($date->lt($beginning_formation_carbon->copy()->addYear())) {
                                    $cont_days_first_year++;
    
                                } else {
                                    $cont_days_second_year++;
    
                                }
                            }
                            break;
                        case 4:
                            if ($record->thursday === 1){
                                $cont_days++;
                                if ($date->lt($beginning_formation_carbon->copy()->addYear())) {
                                    $cont_days_first_year++;
    
                                } else {
                                    $cont_days_second_year++;
    
                                }
                            }
                            break;
                        case 5:
                            if ($record->friday === 1){
                                $cont_days++;
                                if ($date->lt($beginning_formation_carbon->copy()->addYear())) {
                                    $cont_days_first_year++;
    
                                } else {
                                    $cont_days_second_year++;
    
                                }
                            }
                            break;
                        case 6:
                            if ($record->saturday === 0){
                                $fin_semana++;
                            }
                            break;
                    }
                } else {
                    $banckholiday++;
                }
            } else {
                $vacations++;
            }
            $total++;
            $date->addDay();
        } while($end_date->gte($date));
    
        
    
        if ($cont_days_first_year != 0){
            $daily_hours_1 = $formative_hours_first_year / $cont_days_first_year;
            $daily_hours_1 = round($daily_hours_1, 2);
            
            if ($cont_days_second_year != 0) {
                $daily_hours_2 = $formative_hours_second_year / $cont_days_second_year;
                $daily_hours_2 = round($daily_hours_2, 2);
            } else {
                $daily_hours_2 = 0;
            }
            
            $record->update([
                'total_days' => $cont_days_first_year + $cont_days_second_year,
                'daily_hours_1' => $daily_hours_1,
                'daily_hours_2' => $daily_hours_2,
                'formation_hours' => $formation_hours,
            ]);
            $this->updateDates($training_contract_id);
        }
    
        // Devuelve una respuesta HTTP con los datos calculados
        return response()->json([
            'status' => 200,
            'total_hours' => $total_hours,
            'daily_hours_1' => $daily_hours_1,
            'daily_hours_2' => $daily_hours_2,
            'total_days' => $cont_days_first_year + $cont_days_second_year,
        ]);
    }

    public function calculateMonthlyFormationHours($training_contract_id)
    {
        $record = TrainingContract::findOrFail($training_contract_id);

        $beginning_date = Carbon::parse($record->beginning_formation);
        $end_date = Carbon::parse($record->end_formation);

        $monthly_formation_hours = [];

        for ($date = $beginning_date; $date->lte($end_date); $date->addMonth()) {
            $cont_days = 0;
            $month_start_date = (clone $date)->startOfMonth();
            $month_end_date = (clone $date)->endOfMonth();

            for ($day = $month_start_date; $day->lte($month_end_date); $day->addDay()) {
                if ($this->isWorkingDay($day, $record)) {
                    $cont_days++;
                }
            }

            $monthly_formation_hours[$date->format('Y-m')] = $cont_days * $record->daily_hours_1;
        }

        return response()->json([
            'status' => 200,
            'monthly_formation_hours' => $monthly_formation_hours,
        ]);
    }

    private function isWorkingDay($date, $record)
    {
        if (TrainingContractsExcludedDay::nonWorkingDay($record->id, $date)) {
            return false;
        }

        if (TrainingContractFestival::existDay($date, $record->id)->first()) {
            return false;
        }

        switch ($date->dayOfWeek) {
            case Carbon::SUNDAY:
                return $record->sunday === 1;
            case Carbon::MONDAY:
                return $record->monday === 1;
            case Carbon::TUESDAY:
                return $record->tuesday === 1;
            case Carbon::WEDNESDAY:
                return $record->wednesday === 1;
            case Carbon::THURSDAY:
                return $record->thursday === 1;
            case Carbon::FRIDAY:
                return $record->friday === 1;
            case Carbon::SATURDAY:
                return $record->saturday === 1;
        }

        return false;
    }


    public static function updateDates($training_contract_id){
        $training_contract = TrainingContract::find($training_contract_id);
        $training_contract_elements = TrainingContractElement::with('training_action')
            ->where('training_contract_id', $training_contract_id)
            ->orderBy('order', 'asc')
            ->get();

        // Si no hay elementos de contrato de formación, calcula las horas diarias y la formación total y luego retorna
        if ($training_contract_elements->isEmpty()) {
            $formation_hours = $training_contract->calculateFormationHours();
            $daily_hours_1 = $training_contract->formative_hours_first_year != 0 ? $training_contract->formative_hours_first_year / $training_contract->total_days : 0;
            $daily_hours_2 = $training_contract->formative_hours_second_year != 0 ? $training_contract->formative_hours_second_year / $training_contract->total_days : 0;

            $training_contract->update([
                'daily_hours_1' => $daily_hours_1,
                'daily_hours_2' => $daily_hours_2,
                'formation_hours' => $formation_hours,
            ]);

            return;
        }

        $beginning = new Carbon($training_contract->beginning_formation);
        foreach ($training_contract_elements as $element) {
            $hours = 0;
            if ($element->certification_id && $element->certification) {
                $hours = $element->certification->total_hours;
            } elseif ($element->training_action) {
                $hours = $element->training_action->total_hours;
            }
            while (TrainingContractsExcludedDay::nonWorkingDay($training_contract_id, $beginning->toDateString())) {
                $beginning->addDay();
            }
        
            $end = $beginning->copy();
            if ($training_contract->daily_hours_1 != 0) {
                $days_to_add = intval($hours / $training_contract->daily_hours_1);
                $remaining_hours = $hours % $training_contract->daily_hours_1;
                for ($i = 0; $i < $days_to_add; $i++) {
                    while (TrainingContractsExcludedDay::nonWorkingDay($training_contract_id, $end->toDateString())) {
                        $end->addDay();
                    }
                    if ($i < $days_to_add - 1) {
                        $end->addDay();
                    }
                }
            } else {
                throw new \Exception('Las horas diarias del primer año no pueden ser cero.');
            }
            
            if ($training_contract->daily_hours_2 > 0 && $remaining_hours > 0) {
                $days_to_add = intval($remaining_hours / $training_contract->daily_hours_2);
                for ($i = 0; $i < $days_to_add; $i++) {
                    while (TrainingContractsExcludedDay::nonWorkingDay($training_contract_id, $end->toDateString())) {
                        $end->addDay();
                    }
                    if ($i < $days_to_add - 1) {
                        $end->addDay();
                    }
                }
            }

            $element->update([
                'beginning' => $beginning,
                'end' => $end
            ]);

            $beginning = $end->copy()->addDay();
        }

        $last_element = $training_contract_elements->last();
        if ($last_element) {
            $end_formation = Carbon::parse($training_contract->end_formation);
            $last_element->end = $end_formation;
            $last_element->save();
        } else {
            Log::error('No se encontró el último elemento del contrato de formación');
        }
    }

}
