<?php

namespace App\Http\Controllers\API;

use App\Helpers\GeneralHelpers;
use App\Http\Controllers\Controller;
use App\Http\Resources\PotentialTrainingContractResource;
use App\Models\PotentialTrainingContract;
use App\Services\EmailDeliveryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Mail\PotentialTrainingContract as PotentialTrainingContractMail;

class PotentialTrainingContractController extends Controller
{
    public function __construct(private EmailDeliveryService $emailDeliveryService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = PotentialTrainingContract::select('*');

            if ($request->filled('perPage')) {
                $perPage = (int) $request->perPage;

                $paginator = $query->paginate($perPage);

                // Resource sobre el paginator
                $contracts = PotentialTrainingContractResource::collection($paginator);
                // Si no tienes Resource, podrías usar directamente:
                // $certifications = $paginator->items();

                // Datos de paginación (usar SIEMPRE el paginator, NO el builder)
                $paginationData = GeneralHelpers::generatePaginationData($paginator);

                return $this->sendResponse(
                    [
                        'potential_training_contracts' => $contracts,
                        'links'          => $paginationData['links'],
                        'meta'           => $paginationData['meta'],
                    ],
                    trans('Obtenido con éxito')
                );
            }

            // SIN PAGINACIÓN
            $contracts = PotentialTrainingContractResource::collection($query->get());
            // o, sin resource: $certifications = $query->get();

            return $this->sendResponse(
                [
                    'potential_training_contracts' => $contracts,
                ],
                trans('Obtenido con éxito')
            );
        } catch (\Exception $e) {
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $contract = PotentialTrainingContract::create($request->all());

            return $this->sendResponse(
                [
                    'potential_training_contract' => $contract,
                ],
                trans('Creado con éxito')
            );
        } catch (\Exception $e) {
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $contract = PotentialTrainingContract::findOrFail($id);
            return $this->sendResponse(
                [
                    'potential_training_contract' => $contract,
                ],
                trans('Obtenido con éxito')
            );
        } catch (\Exception $e) {
            return response()->json([
                'status' => 404,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id, Request $request)
    {
        try {
            $contract = PotentialTrainingContract::findOrFail($id);
            $contract->update($request->all());
            return $this->sendResponse(
                [
                    'potential_training_contract' => $contract,
                ],
                trans('Guardado con éxito')
            );
        } catch (\Exception $e) {
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $contract = PotentialTrainingContract::findOrFail($id);
            $contract->delete();
            return $this->sendResponse(
                [
                    'potential_training_contract' => $contract,
                ],
                trans('Eliminado con éxito')
            );
        } catch (\Exception $e) {
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function sendEmail(Request $request){
        if ($request['email']){
            try {
                $this->emailDeliveryService->sendTo(
                    $request['email'],
                    new PotentialTrainingContractMail(),
                    [
                        'mail_type' => 'potential_training_contract',
                    ],
                    config('mail.default', 'smtp')
                );
                return $this->sendResponse(
                    [],
                    trans('Enviado con éxito')
                );
            } catch(Exception $e) {
                return response()->json([
                    'status' => 400,
                    'message' => $e->getMessage()
                ]);
            }
        }
        return response()->json([
            'status' => 400,
            'message' => 'Error al enviar correo'
        ]);
    }
}
