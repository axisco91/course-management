<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'bill_amount'
    ];

}
