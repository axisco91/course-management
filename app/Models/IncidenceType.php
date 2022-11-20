<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncidenceType extends Model
{
	use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name'];


    public static function getIncidenceTypes($keyWord, $sortBy, $sortDirection){
        $incidenceTypes = IncidenceType::
        orWhere('name', 'LIKE', $keyWord)
            ->orderBy($sortBy, $sortDirection)->paginate(10);
        foreach ($incidenceTypes as $incidenceType){
            $incidences = AdvisorIncidence::where('incidence_type_id', $incidenceType['id'])->first();
            if ($incidences){
                $incidenceType['used'] = true;
            } else {
                $incidenceType['used'] = false;
            }
        }
        return $incidenceTypes;
    }

    public static function createIncidenceType($data){
        $incidence_type = IncidenceType::create([
            'name' => $data['name']
        ]);

        return $incidence_type;
    }

    public static function updateIncidenceType($id, $data){
        $incidence_type = IncidenceType::find($id);
        $incidence_type->update([
            'name' => $data['name']
        ]);

        return $incidence_type;
    }
}
