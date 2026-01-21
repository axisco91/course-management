<?php

namespace App\Models;

use App\Services\OccupationService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Occupation extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name', 'cno'];

    public function scopeGetOccupation($query)
    {
        return $query
            ->select(
                'occupations.*',
                'occupations.id as value',
                'occupations.name as label'
            )
            ->leftJoin(
                'training_contracts',
                'training_contracts.occupation_id',
                '=',
                'occupations.id'
            )
            ->selectRaw('CASE WHEN training_contracts.id IS NULL THEN false ELSE true END as used')
            ->groupBy('occupations.id');
    }

    public static function createWithService($data)
    {
        $service = app(OccupationService::class);
        return $service->create($data);
    }

    public function updateWithService($data){
        $service = app(OccupationService::class);
        return $service->update($this, $data);
    }
}
