<?php

namespace App\Services;

use App\Models\CourseStatus;

class CourseStatusService
{
    /**
     * Función para crear un estado
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return CourseStatus::create([
            'name' => $data['name'],
        ]);
    }

    /**
     * Función para editar un estado
     */
    public function update(CourseStatus $courseStatus, array $data) {
        $courseStatus->update([
            'name' => $data['name'],
        ]);
        return $courseStatus;
    }
}
