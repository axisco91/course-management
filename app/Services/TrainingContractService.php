<?php

namespace App\Services;

use App\Models\TrainingContract;
use App\Models\TrainingContractElement;
use App\Models\TrainingContractsExcludedDay;
use App\Models\TrainingContractFestival;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Exception;

class TrainingContractService
{
    /**
     * Calcula las horas formativas y actualiza los datos del contrato de formación.
     *
     * @param int $training_contract_id El ID del contrato de formación.
     * @return array Un arreglo con los datos actualizados del contrato de formación.
     * @throws Exception Si no hay días laborables en el período del primer año.
     */
    public function calculateHours($training_contract_id)
    {
        Log::info('calculateHours llamado', ['training_contract_id' => $training_contract_id]);
        $record = TrainingContract::findOrFail($training_contract_id);
        Log::info('Registro recuperado', ['record' => $record->toArray()]);
    
        $beginning_formation_carbon = Carbon::parse($record->beginning_formation);
        $total_hours = $this->calculateTotalHours($training_contract_id);
    
        $bonus_hours_first_year = $record->bonus_hours_first_year;
        $bonus_hours_second_year = $record->bonus_hours_second_year;
    
        Log::info('Horas de bonificación', ['bonus_hours_first_year' => $bonus_hours_first_year, 'bonus_hours_second_year' => $bonus_hours_second_year]);
    
        $initial_end_formation = $record->end_formation;

        // Revisar si existe una fecha de finalización
        if ($record->end_formation) {
            $result = $this->calculateWithEndDate($record, $beginning_formation_carbon, $total_hours, $bonus_hours_first_year, $bonus_hours_second_year);
        } else {
            $result = $this->calculateWithoutEndDate($record, $beginning_formation_carbon, $total_hours, $bonus_hours_first_year, $bonus_hours_second_year);
        }

        // Actualizar el registro con los resultados
        $this->updateRecordWithResult($record, $result);

        // Actualizar las fechas de los elementos relacionados
        $updated_elements = $this->updateDates($training_contract_id, $result['daily_hours_1'], $result['daily_hours_2'], $record->end_formation);

        // Manejar la actualización del último elemento
        $this->handleLastElementUpdate($record, $updated_elements, $initial_end_formation);

        Log::info('Fin de calculateHours', $result);
    
        return array_merge($result, ['updated_elements' => $updated_elements]);
    }

    /**
     * Calcula el total de horas de un contrato de formación basado en sus elementos.
     *
     * @param int $training_contract_id El ID del contrato de formación.
     * @return float El total de horas.
     */
    private function calculateTotalHours($training_contract_id)
    {
        return TrainingContractElement::where('training_contract_id', $training_contract_id)
            ->with('training_action')
            ->get()
            ->sum(function ($element) {
                return $element->training_action ? $element->training_action->total_hours : 0;
            });
    }

    /**
     * Calcula las horas formativas cuando existe una fecha de finalización.
     *
     * @param TrainingContract $record El registro del contrato de formación.
     * @param Carbon $beginning_formation_carbon La fecha de inicio de la formación.
     * @param float $total_hours El total de horas calculadas.
     * @param float $bonus_hours_first_year Las horas de bonificación del primer año.
     * @param float $bonus_hours_second_year Las horas de bonificación del segundo año.
     * @return array Los resultados del cálculo de horas.
     * @throws Exception Si no hay días laborables en el primer año.
     */
    private function calculateWithEndDate($record, $beginning_formation_carbon, $total_hours, $bonus_hours_first_year, $bonus_hours_second_year)
    {
        $date = Carbon::parse($record->beginning_formation);
        $end_date = Carbon::parse($record->end_formation);
    
        list($cont_days_first_year, $cont_days_second_year, $total_days) = $this->calculateWorkingDays($date, $end_date, $beginning_formation_carbon, $record);
    
        if ($cont_days_first_year == 0) {
            throw new Exception("No hay días laborables en el período del primer año");
        }

        return $this->distributeHours($total_hours, $bonus_hours_first_year, $bonus_hours_second_year, $cont_days_first_year, $cont_days_second_year, $total_days);
    }

    /**
     * Calcula las horas formativas cuando no existe una fecha de finalización.
     *
     * @param TrainingContract $record El registro del contrato de formación.
     * @param Carbon $beginning_formation_carbon La fecha de inicio de la formación.
     * @param float $total_hours El total de horas calculadas.
     * @param float $bonus_hours_first_year Las horas de bonificación del primer año.
     * @param float $bonus_hours_second_year Las horas de bonificación del segundo año.
     * @return array Los resultados del cálculo de horas, incluyendo la fecha de finalización calculada.
     */
    private function calculateWithoutEndDate($record, $beginning_formation_carbon, $total_hours, $bonus_hours_first_year, $bonus_hours_second_year)
    {
        $date = Carbon::parse($record->beginning_formation);
        
        list($calculated_end_date, $cont_days_first_year, $cont_days_second_year, $total_days) = $this->calculateEndDate($date, $total_hours, $beginning_formation_carbon, $record);
    
        $result = $this->distributeHours($total_hours, $bonus_hours_first_year, $bonus_hours_second_year, $cont_days_first_year, $cont_days_second_year, $total_days);
        $result['end_formation'] = $calculated_end_date->toDateString();

        return $result;
    }

