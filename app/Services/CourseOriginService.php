<?php

namespace App\Services;

use App\Models\CourseOrigin;

class CourseOriginService
{
    /**
     * Función para crear un origen
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return CourseOrigin::create([
            'name' => $data['name'],
        ]);
    }

    /**
     * Función para editar un origen
     */
    public function update(CourseOrigin $courseOrigin, array $data) {
        $courseOrigin->update([
            'name' => $data['name'],
        ]);
        return $courseOrigin;
    }
}
