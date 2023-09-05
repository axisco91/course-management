<?php

namespace App\Http\Controllers\Api;

use App\Models\AdvisorCommission;
use App\Services\AdvisorCommissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdvisorCommissionController extends BaseController
{
    private $advisorCommissionService;

    public function __construct(AdvisorCommissionService $advisorCommissionService)
    {
        $this->advisorCommissionService = $advisorCommissionService;
    }

    /**
     * Obtener comisiones
     * @return mixed
     */
    public function index($id) {
        try {
            $commissions = AdvisorCommission::select(
                'advisor_commissions.*',
                'commission_types.id as commission_type_id',
                'commission_types.name as commission_type',
                DB::raw("IFNULL(courses.name, training_contracts.number_cfa) AS name"),
                DB::raw("IFNULL(training_contracts.number_cfa, CONCAT(training_actions.formative_action,' / ', courses.group, ' ', training_actions.name)) AS name"),
            )
                ->leftJoin('commission_types', 'commission_types.id', '=', 'advisor_commissions.commission_type_id')
                ->leftJoin('courses', 'courses.id', '=', 'advisor_commissions.course_id')
                ->leftJoin('training_contracts', 'training_contracts.id', '=', 'advisor_commissions.training_contract_id')
                ->leftJoin('training_actions', 'training_actions.id', '=', 'courses.training_action_id')
                ->where('advisor_commissions.advisor_id', $id)
                ->get();

            return $commissions;
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    } // end method

    /**
     * Editar comisión
     * @param $id
     * @param Request $request
     * @return int
     */
    public function update($id, Request $request){
        try {
            $data = $request->all();
            $advisorCommission = AdvisorCommission::find($id);
            $this->advisorCommissionService->update($advisorCommission, $data);

            $commission = AdvisorCommission::select(
                'advisor_commissions.*',
                'commission_types.id as commission_type_id',
                'commission_types.name as commission_type',
                DB::raw("IFNULL(courses.name, CONCAT(training_actions.formative_action,' / ', courses.group, ' ', training_actions.name)) AS course_name"),
                DB::raw('IFNULL(training_contracts.number_cfa, training_contracts.number_cfa) AS contract_name')
            )
                ->leftJoin('commission_types', 'commission_types.id', '=', 'advisor_commissions.commission_type_id')
                ->leftJoin('courses', 'courses.id', '=', 'advisor_commissions.course_id')
                ->leftJoin('training_contracts', 'training_contracts.id', '=', 'advisor_commissions.training_contract_id')
                ->leftJoin('training_actions', 'training_actions.id', '=', 'courses.training_action_id')
                ->where('advisor_commissions.id', $id)
                ->first();

            return response()->json([
                'status' => 200,
                'advisor_commission' => $commission
            ]);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Obtener comisión
     * @param $id
     * @return mixed
     */
    public function show($id){
        $advisorCommission = AdvisorCommission::select(
            'advisor_commissions.*',
            'commission_types.id as commission_type_id',
            'commission_types.name as commission_type',
            DB::raw("IFNULL(courses.name, CONCAT(training_actions.formative_action,' / ', courses.group, ' ', training_actions.name)) AS course_name"),
            DB::raw('IFNULL(training_contracts.number_cfa, training_contracts.number_cfa) AS contract_name')
        )
            ->leftJoin('commission_types', 'commission_types.id', '=', 'advisor_commissions.commission_type_id')
            ->leftJoin('courses', 'courses.id', '=', 'advisor_commissions.course_id')
            ->leftJoin('training_contracts', 'training_contracts.id', '=', 'advisor_commissions.training_contract_id')
            ->leftJoin('training_actions', 'training_actions.id', '=', 'courses.training_action_id')
            ->where('advisor_commissions.id', $id)
            ->first();
        if ($advisorCommission) {
            return response()->json([
                'status' => 200,
                'advisor_commission' => $advisorCommission
            ]);
        }
        return response()->json([
            'status' => 400,
            'message' => 'Comisión no existe'
        ]);
    }

    /**
     * Eliminar comisión
     * @param $id
     * @return int|void
     */
    public function destroy($id){
        if ($id) {
            try {
                AdvisorCommission::destroy($id);
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
