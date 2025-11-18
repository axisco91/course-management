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
    public function create(array $data) {
        $training = TrainingContract::orderBy('id', 'desc')
            ->FilterMainCompany($data['main_company_id'])
            ->first();
        $id = $training ? $training->id + 1 : 1;
        $number_cfa = str_pad($id, 4, '0', STR_PAD_LEFT);

        $bonusYearOne = $data['bonus_hours_first_year'] ? $data['bonus_hours_first_year'] : 0;
        $bonusYearTwo = $data['bonus_hours_second_year'] ? $data['bonus_hours_second_year'] : 0;

        $trainingContract = TrainingContract::create([
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
            'bonus_hours_first_year' => round($bonusYearOne),
            'bonus_hours_second_year' => round($bonusYearTwo),
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
            'main_company_id' => $data['main_company_id'],
        ]);
        if (isset($data['excluded_day_id'])) {
            $trainingContract->excludedDays()->sync($data['excluded_day_id']);
        }

        return $trainingContract;
    }

    public function update(TrainingContract $trainingContract, array $data) {
        $bonusYearOne = $data['bonus_hours_first_year'] ?? 0;
        $bonusYearTwo = $data['bonus_hours_second_year'] ?? 0;

        $trainingContract->update([
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
            // 'formation_hours' => $data['formation_hours'],
            'annually_day_hours' => $data['annually_day_hours'],
            'bonus_hours_first_year' =>  round($bonusYearOne),
            'bonus_hours_second_year' => round($bonusYearTwo),
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
            'formative_hours_first_year' => $data['formative_hours_first_year'] ?? 0,
            'formative_hours_second_year' => $data['formative_hours_second_year'] ?? 0,
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
            'bonification' => $data['bonification'] ?? $trainingContract->bonification,
        ]);
        return $trainingContract;
    }

    public function updateDocumentClause(TrainingContract $trainingContract, array $data) {
        $trainingContract->update([
            'document_additional_clause' => $data['document_additional_clause'],
        ]);
        return $trainingContract;
    }

    /**
     * Calcula las horas formativas y actualiza los datos del contrato de formación.
     *
     * @param int $trainingContractId El ID del contrato de formación.
     * @return array Un arreglo con los datos actualizados del contrato de formación.
     * @throws Exception Si no hay días laborables en el período del primer año.
     */
    public function calculateHours($record)
    {
        // Recuperamos el contrato de formación por su ID, si no existe lanza una excepción
        $trainingContractId = $record->id;

        // Registramos en el log que se ha llamado al método, incluyendo el ID del contrato
        Log::info('calculateHours llamado', ['training_contract_id' => $trainingContractId]);

        // Registramos en el log los datos del contrato recuperado
        Log::info('Registro recuperado', ['record' => $record->toArray()]);

        // Convertimos la fecha de inicio de la formación a un objeto Carbon
        $beginning_formation_carbon = Carbon::parse($record->beginning_formation);

        // Calculamos el total de horas de formación usando un método auxiliar
        $total_hours = $this->calculateTotalHours($trainingContractId);

        // Obtenemos las horas de bonificación para el primer y segundo año
        $bonus_hours_first_year = $record->bonus_hours_first_year;
        $bonus_hours_second_year = $record->bonus_hours_second_year;

        // Registramos en el log las horas de bonificación obtenidas
        Log::info('Horas de bonificación', [
            'bonus_hours_first_year' => $bonus_hours_first_year,
            'bonus_hours_second_year' => $bonus_hours_second_year
        ]);

        // Guardamos la fecha de fin original antes de recalcularla
        $initial_end_formation = $record->end_formation;

        // Si ya existe una fecha de finalización, usamos el método de cálculo con fecha
        if ($record->end_formation) {
            $result = $this->calculateWithEndDate(
                $record,
                $beginning_formation_carbon,
                $total_hours,
                $bonus_hours_first_year,
                $bonus_hours_second_year
            );
        } else {
            // Si no hay fecha final, usamos el método que calcula sin fecha
            $result = $this->calculateWithoutEndDate(
                $record,
                $beginning_formation_carbon,
                $total_hours,
                $bonus_hours_first_year,
                $bonus_hours_second_year
            );
        }

        // Actualizamos el contrato con los datos calculados
        $this->updateRecordWithResult($record, $result);

        // Actualizamos las fechas de los elementos relacionados al contrato
        $updated_elements = $this->updateDates(
            $trainingContractId,
            $result['daily_hours_1'],
            $result['daily_hours_2'],
            $record->end_formation
        );

        // Gestionamos la actualización del último elemento si hubo cambios importantes
        $this->handleLastElementUpdate($record, $updated_elements, $initial_end_formation);

        // Registramos en el log que el método ha terminado con éxito
        Log::info('Fin de calculateHours', $result);

        // Devolvemos el resultado combinado con los elementos actualizados
        return array_merge($result, ['updated_elements' => $updated_elements]);
    }


    /**
     * Calcula el total de horas de un contrato de formación basado en sus elementos.
     *
     * @param int $trainingContractId El ID del contrato de formación.
     * @return float El total de horas.
     */
    private function calculateTotalHours(int $trainingContractId): int
    {
        $elements = TrainingContractElement::where('training_contract_id', $trainingContractId)
            ->with([
                'training_action:id,total_hours',
                'certification:id,total_hours',
            ])
            ->get();

        // 1) Certificados seleccionados (únicos)
        $selectedCertIds = $elements->pluck('certification_id')->filter()->unique();

        // Horas de certificados (evita duplicados)
        $hoursFromCerts = $elements
            ->filter(fn ($e) => !is_null($e->certification_id) && $e->certification) // existe relación cargada
            ->unique(fn ($e) => $e->certification_id)
            ->sum(fn ($e) => (int) ($e->certification->total_hours ?? 0));

        // 2) Acciones seleccionadas (únicas)
        $actions = $elements
            ->pluck('training_action')
            ->filter()               // quita nulls
            ->unique('id');          // evita duplicados de la misma acción

        // 3) Excluir acciones cuyo certificado ya está seleccionado
        $actionsToCount = $actions->reject(function ($action) use ($selectedCertIds) {
            $certId = $action->certification_id ?? null;
            return $certId && $selectedCertIds->contains($certId);
        });

        $hoursFromActions = $actionsToCount->sum(fn ($a) => (int) ($a->total_hours ?? 0));

        return (int) ($hoursFromCerts + $hoursFromActions);
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
    private function  calculateWithEndDate($record, $beginning_formation_carbon, $total_hours, $bonus_hours_first_year, $bonus_hours_second_year)
    {
        // Convertimos la fecha de inicio de la formación a un objeto Carbon
        $date = Carbon::parse($record->beginning_formation);

        // Convertimos la fecha de finalización de la formación a un objeto Carbon
        $end_date = Carbon::parse($record->end_formation);

        // Calculamos los días laborables entre las fechas, incluyendo días del primer y segundo año
        list($cont_days_first_year, $cont_days_second_year, $totalDays) = $this->calculateWorkingDays(
            $date,
            $end_date,
            $beginning_formation_carbon,
            $record
        );

        // Si no hay días laborables en el primer año, lanzamos una excepción
        if ($cont_days_first_year == 0) {
            throw new Exception("No hay días laborables en el período del primer año");
        }

        // Distribuimos las horas totales de formación (más bonificaciones) entre los días laborables
        return $this->distributeHours(
            $total_hours,
            $bonus_hours_first_year,
            $bonus_hours_second_year,
            $cont_days_first_year,
            $cont_days_second_year,
            $totalDays
        );
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
        // Convertimos la fecha de inicio de la formación en un objeto Carbon para manipulación de fechas
        $date = Carbon::parse($record->beginning_formation);

        // Calculamos la fecha estimada de fin, los días laborales del primer y segundo año, y el total de días
        list($calculated_end_date, $cont_days_first_year, $cont_days_second_year, $totalDays) = $this->calculateEndDate(
            $date,
            $total_hours,
            $beginning_formation_carbon,
            $record
        );

        // Distribuimos las horas formativas y bonificadas entre los días laborales calculados
        $result = $this->distributeHours(
            $total_hours,
            $bonus_hours_first_year,
            $bonus_hours_second_year,
            $cont_days_first_year,
            $cont_days_second_year,
            $totalDays
        );

        // Añadimos la fecha de fin calculada al resultado final
        $result['end_formation'] = $calculated_end_date->toDateString();

        // Devolvemos todos los datos generados (horas distribuidas y fecha de fin)
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
     * @param int $totalDays El total de días laborables.
     * @return array Los resultados del cálculo de horas distribuidas.
     */
    private function distributeHours($total_hours, $bonus_hours_first_year, $bonus_hours_second_year, $cont_days_first_year, $cont_days_second_year, $totalDays)
    {
        // Sumamos las horas bonificadas totales de ambos años
        $total_bonus_hours = $bonus_hours_first_year + $bonus_hours_second_year;

        // Si el total de horas requeridas supera las horas bonificadas, distribuimos el exceso
        if ($total_hours > $total_bonus_hours) {
            $exceso_horas = $total_hours - $total_bonus_hours;

            // Si no hay días en el segundo año, asignamos todo el exceso al primer año
            if ($cont_days_second_year == 0) {
                $bonus_hours_first_year += $exceso_horas;
            } else {
                // Calculamos la proporción de días entre ambos años
                $total_working_days = $cont_days_first_year + $cont_days_second_year;
                $proportion_first_year = $cont_days_first_year / $total_working_days;
                $proportion_second_year = $cont_days_second_year / $total_working_days;

                // Distribuimos el exceso proporcionalmente entre los dos años
                $bonus_hours_first_year += $exceso_horas * $proportion_first_year;
                $bonus_hours_second_year += $exceso_horas * $proportion_second_year;
            }
        }

        // Calculamos las horas diarias para cada año, redondeando a dos decimales
        $daily_hours_1 = $cont_days_first_year > 0 ? round($bonus_hours_first_year / $cont_days_first_year, 2) : 0;
        $daily_hours_2 = $cont_days_second_year > 0 ? round($bonus_hours_second_year / $cont_days_second_year, 2) : 0;

        // Devolvemos los resultados en un array estructurado
        return [
            'formative_hours_first_year' => round($bonus_hours_first_year, 2),
            'formative_hours_second_year' => round($bonus_hours_second_year, 2),
            'daily_hours_1' => $daily_hours_1,
            'daily_hours_2' => $daily_hours_2,
            'cont_days_first_year' => $cont_days_first_year,
            'cont_days_second_year' => $cont_days_second_year,
            'total_days' => $totalDays
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
        $totalDays = 0;

        $yearSplit = $beginning_formation_carbon->copy()->addYear();

        for ($date = $start_date; $date->lte($end_date); $date->addDay()) {
            if ($this->isWorkingDay($date, $record)) {
                if ($date->lt($yearSplit)) {
                    $cont_days_first_year++;
                } else {
                    $cont_days_second_year++;
                }
                $totalDays++;
            }
        }

        return [$cont_days_first_year, $cont_days_second_year, $totalDays];
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
        // Inicializamos contadores para los días de formación del primer y segundo año
        $cont_days_first_year = 0;
        $cont_days_second_year = 0;
        $totalDays = 0;
        $current_hours = 0; // Horas acumuladas hasta el momento

        // Copiamos la fecha de inicio para comenzar a contar desde allí
        $date = $start_date->copy();

        // Iteramos día a día hasta acumular todas las horas requeridas
        while ($current_hours < $total_hours) {
            // Verificamos si el día actual es laborable
            if ($this->isWorkingDay($date, $record)) {

                // Si el día es menor a un año desde el inicio, lo consideramos del primer año
                if ($date->lt($beginning_formation_carbon->copy()->addYear())) {
                    $cont_days_first_year++;
                } else {
                    // Si no, es parte del segundo año
                    $cont_days_second_year++;
                }

                $totalDays++; // Aumentamos el conteo total de días de formación

                // Sumamos las horas correspondientes al día actual (según si es del primer o segundo año)
                $current_hours += $this->getDailyHours(
                    $date,
                    $beginning_formation_carbon,
                    $record->daily_hours_1,
                    $record->daily_hours_2
                );
            }

            // Avanzamos al siguiente día
            $date->addDay();
        }

        // Devolvemos la fecha final (restando un día porque ya se sumó uno de más), y los contadores de días
        return [$date->subDay(), $cont_days_first_year, $cont_days_second_year, $totalDays];
    }

    /**
     * Actualiza las fechas de los elementos de formación de acuerdo con las horas diarias calculadas.
     *
     * @param int $trainingContractId El ID del contrato de formación.
     * @param float $daily_hours_1 Las horas diarias del primer año.
     * @param float $daily_hours_2 Las horas diarias del segundo año.
     * @param string|null $end_formation La fecha de finalización de la formación (opcional).
     * @return \Illuminate\Support\Collection La colección de elementos actualizados.
     */
    private function updateDates($trainingContractId, $daily_hours_1, $daily_hours_2, $end_formation = null)
    {
        $trainingContract = TrainingContract::find($trainingContractId);
        $updated_elements = collect();
        $end_formation_carbon = $end_formation ? Carbon::parse($end_formation) : null;

        $trainingContractElements = TrainingContractElement::with('training_action')
            ->where('training_contract_id', $trainingContractId)
            ->orderBy('order', 'asc')
            ->get();

        foreach($trainingContractElements as $trainingContractElement) {
            if (!$trainingContractElement->course_id ) {
                $trainingContractElement->update([
                    'beginning' => null,
                    'end' => null,
                ]);
            }
        }

        Log::info('Total de elementos recuperados', ['count' => $trainingContractElements->count()]);

        if ($trainingContractElements->isEmpty()) {
            return $this->handleEmptyElements($trainingContract);
        }

        $beginning = Carbon::parse($trainingContract->beginning_formation);
        $first_year_limit = $beginning->copy()->addYear();
        $currentDate = $beginning->copy();

        foreach ($trainingContractElements as $element) {
            if (
                $element->course_id !== null ||
                ($element->beginning && Carbon::parse($element->beginning)->isPast())
            ) {
                $updated_elements->push($element);
                $currentDate = Carbon::parse($element->end)->copy()->addDay();
                continue;
            }

            if ($element->training_action) {
                $elementHours = $element->training_action ? $element->training_action->total_hours : 0;
            } else {
                $elementHours = $element->certification ? $element->certification->total_hours : 0;
            }

            $accumulatedHours = 0;
            $courseStart = null;
            $courseEnd = null;

            while ($accumulatedHours < $elementHours && $currentDate->lte($end_formation_carbon)) {
                if (!$this->isNonWorkingDay($currentDate, $trainingContractId, $trainingContract->main_company_id)) {
                    // Decide which daily hours apply (first or second year)
                    $current_daily_hours = $currentDate->lt($first_year_limit)
                        ? $daily_hours_1
                        : $daily_hours_2;

                    // Determine how many hours we can allocate today
                    $hoursToday = min($current_daily_hours, $elementHours - $accumulatedHours);

                    if ($courseStart === null) {
                        $courseStart = $currentDate->copy();
                    }

                    $accumulatedHours += $hoursToday;
                    $courseEnd = $currentDate->copy();
                }

                $currentDate->addDay();
            }

            $element->update([
                'beginning' => $courseStart ? $courseStart->toDateString() : null,
                'end' => $courseEnd ? $courseEnd->toDateString() : null
            ]);

            $updated_elements->push($element);

            // Skip non-working days after setting new current date
            while ($this->isNonWorkingDay($currentDate, $trainingContractId, $trainingContract->main_company_id)) {
                $currentDate->addDay();
            }

            if ($currentDate->gt($end_formation_carbon)) {
                break;
            }
        }

        return $updated_elements;
    }

    /**
     * Maneja la actualización de los elementos cuando no hay elementos de formación disponibles.
     *
     * @param TrainingContract $trainingContract El contrato de formación.
     * @return array Arreglo vacío ya que no hay elementos para actualizar.
     */
    private function handleEmptyElements($trainingContract)
    {
        list($cont_days_first_year, $cont_days_second_year, $total_days) = $this->calculateWorkingDays(
            Carbon::parse($trainingContract->beginning_formation),
            Carbon::parse($trainingContract->end_formation),
            Carbon::parse($trainingContract->beginning_formation),
            $trainingContract
        );

        $formation_hours = $trainingContract->trainingContractElements()
            ->with('training_action')
            ->get()
            ->sum(function ($element) {
                return $element->training_action ? $element->training_action->total_hours : 0;
            });

        $total_days = $cont_days_first_year + $cont_days_second_year;

        // Calculate daily hours directly — no proportional split needed
        $daily_hours_1 = $total_days > 0 ? round($formation_hours / $total_days, 2) : 0;
        $daily_hours_2 = 0;

        $trainingContract->update([
            'daily_hours_1' => $daily_hours_1,
            'daily_hours_2' => $daily_hours_2,
            'formation_hours' => $formation_hours,
            'formative_hours_first_year' => round($formation_hours * ($cont_days_first_year / $total_days), 2),
            'formative_hours_second_year' => round($formation_hours * ($cont_days_second_year / $total_days), 2),
            'total_days' => $total_days,
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
     * @param int $trainingContractId El ID del contrato de formación.
     * @return array Arreglo con la fecha de inicio y fin del elemento.
     */
    private function calculateElementDates($element, $beginning, $first_year_end, $daily_hours_1, $daily_hours_2, $end_formation_carbon, $trainingContractId)
    {
        $hours = $element->training_action ? $element->training_action->total_hours : 0;
        $current_daily_hours = $beginning->lte($first_year_end) ? $daily_hours_1 : $daily_hours_2;
        $days_to_add = $current_daily_hours > 0 ? floor($hours / $current_daily_hours) : 0;

        $end = $beginning->copy();
        $isClamped = false;

        for ($i = 0; $i < $days_to_add; $i++) {
            do {
                $end->addDay();
            } while ($this->isNonWorkingDay($end, $trainingContractId, $element->main_company_id));

            if ($end->gt($end_formation_carbon)) {
                $end = $end_formation_carbon;
                $isClamped = true;
                break;
            }
        }

        // Only backtrack if the date wasn't already forced to end_formation
        if (!$isClamped) {
            while ($this->isNonWorkingDay($end, $trainingContractId, $element->main_company_id)) {
                $end->subDay();
            }
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
        if (TrainingContractsExcludedDay::nonWorkingDay($record->id, $date, $record->main_company_id)) {
            return false;
        }

        if (TrainingContractFestival::existDay($date, $record->id, $record->main_company_id)->first()) {
            return false;
        }

        $dayOfWeek = strtolower($date->format('l'));
        return $record->$dayOfWeek === 1;
    }

    /**
     * Determina si un día específico es no laborable según el contrato de formación.
     *
     * @param Carbon $date La fecha a verificar.
     * @param int $trainingContractId El ID del contrato de formación.
     * @return bool Retorna true si es un día no laborable, de lo contrario false.
     */
    private function isNonWorkingDay($date, $trainingContractId, $mainCompanyId)
    {
        return TrainingContractsExcludedDay::nonWorkingDay($trainingContractId, $date->toDateString(), $mainCompanyId) ||
               TrainingContractFestival::existDay($date, $trainingContractId, $mainCompanyId)->first();
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
        // Si no hay elementos actualizados, no hacemos nada
        if (empty($updated_elements)) return;

        // Obtenemos el último elemento actualizado dependiendo si es un array o una colección
        $lastElement = is_array($updated_elements)
            ? end($updated_elements)
            : $updated_elements->last();

        // Verificamos que haya un último elemento y que el contrato tenga una fecha de fin de formación
        if ($lastElement && $record->end_formation) {
            // Obtenemos la fecha de fin actual del último elemento
            $lastDate = Carbon::parse($lastElement->end); // Nota: el campo es 'end', no 'end_date'

            // Obtenemos la nueva fecha de fin de formación del contrato
            $finalDate = Carbon::parse($record->end_formation);

            // Si la fecha actual del elemento es anterior a la fecha de fin del contrato, la actualizamos
            if ($lastDate->lt($finalDate)) {
                $lastElement->end = $finalDate; // Corregimos la fecha de fin del último elemento
                $lastElement->save(); // Guardamos los cambios en base de datos
            }
        }
    }

    /**
     * Calcula las horas de formación mensuales según el contrato de formación.
     *
     * @param int $trainingContractId El ID del contrato de formación.
     * @return array Un arreglo con el estado y las horas de formación mensuales.
     */
    public function calculateMonthlyFormationHours($trainingContractId)
    {
        $record = TrainingContract::findOrFail($trainingContractId);

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
     * @param int $trainingContractId El ID del contrato de formación.
     * @return float El total de horas formativas.
     */
    public function getTotalHours($trainingContractId)
    {
        $trainingContract = TrainingContract::findOrFail($trainingContractId);
        $total_hours = $trainingContract->formative_hours_first_year + $trainingContract->formative_hours_second_year;
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
