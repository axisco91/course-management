<?php

namespace App\Http\Controllers\API;
use App\Models\ExamTutorial;
use Illuminate\Http\Request;

class ExamTutorialController extends BaseController
{

    public function getExamTutorial($id) {
        if ($id) {
            try {
                return  ExamTutorial::getExamTutorials($id);
            } catch (\Exception $e) {
                return response()->json([
                    'error' => $e->getMessage()
                ]);
            }
        }
    }

    public function create(Request $request){
        $data = json_decode($request->getContent(), true);
        try {
            $exam_tutorial = ExamTutorial::createExamsTutorial($data);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'error' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'exam_tutorial' => $exam_tutorial,
        ]);
    }

    public function update($id, Request $request){
        $data = json_decode($request->getContent(), true);
        try {
            $exam_tutorial = ExamTutorial::updateExamsTutorial($id, $data);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'error' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'exam_tutorial' => $exam_tutorial,
        ]);
    }

    public function destroy($id){
        if ($id) {
            try {
                ExamTutorial::destroy($id);
                return response()->json([
                    'status' => 200
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 400,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }

}
