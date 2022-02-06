<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\CenterController;
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

Route::get('/', function () {
    return view('welcome');
});
Route::prefix('teachers')->group(function() {
    Route::controller(TeacherController::class)->group(function(){
        Route::get('', 'index');
        Route::get('rest', 'restTeachers');
        Route::get('create', 'create');
        Route::get('store', 'store');
        Route::get('update_teacher', 'updateTeacher');
        Route::get('edit_teacher/{id}', 'editTeacher');
        Route::get('delete', 'destroy');
    });
});

Route::prefix('centers')->group(function() {
    Route::controller(CenterController::class)->group(function(){
        Route::get('', 'index');
        Route::get('rest', 'restCenters');
        Route::get('create', 'create');
        Route::get('store', 'store');
        Route::get('update', 'update');
        Route::get('edit/{id}', 'edit');
        Route::get('delete', 'destroy');
    });
});
/*Route::get('teachers/rest', [TeacherController::class, 'restTeachers']);
Route::get('teachers/rest', [TeacherController::class, 'restTeachers']);
Route::get('teachers/create', [TeacherController::class, '']);
Route::resource('teachers', TeacherController::class);*/

