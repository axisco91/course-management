<?php

namespace App\Http\Controllers\Api;
use App\Helpers\GeneralHelpers;
use App\Models\ExamTutorial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExamTutorialController extends BaseController
{

    public function getExamTutorial($id, Request $request) {
        if ($id) {
            try {
               $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

                return  ExamTutorial::getExamTutorials($id, $mainCompanyId);
            } catch (\Exception $e) {
                return response()->json([
                    'message' => $e->getMessage()
                ]);
            }
        }
    }

    public function create(Request $request){
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            $data = $request->all();
            $data['main_company_id'] = $mainCompanyId;

            $examTutorial = ExamTutorial::createWithService($data);

            return response()->json([
                'status' => 200,
                'exam_tutorial' => ExamTutorial::getExamTutorial($examTutorial->id, $mainCompanyId),
            ]);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function edit($id, Request $request){
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            $examTutorial = ExamTutorial::where('id', $id)
                ->FilterMainCompany($mainCompanyId)
                ->first();

            if (!$examTutorial) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Examen o Tutoría no encontrado'
                ]);
            }

            $examTutorial->updateWithService($id, $request);

            return response()->json([
                'status' => 200,
                'exam_tutorial' => ExamTutorial::getExamTutorial($id, $mainCompanyId),
            ]);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function destroy($id, Request $request){
        if ($id) {
            try {
               $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

                $examTutorial = ExamTutorial::where('id', $id)
                    ->FilterMainCompany($mainCompanyId)
                    ->first();

                if (!$examTutorial) {
                    return response()->json([
                        'status' => 404,
                        'message' => 'Examen o Tutoría no encontrado'
                    ]);
                }

                ExamTutorial::destroy($id);
                return response()->json([
                    'status' => 200
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 400,
                    'message' => $e->getMessage()
                ]);
            }
        }
    }
}
