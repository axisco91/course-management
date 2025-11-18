<?php

namespace App\Services;

use App\Models\ExamTutorial;
use Carbon\Carbon;

class ExamTutorialService
{
    /**
     * Función para crear un examen tutoría
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return ExamTutorial::create([
            'training_contract_id' => $data['training_contract_id'],
            'center_id' => $data['center_id'],
            'type' => $data['type'],
            'date' => Carbon::createFromFormat('d-m-Y',$data['date'])->toDateString(),
            'beginning' => Carbon::createFromFormat('H:i:s', $data['beginning'].':00')->toTimeString(),
            'end' => $data['end'] ? Carbon::createFromFormat('H:i:s', $data['end'].':00')->toTimeString() : '',
            'training_action_id' => $data['training_action_id'],
            'main_company_id' => $data['main_company_id'],
        ]);;
    }

    /**
     * Función para editar un examen tutoría
     */
    public function update(ExamTutorial $examTutorial, array $data) {
        $examTutorial->update([
            'training_contract_id' => $data['training_contract_id'],
            'center_id' => $data['center_id'],
            'type' => $data['type'],
            'date' => Carbon::createFromFormat('d-m-Y',$data['date'])->toDateString(),
            'beginning' => $data['beginning'],
            'end' => $data['end'],
            'training_action_id' => $data['training_action_id'],
        ]);;
        return $examTutorial;
    }
}
