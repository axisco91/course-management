<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgreementType extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'description',
        'applicable_agreement_id',
        'main_company_id'
    ];
}
