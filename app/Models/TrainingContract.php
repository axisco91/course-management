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
            'bonification' => $data['bonification'] ?? false,
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
            'bonification' => $data['bonification'] ?? $training_contract->bonification,
        ]);
        return $training_contract;
    }

    /**
     * Calcula las horas formativas y actualiza los datos del contrato de formación.
     *
     * @param int $training_contract_id El ID del contrato de formación.
     * @return array Un arreglo con los datos actualizados del contrato de formación.
     * @throws \Exception Si no hay días laborables en el período del primer año.
     */
    public function calculateHours($training_contract_id)
    {
        Log::info('calculateHours called', ['training_contract_id' => $training_contract_id]);
        $record = TrainingContract::findOrFail($training_contract_id);
        Log::info('Record fetched', ['record' => $record->toArray()]);
    
        $beginning_formation_carbon = Carbon::parse($record->beginning_formation);
    
        $total_hours = TrainingContractElement::where('training_contract_id', $training_contract_id)
            ->with('training_action')
            ->get()
            ->sum(function ($element) {
                return $element->training_action ? $element->training_action->total_hours : 0;
            });
    
        $bonus_hours_first_year = $record->bonus_hours_first_year;
        $bonus_hours_second_year = $record->bonus_hours_second_year;
    
        Log::info('Bonus Hours', ['bonus_hours_first_year' => $bonus_hours_first_year, 'bonus_hours_second_year' => $bonus_hours_second_year]);
    
        $daily_hours_1 = $record->daily_hours_1;
        $daily_hours_2 = $record->daily_hours_2;
    
        $initial_end_formation = $record->end_formation;
    
        if ($record->end_formation) {
            $date = Carbon::parse($record->beginning_formation);
            $end_date = Carbon::parse($record->end_formation);
    
            list($cont_days_first_year, $cont_days_second_year, $total_days) = $this->calculateWorkingDays($date, $end_date, $beginning_formation_carbon, $record);
    
            if ($cont_days_first_year == 0) {
                throw new \Exception("No working days in the first year period");
            }
    
            $total_bonus_hours = $bonus_hours_first_year + $bonus_hours_second_year;
    
            if ($total_hours < $total_bonus_hours) {
                $total_hours = $total_bonus_hours;
            }
    
            if ($total_hours > $total_bonus_hours) {
                $excess_hours = $total_hours - $total_bonus_hours;
                $total_working_days = $cont_days_first_year + $cont_days_second_year;
                
                $proportion_first_year = $bonus_hours_first_year / $total_bonus_hours;
                $proportion_second_year = $bonus_hours_second_year / $total_bonus_hours;
    
                $additional_hours_first_year = round($excess_hours * $proportion_first_year, 2);
                $additional_hours_second_year = round($excess_hours * $proportion_second_year, 2);
    
                $bonus_hours_first_year += $additional_hours_first_year;
                $bonus_hours_second_year += $additional_hours_second_year;
            }
    
            $daily_hours_1 = $cont_days_first_year > 0 ? round($bonus_hours_first_year / $cont_days_first_year, 2) : 0;
            $daily_hours_2 = $cont_days_second_year > 0 ? round($bonus_hours_second_year / $cont_days_second_year, 2) : 0;
    
            $record->update([
                'total_days' => $total_days,
                'daily_hours_1' => $daily_hours_1,
                'daily_hours_2' => $daily_hours_2,
            ]);
        } else {
            $date = Carbon::parse($record->beginning_formation);
            $total_hours_first_year = $bonus_hours_first_year;
            $total_hours_second_year = $bonus_hours_second_year;
    
            list($calculated_end_date, $cont_days_first_year, $cont_days_second_year, $total_days) = $this->calculateEndDate($date, $total_hours_first_year, $total_hours_second_year, $beginning_formation_carbon, $daily_hours_1, $daily_hours_2, $record);
    
            $total_bonus_hours = $bonus_hours_first_year + $bonus_hours_second_year;
    
            if ($total_hours < $total_bonus_hours) {
                $total_hours = $total_bonus_hours;
            }
    
            if ($total_hours > $total_bonus_hours) {
                $excess_hours = $total_hours - $total_bonus_hours;
                $total_working_days = $cont_days_first_year + $cont_days_second_year;
                
                $proportion_first_year = $bonus_hours_first_year / $total_bonus_hours;
                $proportion_second_year = $bonus_hours_second_year / $total_bonus_hours;
    
                $additional_hours_first_year = round($excess_hours * $proportion_first_year, 2);
                $additional_hours_second_year = round($excess_hours * $proportion_second_year, 2);
    
                $bonus_hours_first_year += $additional_hours_first_year;
                $bonus_hours_second_year += $additional_hours_second_year;
            }
    
            $daily_hours_1 = $cont_days_first_year > 0 ? round($bonus_hours_first_year / $cont_days_first_year, 2) : 0;
            $daily_hours_2 = $cont_days_second_year > 0 ? round($bonus_hours_second_year / $cont_days_second_year, 2) : 0;
    
            $record->update([
                'end_formation' => $calculated_end_date,
                'total_days' => $total_days,
                'daily_hours_1' => $daily_hours_1,
                'daily_hours_2' => $daily_hours_2,
            ]);
        }
    
        $updated_elements = $this->updateDates($training_contract_id, $daily_hours_1, $daily_hours_2, $record->end_formation);
    
        $record->refresh();
    
        if ($cont_days_first_year != 0) {
            $formative_hours_first_year = max($cont_days_first_year * $daily_hours_1, $bonus_hours_first_year);
            $formative_hours_second_year = max($cont_days_second_year * $daily_hours_2, $bonus_hours_second_year);
    
            $total_formative_hours = round($formative_hours_first_year + $formative_hours_second_year, 2);
            if ($total_formative_hours < $total_hours) {
                $difference = $total_hours - $total_formative_hours;
                $proportion_first_year = $formative_hours_first_year / $total_formative_hours;
                $proportion_second_year = $formative_hours_second_year / $total_formative_hours;
                
                $formative_hours_first_year += round($difference * $proportion_first_year, 2);
                $formative_hours_second_year += round($difference * $proportion_second_year, 2);
            }
    
            $record->update([
                'formative_hours_first_year' => round($formative_hours_first_year, 2),
                'formative_hours_second_year' => round($formative_hours_second_year, 2),
            ]);
        }
    
        $last_element = !empty($updated_elements) ? end($updated_elements) : null;
        $last_element_dates = $last_element ? ['beginning' => $last_element->beginning, 'end' => $last_element->end] : null;
    
        if (!$initial_end_formation && $last_element) {
            $record->update(['end_formation' => $last_element->end]);
            Log::info('End formation updated', ['end_formation' => $last_element->end]);
        }
    
        Log::info('Last element updated', $last_element_dates ?: []);
    
        Log::info('Updated Elements', ['updated_elements' => collect($updated_elements)->map->only(['id', 'beginning', 'end'])->toArray()]);
    
        Log::info('End of calculateHours', [
            'formative_hours_first_year' => $formative_hours_first_year ?? null,
            'formative_hours_second_year' => $formative_hours_second_year ?? null,
            'daily_hours_1' => $daily_hours_1,
            'daily_hours_2' => $daily_hours_2,
            'cont_days_first_year' => $cont_days_first_year,
            'cont_days_second_year' => $cont_days_second_year,
            'end_formation' => $record->end_formation,
            'end' => $record->end
        ]);
    
        return [
            'formative_hours_first_year' => $formative_hours_first_year ?? null,
            'formative_hours_second_year' => $formative_hours_second_year ?? null,
            'daily_hours_1' => $daily_hours_1,
            'daily_hours_2' => $daily_hours_2,
            'cont_days_first_year' => $cont_days_first_year,
            'cont_days_second_year' => $cont_days_second_year,
            'updated_elements' => $updated_elements,
            'end_formation' => $record->end_formation,
            'end' => $record->end
        ];
    }

