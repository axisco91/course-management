<?php

namespace App\Models;

use App\Services\ModalityService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modality extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function trainingActions()
    {
        return $this->hasMany('App\Models\TrainingAction', 'modality_id', 'id');
    }

    public function scopeGetModality($query)
    {
        return $query
            ->select(
                'modalities.*',
                'modalities.id as value',
                'modalities.name as label'
            )
            ->leftJoin('training_actions', 'training_actions.modality_id', '=', 'modalities.id')
            ->selectRaw('CASE WHEN training_actions.id IS NULL THEN false ELSE true END as used')
            ->groupBy('modalities.id');
    }

    public static function createWithService($data)
    {
        $service = app(ModalityService::class);
        return $service->create($data);
    }

    public function updateWithService($data){
        $service = app(ModalityService::class);
        return $service->update($this, $data);
    }
}
