<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Occupation extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name', 'cno'];

    public static function getOccupations(){
        $occupations = Occupation::select('*', 'id as value', 'name as label')
            ->get();
        foreach ($occupations as $occupation){
            $contact = TrainingContract::where('occupation_id', $occupation['id'])->first();
            if ($contact){
                $occupation['used'] = true;
            } else{
                $occupation['used'] = false;
            }
        }
        return $occupations;
    }

    public static function getOccupation($id){
        $occupation = Occupation::select('*', 'id as value', 'name as label')
            ->where('id', $id)
            ->first();
        $contact = TrainingContract::where('occupation_id', $occupation['id'])->first();
        if ($contact){
            $occupation['used'] = true;
        } else{
            $occupation['used'] = false;
        }
        return $occupation;
    }

    public static function createOccupation($data){
        $occupation = Occupation::create([
            'name' => $data['name']
        ]);

        return $occupation;
    }

    public static function updateOccupation($id, $data){
        $occupation = Occupation::find($id);
        $occupation->update([
            'name' => $data['name']
        ]);

        return $occupation;
    }

}
