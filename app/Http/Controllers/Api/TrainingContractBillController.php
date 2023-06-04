<?php

namespace App\Http\Controllers\API;
use App\Models\Company;
use App\Models\Student;
use App\Models\TrainingContractBill;
use App\Models\TrainingContractBonus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class TrainingContractBillController extends BaseController
{
    public function getBills() {
        try {
            return TrainingContractBill::getTrainingContractBills();
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function create(){
        $bonuses = TrainingContractBonus::bonusesWithNoBills();
        $cont = 0;
        foreach($bonuses as $bonus) {
            TrainingContractBill::createBill($bonus);
            $cont++;
        }

        return response()->json([
            'status' => 200,
            'created' => $cont
        ]);
    }

    public function edit($id, Request $request){
        try {
            $bill = TrainingContractBill::updateBill($id, $request);

            return response()->json([
                'status' => 200,
                'training_contract_bill' => TrainingContractBill::getTrainingContractBill($id)
            ]);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function getBill($id){
        $bill = TrainingContractBill::getTrainingContractBill($id);
        if ($bill) {
            $bill['name'] = $bill['number'].' - '.$bill['student'];
            return response()->json([
                'status' => 200,
                'training_contract_bill' => $bill
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

    public function years() {
        $years = TrainingContractBill::select('year as value', 'year as label')->groupBy('year')->get();

        return $years;
    }

    public function billsCSV(Request $request){
        try {
            if ($request) {
                return TrainingContractBill::getBillCSV($request['student'], $request['company'], $request['month'], $request['year'], $request['invoiced'], $request['charged']);
            }
            return TrainingContractBill::getBillCSV();
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }
}
