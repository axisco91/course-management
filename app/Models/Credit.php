<?php

namespace App\Models;

use App\Services\CreditService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Credit extends Model
{
	use HasFactory;

    public $timestamps = false;

    protected $fillable = ['company_id', 'available_credit', 'consumed_credit', 'year', 'main_company_id'];


    public static function getCredits($id, $mainCompanyId){
        return Credit::select('credits.*', DB::raw('(credits.available_credit - credits.consumed_credit) as credit_left'))
            ->leftjoin('companies', 'companies.id', '=', 'credits.company_id')
            ->where('company_id', $id)
            ->where('credits.main_company_id', $mainCompanyId)
            ->get();
    }

    public static function getCredit($id, $mainCompanyId){
        return Credit::select('credits.*', DB::raw('(credits.available_credit - credits.consumed_credit) as credit_left'))
            ->leftjoin('companies', 'companies.id', '=', 'credits.company_id')
            ->where('credits.id', $id)
            ->where('credits.main_company_id', $mainCompanyId)
            ->first();
    }

    public function scopeFilterMainCompany($query, $mainCompanyId) {
        return $query->where('credits.main_company_id', $mainCompanyId);
    }

    public static function createWithService($data)
    {
        $service = app(CreditService::class);
        return $service->create($data);
    }

    public function updateWithService($data){
        $service = app(CreditService::class);
        return $service->update($this, $data);
    }
}
