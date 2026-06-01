<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Biaya;
use App\Models\Hunian;

class BiayaController extends Controller
{
    public function show($id)
    {
        $hunian = Hunian::find($id);

        if(!$hunian){
            return response()->json(['message' => 'Hunian Tidak Ditemukan!!'], 404);
        }

        $biaya = Biaya::where('id_hunian', $id)->first();

        if(!$biaya){
            return response()->json(['message' => 'Biaya untuk hunian ini belum diatur!!']);
        }

        return response()->json($biaya);
    }

    public function store(Request $request, $id)
    {
        $hunian = Hunian::find($id);

        if(!$hunian){
            return response()->json(['message' => 'Hunian Tidak Ditemukan!!'], 404);
        }

        $existing = Biaya::where('id_hunian', $id)->first();

        if($existing){
            return response()->json(['message' => 'Biaya Hunian Ini Sudah Diatur!!']);
        }

        $request->validate([
            'wifi' => 'required|numeric',
            'sampah' => 'required|numeric',
            'kost' => 'required|numeric',
        ]);

        $biaya = Biaya::create([
            'id_hunian' => $id,
            'wifi' => $request->wifi,
            'sampah' => $request->sampah,
            'kost' => $request->kost,
        ]);

        return response()->json([
            'message' => 'Biaya Berhasil Ditambahkan',
            'data' => $biaya,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $hunian = Hunian::find($id);
        if(!$hunian){
            return response()->json(['message' => 'Hunian Tidak Ditemukan!!'], 404);
        }

        $biaya = Biaya::where('id_hunian', $id)->first();
        if(!$biaya){
            return response()->json(['message' => 'Biaya Hunian Ini Belum Diatur']);
        }

        $request->validate([
            'wifi' => 'required|numeric',
            'sampah' => 'required|numeric',
            'kost' => 'required|numeric', 
        ]);

        $biaya->update([
            'wifi' => $request->wifi,
            'sampah' => $request->sampah,
            'kost' => $request->kost,
        ]);

        return response()->json([
            'message' => 'Biaya Telah Diperbarui!!',
            'data' => $biaya,
        ]);
    }
}
