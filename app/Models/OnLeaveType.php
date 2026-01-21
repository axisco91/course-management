<?php

namespace App\Models;

use App\Services\OccupationService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnLeaveType extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name'];

    public function scopeGetOnLeaveType($query)
    {
        return $query
            ->select(
                'on_leave_types.*',
                'on_leave_types.id as value',
                'on_leave_types.name as label'
            )
            ->leftJoin(
                'training_contracts',
                'training_contracts.on_leave_type_id',
                '=',
                'on_leave_types.id'
            )
            ->selectRaw('CASE WHEN training_contracts.id IS NULL THEN false ELSE true END as used')
            ->groupBy('on_leave_types.id');
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
