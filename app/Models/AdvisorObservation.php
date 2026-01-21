<?php

namespace App\Models;

use App\Services\AdvisorObservationService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvisorObservation extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = ['advisor_id','observation', 'main_company_id'];
    protected $appends = ['date'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function advisor()
    {
        return $this->hasOne('App\Models\Advisor', 'id', 'advisor_id');
    }

    public function scopeForAdvisor($query, int $advisorId, int $mainCompanyId)
    {
        return $query
            ->where('advisor_id', $advisorId)
            ->where('main_company_id', $mainCompanyId);
    }

    public function getDateAttribute(): ?string
    {
        return $this->created_at
            ? $this->created_at->format('d/m/Y')
            : null;
    }

    public static function createWithService($data)
    {
        $service = app(AdvisorObservationService::class);
        return $service->create($data);
    }

    public function updateWithService($data){
        $service = app(AdvisorObservationService::class);
        return $service->update($this, $data);
    }
}
