<?php

namespace App\Services;

use App\Models\Chore;
use App\Models\Registration;
use Illuminate\Support\Carbon;

class ChoreService
{
    /**
     * Función para crear una tarea
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return Chore::create([
            'course_id' => $data['course_id'],
            'company_id' => $data['company_id'],
            'student_id' => $data['student_id'],
            'main_company_id' => isset($data['main_company_id']) ?? null,
        ]);
    }

    /**
     * Función para editar una tarea
     */
    public function update(Chore $chore, array $data) {
        $chore->update([
            'membership_tab_status' => $data['membership_tab_status'],
            'membership_tab_date' => $data['membership_tab_date'] != 'null' ? Carbon::createFromFormat('d-m-Y', $data['membership_tab_date'])->format('Y-m-d') : null,
            'economic_proposal_status' => $data['economic_proposal_status'],
            'economic_proposal_date' => $data['economic_proposal_date'] != 'null' ? Carbon::createFromFormat('d-m-Y', $data['economic_proposal_date'])->format('Y-m-d') : null,
            'student_tab_status' => $data['student_tab_status'],
            'student_tab_date' => $data['student_tab_date'] != 'null' ? Carbon::createFromFormat('d-m-Y', $data['student_tab_date'])->format('Y-m-d') : null,
            'welcome_guid_status' => $data['welcome_guid_status'],
            'welcome_guid_date' => $data['welcome_guid_date'] != 'null' ? Carbon::createFromFormat('d-m-Y', $data['welcome_guid_date'])->format('Y-m-d') : null,
            'registration_status' => $data['registration_status'],
            'registration_date' => $data['registration_date'] != 'null' ? Carbon::createFromFormat('d-m-Y', $data['registration_date'])->format('Y-m-d') : null,
            'diploma_status' => $data['diploma_status'],
            'diploma_status_date' => $data['diploma_status_date'] != 'null' ? Carbon::createFromFormat('d-m-Y', $data['diploma_status_date'])->format('Y-m-d') : null,
            'start_communication_status' => $data['start_communication_status'],
            'start_communication_date' => $data['start_communication_date'] != 'null' ? Carbon::createFromFormat('d-m-Y', $data['start_communication_date'])->format('Y-m-d') : null,
            'close_communication_status' => $data['close_communication_status'],
            'close_communication_date' => $data['close_communication_date'] != 'null' ? Carbon::createFromFormat('d-m-Y', $data['close_communication_date'])->format('Y-m-d') : null,
            'invoiced_status' => $data['invoiced_status'],
            'invoiced_date' => $data['invoiced_date'] != 'null' ? Carbon::createFromFormat('d-m-Y', $data['invoiced_date'])->format('Y-m-d') : null,
            'bonus_sent_status' => $data['bonus_sent_status'],
            'bonus_sent_date' => $data['bonus_sent_date'] != 'null' ? Carbon::createFromFormat('d-m-Y', $data['bonus_sent_date'])->format('Y-m-d') : null,
            'send_doc_status' => $data['send_doc_status'],
            'send_doc_date' => $data['send_doc_date'] != 'null' ? Carbon::createFromFormat('d-m-Y', $data['send_doc_date'])->format('Y-m-d') : null,
            'tutor_guide_status' => $data['tutor_guide_status'],
            'tutor_guide_date' => $data['tutor_guide_date'] != 'null' ? Carbon::createFromFormat('d-m-Y', $data['tutor_guide_date'])->format('Y-m-d') : null
        ]);
        return $chore;
    }

    /**
     * Actualizamos fecha de comunicación de inicio
     * @param Chore $chore
     * @param array $data
     * @return void
     */
    public function updateCommunicationStartDate(Chore $chore, array $data) {
        $status = $data['status'];
        if (empty($data['date'])){
            $status = 0;
        }
        $chore->update([
            'start_communication_date' => empty($data['date']) ? null : Carbon::createFromFormat('d-m-Y', $data['date'])->format('Y-m-d'),
            'start_communication_status' => $status
        ]);
    }

    /**
     * Actualizamos fecha de comunicación de fin
     * @param Chore $chore
     * @param array $data
     * @return void
     */
    public function updateCommunicationEndDate(Chore $chore, array $data) {
        $status = $data['status'];
        if (empty($data['date'])){
            $status = 0;
        }
        $chore->update([
            'close_communication_date' => empty($data['date']) ? null : Carbon::createFromFormat('d-m-Y', $data['date'])->format('Y-m-d'),
            'close_communication_status' => $status
        ]);
    }
}
