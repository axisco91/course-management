<?php

namespace App\Models;

use App\Services\ProfessionalAreaService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfessionalArea extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function trainingActions()
    {
        return $this->hasMany('App\Models\TrainingAction', 'professional_area_id', 'id');
    }

    public function scopeGetProfessionalArea($query)
    {
        return $query
            ->select(
                'professional_areas.*',
            );
    }

    public static function createWithService($data)
    {
        $service = app(ProfessionalAreaService::class);
        return $service->create($data);
    }

    public function updateWithService($data)
    {
        $service = app(ProfessionalAreaService::class);
        return $service->update($this, $data);
    }
}
