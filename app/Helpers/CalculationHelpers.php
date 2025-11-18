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

    public static function timeStringToDecimal($timeString)
    {
        // Check if input is already in decimal format like 23.34
        if (preg_match('/^\d+\.\d{1,2}$/', $timeString)) {
            return $timeString;
        }

        $hours = 0;
        $minutes = 0;
        $seconds = 0;

        // Updated regex: allow optional spaces (using \s*)
        if (preg_match('/(\d+)\s*h/', $timeString, $matches)) {
            $hours = (int)$matches[1];
        }

        if (preg_match('/(\d+)\s*m/', $timeString, $matches)) {
            $minutes = (int)$matches[1];
        }

        if (preg_match('/(\d+)\s*s/', $timeString, $matches)) {
            $seconds = (int)$matches[1];
        }

        // Convert seconds to minutes fraction
        $minutes += $seconds / 60;

        // Format to XX.MM (minutes as two digits)
        $decimal = $hours + ($minutes / 100);

        return number_format($decimal, 2, '.', '');
    }

}
