<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TrainingContract extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded = [];

    public function excludedDays()
    {
        return $this->belongsToMany(ExcludedDayType::class, 'training_contracts_excluded_days', 'training_contract_id', 'excluded_day_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
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

    public function applicableAgreement()
    {
        return $this->belongsTo(ApplicableAgreement::class);
    }

    public function occupation()
    {
        return $this->belongsTo(Occupation::class);
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function scopeGetTrainingContracts($query)
    {
        return $query->select(
            'training_contracts.*',
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
            'occupations.name as occupation'
        )
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

    public static function createTrainingContract($data)
    {
        $training = TrainingContract::orderBy('id', 'desc')->first();
        $id = $training ? $training->id + 1 : 1;
        $number_cfa = str_pad($id, 4, '0', STR_PAD_LEFT);

        $bonusYearOne = $data['bonus_hours_first_year'] ? $data['bonus_hours_first_year'] : 0;
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
    public static function updateTrainingContract($id, $data)
    {
        $bonusYearOne = $data['bonus_hours_first_year'] ? $data['bonus_hours_first_year'] : 0;
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

    public function calculateHours($training_contract_id)
    {
        Log::info('calculateHours called', ['training_contract_id' => $training_contract_id]);
    
        $record = TrainingContract::findOrFail($training_contract_id);
        Log::info('Record fetched', ['record' => $record->toArray()]);
    
        $beginning_formation_carbon = Carbon::parse($record->beginning_formation);
        $end_first_year = $beginning_formation_carbon->copy()->addYear()->subDay();
    
        $total_hours = $record->total_hours;
        $bonus_hours_first_year = $record->bonus_hours_first_year;
        $bonus_hours_second_year = $record->bonus_hours_second_year;
    
        Log::info('Bonus Hours', ['bonus_hours_first_year' => $bonus_hours_first_year, 'bonus_hours_second_year' => $bonus_hours_second_year]);
    
        $cont_days_first_year = 0;
        $cont_days_second_year = 0;
        $daily_hours_1 = $record->daily_hours_1;
        $daily_hours_2 = $record->daily_hours_2;
    
        if ($record->end_formation) {
            // Case where end_formation is given
            $date = Carbon::parse($record->beginning_formation);
            $end_date = Carbon::parse($record->end_formation);
    
            $total_days = 0;
            do {
                if ($this->isWorkingDay($date, $record)) {
                    if ($date->lt($beginning_formation_carbon->copy()->addYear())) {
                        $cont_days_first_year++;
                    } else {
                        $cont_days_second_year++;
                    }
                    $total_days++;
                }
                $date->addDay();
            } while ($end_date->gte($date));
    
            $daily_hours_1 = round($bonus_hours_first_year / $cont_days_first_year, 2);
            $daily_hours_2 = round($bonus_hours_second_year / $cont_days_second_year, 2);
    
            $record->update([
                'total_days' => $total_days,
                'daily_hours_1' => $daily_hours_1,
                'daily_hours_2' => $daily_hours_2,
            ]);
        } else {
            // Case where daily hours are given but end_formation is not
            $date = Carbon::parse($record->beginning_formation);
            $total_hours_first_year = $bonus_hours_first_year;
            $total_hours_second_year = $bonus_hours_second_year;
    
            $calculated_end_date = $date->copy();
            $total_days = 0;
    
            while (($cont_days_first_year * $daily_hours_1 < $total_hours_first_year) || ($cont_days_second_year * $daily_hours_2 < $total_hours_second_year)) {
                if ($this->isWorkingDay($date, $record)) {
                    if ($date->lt($beginning_formation_carbon->copy()->addYear())) {
                        $cont_days_first_year++;
                    } else {
                        $cont_days_second_year++;
                    }
                    $total_days++;
                }
                $calculated_end_date = $date->copy();
                $date->addDay();
            }
    
            $record->update([
                'end_formation' => $calculated_end_date,
                'total_days' => $total_days,
                
            ]);
        }
    
        // Call updateDates method
        $updated_elements = $this->updateDates($training_contract_id, $daily_hours_1, $daily_hours_2, $record->end_formation);
        $updated_elements_dates = array_map(function ($element) {
            return ['beginning' => $element->beginning, 'end' => $element->end];
        }, $updated_elements);
        Log::info('updateDates called', ['updated_elements_dates' => $updated_elements_dates]);
        $record->refresh();
    
        if ($cont_days_first_year != 0) {
            $formative_hours_first_year = $cont_days_first_year * $daily_hours_1;
            $formative_hours_second_year = $cont_days_second_year * $daily_hours_2;
    
            $record->update([
                'formative_hours_first_year' => round($formative_hours_first_year, 2),
                'formative_hours_second_year' => round($formative_hours_second_year, 2),
            ]);
    
            Log::info('Record updated', ['record' => $record->toArray()]);
        }
    
        $last_element = !empty($updated_elements) ? end($updated_elements) : null;
        $last_element_dates = $last_element ? ['beginning' => $last_element->beginning, 'end' => $last_element->end] : null;
        Log::info('Last element dates', ['last_element_dates' => $last_element_dates]);
    
        Log::info('End of calculateHours', ['end_formation' => $record->end_formation, 'end' => $record->end]);
    
        return [
            'formative_hours_first_year' => $formative_hours_first_year ?? null,
            'formative_hours_second_year' => $formative_hours_second_year ?? null,
            'daily_hours_1' => $daily_hours_1 ?? null,
            'daily_hours_2' => $daily_hours_2 ?? null,
            'cont_days_first_year' => $cont_days_first_year ?? null,
            'cont_days_second_year' => $cont_days_second_year ?? null,
            'updated_elements' => $updated_elements,
            'end_formation' => $record->end_formation,
            'end' => $record->end
        ];
    }
    
    private function updateDates($training_contract_id, $daily_hours_1, $daily_hours_2, $end_formation)
    {
        $training_contract = TrainingContract::find($training_contract_id);
        $updated_elements = [];
    
        $training_contract_elements = TrainingContractElement::with('training_action')
            ->where('training_contract_id', $training_contract_id)
            ->orderBy('order', 'asc')
            ->get();
    
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
        $end_first_year = $beginning->copy()->addYear()->subDay();
    
        $updated_elements = [];
    
        foreach ($training_contract_elements as $element) {
            $hours = 0;
            if ($element->certification_id && $element->certification) {
                $hours = $element->certification->total_hours;
            } elseif ($element->training_action) {
                $hours = $element->training_action->total_hours;
            }
    
            if ($beginning->lte($end_first_year)) {
                $days_to_add = $daily_hours_1 != 0 ? intval($hours / $daily_hours_1) : 0;
            } else {
                $days_to_add = $daily_hours_2 != 0 ? intval($hours / $daily_hours_2) : 0;
            }
            
            $end = $beginning->copy();
    
            for ($i = 0; $i < $days_to_add; $i++) {
                do {
                    $end->addDay();
                } while (TrainingContractsExcludedDay::nonWorkingDay($training_contract_id, $end->toDateString()) || TrainingContractFestival::existDay($end, $training_contract_id)->first());
            }
    
            while (TrainingContractsExcludedDay::nonWorkingDay($training_contract_id, $end->toDateString()) || TrainingContractFestival::existDay($end, $training_contract_id)->first()) {
                $end->subDay();
            }
    
            $element->update([
                'beginning' => $beginning,
                'end' => $end
            ]);
    
            $updated_elements[] = $element;
    
            $beginning = $end->copy();
            do {
                $beginning->addDay();
            } while (TrainingContractsExcludedDay::nonWorkingDay($training_contract_id, $beginning->toDateString()) || TrainingContractFestival::existDay($beginning, $training_contract_id)->first());
    
            Log::info('Element', ['id' => $element->id, 'beginning' => $element->beginning, 'end' => $element->end]);
        }
    
        // Ensure the last element's end date matches end_formation if provided
        if ($end_formation) {
            $last_element = end($updated_elements);
            if ($last_element) {
                $last_element->update(['end' => Carbon::parse($end_formation)]);
            }
        }
    
        return $updated_elements;
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
}    