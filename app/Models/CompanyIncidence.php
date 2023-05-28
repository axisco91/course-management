<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
        $company_incidences = CompanyIncidence::select('company_incidences.*',
            'company_incidences.id as value',
            'company_incidences.affair as label',
            'incidence_types.name as incidence_type',
            DB::raw("CONCAT(users.name,' ',users.surname) as user"))
            ->leftjoin('incidence_types', 'incidence_types.id', '=', 'company_incidences.incidence_type_id')
            ->leftjoin('users', 'users.id', '=', 'company_incidences.user_id')
            ->where('company_id', $company_id)->get();
        foreach ($company_incidences as $company_incidence) {
            $company_incidence['created'] = Carbon::createFromFormat('Y-m-d H:i:s', $company_incidence['created_at'])->format('d/m/Y');
        }
        return $company_incidences;
    }

    public static function getCompanyIncidence($id){
        $company_incidence = CompanyIncidence::select('company_incidences.*', 'incidence_types.name as incidence_type',
            DB::raw("CONCAT(users.name,' ',users.surname) as user"))
            ->leftjoin('incidence_types', 'incidence_types.id', '=', 'company_incidences.incidence_type_id')
            ->leftjoin('users', 'users.id', '=', 'company_incidences.user_id')
            ->where('company_incidences.id', $id)->first();
        $company_incidence['created'] = Carbon::createFromFormat('Y-m-d H:i:s', $company_incidence['created_at'])->format('d/m/Y');
        return $company_incidence;
    }

    public static function createCompanyIncidence($data){
        $company_incidence = CompanyIncidence::create([
            'company_id' => $data['company_id'],
            'incidence_type_id' => $data['incidence_type_id'],
            'affair' => $data['affair'],
            'notes' => $data['notes'],
            'user_id' => $data['user_id']
        ]);
        return $company_incidence;
    }

    public static function updateCompanyIncidence($id, $data){
        $company_incidence = CompanyIncidence::find($id);
        $company_incidence->update([
            'company_id' => $data['company_id'],
            'incidence_type_id' => $data['incidence_type_id'],
            'affair' => $data['affair'],
            'notes' => $data['notes'],
            'user_id' => $data['user_id']
        ]);
        return $company_incidence;
    }
}
