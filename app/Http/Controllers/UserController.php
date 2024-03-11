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
            'email' => 'required|email|unique',
            'password' => 'required|min:8'
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $user = new User([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => $request->get('password')
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
    public function login(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return response()->json([
                "msg" => "Login berhasil"
            ], 200);
        } else {
            return response()->json([
                "msg" => "Terjadi kesalahan saat logon"
            ], 500);
        }
    }
}
