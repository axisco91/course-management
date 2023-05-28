<?php

namespace App\Http\Controllers\API;
use App\Models\Billing;
use App\Models\Chore;
use App\Models\Company;
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
            $bill = Billing::find($id);
            $request['is_bonus'] = $bill->is_bonus;
            if ($request['is_bonus'] === 1){
                Billing::calculateExpenses($request['billing'], $request['total_training_activity']);
            }
            $bill = Billing::updateBilling($id, $request);

            Chore::billingDateChore($id, $request['billing_date'], $bill['invoiced']);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'billing' => Billing::getBill($bill->id)
        ]);
    }

    public function getBilling($id){
        $bill = Billing::getBill($id);
        if ($bill) {
            $course = Course::where('id', $bill->course_id)->first();
            $company = Company::where('id', $bill->company_id)->first();
            $billing['name'] = $course->group.'/'. $course->name .' - '. $company->name .' '.Carbon::parse($course->beginning)->format('d/m/Y') .' - '.Carbon::parse($course->end)->format('d/m/Y');
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
                Billing::destroy($id);
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

    public function getBillingStudents($id)
    {
        return response()->json(Student::getBilledStudent($id));
    }

    public function count(){
        return Billing::count();
    }
}
