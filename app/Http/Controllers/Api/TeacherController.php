<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Teacher;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Resources\Teacher as TeacherResource;

class TeacherController extends BaseController
{
    public function index() {
        $teachers = Teacher::all();

        return $this->sendResponse(TeacherResource::collection($teachers), 'Posts fetched.');
    }

    public function store(Request $request){

        $validator  = Validator::make($request->all(), [
           'name' => 'required',
            'surname' => 'required',
            'dni' => 'unique:teachers',
            'user' => 'unique:teachers'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $teacher = Teacher::create([
            'name' => $request['name'],
            'surname' => $request['surname'],
            'dni' => $request['dni'],
            'email' => $request['email'],
            'telephone' => $request['telephone'],
            'user' => $request['user'],
            'password' => $request['password'],
            'observations' => $request['observations']
        ]);

        return response()->json([
            'status' => 200,
            'teacher' => $teacher
        ]);
    }

    public function show($id){

    }

    public function edit($id){

        $teacher = Teacher::find($id);

        return $this->sendResponse(TeacherResource::collection($teacher), 'Posts fetched.');
    }

    public function update(Request $request) {
        $validator  = Validator::make($request->all(), [
            'name' => 'required',
            'surname' => 'required',
            'dni' => 'unique:teachers',
            'user' => 'unique:teachers'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $teacher = Teacher::find($request['id']);

        $teacher->update([
            'name' => $request['name'],
            'surname' => $request['surname'],
            'dni' => $request['dni'],
            'email' => $request['email'],
            'telephone' => $request['telephone'],
            'user' => $request['user'],
            'password' => $request['password'],
            'observations' => $request['observations']
        ]);

        return response()->json([
            'status' => 200,
            'teacher' => $teacher
        ]);
    }

    public function destroy(Teacher $teacher){

        $teacher->delete();

        return response()->json([
           'status' => 200,
        ]);

    }
}