    /**
     * Distribuye las horas formativas entre los años y calcula las horas diarias.
     *
     * @param float $total_hours El total de horas calculadas.
     * @param float $bonus_hours_first_year Las horas de bonificación del primer año.
     * @param float $bonus_hours_second_year Las horas de bonificación del segundo año.
     * @param int $cont_days_first_year Los días laborables del primer año.
     * @param int $cont_days_second_year Los días laborables del segundo año.
     * @param int $total_days El total de días laborables.
     * @return array Los resultados del cálculo de horas distribuidas.
     */
    private function distributeHours($total_hours, $bonus_hours_first_year, $bonus_hours_second_year, $cont_days_first_year, $cont_days_second_year, $total_days)
    {
        $total_bonus_hours = $bonus_hours_first_year + $bonus_hours_second_year;
    
        if ($total_hours > $total_bonus_hours) {
            $exceso_horas = $total_hours - $total_bonus_hours;
            if ($cont_days_second_year == 0) {
                $bonus_hours_first_year += $exceso_horas;
            } else {
                $total_working_days = $cont_days_first_year + $cont_days_second_year;
                $proportion_first_year = $cont_days_first_year / $total_working_days;
                $proportion_second_year = $cont_days_second_year / $total_working_days;

                $bonus_hours_first_year += round($exceso_horas * $proportion_first_year, 2);
                $bonus_hours_second_year += round($exceso_horas * $proportion_second_year, 2);
            }
        }

        $daily_hours_1 = $cont_days_first_year > 0 ? round($bonus_hours_first_year / $cont_days_first_year, 2) : 0;
        $daily_hours_2 = $cont_days_second_year > 0 ? round($bonus_hours_second_year / $cont_days_second_year, 2) : 0;

        return [
            'formative_hours_first_year' => round($bonus_hours_first_year, 2),
            'formative_hours_second_year' => round($bonus_hours_second_year, 2),
            'daily_hours_1' => $daily_hours_1,
            'daily_hours_2' => $daily_hours_2,
            'cont_days_first_year' => $cont_days_first_year,
            'cont_days_second_year' => $cont_days_second_year,
            'total_days' => $total_days
        ];
    }

    /**
     * Calcula los días laborables entre dos fechas y los divide entre los años de formación.
     *
     * @param Carbon $start_date La fecha de inicio.
     * @param Carbon $end_date La fecha de finalización.
     * @param Carbon $beginning_formation_carbon La fecha de inicio de la formación.
     * @param TrainingContract $record El registro del contrato de formación.
     * @return array Los días laborables en el primer año, el segundo año y en total.
     */
    private function calculateWorkingDays($start_date, $end_date, $beginning_formation_carbon, $record)
    {
        $cont_days_first_year = 0;
        $cont_days_second_year = 0;
        $total_days = 0;

        for ($date = $start_date; $date->lte($end_date); $date->addDay()) {
            if ($this->isWorkingDay($date, $record)) {
                if ($date->lt($beginning_formation_carbon->copy()->addYear())) {
                    $cont_days_first_year++;
                } else {
                    $cont_days_second_year++;
                }
                $total_days++;
            }
        }

        return [$cont_days_first_year, $cont_days_second_year, $total_days];
    }

    /**
     * Calcula la fecha de finalización estimada y los días laborables requeridos para alcanzar las horas totales.
     *
     * @param Carbon $start_date La fecha de inicio.
     * @param float $total_hours El total de horas necesarias.
     * @param Carbon $beginning_formation_carbon La fecha de inicio de la formación.
     * @param TrainingContract $record El registro del contrato de formación.
     * @return array La fecha de finalización calculada, los días laborables en el primer año, el segundo año y en total.
     */
    private function calculateEndDate($start_date, $total_hours, $beginning_formation_carbon, $record)
    {
        $cont_days_first_year = 0;
        $cont_days_second_year = 0;
        $total_days = 0;
        $current_hours = 0;

        $date = $start_date->copy();
        while ($current_hours < $total_hours) {
            if ($this->isWorkingDay($date, $record)) {
                if ($date->lt($beginning_formation_carbon->copy()->addYear())) {
                    $cont_days_first_year++;
                } else {
                    $cont_days_second_year++;
                }
                $total_days++;
                $current_hours += $this->getDailyHours($date, $beginning_formation_carbon, $record->daily_hours_1, $record->daily_hours_2);
            }
            $date->addDay();
        }

        return [$date->subDay(), $cont_days_first_year, $cont_days_second_year, $total_days];
    }

