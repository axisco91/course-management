<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Center extends Model
{
	use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name','address','email','telephone'];

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

    public static function getCenter($id){
        $center = Center::select('centers.*', 'id as value', 'name as label')->first();
        $course = Course::orWhere('delivery_center_id', $center['id'])
            ->orWhere('formation_center_id', $center['id'])->first();
        if ($course){
            $center['used'] = true;
        } else {
            $center['used'] = false;
        }
        return $center;
    }

    public static function createCenter($data){
        $center =  Center::create([
            'name' => $data['name'],
            'address' => $data['address'],
            'email' => $data['email'],
            'telephone' => $data['telephone']
        ]);

        return $center;
    }

    public static function updateCenter($id, $data){
        $center = Center::find($id);
        $center->update([
            'name' => $data['name'],
            'address' => $data['address'],
            'email' => $data['email'],
            'telephone' => $data['telephone']
        ]);

        return $center;
    }

}
