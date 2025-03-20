<?php // Code within app\Helpers\Helper.php

namespace App\Helpers;

class GeneralHelpers
{

    /**
     * Get the number passed and convert the coma to a dot.
     */
    public static function convertComa($number){
        $number = str_replace(',', '.', $number);

        return $number;
    }

    public static function seconds_to_human_readable($seconds) {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $remaining_seconds = $seconds % 60;

        $formatted_time = '';
        if ($hours > 0) {
            $formatted_time .= $hours . ' h ';
        }
        if ($minutes > 0) {
            $formatted_time .= $minutes . ' m ';
        }
        if ($remaining_seconds > 0 || $formatted_time === '') {
            $formatted_time .= $remaining_seconds . ' s';
        }
        return trim($formatted_time);
    }
}
