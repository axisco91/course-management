<?php

namespace App\Http\Controllers\Api;
use App\Helpers\GeneralHelpers;
use App\Http\Resources\LiquidationResource;
use App\Models\Liquidation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LiquidationController extends BaseController
{
    public function index(Request $request)
    {
        try {
            $mainCompanyId = GeneralHelpers::urlObtainCompanyId(
                $request->headers->get('origin'),
                Auth::id()
            );

            $query = Liquidation::getliquidation($mainCompanyId);

            // sort param: "course" | "-course" | "advisor" | ...
            $sortParam = (string) $request->get('sort', '-id');
            $dir       = str_starts_with($sortParam, '-') ? 'desc' : 'asc';
            $key       = ltrim($sortParam, '-');

            // ✅ joins SOLO si hace falta
            if ($key === 'course') {
                $query->leftJoin('courses', 'courses.id', '=', 'liquidations.course_id');
            }

            if ($key === 'advisor') {
                $query->leftJoin('advisors', 'advisors.id', '=', 'liquidations.advisor_id');
            }

            /**
             * ✅ SELECT estable:
             * - Siempre seleccionamos liquidations.*
             * - Si ordenas por course/advisor, añadimos aliases con el nombre.
             *   (evita errores con DISTINCT / paginación / order by)
             */
            $select = ['liquidations.*'];

            if ($key === 'course') {
                $select[] = 'courses.name as course_name_sort';
            }

            if ($key === 'advisor') {
                $select[] = 'advisors.name as advisor_name_sort';
            }

            $query->select($select);

            // ✅ whitelist de columnas sortable
            $sortable = [
                'id'           => 'liquidations.id',
                'beginning'    => 'liquidations.beginning',
                'end'          => 'liquidations.end',
                'status'       => 'liquidations.status',
                'paid'         => 'liquidations.paid',
                'invoice_date' => 'liquidations.invoice_date',
                'paid_date'    => 'liquidations.paid_date',
                'bill_number'  => 'liquidations.bill_number',
                'price'        => 'liquidations.price',
                'commission'   => 'liquidations.commission',

                // ✅ ordenar por NOMBRE (alias)
                'course'       => 'course_name_sort',
                'advisor'      => 'advisor_name_sort',
            ];

            if (isset($sortable[$key])) {
                $query->orderBy($sortable[$key], $dir);

                // tie-breaker estable
                if ($key !== 'id') {
                    $query->orderBy('liquidations.id', 'desc');
                }
            } else {
                $query->orderBy('liquidations.id', 'desc');
            }

            if ($request->filled('perPage')) {
                $perPage   = (int) $request->perPage;
                $paginator = $query->paginate($perPage);

                $liquidations = LiquidationResource::collection($paginator);
                $pagination   = GeneralHelpers::generatePaginationData($paginator);

                return $this->sendResponse(
                    [
                        'liquidations' => $liquidations,
                        'links'        => $pagination['links'],
                        'meta'         => $pagination['meta'],
                    ],
                    trans('Obtenido con éxito')
                );
            }

            return $this->sendResponse(
                [
                    'liquidations' => LiquidationResource::collection($query->get()),
                ],
                trans('Obtenido con éxito')
            );
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function create(Request $request){
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            $data = $request->all();
            $data['main_company_id'] = $mainCompanyId;

            $liquidation = Liquidation::createWithService($data);

            return $this->sendResponse(
                [
                    'liquidation' => Liquidation::getliquidation($mainCompanyId)->where('id', $liquidation->id)->first(),
                ],
                trans('Creado con éxito')
            );
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function edit($id, Request $request){
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            $liquidation = Liquidation::where('id', $id)
                ->where('main_company_id', $mainCompanyId)
                ->first();

            if (!$liquidation) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Liquidación no existe'
                ]);
            }
            $data = $request->all();

            $liquidation->updateWithService($data);

            return $this->sendResponse(
                [
                    'liquidation' => Liquidation::getliquidation($mainCompanyId)->where('id', $liquidation->id)->first(),
                ],
                trans('Guardado con éxito')
            );
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function show($id, Request $request){
       $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

        $liquidation = Liquidation::getliquidation($mainCompanyId)->where('id', $id)->first();
        if ($liquidation) {
            return $this->sendResponse(
                [
                    'liquidation' => $liquidation,
                ],
                trans('Obtenido con éxito')
            );
        }
        return response()->json([
            'status' => 400,
            'message' => 'Liquidación no existe'
        ]);
    }

    public function destroy($id, Request $request){
        if ($id) {
            try {
               $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

                $liquidation = Liquidation::where('id', $id)
                    ->where('main_company_id', $mainCompanyId)
                    ->first();

                if (!$liquidation) {
                    return response()->json([
                        'status' => 404,
                        'message' => 'Liquidación no existe'
                    ]);
                }

                Liquidation::destroy($id);
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
}
