<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PracticeController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function() {

    $users = \App\Models\User::all();

    foreach ($users as $user) {
        $roles = $user->getRoleNames();
        echo implode(', ', $roles);
        foreach ($roles as $role){
            echo $role;
        }
    }

});

// clear cache
Route::get('/clear-cache', function() {
    Artisan::call('optimize:clear');
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    echo Artisan::output();
});

Route::get('/updateCoursesStatus', function() {
    ob_start();
    Artisan::call('updateCoursesStatus');
    ob_end_clean();
    echo Artisan::output();
});
