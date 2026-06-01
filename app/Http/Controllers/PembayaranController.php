<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembayaran;
use App\Models\Transaksi;
use App\Models\Tagihan;

class PembayaranController extends Controller
{
    public function index()
    {
        $pembayaran = Pembayaran::with(['transaksi.tagihan.sewa.user', 'transaksi.tagihan.sewa.hunian'])->get();

        return response()->json(['data' => $pembayaran]);
    }

    public function show($invoice)
    {
        $pembayaran = Pembayaran::with('transaksi.tagihan.sewa.user')
            ->where('invoice', $invoice)->first();

        if(!$pembayaran){
            return response()->json(['message' => 'Pembayaran Tidak Ditemukan!!'], 404);
        }

        return response()->json($pembayaran);
    }

    public function me(Request $request)
    {
        $pembayaran = Pembayaran::with(['transaksi.tagihan.sewa.hunian'])
            ->whereHas('transaksi.tagihan.sewa', function ($querry) use ($request){
                $querry->where('id_users', $request->user()->id_users);
            })
            ->get();

        return response()->json(['data' => $pembayaran]);
    }

    public function upload(Request $request)
    {
        $request->validate([
            'invoice' => 'required|exists:transaksi,invoice',
            'nama_pengirim' => 'required|string',
            'bukti_trf' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'tgl_bayar' => 'required|date',
        ]);

        $transaksi = Transaksi::where('invoice', $request->invoice)->first();
        $tagihan = Tagihan::find($transaksi->id_tagihan);

        $existing = Pembayaran::where('invoice', $request->invoice)->first();
        if($existing && $existing->status === 'verif'){
            return response()->json(['message' => 'Pembayaran Sedang Diverifikasi'], 400);
        }
        if($existing && $existing->status === 'paid'){
            return response()->json(['message' => 'Tagihan Sudah Lunas'], 400);
        }

        $file = $request->file('bukti_trf');
        $filename = time() . '-' . $file->getClientOriginalName();
        $file->storeAs('bukti', $filename, 'public');
        $urlBukti = url('storage/bukti/' . $filename);

        if($existing){
            $existing->update([
                'nama_pengirim' => $request->nama_pengirim,
                'bukti_trf' => $urlBukti,
                'tgl_bayar' => $request->tgl_bayar,
                'tgl_jatuhtempo' => $tagihan->tgl_jatuhtempo,
                'status' => 'verif',
            ]);
            $pembayaran = $existing;
        }else{
            $pembayaran = Pembayaran::create([
                'invoice' => $request->invoice,
                'nama_pengirim' => $request->nama_pengirim,
                'bukti_trf' => $urlBukti,
                'tgl_bayar' => $request->tgl_bayar,
                'tgl_jatuhtempo' => $tagihan->tgl_jatuhtempo,
                'status' => 'verif',
            ]);
        }

        $tagihan->update(['status' => 'verif']);

        return response()->json([
            'message' => 'Bukti Pembayaran Berhasil Diupload, Menunggu Verifikasi',
            'data' => $pembayaran
        ], 201);

    }

    public function verifikasi(Request $request, $id)
    {
        $pembayaran = Pembayaran::find($id);
        if(!$pembayaran){
            return response()->json(['message' => 'Pembayaran Tidak Ditemukan'],404);
        }

        $request->validate([
            'status' => 'required|in:paid,notpaid',
        ]);

        $pembayaran->update(['status' => $request->status]);

        $tagihan = Tagihan::find($pembayaran->transaksi->id_tagihan);
        $tagihan->update(['status' => $request->status]);

        $message = $request->status === 'paid' ? 'Pembayaran Berhasil Diverifikasi' : 'Pembayaran Ditolak';

        return response()->json([
            'message' => $message,
            'data' => $pembayaran
        ]);
    }
}
