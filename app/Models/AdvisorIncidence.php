<?php

namespace App\Models;

use App\Services\AdvisorIncidenceService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvisorIncidence extends Model
{
    use HasFactory;

    protected $fillable = [
        'affair',
        'notes',
        'incidence_type_id',
        'advisor_id',
        'user_id',
        'main_company_id',
    ];

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    // Equivalente a getAdvisorIncidences($advisorId, $mainCompanyId)
    public function scopeGetAdvisorIncidences($query, int $advisorId, int $mainCompanyId)
    {
        return $query
            ->where('advisor_id', $advisorId)
            ->where('advisor_incidences.main_company_id', $mainCompanyId);
        // también podría ser ->where('main_company_id', $mainCompanyId) si no hay joins
    }

    /*
    |--------------------------------------------------------------------------
    | Services
    |--------------------------------------------------------------------------
    */

    public static function createWithService($data)
    {
        $service = app(AdvisorIncidenceService::class);

        return $service->create($data);
    }

    public function updateWithService($data)
    {
        $service = app(AdvisorIncidenceService::class);

        return $service->update($this, $data);
    }
}
