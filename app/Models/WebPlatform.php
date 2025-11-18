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

    public static function getWebPlatforms($mainCompanyId){
        $web_platforms = WebPlatform::
        select('*', 'id as value', 'name as label')
            ->where('main_company_id', $mainCompanyId)
            ->get();
        foreach ($web_platforms as $web_platform){
            $trainingAction = TrainingAction::where('web_platform_id', $web_platform['id'])
                ->where('main_company_id', $mainCompanyId)
                ->first();
            if ($trainingAction){
                $web_platform['used'] = true;
            } else {
                $web_platform['used'] = false;
            }
        }
        return $web_platforms;
    }

    public static function getWebPlatform($id, $mainCompanyId){
        $web_platform = WebPlatform::
        select('*', 'id as value', 'name as label')
            ->where('id', $id)
            ->where('main_company_id', $mainCompanyId)
            ->first();
        $trainingAction = TrainingAction::where('web_platform_id', $web_platform['id'])
            ->where('main_company_id', $mainCompanyId)
            ->first();
        if ($trainingAction){
            $web_platform['used'] = true;
        } else {
            $web_platform['used'] = false;
        }
        return $web_platform;
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
