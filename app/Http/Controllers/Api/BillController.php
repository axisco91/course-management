<?php

namespace App\Http\Controllers\API;
use App\Models\Bill;
use App\Models\Chore;
use App\Models\Company;
use App\Models\Course;
use App\Models\Student;
use App\Models\TrainingActionLevel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class BillController extends BaseController
{
    public function getBills() {
        try {
            return Bill::getBillings();
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
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
        try {
            $bill = Bill::find($id);
            $request['is_bonus'] = $bill->is_bonus;
            if ($request['is_bonus'] === 1){
                Bill::calculateExpenses($request['billing'], $request['total_training_activity']);
            }
            $bill = Bill::updateBilling($id, $request);

            Chore::billingDateChore($id, $request['billing_date'], $bill['invoiced']);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'billing' => Bill::getBill($bill->id)
        ]);
    }

    public function getBill($id){
        $bill = Bill::getBill($id);
        if ($bill) {
            $course = Course::where('id', $bill->course_id)->first();
            $company = Company::where('id', $bill->company_id)->first();
            $bill['name'] = $course->group.'/'. $course->name .' - '. $company->name .' '.Carbon::parse($course->beginning)->format('d/m/Y') .' - '.Carbon::parse($course->end)->format('d/m/Y');
            return response()->json([
                'status' => 200,
                'billing' => $bill
            ]);
        }
        return response()->json([
            'status' => 400,
            'message' => 'Factura no existe'
        ]);
    }

    public function destroy($id){
        if ($id) {
            try {
                Bill::destroy($id);
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

    public function getBillStudents($id)
    {
        return response()->json(Student::getBilledStudent($id));
    }

    public function count(){
        return Bill::count();
    }

    public function billsCSV(Request $request){
        try {
            if ($request) {
                return Bill::getBillCSV($request['course'], $request['company'], $request['type'], $request['invoiced'], $request['charged']);
            }
            return Bill::getBillCSV();
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }
}
