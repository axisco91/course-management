<?php

namespace App\Http\Controllers\Api;
use App\Exports\TrainingContractBillsExport;
use App\Helpers\GeneralHelpers;
use App\Http\Resources\TrainingContractBillResource;
use App\Models\AdvisorCommission;
use App\Models\AdvisorCommissionType;
use App\Models\CommissionType;
use App\Models\TrainingContractBill;
use App\Models\TrainingContractBonus;
use App\Models\UserCommission;
use App\Models\UserCommissionType;
use App\Services\AdvisorCommissionService;
use App\Services\UserCommissionService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class TrainingContractBillController extends BaseController
{
    private $advisorCommissionService;
    private $userCommissionService;
    public function __construct(AdvisorCommissionService  $advisorCommissionService, UserCommissionService $userCommissionService)
    {
        $this->advisorCommissionService = $advisorCommissionService;
        $this->userCommissionService = $userCommissionService;
    }

    public function index(Request $request)
    {
        try {
            $query = TrainingContractBill::getTrainingContractBill();

            /**
             * -------------------------
             * ✅ FILTROS (initialFilters)
             * student: string (busca en nombre/apellidos si existen)
             * company: string
             * month: int|string
             * invoiced: 0|1
             * paid: 0|1  (en tu UI lo llamas charged; aquí filtro por charged)
             * year: int
             * -------------------------
             */

            // student (busca por name y surname si existe)
            if ($request->filled('student')) {
                $s = trim((string) $request->student);
                $query->where(function ($q) use ($s) {
                    $q->where('students.name', 'like', "%{$s}%");
                    // si tienes surname en students
                    $q->orWhere('students.surname', 'like', "%{$s}%");
                });
            }

            // company
            if ($request->filled('company')) {
                $c = trim((string) $request->company);
                $query->where('companies.name', 'like', "%{$c}%"); // ✅ sin &
            }

            // month
            if ($request->filled('month')) {
                $query->where('training_contract_bills.month', $request->month);
            }

            // year
            if ($request->filled('year')) {
                $query->where('training_contract_bills.year', $request->year);
            }

            // invoiced (0/1)
            if ($request->filled('invoiced')) {
                $query->where('training_contract_bills.invoiced', (int) $request->invoiced);
            }

            // paid => en tabla lo pintas como "charged"
            if ($request->filled('charged')) {
                $query->where('training_contract_bills.charged', (int) $request->charged);
            }

            /**
             * -------------------------
             * ✅ ORDER BY (DataGrid sort)
             * sort: "field" o "-field"
             * -------------------------
             */
            $sortParam = (string) $request->get('sort', '-number'); // default desc
            $dir       = str_starts_with($sortParam, '-') ? 'desc' : 'asc';
            $key       = ltrim($sortParam, '-');

            // whitelist: field => column
            $sortable = [
                // grid fields
                'number'      => 'training_contract_bills.number',        // si existe
                'number_cfa'  => 'training_contract_bills.number_cfa',
                'cfa'         => 'training_contract_bills.cfa',
                'month'       => 'training_contract_bills.month',
                'year'        => 'training_contract_bills.year',
                'invoiced'    => 'training_contract_bills.invoiced',
                'charged'     => 'training_contract_bills.charged',

                // relaciones (ordenar por nombre)
                'student'     => 'students.name',
                'company'     => 'companies.name',
            ];

            if (isset($sortable[$key])) {
                $query->orderBy($sortable[$key], $dir);

                // desempate estable
                $query->orderBy('training_contract_bills.id', 'desc');
            } else {
                // fallback
                $query->orderBy('training_contract_bills.number', 'desc');
            }

            /**
             * ✅ importante si getTrainingContractBill() mete selects raros:
             */

            // -------------------------
            // PAGINACIÓN
            if ($request->filled('perPage')) {
                $perPage   = (int) $request->perPage;
                $paginator = $query->paginate($perPage);

                $trainingContractBills = TrainingContractBillResource::collection($paginator);
                $paginationData        = GeneralHelpers::generatePaginationData($paginator);

                return $this->sendResponse(
                    [
                        'training_contract_bills' => $trainingContractBills,
                        'links'                   => $paginationData['links'],
                        'meta'                    => $paginationData['meta'],
                    ],
                    trans('Obtenido con éxito')
                );
            }

            // SIN PAGINACIÓN
            return $this->sendResponse(
                [
                    'training_contract_bills' => TrainingContractBillResource::collection($query->get()),
                ],
                trans('Obtenido con éxito')
            );
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function store(){
        $bonuses = TrainingContractBonus::bonusesWithNoBills()->get();
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

        return $this->sendResponse(
            [
                'created' => $cont,
            ],
            trans('Obtenido con éxito')
        );
    }

    public function update($id, Request $request){
        try {
            $bill = TrainingContractBill::updateBill($id, $request);

            if (!is_object($bill)) {
                throw new \Exception('Error al actualizar la factura');
            }
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

            $bill = TrainingContractBill::getTrainingContractBill()
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

            return $this->sendResponse(
                [
                    'training_contract_bill' => $bill,
                ],
                trans('Guardado con éxito')
            );
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function show($id){
        $bill = TrainingContractBill::with('company', 'training_contract.student')->find($id);
        if ($bill) {
            $bill['name'] = $bill['number'] . ' - '.$bill->company->name.' - ' . $bill->training_contract->student->name . ' ' . $bill->training_contract->student->surname;
            $bill['company'] = $bill->company->name;
            $bill['student'] = $bill->training_contract->student->name.' '.$bill->training_contract->student->surname;

            return $this->sendResponse(
                [
                    'training_contract_bill' => $bill,
                ],
                trans('Obtenido con éxito')
            );
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

    public function delete($id)
    {
        try {
            $bill = TrainingContractBill::find($id);

            if (!$bill) {
                return response()->json([
                    'status' => 400,
                    'message' => 'Factura no encontrada'
                ]);
            }

            $bill->delete();

            return $this->sendResponse(
                [],
                trans('Factura eliminada con éxito')
            );
        } catch (\Exception $e) {
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function exportExcel(Request $request)
    {
        try {
            $query = TrainingContractBill::getTrainingContractBill();

            // ✅ mismos filtros que index
            if ($request->filled('student')) {
                $s = trim((string) $request->student);
                $query->where(function ($q) use ($s) {
                    $q->where('students.name', 'like', "%{$s}%")
                        ->orWhere('students.surname', 'like', "%{$s}%");
                });
            }

            if ($request->filled('company')) {
                $c = trim((string) $request->company);
                $query->where('companies.name', 'like', "%{$c}%");
            }

            if ($request->filled('month')) {
                $query->where('training_contract_bills.month', $request->month);
            }

            if ($request->filled('year')) {
                $query->where('training_contract_bills.year', $request->year);
            }

            if ($request->filled('invoiced')) {
                $query->where('training_contract_bills.invoiced', (int) $request->invoiced);
            }

            // ✅ en tu UI lo llamas "paid" pero realmente es charged (cobrado)
            if ($request->filled('charged')) {
                $query->where('training_contract_bills.charged', (int) $request->charged);
            }

            // ✅ mismo ORDER BY que index (opcional)
            $sortParam = (string) $request->get('sort', '-number');
            $dir       = str_starts_with($sortParam, '-') ? 'desc' : 'asc';
            $key       = ltrim($sortParam, '-');

            $sortable = [
                'number'      => 'training_contract_bills.number',
                'bill_number' => 'training_contract_bills.number', // si tu grid manda bill_number
                'cfa_number'  => 'training_contract_bills.number_cfa',
                'month'       => 'training_contract_bills.month',
                'year'        => 'training_contract_bills.year',
                'invoiced'    => 'training_contract_bills.invoiced',
                'charged'     => 'training_contract_bills.charged',
                'student'     => 'students.name',
                'company'     => 'companies.name',
            ];

            if (isset($sortable[$key])) {
                $query->orderBy($sortable[$key], $dir)->orderBy('training_contract_bills.id', 'desc');
            } else {
                $query->orderBy('training_contract_bills.number', 'desc');
            }

            // ✅ si tu builder hace joins y selects extraños, puedes asegurar:
            // $query->select('training_contract_bills.*');

            $rows = $query->get();

            $filename = 'facturas_cfa_' . Carbon::now()->format('Y-m-d_H-i-s') . '.xlsx';

            return Excel::download(new TrainingContractBillsExport($rows), $filename);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

}
