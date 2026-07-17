<?php

namespace App\Services;

use App\Models\Course;
use App\Models\CourseStatus;
use App\Models\TrainingAction;
use Illuminate\Support\Carbon;

class CourseService
{
    /**
     * Función para crear un curso
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        $beginning = $this->normalizeDate($data['beginning'] ?? null);
        $end = $this->normalizeDate($data['end'] ?? null);
        $course_info = Course::courseDates($beginning, $end);
        $anulado = CourseStatus::where('name', 'ANULADO')->first();
        if (isset($data['canceled']) && $data['canceled'] == 1) {
            $course_info['course_status_id'] = $anulado->id;
        }

        $course = Course::create([
            'name' => $data['name'],
            'training_action_id' => $data['training_action_id'],
            'group' => $data['group'],
            'course_type_id' => $data['course_type_id'],
            'teacher_id' => $data['teacher_id'],
            'web_platform_id' => $data['web_platform_id'] ?? null,
            'beginning' => $beginning,
            'end' => $end,
            'morning_schedule' => $data['morning_schedule'],
            'afternoon_schedule' => $data['afternoon_schedule'],
            'formation_center_id' => $data['formation_center_id'],
            'delivery_center_id' => $data['delivery_center_id'],
            'course_observation' => $data['course_observation'],
            'welcome_date' => $beginning,
            'quarter_date' => $course_info['quarter'],
            'half_date' => $course_info['half'],
            'three_quarters_date' => $course_info['three_quarters'],
            'final_date' => $end,
            'course_status_id' => $course_info['course_status_id'],
            'price' => $data['price'],
            'nebrija' => $data['nebrija'],
            'monday' => $data['monday'],
            'tuesday' => $data['tuesday'],
            'wednesday' => $data['wednesday'],
            'thursday' => $data['thursday'],
            'friday' => $data['friday'],
            'saturday' => $data['saturday'],
            'sunday' => $data['sunday'],
            'outsourced' => $data['outsourced'],
            'reactivated' => $data['reactivated'],
            'main_company_id' => $data['main_company_id'],
        ]);
        return $course;
    }

    private function normalizeDate($value): ?string
    {
        if (!$value) {
            return null;
        }

        $value = trim($value);
        $formats = ['Y-m-d', 'd-m-Y', 'd/m/Y'];

        foreach ($formats as $format) {
            try {
                return Carbon::createFromFormat($format, $value)->format('Y-m-d');
            } catch (\Exception $e) {
                continue;
            }
        }

        return Carbon::parse($value)->format('Y-m-d');
    }

    /**
     * Función para editar un curso
     */
    public function update(Course $course, array $data) {

        $course_info = Course::courseDates(
           $data['beginning'],
            $data['end']
        );

        // Si se proporciona course_status_id en la solicitud, úsalo
        if (isset($data['course_status_id'])) {
            $course_info['course_status_id'] = $data['course_status_id'];
            \Log::info('Using provided course_status_id: ' . $data['course_status_id']);
        } elseif (isset($data['canceled']) && $data['canceled'] == 1) {
            $anulado = CourseStatus::where('name', 'ANULADO')->first();
            $course_info['course_status_id'] = $anulado->id;
            \Log::info('Course marked as canceled. Setting status to ANULADO (ID: ' . $anulado->id . ')');
        }

        $course->update([
            'name' => $data['name'],
            'training_action_id' => $data['training_action_id'],
            'group' => $data['group'],
            'course_type_id' => $data['course_type_id'],
            'teacher_id' => $data['teacher_id'],
            'web_platform_id' => $data['web_platform_id'] ?? null,
            'beginning' => $data['beginning'],
            'end' => $data['end'],
            'morning_schedule' => $data['morning_schedule'],
            'afternoon_schedule' => $data['afternoon_schedule'],
            'formation_center_id' => $data['formation_center_id'],
            'delivery_center_id' => $data['delivery_center_id'],
            'course_observation' => $data['course_observation'],
            'welcome_date' => $data['beginning'],
            'quarter_date' => $course_info['quarter'],
            'half_date' => $course_info['half'],
            'three_quarters_date' => $course_info['three_quarters'],
            'final_date' => $data['end'],
            'course_status_id' => $course_info['course_status_id'],
            'price' => $data['price'],
            'nebrija' => $data['nebrija'],
            'monday' => $data['monday'],
            'tuesday' => $data['tuesday'],
            'wednesday' => $data['wednesday'],
            'thursday' => $data['thursday'],
            'friday' => $data['friday'],
            'saturday' => $data['saturday'],
            'sunday' => $data['sunday'],
            'outsourced' => $data['outsourced'],
            'reactivated' => $data['reactivated']
        ]);

        return $course;
    }

    public function setName($id, $trainingActionId, $mainCompanyId) {
        if ($trainingActionId > 0){
            $trainingAction = TrainingAction::where('id', $trainingActionId)
                ->FilterMainCompany($mainCompanyId)
                ->first();

            if ($trainingAction) {
                if ($trainingActionId < 10){
                    $name = '00'.$trainingActionId;
                } else if ($trainingActionId < 100){
                    $name = '0'.$trainingActionId;
                } else {
                    $name = $trainingActionId;
                }

                $num_courses = Course::where( 'training_action_id', $trainingAction['id'])
                    ->FilterMainCompany($mainCompanyId)
                    ->orderby('group', 'desc')->first();

                if ($num_courses) {
                    $cont = intval($num_courses->group);
                    $cont = $cont+1;
                    if ($cont < 10){
                        $group = '000'.$cont;
                    } else if ($cont < 100){
                        $group = '00'.$cont;
                    } else if ($cont < 1000){
                        $group = '0'.$cont;
                    } else {
                        $group = $cont;
                    }
                } else {
                    $group = '0001';
                }
                $price = '';
                if ($id == null){
                    $price = $trainingAction['price'];
                }
            }

            return [
                'name' => $name.' / '. $group .' - '.$trainingAction['name'],
                'group' => $group,
                'price' => $price
            ];
        }
        return null;
    }

    /**
     * Función para editar un curso
     */
    public function updateDates(Course $course, array $data) {

        $course->update([
            'beginning' => $data['beginning'] ? Carbon::createFromFormat('d-m-Y', $data['beginning'])->format('Y-m-d') : null,
            'end' => $data['end'] ? Carbon::createFromFormat('d-m-Y', $data['end'])->format('Y-m-d') : null,
        ]);

        return $course;
    }
}
