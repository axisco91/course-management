<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class TrainingContractElement extends Model
{
    use HasFactory;

    protected $fillable = ['certification_id', 'training_action_id', 'training_contract_id', 'beginning', 'end', 'total_days', 'order', 'course_id'];

    public static function getTrainingContractElements($training_contract_id){
        $training_contract_elements = TrainingContractElement::select('training_contract_elements.*', 'certifications.name as certification_name', 'certifications.total_hours as certification_total_hours',
            'training_contracts.number_cfa as cfa',
            'training_actions.formative_action', 'training_actions.name as training_action_name', 'training_actions.total_hours as training_action_total_hours',
        'training_actions.face_to_face_hours as training_action_face_to_face_hours', 'training_actions.teletraining_hours as training_action_teletraining_hours',
        'certifications.face_to_face_hours as certification_face_to_face_hours', 'certifications.teletraining_hours as certification_teletraining_hours')
            ->leftjoin('training_actions', 'training_actions.id', '=', 'training_contract_elements.training_action_id')
            ->leftjoin('certifications', 'certifications.id', '=', 'training_contract_elements.certification_id')
            ->leftjoin('training_contracts', 'training_contracts.id', '=', 'training_contract_elements.training_contract_id')
            ->where('training_contract_id', $training_contract_id)->orderBy('order', 'asc')->get();
        return $training_contract_elements;
    }

    public static function createTrainingContractElement($training_contract_id, $element_id, $type){
        $hours = 0;
        $training_contract_element = null;
        $last_training_contract_element = TrainingContractElement::where('training_contract_id', $training_contract_id)
        ->orderBy('order', 'desc')->first();
        $order = $last_training_contract_element ? $last_training_contract_element->order + 1 : 1;
        if ($type == 'certification_id'){
            $training_contract_element = TrainingContractElement::where('certification_id', $element_id)
                ->where('training_contract_id', $training_contract_id)->first();
            if (!$training_contract_element){
                $training_contract_element = TrainingContractElement::create([
                    'training_contract_id' => $training_contract_id,
                    'certification_id' => $element_id,
                    'order' => $order
                ]);
                $certification = Certification::find($element_id);
                $hours = $certification['total_hours'];
            }
        } else if ($type == 'training_action_id'){
            $training_contract_element = TrainingContractElement::where('training_action_id', $element_id)
                ->where('training_contract_id', $training_contract_id)->first();
            if (!$training_contract_element){
                $training_contract_element = TrainingContractElement::create([
                    'training_contract_id' => $training_contract_id,
                    'training_action_id' => $element_id,
                    'order' => $order
                ]);
                $training_action = TrainingAction::find($element_id);
                $hours = $training_action['total_hours'];
            }
        }

        $training_contract = TrainingContract::find($training_contract_id);
        $training_contract->update([
            'formation_hours' => $training_contract['formation_hours'] + $hours
        ]);
        return $training_contract_element;
    }

    public static function deleteTrainingContractElement($id){
        $training_contract_element = TrainingContractElement::find($id);
        $training_contract = TrainingContract::find($training_contract_element->training_contract_id);
        $hours = 0;
        if ($training_contract){
            if ($training_contract_element){
                if ($training_contract_element->training_action_id) {
                    $training_action = TrainingAction::find($training_contract_element->training_action_id);
                    $hours = $training_action['total_hours'];
                } else if($training_contract_element->certification_id) {
                    $certification = Certification::find($training_contract_element->certification_id);
                    $hours = $certification['total_hours'];
                }
                $training_contract_element->delete();
            }
            $training_contract->update([
                'formation_hours' => - $hours
            ]);
        }
    }

    public static function getElements(){
        $training_contract_elements = TrainingContractElement::select('training_contract_elements.*', 'certifications.name as certification_name', 'certifications.total_hours as certification_total_hours',
            DB::raw("IFNULL(certifications.name, training_actions.name) AS course"), 'training_contracts.number_cfa as cfa',
            'training_actions.formative_action', 'training_actions.name as training_action_name', 'training_actions.total_hours as training_action_total_hours',
            DB::raw("CONCAT(students.name,' ',students.surname) as student"), 'companies.name as company', 'students.dni as dni',
            'training_actions.face_to_face_hours as training_action_face_to_face_hours', 'training_actions.teletraining_hours as training_action_teletraining_hours',
            'certifications.face_to_face_hours as certification_face_to_face_hours', 'certifications.teletraining_hours as certification_teletraining_hours')
            ->leftjoin('training_actions', 'training_actions.id', '=', 'training_contract_elements.training_action_id')
            ->leftjoin('certifications', 'certifications.id', '=', 'training_contract_elements.certification_id')
            ->leftjoin('training_contracts', 'training_contracts.id', '=', 'training_contract_elements.training_contract_id')
            ->leftjoin('students', 'students.id', '=', 'training_contracts.student_id')
            ->leftjoin('companies', 'companies.id', '=', 'training_contracts.company_id')
            ->whereDate('training_contract_elements.beginning', '<=', Carbon::now())
            ->whereDate('training_contract_elements.end', '>=', Carbon::now())->orderBy('order', 'asc')->get();
        return $training_contract_elements;
    }

    public static function getElementsCSV($student = null, $company = null, $beginning = null, $end = null){
        $training_contract_elements = TrainingContractElement::select('training_contract_elements.*', 'certifications.name as certification_name', 'certifications.total_hours as certification_total_hours',
            'training_actions.formative_action', 'training_actions.name as training_action_name', 'training_actions.total_hours as training_action_total_hours', 'training_contracts.number_cfa as cfa',
            DB::raw("IFNULL(certifications.name, training_actions.name) AS course"),
            DB::raw("CONCAT(students.name,' ',students.surname) as student"), 'companies.name as company', 'students.dni as dni',
            'training_actions.face_to_face_hours as training_action_face_to_face_hours', 'training_actions.teletraining_hours as training_action_teletraining_hours',
            'certifications.face_to_face_hours as certification_face_to_face_hours', 'certifications.teletraining_hours as certification_teletraining_hours')
            ->leftjoin('training_actions', 'training_actions.id', '=', 'training_contract_elements.training_action_id')
            ->leftjoin('certifications', 'certifications.id', '=', 'training_contract_elements.certification_id')
            ->leftjoin('training_contracts', 'training_contracts.id', '=', 'training_contract_elements.training_contract_id')
            ->leftjoin('students', 'students.id', '=', 'training_contracts.student_id')
            ->leftjoin('companies', 'companies.id', '=', 'training_contracts.company_id')
            ->whereDate('training_contract_elements.beginning', '<=', Carbon::now())
            ->whereDate('training_contract_elements.end', '>=', Carbon::now());

        if ($student) {
            $training_contract_elements = $training_contract_elements->where('students.id', 'LIKE', $student);
        }
        if ($company) {
            $training_contract_elements = $training_contract_elements->where('companies.id', $company);
        }
        if ($beginning) {
            $training_contract_elements = $training_contract_elements->where('training_contract_elements.beginning', '>=', $beginning);
        }
        if ($end) {
            $training_contract_elements = $training_contract_elements->where('training_contract_elements.beginning', '<=', $end);
        }

        $training_contract_elements = $training_contract_elements->orderBy('order', 'asc')->get();

        $data = [];
        if (count($training_contract_elements) > 0) {
            foreach($training_contract_elements as $training_contract_element) {
                $beginning = \Carbon\Carbon::parse($training_contract_element['beginning'])->format('d/m/Y');
                $end = Carbon::parse($training_contract_element['end'])->format('d/m/Y');
                $element = [
                    'CFA' => $training_contract_element['cfa'],
                    'Nombre Curso' => $training_contract_element['course'],
                    'Empresa' => $training_contract_element['company'],
                    'Alumno' => $training_contract_element['student'],
                    'DNI' => $training_contract_element['DNI'],
                    'Inicio' => $beginning,
                    'Fin' => $end,
                ];
                $data[] = $element;
            }
        } else {
            $element = [
                'CFA' => '',
                'Nombre Curso' => '',
                'Empresa' => '',
                'Alumno' => '',
                'DNI' => '',
                'Inicio' => '',
                'Fin' => '',
            ];
            $data[] = $element;
        }
        return $data;
    }

    public static function getTrainingContractElement($id){
        $training_contract_element = TrainingContractElement::select('training_contract_elements.*', 'certifications.name as certification_name', 'certifications.total_hours as certification_total_hours',
            'training_actions.formative_action', 'training_actions.name as training_action_name', 'training_actions.total_hours as training_action_total_hours')
            ->leftjoin('training_actions', 'training_actions.id', '=', 'training_contract_elements.training_action_id')
            ->leftjoin('certifications', 'certifications.id', '=', 'training_contract_elements.certification_id')
            ->where('training_contract_elements.id', $id)->first();
        return $training_contract_element;
    }

    public static function orderTrainingContractElement($data) {
        $cont = 1;
        foreach ($data as $element) {
            $training_contract_element = TrainingContractElement::find($element['id']);
            $training_contract_element->update([
                'order' => $cont
            ]);
            $cont++;
        }
    }

    public function scopeInfo($query) {
        return $query->select('training_contract_elements.*', 'certifications.name as certification_name', 'certifications.total_hours as certification_total_hours',
            'training_contracts.number_cfa as cfa',
            'training_actions.formative_action', 'training_actions.name as training_action_name', 'training_actions.total_hours as training_action_total_hours',
            'training_actions.face_to_face_hours as training_action_face_to_face_hours', 'training_actions.teletraining_hours as training_action_teletraining_hours',
            'certifications.face_to_face_hours as certification_face_to_face_hours', 'certifications.teletraining_hours as certification_teletraining_hours')
            ->leftjoin('training_actions', 'training_actions.id', '=', 'training_contract_elements.training_action_id')
            ->leftjoin('certifications', 'certifications.id', '=', 'training_contract_elements.certification_id')
            ->leftjoin('training_contracts', 'training_contracts.id', '=', 'training_contract_elements.training_contract_id')
            ->orderBy('order', 'asc');
    }

    public function scopeTrainingContracts($query, $trainingContractId){
        return $query->where('training_contract_id', $trainingContractId)
            ->orderBy('order', 'asc');
    }

}
