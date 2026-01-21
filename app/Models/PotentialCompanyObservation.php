<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PotentialCompanyObservation extends Model
{
	use HasFactory;

    public $timestamps = true;

    protected $fillable = ['potential_company_id','observation', 'main_company_id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function company()
    {
        return $this->hasOne('App\Models\PotentialCompany', 'id', 'potential_company_id');
    }

    public function scopeGetPotentialCompanyObservations($query, $companyId)
    {
        return $query
            ->select(
                'potential_company_observations.*',
                DB::raw("DATE_FORMAT(potential_company_observations.created_at, '%d/%m/%Y') as date")
            )
            ->where('potential_company_id', $companyId);
    }

    public static function createPotentialCompanyObservation($data){
        $company_observation = PotentialCompanyObservation::create([
            'potential_company_id' => $data['potential_company_id'],
            'observation' => $data['observation']
        ]);

        return $company_observation;
    }

    public static function updatePotentialCompanyObservation($id, $data){
        $company_observation = PotentialCompanyObservation::find($id);
        $company_observation->update([
            'observation' => $data['observation']
        ]);

        return $company_observation;
    }

}
