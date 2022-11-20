<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyIncidence extends Model
{
	use HasFactory;

    protected $fillable = [
        'affair',
        'notes',
        'incidence_type_id',
        'company_id',
        'user_id',
    ];

    public static function getCompanyIncidences($company_id){
        $company_incidences = CompanyIncidence::select('*')->where('company_id', $company_id)->get();

        return $company_incidences;
    }

    public static function createCompanyIncidence($data){
        $company_incidence = CompanyIncidence::create(
            $data
        );

        return $company_incidence;
    }

    public static function updateCompanyIncidence($id, $data){
        $company_incidence = CompanyIncidence::find($id);
        $company_incidence->update(
            $data
        );

        return $company_incidence;
    }
}
