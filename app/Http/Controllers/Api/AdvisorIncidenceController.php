<?php

namespace App\Http\Controllers\Api;
use App\Models\AdvisorIncidence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdvisorIncidenceController extends BaseController
{
    /**
     * Get Advisor Incidences
     * @return mixed
     */
    public function getAdvisorIncidences(Request $request) {
        return AdvisorIncidence::getAdvisorIncidences($request->advisor_id);
    }
    /**
     * Get all incidences for a specific advisor
     * @param $advisor_id
     * @return \Illuminate\Http\Response
     */
    public function getIncidencesForAdvisor($advisor_id) {
        $incidences = AdvisorIncidence::where('advisor_id', $advisor_id)->get();

        return response()->json($incidences);
    }
    /**
     * Create Advisor Incidences
     * @param Request $request
     * @return mixed
     */
    public function create(Request $request){
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

        return AdvisorIncidence::createAdvisorIncidence($data);
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
        return TrainingActionLevel::find($id);
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
