<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cnae extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function companies()
    {
        return $this->hasMany('App\Models\Company', 'cnae_id', 'id');
    }

    public static function getCnaes(){
        $cnaes = Cnae::select('cnaes.*', 'id as value', 'name as label')
            ->get();

        foreach ($cnaes as $cnae){
            $company = Company::where('cnae_id', $cnae['id'])->first();
            if ($company){
                $cnae['used'] = true;
            } else{
                $cnae['used'] = false;
            }
        }
        return $cnaes;
    }

    public static function createCnae($data){
        $cnae = Cnae::create([
            'name' => $data['name']
        ]);

        return $cnae;
    }

    public static function updateCnae($id, $data){
        $cnae = Cnae::find($id);
        $cnae->update([
            'name' => $data['name']
        ]);

        return $cnae;
    }

}
