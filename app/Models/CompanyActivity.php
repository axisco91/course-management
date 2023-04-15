<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public static function getCompanyActivities(){
        $companyActivities = CompanyActivity::select('*', 'id as value', 'name as label')->get();
        foreach ($companyActivities as $companyActivity) {
            $company = Company::where('company_activity_id', $companyActivity['id'])->first();
            if ($company) {
                $companyActivity['used'] = true;
            } else {
                $companyActivity['used'] = false;
            }
        }

        return $companyActivities;
    }

    public static function createCompanyActivity($data){
        $company_activity = CompanyActivity::create([
            'name' => $data['name']
        ]);

        return $company_activity;
    }

    public static function updateCompanyActivity($id, $data){
        $record = CompanyActivity::find($id);
        $record->update([
            'name' => $data['name']
        ]);
    }

}
