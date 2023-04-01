<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfessionalCategory extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function students()
    {
        return $this->hasMany('App\Models\Student', 'professional_category_id', 'id');
    }

    public static function getProfessionalCategories(){
        $professional_categories = ProfessionalCategory::
        select('professional_categories.*', 'id as value', 'name as label')
            ->get();
        foreach ($professional_categories as $professional_category){
            $student = Student::where('professional_category_id', $professional_category['id'])->first();
            if ($student) {
                $professional_category['used'] = true;
            } else {
                $professional_category['used'] = false;
            }
        }
        return $professional_categories;
    }

    public static function getProfessionalCategory($id){
        $professional_category = ProfessionalCategory::
        select('professional_categories.*', 'id as value', 'name as label')
            ->where('id', $id)->first();
        $student = Student::where('professional_category_id', $professional_category['id'])->first();
        if ($student) {
            $professional_category['used'] = true;
        } else {
            $professional_category['used'] = false;
        }
        return $professional_category;
    }

    public static function createProfessionalCategory($data){
        $professional_category = ProfessionalCategory::create([
            'name' => $data['name']
        ]);
        return $professional_category;
    }

    public static function updateProfessionalCategory($id, $data){
        $professional_category = ProfessionalCategory::find($id);
        $professional_category->update([
            'name' => $data['name']
        ]);
        return $professional_category;
    }

}
