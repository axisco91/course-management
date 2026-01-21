<?php

namespace App\Http\Controllers\Api;

use App\Exports\CompanyCoursesExport;
use App\Helpers\GeneralHelpers;
use App\Http\Requests\CompanyRequests;
use App\Http\Resources\CompanyResource;
use App\Models\Advisor;
use App\Models\Company;
use App\Models\Course;
use App\Models\Provider;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

// ✅ Excel
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CompaniesExport;

class CompanyController extends BaseController
{
    /**
     * ✅ Helper: mismos filtros que index
     */
    private function applyCompanyFilters($query, Request $request)
    {
        if ($request->name) {
            $query->where('companies.name', 'like', '%' . $request->name . '%');
        }
        if ($request->nif) {
            $query->where('companies.nif', 'like', '%' . $request->nif . '%');
        }
        if ($request->type) {
            $query->where('companies.company_type_id', $request->type);
        }
        if ($request->activity) {
            $query->where('companies.company_activity_id', $request->activity);
        }
        if ($request->advisor) {
            $query->where('companies.advisor_id', $request->advisor);
        }
        if ($request->province) {
            $query->where('companies.province_id', $request->province);
        }
        if ($request->population) {
            $query->where('companies.population_id', $request->population);
        }
        if ($request->collaborator) {
            $query->where('companies.collaborator_id', $request->collaborator);
        }

        if ($request->status) {
            if ($request->status === 'Potential') {
                $query->where('companies.potential', 1);
            } elseif ($request->status === 'Inactive') {
                $query->where('companies.active', 0)->where('companies.potential', 0);
            } elseif ($request->status === 'Active') {
                $query->where('companies.active', 1)->where('companies.potential', 0);
            }
        }

        return $query;
    }

