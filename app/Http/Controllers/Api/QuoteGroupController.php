<?php

namespace App\Http\Controllers\Api;
use App\Models\QuoteGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class QuoteGroupController extends BaseController
{
    public function quoteGroups() {
        try {
            return QuoteGroup::getQuoteGroups();
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function create(Request $request){
        try {
            $quote = QuoteGroup::createQuoteGroup($request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'quote_group' => $quote
        ]);
    }

    public function edit($id, Request $request){
        try {
            $quote = QuoteGroup::updateQuoteGroup($id, $request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'quote_group' => $quote
        ]);
    }

    public function getQuoteGroup($id){
        $quote = QuoteGroup::find($id);
        if ($quote) {
            return response()->json([
                'status' => 200,
                'quote_group' => $quote
            ]);
        }
        return response()->json([
            'status' => 400,
            'message' => 'Cuota no existe'
        ]);
    }

    public function destroy($id){
        if ($id) {
            try {
                QuoteGroup::destroy($id);
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
        return QuoteGroup::count();
    }
}
