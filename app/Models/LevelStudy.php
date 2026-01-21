<?php

namespace App\Models;

use App\Services\LevelStudyService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LevelStudy extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'level_studies';

    protected $fillable = ['name', 'code'];

    /**
     * Relación uno a muchos con el modelo Student.
     *
     * Un nivel de estudio puede estar asociado con múltiples estudiantes.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function students()
    {
        return $this->hasMany('App\Models\Student', 'level_study_id', 'id');
    }

    public static function createWithService($data)
    {
        $service = app(LevelStudyService::class);

        return $service->create($data);
    }

    public function updateWithService($data)
    {
        $service = app(LevelStudyService::class);

        return $service->update($this, $data);
    }
}
