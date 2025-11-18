<?php

namespace App\Models;

use App\Services\TrainingContractElementService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;


class TrainingContractElement extends Model
{
    use HasFactory;

    protected $fillable = ['certification_id', 'training_action_id', 'training_contract_id', 'beginning', 'end', 'total_days', 'order', 'course_id', 'training_tutor', 'training_tutor_dni', 'main_company_id'];

    public function training_contract(){
        return $this->belongsTo(TrainingContract::class);
    }
    public function training_action(){
        return $this->belongsTo(TrainingAction::class);
    }

    public function certification(){
        return $this->belongsTo(Certification::class);
    }

    public static function getTrainingContractElements($trainingContractId, $mainCompanyId){
        return TrainingContractElement::select('training_contract_elements.*', 'certifications.name as certification_name', 'certifications.total_hours as certification_total_hours',
            'training_contracts.number_cfa as cfa',
            'training_actions.formative_action', 'training_actions.name as training_action_name', 'training_actions.total_hours as training_action_total_hours',
        'training_actions.face_to_face_hours as training_action_face_to_face_hours', 'training_actions.teletraining_hours as training_action_teletraining_hours',
        'certifications.face_to_face_hours as certification_face_to_face_hours', 'certifications.teletraining_hours as certification_teletraining_hours')
            ->leftjoin('training_actions', 'training_actions.id', '=', 'training_contract_elements.training_action_id')
            ->leftjoin('certifications', 'certifications.id', '=', 'training_contract_elements.certification_id')
            ->leftjoin('training_contracts', 'training_contracts.id', '=', 'training_contract_elements.training_contract_id')
            ->where('training_contract_id', $trainingContractId)
            ->where('training_contract_elements.main_company_id', $mainCompanyId)
            ->orderBy('order', 'asc')
            ->get();
    }

    public static function getAllTrainingContractElements($mainCompanyId){

        return TrainingContractElement::with('training_contract', 'training_contract.student')
            ->select('training_contract_elements.*', 'certifications.name as certification_name', 'certifications.total_hours as certification_total_hours',
                'training_contracts.number_cfa as cfa',
                'training_actions.formative_action', 'training_actions.name as training_action_name', 'training_actions.total_hours as training_action_total_hours',
            'training_actions.face_to_face_hours as training_action_face_to_face_hours', 'training_actions.teletraining_hours as training_action_teletraining_hours',
            'certifications.face_to_face_hours as certification_face_to_face_hours', 'certifications.teletraining_hours as certification_teletraining_hours')
            ->leftjoin('training_actions', 'training_actions.id', '=', 'training_contract_elements.training_action_id')
            ->leftjoin('certifications', 'certifications.id', '=', 'training_contract_elements.certification_id')
            ->leftjoin('training_contracts', 'training_contracts.id', '=', 'training_contract_elements.training_contract_id')
            ->where('training_contract_elements.main_company_id', $mainCompanyId)
            ->orderBy('order', 'asc')
            ->get();
    }

    public static function getActiveTrainingContractElements($mainCompanyId){

         return TrainingContractElement::with('training_contract', 'training_contract.student')
            ->select('training_contract_elements.*', 'certifications.name as certification_name', 'certifications.total_hours as certification_total_hours',
                'training_contracts.number_cfa as cfa',
                'training_actions.formative_action', 'training_actions.name as training_action_name', 'training_actions.total_hours as training_action_total_hours',
                'training_actions.face_to_face_hours as training_action_face_to_face_hours', 'training_actions.teletraining_hours as training_action_teletraining_hours',
                'certifications.face_to_face_hours as certification_face_to_face_hours', 'certifications.teletraining_hours as certification_teletraining_hours')
            ->leftjoin('training_actions', 'training_actions.id', '=', 'training_contract_elements.training_action_id')
            ->leftjoin('certifications', 'certifications.id', '=', 'training_contract_elements.certification_id')
            ->leftjoin('training_contracts', 'training_contracts.id', '=', 'training_contract_elements.training_contract_id')
            ->leftjoin('training_contract_statuses', 'training_contract_statuses.id', '=', 'training_contracts.training_contract_status_id')
            ->whereNotIn('training_contract_statuses.name', ['BAJA', 'BAJA IT'])
            ->where('training_contract_elements.main_company_id', $mainCompanyId)
            ->orderBy('order', 'asc')
            ->get();
    }

