<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sewa;
use App\Models\Hunian;

class SewaController extends Controller
{
    public function index()
    {
        $sewa = Sewa::with(['user', 'hunian'])->get();

        return response()->json(['data' => $sewa]);
    }

    public function show($id)
    {
        $sewa = Sewa::with(['user', 'hunian'])->find($id);

        if(!$sewa){
            return response()->json(['message' => 'Data Sewa Tidak Ditemukan!!'], 404);
        }

        return response()->json($sewa);
    }

    public function me(Request $request)
    {
        $sewa = Sewa::with(['hunian.biaya'])
            ->where('id_users', $request->user()->id_users)
            ->where('status', 'aktif')
            ->first();

        if(!$sewa){
            return response()->json(['message' => 'Data Sewa Tidak Ditemukan!!']);
        }

        return response()->json($sewa);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_hunian' => 'required|exists:hunian,id_hunian',
            'tgl_jatuhtempo' => 'required|date',
        ]);

        $hunian = Hunian::find($request->id_hunian);

        if($hunian->status_harian === 'full'){
            return response()->json(['message' => 'Kamar Sudah Full!!'], 400);
        }

        $existing = Sewa::where('id_users', $request->user()->id_users)->whereIn('status', ['aktif', 'pending'])->first();

        if($existing){
            return response()->json(['message' => 'Anda Sudah Memiliki Sewa Aktif Atau Pending'], 400);
        }

        $sewa = Sewa::create([
            'id_users' => $request->user()->id_users,
            'id_hunian' => $request->id_hunian,
            'tgl_jatuhtempo' => $request->tgl_jatuhtempo,
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Sewa Telah Diajukan Dalam Status Pending, Menunggu Persetujuan Admin',
            'data' => $sewa
        ], 201);

    }

    public function update(Request $request, $id)
    {
        $sewa = Sewa::find($id);
        if(!$sewa){
            return response()->json(['message' => 'Data Sewa Tidak Ditemukan'], 404);
        }

        $request->validate([
            'status' => 'required|in:aktif,nonaktif,pending',
        ]);

        if($request->status === 'aktif'){
            $sewa->hunian->update(['status_harian' => 'full']);
        }

        if($request->status ==='nonaktif'){
            $sewa->hunian->update(['status_harian' => 'kosong']);
        }

        $sewa->update(['status' => $request->status]);

        return response()->json([
            'message' => 'Data Sewa Berhasil Diperbarui',
            'data' => $sewa
        ]);
    }

    public function destroy($id)
    {
        $sewa = Sewa::find($id);

        if(!$sewa){
            return response()->json(['message' => 'Data Sewa Tidak Ditemukan!!'], 404);
        }

        $sewa->hunian->update(['status_harian' => 'kosong']);
        $sewa->delete();

        return response()->json(['message' => 'Data Sewa Berhasil Dihapus!!']);
    }
}
