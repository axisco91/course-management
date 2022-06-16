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

}
