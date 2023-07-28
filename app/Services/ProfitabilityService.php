<?php

namespace App\Services;


use App\Helpers\CalculationHelpers;
use App\Helpers\GeneralHelpers;
use App\Models\Profitability;

class ProfitabilityService
{
    /**
     * Función para crear la rentabilidad
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        $advisor_commission = null;
        $collaborator_commission = null;
        if ($data['advisor_percentage'] && $data['price']){
            $advisor_commission = ($data['advisor_percentage'] / 100) * $data['price'];
        }
        if ($data['collaborator_percentage'] && $data['price']){
            $collaborator_commission = ($data['advisor_percentage'] / 100) * $data['price'];
        }

        return Profitability::create([
            'course_id' =>$data['course_id'],
            'company_id' => $data['company_id'],
            'price' => $data['price'],
            'advisor_percentage' => $data['advisor_percentage'],
            'collaborator_percentage' => $data['collaborator_percentage'],
            'advisor_commission ' => $advisor_commission,
            'collaborator_commission ' => $collaborator_commission,
            'number_students' => 1
        ]);
    }

    /**
     * Función para editar la rentabilidad
     */
    public function update(Profitability $profitability, array $data) {
        $advisor_commission = 0;
        $collaborator_commission = 0;
        if ($data['advisor_percentage'] && $data['price']){
            $advisor_commission = ($data['advisor_percentage'] / 100) * $data['price'];
        }
        if ($data['collaborator_percentage'] && $data['price']){
            $collaborator_commission = ($data['collaborator_percentage'] / 100) * $data['price'];
        }
        $prices = CalculationHelpers::getCalculateBenefits(GeneralHelpers::convertComa($data['price']), GeneralHelpers::convertComa($data['teacher']),
            GeneralHelpers::convertComa($data['management']), GeneralHelpers::convertComa($data['nebrija_title']), GeneralHelpers::convertComa($data['discount']), $collaborator_commission, $advisor_commission);
        $profitability->update([
            'price' => GeneralHelpers::convertComa($data['price']),
            'license' => $data['license'],
            'teacher' => GeneralHelpers::convertComa($data['teacher']),
            'management' => GeneralHelpers::convertComa($data['management']),
            'nebrija_title' => GeneralHelpers::convertComa($data['nebrija_title']),
            'discount' => GeneralHelpers::convertComa($data['discount']),
            'collaborator_commission' => GeneralHelpers::convertComa($collaborator_commission),
            'advisor_commission' => GeneralHelpers::convertComa($advisor_commission),
            'advisor_percentage' => $data['advisor_percentage'] ? $data['advisor_percentage'] : 0,
            'collaborator_percentage' => $data['collaborator_percentage'] ? $data['collaborator_percentage'] : 0,
            'total' => $prices['total_cost'],
            'benefits' => $prices['benefits'],
            'observations' => $data['observations']
        ]);
        return $profitability;
    }

    /**
     * Función para editar la rentabilidad con la matriculación en el curso
     */
    public function updateRegistration(Profitability $profitability, array $data) {
        $advisor_commission = null;
        $collaborator_commission = null;
        $price = $profitability->price + GeneralHelpers::convertComa($data['price']);
        if ($profitability['advisor_percentage'] && $data['price']){
            $advisor_commission = ($profitability['advisor_percentage'] / 100) * $price;
        }
        if ($profitability['collaborator_percentage'] && $data['price']){
            $collaborator_commission = ($profitability['advisor_percentage'] / 100) * $price;
        }
        $prices = CalculationHelpers::getCalculateBenefits(GeneralHelpers::convertComa($price),
            GeneralHelpers::convertComa($profitability['teacher']),
            GeneralHelpers::convertComa($profitability['management']),
            GeneralHelpers::convertComa($profitability['nebrija_title']),
            GeneralHelpers::convertComa($profitability['discount']),
            $collaborator_commission, $advisor_commission);
        $profitability->update([
            'price' => $price,
            'advisor_percentage' => $data['advisor_percentage'],
            'collaborator_percentage' => $data['collaborator_percentage'],
            'advisor_commission ' => $advisor_commission,
            'collaborator_commission ' => $collaborator_commission,
            'total' => $prices['total_cost'],
            'benefits' => $prices['benefits'],
            'number_students' => $profitability['number_students']-1
        ]);
        return $profitability;
    }
}
