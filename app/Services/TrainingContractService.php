<?php

namespace App\Services;

use App\Models\TrainingContract;
use App\Models\TrainingContractElement;
use App\Models\TrainingContractsExcludedDay;
use App\Models\TrainingContractFestival;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class TrainingContractService
{
    private function normalizeNumber($value, $default = 0)
    {
        if ($value === null || $value === '') {
            return $default;
        }

        return is_numeric($value) ? $value + 0 : $default;
    }

    private function normalizeDate($value): ?string
    {
        if ($value === null || $value === '' || $value === 'null') {
            return null;
        }

        if ($value instanceof Carbon) {
            return $value->format('Y-m-d');
        }

        if (preg_match('/^\d{2}-\d{2}-\d{4}$/', $value)) {
            return Carbon::createFromFormat('d-m-Y', $value)->format('Y-m-d');
        }

        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
            return Carbon::createFromFormat('Y-m-d', $value)->format('Y-m-d');
        }

        return Carbon::parse($value)->format('Y-m-d');
    }

    private function buildCreateAttributes(array $data): array
    {
        $bonusYearOne = $this->normalizeNumber($data['bonus_hours_first_year'] ?? null);
        $bonusYearTwo = $this->normalizeNumber($data['bonus_hours_second_year'] ?? null);

        return [
            'company_id' => $data['company_id'],
            'student_id' => $data['student_id'],
            'company_tutor' => $data['company_tutor'],
            'company_tutor_dni' => $data['company_tutor_dni'],
            'occupation_id' => $data['occupation_id'],
            'center_of_work' => $data['center_of_work'],
            'remuneration' => $data['remuneration'] ?? null,
            'remuneration_period' => $data['remuneration_period'] ?? null,
            'annual_holidays' => $data['annual_holidays'] ?? null,
            'province_id' => $data['province_id'],
            'beginning' => $this->normalizeDate($data['beginning'] ?? null),
            'end' => $this->normalizeDate($data['end'] ?? null),
            'beginning_formation' => $this->normalizeDate($data['beginning_formation'] ?? null),
            'end_formation' => $this->normalizeDate($data['end_formation'] ?? null),
            'annually_day_hours' => $data['annually_day_hours'],
            'bonus_hours_first_year' => round($bonusYearOne),
            'bonus_hours_second_year' => round($bonusYearTwo),
            'training_schedule' => $data['training_schedule'],
            'working_hours' => $data['working_hours'],
            'complete_schedule' => $data['complete_schedule'],
            'training_contract_status_id' => $data['training_contract_status_id'],
            'on_leave_type_id' => $data['on_leave_type_id'],
            'on_leave_date' => $this->normalizeDate($data['on_leave_date'] ?? null),
            'advisor_id' => $data['advisor_id'],
            'collaborator_id' => $data['collaborator_id'],
            'percentage_first_year' => $this->normalizeNumber($data['percentage_first_year'] ?? null),
            'percentage_second_year' => $this->normalizeNumber($data['percentage_second_year'] ?? null),
            'formative_hours_first_year' => $this->normalizeNumber($data['formative_hours_first_year'] ?? null),
            'formative_hours_second_year' => $this->normalizeNumber($data['formative_hours_second_year'] ?? null),
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
            'daily_hours_1' => $this->normalizeNumber($data['daily_hours_1'] ?? null),
            'daily_hours_2' => $this->normalizeNumber($data['daily_hours_2'] ?? null),
            'bonification' => $data['bonification'] ?? false,
            'main_company_id' => $data['main_company_id'],
        ];
    }

    private function findExistingContract(array $attributes): ?TrainingContract
    {
        $query = TrainingContract::query();

        foreach ($attributes as $column => $value) {
            if ($value === null) {
                $query->whereNull($column);
            } else {
                $query->where($column, $value);
            }
        }

        return $query->lockForUpdate()->first();
    }

    public function create(array $data) {
        $attributes = $this->buildCreateAttributes($data);

        $existingContract = $this->findExistingContract($attributes);

        if ($existingContract) {
            return $existingContract;
        }

        $training = TrainingContract::orderBy('id', 'desc')
            ->FilterMainCompany($data['main_company_id'])
            ->lockForUpdate()
            ->first();
        $id = $training ? $training->id + 1 : 1;
        $number_cfa = str_pad($id, 4, '0', STR_PAD_LEFT);

        $trainingContract = TrainingContract::create(array_merge(
            ['number_cfa' => $number_cfa],
            $attributes
        ));
        if (isset($data['excluded_day_id'])) {
            $trainingContract->excludedDays()->sync($data['excluded_day_id']);
        }

        return $trainingContract;
    }

    public function update(TrainingContract $trainingContract, array $data) {
        $bonusYearOne = $this->normalizeNumber($data['bonus_hours_first_year'] ?? null);
        $bonusYearTwo = $this->normalizeNumber($data['bonus_hours_second_year'] ?? null);

        $trainingContract->update([
            'company_id' => $data['company_id'],
            'student_id' => $data['student_id'],
            'company_tutor' => $data['company_tutor'],
            'company_tutor_dni' => $data['company_tutor_dni'],
            'occupation_id' => $data['occupation_id'],
            'center_of_work' => $data['center_of_work'],
            'remuneration' => $data['remuneration'] ?? null,
            'remuneration_period' => $data['remuneration_period'] ?? null,
            'annual_holidays' => $data['annual_holidays'] ?? null,
            'province_id' => $data['province_id'],
            'beginning' => $this->normalizeDate($data['beginning'] ?? null),
            'end' => $this->normalizeDate($data['end'] ?? null),
            'beginning_formation' => $this->normalizeDate($data['beginning_formation'] ?? null),
            'end_formation' => $this->normalizeDate($data['end_formation'] ?? null),
            // 'formation_hours' => $data['formation_hours'],
            'annually_day_hours' => $data['annually_day_hours'],
            'bonus_hours_first_year' =>  round($bonusYearOne),
            'bonus_hours_second_year' => round($bonusYearTwo),
            'training_schedule' => $data['training_schedule'],
            'working_hours' => $data['working_hours'],
            'complete_schedule' => $data['complete_schedule'],
            'training_contract_status_id' => $data['training_contract_status_id'],
            'on_leave_type_id' => $data['on_leave_type_id'],
            'on_leave_date' => $this->normalizeDate($data['on_leave_date'] ?? null),
            'advisor_id' => $data['advisor_id'],
            'collaborator_id' => $data['collaborator_id'],
            'percentage_first_year' => $this->normalizeNumber($data['percentage_first_year'] ?? $trainingContract->percentage_first_year),
            'percentage_second_year' => $this->normalizeNumber($data['percentage_second_year'] ?? null),
            'formative_hours_first_year' => $this->normalizeNumber($data['formative_hours_first_year'] ?? null),
            'formative_hours_second_year' => $this->normalizeNumber($data['formative_hours_second_year'] ?? null),
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
            'daily_hours_1' => $this->normalizeNumber($data['daily_hours_1'] ?? null),
            'daily_hours_2' => $this->normalizeNumber($data['daily_hours_2'] ?? null),
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
    public function calculateHours($record, float $tolerancePercentage = 0)
    {
        return DB::transaction(fn () => $this->performCalculateHours($record, $tolerancePercentage));
    }

    private function performCalculateHours($record, float $tolerancePercentage)
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
            $result['daily_hours_1_internal'],
            $result['daily_hours_2_internal'],
            $record->end_formation,
            $tolerancePercentage
        );

        // Gestionamos la actualización del último elemento si hubo cambios importantes
        $this->handleLastElementUpdate($record, $updated_elements['updated_elements'], $initial_end_formation);

        // Registramos en el log que el método ha terminado con éxito
        Log::info('Fin de calculateHours', $result);

        // Devolvemos el resultado combinado con los elementos actualizados
        return array_merge($result, $updated_elements);
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

        // Conservamos la precisión completa para el calendario y redondeamos solo los valores visibles.
        $daily_hours_1_internal = $cont_days_first_year > 0 ? $bonus_hours_first_year / $cont_days_first_year : 0;
        $daily_hours_2_internal = $cont_days_second_year > 0 ? $bonus_hours_second_year / $cont_days_second_year : 0;

        // Devolvemos los resultados en un array estructurado
        return [
            'formative_hours_first_year' => round($bonus_hours_first_year, 2),
            'formative_hours_second_year' => round($bonus_hours_second_year, 2),
            'daily_hours_1' => round($daily_hours_1_internal, 2),
            'daily_hours_2' => round($daily_hours_2_internal, 2),
            'daily_hours_1_internal' => $daily_hours_1_internal,
            'daily_hours_2_internal' => $daily_hours_2_internal,
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
    private function updateDates($trainingContractId, $daily_hours_1, $daily_hours_2, $end_formation = null, float $tolerancePercentage = 0)
    {
        $trainingContract = TrainingContract::find($trainingContractId);
        $end_formation_carbon = $end_formation ? Carbon::parse($end_formation) : null;

        $trainingContractElements = TrainingContractElement::with(['training_action', 'certification'])
            ->where('training_contract_id', $trainingContractId)
            ->orderBy('order', 'asc')
            ->get();

        Log::info('Total de elementos recuperados', ['count' => $trainingContractElements->count()]);

        if ($trainingContractElements->isEmpty()) {
            return [
                'updated_elements' => collect($this->handleEmptyElements($trainingContract)),
                'compacted_days' => 0,
                'compacted_courses' => 0,
                'effective_tolerance_percentage' => 0,
                'adjustment_warning' => null,
            ];
        }

        $calendar = $this->loadItineraryCalendar($trainingContract);
        $targetEnd = $this->lastWorkingDayOnOrBefore($end_formation_carbon, $trainingContract, $calendar);
        $compactedDayCounts = [];
        $usedTolerancePercentages = [];
        $simulation = $this->simulateElementDates(
            $trainingContractElements,
            $trainingContract,
            $daily_hours_1,
            $daily_hours_2,
            $compactedDayCounts,
            $tolerancePercentage,
            $calendar
        );

        while ($simulation['last_end'] && $simulation['last_end']->gt($targetEnd)) {
            $candidates = collect($simulation['candidates'])
                ->sort(function ($left, $right) {
                    $coverageComparison = $right['currently_meets_hours'] <=> $left['currently_meets_hours'];
                    if ($coverageComparison !== 0) {
                        return $coverageComparison;
                    }

                    $hoursComparison = $right['element_hours'] <=> $left['element_hours'];

                    return $hoursComparison !== 0
                        ? $hoursComparison
                        : $left['resulting_deficit_percentage'] <=> $right['resulting_deficit_percentage'];
                });
            $selectedCandidate = null;
            $selectedSimulation = null;

            foreach ($candidates as $candidate) {
                $trialCounts = $compactedDayCounts;
                $trialCounts[$candidate['element_id']] = ($trialCounts[$candidate['element_id']] ?? 0) + 1;
                $trial = $this->simulateElementDates(
                    $trainingContractElements,
                    $trainingContract,
                    $daily_hours_1,
                    $daily_hours_2,
                    $trialCounts,
                    $tolerancePercentage,
                    $calendar
                );

                if (
                    ($trial['applied_compactions'][$candidate['element_id']] ?? 0) === $trialCounts[$candidate['element_id']]
                    && $trial['last_end']
                    && $trial['last_end']->lt($simulation['last_end'])
                ) {
                    $selectedCandidate = $candidate;
                    $selectedSimulation = $trial;
                    break;
                }
            }

            if (!$selectedCandidate || !$selectedSimulation) {
                throw new \DomainException(
                    'El itinerario no cabe con la tolerancia máxima indicada. Amplía la fecha final o aumenta la tolerancia.'
                );
            }

            $compactedDayCounts = $selectedSimulation['applied_compactions'];
            $usedTolerancePercentages[$selectedCandidate['element_id']] = $selectedCandidate['resulting_deficit_percentage'];
            $simulation = $selectedSimulation;
        }

        $warning = null;
        if (!$simulation['last_end'] || !$simulation['last_end']->equalTo($targetEnd)) {
            $warning = 'Las fechas de los cursos ya creados impiden ocupar exactamente todo el período formativo.';
        }

        foreach ($trainingContractElements as $element) {
            if ($element->course_id === null) {
                $element->update($simulation['assignments'][$element->id] ?? [
                    'beginning' => null,
                    'end' => null,
                ]);
            }
        }

        $effectivePercentages = array_intersect_key(
            $usedTolerancePercentages,
            $compactedDayCounts
        );

        $freshElements = $trainingContractElements->fresh(['training_action', 'certification']);
        $this->appendAssignedScheduleMetrics($trainingContract, $freshElements);

        return [
            'updated_elements' => $freshElements,
            'compacted_days' => array_sum($compactedDayCounts),
            'compacted_courses' => count($compactedDayCounts),
            'effective_tolerance_percentage' => empty($effectivePercentages)
                ? 0
                : round(max($effectivePercentages), 2),
            'adjustment_warning' => $warning,
        ];
    }

    public function appendAssignedScheduleMetrics(TrainingContract $trainingContract, $elements)
    {
        $calendar = $this->loadItineraryCalendar($trainingContract);
        $firstYearLimit = Carbon::parse($trainingContract->beginning_formation)->addYear();

        foreach ($elements as $element) {
            $assignedTrainingDays = 0;
            $assignedHours = 0.0;

            if ($element->beginning && $element->end) {
                $date = Carbon::parse($element->beginning);
                $end = Carbon::parse($element->end);

                while ($date->lte($end)) {
                    if ($this->isItineraryWorkingDay($date, $trainingContract, $calendar)) {
                        $assignedTrainingDays++;
                        $dailyHours = $date->lt($firstYearLimit)
                            ? (float) $trainingContract->daily_hours_1
                            : (float) $trainingContract->daily_hours_2;
                        if ($dailyHours <= 0) {
                            $dailyHours = max(
                                (float) $trainingContract->daily_hours_1,
                                (float) $trainingContract->daily_hours_2
                            );
                        }
                        $assignedHours += $dailyHours;
                    }

                    $date->addDay();
                }
            }

            $element->setAttribute('assigned_training_days', $assignedTrainingDays);
            $element->setAttribute('assigned_hours', round($assignedHours, 2));
        }

        return $elements;
    }

    private function simulateElementDates(
        $elements,
        $trainingContract,
        float $dailyHoursFirstYear,
        float $dailyHoursSecondYear,
        array $compactedDayCounts,
        float $maximumTolerance,
        array $calendar
    ): array {
        $beginning = Carbon::parse($trainingContract->beginning_formation);
        $firstYearLimit = $beginning->copy()->addYear();
        $currentDate = $beginning->copy();
        $assignments = [];
        $candidates = [];
        $appliedCompactions = [];
        $lastEnd = null;

        foreach ($elements as $element) {
            if ($element->course_id !== null) {
                if ($element->end) {
                    $lastEnd = Carbon::parse($element->end);
                    $currentDate = $lastEnd->copy()->addDay();
                }
                continue;
            }

            $elementHours = (float) ($element->training_action?->total_hours
                ?? $element->certification?->total_hours
                ?? 0);
            if ($elementHours <= 0) {
                throw new \DomainException('Hay un elemento del itinerario sin horas formativas válidas.');
            }

            $accumulatedHours = 0.0;
            $strictDays = [];
            $scanDate = $currentDate->copy();

            while ($accumulatedHours + 0.0000001 < $elementHours) {
                $scanDate = $this->nextWorkingDay($scanDate, $trainingContract, $calendar);
                $dailyHours = $scanDate->lt($firstYearLimit)
                    ? $dailyHoursFirstYear
                    : $dailyHoursSecondYear;
                if (
                    $dailyHours <= 0
                    && $trainingContract->end_formation
                    && $scanDate->gt(Carbon::parse($trainingContract->end_formation))
                ) {
                    // Para medir el exceso estricto podemos simular fuera del período con la última tarifa válida.
                    $dailyHours = max($dailyHoursFirstYear, $dailyHoursSecondYear);
                }
                if ($dailyHours <= 0) {
                    throw new \DomainException('No hay horas diarias disponibles para completar el itinerario.');
                }

                $allocatedHours = min($dailyHours, $elementHours - $accumulatedHours);
                $strictDays[] = [
                    'date' => $scanDate->copy(),
                    'capacity' => $dailyHours,
                    'allocated' => $allocatedHours,
                ];
                $accumulatedHours += $allocatedHours;
                $scanDate->addDay();
            }

            $requestedRemovals = (int) ($compactedDayCounts[$element->id] ?? 0);
            $appliedRemovals = min($requestedRemovals, max(0, count($strictDays) - 1));
            $retainedDays = array_slice($strictDays, 0, count($strictDays) - $appliedRemovals);
            if ($appliedRemovals > 0) {
                $appliedCompactions[$element->id] = $appliedRemovals;
            }

            $courseStart = $retainedDays[0]['date'];
            $courseEnd = $retainedDays[count($retainedDays) - 1]['date'];
            $assignedCapacity = array_sum(array_column($retainedDays, 'capacity'));
            $currentDate = $courseEnd->copy()->addDay();

            if (count($retainedDays) > 1) {
                $capacityAfterRemoval = $assignedCapacity - $retainedDays[count($retainedDays) - 1]['capacity'];
                $resultingDeficit = max(0, $elementHours - $capacityAfterRemoval);
                $resultingDeficitPercentage = ($resultingDeficit / $elementHours) * 100;

                if ($resultingDeficitPercentage <= $maximumTolerance + 0.0000001) {
                    $candidates[] = [
                        'element_id' => $element->id,
                        'element_hours' => $elementHours,
                        'currently_meets_hours' => $assignedCapacity + 0.0000001 >= $elementHours,
                        'resulting_deficit_percentage' => $resultingDeficitPercentage,
                    ];
                }
            }

            $assignments[$element->id] = [
                'beginning' => $courseStart?->toDateString(),
                'end' => $courseEnd?->toDateString(),
            ];
            $lastEnd = $courseEnd;
        }

        return [
            'assignments' => $assignments,
            'candidates' => $candidates,
            'applied_compactions' => $appliedCompactions,
            'last_end' => $lastEnd,
        ];
    }

    private function loadItineraryCalendar(TrainingContract $trainingContract): array
    {
        $excludedDays = TrainingContractsExcludedDay::where('training_contract_id', $trainingContract->id)
            ->where('main_company_id', $trainingContract->main_company_id)
            ->where('valid', 1)
            ->pluck('day')
            ->map(fn ($day) => Carbon::parse($day)->toDateString())
            ->all();
        $festivalDays = TrainingContractFestival::festivals($trainingContract->main_company_id)
            ->where('training_contract_festivals.training_contract_id', $trainingContract->id)
            ->get()
            ->pluck('day')
            ->filter()
            ->map(fn ($day) => Carbon::parse($day)->toDateString())
            ->all();

        return [
            'excluded' => array_fill_keys($excludedDays, true),
            'festivals' => array_fill_keys($festivalDays, true),
        ];
    }

    private function isItineraryWorkingDay(
        Carbon $date,
        TrainingContract $trainingContract,
        array $calendar
    ): bool {
        if ($date->isWeekend()) {
            return false;
        }

        $dateString = $date->toDateString();
        if (isset($calendar['excluded'][$dateString]) || isset($calendar['festivals'][$dateString])) {
            return false;
        }

        $dayOfWeek = strtolower($date->format('l'));
        return $trainingContract->$dayOfWeek === 1;
    }

    private function nextWorkingDay(
        Carbon $date,
        TrainingContract $trainingContract,
        array $calendar
    ): Carbon
    {
        $attempts = 0;
        while (!$this->isItineraryWorkingDay($date, $trainingContract, $calendar)) {
            $date->addDay();
            if (++$attempts > 3660) {
                throw new \DomainException('No se han encontrado días laborables para completar el itinerario.');
            }
        }

        return $date;
    }

    private function lastWorkingDayOnOrBefore(
        Carbon $date,
        TrainingContract $trainingContract,
        array $calendar
    ): Carbon
    {
        $target = $date->copy();
        $attempts = 0;
        while (!$this->isItineraryWorkingDay($target, $trainingContract, $calendar)) {
            $target->subDay();
            if (++$attempts > 3660) {
                throw new \DomainException('No se ha encontrado un día laborable al final de la formación.');
            }
        }

        return $target;
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
        if ($date->isWeekend()) {
            return false;
        }

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
        if (
            $initial_end_formation === null
            && $lastElement
            && $record->end_formation
            && !$lastElement->course_id
            && $lastElement->end
        ) {
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
