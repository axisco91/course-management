<?php

namespace App\Models;

use App\Services\LiquidationService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Liquidation extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['company_id', 'course_id', 'beginning', 'end', 'price', 'paid', 'commission_percent', 'commission', 'paid_date', 'invoice_date', 'advisor_id', 'bill_number', 'status', 'main_company_id'];

    public function course()
    {
        return $this->hasOne('App\Models\Course', 'id', 'course_id');
    }

    public function company()
    {
        return $this->hasOne('App\Models\Company', 'id', 'company_id');
    }

    public function advisor()
    {
        return $this->hasOne('App\Models\Advisor', 'id', 'advisor_id');
    }

    public function scopeGetLiquidation($query, $mainCompanyId)
    {
        return $query
            ->select('*')
            ->where('liquidations.main_company_id', $mainCompanyId)
            ->with('course')
            ->with('company')
            ->with('advisor');
    }

    public function scopeGetAdvisorLiquidation($query, $advisorId, $companyId, $courseId, $mainCompanyId)
    {
        return $query->where('liquidations.advisor_id', $advisorId)
                ->where('liquidations.company_id', $companyId)
                ->where('liquidations.course_id', $courseId)
                ->where('liquidations.main_company_id', $mainCompanyId);
    }

    public function scopeFilterMainCompany($query, $mainCompanyId) {
        return $query->where('liquidations.main_company_id', $mainCompanyId);
    }

    public static function createWithService($data)
    {
        $service = app(LiquidationService::class);
        return $service->create($data);
    }

    public function updateWithService($data){
        $service = app(LiquidationService::class);
        return $service->update($this, $data);
    }
    public function updateCommission($data){
        $service = app(LiquidationService::class);
        return $service->updateCommission($this, $data);
    }
}
