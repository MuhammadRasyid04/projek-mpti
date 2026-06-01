<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Tagihan;
use Illuminate\Support\Str;

class TransaksiController extends Controller
{
    public function index()
    {
        $transaksi = Transaksi::with(['tagihan.sewa.user', 'tagihan.sewa.hunian'])->get();
        return response()->json(['data' => $transaksi]);
    }

    public function show($invoice)
    {
        $transaksi = Transaksi::with(['tagihan.sewa.user', 'pembayaran'])->where('invoice', $invoice)->first();
        if(!$transaksi){
            return response()->json(['message' => 'Transaksi Tidak Ditemukan!!'], 404);
        }

        return response()->json($transaksi);
    }

    public function me(Request $request)
    {
        $transaksi = Transaksi::with(['tagihan.sewa.hunian', 'pembayaran'])
            ->whereHas('tagihan.sewa', function ($querry) use ($request){
                $querry->where('id_users', $request->user()->id_users);
            })
            ->get();

        return response()->json(['data' => $transaksi]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_tagihan' => 'required|exists:tagihan,id_tagihan',
        ]);

        $tagihan = Tagihan::with(['sewa.hunian.biaya'])->find($request->id_tagihan);

        if($tagihan->status !== 'notpaid'){
            return response()->json(['message' => 'Tagihan Ini Sudah Diproses'], 400);
        }

        $biaya = $tagihan->sewa->hunian->biaya;

        $total = $tagihan->air + $tagihan->wifi + $biaya->sampah + $biaya->kost;

        $invoice = 'INV-' . strtoupper(Str::random(8));

        $transaksi = Transaksi::create([
            'invoice' => $invoice,
            'id_tagihan' => $tagihan->id_tagihan,
            'biaya_sampah' => $biaya->sampah,
            'biaya_wifi' => $tagihan->wifi,
            'biaya_air' => $tagihan->air,
            'biaya_kost' => $biaya->kost,
            'total_bayar' => $total,
            'tgl_request' => now(),
        ]);

        return response()->json([
            'message' => 'Transaksi Berhasil Dibuat',
            'data' => $transaksi
        ], 201);
    }
}
