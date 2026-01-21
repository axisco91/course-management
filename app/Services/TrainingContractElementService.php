<?php

namespace App\Services;

use App\Models\Certification;
use App\Models\Course;
use App\Models\Teacher;
use App\Models\TrainingAction;
use App\Models\TrainingContract;
use App\Models\TrainingContractElement;
use Illuminate\Support\Carbon;

class TrainingContractElementService
{
    /**
     * Función para crear una convocatoria del contrato de formación
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        $tutorName = '';
        $tutorDNI = '';
        // Vemos si tiene un curso asignado para buscar el nombre y DNI  por defecto del docente
        if (isset($data['course_id'])) {
            $course = Course::find($data['course_id']);
            if ($course) {
                if ($course->teacher_id) {
                    $teacher = Teacher::find($course->teacher_id);
                    if ($teacher) {
                        $tutorName = $teacher->name.' '.$teacher->surname;
                        $tutorDNI = $teacher->dni;
                    }
                }
            }
        }

        return TrainingContractElement::create([
            'training_contract_id' => $data['training_contract_id'] ?? null,
            'certification_id' => $data['certification_id'] ?? null,
            'training_action_id' => $data['training_action_id'] ?? null,
            'total_days' => $data['total_days'] ?? null,
            'beginning' => $data['beginning'] ?? null,
            'end' => $data['end'] ?? null,
            'order' => $data['order'] ?? null,
            'course_id' => $data['course_id'] ?? null,
            'training_tutor' => $tutorName,
            'training_tutor_dni' => $tutorDNI,
            'main_company_id' => $data['main_company_id'],
        ]);
    }

    /**
     * Función para editar una convocatoria del contrato de formación
     */
    public function update(TrainingContractElement $trainingContractElement, array $data) {
        $trainingContractElement->update([
            'training_contract_id' => $data['training_contract_id'],
            'certification_id' => $data['certification_id'] ?? null,
            'training_action_id' => $data['training_action_id'] ?? null,
            'total_days' => $data['total_days'] ?? null,
            'beginning' => $data['beginning'],
            'end' => $data['end'],
            'order' => $data['order'] ?? null,
            'course_id' => $data['course_id'] ?? null,
        ]);
        return $trainingContractElement;
    }

    public function createTrainingContractElement($data){
        $hours = 0;
        $trainingContractId = $data['training_contract_id'];
        $elementId = $data['element_id'];
        $type = $data['type'];

        $trainingContract = TrainingContract::find($trainingContractId);
        $highestOrder = TrainingContractElement::where('training_contract_id', $trainingContractId)
            ->max('order');
        // El inicio es siempre el inicio de la formación del contrato de formación
    //    $beginning = new Carbon($trainingContract->beginning_formation);
    //    $end = $beginning->copy();

        if ($type == 'certification_id'){
            $trainingContractElement = TrainingContractElement::where('certification_id', $elementId)
                ->where('training_contract_id', $trainingContractId)->first();
            if (!$trainingContractElement){
                $certification = Certification::find($elementId);
                $hours = $certification['total_hours'];

                $elementData = [
                    'training_contract_id' => $trainingContractId,
                    'certification_id' => $elementId,
                //    'beginning' => $beginning,
                //    'end' => $end,
                    'order' => $highestOrder+1,
                    'main_company_id' => $data['main_company_id'],
                ];
                return $this->create($elementData);
            }
        } else if ($type == 'training_action_id') {
            $trainingContractElement = TrainingContractElement::where('training_action_id', $elementId)
                ->where('training_contract_id', $trainingContractId)->first();
            if (!$trainingContractElement) {
                $trainingAction = TrainingAction::find($elementId);
                $hours = $trainingAction['total_hours'];

                // Aquí se añaden los valores predeterminados de training_tutor y training_tutor_dni
                $elementData = [
                    'training_contract_id' => $trainingContractId,
                    'training_action_id' => $elementId,
                  //  'beginning' => $beginning,
                  //  'end' => $end,
                    'training_tutor' => $trainingAction->training_tutor,
                    'training_tutor_dni' => $trainingAction->training_tutor_dni,
                    'order' => $highestOrder+1,
                    'main_company_id' => $data['main_company_id']
                ];
                $trainingContractElement = $this->create($elementData);
            }
        }
        if (isset($trainingContractElement->beginning) && isset($trainingContractElement->end)) {
            $beginning = new \DateTime($trainingContractElement->beginning);
            $end = new \DateTime($trainingContractElement->end);
            $totalDays = $beginning->diff($end)->days;

            $trainingContractElement->total_days = $totalDays;
            $trainingContractElement->save();
        }

        $trainingContract->update([
            'formation_hours' => $trainingContract['formation_hours'] + $hours
        ]);
        return $trainingContractElement;
    }

    /**
     * Función para editar una convocatoria del contrato de formación
     */
    public function updateTutorInfo(TrainingContractElement $trainingContractElement, array $data) {
        $trainingContractElement->update([
            'training_tutor' => $data['training_tutor'],
            'training_tutor_dni' => $data['training_tutor_dni']
        ]);
        return $trainingContractElement;
    }

    /**
     * Función para editar las fechas
     */
    public function updateDates(TrainingContractElement $trainingContractElement, array $data) {
        $beginningRaw = $data['beginning'] ?? null;
        $endRaw = $data['end'] ?? null;

        $parseDate = function ($v) {
            if (!$v) return null;

            // Si ya viene YYYY-MM-DD, úsalo directo
            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $v)) {
                return Carbon::createFromFormat('Y-m-d', $v)->format('Y-m-d');
            }

            // Si viene DD-MM-YYYY
            if (preg_match('/^\d{2}-\d{2}-\d{4}$/', $v)) {
                return Carbon::createFromFormat('d-m-Y', $v)->format('Y-m-d');
            }

            // último intento (por si llega con / o datetime)
            try {
                return Carbon::parse($v)->format('Y-m-d');
            } catch (\Exception $e) {
                return null; // o lanza excepción si quieres
            }
        };

        $beginning = $parseDate($beginningRaw);
        $end = $parseDate($endRaw);
        $trainingContractElement->update([
            'beginning' => $beginning,
            'end' =>  $end,
        ]);

        if ($trainingContractElement->course_id && $beginning && $end) {
            $course = Course::find($trainingContractElement->course_id);

            if ($course) {
                $course->courseDates($beginning, $end);
                $course->updateDatesWithService($data);
            }
        }

        return $trainingContractElement;
    }

    /**
     * Función para añadir curso_id a la convocatoría
     */
    public function addCourse(TrainingContractElement $trainingContractElement, array $data) {
        $course = Course::find($data['course_id']);

        // Vemos si tiene un curso asignado para buscar el nombre y DNI  por defecto del docente
        if ($course) {
            $tutorName = '';
            $tutorDNI = '';
            if ($course->teacher_id) {
                $teacher = Teacher::find($course->teacher_id);
                if ($teacher) {
                    $tutorName = $teacher->name.' '.$teacher->surname;
                    $tutorDNI = $teacher->dni;
                }
            }
            $trainingContractElement->update([
                'course_id' => $data['course_id'],
                'training_tutor' => $tutorName,
                'training_tutor_dni' => $tutorDNI
            ]);
            return $trainingContractElement;
        }
    }
}
