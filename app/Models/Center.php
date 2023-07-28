<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Center extends Model
{
	use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name','address','email','telephone'];

    public function scopeGetCenter($query) {
        return $query->select('centers.*', 'id as value', 'name as label');
    }

    public static function getCenters(){
        $centers = Center::select('centers.*', 'id as value', 'name as label')->get();
        foreach ($centers as $center) {
            $course = Course::orWhere('delivery_center_id', $center['id'])
                ->orWhere('formation_center_id', $center['id'])->first();
            if ($course){
                $center['used'] = true;
            } else {
                $center['used'] = false;
            }
        }
        return $centers;
    }
}
