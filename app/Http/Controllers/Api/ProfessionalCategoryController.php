<?php

namespace App\Http\Controllers\API;
use App\Models\ProfessionalCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfessionalCategoryController extends BaseController
{
    public function professionalCategories() {
        try {
            return ProfessionalCategory::getProfessionalCategories();
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function create(Request $request){
        try {
            $category = ProfessionalCategory::createProfessionalCategory($request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'professional_category' => $category
        ]);
    }

    public function edit($id, Request $request){

        try {
            $category = ProfessionalCategory::updateProfessionalCategory($id, $request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'professional_category' => $category
        ]);
    }

    public function getProfessionalCategories($id){
        $category = ProfessionalCategory::getProfessionalCategory($id);
        if ($category) {
            return response()->json([
                'status' => 200,
                'professional_category' => $category
            ]);
        }
        return response()->json([
            'status' => 400,
            'message' => 'Categoria no existe'
        ]);
    }

    public function destroy($id){
        if ($id) {
            try {
                ProfessionalCategory::destroy($id);
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

    public function count(){
        return ProfessionalCategory::count();
    }
}
