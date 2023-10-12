<?php

namespace App\Http\Controllers\Api;
use App\Models\AdvisorCommission;
use App\Models\AdvisorCommissionType;
use App\Models\Bill;
use App\Models\CommissionType;
use App\Models\TrainingContractBill;
use App\Models\TrainingContractBonus;
use App\Models\UserCommission;
use App\Models\UserCommissionType;
use App\Services\AdvisorCommissionService;
use App\Services\UserCommissionService;
use Illuminate\Http\Request;

class TrainingContractBillController extends BaseController
{
    private $advisorCommissionService;
    private $userCommissionService;
    public function __construct(AdvisorCommissionService  $advisorCommissionService, UserCommissionService $userCommissionService)
    {
        $this->advisorCommissionService = $advisorCommissionService;
        $this->userCommissionService = $userCommissionService;
    }

    public function index(Request $request) {
        try {
            $bills = TrainingContractBill::getTrainingContractBills();

            if ($request->student) {
                $bills = $bills->where('students.name', 'like', '%'.$request->student.'%');
            }
            if ($request->company) {
                $bills = $bills->where('companies.name', 'like', '%'.$request->company.'&');
            }
            $bills = $bills->orderBy('training_contract_bills.number', 'desc')->get();

            foreach ($bills as $bill) {
                switch ($bill['month']) {
                    case 1:
                        $bill['month_name'] = 'Enero';
                        break;
                    case 2:
                        $bill['month_name'] = 'Febrero';
                        break;
                    case 3:
                        $bill['month_name'] = 'Marzo';
                        break;
                    case 4:
                        $bill['month_name'] = 'Abril';
                        break;
                    case 5:
                        $bill['month_name'] = 'Mayo';
                        break;
                    case 6:
                        $bill['month_name'] = 'Junio';
                        break;
                    case 7:
                        $bill['month_name'] = 'Julio';
                        break;
                    case 8:
                        $bill['month_name'] = 'Agosto';
                        break;
                    case 9:
                        $bill['month_name'] = 'Septiembre';
                        break;
                    case 10:
                        $bill['month_name'] = 'Octubre';
                        break;
                    case 11:
                        $bill['month_name'] = 'Noviembre';
                        break;
                    case 12:
                        $bill['month_name'] = 'Diciembre';
                        break;
                }
            }

            return $bills;
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function store(){
        $bonuses = TrainingContractBonus::bonusesWithNoBills();
        $cont = 0;
        foreach($bonuses as $bonus) {
            if ($bonus->amount != 0) {
                $bill = TrainingContractBill::createBill($bonus);
                // Buscamos el tipo de los bonificados
                $commissionType = CommissionType::where('name', 'CFA')
                    ->first();
                if ($commissionType) {
                    $percentage = $commissionType->percentage;
                    if ($bonus->advisor_id) {
                        $advisorCommission = AdvisorCommission::where('commissionable_id', $bill->id)
                            ->where('commissionable_type', 'App\Models\TrainingContractBill')
                            ->where('advisor_id', $bonus->advisor_id)
                            ->first();
                        $advisorCommissionType = AdvisorCommissionType::where('advisor_id', $bill->advisor_id)
                            ->where('commission_type_id', $commissionType->id)->first();
                        if ($advisorCommissionType) {
                            $percentage = $advisorCommissionType->percentage;
                        }
                        $commissionData = [
                            'advisor_id' => $bonus->advisor_id,
                            'training_contract_id' => $bill['training_contract_id'],
                            'commissionable_id' => $bill->id,
                            'commissionable_type' => 'App\Models\TrainingContractBill',
                            'commission_type_id' => $commissionType->id,
                            'percentage' => $percentage,
                            'amount' => ($percentage / 100) * $bill->amount,
                            'bill_amount' => $bill->amount
                        ];
                        if ($advisorCommission) {
                            $this->advisorCommissionService->update($advisorCommission, $commissionData);
                        } else {
                            $this->advisorCommissionService->create($commissionData);
                        }
                    }
                    if ($bonus->collaborator_id) {
                        $percentage = $commissionType->percentage;
                        $userCommission = UserCommission::where('commissionable_id', $bill->id)
                            ->where('commissionable_type', 'App\Models\Bill')
                            ->where('user_id', $bonus->user_id)
                            ->first();
                        $userCommissionType = UserCommissionType::where('user_id', $bonus->collaborator_id)
                            ->where('commission_type_id', $commissionType->id)->first();
                        if ($userCommissionType) {
                            $percentage = $userCommissionType->percentage;
                        }
                        $commissionData = [
                            'user_id' => $bonus->collaborator_id,
                            'training_contract_id' => $bill['training_contract_id'],
                            'commissionable_id' => $bill->id,
                            'commissionable_type' => 'App\Models\TrainingContractBill',
                            'commission_type_id' => $commissionType->id,
                            'percentage' => $percentage,
                            'amount' => ($percentage / 100) * $bill->billing,
                            'bill_amount' => $bill->billing
                        ];
                        if ($userCommission) {
                            $this->userCommissionService->update($userCommission, $commissionData);
                        } else {
                            $this->userCommissionService->create($commissionData);
                        }
                    }
                }
                $cont++;
            }
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

            $bill = TrainingContractBill::getTrainingContractBills()
                ->where('training_contract_bills.id', $id)->first();

            switch ($bill['month']) {
                case 1:
                    $bill['month_name'] = 'Enero';
                    break;
                case 2:
                    $bill['month_name'] = 'Febrero';
                    break;
                case 3:
                    $bill['month_name'] = 'Marzo';
                    break;
                case 4:
                    $bill['month_name'] = 'Abril';
                    break;
                case 5:
                    $bill['month_name'] = 'Mayo';
                    break;
                case 6:
                    $bill['month_name'] = 'Junio';
                    break;
                case 7:
                    $bill['month_name'] = 'Julio';
                    break;
                case 8:
                    $bill['month_name'] = 'Agosto';
                    break;
                case 9:
                    $bill['month_name'] = 'Septiembre';
                    break;
                case 10:
                    $bill['month_name'] = 'Octubre';
                    break;
                case 11:
                    $bill['month_name'] = 'Noviembre';
                    break;
                case 12:
                    $bill['month_name'] = 'Diciembre';
                    break;
            }

            return response()->json([
                'status' => 200,
                'training_contract_bill' => $bill
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
}
