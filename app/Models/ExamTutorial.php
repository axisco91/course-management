<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

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

    public static function createExamsTutorial($data){
        return ExamTutorial::create($data);
    }
}
