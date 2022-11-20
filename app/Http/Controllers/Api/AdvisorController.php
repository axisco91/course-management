<?php

namespace App\Http\Controllers\API;
use App\Models\Advisor;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdvisorController extends BaseController
{
    /**
     * Retrive Advisors
     * @return mixed
     */
    public function getAdvisors(Request $request) {
        return Advisor::getAdvisors();
    } // end method

    /**
     * Create Advisor
     * @param Request $request
     * @return void
     */
    public function create(Request $request){
        $request->validate([
            'name' => 'required',
            'type_id' => 'required',
            'activity_id' => 'required',
            'province_id' => 'required',
        ]);
        $data = [
            'name' => $request->name,
            'company_id' => $request->company_id,
            'irpf' => $request->irpf,
            'commission' => $request->commission,
            'contact_1' => $request->contact_1,
            'contact_2' => $request->contact_2,
            'contact_3' => $request->contact_3,
            'nif' => $request->nif,
            'company_type_id' => $request->type_id,
            'company_activity_id' => $request->activity_id,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'legal_representative' => $request->legal_representative,
            'dni_legal_representative' => $request->dni_legal_representative,
            'quote' => $request->quote,
            'cnae_id' => $request->cnae_id,
            'average_template' => $request->average_template,
            'iban' => $request->iban,
            'sepa' => $request->sepa,
            'b2b' => $request->b2b,
            'address' => $request->address,
            'post_code' => $request->post_code,
            'province_id' => $request->province_id,
            'population' => $request->population,
            'active' => $request->active == true ? 1 : 0,
            'advisor_id' => $request->advisor_id != -1 ? $request->advisor_id : null,
            'collaborator_id' => $request->collaborator_id != -1 ? $request->collaborator_id : null
        ];
        if (!$request->request->has('company-id')){
            $company = Company::createCompany($data);
            if ($company){
                $data['company_id'] = $company->id;
            }
        }
        $advisor = Advisor::createAdvisor($data);
        if ($advisor){
            return 1;
        } else{
            return 0;
        }
    } // end method

    /**
     * Edit Advisor
     * @param $id
     * @param Request $request
     * @return int
     */
    public function edit($id, Request $request){
        $data = [
            'name' => $request->name,
            'company_id' => $request->company_id,
            'irpf' => $request->irpf,
            'commission' => $request->commission,
            'contact_1' => $request->contact_1,
            'contact_2' => $request->contact_2,
            'contact_3' => $request->contact_3,
            'nif' => $request->nif,
            'company_type_id' => $request->type_id,
            'company_activity_id' => $request->activity_id,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'legal_representative' => $request->legal_representative,
            'dni_legal_representative' => $request->dni_legal_representative,
            'quote' => $request->quote,
            'cnae_id' => $request->cnae_id,
            'average_template' => $request->average_template,
            'iban' => $request->iban,
            'sepa' => $request->sepa,
            'b2b' => $request->b2b,
            'address' => $request->address,
            'post_code' => $request->post_code,
            'province_id' => $request->province_id,
            'population' => $request->population,
            'active' => $request->active == true ? 1 : 0,
            'advisor_id' => $request->advisor_id != -1 ? $request->advisor_id : null,
            'collaborator_id' => $request->collaborator_id != -1 ? $request->collaborator_id : null
        ];
        $advisor = Advisor::updateAdvisor($id, $data);
        if ($advisor){
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * Get Advisor
     * @param $id
     * @return mixed
     */
    public function getAdvisor($id){
        return Advisor::find($id);
    }

    /**
     * Destroy Advisor
     * @param $id
     * @return int|void
     */
    public function destroy($id){
        if ($id) {
            Advisor::destroy($id);
            return 1;
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
}