private function calculateWorkingDays($date, $end_date, $beginning_formation_carbon, $record, $cont_days_first_year = 0, $cont_days_second_year = 0, $total_days = 0)
{
    if ($date->gt($end_date)) {
        return [$cont_days_first_year, $cont_days_second_year, $total_days];
    }

    if ($this->isWorkingDay($date, $record)) {
        if ($date->lt($beginning_formation_carbon->copy()->addYear())) {
            $cont_days_first_year++;
        } else {
            $cont_days_second_year++;
        }
        $total_days++;
    }

    return $this->calculateWorkingDays($date->copy()->addDay(), $end_date, $beginning_formation_carbon, $record, $cont_days_first_year, $cont_days_second_year, $total_days);
}

private function calculateEndDate($date, $total_hours_first_year, $total_hours_second_year, $beginning_formation_carbon, $daily_hours_1, $daily_hours_2, $record, $cont_days_first_year = 0, $cont_days_second_year = 0, $total_days = 0)
{
    if (($cont_days_first_year * $daily_hours_1 >= $total_hours_first_year) && ($cont_days_second_year * $daily_hours_2 >= $total_hours_second_year)) {
        return [$date, $cont_days_first_year, $cont_days_second_year, $total_days];
    }

    if ($this->isWorkingDay($date, $record)) {
        if ($date->lt($beginning_formation_carbon->copy()->addYear())) {
            $cont_days_first_year++;
        } else {
            $cont_days_second_year++;
        }
        $total_days++;
    }

    return $this->calculateEndDate($date->copy()->addDay(), $total_hours_first_year, $total_hours_second_year, $beginning_formation_carbon, $daily_hours_1, $daily_hours_2, $record, $cont_days_first_year, $cont_days_second_year, $total_days);
}

