<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExcludedDay extends Model
{
	use HasFactory;

    public $timestamps = false;

    protected $fillable = ['day', 'general', 'province_id'];

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

    public static function getYears(){
        $excluded_days = ExcludedDay::select(DB::raw('count(id) as `data`'), DB::raw("DATE_FORMAT(day, '%m-%Y') new_date"),  DB::raw('YEAR(day) year'))
            ->groupby('year')->get();
        return $excluded_days;
    }

}
