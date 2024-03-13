<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8'
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $user = new User([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password'))
        ]);
        if ($user->save()) {
            return response()->json([
                "msg" => "Data berhasil disimpan",
            ], 200);
        } else {
            return response()->json([
                "msg" => "Terjadi kesalahan saat menyimpan data"
            ], 500);
        }
    }
    public function update(Request $request)
    {
        $user = User::where('id', $request->get("id"))->first();
        $user->id = $request->get('id');
        $user->name = $request->get('name');
        $user->email = $request->get('email');
        $user->password = $request->get('password');
        if ($user->save()) {
            return response()->json([
                "msg" => "Akun dengan berhasil di update"
            ], 200);
        } else {
            return response()->json([
                "msg" => "Terjadi kesalahan saat mengupdate data"
            ], 500);
        }
    }
    public function delete(Request $request)
    {
        $user = User::where('id', $request->get('id'))->first();
        if ($user->delete()) {
            return response()->json([
                "msg" => "Akun berhasil di hapus"
            ], 200);
        } else {
            return response()->json([
                "msg" => "Terjadi kesalahan saat menghapus data"
            ], 500);
        }
    }
    public function login(Request $request)
    {
        $validators = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        if ($validators->fails()) {
            return response()->json([
                'msg' => 'Error validation',
                'error' => $validators->errors()
            ]);
        }

        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            // create token for user
            $user = User::where('id', Auth::user()->id)->first();
            $token = $user->createToken('user-token')->plainTextToken;

            return response()->json([
                "msg" => "Login berhasil",
                'data' => $user,
                'token' => $token
            ], 200);
        } else {
            return response()->json([
                "msg" => "Terjadi kesalahan saat logon"
            ], 500);
        }
    }

    public function logout(Request $req) {
        $req->user()->currentAccessToken()->delete();
    
        return response()->json([
            'msg' => 'Berhasil Logout'
        ]);
    }
}

