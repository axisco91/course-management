<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyObservation extends Model
{
	use HasFactory;

    public $timestamps = true;

    protected $fillable = ['company_id','observation'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function company()
    {
        return $this->hasOne('App\Models\Company', 'id', 'company_id');
    }

    public function getCompanyObservations($id){
        $observations = CompanyObservation::where('company_id', $id)->get();

        foreach ($observations as $observation){
            $observation['date'] = Carbon::createFromFormat('Y-m-d H:i:s', $observation['created_at'])->format('d/m/Y');
        }

        return $observations;
    }

    public function createCompanyObservation($data){
        $company_observation = CompanyObservation::create([
            'company_id' => $data['company_id'],
            'observation' => $data['observation']
        ]);

        return $company_observation;
    }

    public function updateCompanyObservation($id, $data){
        $company_observation = CompanyObservation::find($id);
        $company_observation->update([
            'observation' => $data['observation']
        ]);

        return $company_observation;
    }

}
