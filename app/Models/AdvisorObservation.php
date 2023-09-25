<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvisorObservation extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = ['advisor_id','observation'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function advisor()
    {
        return $this->hasOne('App\Models\Advisor', 'id', 'advisor_id');
    }

    public static function getAdvisorObservations($id){
        $observations = AdvisorObservation::where('advisor_id', $id)->get();

        foreach ($observations as $observation){
            $observation['date'] = Carbon::createFromFormat('Y-m-d H:i:s', $observation['created_at'])->format('d/m/Y');
        }

        return $observations;
    }

}
