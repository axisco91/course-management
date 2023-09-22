<?php

namespace App\Services;

use App\Helpers\GeneralHelpers;
use App\Models\Bill;
use App\Models\Chore;
use App\Models\Profitability;
use App\Models\Registration;
use App\Models\Tracing;

class RegistrationService
{
    private $tracingService;
    private $choreService;

    public function __construct(TracingService $tracingService, ChoreService $choreService)
    {
        $this->tracingService = $tracingService;
        $this->choreService = $choreService;
    }

    /**
     * Función para matricular
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        $trainingData = [
            'course_id' => $data['course_id'],
            'company_id' => $data['company_id'],
            'student_id' => $data['student_id']
        ];
        $tracing = $this->tracingService->create($trainingData);

        $choreData = [
            'course_id' => $data['course_id'],
            'company_id' => $data['company_id'],
            'student_id' => $data['student_id']
        ];
        $chore = $this->choreService->create($choreData);

        return Registration::create([
            'course_id' => $data['course_id'],
            'company_id' => $data['company_id'],
            'student_id' => $data['student_id'],
            'billing_id' => isset($data['billing_id']) ? $data['billing_id'] : null,
            'tracing_id' => $tracing->id,
            'chore_id' => $chore->id,
            'price' => $data['price'],
            'profitability_id' => $data['profitability_id'],
            'is_bonus' => $data['is_bonus']
        ]);
    }

    /**
     * Función para editar matriculación
     */
    public function update(Student $student, array $data) {
        $student->update([
            'name' => $data['name'],
            'surname' => $data['surname'],
            'dni' => $data['dni'],
            'telephone' => $data['telephone'],
            'email' => $data['email'],
            'company_id' => $data['company_id'],
            'user' => $data['user'],
            'password' => $data['password'],
            'date_of_birth' => $data['date_of_birth'] ? Carbon::createFromFormat('d-m-Y', $data['date_of_birth'])->format('Y-m-d') : null,
            'level_study_id' => $data['level_study_id'],
            'social_security_number' => $data['social_security_number'] ? $data['social_security_number'] : null,
            'c_quote' => $data['c_quote'] ? $data['c_quote'] : null,
            'quote_group_id' => $data['quote_group_id'] ? $data['quote_group_id'] : null,
            'professional_category_id' => $data['professional_category_id'] ? $data['professional_category_id'] : null,
            'annual_gross_salary' => $data['annual_gross_salary'] ? $data['annual_gross_salary'] : null,
            'annual_hours' => $data['annual_hours'] ? $data['annual_hours'] : null,
            'hourly_cost_worker_gross' => $data['hourly_cost_worker_gross'] ? $data['hourly_cost_worker_gross'] : null,
            'direction' => $data['direction'] ? $data['direction'] : null,
            'post_code' => $data['post_code'] ? $data['post_code'] : null,
            'province_id' => $data['province_id'] ? $data['province_id'] : null,
            'population' => $data['population'] ? $data['population'] : null,
            'observation' => $data['observation'] ? $data['observation'] : null,
            'iban' => $data['iban'] ? $data['iban'] : null,
            'disabled' => $data['disabled'],
            'active' => $data['active']
        ]);
        return $student;
    }

    public function destroy(Registration $registration) {
        $bill = Bill::where('course_id',$registration['course_id'])
            ->where('company_id', $registration['company_id'])
            ->where('is_bonus', $registration['is_bonus'])->first();
        if ($bill){
            if ($bill['number_students']-1 == 0) {
                $bill->delete();
            } else {
                $bill->update([
                    'number_students' => $bill['number_students']-1,
                    'billing' => $bill['billing'] - $registration['price']
                ]);
            }
        }
        $tracing = Tracing::find($registration['tracing_id']);
        if ($tracing){
            $tracing->delete();
        }
        $chore = Chore::find($registration['chore_id']);
        if ($chore){
            $chore->delete();
        }
        $profitability = Profitability::find($registration['profitability_id']);
        if ($profitability){
            if ($registration['is_bonus'] == 1){
                if ($profitability['number_students'] > 1){
                    $price = $profitability['price']-$registration['price'];
                    if ($profitability['advisor_percentage'] && $profitability['price']){
                        $advisor_commission = ($profitability['advisor_percentage'] / 100) * $price;
                    }
                    if ($profitability['collaborator_percentage'] && $profitability['price']){
                        $collaborator_commission = ($profitability['advisor_percentage'] / 100) * $price;
                    }
                    $prices = Profitability::getCalculateBenefits(GeneralHelpers::convertComa($price),
                        GeneralHelpers::convertComa($profitability['teacher']),
                        GeneralHelpers::convertComa($profitability['management']),
                        GeneralHelpers::convertComa($profitability['nebrija_title']),
                        GeneralHelpers::convertComa($profitability['discount']),
                        $collaborator_commission, $advisor_commission);
                    $profitability->update([
                        'number_students' => $profitability['number_students']-1,
                        'price' => $price,
                        'total' => $prices['total_cost'],
                        'benefits' => $prices['benefits'],
                        'advisor_commission' => $advisor_commission,
                        'collaborator_commission' => $collaborator_commission
                    ]);
                } else{
                    $profitability->delete();
                }
            } else{
                $profitability->delete();
            }
        }
        $registration->delete();
    }
}
