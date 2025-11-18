<?php

namespace App\Http\Controllers\Api;
use App\Helpers\GeneralHelpers;
use App\Models\Chore;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChoreController extends BaseController
{

    /**
     * Obtenemos tareas
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request) {
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
            $start = \Illuminate\Support\Carbon::now();
            $number_days = 3;
            if ($start->dayOfWeek >= 3)
                $number_days = 5;

            $chores = Chore::chore($mainCompanyId);
            $user = User::find(Auth::id());
            if ($user->teacher_id) {
                $chores = $chores->where('courses.teacher_id', $user->teacher_id);
            }

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
                $chores = $chores
                    ->where('course_statuses.name', 'LIKE', $request->status);
            }
            if ($request->beginning) {
                $chores = $chores->where('courses.beginning', '>=', \Illuminate\Support\Carbon::parse($request->beginning));
            }
            if ($request->end) {
                $chores = $chores->where('courses.beginning', '<=', Carbon::parse($request->end));
            }

            if ($request->type) {
                $chores = $chores->where('course_types.name', 'LIKE', $request->type);
            }

            $chores = $chores->orderBy('chores.id', 'desc')
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

                if ($chore->send_doc_status == 0) {
                    $chore['send_doc_status_name'] = 'Pendiente';
                } else if ($chore->send_doc_status == 1) {
                    $chore['send_doc_status_name'] = 'Realizada';
                } else if ($chore->send_doc_status == 2) {
                    $chore['send_doc_status_name'] = 'No procede';
                }

                if ($chore->tutor_guide_status == 0) {
                    $chore['tutor_guide_status_name'] = 'Pendiente';
                } else if ($chore->tutor_guide_status == 1) {
                    $chore['tutor_guide_status_name'] = 'Realizada';
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
    public function show($id, Request $request) {
       $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
        $chore = Chore::chore($mainCompanyId)
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

            if ($chore->send_doc_status == 0) {
                $chore['send_doc_status_name'] = 'Pendiente';
            } else if ($chore->send_doc_status == 1) {
                $chore['send_doc_status_name'] = 'Realizada';
            } else if ($chore->send_doc_status == 2) {
                $chore['send_doc_status_name'] = 'No procede';
            }

            if ($chore->tutor_guide_status == 0) {
                $chore['tutor_guide_status_name'] = 'Pendiente';
            } else if ($chore->tutor_guide_status == 1) {
                $chore['tutor_guide_status_name'] = 'Realizada';
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

    /**
     * Editamos tarea
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function update($id, Request $request){
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
            $chore = Chore::where('id', $id)
                ->FilterMainCompany($mainCompanyId)
                ->first();
            if ($chore) {
                $data = $request->all();
                $element = $chore->updateWithService($data);
                $chore = Chore::chore($mainCompanyId)
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
                return response()->json([
                    'status' => 404,
                    'message' => 'Tarea no encontrada'
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
    public function destroy($id, Request $request){
        if ($id) {
            try {
               $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

                $chore = Chore::where('id', $id)
                    ->FilterMainCompany($mainCompanyId)
                    ->first();

                if (!$chore) {
                    return response()->json([
                        'status' => 404,
                        'message' => 'Tarea no encontrada'
                    ]);
                }
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
}
