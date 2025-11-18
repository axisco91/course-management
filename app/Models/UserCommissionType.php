<?php

namespace App\Models;

use App\Services\UserCommissionTypeService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCommissionType extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'commission_type_id',
        'percentage',
        'main_company_id'
    ];

    public static function createWithService($data)
    {
        $service = app(UserCommissionTypeService::class);
        return $service->create($data);
    }

    public function updateWithService($data){
        $service = app(UserCommissionTypeService::class);
        return $service->update($this, $data);
    }

    public function deleteWithService(){
        $service = app(UserCommissionTypeService::class);
        return $service->delete($this);
    }
}
