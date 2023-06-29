<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ExamTutorial extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'exams_tutorials';
    protected $fillable = ['training_contract_id', 'center_id', 'type', 'date', 'beginning', 'end'];

    public static function getExamTutorials($training_contract_id){
        $exam_tutorials = ExamTutorial::select('exams_tutorials.*', 'centers.name as center')
            ->leftjoin('centers', 'centers.id', '=', 'exams_tutorials.center_id')
            ->where('training_contract_id', $training_contract_id)->get();
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
            'end' => $data['end'] ? Carbon::createFromFormat('H:i:s', $data['end'].':00')->toTimeString() : ''
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
            'end' => $data['end']
        ]);;
        return $exam_tutorial;
    }
}
