<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Credit extends Model
{
	use HasFactory;

    public $timestamps = false;

    protected $fillable = ['company_id', 'available_credit', 'consumed_credit', 'year'];


    public static function getCredits($id){
        $credits = Credit::select('credits.*', DB::raw('(credits.available_credit - credits.consumed_credit) as credit_left'))
            ->leftjoin('companies', 'companies.id', '=', 'credits.company_id')
            ->where('company_id', $id)
            ->get();

        return $credits;
    }

    public static function getCredit($id){
        $credit = Credit::select('credits.*', DB::raw('(credits.available_credit - credits.consumed_credit) as credit_left'))
            ->leftjoin('companies', 'companies.id', '=', 'credits.company_id')
            ->where('credits.id', $id)
            ->first();

        return $credit;
    }

    public static function createCredit($data){
        $credit = Credit::create([
            'company_id' => $data['company_id'],
            'available_credit' => $data['available_credit'],
            'consumed_credit' => $data['consumed_credit'],
            'year' => $data['year']
        ]);

        return $credit;
    }

    public static function updateCredit($id, $data){
        $credit = Credit::find($id);
        $credit->update([
            'company_id' => $data['company_id'],
            'available_credit' => $data['available_credit'],
            'consumed_credit' => $data['consumed_credit'],
            'year' => $data['year']
        ]);

        return $credit;
    }

}
