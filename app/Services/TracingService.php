<?php

namespace App\Services;

use App\Helpers\CalculationHelpers;
use App\Helpers\GeneralHelpers;
use App\Models\Tracing;
use Illuminate\Support\Carbon;

class TracingService
{
    /**
     * Función para crear un alumno
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return Tracing::create([
            'course_id' => $data['course_id'],
            'company_id' => $data['company_id'],
            'student_id' => $data['student_id'],
            'main_company_id' => isset($data['main_company_id']) ?? null,
        ]);
    }

    /**
     * Función para editar un alumno
     */
    public function update(Tracing $tracing, $data) {
        $tracing->update([
            'course_id' => $data['course_id'],
            'company_id' => $data['company_id'],
            'student_id' => $data['student_id'],
            'last_connection' => GeneralHelpers::parseDateOrNull($data['last_connection']),
            'performed_activities' => $data['performed_activities'] ? $data['performed_activities'] : 0,
            'performed_hours' => $data['performed_hours'] ? CalculationHelpers::timeStringToDecimal($data['performed_hours']) : 0,
            'performed_units' => $data['performed_units'] ? $data['performed_units'] : 0,
            'follow_up_date' => $data['follow_up_date'] ? Carbon::createFromFormat('d-m-Y', $data['follow_up_date'])->format('Y-m-d') : null,
            'final_test' => $data['final_test'],
            'questionnaire' => $data['questionnaire'],
            'observation' => $data['observation'],
            'welcome_date_sent' => $data['welcome_date_sent'] ? Carbon::createFromFormat('d-m-Y', $data['welcome_date_sent'])->format('Y-m-d') : null,
            'quarter_date_sent' => $data['quarter_date_sent'] ? Carbon::createFromFormat('d-m-Y', $data['quarter_date_sent'])->format('Y-m-d') : null,
            'half_date_sent' => $data['half_date_sent'] ? Carbon::createFromFormat('d-m-Y', $data['half_date_sent'])->format('Y-m-d') : null,
            'three_quarters_date_sent' => $data['three_quarters_date_sent'] ? Carbon::createFromFormat('d-m-Y', $data['three_quarters_date_sent'])->format('Y-m-d') : null,
            'final_date_sent' => $data['final_date_sent'] ? Carbon::createFromFormat('d-m-Y', $data['final_date_sent'])->format('Y-m-d') : null,
            'welcome_message' => $data['welcome_message'],
            'quarter_message' => $data['quarter_message'],
            'half_message' => $data['half_message'],
            'three_quarters_message' => $data['three_quarters_message'],
            'final_message' => $data['final_message']
        ]);
        return $tracing;
    }
}
