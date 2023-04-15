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
                'error' => $e->getMessage()
            ]);
        }
    }

    public function create(Request $request){
        $data = json_decode($request->getContent(), true);
        try {
            $category = ProfessionalCategory::createProfessionalCategory($data);
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
        $data = json_decode($request->getContent(), true);
        try {
            $category = ProfessionalCategory::updateProfessionalCategory($id, $data);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'error' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'professional_category' => $category
        ]);
    }

    public function getProfessionalCategories($id){
        $category = ProfessionalCategory::getProfessionalCategories($id);
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
                    'error' => $e->getMessage()
                ]);
            }
        }
    }

    public function count(){
        return ProfessionalCategory::count();
    }
}
