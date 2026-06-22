<?php

namespace App\Http\Controllers\Api;

use App\Helpers\GeneralHelpers;
use App\Http\Resources\TrainingContractSeriesResource;
use Illuminate\Http\Request;
use App\Models\TrainingContractSeries;
use App\Http\Controllers\Controller;

class TrainingContractSeriesController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $query = TrainingContractSeries::select('*');

        if ($request->filled('perPage')) {
            $perPage = (int) $request->perPage;

            $paginator = $query->paginate($perPage);

            // Resource sobre el paginator
            $trainingContractSeries = TrainingContractSeriesResource::collection($paginator);
            // Si no tienes Resource, podrías usar directamente:
            // $certifications = $paginator->items();

            // Datos de paginación (usar SIEMPRE el paginator, NO el builder)
            $paginationData = GeneralHelpers::generatePaginationData($paginator);

            return $this->sendResponse(
                [
                    'training_contract_series' => $trainingContractSeries,
                    'links'          => $paginationData['links'],
                    'meta'           => $paginationData['meta'],
                ],
                trans('Obtenido con éxito')
            );
        }

        // SIN PAGINACIÓN
        $trainingContractSeries = TrainingContractSeriesResource::collection($query->get());
        // o, sin resource: $certifications = $query->get();

        return $this->sendResponse(
            [
                'training_contract_series' => $trainingContractSeries,
            ],
            trans('Obtenido con éxito')
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function get($id)
    {
        $series = TrainingContractSeries::findOrFail($id);
        return $this->sendResponse(
            [
                'training_contract_series' => $series,
            ],
            trans('Obtenido con éxito')
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $series = TrainingContractSeries::create($request->all());
        return $this->sendResponse(
            [
                'training_contract_series' => $series,
            ],
            trans('Creado con éxito')
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $series = TrainingContractSeries::findOrFail($id);
        $series->update($request->all());
        return $this->sendResponse(
            [
                'training_contract_series' => $series,
            ],
            trans('Guardado con éxito')
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $series = TrainingContractSeries::findOrFail($id);
        $series->delete();
        return $this->sendResponse(
            [],
            trans('Obtenido con éxito')
        );
    }
}
