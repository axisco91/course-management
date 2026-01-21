<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChoreResource extends JsonResource
{
    public function toArray($request)
    {
        $course = $this->course;
        $ta     = $course?->trainingAction;
        $status = $course?->courseStatus;
        $type   = $course?->courseType;

        return [
            'id' => $this->id,
            'course_id' => $this->course_id,
            'company_id' => $this->company_id,
            'student_id' => $this->student_id,

            // lo que antes salía “plano”
            'company' => $this->company,
            'student' => $this->student,

            'course_group' => $course?->group,
            'beginning' => $course?->beginning,
            'end' => $course?->end,

            'status' => $status?->name,
            'course_type' => $type?->name,

            'course' => $course,
            'course_name' => $ta
                ? trim(($ta->formative_action ?? '').' / '.($course?->group ?? '').' '.($ta->name ?? ''))
                : null,

            'name' => $ta
                ? trim(($ta->formative_action ?? '').' / '.($course?->group ?? '').' '.($ta->name ?? '').' - '.(($this->student?->name ?? '').' '.($this->student?->surname ?? '')))
                : null,

            // todos los campos del modelo (incluye appends status_name)
            'membership_tab_status' => $this->membership_tab_status,
            'membership_tab_status_name' => $this->membership_tab_status_name,
            'economic_proposal_status' => $this->economic_proposal_status,
            'economic_proposal_status_name' => $this->economic_proposal_status_name,
            'student_tab_status' => $this->student_tab_status,
            'student_tab_status_name' => $this->student_tab_status_name,
            'welcome_guid_status' => $this->welcome_guid_status,
            'welcome_guid_status_name' => $this->welcome_guid_status_name,
            'registration_status' => $this->registration_status,
            'registration_status_name' => $this->registration_status_name,
            'diploma_status' => $this->diploma_status,
            'diploma_status_name' => $this->diploma_status_name,
            'start_communication_status' => $this->start_communication_status,
            'start_communication_status_name' => $this->start_communication_status_name,
            'close_communication_status' => $this->close_communication_status,
            'close_communication_status_name' => $this->close_communication_status_name,
            'invoiced_status' => $this->invoiced_status,
            'invoiced_status_name' => $this->invoiced_status_name,
            'bonus_sent_status' => $this->bonus_sent_status,
            'bonus_sent_status_name' => $this->bonus_sent_status_name,
            'send_doc_status' => $this->send_doc_status,
            'send_doc_status_name' => $this->send_doc_status_name,
            'tutor_guide_status' => $this->tutor_guide_status,
            'tutor_guide_status_name' => $this->tutor_guide_status_name,

            // opcional: devolver relaciones completas si te interesan
            'relations' => [
                'course' => $course,
                'company' => $this->company,
                'student' => $this->student,
            ],
        ];
    }
}
