<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sewa;
use App\Models\Tagihan;

class TagihanController extends Controller
{
    public function index()
    {
        $tagihan = Tagihan::with(['sewa.user', 'sewa.hunian.biaya'])->get();

        return response()->json(['data' => $tagihan]);
    }

    public function show($id)
    {
        $tagihan = Tagihan::with(['sewa.user', 'sewa.hunian'])->find($id);

        if(!$tagihan){
            return response()->json(['message' => 'Tagihan Tidak Ditemukan!!'], 404);
        }

        return response()->json([$tagihan]);
    }

    public function me(Request $request){
        $sewa = Sewa::where('id_users', $request->user()->id_users)->where('status', 'aktif')->first();
        if(!$sewa){
            return response()->json(['message' => 'Anda Tidak Memiliki Sewa Aktif']);
        }

        $tagihan = Tagihan::with(['transaksi'])->where('id_sewa' , $sewa->id_sewa)->get();

        return response()->json(['data' => $tagihan]);
    }

    public function generate(Request $request)
    {   
        $request->validate([
            'bulan' => 'required|date_format:Y-m',
            'data' => 'required|array',
            'data.*.id_sewa' => 'required|exists:sewa,id_sewa',
            'data.*.air' => 'required|numeric',
        ]);

        $total = 0;
        $data = [];

        foreach($request->data as $item){
            $sewa = Sewa::with(['hunian.biaya'])->find($item['id_sewa']);

            if(!$sewa || $sewa->status !== 'aktif') continue;

            $biaya = $sewa->hunian->biaya;
            if(!$biaya) continue;

            $tglSewa = date('d', strtotime($sewa->tgl_jatuhtempo));
            $tglJatuhTempo = now()->parse($request->bulan . '-' . $tglSewa)->format('Y-m-d');

            $tagihan = Tagihan::create([
                'id_sewa' => $sewa->id_sewa,
                'tgl_tagihan' => now()->format('Y-m-d'),
                'air' => $item['air'],
                'wifi' => $biaya->wifi,
                'tgl_jatuhtempo' => $tglJatuhTempo,
                'status' => 'notpaid',
            ]);

            $data[] = $tagihan;
            $total++; 
        }

        return response()->json([
            'message' => "Tagihan berhasil digenerate untuk {$total} penghuni",
            'total' => $total,
            'data' => $data
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $tagihan = Tagihan::find($id);
        if(!$tagihan){
            return response()->json(['message' => 'Tagihan Tidak Ditemukan!!'], 404);
        }

        $request->validate([
            'air' => 'required|numeric',
            'wifi' => 'required|numeric',
            'tgl_jatuhtempo' => 'required|date',
        ]);

        $tagihan->update([
            'air' => $request->air,
            'wifi' => $request->wifi,
            'tgl_jatuhtempo' => $request->tgl_jatuhtempo,
        ]);

        return response()->json([
            'message' => 'Tagihan Berhasil Diperbarui',
            'data' => $tagihan
        ]);

    }

}
