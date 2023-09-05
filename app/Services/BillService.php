<?php

namespace App\Services;

use App\Helpers\CalculationHelpers;
use App\Helpers\GeneralHelpers;
use App\Models\Bill;
use App\Models\Chore;
use App\Models\Company;
use App\Models\Registration;
use Illuminate\Support\Carbon;

class BillService
{

    private $choreService;

    public function __construct(ChoreService $choreService)
    {
        $this->choreService = $choreService;
    }

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
                    'status' => $status
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
                    'status' => $status
                ];
                $this->updateCloseCommunicationDate($bill->id, $communicationData);
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
            'collaborator_id' => $data['collaborator_id']
        ]);
        if ($data['company_name'] == 'SIN EMPRESA'){
            $bill->update([
                'student_id' => $data['student_id']
            ]);
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
        $registrations = Registration::billingRegistration($bill->id)->get();
        foreach ($registrations as $registration) {
            $chore = Chore::find($registration->chore_id);
            $communicationData = [
                'date' => $data['date'],
                'status' => $data['status']
            ];
            $this->choreService->updateCommunicationStartDate($chore, $communicationData);
        }
    }

    /**
     * Actualizamos la fecha de fin de comunicación
     * @param $id
     * @param $date
     * @param $status
     * @return void
     */
    public function updateCloseCommunicationDate($id, $data){
        $bill = Bill::find($id);
        $bill->update([
            'communication_end_date' => $data['date'] ? Carbon::createFromFormat('d-m-Y', $data['date'])->format('Y-m-d') : null
        ]);
        $registrations = Registration::billingRegistration($id)->get();
        foreach ($registrations as $registration) {
            $chore = Chore::find($registration->chore_id);
            $communicationData = [
                'date' => $data['date'],
                'status' => $data['status']
            ];
            $this->choreService->updateCommunicationEndDate($chore, $communicationData);
        }
    }
}