    /**
     * Actualiza las fechas de los elementos de formación de acuerdo con las horas diarias calculadas.
     *
     * @param int $training_contract_id El ID del contrato de formación.
     * @param float $daily_hours_1 Las horas diarias del primer año.
     * @param float $daily_hours_2 Las horas diarias del segundo año.
     * @param string|null $end_formation La fecha de finalización de la formación (opcional).
     * @return \Illuminate\Support\Collection La colección de elementos actualizados.
     */
    private function updateDates($training_contract_id, $daily_hours_1, $daily_hours_2, $end_formation = null)
    {
        $training_contract = TrainingContract::find($training_contract_id);
        $updated_elements = collect();
        $end_formation_carbon = $end_formation ? Carbon::parse($end_formation) : null;

        $training_contract_elements = TrainingContractElement::with('training_action')
            ->where('training_contract_id', $training_contract_id)
            ->orderBy('order', 'asc')
            ->get();

        Log::info('Total de elementos recuperados', ['count' => $training_contract_elements->count()]);

        if ($training_contract_elements->isEmpty()) {
            return $this->handleEmptyElements($training_contract);
        }

        $beginning = new Carbon($training_contract->beginning_formation);
        $first_year_end = $beginning->copy()->addYear()->subDay();

        foreach ($training_contract_elements as $element) {
            if ($element->course_id !== null) {
                $updated_elements->push($element);
                continue;
            }

            list($beginning, $end) = $this->calculateElementDates($element, $beginning, $first_year_end, $daily_hours_1, $daily_hours_2, $end_formation_carbon, $training_contract_id);

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

    /**
     * Maneja la actualización de los elementos cuando no hay elementos de formación disponibles.
     *
     * @param TrainingContract $training_contract El contrato de formación.
     * @return array Arreglo vacío ya que no hay elementos para actualizar.
     */
    private function handleEmptyElements($training_contract)
    {
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

    /**
     * Calcula las fechas de inicio y fin de un elemento de formación basado en las horas diarias.
     *
     * @param TrainingContractElement $element El elemento de formación.
     * @param Carbon $beginning La fecha de inicio.
     * @param Carbon $first_year_end La fecha de fin del primer año.
     * @param float $daily_hours_1 Las horas diarias del primer año.
     * @param float $daily_hours_2 Las horas diarias del segundo año.
     * @param Carbon|null $end_formation_carbon La fecha de finalización de la formación (opcional).
     * @param int $training_contract_id El ID del contrato de formación.
     * @return array Arreglo con la fecha de inicio y fin del elemento.
     */
    private function calculateElementDates($element, $beginning, $first_year_end, $daily_hours_1, $daily_hours_2, $end_formation_carbon, $training_contract_id)
    {
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

        return [$beginning, $end];
    }

    /**
     * Determina si un día específico es laborable según el contrato de formación.
     *
     * @param Carbon $date La fecha a verificar.
     * @param TrainingContract $record El contrato de formación.
     * @return bool Retorna true si es un día laborable, de lo contrario false.
     */
    private function isWorkingDay($date, $record)
    {
        if (TrainingContractsExcludedDay::nonWorkingDay($record->id, $date)) {
            return false;
        }

        if (TrainingContractFestival::existDay($date, $record->id)->first()) {
            return false;
        }

        $dayOfWeek = strtolower($date->format('l'));
        return $record->$dayOfWeek === 1;
    }

    /**
     * Determina si un día específico es no laborable según el contrato de formación.
     *
     * @param Carbon $date La fecha a verificar.
     * @param int $training_contract_id El ID del contrato de formación.
     * @return bool Retorna true si es un día no laborable, de lo contrario false.
     */
    private function isNonWorkingDay($date, $training_contract_id)
    {
        return TrainingContractsExcludedDay::nonWorkingDay($training_contract_id, $date->toDateString()) || 
               TrainingContractFestival::existDay($date, $training_contract_id)->first();
    }

    /**
     * Actualiza el registro del contrato de formación con los resultados calculados.
     *
     * @param TrainingContract $record El contrato de formación.
     * @param array $result Los resultados del cálculo de horas.
     */
    private function updateRecordWithResult($record, $result)
    {
        $record->update([
            'total_days' => $result['total_days'],
            'daily_hours_1' => $result['daily_hours_1'],
            'daily_hours_2' => $result['daily_hours_2'],
            'formative_hours_first_year' => $result['formative_hours_first_year'],
            'formative_hours_second_year' => $result['formative_hours_second_year'],
            'end_formation' => $result['end_formation'] ?? $record->end_formation,
        ]);
    }

    /**
     * Maneja la actualización de la fecha de finalización del contrato de formación si no estaba previamente definida.
     *
     * @param TrainingContract $record El contrato de formación.
     * @param \Illuminate\Support\Collection $updated_elements Los elementos actualizados.
     * @param string|null $initial_end_formation La fecha de finalización inicial.
     */
    private function handleLastElementUpdate($record, $updated_elements, $initial_end_formation)
    {
        $last_element = !empty($updated_elements) ? end($updated_elements) : null;
        if (!$initial_end_formation && $last_element) {
            $record->update(['end_formation' => $last_element->end]);
            Log::info('Fecha de fin de formación actualizada', ['end_formation' => $last_element->end]);
        }
    }

    /**
     * Calcula las horas de formación mensuales según el contrato de formación.
     *
     * @param int $training_contract_id El ID del contrato de formación.
     * @return array Un arreglo con el estado y las horas de formación mensuales.
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

            $daily_hours = $date->lt($beginning_date->copy()->addYear()) ? $record->daily_hours_1 : $record->daily_hours_2;
            $monthly_formation_hours[$date->format('Y-m')] = $cont_days * $daily_hours;
        }

        return [
            'status' => 200,
            'monthly_formation_hours' => $monthly_formation_hours,
        ];
    }

    /**
     * Recalcula las horas formativas de un contrato de formación basado en los elementos actualizados.
     *
     * @param TrainingContract $record El contrato de formación.
     * @param \Illuminate\Support\Collection $updated_elements Los elementos actualizados.
     * @return array Un arreglo con las horas formativas recalculadas para el primer y segundo año.
     */
    public function recalculateFormativeHours($record, $updated_elements)
    {
        $formative_hours_first_year = 0;
        $formative_hours_second_year = 0;
        $first_year_end = Carbon::parse($record->beginning_formation)->addYear()->subDay();

        foreach ($updated_elements as $element) {
            $element_start = Carbon::parse($element->beginning);
            $element_end = Carbon::parse($element->end);
            $element_days = $this->countWorkingDays($record, $element_start, $element_end);
            $daily_hours = $this->getDailyHours($element_start, Carbon::parse($record->beginning_formation), $record->daily_hours_1, $record->daily_hours_2);

            if ($element_end->lte($first_year_end)) {
                $formative_hours_first_year += $element_days * $daily_hours;
            } elseif ($element_start->gt($first_year_end)) {
                $formative_hours_second_year += $element_days * $daily_hours;
            } else {
                $first_year_days = $this->countWorkingDays($record, $element_start, $first_year_end);
                $second_year_days = $element_days - $first_year_days;
                $formative_hours_first_year += $first_year_days * $daily_hours;
                $formative_hours_second_year += $second_year_days * $this->getDailyHours($first_year_end->addDay(), Carbon::parse($record->beginning_formation), $record->daily_hours_1, $record->daily_hours_2);
            }
        }

        return [
            'formative_hours_first_year' => round($formative_hours_first_year, 2),
            'formative_hours_second_year' => round($formative_hours_second_year, 2),
        ];
    }

    /**
     * Cuenta los días laborables entre dos fechas específicas.
     *
     * @param TrainingContract $record El contrato de formación.
     * @param Carbon $start_date La fecha de inicio.
     * @param Carbon $end_date La fecha de finalización.
     * @return int El número total de días laborables.
     */
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

    /**
     * Obtiene el total de horas formativas de un contrato de formación.
     *
     * @param int $training_contract_id El ID del contrato de formación.
     * @return float El total de horas formativas.
     */
    public function getTotalHours($training_contract_id)
    {
        $training_contract = TrainingContract::findOrFail($training_contract_id);
        $total_hours = $training_contract->formative_hours_first_year + $training_contract->formative_hours_second_year;
        Log::info('Horas totales calculadas', ['total_hours' => $total_hours]);
        return $total_hours;
    }

    /**
     * Obtiene las horas diarias aplicables según la fecha y el año de formación.
     *
     * @param Carbon $date La fecha actual.
     * @param Carbon $beginning_formation_carbon La fecha de inicio de la formación.
     * @param float $daily_hours_1 Las horas diarias del primer año.
     * @param float $daily_hours_2 Las horas diarias del segundo año.
     * @return float Las horas diarias calculadas.
     */
    private function getDailyHours($date, $beginning_formation_carbon, $daily_hours_1, $daily_hours_2)
    {
        return $date->lt($beginning_formation_carbon->copy()->addYear()) ? $daily_hours_1 : $daily_hours_2;
    }
}
