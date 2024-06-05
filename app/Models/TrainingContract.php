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
            'daily_hours_2' => $data['daily_hours_2']
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
            'daily_hours_2' => $data['daily_hours_2']
        ]);
        return $training_contract;
    }
    /**
     * Calcula las horas de formación para un contrato formacion
     *
     * @param int $training_contract_id El ID del contrato de entrenamiento.
     * @return array Un arreglo con la cantidad de horas formativas del primer año, 
     *              la cantidad de horas formativas del segundo año, las horas diarias 
     *              del primer año, las horas diarias del segundo año, los días 
     *              continuos del primer año, los días continuos del segundo año, 
     *              los elementos actualizados, la fecha de finalización de la formación 
     *              y la fecha de finalización del contrato.
     * @throws \Exception Si no hay días laborables en el período del primer año.
     */
    public function calculateHours($training_contract_id)
{
    $record = TrainingContract::findOrFail($training_contract_id);
    $beginning_formation_carbon = Carbon::parse($record->beginning_formation);
    $end_first_year = $beginning_formation_carbon->copy()->addYear()->subDay();

    // Calcula las horas totales de las acciones de formación
    $total_hours = TrainingContractElement::where('training_contract_id', $training_contract_id)
                    ->with('training_action')
                    ->get()
                    ->sum(function($element) {
                        return $element->training_action ? $element->training_action->total_hours : 0;
                    });

    $bonus_hours_first_year = $record->bonus_hours_first_year;
    $bonus_hours_second_year = $record->bonus_hours_second_year;

    Log::info('Bonus Hours', ['bonus_hours_first_year' => $bonus_hours_first_year, 'bonus_hours_second_year' => $bonus_hours_second_year]);

    $cont_days_first_year = 0;
    $cont_days_second_year = 0;
    $daily_hours_1 = $record->daily_hours_1;
    $daily_hours_2 = $record->daily_hours_2;

    $initial_end_formation = $record->end_formation;

    if ($record->end_formation) {
        // Caso en el que se proporciona end_formation
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

        if ($cont_days_first_year == 0) {
            throw new \Exception("No working days in the first year period");
        }

        $daily_hours_1 = round($bonus_hours_first_year / $cont_days_first_year, 2);
        $daily_hours_2 = $cont_days_second_year > 0 ? round($bonus_hours_second_year / $cont_days_second_year, 2) : 0;

        $record->update([
            'total_days' => $total_days,
            'daily_hours_1' => $daily_hours_1,
            'daily_hours_2' => $daily_hours_2,
        ]);
    } else {
        // Caso en el que se proporcionan las horas diarias pero no se proporciona end_formation
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

    // Llamar al método updateDates
    $updated_elements = $this->updateDates($training_contract_id, $daily_hours_1, $daily_hours_2, $record->end_formation);
    $updated_elements_dates = array_map(function ($element) {
        return ['beginning' => $element->beginning, 'end' => $element->end];
    }, $updated_elements);
    $record->refresh();

    if ($cont_days_first_year != 0) {
        $formative_hours_first_year = $cont_days_first_year * $daily_hours_1;
        $formative_hours_second_year = $cont_days_second_year * $daily_hours_2;

        $record->update([
            'formative_hours_first_year' => round($formative_hours_first_year, 2),
            'formative_hours_second_year' => round($formative_hours_second_year, 2),
        ]);
    }

    $last_element = !empty($updated_elements) ? end($updated_elements) : null;
    $last_element_dates = $last_element ? ['beginning' => $last_element->beginning, 'end' => $last_element->end] : null;

    // Si no se proporcionó inicialmente end_formation, actualízalo con la fecha de finalización del último elemento
    if (!$initial_end_formation && $last_element) {
        $record->update(['end_formation' => $last_element->end]);
        Log::info('End formation updated', ['end_formation' => $last_element->end]);
    }
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


    /**
     * Actualiza las fechas de los elementos de un contrato de formación.
     *
     * @param int $training_contract_id El ID del contrato de formación.
     * @param float $daily_hours_1 Las horas diarias para el primer año.
     * @param float $daily_hours_2 Las horas diarias para el segundo año.
     * @param string|null $end_formation La fecha de finalización de la formación (opcional).
     * @return array|null Los elementos actualizados o null si no hay elementos.
     */
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

    foreach ($training_contract_elements as $element) {
        // No modificar fechas si el course_id no es null
        if ($element->course_id !== null) {
            $updated_elements[] = $element;
            continue;
        }

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

    // Asegúrate de que el último elemento coincida con la fecha de end_formation proporcionada solo si se proporciona end_formation
    if ($end_formation && !empty($updated_elements)) {
        $last_element = end($updated_elements);
        if (Carbon::parse($end_formation)->gt($last_element->end)) {
            $last_element->update(['end' => Carbon::parse($end_formation)]);
        }
    }

    return $updated_elements;
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


    
     /**
     * Calculate the monthly formation hours for a training contract.
     *
     * @param int $training_contract_id The ID of the training contract.
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