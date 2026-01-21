<?php

namespace App\Models;

use App\Services\ProfessionalFamilyService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfessionalFamily extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function trainingActions()
    {
        return $this->hasMany('App\Models\TrainingAction', 'professional_family_id', 'id');
    }

    public function scopeGetProfessionalFamily($query)
    {
        return $query
            ->select(
                'professional_families.*',
                'professional_families.id as value',
                'professional_families.name as label'
            )
            ->leftJoin(
                'training_actions',
                'training_actions.professional_family_id',
                '=',
                'professional_families.id'
            )
            ->selectRaw('CASE WHEN training_actions.id IS NULL THEN false ELSE true END as used')
            ->groupBy('professional_families.id');
    }

    public static function createWithService($data)
    {
        $service = app(ProfessionalFamilyService::class);
        return $service->create($data);
    }

    public function updateWithService($data)
    {
        $service = app(ProfessionalFamilyService::class);
        return $service->update($this, $data);
    }
}
