<?php

namespace App\Http\Controllers\Api;
use App\Helpers\GeneralHelpers;
use App\Models\AdvisorIncidence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdvisorIncidenceController extends BaseController
{
    /**
     * Get all incidences for a specific advisor
     * @param $advisor_id
     * @return \Illuminate\Http\Response
     */
    public function getIncidencesForAdvisor($advisor_id, Request $request) {

       $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
        $incidences = AdvisorIncidence::where('advisor_id', $advisor_id)
            ->where('main_company_id', $mainCompanyId)
            ->get();

        return response()->json($incidences);
    }
    /**
     * Create Advisor Incidences
     * @param Request $request
     * @return mixed
     */
    public function create(Request $request){
       $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

        $request->validate([
            'affair' => 'required',
            'advisor_id' => 'required',
            'user_id' => 'required',
            'incidence_type_id' => 'required'
        ]);
        $data = [
            'advisor_id' => $request->advisor_id,
            'affair' => $request->affair,
            'notes' => $request->notes,
            'user_id' => $request->user_id,
            'incidence_type_id' => $request->incidence_type_id,
            'main_company_id' => $mainCompanyId
        ];

        return AdvisorIncidence::createWithService($data);
    }

    /**
     * Edtir Advisor Incidence
     * @param $id
     * @param Request $request
     * @return int
     */
    public function edit($id, Request $request){
        $request->validate([
            'affair' => 'required',
            'advisor_id' => 'required',
            'user_id' => 'required',
            'incidence_type_id' => 'required'
        ]);
        $data = [
            'advisor_id' => $request->advisor_id,
            'affair' => $request->affair,
            'notes' => $request->notes,
            'user_id' => $request->user_id,
            'incidence_type_id' => $request->incidence_type_id,
        ];
        $advisor_incidence = AdvisorIncidence::updateAdvisorIncidence($id, $data);
        if ($advisor_incidence){
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * Get Advisor Incidence
     * @param $id
     * @return mixed
     */
    public function getTrainingActionLevel($id){
        return AdvisorIncidence::find($id);
    }

    /**
     * Destroy Advisor Incidence
     * @param $id
     * @return int|void
     */
    public function destroy($id){
        if ($id) {
            AdvisorIncidence::destroy($id);
            return 1;
        }
    }
}