private function updateDates($training_contract_id, $daily_hours_1, $daily_hours_2, $end_formation = null)
{
    $training_contract = TrainingContract::find($training_contract_id);
    $updated_elements = collect();
    $end_formation_carbon = $end_formation ? Carbon::parse($end_formation) : null;

    $training_contract_elements = TrainingContractElement::with('training_action')
        ->where('training_contract_id', $training_contract_id)
        ->orderBy('order', 'asc')
        ->get();

    Log::info('Total elements fetched', ['count' => $training_contract_elements->count()]);

    if ($training_contract_elements->isEmpty()) {
        $formation_hours = $training_contract->calculateFormationHours();
        $daily_hours_1 = $training_contract->formative_hours_first_year != 0 ? $training_contract->formative_hours_first_year / $training_contract->total_days : 0;
        $daily_hours_2 = $training_contract->formative_hours_second_year != 0 ? $training_contract->formative_hours_second_year / $training_contract->total_days : 0;

        $training_contract->update([
            'daily_hours_1' => $daily_hours_1,
            'daily_hours_2' => $daily_hours_2,
            'formation_hours' => $formation_hours,
        ]);

        return [];
    }

    $beginning = new Carbon($training_contract->beginning_formation);
    $first_year_end = $beginning->copy()->addYear()->subDay();

    foreach ($training_contract_elements as $element) {
        if ($element->course_id !== null) {
            $updated_elements->push($element);
            continue;
        }

        $hours = $element->training_action ? $element->training_action->total_hours : 0;
        $current_daily_hours = $beginning->lte($first_year_end) ? $daily_hours_1 : $daily_hours_2;
        $days_to_add = $current_daily_hours > 0 ? ceil($hours / $current_daily_hours) : 0;

        $end = $beginning->copy();
        for ($i = 0; $i < $days_to_add; $i++) {
            do {
                $end->addDay();
            } while ($this->isNonWorkingDay($end, $training_contract_id));

            if ($end->gt($end_formation_carbon)) {
                $end = $end_formation_carbon;
                break;
            }
        }

        while ($this->isNonWorkingDay($end, $training_contract_id)) {
            $end->subDay();
        }

        $element->update([
            'beginning' => $beginning->toDateString(),
            'end' => $end->toDateString()
        ]);

        $updated_elements->push($element);

        $beginning = $end->copy()->addDay();
        while ($this->isNonWorkingDay($beginning, $training_contract_id)) {
            $beginning->addDay();
        }

        if ($beginning->gt($end_formation_carbon)) {
            break;
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

    private function isNonWorkingDay($date, $training_contract_id)
    {
        return TrainingContractsExcludedDay::nonWorkingDay($training_contract_id, $date->toDateString()) || TrainingContractFestival::existDay($date, $training_contract_id)->first();
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

            $daily_hours = $date->lt($beginning_date->copy()->addYear()) ? $record->daily_hours_1 : $record->daily_hours_2;
            $monthly_formation_hours[$date->format('Y-m')] = $cont_days * $daily_hours;
        }

        return response()->json([
            'status' => 200,
            'monthly_formation_hours' => $monthly_formation_hours,
        ]);
    }

        private function calculateWorkingDaysAndHours($record, $beginning_formation, $end_formation, $total_hours)
        {
            $first_year_end = $beginning_formation->copy()->addYear()->subDay();
            $first_year_working_days = $this->countWorkingDays($record, $beginning_formation, $first_year_end);
            $second_year_working_days = $this->countWorkingDays($record, $first_year_end->copy()->addDay(), $end_formation);

            $total_working_days = $first_year_working_days + $second_year_working_days;

            $daily_hours = $total_working_days > 0 ? $total_hours / $total_working_days : 0;

            $formative_hours_first_year = $first_year_working_days * $daily_hours;
            $formative_hours_second_year = $second_year_working_days * $daily_hours;

            return [
                'daily_hours_1' => round($daily_hours, 2),
                'daily_hours_2' => round($daily_hours, 2),
                'formative_hours_first_year' => round($formative_hours_first_year, 2),
                'formative_hours_second_year' => round($formative_hours_second_year, 2),
                'cont_days_first_year' => $first_year_working_days,
                'cont_days_second_year' => $second_year_working_days,
            ];
        }

        private function countWorkingDays($record, $start_date, $end_date)
        {
            $working_days = 0;
            for ($date = $start_date->copy(); $date->lte($end_date); $date->addDay()) {
                if ($this->isWorkingDay($date, $record)) {
                    $working_days++;
                }
            }
            return $working_days;
        }

        private function updateElementDates($training_contract_id, $end_formation, $daily_hours_1, $daily_hours_2)
        {
            $elements = TrainingContractElement::where('training_contract_id', $training_contract_id)
                ->orderBy('order')
                ->get();

            $current_date = Carbon::parse($this->beginning_formation);
            $updated_elements = [];

            foreach ($elements as $element) {
                $element_start = $this->getNextWorkingDay($current_date, $training_contract_id);
                $daily_hours = $this->getDailyHours($element, $element_start, $daily_hours_1, $daily_hours_2);
                $total_hours = $element->training_action->total_hours ?? 0;
                $days_needed = ceil($total_hours / $daily_hours);

                $element_end = $this->calculateElementEnd($element_start, $days_needed, $end_formation, $training_contract_id);

                $element->update([
                    'beginning' => $element_start->toDateString(),
                    'end' => $element_end->toDateString()
                ]);

                $updated_elements[] = $element;
                $current_date = $element_end->copy()->addDay();
            }

            return $updated_elements;
        }

    private function recalculateFormativeHours($record, $updated_elements)
    {
        $formative_hours_first_year = 0;
        $formative_hours_second_year = 0;
        $first_year_end = Carbon::parse($record->beginning_formation)->addYear()->subDay();

        foreach ($updated_elements as $element) {
            $element_start = Carbon::parse($element->beginning);
            $element_end = Carbon::parse($element->end);
            $element_days = $this->countWorkingDays($record, $element_start, $element_end);
            $daily_hours = $this->getDailyHours($element, $element_start, $record->daily_hours_1, $record->daily_hours_2);

            if ($element_end->lte($first_year_end)) {
                $formative_hours_first_year += $element_days * $daily_hours;
            } elseif ($element_start->gt($first_year_end)) {
                $formative_hours_second_year += $element_days * $daily_hours;
            } else {
                $first_year_days = $this->countWorkingDays($record, $element_start, $first_year_end);
                $second_year_days = $element_days - $first_year_days;
                $formative_hours_first_year += $first_year_days * $daily_hours;
                $formative_hours_second_year += $second_year_days * $daily_hours;
            }
        }

        return [
            'formative_hours_first_year' => round($formative_hours_first_year, 2),
            'formative_hours_second_year' => round($formative_hours_second_year, 2),
        ];
    }

    private function getNextWorkingDay($date, $training_contract_id)
    {
        while ($this->isNonWorkingDay($date, $training_contract_id)) {
            $date->addDay();
        }
        return $date;
    }

    private function getTotalHours($training_contract_id)
    {
        $training_contract = $this->findOrFail($training_contract_id);

        // Supongamos que las horas totales se calculan a partir de las horas formativas del primer y segundo año
        $total_hours = $training_contract->formative_hours_first_year + $training_contract->formative_hours_second_year;

        Log::info('Total hours calculated', ['total_hours' => $total_hours]);

        return $total_hours;
    }

    private function updateContractDates($record, $total_hours, $bonus_hours_first_year, $bonus_hours_second_year, $daily_hours_1, $daily_hours_2)
    {
        Log::info('updateContractDates called', [
            'record_id' => $record->id,
            'total_hours' => $total_hours,
            'bonus_hours_first_year' => $bonus_hours_first_year,
            'bonus_hours_second_year' => $bonus_hours_second_year,
            'daily_hours_1' => $daily_hours_1,
            'daily_hours_2' => $daily_hours_2
        ]);

        $beginning_formation_carbon = Carbon::parse($record->beginning_formation);
        Log::info('Parsed beginning formation date', ['beginning_formation_carbon' => $beginning_formation_carbon->toDateString()]);

        $cont_days_first_year = 0;
        $cont_days_second_year = 0;
        $total_days = 0;

        if ($record->end_formation) {
            $date = Carbon::parse($record->beginning_formation);
            $end_date = Carbon::parse($record->end_formation);
            Log::info('Parsed end formation date', ['end_date' => $end_date->toDateString()]);

            list($cont_days_first_year, $cont_days_second_year, $total_days) = $this->calculateWorkingDays($date, $end_date, $beginning_formation_carbon, $record);
            Log::info('Calculated working days', [
                'cont_days_first_year' => $cont_days_first_year,
                'cont_days_second_year' => $cont_days_second_year,
                'total_days' => $total_days
            ]);

            if ($cont_days_first_year == 0) {
                throw new \Exception("No working days in the first year period");
            }

            $total_bonus_hours = $bonus_hours_first_year + $bonus_hours_second_year;
            Log::info('Total bonus hours calculated', ['total_bonus_hours' => $total_bonus_hours]);

            if ($total_hours > $total_bonus_hours) {
                $excess_hours = $total_hours - $total_bonus_hours;
                if ($cont_days_second_year == 0) {
                    $bonus_hours_first_year += $excess_hours;
                } else {
                    $total_working_days = $cont_days_first_year + $cont_days_second_year;
                    $proportion_first_year = $cont_days_first_year / $total_working_days;
                    $proportion_second_year = $cont_days_second_year / $total_working_days;

                    $additional_hours_first_year = round($excess_hours * $proportion_first_year, 2);
                    $additional_hours_second_year = round($excess_hours * $proportion_second_year, 2);

                    $bonus_hours_first_year += $additional_hours_first_year;
                    $bonus_hours_second_year += $additional_hours_second_year;
                }
            }

            $daily_hours_1 = round($bonus_hours_first_year / $cont_days_first_year, 2);
            $daily_hours_2 = $cont_days_second_year > 0 ? round($bonus_hours_second_year / $cont_days_second_year, 2) : 0;
            Log::info('Calculated daily hours', [
                'daily_hours_1' => $daily_hours_1,
                'daily_hours_2' => $daily_hours_2
            ]);

            $record->update([
                'total_days' => $total_days,
                'daily_hours_1' => $daily_hours_1,
                'daily_hours_2' => $daily_hours_2,
            ]);
            Log::info('Record updated with total days and daily hours');
        }

        return [
            'formative_hours_first_year' => $bonus_hours_first_year,
            'formative_hours_second_year' => $bonus_hours_second_year,
            'daily_hours_1' => $daily_hours_1,
            'daily_hours_2' => $daily_hours_2,
            'cont_days_first_year' => $cont_days_first_year,
            'cont_days_second_year' => $cont_days_second_year,
            'end_formation' => $record->end_formation,
            'end' => $record->end
        ];
    }


    private function calculateElementEnd($beginning, $total_days_needed, $end_formation, $training_contract_id)
    {
        $end = $beginning->copy();
        $days_added = 0;

        while ($days_added < $total_days_needed && $end->lte($end_formation)) {
            if (!$this->isNonWorkingDay($end, $training_contract_id)) {
                $days_added++;
            }
            if ($days_added < $total_days_needed) {
                $end->addDay();
            }
        }

        while ($this->isNonWorkingDay($end, $training_contract_id)) {
            $end->subDay();
        }

        if ($end->gt($end_formation)) {
            $end = $end_formation;
        }

        return $end;
    }

    private function processElementsWithCourseId($training_contract, $training_contract_elements, $daily_hours_1, $end_formation_carbon, &$last_course_end_date)
    {
        $updated_elements = [];
        foreach ($training_contract_elements as $element) {
            if ($element->course_id !== null) {
                $updated_elements[] = $this->updateElementWithCourseId($element, $last_course_end_date, $daily_hours_1, $end_formation_carbon, $training_contract);
                $last_course_end_date = Carbon::parse($element->end);
                Log::info('Element with course_id', [
                    'id' => $element->id,
                    'beginning' => $element->beginning,
                    'end' => $element->end
                ]);
            }
        }
        return $updated_elements;
    }

    private function processElementsWithoutCourseId($training_contract, $training_contract_elements, $daily_hours_1, $daily_hours_2, $end_formation_carbon, $last_course_end_date, $updated_elements)
    {
        $beginning = $last_course_end_date ? $last_course_end_date->copy()->addDay() : new Carbon($training_contract->beginning_formation);
        while ($this->isNonWorkingDay($beginning, $training_contract->id)) {
            $beginning->addDay();
        }

        Log::info('Initial beginning date for non-course_id elements', ['beginning' => $beginning->toDateString()]);

        $total_days = 0;
        $date_iterator = $beginning->copy();
        while ($date_iterator->lte($end_formation_carbon)) {
            if ($this->isWorkingDay($date_iterator, $training_contract)) {
                $total_days++;
            }
            $date_iterator->addDay();
        }

        Log::info('Total working days calculated', ['total_days' => $total_days]);

        $total_hours = $training_contract->bonus_hours_first_year + $training_contract->bonus_hours_second_year;
        $daily_hours_1 = $total_days > 0 ? round($total_hours / $total_days, 2) : $daily_hours_1;

        foreach ($training_contract_elements as $element) {
            if ($element->course_id === null) {
                $updated_elements = $this->updateElementWithoutCourseId($element, $beginning, $daily_hours_1, $daily_hours_2, $end_formation_carbon, $training_contract->id, $updated_elements);
            }
        }

        return $updated_elements;
    }

    private function updateElementWithCourseId($element, $last_course_end_date, $daily_hours_1, $end_formation_carbon, $training_contract)
    {
        // Initialize variables as needed
        $beginning = null;
        $days_to_add = 0;
        $end = null;

        // Calculate the beginning date for the element
        $beginning = $this->calculateBeginningDate($last_course_end_date, $training_contract);

        // Calculate the number of days to add based on the element's total hours and the daily hours
        $days_to_add = $this->calculateDaysToAdd($element, $daily_hours_1);

        // Calculate the end date for the element
        $end = $this->calculateElementEnd($beginning, $days_to_add, $end_formation_carbon, $training_contract->id);

        // Log the updated element details
        $this->logElementUpdate($element, $beginning, $end);

        // Update the element's beginning and end dates
        $element->update([
            'beginning' => $beginning->toDateString(),
            'end' => $end->toDateString()
        ]);

        // Update the last course end date
        $last_course_end_date = $end->copy();

        return $element;
    }

    private function updateElementWithoutCourseId($element, $beginning, $daily_hours_1, $daily_hours_2, $end_formation_carbon, $training_contract_id)
    {
        // Initialize variables as needed
        $days_to_add = 0;
        $end = null;

        // Calculate the number of days to add based on the element's total hours and the daily hours
        $days_to_add = $this->calculateDaysToAdd($element, $this->getDailyHours($element, $beginning, $daily_hours_1, $daily_hours_2));

        // Calculate the end date for the element
        $end = $this->calculateElementEnd($beginning, $days_to_add, $end_formation_carbon, $training_contract_id);

        // Log the updated element details
        $this->logElementUpdate($element, $beginning, $end);

        // Update the element's beginning and end dates
        $element->update([
            'beginning' => $beginning->toDateString(),
            'end' => $end->toDateString()
        ]);

        // Update the beginning date for the next element
        $beginning = $end->copy()->addDay();
        while ($this->isNonWorkingDay($beginning, $training_contract_id)) {
            $beginning->addDay();
        }

        return $element;
    }

    private function calculateBeginningDate($last_course_end_date, $training_contract)
    {
        $beginning = $last_course_end_date ? $last_course_end_date->copy()->addDay() : new Carbon($training_contract->beginning_formation);
        while ($this->isNonWorkingDay($beginning, $training_contract->id)) {
            $beginning->addDay();
        }
        return $beginning;
    }

   
    private function calculateDaysToAdd($element, $daily_hours)
    {
        $hours = $element->training_action ? $element->training_action->total_hours : 0;
        $days_to_add = $daily_hours != 0 ? round($hours / $daily_hours) : 0;
        return $days_to_add;
    }

    private function logElementUpdate($element, $beginning, $end)
    {
        Log::info('Updating element', [
            'id' => $element->id,
            'beginning' => $beginning->toDateString(),
            'end' => $end->toDateString()
        ]);
    }

    private function getDailyHours($element, $beginning, $daily_hours_1, $daily_hours_2)
    {
        if ($beginning->diffInYears($element->training_contract->beginning_formation) < 1) {
            return $daily_hours_1;
        } else {
            return $daily_hours_2;
        }
    }

    private function adjustDays($updated_elements, $end_formation_carbon)
    {
        $total_days = 0;
        $total_days_assigned = 0;

        foreach ($updated_elements as $element) {
            $total_days_assigned += (Carbon::parse($element->end)->diffInDays(Carbon::parse($element->beginning))) + 1;
        }

        $difference = $total_days - $total_days_assigned;
        Log::info('Adjusting days', ['difference' => $difference]);

        if ($difference != 0) {
            foreach ($updated_elements as $element) {
                $end = Carbon::parse($element->end);
                if ($difference > 0) {
                    $end->addDays($difference);
                    $difference = 0;
                } elseif ($difference < 0) {
                    $end->subDays(abs($difference));
                    $difference = 0;
                }
                $element->update(['end' => $end->toDateString()]);

                if ($difference == 0) {
                    break;
                }
            }
        }

        return $difference;
    }

    private function updateFormativeHours($record, $updated_contract_info, $total_hours)
    {
        $formative_hours_first_year = 0;
        $formative_hours_second_year = 0;

        if ($updated_contract_info['cont_days_first_year'] != 0) {
            $formative_hours_first_year = $updated_contract_info['cont_days_first_year'] * $updated_contract_info['daily_hours_1'];
        }

        if ($updated_contract_info['cont_days_second_year'] != 0) {
            $formative_hours_second_year = $updated_contract_info['cont_days_second_year'] * $updated_contract_info['daily_hours_2'];
        }

        $total_formative_hours = round($formative_hours_first_year + $formative_hours_second_year, 2);
        $bonus_hours_first_year = $updated_contract_info['formative_hours_first_year'];
        $bonus_hours_second_year = $updated_contract_info['formative_hours_second_year'];

        if ($total_formative_hours != $total_hours) {
            $difference = $total_hours - $total_formative_hours;
            if ($updated_contract_info['cont_days_second_year'] == 0) {
                $formative_hours_first_year += $difference;
            } else {
                $formative_hours_first_year += $difference / 2;
                $formative_hours_second_year += $difference / 2;
            }
        }

        // Ensure formative hours are not less than bonus hours
        if ($formative_hours_first_year < $bonus_hours_first_year) {
            $formative_hours_first_year = $bonus_hours_first_year;
        }

        if ($formative_hours_second_year < $bonus_hours_second_year) {
            $formative_hours_second_year = $bonus_hours_second_year;
        }

        $record->update([
            'formative_hours_first_year' => round($formative_hours_first_year, 2),
            'formative_hours_second_year' => round($formative_hours_second_year, 2),
        ]);

        return [
            'formative_hours_first_year' => round($formative_hours_first_year, 2),
            'formative_hours_second_year' => round($formative_hours_second_year, 2),
        ];
    }
   
}
