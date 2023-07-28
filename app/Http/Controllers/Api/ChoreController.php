<?php

namespace App\Http\Controllers\Api;
use App\Models\Chore;
use App\Models\Course;
use App\Models\Student;
use App\Services\ChoreService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ChoreController extends BaseController
{
    private $choreService;

    public function __construct(ChoreService $choreService)
    {
        $this->choreService = $choreService;
    }

    /**
     * Obtenemos tareas
     * @return \Illuminate\Http\JsonResponse
     */
    public function getChores() {
        try {
            $start = \Illuminate\Support\Carbon::now();
            $number_days = 3;
            if ($start->dayOfWeek >= 3)
                $number_days = 5;
            $start = $start->addDays($number_days);
            $chores = Chore::chore()
                ->orderBy('chores.id', 'desc')
                ->get();

            foreach ($chores as $chore){
                if ($chore->membership_tab_status == 0) {
                    $chore['membership_tab_status_name'] = 'Pendiente';
                } else if ($chore->membership_tab_status == 1) {
                    $chore['membership_tab_status_name'] = 'Enviada';
                } else if ($chore->membership_tab_status == 2) {
                    $chore['membership_tab_status_name'] = 'Recibida';
                } else if ($chore->membership_tab_status == 3) {
                    $chore['membership_tab_status_name'] = 'No procede';
                }

                if ($chore->economic_proposal_status == 0) {
                    $chore['economic_proposal_status_name'] = 'Pendiente';
                } else if ($chore->economic_proposal_status == 1) {
                    $chore['economic_proposal_status_name'] = 'Enviada';
                } else if ($chore->economic_proposal_status == 2) {
                    $chore['economic_proposal_status_name'] = 'Recibida';
                }

                if ($chore->student_tab_status == 0) {
                    $chore['student_tab_status_name'] = 'Pendiente';
                } else if ($chore->student_tab_status == 1) {
                    $chore['student_tab_status_name'] = 'Enviada';
                } else if ($chore->student_tab_status == 2) {
                    $chore['student_tab_status_name'] = 'Recibida';
                }

                if ($chore->welcome_guid_status == 0) {
                    $chore['welcome_guid_status_name'] = 'Pendiente';
                } else if ($chore->welcome_guid_status == 1) {
                    $chore['welcome_guid_status_name'] = 'Realizada';
                }

                if ($chore->registration_status == 0) {
                    $chore['registration_status_name'] = 'Pendiente';
                } else if ($chore->registration_status == 1) {
                    $chore['registration_status_name'] = 'Realizada';
                }

                if ($chore->diploma_status == 0) {
                    $chore['diploma_status_name'] = 'Pendiente';
                } else if ($chore->diploma_status == 1) {
                    $chore['diploma_status_name'] = 'Realizada';
                } else if ($chore->diploma_status == 2) {
                    $chore['diploma_status_name'] = 'No procede';
                }

                if ($chore->start_communication_status == 0) {
                    $chore['start_communication_status_name'] = 'Pendiente';
                } else if ($chore->start_communication_status == 1) {
                    $chore['start_communication_status_name'] = 'Realizada';
                } else if ($chore->start_communication_status == 2) {
                    $chore['start_communication_status_name'] = 'No procede';
                }

                if ($chore->close_communication_status == 0) {
                    $chore['close_communication_status_name'] = 'Pendiente';
                } else if ($chore->close_communication_status == 1) {
                    $chore['close_communication_status_name'] = 'Realizada';
                } else if ($chore->close_communication_status == 2) {
                    $chore['close_communication_status_name'] = 'No procede';
                }

                if ($chore->invoiced_status == 0) {
                    $chore['invoiced_status_name'] = 'Pendiente';
                } else if ($chore->invoiced_status == 1) {
                    $chore['invoiced_status_name'] = 'Realizada';
                } else if ($chore->invoiced_status == 2) {
                    $chore['invoiced_status_name'] = 'No procede';
                }

                if ($chore->bonus_sent_status == 0) {
                    $chore['bonus_sent_status_name'] = 'Pendiente';
                } else if ($chore->bonus_sent_status == 1) {
                    $chore['bonus_sent_status_name'] = 'Realizada';
                } else if ($chore->bonus_sent_status == 2) {
                    $chore['bonus_sent_status_name'] = 'No procede';
                }
            }

            return $chores;
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Obtener tarea
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getChore($id){
        $chore = Chore::chore()
            ->where('chores.id', $id)
            ->first();
        if ($chore) {
            if ($chore->membership_tab_status == 0) {
                $chore['membership_tab_status_name'] = 'Pendiente';
            } else if ($chore->membership_tab_status == 1) {
                $chore['membership_tab_status_name'] = 'Enviada';
            } else if ($chore->membership_tab_status == 2) {
                $chore['membership_tab_status_name'] = 'Recibida';
            } else if ($chore->membership_tab_status == 3) {
                $chore['membership_tab_status_name'] = 'No procede';
            }

            if ($chore->economic_proposal_status == 0) {
                $chore['economic_proposal_status_name'] = 'Pendiente';
            } else if ($chore->economic_proposal_status == 1) {
                $chore['economic_proposal_status_name'] = 'Enviada';
            } else if ($chore->economic_proposal_status == 2) {
                $chore['economic_proposal_status_name'] = 'Recibida';
            }

            if ($chore->student_tab_status == 0) {
                $chore['student_tab_status_name'] = 'Pendiente';
            } else if ($chore->student_tab_status == 1) {
                $chore['student_tab_status_name'] = 'Enviada';
            } else if ($chore->student_tab_status == 2) {
                $chore['student_tab_status_name'] = 'Recibida';
            }

            if ($chore->welcome_guid_status == 0) {
                $chore['welcome_guid_status_name'] = 'Pendiente';
            } else if ($chore->welcome_guid_status == 1) {
                $chore['welcome_guid_status_name'] = 'Realizada';
            }

            if ($chore->registration_status == 0) {
                $chore['registration_status_name'] = 'Pendiente';
            } else if ($chore->registration_status == 1) {
                $chore['registration_status_name'] = 'Realizada';
            }

            if ($chore->diploma_status == 0) {
                $chore['diploma_status_name'] = 'Pendiente';
            } else if ($chore->diploma_status == 1) {
                $chore['diploma_status_name'] = 'Realizada';
            } else if ($chore->diploma_status == 2) {
                $chore['diploma_status_name'] = 'No procede';
            }

            if ($chore->start_communication_status == 0) {
                $chore['start_communication_status_name'] = 'Pendiente';
            } else if ($chore->start_communication_status == 1) {
                $chore['start_communication_status_name'] = 'Realizada';
            } else if ($chore->start_communication_status == 2) {
                $chore['start_communication_status_name'] = 'No procede';
            }

            if ($chore->close_communication_status == 0) {
                $chore['close_communication_status_name'] = 'Pendiente';
            } else if ($chore->close_communication_status == 1) {
                $chore['close_communication_status_name'] = 'Realizada';
            } else if ($chore->close_communication_status == 2) {
                $chore['close_communication_status_name'] = 'No procede';
            }

            if ($chore->invoiced_status == 0) {
                $chore['invoiced_status_name'] = 'Pendiente';
            } else if ($chore->invoiced_status == 1) {
                $chore['invoiced_status_name'] = 'Realizada';
            } else if ($chore->invoiced_status == 2) {
                $chore['invoiced_status_name'] = 'No procede';
            }

            if ($chore->bonus_sent_status == 0) {
                $chore['bonus_sent_status_name'] = 'Pendiente';
            } else if ($chore->bonus_sent_status == 1) {
                $chore['bonus_sent_status_name'] = 'Realizada';
            } else if ($chore->bonus_sent_status == 2) {
                $chore['bonus_sent_status_name'] = 'No procede';
            }
            return response()->json([
                'status' => 200,
                'chore' => $chore
            ]);
        }
        return response()->json([
            'status' => 400,
            'message' => 'Seguimiento no existe'
        ]);
    }

    public function create(Request $request){

    }

    /**
     * Editamos tarea
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function edit($id, Request $request){
        try {
            $chore = Chore::find($id);
            $data = $request->all();
            $element = $this->choreService->update($chore, $data);
            $chore = Chore::chore()
                ->where('chores.id', $element->id)
                ->first();
            if ($chore) {
                if ($chore->membership_tab_status == 0) {
                    $chore['membership_tab_status_name'] = 'Pendiente';
                } else if ($chore->membership_tab_status == 1) {
                    $chore['membership_tab_status_name'] = 'Enviada';
                } else if ($chore->membership_tab_status == 2) {
                    $chore['membership_tab_status_name'] = 'Recibida';
                } else if ($chore->membership_tab_status == 3) {
                    $chore['membership_tab_status_name'] = 'No procede';
                }

                if ($chore->economic_proposal_status == 0) {
                    $chore['economic_proposal_status_name'] = 'Pendiente';
                } else if ($chore->economic_proposal_status == 1) {
                    $chore['economic_proposal_status_name'] = 'Enviada';
                } else if ($chore->economic_proposal_status == 2) {
                    $chore['economic_proposal_status_name'] = 'Recibida';
                }

                if ($chore->student_tab_status == 0) {
                    $chore['student_tab_status_name'] = 'Pendiente';
                } else if ($chore->student_tab_status == 1) {
                    $chore['student_tab_status_name'] = 'Enviada';
                } else if ($chore->student_tab_status == 2) {
                    $chore['student_tab_status_name'] = 'Recibida';
                }

                if ($chore->welcome_guid_status == 0) {
                    $chore['welcome_guid_status_name'] = 'Pendiente';
                } else if ($chore->welcome_guid_status == 1) {
                    $chore['welcome_guid_status_name'] = 'Realizada';
                }

                if ($chore->registration_status == 0) {
                    $chore['registration_status_name'] = 'Pendiente';
                } else if ($chore->registration_status == 1) {
                    $chore['registration_status_name'] = 'Realizada';
                }

                if ($chore->diploma_status == 0) {
                    $chore['diploma_status_name'] = 'Pendiente';
                } else if ($chore->diploma_status == 1) {
                    $chore['diploma_status_name'] = 'Realizada';
                } else if ($chore->diploma_status == 2) {
                    $chore['diploma_status_name'] = 'No procede';
                }

                if ($chore->start_communication_status == 0) {
                    $chore['start_communication_status_name'] = 'Pendiente';
                } else if ($chore->start_communication_status == 1) {
                    $chore['start_communication_status_name'] = 'Realizada';
                } else if ($chore->start_communication_status == 2) {
                    $chore['start_communication_status_name'] = 'No procede';
                }

                if ($chore->close_communication_status == 0) {
                    $chore['close_communication_status_name'] = 'Pendiente';
                } else if ($chore->close_communication_status == 1) {
                    $chore['close_communication_status_name'] = 'Realizada';
                } else if ($chore->close_communication_status == 2) {
                    $chore['close_communication_status_name'] = 'No procede';
                }

                if ($chore->invoiced_status == 0) {
                    $chore['invoiced_status_name'] = 'Pendiente';
                } else if ($chore->invoiced_status == 1) {
                    $chore['invoiced_status_name'] = 'Realizada';
                } else if ($chore->invoiced_status == 2) {
                    $chore['invoiced_status_name'] = 'No procede';
                }

                if ($chore->bonus_sent_status == 0) {
                    $chore['bonus_sent_status_name'] = 'Pendiente';
                } else if ($chore->bonus_sent_status == 1) {
                    $chore['bonus_sent_status_name'] = 'Realizada';
                } else if ($chore->bonus_sent_status == 2) {
                    $chore['bonus_sent_status_name'] = 'No procede';
                }
                return response()->json([
                    'status' => 200,
                    'chore' => $chore
                ]);
            }
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Eliminamos tarea
     * @param $id
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function destroy($id){
        if ($id) {
            try {
                Chore::destroy($id);
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

    public function choresCSV(Request $request){
        try {
            $chores = Chore::chore();
            if ($request->course) {
                $chores = $chores->where('courses.id', $request->course);
            }
            if ($request->company) {
                $chores = $chores->where('companies.id', $request->company);
            }
            if ($request->student) {
                $chores = $chores->where('students.id', 'LIKE', $request->student);
            }
            if ($request->status) {
                $chores = $chores->where('courses.course_status_id', 'LIKE', $request->status);
            }
            if ($request->beginning) {
                $chores = $chores->where('courses.beginning', '>=', \Illuminate\Support\Carbon::parse($request->beginning));
            }
            if ($request->end) {
                $chores = $chores->where('courses.beginning', '<=', Carbon::parse($request->end));
            }

            $chores = $chores->orderBy('chores.id', 'desc')->get();

            $data = [];
            foreach ($chores as $chore) {
                $element = [
                    'Curso' => $chore['course'],
                    'Empresa' => $chore['company'],
                    'Alumno' => $chore['student'],
                    'Estado' => $chore['status'],
                    'Ficha Adhesión' => $chore['membership_tab_status'] == 0 ? 'Pendiente' : ($chore['membership_tab_status'] == 1 ? 'Enviado' : ($chore['membership_tab_status'] == 2 ? 'Recibido' : 'No procede')),
                    'Propuesta Económica' => $chore['economic_proposal_status'] == 0 ? 'Pendiente' : ($chore['economic_proposal_status'] == 1 ? 'Enviado' : 'Recibido'),
                    'Ficha Alumno' => $chore['student_tab_status'] == 0 ? 'Pendiente' : ($chore['student_tab_status'] == 1 ? 'Enviado' : 'Recibido'),
                    'Guia Bienvenida' => $chore['welcome_guid_status'] == 0 ? 'Pendiente' : ($chore['welcome_guid_status'] == 1 ? 'Enviado' : 'Recibido'),
                    'Matriculación' => $chore['registration_status'] == 0 ? 'Pendiente' : 'Realizada',
                    'Diploma' => $chore['diploma_status'] == 0 ? 'Pendiente' : ($chore['diploma_status'] == 1 ? 'Enviada' : 'No procede'),
                    'Comunicación Inicio' => $chore['start_communication_status'] == 0 ? 'Pendiente' : ($chore['start_communication_status'] == 1 ? 'Realizada' : 'No procede'),
                    'Comunicación Cierre' => $chore['close_communication_status'] == 0 ? 'Pendiente' : ($chore['close_communication_status'] == 1 ? 'Realizada' : 'No procede'),
                    'Facturado' => $chore['invoiced_status'] == 0 ? 'Pendiente' : ($chore['invoiced_status'] == 1 ? 'Realizada' : 'No procede'),
                    'Bonificacion Enviada' => $chore['bonus_sent_status'] == 0 ? 'Pendiente' : ($chore['bonus_sent_status'] == 1 ? 'Realizada' : 'No procede')
                ];
                $data[] = $element;
            }
            return $data;
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }
}
