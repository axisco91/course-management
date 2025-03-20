<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ExamTutorial extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'exams_tutorials';
    protected $fillable = ['training_contract_id', 'center_id', 'type', 'date', 'beginning', 'end', 'training_action_id'];

    public function trainingAction()
    {
        return $this->belongsTo(TrainingAction::class);
    }

    public static function getExamTutorials($trainingContractId){
        $exam_tutorials = ExamTutorial::select(
            'exams_tutorials.id',
            'exams_tutorials.*',
            'centers.name as center',
            DB::raw("CONCAT(training_actions.formative_action,' / ', MAX(courses.group), ' ', training_actions.name) as label"),
            DB::raw("CONCAT(training_actions.formative_action,' - ', training_actions.name) as training_action"))
            ->join('centers', 'centers.id', '=', 'exams_tutorials.center_id')
            ->join('training_actions', 'training_actions.id', '=', 'exams_tutorials.training_action_id')
            ->join('courses', 'courses.training_action_id', '=', 'training_actions.id')
            ->where('training_contract_id', $trainingContractId)
            ->groupBy('exams_tutorials.id', 'centers.name', 'training_actions.formative_action', 'training_actions.name')
            ->get();


        return $exam_tutorials;
    }

    public static function getExamTutorial($id){
        $exam_tutorials = ExamTutorial::select('exams_tutorials.*', 'centers.name as center')
            ->leftjoin('centers', 'centers.id', '=', 'exams_tutorials.center_id')
            ->where('exams_tutorials.id', $id)->first();
        return $exam_tutorials;
    }

    public static function createExamsTutorial($data){

        return ExamTutorial::create([
            'training_contract_id' => $data['training_contract_id'],
            'center_id' => $data['center_id'],
            'type' => $data['type'],
            'date' => Carbon::createFromFormat('d-m-Y',$data['date'])->toDateString(),
            'beginning' => Carbon::createFromFormat('H:i:s', $data['beginning'].':00')->toTimeString(),
            'end' => $data['end'] ? Carbon::createFromFormat('H:i:s', $data['end'].':00')->toTimeString() : '',
            'training_action_id' => $data['training_action_id'],
        ]);;
    }

    public static function updateExamsTutorial($id, $data){
        $exam_tutorial = ExamTutorial::find($id);
        $exam_tutorial = $exam_tutorial->update([
            'training_contract_id' => $data['training_contract_id'],
            'center_id' => $data['center_id'],
            'type' => $data['type'],
            'date' => Carbon::createFromFormat('d-m-Y',$data['date'])->toDateString(),
            'beginning' => $data['beginning'],
            'end' => $data['end'],
            'training_action_id' => $data['training_action_id'],
        ]);;
        return $exam_tutorial;
    }
}
