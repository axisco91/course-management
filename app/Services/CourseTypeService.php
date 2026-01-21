<?php

namespace App\Services;

use App\Models\CourseStatus;
use App\Models\CourseType;

class CourseTypeService
{
    /**
     * Función para crear un tipo
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return CourseType::create([
            'name' => $data['name'],
        ]);
    }

    /**
     * Función para editar un tipo
     */
    public function update(CourseType $courseType, array $data) {
        $courseType->update([
            'name' => $data['name'],
        ]);
        return $courseType;
    }
}
