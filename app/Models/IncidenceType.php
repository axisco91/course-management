<?php

namespace App\Models;

use App\Services\IncidenceTypeService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncidenceType extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name'];

    public function scopeGetIncidenceType($query)
    {
        return $query
            ->select(
                'incidence_types.*'
            );
    }

    public static function createWithService($data)
    {
        $service = app(IncidenceTypeService::class);

        return $service->create($data);
    }

    public function updateWithService($data)
    {
        $service = app(IncidenceTypeService::class);

        return $service->update($this, $data);
    }
}
