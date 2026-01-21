<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BillResource extends JsonResource
{
    public function toArray($request)
    {
        $course = $this->course;
        $ta = $course?->trainingAction;

        return [
            'id' => $this->id,
            'billing_number' => $this->billing_number,
            // campos billings
            'is_bonus' => $this->is_bonus,
            'invoiced' => $this->invoiced,
            'charged'  => $this->charged,

            // ✅ lo que consumías antes como strings
            'company' => $this->company?->name,
            'payment' => $this->payment?->name,
            'advisor' => $this->advisor ? trim($this->advisor->name ) : null,
            'collaborator' => $this->collaborator ? trim(($this->collaborator->name ?? '').' '.($this->collaborator->surname ?? '')) : null,

            'status' => $course?->courseStatus?->name,

            'beginning' => $course?->beginning,
            'year' => $course?->beginning ? (int) \Carbon\Carbon::parse($course->beginning)->year : null,

            // ✅ label curso como antes
            'course' => $ta
                ? trim(($ta->formative_action ?? '').' / '.($course->group ?? '').' '.($ta->name ?? ''))
                : null,

            // ✅ aliases como antes
            'type' => $this->is_bonus == 1 ? 'Bonificada' : 'No bonificada',
            'invoice' => $this->invoiced == 1 ? 'Si' : 'No',
            'charge' => $this->charged == 1 ? 'Si' : 'No',
        ];
    }
}
