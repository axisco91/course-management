<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index() {

        return view('users.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('users/createUser');
    }

    public function store(Request $request){

        $validator  = Validator::make($request->all(), [
            'nombre' => 'required',
            'apellidos' => 'required',
            'username' => 'required|unique:users',
            'email' => 'required|unique:users',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $user = User::create([
            'name' => $request['nombre'],
            'surname' => $request['apellidos'],
            'username' => $request['username'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
        ]);

        return response()->json([
            'status' => 200,
            'user' => $user
        ]);
    }

    public function editUser($id){

        $user = User::find($id);

        return view('users/editUser', compact('user'));
    }

    public function updateUser(Request $request) {

        $validator  = Validator::make($request->all(), [
            'nombre' => 'required',
            'apellidos' => 'required',
            'username' => 'required',
            'correo' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $user = User::find($request['id']);

        $user->update([
            'id' => $request['id'],
            'name' => $request['nombre'],
            'surname' => $request['apellidos'],
            'username' => $request['username'],
            'email' => $request['correo'],
        ]);

        return response()->json([
            'status' => 200,
            'user' => $user
        ]);
    }

    public function destroy(Request $request){

        $user = User::find($request['id']);

        $user->delete();

        return response()->json([
            'status' => 200,
        ]);
    }

    public function restUsers(Request $request){
        $users = User::all();
        $data = [];

        foreach($users as $user) {
            $info = [
                'name' => $user['name'].' '.$user['surname'],
                'email' => $user['email'],
                'actions' => '<a class="btn btn-success btn-sm" href="users/edit/'.$user['id'].'"><i class="far fa-edit"></i></a> <a class="btn btn-danger btn-sm deleteUser" data-id="'.$user['id'].'"><i class="far fa-trash-alt"></i></a>'
            ];
            array_push($data, $info);
        }
        return response()->json(['data' => $data]);
    }
}
