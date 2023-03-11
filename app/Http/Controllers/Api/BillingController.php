<?php

namespace App\Http\Controllers\API;
use App\Models\Billing;
use App\Models\Chore;
use App\Models\Course;
use App\Models\Student;
use App\Models\TrainingActionLevel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class BillingController extends BaseController
{
    public function getBillings() {
        try {
            return Billing::getBillings();
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }
    }

    public function create(Request $request){
        $data = [
            'name' => $request->name
        ];

        return TrainingActionLevel::createTrainingActionLevel();;
    }

    public function edit($id, Request $request){
        $data = json_decode($request->getContent(), true);
        try {
            $billing = Billing::find($id);
            $data['is_bonus'] = $billing->is_bonus;
            if ($data['is_bonus'] === 1){
                $expenses = Billing::calculateExpenses($data['billing'], $data['total_training_activity']);
            }
            $billing = Billing::updateBilling($id, $data);

            //    Chore::billingDateChore($id, $data['billing_date'], $billing['invoiced']);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'error' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'billing' => $billing
        ]);
    }

    public function getBilling($id){
        $billing = Billing::find($id);
        if ($billing) {
            $course = Course::where('id', $billing->course_id)->first();
            $student = Student::where('id', $billing->student_id)->first();
            $billing['name'] = $course->group.'/'. $course->name .' - '. $student->name .' '.Carbon::parse($course->beginning)->format('d/m/Y') .' - '.Carbon::parse($course->end)->format('d/m/Y');
            return response()->json([
                'status' => 200,
                'billing' => $billing
            ]);
        }
        return response()->json([
            'status' => 400,
            'message' => 'Factura no existe'
        ]);
    }

    public function destroy($id){
        if ($id) {
            TrainingActionLevel::destroy($id);
            return 1;
        }
    }

    public function getBillingStudents($id)
    {
        return response()->json(Student::getBilledStudent($id));
    }
}
