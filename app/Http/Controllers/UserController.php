<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $querry = User::query();

        if($request->status){
            $querry->where('status', $request->status);
        }

        if($request->role){
            $querry->where('role', $request->role);
        }

        return response()->json(['data' => $querry->get()]);
    }

    public function show($id)
    {
        $user = User::find($id);
        if(!$user){
            return response()->json(['message' => 'User Tidak Ditemukan'], 404);
        }

        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if(!$user){
            return response()->json(['message' => 'User Tidak Ditemukan'], 404);
        }

        $request->validate([
            'nama'      => 'required|string',
            'email'     => 'required|email|unique:users,email,' . $id . ',id_users',
            'no_hp'     => 'nullable|string',
            'pekerjaan' => 'nullable|string',
            'provinsi'  => 'nullable|string',
            'kabupaten' => 'nullable|string',
            'kecamatan' => 'nullable|string',
            'alamat'    => 'nullable|string',
        ]);

        $user->update([
            'nama'      => $request->nama,
            'email'     => $request->email,
            'no_hp'     => $request->no_hp,
            'pekerjaan' => $request->pekerjaan,
            'provinsi'  => $request->provinsi,
            'kabupaten' => $request->kabupaten,
            'kecamatan' => $request->kecamatan,
            'alamat'    => $request->alamat,
        ]);

        return response()->json([
            'message' => 'Data user berhasil diperbarui',
            'data'    => $user
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }

        $request->validate([
            'status' => 'required|in:aktif,blokir',
        ]);

        $user->update(['status' => $request->status]);

        return response()->json([
            'message' => 'Status User Berhasil Diperbarui',
            'data' => $user
        ]);
    }

    public function destroy($id)
    {
        $user = User::find($id);
        if(!$user){
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'User Berhasil Dihapus']);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'nama'      => 'required|string',
            'email'     => 'required|email|unique:users,email,' . $user->id_users . ',id_users',
            'no_hp'     => 'nullable|string',
            'pekerjaan' => 'nullable|string',
            'provinsi'  => 'nullable|string',
            'kabupaten' => 'nullable|string',
            'kecamatan' => 'nullable|string',
            'alamat'    => 'nullable|string',
        ]);

        $user->update([
            'nama'      => $request->nama,
            'email'     => $request->email,
            'no_hp'     => $request->no_hp,
            'pekerjaan' => $request->pekerjaan,
            'provinsi'  => $request->provinsi,
            'kabupaten' => $request->kabupaten,
            'kecamatan' => $request->kecamatan,
            'alamat'    => $request->alamat,
         ]);

         return response()->json([
            'message' => 'Profile Telah Diperbarui',
            'data' => $user
         ]);
    }

    public function updateFoto(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'foto' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $file = $request->file('foto');
        $filename = time() . '-' . $file->getClientOriginalName();
        $file->storeAs('foto', $filename, 'public');

        $user->update([
            'foto' => url('storage/foto/' . $filename),
        ]);

        return response()->json([
            'message' => 'Foto profil berhasil diperbarui',
            'data'    => $user
        ]);
    }

}
