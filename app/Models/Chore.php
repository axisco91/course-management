<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Chore extends Model
{
	use HasFactory;

    public $timestamps = true;

    protected $fillable = ['course_id',
        'company_id',
        'student_id',
        'membership_tab_status',
        'membership_tab_date',
        'economic_proposal_status',
        'economic_proposal_date',
        'student_tab_status',
        'student_tab_date',
        'welcome_guid_status',
        'welcome_guid_date',
        'registration_status',
        'registration_date',
        'diploma_status',
        'diploma_status_date',
        'start_communication_status',
        'start_communication_date',
        'close_communication_status',
        'close_communication_date',
        'invoiced_status',
        'invoiced_date',
        'bonus_sent_status',
        'bonus_sent_date',
        'quarter_date_sent',
        'half_date_sent',
        'three_quarters_date_sent',
        'final_date_sent'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function company()
    {
        return $this->hasOne('App\Models\Company', 'id', 'company_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function course()
    {
        return $this->hasOne('App\Models\Course', 'id', 'course_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function registrations()
    {
        return $this->hasMany('App\Models\Registration', 'chore_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function student()
    {
        return $this->hasOne('App\Models\Student', 'id', 'student_id');
    }

    public static function getChores(){
        $start = Carbon::now();
        $number_days = 3;
        if ($start->dayOfWeek >= 3)
            $number_days = 5;
        $start = $start->addDays($number_days);
        $chores = Chore::select('chores.*', DB::raw("CONCAT(training_actions.formative_action,' / ', courses.group, ' ', training_actions.name) as course"),
            'companies.name as company', 'students.name as student_name',
            'students.surname as student_surname', 'course_statuses.name as status', 'courses.group as course_group',
            DB::raw("CONCAT(students.name,' ', students.surname) as student"),
            'courses.beginning as beginning',
            'courses.end as end')
            ->leftjoin('courses', 'courses.id', '=', 'chores.course_id')
            ->leftjoin('course_statuses', 'course_statuses.id', '=', 'courses.course_status_id')
            ->leftjoin('companies', 'companies.id', '=', 'chores.company_id')
            ->leftjoin('students', 'students.id', '=', 'chores.student_id')
            ->leftjoin('training_actions', 'training_actions.id', '=', 'courses.training_action_id')
            ->orderBy('chores.id', 'desc')->get();

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
    }

    public static function getChoresSendWelcome(){
        $chores = Chore::select('chores.*', 'courses.name as course', 'companies.name as company', 'students.name as student_name',
            'courses.beginning',
            'students.surname as student_surname', 'course_statuses.name as status', 'courses.group as course_group')
            ->leftjoin('courses', 'courses.id', '=', 'chores.course_id')
            ->leftjoin('course_statuses', 'course_statuses.id', '=', 'courses.course_status_id')
            ->leftjoin('companies', 'companies.id', '=', 'chores.company_id')
            ->leftjoin('students', 'students.id', '=', 'chores.student_id')
            ->where('welcome_guid_status', 0)
            ->get();

        foreach ($chores as $chore){
            $chore['student'] = $chore['student_name'].' '.$chore['student_surname'];
        }

        return $chores;
    }

    public static function createChore($data){
        $chore = Chore::create([
            'course_id' => $data['course_id'],
            'company_id' => $data['company_id'],
            'student_id' => $data['student_id']
        ]);
        return $chore;
    }

    public static function updateChore($id, $data){
        if ($id) {
            $chore = Chore::find($id);
            $registration = Registration::where('chore_id', $id)->first();
/*
            if ($registration){
                if ($registration->billing_id){
                    Billing::updateStartCommunicationDate($registration->billing_id, $data['start_communication_date'], $data['start_communication_status']);
                    Billing::updateCloseCommunicationDate($registration->billing_id, $data['close_communication_date'], $data['close_communication_status']);
                    Billing::updateInvicedDate($registration->billing_id, $data['invoiced_date'], $data['invoiced_status']);
                }
            }*/

            $chore->update([
                'membership_tab_status' => $data['membership_tab_status'],
                'membership_tab_date' => $data['membership_tab_date'] != 'null' ? Carbon::createFromFormat('d-m-Y', $data['membership_tab_date'])->format('Y-m-d') : null,
                'economic_proposal_status' => $data['economic_proposal_status'],
                'economic_proposal_date' => $data['economic_proposal_date'] != 'null' ? Carbon::createFromFormat('d-m-Y', $data['economic_proposal_date'])->format('Y-m-d') : null,
                'student_tab_status' => $data['student_tab_status'],
                'student_tab_date' => $data['student_tab_date'] != 'null' ? Carbon::createFromFormat('d-m-Y', $data['student_tab_date'])->format('Y-m-d') : null,
                'welcome_guid_status' => $data['welcome_guid_status'],
                'welcome_guid_date' => $data['welcome_guid_date'] != 'null' ? Carbon::createFromFormat('d-m-Y', $data['welcome_guid_date'])->format('Y-m-d') : null,
                'registration_status' => $data['registration_status'],
                'registration_date' => $data['registration_date'] != 'null' ? Carbon::createFromFormat('d-m-Y', $data['registration_date'])->format('Y-m-d') : null,
                'diploma_status' => $data['diploma_status'],
                'diploma_status_date' => $data['diploma_status_date'] != 'null' ? Carbon::createFromFormat('d-m-Y', $data['diploma_status_date'])->format('Y-m-d') : null,
                'start_communication_status' => $data['start_communication_status'],
                'start_communication_date' => $data['start_communication_date'] != 'null' ? Carbon::createFromFormat('d-m-Y', $data['start_communication_date'])->format('Y-m-d') : null,
                'close_communication_status' => $data['close_communication_status'],
                'close_communication_date' => $data['close_communication_date'] != 'null' ? Carbon::createFromFormat('d-m-Y', $data['close_communication_date'])->format('Y-m-d') : null,
                'invoiced_status' => $data['invoiced_status'],
                'invoiced_date' => $data['invoiced_date'] != 'null' ? Carbon::createFromFormat('d-m-Y', $data['invoiced_date'])->format('Y-m-d') : null,
                'bonus_sent_status' => $data['bonus_sent_status'],
                'bonus_sent_date' => $data['bonus_sent_date'] != 'null' ? Carbon::createFromFormat('d-m-Y', $data['bonus_sent_date'])->format('Y-m-d') : null
            ]);

            return $chore;
        }
    }

    public static function billingDateChore($id, $date, $status){
        $registrations = Registration::billingRegistration($id);
        foreach ($registrations as $registration){
            $chore = Chore::find($registration->chore_id);
            $chore->update([
                'bonus_sent_status' => $status,
                'bonus_sent_date' => $date ? Carbon::createFromFormat('d-m-Y', $date)->format('Y-m-d') : null,
                'invoiced_status' => $status,
                'invoiced_date' => $date ? Carbon::createFromFormat('d-m-Y', $date)->format('Y-m-d') : null
                ]);
        }
        return true;
    }

    public static function updateCommunicationStartDate($id, $date,$status){
        $chore = Chore::find($id);
        if (empty($date)){
            $status = 0;
        }
        $chore->update([
            'start_communication_date' => empty($date) ? null : Carbon::createFromFormat('d-m-Y', $date)->format('Y-m-d'),
            'start_communication_status' => $status
        ]);
    }

    public static function updateCommunicationEndDate($id, $date,$status){
        $chore = Chore::find($id);
        if (empty($date)){
            $status = 0;
        }
        $chore->update([
            'close_communication_date' => empty($date) ? null : Carbon::createFromFormat('d-m-Y', $date)->format('Y-m-d'),
            'close_communication_status' => $status
        ]);
        return $chore;
    }

    public static function getChoreCSV($course = null, $company = null, $student = null, $status = null, $beginning = null, $end = null){
        $chores = Chore::select('chores.*', DB::raw("CONCAT(training_actions.formative_action,' / ', courses.group, ' ', training_actions.name) as course"),
            'companies.name as company', 'students.name as student_name',
            'students.surname as student_surname', 'course_statuses.name as status', 'courses.group as course_group',
            DB::raw("CONCAT(students.name,' ', students.surname) as student"),
            'courses.beginning as beginning',
            'courses.end as end')
            ->leftjoin('courses', 'courses.id', '=', 'chores.course_id')
            ->leftjoin('course_statuses', 'course_statuses.id', '=', 'courses.course_status_id')
            ->leftjoin('companies', 'companies.id', '=', 'chores.company_id')
            ->leftjoin('students', 'students.id', '=', 'chores.student_id')
            ->leftjoin('training_actions', 'training_actions.id', '=', 'courses.training_action_id');

        if ($course) {
            $chores = $chores->where('courses.id', $course);
        }
        if ($company) {
            $chores = $chores->where('companies.id', $company);
        }
        if ($student) {
            $chores = $chores->where('students.id', 'LIKE', $student);
        }
        if ($status) {
            $chores = $chores->where('courses.course_status_id', 'LIKE', $status);
        }
        if ($beginning) {
            $chores = $chores->where('courses.beginning', '>=', $beginning);
        }
        if ($end) {
            $chores = $chores->where('courses.beginning', '<=', $end);
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
    }

}
