<?php

namespace App\Http\Controllers\Api;
use App\Helpers\GeneralHelpers;
use App\Http\Resources\QuoteGroupResource;
use App\Models\QuoteGroup;
use Illuminate\Http\Request;

class QuoteGroupController extends BaseController
{
    public function quoteGroups(Request $request) {
        try {
            $query = QuoteGroup::getQuoteGroups();

            if ($request->filled('perPage')) {
                $perPage = (int) $request->perPage;

                $paginator = $query->paginate($perPage);

                // Resource sobre el paginator
                $quoteGroups = QuoteGroup::collection($paginator);
                // Si no tienes Resource, podrías usar directamente:
                // $certifications = $paginator->items();

                // Datos de paginación (usar SIEMPRE el paginator, NO el builder)
                $paginationData = GeneralHelpers::generatePaginationData($paginator);

                return $this->sendResponse(
                    [
                        'quote_groups' => $quoteGroups,
                        'links'          => $paginationData['links'],
                        'meta'           => $paginationData['meta'],
                    ],
                    trans('Obtenido con éxito')
                );
            }

            // SIN PAGINACIÓN
            $quoteGroups = QuoteGroupResource::collection($query->get());
            // o, sin resource: $certifications = $query->get();

            return $this->sendResponse(
                [
                    'quote_groups' => $quoteGroups,
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
            $quote = QuoteGroup::createQuoteGroup($request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return $this->sendResponse(
            [
                'quote_group' => $quote,
            ],
            trans('Creado con éxito')
        );
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

        return $this->sendResponse(
            [
                'quote_group' => $quote,
            ],
            trans('Guardado con éxito')
        );
    }

    public function getQuoteGroup($id){
        $quote = QuoteGroup::find($id);
        if ($quote) {
            return $this->sendResponse(
                [
                    'quote_group' => $quote,
                ],
                trans('Obtenido con éxito')
            );
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
        return QuoteGroup::count();
    }
}
