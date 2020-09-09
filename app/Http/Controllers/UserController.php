<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    public function index(){
        return response()->json([
            'success' => true,
            'data' => User::all()->toArray()
        ], 200);
    }

    public function store(Request $request){
        
        $request->validate([
            'name' => 'required|min:5',
            'last_name' => 'required|min:5',
            'phone' => 'required|min:5',
            'email' => 'required|email|unique:users',
            'address' => 'required'
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->last_name = $request->last_name;
        $user->phone = $request->phone;
        $user->email = $request->email;
        $user->address = $request->address;

        if(!$user->save()) {
            return response()->json([
                'success' => false,
                'data' => 'Ha ocurrido un error al tratar de crear el usuario.'
            ], 500);
        }

        return response()->json([
            'success' => true,
            'data' => $user->toArray()
        ], 200)->withHeaders([
            'Location' => 'http://127.0.0.1:8000/user/'.$user->id,
            'Content-type' => 'application/json'
        ]);
    }

    public function show($id) {
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'success' => false,
                'data' => 'No se ha encontrado datos para el usuario con id: '. $id
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $user
        ], 200);
    }

    public function update(Request $request, $id) {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'data' => 'No se ha encontrado datos para el usuario con id: '. $id
            ], 404);
        }

        $user->name = $request->name;
        $user->last_name = $request->last_name;
        $user->phone = $request->phone;
        $user->email = $request->email;
        $user->address = $request->address;

        if ( !$user->save() ) {
            return response()->json([
                'success' => false,
                'data' => 'No se ha podido actualizar los datos del usuario.'
            ], 500);
        }

        return response()->json([
            'success' => true,
            'data' => $user
        ], 200);
    }

    public function destroy($id) {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'data' => 'No se ha encontrado datos para el usuario con id: '. $id
            ], 404);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'data' => 'Se ha eliminado correctamente el usuario'
        ], 200);
    }
}
