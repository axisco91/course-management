<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PotentialCompanyObservation extends Model
{
	use HasFactory;

    public $timestamps = true;

    protected $fillable = ['potential_company_id','observation'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function company()
    {
        return $this->hasOne('App\Models\PotentialCompany', 'id', 'potential_company_id');
    }

    public static function getPotentialCompanyObservations($id){
        $observations = PotentialCompanyObservation::where('potential_company_id', $id)->get();

        foreach ($observations as $observation){
            $observation['date'] = Carbon::createFromFormat('Y-m-d H:i:s', $observation['created_at'])->format('d/m/Y');
        }

        return $observations;
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
