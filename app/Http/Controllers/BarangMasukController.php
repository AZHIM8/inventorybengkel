<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Satuan;
use App\Models\Supplier;
use App\Models\BarangMasuk;
use Illuminate\Http\Request;
use App\Models\DetailBarangMasuk;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Validated;
use Illuminate\Support\Facades\Validator;

class BarangMasukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('barang-masuk.index', [
            'barangs'      => Barang::all(),
            'barangsMasuk' => BarangMasuk::with('supplier')->orderBy('id', 'DESC')->get(),
            'suppliers'    => Supplier::all()
        ]);
    }

    public function daftarBarang()
    {
        $barangs = Barang::all();
        return response()->json($barangs);
    }

    public function getDataBarangMasuk()
    {
        return response()->json([
            'success'   => true,
            'data'      => BarangMasuk::with('supplier')->orderBy('id', 'DESC')->get(),
            'supplier'  => Supplier::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('barang-masuk.create', [
            'barangs'   => Barang::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'barang_id'         => 'required|array',
            'jumlah_masuk'      => 'required|array',
            'jumlah_masuk.*'    => 'required|integer|min:0',
            'supplier_id'       => 'required',
        ], [
            'kode_transaksi.required'   => 'wajib diisi!',
            'barang_id.required'        => 'wajib diisi!',
            'jumlah_masuk.required'     => 'wajib diisi!',
            'jumlah_masuk.*.required'   => 'Masukkan jumlah!',
            'jumlah_masuk.*.integer'    => 'Masukkan jumlah yang valid!',
            'jumlah_masuk.*.min'        => 'jumlah harus lebih besar atau sama dengan 0!',
            'supplier_id.required'      => 'wajib diisi!',
        ]);


        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $barangMasuk = BarangMasuk::create([
            'tgl_masuk'         => $request->tgl_masuk,
            'supplier_id'       => $request->supplier_id,
            'kode_transaksi'    => $request->kode_transaksi,
            'user_id'           => auth()->user()->id
        ]);

        foreach ($request->barang_id as $key => $barangId) {
            $jumlah_masuk  = $request->jumlah_masuk[$key];

            $barang = Barang::findOrFail($barangId);
            $currentStock = $barang->stok;

            $newStock = $currentStock + $jumlah_masuk;

            $barang->update(['stok' => $newStock]);

            DetailBarangMasuk::create([
                'barang_masuk_id'   => $barangMasuk->id,
                'barang_id'         => $barangId,
                'jumlah_masuk'      => $jumlah_masuk,
            ]);
        }

        return response()->json(['message' => 'Data barang masuk berhasil disimpan']);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $barangMasuk = BarangMasuk::with('supplier')->find($id);
        $detailBarangMasuks = DetailBarangMasuk::with('barang')->where('barang_masuk_id', $id)->get();
        return response()->json([
            'success'               => true,
            'barang_masuk'          => $barangMasuk,
            'detail_barang_masuks'  => $detailBarangMasuks,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $barangMasuk = BarangMasuk::with('supplier')->find($id);
        $detailBarangMasuks = DetailBarangMasuk::with('barang')->where('barang_masuk_id', $id)->get();
        return response()->json([
            'success'               => true,
            'barang_masuk'          => $barangMasuk,
            'detail_barang_masuks'  => $detailBarangMasuks,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'tgl_masuk'         => 'required|date',
            'barang_id'         => 'required|array',
            'jumlah_masuk'      => 'required|array',
            'jumlah_masuk.*'    => 'required|integer|min:0',
            'supplier_id'       => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $barangMasuk = BarangMasuk::findOrFail($id);
        $barangMasuk->tgl_masuk = $request->tgl_masuk;
        $barangMasuk->supplier_id = $request->supplier_id;
        $barangMasuk->save();

        $perbedaanStok = [];

        foreach ($request->barang_id as $key => $barangId) {
            $barangMasukDetail = DetailBarangMasuk::where('barang_masuk_id', $id)
                ->where('barang_id', $barangId)
                ->firstOrFail();
            $stokLama                   = $barangMasukDetail->jumlah_masuk;
            $stokBaru                   = $request->jumlah_masuk[$key];
            $perbedaan                  = $stokBaru - $stokLama;
            $perbedaanStok[$barangId]   = $perbedaan;

            $barang = Barang::findOrFail($barangId);
            $barang->stok += $perbedaan;
            $barang->save();
        }

        foreach ($request->barang_id as $key => $barangId) {
            $detailBarangMasuk = DetailBarangMasuk::where('barang_masuk_id', $id)
                ->where('barang_id', $barangId)
                ->firstOrFail();
            $detailBarangMasuk->update([
                'jumlah_masuk'  => $request->jumlah_masuk[$key],
            ]);
        }

        return response()->json(['message' => 'Data barang masuk berhasil diupdate!']);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BarangMasuk $barangMasuk)
    {
        $detailBarangMasuks = $barangMasuk->detailBarangMasuks;

        foreach ($detailBarangMasuks as $detail) {
            $barang = $detail->barang;

            $barang->stok -= $detail->jumlah_masuk;
            $barang->save();
        }

        $barangMasuk->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data Barang Berhasil Dihapus!'
        ]);
    }



    /**
     * Create Autocomplete Data
     */
    public function getAutoCompleteData(Request $request)
    {
        $barang = Barang::where('nama_barang', $request->nama_barang)->first();;
        if ($barang) {
            return response()->json([
                'nama_barang'   => $barang->nama_barang,
                'stok'          => $barang->stok,
                'satuan_id'     => $barang->satuan_id,
            ]);
        }
    }

    /**
     * Get Satuan
     */
    public function getSatuan()
    {
        $satuans = Satuan::all();

        return response()->json($satuans);
    }
}
