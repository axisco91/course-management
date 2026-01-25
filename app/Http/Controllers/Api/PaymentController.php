<?php

namespace App\Http\Controllers\Api;
use App\Helpers\GeneralHelpers;
use App\Http\Resources\PaymentResource;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends BaseController
{
    public function getPayments(Request $request)
    {
        try {
            $query = Payment::getPayment();

            // ✅ FILTRO POR NOMBRE
            // ?search=texto
            if ($request->filled('search')) {
                $search = trim($request->input('search'));
                $query->where('name', 'LIKE', '%' . $search . '%');
            }

            // ✅ SORT
            // ?sort=name   -> ASC
            // ?sort=-name  -> DESC
            $sort = $request->input('sort', 'name');
            $direction = 'asc';

            if (is_string($sort) && strlen($sort) > 0 && $sort[0] === '-') {
                $direction = 'desc';
                $sort = substr($sort, 1);
            }

            // 🔒 whitelist de columnas permitidas
            $allowedSorts = ['id', 'name', 'created_at', 'updated_at'];

            if (!in_array($sort, $allowedSorts, true)) {
                $sort = 'name';
                $direction = 'asc';
            }

            $query->orderBy($sort, $direction);

            // ✅ PAGINACIÓN
            if ($request->filled('perPage')) {
                $perPage = (int) $request->perPage;

                $paginator = $query->paginate($perPage);

                $payments = PaymentResource::collection($paginator);

                $paginationData = GeneralHelpers::generatePaginationData($paginator);

                return $this->sendResponse(
                    [
                        'payments' => $payments,
                        'links'    => $paginationData['links'],
                        'meta'     => $paginationData['meta'],
                    ],
                    trans('Obtenido con éxito')
                );
            }

            // ✅ SIN PAGINACIÓN
            $payments = PaymentResource::collection($query->get());

            return $this->sendResponse(
                [
                    'payments' => $payments,
                ],
                trans('Obtenido con éxito')
            );
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function create(Request $request){
        try {
            $payment = Payment::createWithService($request->all());
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return $this->sendResponse(
            [
                'payment' => $payment,
            ],
            trans('Creado con éxito')
        );
    }

    public function edit($id, Request $request){
        try {
            $payment = Payment::find($id);
            $payment->updateWithService($request->all());
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return $this->sendResponse(
            [
                'payment' => $payment,
            ],
            trans('Guardado con éxito')
        );
    }

    public function getPayment($id){
        $payment = Payment::getPayment()->where('payments.id', $id)->first();
        if ($payment) {
            return $this->sendResponse(
                [
                    'payment' => $payment,
                ],
                trans('Obtenido con éxito')
            );
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
                return $this->sendResponse(
                    [],
                    trans('Eliminado con éxito')
                );
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
