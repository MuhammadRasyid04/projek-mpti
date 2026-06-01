<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hunian;

class HunianController extends Controller
{
    public function index()
    {
        $hunian = Hunian::with('biaya')->get();

        return response()->json(['data' => $hunian]);
    }

    public function show($id)
    {
        $hunian = Hunian::with('biaya')->find($id);

        if (!$hunian){
            return response()->json(['message' => 'Hunian tidak ditemukan!!'], 404);
        }

        return response()->json($hunian);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_hunian' => 'required|string',
            'gambar_hunian' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'deskripsi_hunian' => 'nullable|string',
            'status_harian' => 'required|in:kosong,full',
        ]);

        $gambar = null;

        if($request->hasFile('gambar_hunian')){
            $file = $request->file('gambar_hunian');
            $filename = time() . '-' . $file->getClientOriginalName();
            $file->storeAs('hunian', $filename, 'public');
            $gambar = url('storage/hunian/' . $filename);
        }

        $hunian = Hunian::create([
            'nama_hunian' => $request->nama_hunian,
            'gambar_hunian' => $gambar,
            'deskripsi_hunian' => $request->deskripsi_hunian,
            'status_harian' => $request->status_harian,
        ]);

        return response()->json([
            'message' => 'Hunian berhasil ditambahkan!!',
            'data' => $hunian
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $hunian = Hunian::find($id);

        if(!$hunian){
            return response()->json(['message' => 'Hunian Tidak Ditemukan!!'], 404);
        }

        $request->validate([
            'nama_hunian' => 'required|string',
            'gambar_hunian' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'deskripsi_hunian' => 'nullable|string',
            'status_harian' => 'required|in:kosong,full',
        ]);

        $gambar = $hunian->gambar_hunian;

        if($request->hasFile('gambar_hunian')){
            $file = $request->file('gambar_hunian');
            $filename = time() . '-' . $file->getClientOriginalName();
            $file->storeAs('hunian', $filename, 'public');
            $gambar = url('storage/hunian/' . $filename);
        }

        $hunian->update([
            'nama_hunian' => $request->nama_hunian,
            'gambar_hunian' => $gambar,
            'deskripsi_hunian' => $request->deskripsi_hunian,
            'status_harian' => $request->status_harian,
        ]);

        return response()->json([
            'message' => 'Hunian Berhasil Diperbarui!!',
            'data' => $hunian
        ]);
    }

    public function destroy($id)
    {
        $hunian = Hunian::find($id);

        if(!$hunian){
            return response()->json(['message' => 'Hunian Tidak Ditemukan!!'], 404);
        }

        $hunian->delete();

        return response()->json(['message' => 'Hunian Berhasil Dihapus!!']);
    }
}
