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
        $types = OnLeaveType::select('*', 'id as value', 'name as label')
            ->get();
        foreach($types as $type) {
            $contract = TrainingContract::where('on_leave_type_id', $type->id)->first();
            if ($contract) {
                $type['used'] = true;
            } else {
                $type['used'] = false;
            }
        }
        return $types;
    }

    public static function getOnLeaveType($id){
        $type = OnLeaveType::select('*', 'id as value', 'name as label')
            ->where('id', $id)
            ->first();
        $contract = TrainingContract::where('on_leave_type_id', $type->id)->first();
        if ($contract) {
            $type['used'] = true;
        } else {
            $type['used'] = false;
        }
        return $type;
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
