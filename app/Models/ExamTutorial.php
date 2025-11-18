<?php

namespace App\Models;

use App\Services\ExamTutorialService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ExamTutorial extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'exams_tutorials';
    protected $fillable = ['training_contract_id', 'center_id', 'type', 'date', 'beginning', 'end', 'training_action_id', 'main_company_id'];

    public function trainingAction()
    {
        return $this->belongsTo(TrainingAction::class);
    }

    public static function getExamTutorials($trainingContractId, $mainCompanyId){
        return ExamTutorial::select(
            'exams_tutorials.id',
            'exams_tutorials.*',
            'centers.name as center',
            DB::raw("CONCAT(training_actions.formative_action,' / ', MAX(courses.group), ' ', training_actions.name) as label"),
            DB::raw("CONCAT(training_actions.formative_action,' - ', training_actions.name) as training_action"))
            ->join('centers', 'centers.id', '=', 'exams_tutorials.center_id')
            ->join('training_actions', 'training_actions.id', '=', 'exams_tutorials.training_action_id')
            ->join('courses', 'courses.training_action_id', '=', 'training_actions.id')
            ->where('training_contract_id', $trainingContractId)
            ->where('exams_tutorials.main_company_id', $mainCompanyId)
            ->groupBy('exams_tutorials.id', 'centers.name', 'training_actions.formative_action', 'training_actions.name')
            ->get();
    }

    public static function getExamTutorial($id, $mainCompanyId){
        return ExamTutorial::select('exams_tutorials.*', 'centers.name as center')
            ->leftjoin('centers', 'centers.id', '=', 'exams_tutorials.center_id')
            ->where('exams_tutorials.id', $id)
            ->where('exams_tutorials.main_company_id', $mainCompanyId)
            ->first();
    }

    public function scopeFilterMainCompany($query, $mainCompanyId) {
        return $query->where('exams_tutorials.main_company_id', $mainCompanyId);
    }

    public static function createWithService($data)
    {
        $service = app(ExamTutorialService::class);
        return $service->create($data);
    }

    public function updateWithService($data){
        $service = app(ExamTutorialService::class);
        return $service->update($this, $data);
    }
}
