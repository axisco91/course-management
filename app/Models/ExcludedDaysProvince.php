<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExcludedDaysProvince extends Model
{
	use HasFactory;

    public $timestamps = true;

    protected $fillable = ['excluded_day_id','province_id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function excludedDays()
    {
        return $this->hasOne('App\Models\ExcludedDay', 'id', 'excluded_day_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function Provinces()
    {
        return $this->hasOne('App\Models\Province', 'id', 'province_id');
    }

}
