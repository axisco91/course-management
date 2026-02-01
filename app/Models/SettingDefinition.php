<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SettingDefinition extends Model
{
    protected $table = 'setting_definitions';

    protected $fillable = [
        'key',
        'name',
        'alias',
        'description',
        'value',
    ];

    /**
     * Overrides de este setting
     */
    public function overrides()
    {
        return $this->hasMany(CompanySettingOverride::class, 'setting_id');
    }
}
