<?php

namespace App\Http\Controllers;

class CommandController extends Controller
{
    public function index() {

        $output = exec('../../ php artisan optimize:clear');
        var_dump($output);
        exit();
    }

}
