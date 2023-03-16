<?php

namespace App\Http\Controllers\API;
use App\Models\Advisor;
use App\Models\Company;
use App\Models\Course;
use App\Models\Provider;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CompanyController extends BaseController
{
    public function companies() {
        try {
            return Company::getCompanies();
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e.message
            ]);
        }
    }

    public function getActiveCompanies(){
        return Company::select('companies.*', 'id as value', 'name as label')
            ->where('active', 1)->get();
    }

    public function create(Request $request){
        $data = json_decode($request->getContent(), true);
        try {
            $company = Company::createCompany($data);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'company' => $company
        ]);
    }

    public function edit($id, Request $request){
        $data = json_decode($request->getContent(), true);
        try {
            $company = Company::updateCompany($id, $data);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'error' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'company' => $company
        ]);
    }

    public function getCompany($id){
        $company = Company::find($id);
        if ($company) {
            return response()->json([
                'status' => 200,
                'company' => $company
            ]);
        }
        return response()->json([
            'status' => 400,
            'message' => 'Empresa no existe'
        ]);
    }

    public function destroy($id){
        if ($id) {
            try {
                Company::destroy($id);
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

    public function convertClient($id){
        if ($id) {
            try {
                $company = Company::find($id);
                $company->update([
                    'potential' => 0
                ]);
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
        return response()->json([
            'status' => 400,
            'error' => 'No has pasado empresa'
        ]);
    }

    public function convertAdvisor($id){
        if ($id) {
            try {
                Advisor::convertAdvisor($id);
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
        return response()->json([
            'status' => 400,
            'error' => 'No has pasado empresa'
        ]);
    }

    public function convertProvider($id){
        if ($id) {
            try {
                Provider::convertProvider($id);
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
        return response()->json([
            'status' => 400,
            'error' => 'No has pasado empresa'
        ]);
    }

    public function getCompanyCourses($id) {
        return Course::getCompanyCourses($id);
    }

    public function getCompanyStudents($id) {
        return Student::getCompanyStudents($id);
    }

    public function count(){
        return Company::count();
    }
}
