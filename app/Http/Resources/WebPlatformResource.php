<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WebPlatformResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'url' => $this->url,
            'token_configured' => filled($this->token),
            'required_moodle_usernames' => $this->required_moodle_usernames ?? [],
            'required_moodle_roles' => $this->required_moodle_roles ?? [],
            'value' => $this->id,
            'label' => $this->name,
            'used' => (bool) ($this->used ?? false),
        ];
    }
}
