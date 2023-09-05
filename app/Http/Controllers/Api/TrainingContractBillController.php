<?php

namespace App\Http\Controllers\Api;
use App\Models\AdvisorCommission;
use App\Models\CommissionType;
use App\Models\TrainingContractBill;
use App\Models\TrainingContractBonus;
use App\Services\AdvisorCommissionService;
use Illuminate\Http\Request;

class TrainingContractBillController extends BaseController
{
    private $advisorCommissionService;
    public function __construct(AdvisorCommissionService  $advisorCommissionService)
    {
        $this->advisorCommissionService = $advisorCommissionService;
    }

    public function index() {
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
            $bill = TrainingContractBill::createBill($bonus);
            if ($bonus->advisor_id) {
                $advisorCommission = AdvisorCommission::where('commissionable_id', $bill->id)
                    ->where('commissionable_type', 'App\Models\TrainingContractBill')
                    ->where('advisor_id', $bill->advisor_id)
                    ->first();

                // Buscamos el tipo de los bonificados
                $commissionType = CommissionType::where('name', 'CFA')
                    ->first();
                if ($commissionType) {
                    $commissionData = [
                        'advisor_id' => $bonus->advisor_id,
                        'training_contract_id' => $bill['training_contract_id'],
                        'commissionable_id' => $bill->id,
                        'commissionable_type' => 'App\Models\TrainingContractBill',
                        'commission_type_id' => $commissionType->id,
                        'percentage' => $commissionType->percentage,
                        'amount' => ($commissionType->percentage / 100) * $bill->amount,
                        'bill_amount' => $bill->amount
                    ];
                    if ($advisorCommission) {
                        $this->advisorCommissionService->update($advisorCommission, $commissionData);
                    } else {
                        $this->advisorCommissionService->create($commissionData);
                    }
                }
            }
            $cont++;
        }

        return response()->json([
            'status' => 200,
            'created' => $cont
        ]);
    }

    public function update($id, Request $request){
        try {
            $bill = TrainingContractBill::updateBill($id, $request);
            if ($bill->advisor_id) {
                $advisorCommission = AdvisorCommission::where('commissionable_id', $bill->id)
                    ->where('commissionable_type', 'App\Models\TrainingContractBill')
                    ->where('advisor_id', $bill->advisor_id)
                    ->first();

                // Buscamos el tipo de los bonificados
                $commissionType = CommissionType::where('name', 'Bonificado')
                    ->first();
                if ($commissionType) {
                    $commissionData = [
                        'advisor_id' => $bill->advisor_id,
                        'training_contract_id' => $bill['training_contract_id'],
                        'commissionable_id' => $bill->id,
                        'commissionable_type' => 'App\Models\TrainingContractBill',
                        'commission_type_id' => $commissionType->id,
                        'percentage' => $commissionType->percentage,
                        'amount' => ($commissionType->percentage / 100) * $bill->billing,
                        'bill_amount' => $bill->billing
                    ];
                    if ($advisorCommission) {
                        $this->advisorCommissionService->update($advisorCommission, $commissionData);
                    } else {
                        $this->advisorCommissionService->create($commissionData);
                    }
                }
            }
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

    public function show($id){
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
