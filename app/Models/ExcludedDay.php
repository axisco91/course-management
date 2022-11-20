<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExcludedDay extends Model
{
	use HasFactory;

    public $timestamps = false;

    protected $fillable = ['day'];

    public static function getExcludedDays($keyWord){
        $excludedDay = ExcludedDay::orWhere('day', 'LIKE', $keyWord)
            ->paginate(10);
        return $excludedDay;
    }

    public static function createExcludedDay($data){
        $excludedDay = ExcludedDay::create([
            'day' => $data['day']
        ]);

        return $excludedDay;
    }

    public static function updateExcludedDay($id, $data){
        $excludedDay = ExcludedDay::find($id);
        $excludedDay->update([
            'day' => $data['dat']
        ]);

        return $excludedDay;
    }

}
