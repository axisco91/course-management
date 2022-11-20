<?php

namespace App\Imports;

use App\Models\Company;
use App\Models\Province;
use Maatwebsite\Excel\Concerns\ToModel;

class CompaniesImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $province = Province::where('name', 'like', $row[5])->first();
        if ($row[1] != ''){
            $company = Company::where('name', 'like', $row[0])->first();
            if ($company){
                return false;
            } else{
                return new Company([
                    'name' => $row[0],
                    'nif' => $row[1],
                    'telephone' => $row[2],
                    'email' => $row[3],
                    'address' => $row[4],
                    'province' => $province ? $province->id : null,
                    'population' => $row[6],
                    'active' => 0,
                    'potential' => 1,
                ]);
            }
        } else{
            $company = Company::where('nif', $row[1])->first();
            if ($company){
                return false;
            } else{
                return new Company([
                    'name' => $row[0],
                    'nif' => $row[1],
                    'telephone' => $row[2],
                    'email' => $row[3],
                    'address' => $row[4],
                    'province' => $province ? $province->id : null,
                    'population' => $row[6],
                    'active' => 0,
                    'potential' => 1,
                ]);
            }
        }
    }
}