    public static function deleteTrainingContractElement($id, $mainCompanyId){
        $trainingContractElement = TrainingContractElement::where('id', $id)
            ->where('main_company_id', $mainCompanyId)
            ->first();
        $trainingContract = TrainingContract::where('id', $trainingContractElement->training_contract_id)
            ->where('main_company_id', $mainCompanyId)
            ->first();

        $hours = 0;
        if ($trainingContract){
            if ($trainingContractElement){
                if ($trainingContractElement->training_action_id) {
                    $trainingAction = TrainingAction::find($trainingContractElement->training_action_id);
                    $hours = $trainingAction['total_hours'];
                } else if($trainingContractElement->certification_id) {
                    $certification = Certification::find($trainingContractElement->certification_id);
                    $hours = $certification['total_hours'];
                }
                $trainingContractElement->delete();
            }
            $trainingContract->update([
                'formation_hours' => - $hours
            ]);
        }
    }
    public function scopeGetElements($query, $mainCompanyId){
        return $query->select('training_contract_elements.*', 'certifications.name as certification_name', 'certifications.total_hours as certification_total_hours',
            DB::raw("IFNULL(certifications.name, training_actions.name) AS course"), 'training_contracts.number_cfa as cfa',
            'training_actions.formative_action', 'training_actions.name as training_action_name', 'training_actions.total_hours as training_action_total_hours',
            DB::raw("CONCAT(students.name,' ',students.surname) as student"), 'companies.name as company', 'students.dni as dni',
            'training_actions.face_to_face_hours as training_action_face_to_face_hours', 'training_actions.teletraining_hours as training_action_teletraining_hours',
            'certifications.face_to_face_hours as certification_face_to_face_hours', 'certifications.teletraining_hours as certification_teletraining_hours', 'training_actions.course_z as course_z')
            ->leftjoin('training_actions', 'training_actions.id', '=', 'training_contract_elements.training_action_id')
            ->leftjoin('certifications', 'certifications.id', '=', 'training_contract_elements.certification_id')
            ->leftjoin('training_contracts', 'training_contracts.id', '=', 'training_contract_elements.training_contract_id')
            ->leftjoin('students', 'students.id', '=', 'training_contracts.student_id')
            ->leftjoin('companies', 'companies.id', '=', 'training_contracts.company_id')
            ->whereDate('training_contract_elements.beginning', '<=', Carbon::now())
            ->whereDate('training_contract_elements.end', '>=', Carbon::now())
            ->where('training_contract_elements.main_company_id', $mainCompanyId);
    }

    public static function getTrainingContractElement($id, $mainCompanyId){
         return TrainingContractElement::select('training_contract_elements.*', 'certifications.name as certification_name', 'certifications.total_hours as certification_total_hours',
            'training_actions.formative_action', 'training_actions.name as training_action_name', 'training_actions.total_hours as training_action_total_hours', 'training_actions.course_z as course_z')
            ->leftjoin('training_actions', 'training_actions.id', '=', 'training_contract_elements.training_action_id')
            ->leftjoin('certifications', 'certifications.id', '=', 'training_contract_elements.certification_id')
            ->where('training_contract_elements.id', $id)
            ->where('training_contract_elements.main_company_id', $mainCompanyId)
            ->first();
    }

    public static function orderTrainingContractElement($data, $mainCompanyId) {
        $cont = 1;
        foreach ($data as $element) {
            $trainingContractElement = TrainingContractElement::where('id', $element['id'])
                ->where('main_company_id', $mainCompanyId)
                ->first();
            $trainingContractElement->update([
                'order' => $cont
            ]);
            $cont++;
        }
    }

    public function scopeInfo($query, $mainCompanyId) {
        return $query->select('training_contract_elements.*', 'certifications.name as certification_name', 'certifications.total_hours as certification_total_hours',
            'training_contracts.number_cfa as cfa',
            'training_actions.formative_action', 'training_actions.name as training_action_name', 'training_actions.total_hours as training_action_total_hours',
            'training_actions.face_to_face_hours as training_action_face_to_face_hours', 'training_actions.teletraining_hours as training_action_teletraining_hours',
            'certifications.face_to_face_hours as certification_face_to_face_hours', 'certifications.teletraining_hours as certification_teletraining_hours', 'training_actions.course_z as course_z',  'training_actions.course_origin_id')
            ->leftjoin('training_actions', 'training_actions.id', '=', 'training_contract_elements.training_action_id')
            ->leftjoin('certifications', 'certifications.id', '=', 'training_contract_elements.certification_id')
            ->leftjoin('training_contracts', 'training_contracts.id', '=', 'training_contract_elements.training_contract_id')
            ->where('training_contract_elements.main_company_id', $mainCompanyId);
    }

    public function scopeTrainingContracts($query, $trainingContractId, $mainCompanyId){
        return $query->where('training_contract_id', $trainingContractId)
            ->where('training_contract_elements.main_company_id', $mainCompanyId)
            ->orderBy('order', 'asc');
    }

    public function scopeFilterMainCompany($query, $mainCompanyId) {
        return $query->where('training_contract_elements.main_company_id', $mainCompanyId);
    }

    public static function createWithService($data)
    {
        $service = app(TrainingContractElementService::class);
        return $service->create($data);
    }

    public function updateWithService($data){
        $service = app(TrainingContractElementService::class);
        return $service->update($this, $data);
    }

    public function addCourse($data){
        $service = app(TrainingContractElementService::class);
        return $service->addCourse($this, $data);
    }

    public static function createTrainingContractElement($data)
    {
        $service = app(TrainingContractElementService::class);
        return $service->createTrainingContractElement($data);
    }

    public function updateTutorInfo($data){
        $service = app(TrainingContractElementService::class);
        return $service->updateTutorInfo($this, $data);
    }

    public function updateDates($data){
        $service = app(TrainingContractElementService::class);
        return $service->updateDates($this, $data);
    }
}
