<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Province extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function companies()
    {
        return $this->hasMany('App\Models\Company', 'province_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function advisors()
    {
        return $this->hasMany('App\Models\Advisor', 'province_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function students()
    {
        return $this->hasMany('App\Models\Student', 'province_id', 'id');
    }

    public function excludedDays(){
        return $this->belongsToMany(ExcludedDayType::class, 'excluded_days_provinces', 'province_id', 'excluded_day_id');
    }

    public static function getProvinces(){
        $provinces = Province::
        select('*', 'id as value', 'name as label')
            ->get();
        return $provinces;
    }

    public static function createProvince($data){
        $province = Province::create([
            'name' => $data['name']
        ]);
        return $province;
    }

    public static function updateProvince($id, $data){
        $province = Province::find($id);
        $province->update([
            'name' => $data['name']
        ]);
        return $province;
    }

    public function scopeProvincesWithFestivals($query, $start = null, $end = null){
        $query->select('provinces.*', 'provinces.id as value', 'provinces.name as label')
            ->leftjoin('province_festivals', 'province_festivals.province_id', '=', 'provinces.id');
        if ($start && $end){
            $query->whereBetween('province_festivals.day', [$start, $end]);
        }
        $query->groupBy('provinces.id');
        return $query;
    }
}
