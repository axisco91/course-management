<?php

namespace App\Models;

use App\Services\CompanyActivityService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CompanyActivity extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function companies()
    {
        return $this->hasMany('App\Models\Company', 'activity_id', 'id');
    }

    /**
     * Services
     */
    public static function createWithService($data)
    {
        $service = app(CompanyActivityService::class);

        return $service->create($data);
    }

    public function updateWithService($data)
    {
        $service = app(CompanyActivityService::class);

        return $service->update($this, $data);
    }
}
