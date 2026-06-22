<?php

namespace App\Http\Controllers\Api;
use App\Helpers\GeneralHelpers;
use App\Http\Resources\PotentialStudentResource;
use App\Mail\PotentialPrivateStudent as PotentialPrivateStudent;
use App\Mail\PotentialStudent as PotentialEmail;
use App\Models\MainCompany;
use App\Models\PotentialStudent;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Mockery\Exception;

class PotentialStudentController extends BaseController
{
    public function getPotentialStudents(Request $request) {
        try {
            $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            $query = PotentialStudent::getPotentialStudent($mainCompanyId);

            if ($request->filled('name')) {
                $query = $query->where('potential_students.name', 'like', '%' . $request->name . '%');
            }
            if ($request->filled('surname')) {
                $query = $query->where('potential_students.surname', 'like', '%' . $request->surname . '%');
            }
            if ($request->filled('dni')) {
                $query = $query->where('potential_students.dni', 'like', '%' . $request->dni . '%');
            }
            if ($request->filled('telephone')) {
                $query = $query->where('potential_students.telephone', 'like', '%' . $request->telephone . '%');
            }
            if ($request->filled('email')) {
                $query = $query->where('potential_students.email', 'like', '%' . $request->email . '%');
            }
            if ($request->filled('company_id') && (int) $request->company_id > 0) {
                $query = $query->where('potential_students.company_id', (int) $request->company_id);
            }

            $sortParam = (string) $request->get('sort', 'name');
            $direction = str_starts_with($sortParam, '-') ? 'desc' : 'asc';
            $sortField = ltrim($sortParam, '-');

            $sortMap = [
                'name' => 'potential_students.name',
                'surname' => 'potential_students.surname',
                'dni' => 'potential_students.dni',
                'telephone' => 'potential_students.telephone',
                'email' => 'potential_students.email',
                'status' => 'potential_students.active',
                'active' => 'potential_students.active',
            ];

            // scopeGetPotentialStudent adds a default orderBy(name asc); reorder() replaces it.
            if ($sortField === 'name') {
                $query = $query->reorder('potential_students.name', $direction)
                    ->orderBy('potential_students.surname', $direction);
            } elseif (in_array($sortField, ['company', 'company_name'])) {
                $query = $query
                    ->leftJoin('companies', 'companies.id', '=', 'potential_students.company_id')
                    ->reorder(DB::raw('COALESCE(companies.name, potential_students.company_name)'), $direction);
            } else {
                $sortColumn = $sortMap[$sortField] ?? 'potential_students.name';
                $query = $query->reorder($sortColumn, $direction);
            }

            if ($request->filled('perPage')) {
                $perPage = (int) $request->perPage;

                $paginator = $query->paginate($perPage);

                // Resource sobre el paginator
                $potentialStudents = PotentialStudentResource::collection($paginator);
                // Si no tienes Resource, podrías usar directamente:
                // $certifications = $paginator->items();

                // Datos de paginación (usar SIEMPRE el paginator, NO el builder)
                $paginationData = GeneralHelpers::generatePaginationData($paginator);

                return $this->sendResponse(
                    [
                        'potential_students' => $potentialStudents,
                        'links'          => $paginationData['links'],
                        'meta'           => $paginationData['meta'],
                    ],
                    trans('Obtenido con éxito')
                );
            }

            // SIN PAGINACIÓN
            $potentialStudents = PotentialStudentResource::collection($query->get());
            // o, sin resource: $certifications = $query->get();

            return $this->sendResponse(
                [
                    'potential_students' => $potentialStudents,
                ],
                trans('Obtenido con éxito')
            );
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function getPotentialStudent($id, Request $request) {
       $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

        $potentialStudent = PotentialStudent::getPotentialStudent($mainCompanyId)->where('potential_students.id', $id)->first();
        if ($potentialStudent) {
            return $this->sendResponse(
                [
                    'potential_student' => $potentialStudent,
                ],
                trans('Obtenido con éxito')
            );
        }
        return response()->json([
            'status' => 400,
            'message' => 'Alumno no existe'
        ]);
    }

    public function create(Request $request){
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            $data = $request->all();
            $data['main_company_id'] = $mainCompanyId;

            $student = PotentialStudent::createWithService($data);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return $this->sendResponse(
            [
                'potential_student' => $student,
            ],
            trans('Creado con éxito')
        );
    }

    public function edit($id, Request $request){
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            $student = PotentialStudent::updateStudent($id, $request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return $this->sendResponse(
            [
                'potential_student' => $student,
            ],
            trans('Guardado con éxito')
        );
    }

    public function destroy($id, Request $request){
        if ($id) {
            try {
               $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

                $potentialStudent = PotentialStudent::where('id', $id)
                    ->FilterMainCompanyId($mainCompanyId)
                    ->first();

                if (!$potentialStudent) {
                    return response()->json([
                        'status' => 404,
                        'message' => 'Alumno potencial no encontrado'
                    ]);
                }

                PotentialStudent::destroy($id);
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

    public function sendEmail(Request $request){
        if ($request['email']){
            try {
               $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

                $mainCompany = MainCompany::find($mainCompanyId);

                Mail::getSwiftMailer()
                    ->getTransport()
                    ->setUsername('zona@avzformacion.com')
                    ->setPassword('Avz.2021');
                    Mail::to($request['email'])->send(new PotentialPrivateStudent($mainCompany->url, $mainCompany->name));
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

    public function sendBonusEmail(Request $request){
        if ($request['email']){
            try {
               $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

                $mainCompany = MainCompany::find($mainCompanyId);

                Mail::getSwiftMailer()
                    ->getTransport()
                    ->setUsername('zona@avzformacion.com')
                    ->setPassword('Avz.2021');
                Mail::to($request['email'])->send(new PotentialEmail($mainCompany->url, $mainCompany->name));
                return $this->sendResponse(
                    [],
                    trans('Eliminado con éxito')
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

    public function checkDni(Request $request){
       $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

        $dni = PotentialStudent::findDni($request['dni'], $mainCompanyId, $request['id']);
        if ($dni){
            return $this->sendResponse(
                [
                    'exists' => true,
                ],
                trans('Obtenido con éxito')
            );
        } else {
            return $this->sendResponse(
                [
                    'exists' => false,
                ],
                trans('Obtenido con éxito')
            );
        }
    }

    public function count(Request $request){
       $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

        return PotentialStudent::FilterMainCompany($mainCompanyId)->count();
    }

    public function convertStudent($id, Request $request) {
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            $potentialStudent = PotentialStudent::where('id', $id)
                ->FilterMainCompanyId($mainCompanyId)
                ->first();

            if (!$potentialStudent) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Alumno potencial no encontrado'
                ]);
            }

            Student::createWithService($request->all());
            $potentialStudent->convertPotentialStudent();
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return $this->sendResponse(
            [
                'student' => $potentialStudent,
            ],
            trans('Obtenido con éxito')
        );
    }
}