    /**
     * Obtenemos empresas
     */
    public function index(Request $request)
    {
        try {
            $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
            $query = Company::company($mainCompanyId);

            $user = User::find(Auth::id());
            if ($user && $user->teacher_id) {
                $query->leftjoin('registrations', 'registrations.company_id', '=', 'companies.id')
                    ->leftjoin('courses', 'courses.id', '=', 'registrations.course_id')
                    ->where('courses.teacher_id', $user->teacher_id);
            }

            // ✅ filtros
            $query = $this->applyCompanyFilters($query, $request);

            $query = $query->groupBy('companies.id', 'companies.name');

            // ✅ SORT
            $sort = (string) $request->get('sort', 'name');
            $dir  = str_starts_with($sort, '-') ? 'desc' : 'asc';
            $key  = ltrim($sort, '-');

            $sortable = [
                'id'           => 'companies.id',
                'name'         => 'companies.name',
                'nif'          => 'companies.nif',
                'telephone'    => 'companies.telephone',
                'email'        => 'companies.email',
                'created_at'   => 'companies.created_at',

                // relaciones (depende de joins)
                'company_type' => 'company_types.name',
                'consultant'   => 'users.name',
                'advisor'      => 'advisors.name',

                'active'       => 'companies.active',
                'status'       => 'status_sort',
            ];

            switch ($key) {
                case 'company_type':
                    $query->leftJoin('company_types', 'company_types.id', '=', 'companies.company_type_id');
                    break;

                case 'consultant':
                    $query->leftJoin('users', 'users.id', '=', 'companies.collaborator_id');
                    break;

                case 'advisor':
                    $query->leftJoin('advisors', 'advisors.id', '=', 'companies.advisor_id');
                    break;

                case 'status':
                    $query->addSelect(DB::raw("
                        CASE
                            WHEN companies.potential = 1 THEN 3
                            WHEN companies.active = 0 THEN 1
                            ELSE 2
                        END AS status_sort
                    "));
                    break;
            }

            if (in_array($key, ['company_type', 'consultant', 'advisor'], true)) {
                $query->select('companies.*')->distinct();
            }

            if ($key === 'status') {
                $query->orderBy('status_sort', $dir);
            } elseif (isset($sortable[$key])) {
                $query->orderBy($sortable[$key], $dir);
            } else {
                $query->orderBy('companies.name', 'desc');
            }

            if ($request->filled('perPage')) {
                $perPage = (int) $request->perPage;

                $paginator = $query->paginate($perPage);

                return $this->sendResponse(
                    [
                        'companies' => CompanyResource::collection($paginator),
                        'links' => GeneralHelpers::generatePaginationData($paginator)['links'],
                        'meta'  => GeneralHelpers::generatePaginationData($paginator)['meta'],
                    ],
                    trans('Obtenido')
                );
            }

            $companies = CompanyResource::collection($query->get());

            return $this->sendResponse(
                [
                    'companies' => $companies,
                ],
                trans('Obtenido con éxito')
            );
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * ✅ Export Excel Empresas (según Empresas.xlsx)
     */
    public function exportExcel(Request $request)
    {
        $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

        $query = Company::company($mainCompanyId);

        // ✅ misma restricción que index si el user es teacher
        $user = User::find(Auth::id());
        if ($user && $user->teacher_id) {
            $query->leftjoin('registrations', 'registrations.company_id', '=', 'companies.id')
                ->leftjoin('courses', 'courses.id', '=', 'registrations.course_id')
                ->where('courses.teacher_id', $user->teacher_id);
        }

        // ✅ mismos filtros que index
        $query = $this->applyCompanyFilters($query, $request);

        $query->groupBy('companies.id', 'companies.name');

        $companies = $query->orderBy('companies.name', 'asc')->get();

        $rows = $companies->map(function ($c) {
            // Estado
            if ((int)($c->potential ?? 0) === 1) {
                $status = 'Potencial';
            } elseif ((int)($c->active ?? 0) === 1) {
                $status = 'Activo';
            } else {
                $status = 'Inactivo';
            }

            // ✅ fila numérica (evita excel corrupto)
            return [
                (string)($c->name ?? ''),
                (string)($c->nif ?? ''),
                (string)($c->companyType ? $c->companyType->name : ''),
                (string)($c->companyActivity ? $c->companyActivity->name : ''),
                (string)($c->email ?? ''),
                (string)($c->telephone ?? ''),
                (string)($c->legal_representative ?? ''),
                (string)($c->legal_representative_dni ?? ''),
                (string)($c->c_quote ?? ''),
                (string)($c->collaborator ? $c->collaborator->name .' '. $c->collaborator->surname : ''),
                (string)($c->cnae ? $c->cnae->name : ''),
                (string)($c->average_staff ?? ''),
                (string)($c->iban ?? ''),
                (string)($c->sepa ?? ''),
                (string)($c->b2b ?? ''),
                (string)($c->address ?? ''),
                (string)($c->post_code ?? ''),
                (string)($c->province ? $c->province->name : ''),
                (string)($c->population ?? ''),
                (string)($c->advisor ? $c->advisor->name : ''),
                (string)($status),
            ];
        });

        $fileName = 'empresas_' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new CompaniesExport($rows), $fileName);
    }

    /**
     * Obtener empresa
     */
    public function show($id, Request $request)
    {
        $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

        $company = Company::company($mainCompanyId)
            ->where('companies.id', $id)
            ->first();

        if ($company) {
            return $this->sendResponse(
                [
                    'company' => $company,
                ],
                trans('Obtenido con éxito')
            );
        }

        return response()->json([
            'status' => 400,
            'message' => 'Empresa no existe'
        ], 400);
    }

    /**
     * Crear empresa
     */
    public function store(CompanyRequests $request)
    {
        try {
            $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            $data = $request->all();
            $data['main_company_id'] = $mainCompanyId;

            if (isset($data['agreement'])) {
                Log::info('Agreement received: ' . $data['agreement']);
            } else {
                Log::warning('Agreement not received');
            }

            $element = Company::createWithService($data);

            $company = Company::company($mainCompanyId)
                ->where('companies.id', $element->id)
                ->first();

            return $this->sendResponse(
                [
                    'company' => $company,
                ],
                trans('Creado con éxito')
            );
        } catch (\Exception $e) {
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Editar empresa
     */
    public function update($id, CompanyRequests $request)
    {
        try {
            $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
            $data = $request->all();

            if (isset($data['agreement'])) {
                Log::info('Agreement received: ' . $data['agreement']);
            } else {
                Log::warning('Agreement not received');
            }

            $company = Company::where('id', $id)
                ->FilterMainCompany($mainCompanyId)
                ->first();

            if (!$company) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Empresa no existe'
                ], 404);
            }

            $element = $company->updateWithService($data);

            $company = Company::where('companies.id', $element->id)->first();

            $advisor = Advisor::where('company_id', $company->id)->first();
            if ($advisor) {
                $data['company_id'] = $company->id;
                $advisor->updateAdvisorCompany($data);
            }

            return $this->sendResponse(
                [
                    'company' => $company,
                ],
                trans('Guardado con éxito')
            );
        } catch (\Exception $e) {
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Eliminar empresa
     */
    public function destroy($id, Request $request)
    {
        if ($id) {
            try {
                $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

                $company = Company::where('id', $id)
                    ->FilterMainCompany($mainCompanyId)
                    ->first();

                if (!$company) {
                    return response()->json([
                        'status' => 404,
                        'message' => 'Empresa no existe'
                    ], 404);
                }

                Company::destroy($id);

                return $this->sendResponse([]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 400,
                    'message' => $e->getMessage()
                ], 400);
            }
        }
    }

    /**
     * Convertir a cliente
     */
    public function convertClient($id, Request $request)
    {
        if ($id) {
            try {
                $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

                $company = Company::where('id', $id)
                    ->FilterMainCompany($mainCompanyId)
                    ->first();

                if (!$company) {
                    return response()->json([
                        'status' => 404,
                        'message' => 'Empresa no existe'
                    ], 404);
                }

                $company->update(['potential' => 0]);

                return $this->sendResponse(
                    [
                        'company' => $company,
                    ],
                    trans('Guardado con éxito')
                );
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 400,
                    'message' => $e->getMessage()
                ], 400);
            }
        }

        return response()->json([
            'status' => 400,
            'error' => 'No has pasado empresa'
        ], 400);
    }

    /**
     * Convertir a asesoria
     */
    public function convertAdvisor($id, Request $request)
    {
        if ($id) {
            try {
                $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

                $company = Company::where('id', $id)
                    ->FilterMainCompany($mainCompanyId)
                    ->first();

                if (!$company) {
                    return response()->json([
                        'status' => 404,
                        'message' => 'Empresa no existe'
                    ], 404);
                }

                Advisor::convertAdvisor($id);

                return $this->sendResponse(
                    [
                        'company' => $company,
                    ],
                    trans('Guardado con éxito')
                );
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 400,
                    'message' => $e->getMessage()
                ], 400);
            }
        }

