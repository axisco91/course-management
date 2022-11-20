<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankHolidayGroup extends Model
{
	use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function excludedDays(){
        return $this->belongsToMany(ExcludedDay::class, 'bank_holiday_groups_excluded_days', 'group_id', 'excluded_day_id');
    }

    public static function getBankHolidayGroup($start = null, $end = null){
        $groups = BankHolidayGroup::select('bank_holiday_groups.*')
            ->leftjoin('bank_holiday_groups_excluded_days', 'bank_holiday_groups_excluded_days.group_id', '=', 'bank_holiday_groups.id')
            ->leftjoin('excluded_days', 'excluded_days.id', '=', 'bank_holiday_groups_excluded_days.excluded_day_id');
        if ($start && $end){
            $groups->whereBetween('excluded_days.day', [$start, $end]);
        }
        $groups = $groups->groupBy('bank_holiday_groups.id')->get();
        return $groups;
    }

    public static function createGroup($data){
        $group = BankHolidayGroup::create([
            'name' => $data['name']
        ]);
        return $group;
    }

    public static function updateGroup($id, $data){
        $group = BankHolidayGroup::find($id);
        $group->update([
            'name' => $data['name']
        ]);
        return $group;
    }
}
