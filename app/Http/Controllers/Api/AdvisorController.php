<?php

namespace App\Http\Controllers\API;
use App\Models\Advisor;
use App\Models\Company;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdvisorController extends BaseController
{
    /**
     * Retrive Advisors
     * @return mixed
     */
    public function advisors(Request $request) {
        try {
            return Advisor::getAdvisors();
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e.message
            ]);
        }
    } // end method

    public function getActiveAdvisors(){
        return Advisor::select('advisors.*', 'id as value', 'name as label')
            ->where('active', 1)
            ->get();
    }

    /**
     * Create Advisor
     * @param Request $request
     * @return void
     */
    public function create(Request $request){
        $data = json_decode($request->getContent(), true);
        try {
            if (!$data['company_id']){
                $company = Company::createCompany($data);
                if ($company){
                    $data['company_id'] = $company->id;
                }
            }
            $advisor = Advisor::createAdvisor($data);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'advisor' => $advisor
        ]);
    } // end method

    /**
     * Edit Advisor
     * @param $id
     * @param Request $request
     * @return int
     */
    public function edit($id, Request $request){
        $data = json_decode($request->getContent(), true);
        try {
            $advisor = Advisor::updateAdvisor($id, $data);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'error' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'advisor' => $advisor
        ]);
    }

    /**
     * Get Advisor
     * @param $id
     * @return mixed
     */
    public function getAdvisor($id){
        $advisor = Advisor::find($id);
        if ($advisor) {
            return response()->json([
                'status' => 200,
                'advisor' => $advisor
            ]);
        }
        return response()->json([
            'status' => 400,
            'message' => 'Asesoría no existe'
        ]);
    }

    /**
     * Destroy Advisor
     * @param $id
     * @return int|void
     */
    public function destroy($id){
        if ($id) {
            try {
                Advisor::destroy($id);
                return response()->json([
                    'status' => 200
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 400,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }

    /**
     * Check if NIF exist
     * @param Request $request
     * @return int
     */
    public function checkNif(Request $request){
        if ($request->nif){
            $nif = Advisor::findNif($request->nif);
            if ($nif){
                return 1;
            }
        }
    }

    /**
     * Convert Company to Advisor
     * @param $id
     * @return int
     */
    public function convertAdvisor($id){
        $advisor = Advisor::convertAdvisor($id);
        if ($advisor){
            return 1;
        } else {
            return 0;
        }
    }

    public function getAdvisorCourses($id) {
        return Course::select('courses.*')
            ->leftjoin('registrations', 'registrations.course_id', '=', 'courses.id')
            ->leftjoin('billings', 'billings.id', '=', 'registrations.billing_id')
            ->where('billings.advisor_id', $id)->get();
    }

    public function getAdvisorCompanies($id) {
        return Company::getAdvisorsCompanies($id);
    }

    public function count(){
        return Advisor::count();
    }
}
