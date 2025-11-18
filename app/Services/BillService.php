<?php

namespace App\Services;

use App\Helpers\GeneralHelpers;
use App\Models\AdvisorCommission;
use App\Models\Bill;
use App\Models\Chore;
use App\Models\CommissionType;
use App\Models\Course;
use App\Models\CourseOrigin;
use App\Models\CourseType;
use App\Models\Liquidation;
use App\Models\Registration;
use App\Models\TrainingAction;
use App\Models\UserCommission;
use Illuminate\Support\Carbon;

class BillService
{

    /**
     * Función para crear una factura
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return Bill::create([
            'name' => $data['name'],
            'nif' => $data['nif'],
            'company_type_id' => $data['company_type_id'],
            'company_activity_id' => $data['company_activity_id'],
            'email' => $data['email'],
            'telephone' => $data['telephone'],
            'legal_representative' => $data['legal_representative'],
            'dni_legal_representative' => $data['dni_legal_representative'],
            'quote' => $data['quote'],
            'cnae_id' => $data['cnae_id'],
            'average_template' => $data['average_template'],
            'iban' => $data['iban'],
            'sepa' => $data['sepa'],
            'b2b' => $data['b2b'],
            'address' => $data['address'],
            'post_code' => $data['post_code'],
            'province_id' => $data['province_id'],
            'population' => $data['population'],
            'advisor_id' => $data['advisor_id'],
            'collaborator_id' => $data['collaborator_id'],
            'active' => $data['active'],
            'potential' => $data['potential'],
            'main_company_id' => $data['main_company_id'],
        ]);
    }

    /**
     * Función para editar una factura
     */
    public function update(Bill $bill, array $data) {
        $totalTrainingActivity = 0;
        if (isset($data['communication_start_date'])) {
            if ($bill->communication_start_date != $data['communication_start_date']){
                $status = 0;
                if ($data['communication_start_date']){
                    $status = 1;
                }
                $communicationData = [
                    'date' => $data['communication_start_date'],
                    'status' => $status,
                    'main_company_id' => $data['main_company_id'],
                ];
                $this->updateStartCommunicationDate($bill, $communicationData);
            }
        }
        if (isset($data['communication_end_date'])) {
            if ($bill->communication_end_date != $data['communication_end_date']){
                $status = 0;
                if ($data['communication_end_date']){
                    $status = 1;
                }
                $communicationData = [
                    'date' => $data['communication_end_date'],
                    'status' => $status,
                    'main_company_id' => $data['main_company_id'],
                ];
                $this->updateCloseCommunicationDate($bill, $communicationData);
            }
        }

        $bill->update([
            'number_students' => $data['number_students'],
            'billing' => $data['billing'] ? GeneralHelpers::convertComa($data['billing']) : 0,
            'bonus' => $data['bonus'] ? GeneralHelpers::convertComa($data['bonus']) : 0,
            'total_training_activity' => $data['bonus'] ? GeneralHelpers::convertComa($data['total_training_activity']) : 0,
            'expenses' => $data['expenses'] ? GeneralHelpers::convertComa($data['expenses']) : 0,
            'salary_costs' => $data['salary_costs'] ? GeneralHelpers::convertComa($data['salary_costs']) : 0,
            'payment_id' => $data['payment_id'] ? $data['payment_id'] : null,
            'communication_start_date' => $data['communication_start_date'] ? Carbon::createFromFormat('d-m-Y', $data['communication_start_date'])->format('Y-m-d') : null,
            'communication_end_date' => $data['communication_end_date'] ? Carbon::createFromFormat('d-m-Y', $data['communication_end_date'])->format('Y-m-d') : null,
            'billing_number' => $data['billing_number'],
            'billing_date' => $data['billing_date'] ? Carbon::createFromFormat('d-m-Y', $data['billing_date'])->format('Y-m-d') : null,
            'collection_date' => $data['collection_date'] ? Carbon::createFromFormat('d-m-Y', $data['collection_date'])->format('Y-m-d') : null,
            'bonus_status' => $data['bonus_status'],
            'observation' => $data['observation'],
            'is_bonus' => $data['is_bonus'],
            'advisor_id' => $data['advisor_id'],
            'collaborator_id' => $data['collaborator_id'],
            'only_organizing_entity' => $data['only_organizing_entity'],
            'invoiced' => $data['invoiced'],
            'company_bonus' => $data['company_bonus'],
            'charged' => $data['charged'],
            'remitted' => $data['remitted']
        ]);

        return $bill;
    }

