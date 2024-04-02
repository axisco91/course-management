<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TrainingContract;
    
class ApplicableAgreement extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'code',
        'annually_hours',
        'agreement_type_id',  // Futura clave foránea
    ];

    /**
     * Get the agreement type associated with the applicable agreement.
     */
    public function agreementType()
    {
        return $this->belongsTo(AgreementType::class);
    }
    public function trainingContracts()
    {
        return $this->hasMany(TrainingContract::class);
    }
}