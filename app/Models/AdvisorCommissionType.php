<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvisorCommissionType extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'advisor_id',
        'commission_type_id',
        'percentage',
        'main_company_id'
    ];

}
