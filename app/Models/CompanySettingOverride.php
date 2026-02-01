<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanySettingOverride extends Model
{
    protected $table = 'company_setting_overrides';

    protected $fillable = [
        'company_id',
        'setting_id',
        'value',
    ];

    /**
     * Setting al que pertenece
     */
    public function setting()
    {
        return $this->belongsTo(SettingDefinition::class, 'setting_id');
    }

    /**
     * Empresa
     */
    public function company()
    {
        return $this->belongsTo(MainCompany::class, 'company_id');
    }
}
