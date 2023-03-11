<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Occupation extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name'];

    public static function getOccupations(){
        $occupations = Occupation::select('*', 'id as value', 'name as label')
            ->get();
        return $occupations;
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
