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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function excludedDays()
    {
        return $this->belongsToMany(
            ExcludedDayType::class,
            'bank_holiday_groups_excluded_days',
            'group_id',
            'excluded_day_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Scope equivalente a getBankHolidayGroup($start, $end)
    |--------------------------------------------------------------------------
    |
    | Uso:
    |   BankHolidayGroup::getBankHolidayGroup()->get();
    |   BankHolidayGroup::getBankHolidayGroup($start, $end)->get();
    |
    */
    public function scopeGetBankHolidayGroup($query, $start = null, $end = null)
    {
        $query->select(
            'bank_holiday_groups.*',
            'bank_holiday_groups.id as value',
            'bank_holiday_groups.name as label'
        )
            ->leftJoin(
                'bank_holiday_groups_excluded_days',
                'bank_holiday_groups_excluded_days.group_id',
                '=',
                'bank_holiday_groups.id'
            )
            ->leftJoin(
                'excluded_days',
                'excluded_days.id',
                '=',
                'bank_holiday_groups_excluded_days.excluded_day_id'
            );

        if ($start && $end) {
            $query->whereBetween('excluded_days.day', [$start, $end]);
        }

        return $query->groupBy('bank_holiday_groups.id');
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers de creación/actualización (los dejo como static)
    |--------------------------------------------------------------------------
    */

    public static function createGroup($data)
    {
        return self::create([
            'name' => $data['name'],
        ]);
    }

    public static function updateGroup($id, $data)
    {
        $group = self::find($id);

        if ($group) {
            $group->update([
                'name' => $data['name'],
            ]);
        }

        return $group;
    }
}
