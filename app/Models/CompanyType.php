<?php

namespace App\Models;

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

    public function getCompanyTypes($keyWord){
        $companyTypes = CompanyType::
        orWhere('name', 'LIKE', $keyWord)
            ->paginate(10);
        foreach ($companyTypes as $companyType){
            $company = Company::where('company_type_id', $companyType['id'])->first();
            if ($company){
                $companyType['used'] = true;
            } else {
                $companyType['used'] = false;
            }
        }
        return $companyTypes;
    }

    public function createCompanyType($data){
        $company_type = CompanyType::create([
            'name' => $data['name']
        ]);

        return $company_type;
    }

    public function updateCompanyType($id, $data){
        $company_type = CompanyType::find($id);
        $company_type->update([
            'name' => $data['name']
        ]);

        return $company_type;
    }
}
