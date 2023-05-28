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
                'message' => $e->getMessage()
            ]);
        }
    }

    public function create(Request $request){
        try {
            $payment = Payment::createPayment($request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'payment' => Payment::getPayment($payment->id)
        ]);
    }

    public function edit($id, Request $request){
        try {
            $payment = Payment::updatePayment($id, $request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'payment' => Payment::getPayment($payment->id)
        ]);
    }

    public function getPayment($id){
        $payment = Payment::getPayment($id);
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
                    'message' => $e->getMessage()
                ]);
            }
        }
    }

    public function count(){
        return Payment::count();
    }
}
