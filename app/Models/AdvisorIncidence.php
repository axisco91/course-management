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
        'main_company_id'
    ];

    public static function getAdvisorIncidences($advisorId, $mainCompanyId){
        $advisor_incidences = AdvisorIncidence::select('*')->where('advisor_id', $advisorId)
            ->where('advisor_inscidences', $mainCompanyId)->get();

        return $advisor_incidences;
    }

    public static function createWithService($data)
    {
        $service = app(AdvisorIncidenceService::class);
        return $service->create($data);
    }

    public function updateWithService($data){
        $service = app(AdvisorIncidenceService::class);
        return $service->update($this, $data);
    }
}