    public function createBillingRegistrations(array $data){
        $total_training_activity = GeneralHelpers::convertComa($data['price']);
        $expenses = GeneralHelpers::convertComa($data['price'] - $total_training_activity);
        $bill = Bill::create([
            'course_id' => $data['course_id'],
            'company_id' => $data['company_id'],
            'number_students' => 1,
            'is_bonus' => $data['is_bonus'],
            'billing' => GeneralHelpers::convertComa($data['price']),
            'total_training_activity' => $total_training_activity,
            'expenses' => $expenses,
            'advisor_id' => $data['advisor_id'],
            'collaborator_id' => $data['collaborator_id'],
            'main_company_id' => $data['main_company_id']
        ]);
        if ($data['company_name'] == 'SIN EMPRESA'){
            $bill->update([
                'student_id' => $data['student_id']
            ]);
        }

        $course = Course::find($data['course_id']);

        // Para crear las comisiones primero tenemos que asegurar que tiene una asesoría
        if ($data['advisor_id']) {
            $advisorCommission = AdvisorCommission::where('commissionable_id', $bill->id)
                ->where('commissionable_type', 'App\Models\Bill')
                ->where('advisor_id', $data['advisor_id'])
                ->FilterMainCompany($data['main_company_id'])
                ->first();

            // Buscamos el curso de que pertenece esta matriculación
            $course = Course::where('id' , $data['course_id'])
                ->FilterMainCompany($data['main_company_id'])
                ->first();
            if ($course) {
                $commissionType = null;
                // Obtenemos la acción formativa para ver que origen tiene
                $trainingAction = TrainingAction::where('id', $course->training_action_id)
                    ->FilterMainCompany($data['main_company_id'])
                    ->first();

                $percentage = '';
                $amount = '';
                if ($trainingAction->course_origin_id) {
                    // Vemos si existe un tipo de comisión con el nombre de origen
                    $courseOrigin = CourseOrigin::find($trainingAction->course_origin_id);
                    $commissionType = CommissionType::where('name', $courseOrigin->name)
                        ->FilterMainCompany($data['main_company_id'])
                        ->first();
                }
                // Si no existe ya miramos el tipo de curso para crear la comisión
                if (!$commissionType) {
                    $courseType = CourseType::where('id', $course->course_type_id)
                        ->first();

                    $commissionType = CommissionType::where('name', $courseType->name)
                        ->FilterMainCompany($data['main_company_id'])
                        ->first();
                }
                if ($commissionType) {
                    $commissionData = [
                        'advisor_id' => $data['advisor_id'],
                        'course_id' => $data['course_id'],
                        'commissionable_id' => $bill->id,
                        'commissionable_type' => 'App\Models\Bill',
                        'commission_type_id' => $commissionType->id,
                        'percentage' => $commissionType->percentage,
                        'amount' => ($commissionType->percentage / 100) * $bill->billing,
                        'bill_amount' => $bill->billing,
                        'main_company_id' => $data['main_company_id'],
                    ];
                    if ($advisorCommission) {
                        $advisorCommission->updateCommission($advisorCommission, $commissionData);
                    } else {
                        $advisorCommission = AdvisorCommission::createCommission($commissionData);
                    }

                    $liquidation = Liquidation::GetAdvisorLiquidation($data['advisor_id'], $data['company_id'], $data['course_id'], $data['main_company_id'])
                        ->first();

                    $liquidationData = [
                        'company_id' => $data['company_id'],
                        'course_id' => $data['course_id'],
                        'beginning' => $course->beginning,
                        'end' => $course->end,
                        'price' => $data['price'],
                        'paid' => 0,
                        'commission_percent' => $advisorCommission->percentage,
                        'commission' => $advisorCommission->amount,
                        'paid_date' => null,
                        'invoice_date' => null,
                        'advisor_id' => $data['advisor_id'],
                        'bill_number' => '',
                        'status' => 1,
                        'main_company_id' => $data['main_company_id'],
                    ];
                    if ($liquidation) {
                        $liquidation->updateCommission($commissionData);
                    } else {
                        Liquidation::createWithService($liquidationData);
                    }
                }
            }
        }

        if ($data['collaborator_id']) {
            $userCommission = UserCommission::where('user_id', $data['collaborator_id'])
                ->where('course_id', $data['course_id'])
                ->where('main_company_id', $data['main_company_id'])
                ->first();

           if ($userCommission) {
               $liquidationData = [
                   'company_id' => $data['company_id'],
                   'course_id' => $data['course_id'],
                   'beginning' => $course->beginning,
                   'end' => $course->end,
                   'price' => $data['price'],
                   'paid' => 0,
                   'commission_percent' => $userCommission->percentage,
                   'commission' => $userCommission->amount,
                   'paid_date' => null,
                   'invoice_date' => null,
                   'bill_number' => '',
                   'status' => 1,
                   'main_company_id' => $data['main_company_id'],
               ];

               Liquidation::createWithService($liquidationData);
           }
        }

        return $bill;
    }

