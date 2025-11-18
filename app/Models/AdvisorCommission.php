<?php

namespace App\Models;

use App\Services\AdvisorCommissionService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AdvisorCommission extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'advisor_id',
        'training_contract_id',
        'course_id',
        'commissionable_id',
        'commissionable_type',
        'commission_type_id',
        'percentage',
        'amount',
        'bill_amount',
        'main_company_id'
    ];

    public function scopeCommissions($query, $mainCompanyId) {
        return $query->select(
            'advisor_commissions.*',
            'commission_types.id as commission_type_id',
            'commission_types.name as commission_type',
            DB::raw("IFNULL(courses.name, training_contracts.number_cfa) AS name"),
            DB::raw("IFNULL(training_contracts.number_cfa, CONCAT(training_actions.formative_action,' / ', courses.group, ' ', training_actions.name)) AS name"),
        )
            ->leftJoin('commission_types', 'commission_types.id', '=', 'advisor_commissions.commission_type_id')
            ->leftJoin('courses', 'courses.id', '=', 'advisor_commissions.course_id')
            ->leftJoin('training_contracts', 'training_contracts.id', '=', 'advisor_commissions.training_contract_id')
            ->leftJoin('training_actions', 'training_actions.id', '=', 'courses.training_action_id')
            ->where('advisor_commissions.main_company_id', $mainCompanyId);
    }

    public function scopeFilterMainCompany($query, $mainCompanyId) {
        return $query->where('advisor_commissions.main_company_id', $mainCompanyId);
    }

    public static function createCommission($data)
    {
        $service = app(AdvisorCommissionService::class);
        return $service->create($data);
    }

    public function updateCommission($data){
        $service = app(AdvisorCommissionService::class);
        return $service->update($this, $data);
    }
}
