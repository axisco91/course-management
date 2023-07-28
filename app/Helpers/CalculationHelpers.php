<?php // Code within app\Helpers\Helper.php

namespace App\Helpers;

class CalculationHelpers
{

    /**
     * Calcular el total
     */
    public static function totalTrainingActivity($bonus){
        return round($bonus / 1.1, 2);
    }

    /**
     * Calcular los beneficios de la rentabilidad
     * @param $price
     * @param $teacher
     * @param $management
     * @param $nebrija_title
     * @param $discount
     * @param $collaborator_commission
     * @param $advisor_commission
     * @return array
     */
    public static function getCalculateBenefits($price, $teacher, $management, $nebrija_title, $discount, $collaborator_commission, $advisor_commission){
        $total_cost = $teacher + $management + $nebrija_title + $collaborator_commission + $advisor_commission;
        $benefits = $price - $teacher - $management - $nebrija_title - $discount - $collaborator_commission - $advisor_commission;

        return [
            'total_cost' => $total_cost,
            'benefits' => $benefits
        ];
    }
}
