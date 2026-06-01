<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'nama' => 'required|string',
            'username' => 'required|string|unique:users',
            'email' => 'required|string|unique:users',
            'password' => 'required|min:8|confirmed',
            'no_hp' => 'nullable|string',
            'pekerjaan' => 'nullable|string',
            'provinsi' => 'nullable|string',
            'kabupaten' => 'nullable|string',
            'kecamatan' => 'nullable|string',
            'alamat' => 'nullable|string',
        ]);

        $user = User::create([
            'nama' => $request->nama,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'no_hp' => $request->no_hp,
            'pekerjaan' => $request->pekerjaan,
            'provinsi' => $request->provinsi,
            'kabupaten' => $request->kabupaten,
            'kecamatan' => $request->kecamatan,
            'alamat' => $request->alamat,
        ]);

        return response()->json([
            'message' => 'Registrasi berhasil',
            'user' => $user,
        ], 201);
    }    

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string'
        ]);

        $user = User::where('username', $request->username)->first();

        if(!$user || !Hash::check($request->password, $user->password)){
            return response()->json([
                'message' => 'Username atau password salah',
            ], 401);
        }

        if($user->status === 'blokir'){
            return response()->json([
                'message' => 'Akun diblokir, hubungi admin',
            ], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login Berhasil!!',
            'token' => $token,
            'user' => $user,
        ], 200);


    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout Berhasil!!',
        ], 200);
    }

    public function me(Request $request)
    {
        return response()->json($request->user());
    }

}
