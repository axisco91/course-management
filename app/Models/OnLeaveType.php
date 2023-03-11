<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnLeaveType extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name'];

    public static function getOnLeaveTypes(){
        $status = OnLeaveType::select('*', 'id as value', 'name as label')
            ->get();
        return $status;
    }

    public static function createOnLeaveType($data){
        $status = OnLeaveType::create([
            'name' => $data['name']
        ]);

        return $status;
    }

    public static function updateOnLeaveType($id, $data){
        $status = OnLeaveType::find($id);
        $status->update([
            'name' => $data['name']
        ]);

        return $status;
    }

}
