<?php

namespace App\Models;

use App\Services\AdvisorObservationService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActionType extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function trainingActions()
    {
        return $this->hasMany('App\Models\TrainingAction', 'action_type_id', 'id');
    }

    public function scopeGetActionType($query) {
        return $query->select('action_types.*', 'id as value', 'name as label');
    }

    public static function createWithService($data)
    {
        $service = app(AdvisorObservationService::class);
        return $service->create($data);
    }

    public function updateWithService($data)
    {
        $service = app(AdvisorObservationService::class);
        return $service->update($this, $data);
    }

    public function deleteWithService()
    {
        $service = app(AdvisorObservationService::class);
        return $service->delete($this);
    }
}