        return response()->json([
            'status' => 400,
            'error' => 'No has pasado empresa'
        ], 400);
    }

    /**
     * Convertir a proveedor
     */
    public function convertProvider($id, Request $request)
    {
        if ($id) {
            try {
                $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

                $company = Company::where('id', $id)
                    ->FilterMainCompany($mainCompanyId)
                    ->first();

                if (!$company) {
                    return response()->json([
                        'status' => 404,
                        'message' => 'Empresa no existe'
                    ], 404);
                }

                Provider::convertProvider($id, $company);

                return $this->sendResponse(
                    [
                        'company' => $company,
                    ],
                    trans('Guardado con éxito')
                );
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 400,
                    'message' => $e->getMessage()
                ], 400);
            }
        }

        return response()->json([
            'status' => 400,
            'error' => 'No has pasado empresa'
        ], 400);
    }

    /**
     * Cursos de empresa
     */
    public function getCompanyCourses($id, Request $request)
    {
        $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

        $company = Company::where('id', $id)
            ->FilterMainCompany($mainCompanyId)
            ->first();

        if (!$company) {
            return response()->json([
                'status' => 404,
                'message' => 'Empresa no existe'
            ], 404);
        }

        $courses = Course::companyCourses($id, $mainCompanyId)
            ->orderBy('beginning', 'DESC')
            ->get();

        if (count($courses)) {
            foreach ($courses as $course) {
                $course['beginning'] = Carbon::parse($course['beginning'])->format('d/m/Y');
                $course['end'] = Carbon::parse($course['end'])->format('d/m/Y');
            }
        }

        return $this->sendResponse(
            [
                'courses' => $courses,
            ],
            trans('Obtenido con éxito')
        );
    }

    /**
     * Alumnos de empresa
     */
    public function getCompanyStudents($id, Request $request)
    {
        $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

        $company = Company::where('id', $id)
            ->FilterMainCompany($mainCompanyId)
            ->first();

        if (!$company) {
            return response()->json([
                'status' => 404,
                'message' => 'Empresa no existe'
            ], 404);
        }

        return $this->sendResponse(
            [
                'students' => Student::companyStudents($id, $mainCompanyId)->get(),
            ],
            trans('Obtenido con éxito')
        );
    }

    public function coursesExportExcel($id, Request $request)
    {
        try {
            $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            $company = Company::where('id', $id)
                ->FilterMainCompany($mainCompanyId)
                ->first();

            if (!$company) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Empresa no existe'
                ], 404);
            }

            $courses = Course::companyCourses($id, $mainCompanyId)
                ->orderBy('beginning', 'DESC')
                ->get();

            // Mapeo a columnas del Excel
            $rows = $courses->map(function ($c) {
                return [
                    'Nombre'      => $c->name ?? '',
                    'Grupo'       => $c->group ?? '',
                    'Fecha Inicio'=> !empty($c->beginning) ? Carbon::parse($c->beginning)->format('d/m/Y') : '',
                    'Fecha Fin'   => !empty($c->end) ? Carbon::parse($c->end)->format('d/m/Y') : '',
                    'Tipo' => $c->courseType->name ?? '',
                ];
            });

            $fileName = 'cursos_' . now()->format('Y-m-d_H-i-s') . '.xlsx';

            return Excel::download(new CompanyCoursesExport($rows), $fileName);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
