<?php

namespace App\Http\Controllers;

use App\Http\Resources\Teacher as TeacherResource;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TeacherController extends Controller
{
    public function index() {

        return view('teachers/list');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('teachers/createTeacher');
    }

    public function store(Request $request){

        $validator  = Validator::make($request->all(), [
            'nombre' => 'required',
            'apellidos' => 'required',
            'dni' => 'unique:teachers',
            'user' => 'unique:teachers',
            'email' => 'unique:teachers',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $teacher = Teacher::create([
            'name' => $request['nombre'],
            'surname' => $request['apellidos'],
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

    public function editTeacher($id){

        $teacher = Teacher::find($id);

        return view('teachers/editTeacher', compact('teacher'));
    }

    public function updateTeacher(Request $request) {

        $validator  = Validator::make($request->all(), [
            'nombre' => 'required',
            'apellidos' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $teacher = Teacher::find($request['id']);

        $teacher->update([
            'name' => $request['nombre'],
            'surname' => $request['apellidos'],
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

    public function destroy(Request $request){

        $teacher = Teacher::find($request['id']);

        $teacher->delete();

        return response()->json([
            'status' => 200,
        ]);
    }

    public function restTeachers(Request $request){
        $teachers = Teacher::all();
        $data = [];

        foreach($teachers as $teacher) {
            $info = [
                'name' => $teacher['name'].' '.$teacher['surname'],
                'dni' => $teacher['dni'],
                'email' => $teacher['email'],
                'telephone' => $teacher['telephone'],
                'accions' => '<a class="btn btn-success btn-sm" href="teachers/edit_teacher/'.$teacher['id'].'"><i class="far fa-edit"></i></a> <a class="btn btn-danger btn-sm" id="deleteTeacher" data-id="'.$teacher['id'].'"><i class="far fa-trash-alt"></i></a>'
            ];
            array_push($data, $info);
        }
        return response()->json(['data' => $data]);
    }
}
