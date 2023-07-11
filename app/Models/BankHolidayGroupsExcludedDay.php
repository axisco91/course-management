<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankHolidayGroupsExcludedDay extends Model
{
	use HasFactory;

    public $timestamps = true;

    protected $fillable = ['group_id','excluded_day_id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function Groups()
    {
        return $this->hasOne('App\Models\BankHolidayGroup', 'id', 'group_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function teacherArea()
    {
        return $this->hasOne('App\Models\ExcludedDayType', 'id', 'excluded_day_id');
    }

}
