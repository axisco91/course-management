<?php

namespace App\Http\Controllers\Api;
use App\Helpers\GeneralHelpers;
use App\Http\Resources\CourseResource;
use App\Http\Resources\RegistrationResource;
use App\Models\Advisor;
use App\Models\Bill;
use App\Models\Company;
use App\Models\Profitability;
use App\Models\Registration;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RegistrationController extends BaseController
{

    /**
     * Obtenemos los alumnos matriculados
     * @param $id
     * @return mixed
     */
    public function getRegistrations($id, Request $request) {
       $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

        $query = Student::getRegistrated($id, $mainCompanyId);

        if ($request->filled('perPage')) {
            $perPage = (int) $request->perPage;

            $paginator = $query->paginate($perPage);

            // Resource sobre el paginator
            $students = RegistrationResource::collection($paginator);
            // Si no tienes Resource, podrías usar directamente:
            // $certifications = $paginator->items();

            // Datos de paginación (usar SIEMPRE el paginator, NO el builder)
            $paginationData = GeneralHelpers::generatePaginationData($paginator);

            return $this->sendResponse(
                [
                    'students' => $students,
                    'links'          => $paginationData['links'],
                    'meta'           => $paginationData['meta'],
                ],
                trans('Obtenido con éxito')
            );
        }

        // SIN PAGINACIÓN
        $students = RegistrationResource::collection($query->get());
        // o, sin resource: $certifications = $query->get();

        return $this->sendResponse(
            [
                'students' => $students,
            ],
            trans('Obtenido con éxito')
        );
    }

    /**
     * Obtenemos los alumnos no matriculados
     * @param $id
     * @return mixed
     */
    public function getNotRegistered($id, Request $request) {
       $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

        return Student::getUnregistrated($id, $mainCompanyId)
            ->get();
    }

    /**
     * Creamos la matriculación
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request){
        try {
            DB::beginTransaction();
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            $student = Student::where('id', $request['student_id'])
                ->FilterMainCompany($mainCompanyId)
                ->first();

            if (!$student) {
                DB::rollBack();
                return response()->json([
                    'status' => 404,
                    'message' => 'Alumno no encontrado',
                ]);
            }
            // Obtenemos los datos de asesorías y colaboradores
            $advisor_id = null;
            $collaborator_id = null;
            $company = Company::where('id', $student['company_id'])
                ->FilterMainCompany($mainCompanyId)
                ->first();

            $advisor = null;
            if ($company->advisor_id) {
                $advisor = Advisor::where('id', $company->advisor_id)
                    ->FilterMainCompany($mainCompanyId)
                    ->first();
            }

            $advisor_percentage = null;
            $collaborator_percentage = null;
            if ($advisor) {
                if ($advisor['collaborator_id']){
                    $collaborator_id = $advisor['collaborator_id'];
                }
                if ($advisor['commission']){
                    $advisor_percentage = intval($advisor['commission']);
                }
            }
            if ($company){
                if ($company['advisor_id']){
                    $advisor_id = $company['advisor_id'];
                }
                if ($company['collaborator_id']){
                    $collaborator_id = $company['collaborator_id'];
                }
            }
            if ($collaborator_id){
                $user = User::where('id', $collaborator_id)
                    ->FilterMainCompany($mainCompanyId)
                    ->first();

                if ($user){
                    $collaborator_percentage = $user['commission'];
                }
            }
            // Vemos si ya existe una factura sino creamos otro
            $bill = Bill::where('course_id', $request->course_id)
                ->where('company_id', $company->id)
                ->where('is_bonus', $request->is_bonus)
                ->FilterMainCompany($mainCompanyId)
                ->first();

            $billData = [
                'course_id' => $request['course_id'],
                'company_id' => $student['company_id'],
                'is_bonus' => $request['is_bonus'],
                'price' => $request['price'],
                'student_id' => $student['id'],
                'advisor_id' => $advisor_id,
                'collaborator_id' => $collaborator_id,
                'company_name' => $company['name'],
                'main_company_id' => $mainCompanyId,
            ];

            if ($bill && $company['name'] != 'SIN EMPRESA'){
                $bill->updateBillingRegistrations($billData);
            } else {
                $bill = Bill::createBillingRegistrations($billData);
            }

            // Vemos si existe la rentabilidad sino creamos otra
            $profitabilityData =[
                'course_id' =>$request['course_id'],
                'company_id' => $student['company_id'],
                'student_id' => $student['id'],
                'price' => $request['price'],
                'total' => $request['price'],
                'advisor_percentage' => $advisor_percentage,
                'collaborator_percentage' => $collaborator_percentage,
                'is_bonus' => $request['is_bonus'],
                'main_company_id' => $mainCompanyId,
            ];
            if ($request->is_bonus) {
                $profitabilityData['student_id'] = null;
                $profitability = Profitability::select('profitabilities.*')->leftjoin('registrations', 'registrations.profitability_id', '=', 'profitabilities.id')
                    ->where('profitabilities.course_id', $request->course_id)
                    ->where('profitabilities.company_id', $request->company_id)
                    ->where('registrations.is_bonus', $request->is_bonus)
                    ->FilterMainCompany($mainCompanyId)
                    ->first();

                if ($profitability) {
                    $profitability->updateRegistration($profitabilityData);
                } else {
                    $profitability = Profitability::createWithService($profitabilityData);
                }
            } else {
                $profitability = Profitability::createWithService($profitabilityData);
            }

            // Creamos la matriculación junto con los datos de tareas y seguimiento
            $data = [
                'course_id' => $request['course_id'],
                'company_id' => $student['company_id'],
                'student_id' => $student['id'],
                'advisor_id' => $advisor_id,
                'collaborator_id' => $collaborator_id,
                'price' => $request['price'],
                'profitability_id' => $profitability['id'],
                'is_bonus' => $request['is_bonus'],
                'billing_id' => $bill->id,
                'main_company_id' => $mainCompanyId,
            ];
            $registration = Registration::createWithService($data);

            $student['registration_id'] = $registration->id;

        } catch (\Exception $e){
            DB::rollBack();
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
        DB::commit();
        return $this->sendResponse(
            [
                'registration' => $registration,
                'student' => $student
            ],
            trans('Creado con éxito')
        );
    }

    /**
     * Obtenemos la matriculación
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRegistration($id, Request $request){
       $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

        $registration = Registration::where('id', $id)
            ->FilterMainCompany($mainCompanyId)
            ->first();

            if ($registration) {
            Log::info($registration);
            return $this->sendResponse(
                    [
                        'registration' => $registration,
                    ],
                    trans('Obtenido con éxito')
                );
        }
        return response()->json([
            'status' => 400,
            'message' => 'Matriculación no existe'
        ]);
    }

    /**
     * Eliminamos la matriculación
     * @param $id
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function destroy($id, Request $request){
        if ($id) {
            try {
               $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

                $registration = Registration::where('id', $id)
                    ->FilterMainCompany($mainCompanyId)
                    ->first();
                if (!$registration) {
                    return response()->json([
                        'status' => 404,
                        'message' => 'Matriculación no existe'
                    ]);
                }

                $student = Student::where('id', $registration['student_id'])
                    ->FilterMainCompany($mainCompanyId)
                    ->first();

                $registration->destroyRegistration();

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
    /**
     * Obtiene todos los registros (registrations).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllRegistrations(Request $request) {
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            $registrations = Registration::with('course')
                ->FilterMainCompany($mainCompanyId)
                ->get();

            return response()->json([
                'status' => 200,
                'registrations' => $registrations
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }
    /**
     * Update a registration
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            $registration = Registration::where('id', $id)
            ->FilterMainCompany($mainCompanyId)
            ->first();

            $registration->update($request->all());


            $registration->load('company');


            return $this->sendResponse(
                [
                    'student' => $registration
                ],
                trans('Guardado con éxito')
            );
        } catch (\Exception $e) {
            Log::error('Error updating registration: ' . $e->getMessage());
            return response()->json([
                'status' => 500,
                'message' => 'Error updating registration'
            ], 500);
        }
    }
}
