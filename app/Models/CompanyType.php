<?php

namespace App\Models;

use App\Services\CompanyTypeService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyType extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function companies()
    {
        return $this->hasMany('App\Models\Company', 'type_id', 'id');
    }

    public function scopeGetCompanyTypes($query)
    {
        return $query
            ->leftJoin('companies', 'companies.company_type_id', '=', 'company_types.id')
            ->select(
                'company_types.*',
                'company_types.id as value',
                'company_types.name as label'
            )
            ->selectRaw('CASE WHEN COUNT(companies.id) > 0 THEN true ELSE false END as used')
            ->groupBy('company_types.id');
    }

    public static function createWithService($data)
    {
        $service = app(CompanyTypeService::class);
        return $service->create($data);
    }

    public function updateWithService($data){
        $service = app(CompanyTypeService::class);
        return $service->update($this, $data);
    }
}
