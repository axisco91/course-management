<?php

namespace App\Http\Controllers\API;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PaymentController extends BaseController
{
    public function getPayments() {
        try {
            return Payment::getPayments();
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }
    }

    public function create(Request $request){
        $data = json_decode($request->getContent(), true);
        try {
            $payment = Payment::createPayment($data);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'payment' => $payment
        ]);
    }

    public function edit($id, Request $request){
        $data = json_decode($request->getContent(), true);
        try {
            $payment = Payment::updatePayment($id, $data);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'error' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'payment' => $payment
        ]);
    }

    public function getPayment($id){
        $payment = Payment::find($id);
        if ($payment) {
            return response()->json([
                'status' => 200,
                'payment' => $payment
            ]);
        }
        return response()->json([
            'status' => 400,
            'message' => 'Metodos de pago no existe'
        ]);
    }

    public function destroy($id){
        if ($id) {
            try {
                Payment::destroy($id);
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
}
