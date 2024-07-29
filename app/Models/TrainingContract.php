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

        $total_hours = $this->getTotalHours($training_contract_id);
        $bonus_hours_first_year = $record->bonus_hours_first_year;
        $bonus_hours_second_year = $record->bonus_hours_second_year;

        Log::info('Bonus Hours', ['bonus_hours_first_year' => $bonus_hours_first_year, 'bonus_hours_second_year' => $bonus_hours_second_year]);

        $daily_hours_1 = $record->daily_hours_1;
        $daily_hours_2 = $record->daily_hours_2;

        $initial_end_formation = $record->end_formation;

        $updated_contract_info = $this->updateContractDates($record, $total_hours, $bonus_hours_first_year, $bonus_hours_second_year, $daily_hours_1, $daily_hours_2);

        // Si `end` y `end_formation` son null, establecer `end_formation` basado en la fecha de finalización del último elemento
        if (is_null($record->end) && is_null($record->end_formation)) {
            $updated_elements = $this->updateElementDates($training_contract_id, $record->beginning_formation) ?: [];

            if (!empty($updated_elements)) {
                $last_element = end($updated_elements);
                $record->update(['end_formation' => $last_element->end]);
                Log::info('End formation updated based on last element', ['end_formation' => $last_element->end]);
            }
        } else {
            $updated_elements = $this->updateElementDates($training_contract_id, $updated_contract_info['end_formation']) ?: [];
        }
        
        if (!is_array($updated_elements)) {
            Log::error('Updated elements is not an array', ['updated_elements' => $updated_elements]);
            $updated_elements = [];
        }

        Log::info('Updated Elements after updateElementDates', ['updated_elements' => $updated_elements]);

        $record->refresh();

        $updated_formative_hours = $this->updateFormativeHours($record, $updated_contract_info, $total_hours);

        $updated_contract_info = array_merge($updated_contract_info, $updated_formative_hours);

        $last_element = !empty($updated_elements) ? end($updated_elements) : null;
        $last_element_dates = $last_element ? ['beginning' => $last_element->beginning, 'end' => $last_element->end] : null;

        if (!$initial_end_formation && $last_element) {
            $record->update(['end_formation' => $last_element->end]);
            Log::info('End formation updated', ['end_formation' => $last_element->end]);
        }

        Log::info('Last element updated', $last_element_dates ?: []);

        $updated_elements_array = collect($updated_elements)->map(function($element) {
            return $element->only(['id', 'beginning', 'end']);
        })->toArray();

        Log::info('Updated Elements final', ['updated_elements' => $updated_elements_array]);

        Log::info('End of calculateHours', $updated_contract_info);

        return array_merge($updated_contract_info, ['updated_elements' => $updated_elements_array]);
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


    private function updateElementDates($training_contract_id, $end_date)
{
    Log::info('updateElementDates called', ['training_contract_id' => $training_contract_id, 'end_date' => $end_date]);

    try {
        $end_date = Carbon::parse($end_date);
    } catch (\Exception $e) {
        Log::error('Invalid end_date format', ['end_date' => $end_date, 'error' => $e->getMessage()]);
        return [];
    }

    $elements = TrainingContractElement::where('training_contract_id', $training_contract_id)
        ->orderBy('order')
        ->get();

    Log::info('Elements fetched for update', ['elements_count' => $elements->count()]);

    $current_date = Carbon::parse($this->beginning_formation);
    $updated_elements = [];
    $last_course_end_date = null;

    foreach ($elements as $element) {
        if ($element->course_id !== null) {
            // Keep the original dates for elements with course_id
            $last_course_end_date = Carbon::parse($element->end);
            $updated_elements[] = $element;
            Log::info('Element with course_id skipped', ['element_id' => $element->id]);
            continue;
        }

        // Calculate the start date for the element
        while ($this->isNonWorkingDay($current_date, $training_contract_id)) {
            $current_date->addDay();
        }

        // If there's a last course end date, start the new element the day after
        if ($last_course_end_date && $current_date->lte($last_course_end_date)) {
            $current_date = $last_course_end_date->copy()->addDay();
            while ($this->isNonWorkingDay($current_date, $training_contract_id)) {
                $current_date->addDay();
            }
        }

        $element_start = $current_date->copy();

        // Calculate the end date based on the working days needed
        $daily_hours = $this->getDailyHours($element, $current_date, $this->daily_hours_1, $this->daily_hours_2);
        if ($daily_hours <= 0) {
            Log::warning("Daily hours is zero or negative for element {$element->id}. Using default value of 1.", [
                'element_id' => $element->id,
                'daily_hours' => $daily_hours,
                'daily_hours_1' => $this->daily_hours_1,
                'daily_hours_2' => $this->daily_hours_2
            ]);
            $daily_hours = 1; // Valor por defecto si las horas diarias son 0 o negativas
        }

        $total_hours = $element->training_action->total_hours ?? 0;
        if ($total_hours <= 0) {
            Log::warning("Total hours is zero or negative for element {$element->id}. Skipping this element.", [
                'element_id' => $element->id,
                'total_hours' => $total_hours
            ]);
            continue;
        }

        $total_days_needed = ceil($total_hours / $daily_hours);

        Log::info("Calculating total days needed", [
            'element_id' => $element->id,
            'total_hours' => $total_hours,
            'daily_hours' => $daily_hours,
            'total_days_needed' => $total_days_needed
        ]);

        $element_end = $this->calculateElementEnd($element_start, $total_days_needed, $end_date, $training_contract_id);

        // Update the element with the new dates
        Log::info('Updating element', [
            'id' => $element->id,
            'beginning' => $element_start->toDateString(),
            'end' => $element_end->toDateString()
        ]);

        $element->update([
            'beginning' => $element_start->toDateString(),
            'end' => $element_end->toDateString()
        ]);

        $updated_elements[] = $element;

        // Move the current date to the next working day after the end date of the current element
        $current_date = $element_end->copy()->addDay();
        while ($this->isNonWorkingDay($current_date, $training_contract_id)) {
            $current_date->addDay();
        }
    }

    Log::info('Elements updated', ['updated_elements_count' => count($updated_elements)]);

    return $updated_elements;
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


    /**
     * Calcula los días laborables entre dos fechas de forma recursiva.
     *
     * @param \Carbon\Carbon $date La fecha de inicio.
     * @param \Carbon\Carbon $end_date La fecha de finalización.
     * @param \Carbon\Carbon $beginning_formation_carbon La fecha de inicio de la formación.
     * @param object $record El registro del contrato de formación.
     * @return array Un array con los contadores de días del primer y segundo año, y el total de días.
     */
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

    /**
     * Verifica si una fecha es un día laborable según el contrato de entrenamiento.
     *
     * @param  \Carbon\Carbon  $date  La fecha a verificar.
     * @param  object  $record  El registro del contrato de entrenamiento.
     * @return bool  Devuelve true si la fecha es un día laborable, de lo contrario devuelve false.
     */
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

    /**
     * Calcula las horas mensuales de formación para un contrato de formación.
     *
     * @param int $training_contract_id El ID del contrato de formación.
     * @return \Illuminate\Http\JsonResponse
     */
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
}
