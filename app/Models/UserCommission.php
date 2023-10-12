<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UserCommission extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'training_contract_id',
        'course_id',
        'commissionable_id',
        'commissionable_type',
        'commission_type_id',
        'percentage',
        'amount',
        'bill_amount'
    ];

    public function scopeCommissions($query) {
        return $query->select(
            'user_commissions.*',
            'commission_types.id as commission_type_id',
            'commission_types.name as commission_type',
            DB::raw("IFNULL(courses.name, training_contracts.number_cfa) AS name"),
            DB::raw("IFNULL(training_contracts.number_cfa, CONCAT(training_actions.formative_action,' / ', courses.group, ' ', training_actions.name)) AS name"),
        )
            ->leftJoin('commission_types', 'commission_types.id', '=', 'user_commissions.commission_type_id')
            ->leftJoin('courses', 'courses.id', '=', 'user_commissions.course_id')
            ->leftJoin('training_contracts', 'training_contracts.id', '=', 'user_commissions.training_contract_id')
            ->leftJoin('training_actions', 'training_actions.id', '=', 'courses.training_action_id');
    }

}
