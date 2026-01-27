<?php

namespace App\Http\Controllers\Api;
use App\Exports\AdvisorsExport;
use App\Helpers\GeneralHelpers;
use App\Models\Advisor;
use App\Models\Company;
use App\Models\Course;
use App\Models\TrainingContract;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class AdvisorController extends BaseController
{

    /**
     * Obtener asesorías
     * @return mixed
     */
    public function index(Request $request)
    {
        try {
            $user = Auth::user();

            $mainCompanyId = GeneralHelpers::urlObtainCompanyId(
                $request->headers->get('origin'),
                $user->id
            );

            // Query base
            $query = Advisor::getAdvisor($mainCompanyId);

            // Filtro por teacher si aplica
            if ($user->teacher_id) {
                $query = $query
                    ->leftJoin('billings', 'billings.advisor_id', '=', 'advisors.id')
                    ->leftJoin('courses', 'courses.id', '=', 'billings.course_id')
                    ->where('courses.teacher_id', $user->teacher_id);
            }

            // Filtros dinámicos
            $query
                ->when($request->filled('name'), function ($q) use ($request) {
                    $q->where('advisors.name', 'like', '%' . $request->name . '%');
                })
                ->when($request->filled('nif'), function ($q) use ($request) {
                    $q->where('advisors.nif', 'like', '%' . $request->nif . '%');
                })
                ->when($request->filled('type'), function ($q) use ($request) {
                    $q->where('advisors.company_types_id', $request->type);
                })
                ->when($request->filled('activity'), function ($q) use ($request) {
                    $q->where('advisors.company_activities_id', $request->activity);
                })
                ->when($request->filled('province'), function ($q) use ($request) {
                    $q->where('advisors.province_id', 'like', '%' . $request->province);
                })
                ->when($request->has('show_inactive') && $request->show_inactive === 'false', function ($q) {
                    $q->where('advisors.active', 1);
                });

            $query->groupBy('advisors.id', 'advisors.name');

            // =======================
            // 🔃 SORT ADVISORS
            // =======================
            $sort = (string) $request->get('sort', '-id');
            $dir  = str_starts_with($sort, '-') ? 'desc' : 'asc';
            $key  = ltrim($sort, '-');

            // whitelist segura
            $sortable = [
                'id'                  => 'advisors.id',
                'name'                => 'advisors.name',
                'cif'                 => 'advisors.cif',       // o nif si usas nif
                'email'               => 'advisors.email',
                'telephone'           => 'advisors.telephone',
                'legal_representative'=> 'advisors.legal_representative',
                'status'              => 'advisors.active',
            ];

            // 🔹 company_type (relación)
            if ($key === 'company_type') {
                $query->distinct()
                    ->orderBy('company_types.name', $dir);
            }
            // 🔹 columnas directas
            elseif (isset($sortable[$key])) {
                $query->orderBy($sortable[$key], $dir);
            }
            // 🔹 fallback
            else {
                $query->orderBy('advisors.id', 'desc');
            }


            // === CON PAGINACIÓN ===
            if ($request->filled('perPage')) {
                $perPage   = (int) $request->perPage;
                $paginator = $query->paginate($perPage);

                // Colección de la página actual
                $advisors = $paginator->getCollection();

                // Cargamos companies de golpe (evitar N+1)
                $companyIds = $advisors->pluck('company_id')->filter()->unique();

                $companies = Company::select('companies.*', 'advisors.name as advisor')
                    ->leftJoin('advisors', 'advisors.id', '=', 'companies.advisor_id')
                    ->whereIn('companies.id', $companyIds)
                    ->get()
                    ->keyBy('id');

                // Enriquecemos cada advisor
                $advisors->transform(function ($advisor) use ($companies) {
                    $company = $companies->get($advisor->company_id);

                    $advisor->used = $company !== null;

                    if ($company) {
                        $advisor->advisor_id = $company->advisor_id;
                        $advisor->advisor    = $company->advisor;
                    } else {
                        $advisor->advisor_id = null;
                        $advisor->advisor    = null;
                    }

                    return $advisor;
                });

                // Actualizamos la colección del paginator (por si acaso)
                $paginator->setCollection($advisors);

                // Tu helper de paginación
                $paginationData = GeneralHelpers::generatePaginationData($paginator);

                return $this->sendResponse(
                    [
                        'advisors' => $advisors, // solo la página actual
                        'links'    => $paginationData['links'],
                        'meta'     => $paginationData['meta'],
                    ],
                    trans('Obtenido')
                );
            }

            // === SIN PAGINACIÓN ===
            $advisors = $query->get();

            $companyIds = $advisors->pluck('company_id')->filter()->unique();

            $companies = Company::select('companies.*', 'advisors.name as advisor')
                ->leftJoin('advisors', 'advisors.id', '=', 'companies.advisor_id')
                ->whereIn('companies.id', $companyIds)
                ->get()
                ->keyBy('id');

            $advisors->transform(function ($advisor) use ($companies) {
                $company = $companies->get($advisor->company_id);

                $advisor->used = $company !== null;

                if ($company) {
                    $advisor->advisor_id = $company->advisor_id;
                    $advisor->advisor    = $company->advisor;
                } else {
                    $advisor->advisor_id = null;
                    $advisor->advisor    = null;
                }

                return $advisor;
            });

            return $this->sendResponse(
                [
                    'advisors' => $advisors,
                ],
                trans('Obtenido')
            );
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Crear asesoría
     * @param Request $request
     * @return void
     */
    public function store(Request $request){
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            $data = $request->all();
            $data['main_company_id'] = $mainCompanyId;
            if (!$request['company_id']){
                $company = Company::createWithService($data);
                if ($company){
                    $data['company_id'] = $company->id;
                }
            }
            $advisor = Advisor::createWithService($data);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return $this->sendResponse(
            [
                'advisor' => Advisor::getAdvisor($mainCompanyId)->where('id', $advisor->id)->first(),
            ],
            trans('Creado con exito')
        );
    } // end method

    /**
     * Editar asesoría
     * @param $id
     * @param Request $request
     * @return int
     */
    public function update($id, Request $request){
        try {
            $data = $request->all();
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
            $company = Company::find($request->company_id);
            $company->updateWithService($data);
            $advisor = Advisor::where('advisors.id', $id)
            ->where('advisors.main_company_id', $mainCompanyId)->first();

            if (!$advisor){
                return response()->json([
                    'status' => 404,
                    'message' => 'Asesoria no encontrada'
                ]);
            }
            $advisor->updateWithService($data);

            return $this->sendResponse(
                [
                    'advisor' => Advisor::getAdvisor($mainCompanyId)->where('id', $advisor->id)->first(),
                ],
                trans('Actualizado con exito')
            );
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Obtener asesoría
     * @param $id
     * @return mixed
     */
    public function show($id, Request $request)
    {
        try {
            $mainCompanyId = GeneralHelpers::urlObtainCompanyId(
                $request->headers->get('origin'),
                Auth::id()
            );

            $advisor = Advisor::getAdvisor($mainCompanyId)
                ->where('advisors.id', $id)
                ->first();

            if (!$advisor) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Asesoría no existe'
                ], 404);
            }

            return $this->sendResponse(
                [
                    'advisor' => $advisor
                ],
                trans('Obtenido')
            );
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Eliminar asesoría
     * @param $id
     * @return int|void
     */
    public function destroy($id){
        if ($id) {
            try {
                Advisor::destroy($id);
                return $this->sendResponse(
                    [

                    ],
                    trans('Eliminado con exito')
                );
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 400,
                    'message' => $e->getMessage()
                ]);
            }
        }
    }

    /**
     * Comprueba si el nif existe
     * @param Request $request
     * @return int
     */
    public function checkNif(Request $request){
        $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

        $advisor = Advisor::findNif($request->nif, $mainCompanyId);

        if ($request['id']) {
            $advisor = $advisor->where('id', '!=', $request['id']);
        }

        $advisor = $advisor->first();

        return response()->json([
            'exists' => (bool) $advisor
        ]);
    }

    /**
     * Convert Company to Advisor
     * @param $id
     * @return int
     */
    public function convertAdvisor($id){
        $advisor = Advisor::convertAdvisor($id);
        if ($advisor){
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * Obtenemos los cursos de la asesoría
     * @param $id
     * @return mixed
     */
    public function getAdvisorCourses($id, Request $request) {
       $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
        $courses = Course::select('courses.*')
            ->leftjoin('registrations', 'registrations.course_id', '=', 'courses.id')
            ->leftjoin('billings', 'billings.id', '=', 'registrations.billing_id')
            ->where('billings.advisor_id', $id)
            ->where('courses.main_company_id', $mainCompanyId);

        $user = User::find(Auth::id());
        if ($user->teacher_id) {
            $courses = $courses->where('courses.teacher_id', $user->teacher_id);
        }

        return $courses
            ->groupBy('courses.id', 'courses.name')->get();
    }

    /**
     * Obtenemos los CFA de la asesoría
     * @param $id
     * @return mixed
     */
    public function getAdvisorTrainingContracts($id, Request $request) {
       $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
        $trainingActions = TrainingContract::select('training_contracts.*', 'companies.name as company_name', 'students.name as student_name', 'students.surname as student_surname')
            ->leftjoin('companies', 'companies.id', '=', 'training_contracts.company_id')
            ->leftjoin('students', 'students.id', '=', 'training_contracts.student_id')
            ->where('training_contracts.advisor_id', $id)
            ->where('training_contracts.main_company_id', $mainCompanyId);

        $user = User::find(Auth::id());
        if ($user->teacher_id) {
            $trainingActions->leftjoin('training_contract_elements', 'training_contract_elements.training_contract_id'. '='. 'training_contracts.id')
                ->leftjoin('courses', 'courses.id', '=', 'training_contract_elements.course_id')
                ->where('courses.teacher_id', $user->teacher_id);
        }

        $trainingActions = $trainingActions->groupBy('training_contracts.id', 'training_contracts.number_cfa')->get();

        return $this->sendResponse(
            [
                'training_actions' => $trainingActions,
            ],
            trans('Obtenido')
        );
    }

    /**
     * Obtenemos las empresas de la asesoría
     * @param $id
     * @return mixed
     */
    public function getAdvisorCompanies($id, Request $request) {
       $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
        $query = Company::select('companies.*')->where('advisor_id', $id)
        ->where('main_company_id', $mainCompanyId);

        $user = User::find(Auth::id());
        if ($user->teacher_id) {
            $query = $query->leftjoin('registrations', 'registrations.company_id', '=', 'companies.id')
                ->leftjoin('courses', 'courses.id', '=', 'registrations.course_id')
                ->where('courses.teacher_id', $user->teacher_id);
        }

        if ($request->filled('perPage')) {
            $perPage   = (int) $request->perPage;
            $paginator = $query->paginate($perPage);

            // Colección de la página actual
            $companies = $paginator->getCollection();

            // Actualizamos la colección del paginator (por si acaso)
            $paginator->setCollection($companies);

            // Tu helper de paginación
            $paginationData = GeneralHelpers::generatePaginationData($paginator);

            return $this->sendResponse(
                [
                    'companies' => $companies, // solo la página actual
                    'links'    => $paginationData['links'],
                    'meta'     => $paginationData['meta'],
                ],
                trans('Obtenido')
            );
        }
    }
    /**
     * Obtenemos las comisiones de la asesoría con la asesoria para los graphs
     * @param $id
     * @return mixed
     */
    public function indexWithCommissions(Request $request) {
       $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
        $advisors = Advisor::with('commissions')
            ->where('advisors.main_company_id', $mainCompanyId)
            ->get();
        return $this->sendResponse(
            [
                'advisors' =>$advisors,
            ],
            trans('Obtenido')
        );
    }

    public function createAdvisorUser($id, Request $request) {
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
            $advisor = Advisor::where($id)
                ->where('advisors.main_company_id', $mainCompanyId)
                ->first();

            if (!$advisor) {
                return response()->json([
                    'status' => 404,
                    'message' => 'El usuario no existe.'
                ]);
            }
            $email = explode(';', $advisor->email)[0] ?? $advisor->email;

            $user = User::FilterEmail($email)
                ->where('advisors.main_company_id', $mainCompanyId)
                ->first();
            if (!$user) {
                if ($advisor) {
                    $advisor->advisorUser();
                    return $this->sendResponse(
                        [
                            'advisor' => Advisor::getAdvisor($mainCompanyId)->where('id', $advisor->id)->first(),
                        ],
                        trans('Creado con éxito')
                    );
                }
            } else {
                return response()->json([
                    'status' => 409,
                    'message' => 'El usuario con este correo ya existe.'
                ]);
            }

        } catch (\Exception $exception) {
            return response()->json([]);
        }
    }

    public function sendEmail($id, Request $request)
    {
       $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
        try {
            $advisor = Advisor::where('advisors.id', $id)
                ->where('advisors.main_company_id', $mainCompanyId);

            if ($advisor) {
                $advisor->sendEmail($mainCompanyId);
                return $this->sendResponse(
                    [
                        'advisor' => $advisor,
                    ],
                    trans('enviado con éxito')
                );
            }
            else {
                return response()->json([
                    'status' => 404,
                    'message' => 'La asesoría no existe existe.'
                ]);
            }

        } catch (\Exception $exception) {
            return response()->json([]);
        }
    }

    public function exportExcel(Request $request)
    {
        try {
            $user = Auth::user();

            $mainCompanyId = GeneralHelpers::urlObtainCompanyId(
                $request->headers->get('origin'),
                $user->id
            );

            // Query base igual que tu index
            $query = Advisor::getAdvisor($mainCompanyId);

            // Filtro por teacher si aplica (igual que index)
            if ($user->teacher_id) {
                $query = $query
                    ->leftJoin('billings', 'billings.advisor_id', '=', 'advisors.id')
                    ->leftJoin('courses', 'courses.id', '=', 'billings.course_id')
                    ->where('courses.teacher_id', $user->teacher_id);
            }

            // Filtros (igual que index)
            $query
                ->when($request->filled('name'), function ($q) use ($request) {
                    $q->where('advisors.name', 'like', '%' . $request->name . '%');
                })
                ->when($request->filled('nif'), function ($q) use ($request) {
                    $q->where('advisors.nif', 'like', '%' . $request->nif . '%');
                })
                ->when($request->filled('type'), function ($q) use ($request) {
                    $q->where('advisors.company_types_id', $request->type);
                })
                ->when($request->filled('activity'), function ($q) use ($request) {
                    $q->where('advisors.company_activities_id', $request->activity);
                })
                ->when($request->filled('province'), function ($q) use ($request) {
                    $q->where('advisors.province_id', 'like', '%' . $request->province);
                })
                ->when($request->has('show_inactive') && $request->show_inactive === 'false', function ($q) {
                    $q->where('advisors.active', 1);
                });

            $query->groupBy('advisors.id', 'advisors.name');

            // (Opcional) Si quieres respetar sort como en index:
            $sort = (string) $request->get('sort', '-id');
            $dir  = str_starts_with($sort, '-') ? 'desc' : 'asc';
            $key  = ltrim($sort, '-');

            $sortable = [
                'id'                  => 'advisors.id',
                'name'                => 'advisors.name',
                'cif'                 => 'advisors.cif',
                'email'               => 'advisors.email',
                'telephone'           => 'advisors.telephone',
                'legal_representative'=> 'advisors.legal_representative',
                'status'              => 'advisors.active',
            ];

            if (isset($sortable[$key])) {
                $query->orderBy($sortable[$key], $dir);
            } else {
                $query->orderBy('advisors.id', 'desc');
            }

            // Traemos todo (sin paginación) y enriquecemos como en index
            $advisors = $query->get();

            $companyIds = $advisors->pluck('company_id')->filter()->unique();
            $companies = Company::select('companies.*', 'advisors.name as advisor')
                ->leftJoin('advisors', 'advisors.id', '=', 'companies.advisor_id')
                ->whereIn('companies.id', $companyIds)
                ->get()
                ->keyBy('id');

            $advisors->transform(function ($advisor) use ($companies) {
                $company = $companies->get($advisor->company_id);

                $advisor->used = $company !== null;

                if ($company) {
                    $advisor->advisor_id = $company->advisor_id;
                    $advisor->advisor    = $company->advisor;
                } else {
                    $advisor->advisor_id = null;
                    $advisor->advisor    = null;
                }

                return $advisor;
            });

            // Mapeo EXACTO a tu Excel "Asesorias.xlsx"
            $rows = $advisors->map(function ($a) {
                // ⚠️ Como no sé tus nombres exactos de relaciones/atributos,
                // uso data_get con varias posibilidades para que no se rompa.
                $tipo      = data_get($a, 'companyType.name')
                    ?? data_get($a, 'company_type.name')
                    ?? data_get($a, 'type.name')
                    ?? '';

                $actividad = data_get($a, 'companyActivity.name')
                    ?? data_get($a, 'company_activity.name')
                    ?? data_get($a, 'activity.name')
                    ?? '';

                $provincia = data_get($a, 'province.name')
                    ?? data_get($a, 'provincia.name')
                    ?? '';

                $cnae      = data_get($a, 'cnae.name')
                    ?? data_get($a, 'cnae_id')
                    ?? '';

                $colab     = data_get($a, 'collaborator.name')
                    ?? data_get($a, 'collaborator_user.name')
                    ?? data_get($a, 'user.name')
                    ?? '';

                $potencial = (string) (data_get($a, 'potential', '0')) === '1' ? 'Sí' : 'No';

                return [
                    'Nombre'                 => $a->name ?? '',
                    'CIF'                    => $a->cif ?? ($a->nif ?? ''),
                    'Tipo'                   => $tipo,
                    'Actividad'              => $actividad,
                    'Email'                  => $a->email ?? '',
                    'Teléfono'               => $a->telephone ?? '',
                    'Representante Legal'    => $a->legal_representative ?? '',
                    'DNI Representante Legal'=> $a->dni_legal_representative ?? '',
                    'IRPF'                   => $a->irpf ?? '',
                    'Comisión'               => $a->commission ?? '',
                    'Contacto 1'             => $a->contact_1 ?? '',
                    'Contacto 2'             => $a->contact_2 ?? '',
                    'Contacto 3'             => $a->contact_3 ?? '',
                    'Cotización'             => $a->quote ?? '',
                    'Colaborador'            => $colab,
                    'CNAE'                   => $cnae,
                    'Plantilla Media'        => $a->average_template ?? '',
                    'IBAN'                   => $a->iban ?? '',
                    'SEPA'                   => $a->sepa ?? '',
                    'B2B'                    => $a->b2b ?? '',
                    'Dirección'              => $a->address ?? '',
                    'Código Postal'          => $a->post_code ?? '',
                    'Provincia'              => $provincia,
                    'Población'              => $a->population ?? '',
                    'Asesor'                 => $a->advisor ?? '',
                    'Potencial'              => $potencial,
                ];
            });

            $fileName = 'asesorias_' . now()->format('Ymd_His') . '.xlsx';

            return Excel::download(new AdvisorsExport($rows->toArray()), $fileName);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
