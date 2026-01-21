<?php

namespace App\Models;

use App\Http\Controllers\Api\WebPlatformController;
use App\Services\WebPlatformService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebPlatform extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name','url','token', 'main_company_id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function trainingActions()
    {
        return $this->hasMany('App\Models\TrainingAction', 'web_platform_id', 'id');
    }

    public function scopeGetWebPlatform($query, $mainCompanyId)
    {
        return $query
            ->select(
                'web_platforms.*',
                'web_platforms.id as value',
                'web_platforms.name as label'
            )
            ->leftJoin('training_actions', function ($join) use ($mainCompanyId) {
                $join->on('training_actions.web_platform_id', '=', 'web_platforms.id')
                    ->where('training_actions.main_company_id', '=', $mainCompanyId);
            })
            ->selectRaw('CASE WHEN training_actions.id IS NULL THEN false ELSE true END as used')
            ->where('web_platforms.main_company_id', $mainCompanyId)
            ->groupBy('web_platforms.id');
    }

    public function scopeFilterMainCompany($query, $mainCompanyId) {
        return $query->where('web_platforms.main_company_id', $mainCompanyId);
    }

    public static function createWithService($data)
    {
        $service = app(WebPlatformService::class);
        return $service->create($data);
    }

    public function updateWithService($data){
        $service = app(WebPlatformController::class);
        return $service->update($this, $data);
    }
}
