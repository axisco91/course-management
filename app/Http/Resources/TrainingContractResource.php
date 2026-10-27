<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class TrainingContractResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data = parent::toArray($request);
        $sequence = (int) ($this->extension_sequence ?? 0);
        $rootBeginning = $this->originalContract?->beginning ?? $this->beginning;
        $maximumEnd = $rootBeginning ? \App\Services\TrainingContractPeriod::lastDay($this->resource) : null;
        $firstAnniversary = $rootBeginning ? Carbon::parse($rootBeginning)->addYear() : null;

        $data['contract_type'] = $sequence === 0
            ? 'Contrato inicial'
            : $sequence . '.ª prórroga';
        $data['maximum_renewal_end'] = optional($maximumEnd)->format('Y-m-d');
        $data['previous_contract_id'] = $this->previous_training_contract_id;
        $data['next_contract_id'] = $this->nextRenewal?->id;
        $data['external_second_year'] = (bool) $this->external_second_year;
        $data['is_second_year_contract'] = $this->external_second_year || ($firstAnniversary
            && Carbon::parse($this->beginning)->gte($firstAnniversary));
        $data['can_create_renewal'] = !$this->external_second_year && (int) ($this->next_renewal_count ?? 0) === 0
            && $maximumEnd
            && Carbon::parse($this->end)->lt($maximumEnd);

        return $data;
    }
}
