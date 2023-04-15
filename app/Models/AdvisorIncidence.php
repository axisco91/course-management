<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvisorIncidence extends Model
{
	use HasFactory;

    protected $fillable = [
        'affair',
        'notes',
        'incidence_type_id',
        'advisor_id',
        'user_id',
    ];

    public static function getAdvisorIncidences($advisor_id){
        $advisor_incidences = AdvisorIncidence::select('*')->where('advisor_id', $advisor_id)->get();

        return $advisor_incidences;
    }

    public static function createAdvisorIncidence($data){
        $advisor_incidence = AdvisorIncidence::create(
            $data
        );

        return $advisor_incidence;
    }

    public static function updateAdvisorIncidence($id, $data){
        $advisor_incidence = AdvisorIncidence::find($id);
        $advisor_incidence->update(
            $data
        );

        return $advisor_incidence;
    }
}
