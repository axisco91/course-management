<?php

namespace App\Models;

use App\Services\CertificationElementService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CertificationElement extends Model
{
	use HasFactory;

    protected $fillable = ['certification_id', 'training_unit_id', 'module_id', 'main_company_id'];

    public function scopeFilterMainCompany($query, $mainCompanyId) {
        return $query->where('certification_elements.main_company_id', $mainCompanyId);
    }

    public function scopeGetCertificationElements($query, $certificationId, $mainCompanyId) {
        return  $query->select('certification_elements.*', DB::raw('IFNULL(training_units.formative_unit, modules.formative_module) AS formative_action'),DB::raw('IFNULL(training_units.name, modules.name) AS element'), 'training_units.name as training_unit_name',
            'training_units.exam_hours as training_unit_exam_hours', 'modules.exam_hours as module_exam_hours',
            'training_units.tutoring_hours as training_unit_tutoring_hours', 'modules.tutoring_hours as module_tutoring_hours',
            'training_units.face_to_face_hours as training_unit.face_to_face_hours', 'modules.face_to_face_hours as module_face_to_face_hours',
            'training_units.teletraining_hours as training_unit_teletraining_hours', 'modules.teletraining_hours as module_teletraining_hours',
            'training_units.total_hours as training_unit.total_hours', 'modules.total_hours as module_total_hours',
            'training_units.formative_unit', 'modules.name as module_name', 'modules.formative_module', 'certification_elements.id as value')
            ->leftjoin('training_units', 'training_units.id', '=', 'certification_elements.training_unit_id')
            ->leftjoin('modules', 'modules.id', '=', 'certification_elements.module_id')
            ->where('certification_elements.certification_id', $certificationId)
            ->where('certification_elements.main_company_id', $mainCompanyId);
    }

    public function scopeGetCertificationElement($query, $id, $mainCompanyId) {
        return $query->select('certification_elements.*', DB::raw('IFNULL(training_units.formative_unit, modules.formative_module) AS formative_action'),DB::raw('IFNULL(training_units.name, modules.name) AS element'), 'training_units.name as training_unit_name',
            'training_units.exam_hours as training_unit_exam_hours', 'modules.exam_hours as module_exam_hours',
            'training_units.tutoring_hours as training_unit_tutoring_hours', 'modules.tutoring_hours as module_tutoring_hours',
            'training_units.face_to_face_hours as training_unit.face_to_face_hours', 'modules.face_to_face_hours as module_face_to_face_hours',
            'training_units.teletraining_hours as training_unit_teletraining_hours', 'modules.teletraining_hours as module_teletraining_hours',
            'training_units.total_hours as training_unit.total_hours', 'modules.total_hours as module_total_hours',
            'training_units.formative_unit', 'modules.name as module_name', 'modules.formative_module', 'certification_elements.id as value')
            ->leftjoin('training_units', 'training_units.id', '=', 'certification_elements.training_unit_id')
            ->leftjoin('modules', 'modules.id', '=', 'certification_elements.module_id')
            ->where('certification_elements.id', $id)
            ->where('certification_elements.main_company_id', $mainCompanyId);
    }

    public static function createCertificationElement($certification_id, $elementId, $type, $mainCompanyId) {
        $service = app(CertificationElementService::class);
        return $service->create($certification_id, $elementId, $type, $mainCompanyId);
    }

    public static function deleteCertificationElement($id, $mainCompanyId) {
        $service = app(CertificationElementService::class);
        return $service->delete($id, $mainCompanyId);
    }
}
