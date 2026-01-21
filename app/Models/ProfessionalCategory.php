<?php

namespace App\Models;

use App\Services\ProfessionalCategoryService;
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

    public function scopeGetProfessionalCategory($query)
    {
        return $query
            ->select(
                'professional_categories.*',
                'professional_categories.id as value',
                'professional_categories.name as label'
            )
            ->leftJoin(
                'students',
                'students.professional_category_id',
                '=',
                'professional_categories.id'
            )
            ->selectRaw('CASE WHEN students.id IS NULL THEN false ELSE true END as used')
            ->groupBy('professional_categories.id');
    }

    public static function createWithService($data)
    {
        $service = app(ProfessionalCategoryService::class);
        return $service->create($data);
    }

    public function updateWithService($data)
    {
        $service = app(ProfessionalCategoryService::class);
        return $service->update($this, $data);
    }
}
