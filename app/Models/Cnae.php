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

    public function getCnaes($keyWord){
        $cnaes = Cnae::orWhere('name', 'LIKE', $keyWord)
            ->paginate(10);

        foreach ($cnaes as $cnae){
            $company = Company::where('cnae_id', $cnae['id'])->first();
            if ($company){
                $cnaes['used'] = true;
            } else{
                $cnaes['used'] = false;
            }
        }
        return $cnaes;
    }

    public function createCnae($data){
        $cnae = Cnae::create([
            'name' => $this-> name
        ]);

        return $cnae;
    }

    public function updateCnae($id, $data){
        $cnae = Cnae::find($this->selected_id);
        $cnae->update([
            'name' => $this-> name
        ]);

        return $cnae;
    }

}