    public function updateBillingRegistrations(Bill $bill, array $data){
        $total_training_activity = GeneralHelpers::convertComa($data['price']) + $bill['billing'];
        $expenses = GeneralHelpers::convertComa($data['price'] - $total_training_activity);
        $bill->update([
            'number_students' => $bill['number_students']+1,
            'billing' => $data['price'] + $bill['billing'],
            'total_training_activity' => $total_training_activity,
            'expenses' => $expenses,
            'advisor_id' => $data['advisor_id'],
            'collaborator_id' => $data['collaborator_id']
        ]);
        if ($data['company_name'] == 'SIN EMPRESA'){
            $bill->update([
                'student_id' => $data['student_id']
            ]);
        }
        return $bill;
    }

    /**
     * Actualizamos la fecha de inicio de comunicación
     * @param Bill $bill
     * @param $data
     * @return void
     */
    public function updateStartCommunicationDate(Bill $bill, $data){
        $bill->update([
            'communication_start_date' => $data['date'] ? Carbon::createFromFormat('d-m-Y', $data['date'])->format('Y-m-d') : null,
        ]);
        $registrations = Registration::billingRegistration($bill->id, $data->main_company_id)
            ->get();
        foreach ($registrations as $registration) {
            $chore = Chore::where('id', $registration->chore_id)
                ->FilterMainCompany($bill->main_company_id)
                ->first();
            $communicationData = [
                'date' => $data['date'],
                'status' => $data['status']
            ];
            $chore->updateCommunicationStartDate($communicationData);
        }
    }

    /**
     * Actualizamos la fecha de fin de comunicación
     * @param $id
     * @param $date
     * @param $status
     * @return void
     */
    public function updateCloseCommunicationDate(Bill $bill, $data){
        $bill->update([
            'communication_end_date' => $data['date'] ? Carbon::createFromFormat('d-m-Y', $data['date'])->format('Y-m-d') : null
        ]);
        $registrations = Registration::billingRegistration($bill->id, $data['main_company_id'])->get();
        foreach ($registrations as $registration) {
            $chore = Chore::where('id', $registration->chore_id)
                ->FilterMainCompany($data['main_company_id'])
                ->first();

            $communicationData = [
                'date' => $data['date'],
                'status' => $data['status']
            ];
            $chore->updateCommunicationEndDate($communicationData);
        }
    }
}
