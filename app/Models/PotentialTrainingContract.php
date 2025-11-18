<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

Class PotentialTrainingContract extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'company',
        'company_tutor',
        'company_tutor_dni',
        'workplace',
        'advisory',
        'province',
        'student',
        'occupation',
        'disabled',
        'youth_guarantee',
        'social_exclusion',
        'specialty',
        'professional_certificate',
        'contract_start_date',
        'training_start_date',
        'total_contract_hours',
        'training_schedule',
        'working_schedule',
        'full_schedule',
        'monday',
        'tuesday',
        'wednesday',
        'thursday',
        'friday',
        'saturday',
        'sunday',
        'main_company_id'
    ];

}
