<?php

namespace App\Models;

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

    /**
     * Obtiene todos los niveles de estudio.
     *
     * Recupera todos los registros de niveles de estudio, agregando un campo 'used' que indica 
     * si el nivel de estudio está asociado a algún estudiante.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getLevelStudies(){
        $level_studies = LevelStudy::select('*', 'id as value', 'name as label')
            ->get();
        foreach ($level_studies as $level_study){
            $student = Student::where('level_study_id', $level_study['id'])->first();
            $level_study['used'] = $student ? true : false;
        }
        return $level_studies;
    }

    /**
     * Obtiene un nivel de estudio por su ID.
     *
     * Recupera un registro de nivel de estudio por su ID, agregando un campo 'used' que indica 
     * si el nivel de estudio está asociado a algún estudiante.
     *
     * @param int $id
     * @return \App\Models\LevelStudy|null
     */
    public static function getLevelStudy($id){
        $level_study = LevelStudy::select('*', 'id as value', 'name as label')
            ->where('id', $id)->first();
        $student = Student::where('level_study_id', $level_study['id'])->first();
        $level_study['used'] = $student ? true : false;
        return $level_study;
    }

    /**
     * Crea un nuevo nivel de estudio.
     *
     * Crea un nuevo registro en la tabla 'level_studies' con los datos proporcionados.
     *
     * @param array $data
     * @return \App\Models\LevelStudy
     */
    public static function createLevelStudy($data){
        $level_study = LevelStudy::create([
            'name' => $data['name'],
            'code' => $data['code']
        ]);

        return $level_study;
    }

    /**
     * Actualiza un nivel de estudio existente.
     *
     * Actualiza un registro en la tabla 'level_studies' con los datos proporcionados.
     *
     * @param int $id
     * @param array $data
     * @return \App\Models\LevelStudy
     */
    public static function updateLevelStudy($id, $data){
        $level_study = LevelStudy::find($id);
        $level_study->update([
            'name' => $data['name'],
            'code'=> $data['code']
        ]);

        return $level_study;
    }

}
