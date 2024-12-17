<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Satuan;
use App\Models\Customer;
use App\Models\BarangKeluar;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DetailBarangKeluar;
use Illuminate\Support\Facades\Validator;

class BarangKeluarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('barang-keluar.index', [
            'barangs'           => Barang::all(),
            'barangKeluar'      => BarangKeluar::with('customer')->orderBy('id', 'DESC')->get(),
            'customers'         => Customer::all()
        ]);
    }

    public function daftarBarang()
    {
        $barangs = Barang::all();
        return response()->json($barangs);
    }

    public function getDataBarangKeluar()
    {
        return response()->json([
            'success'   => true,
            'data'      => BarangKeluar::with('customer')->orderBy('id', 'DESC')->get(),
            'customer'  => Customer::all()
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('barang-keluar.create', [
            'barangs' => Barang::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'barang_id'          => 'required|array',
            'jumlah_keluar'      => 'required|array',
            'jumlah_keluar.*'    => 'required|integer|min:0',
            'customer_id'        => 'required',
        ], [
            'kode_transaksi.required'   => 'wajib diisi!',
            'barang_id.required'        => 'wajib diisi!',
            'jumlah_keluar.required'     => 'wajib diisi!',
            'jumlah_keluar.*.required'   => 'keluarkan jumlah!',
            'jumlah_keluar.*.integer'    => 'keluarkan jumlah yang valid!',
            'jumlah_keluar.*.min'        => 'jumlah harus lebih besar atau sama dengan 0!',
            'customer_id.required'      => 'wajib diisi!',
        ]);

        // Custom validation for stock check
        $validator->after(function ($validator) use ($request) {
            foreach ($request->barang_id as $key => $barangId) {
                $barang = Barang::find($barangId);
                if ($barang) {
                    $jumlah_keluar = $request->jumlah_keluar[$key];
                    if ($jumlah_keluar > $barang->stok) {
                        $validator->errors()->add('jumlah_keluar.' . $key, 'Stok tidak cukup untuk barang: ' . $barang->nama_barang);
                    }
                } else {
                    $validator->errors()->add('barang_id.' . $key, 'Barang tidak ditemukan.');
                }
            }
        });

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $barangKeluar = BarangKeluar::create([
            'tgl_keluar'        => $request->tgl_keluar,
            'customer_id'       => $request->customer_id,
            'kode_transaksi'    => $request->kode_transaksi,
            'user_id'           => auth()->user()->id
        ]);

        if ($barangKeluar) {
            foreach ($request->barang_id as $key => $barangId) {
                $jumlah_keluar  = $request->jumlah_keluar[$key];

                $barang = Barang::findOrFail($barangId);
                $currentStock = $barang->stok;

                $newStock = $currentStock - $jumlah_keluar;

                $barang->update(['stok' => $newStock]);

                DetailBarangKeluar::create([
                    'barang_keluar_id'   => $barangKeluar->id,
                    'barang_id'         => $barangId,
                    'jumlah_keluar'      => $jumlah_keluar,
                ]);
            }
        }

        return response()->json(['message' => 'Data barang keluar berhasil disimpan']);
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $barangKeluar = BarangKeluar::with('customer')->find($id);
        $detailBarangKeluars = DetailBarangKeluar::with('barang')->where('barang_keluar_id', $id)->get();
        return response()->json([
            'success'               => true,
            'barang_keluar'          => $barangKeluar,
            'detail_barang_keluars'  => $detailBarangKeluars,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $barangKeluar = BarangKeluar::with('customer')->find($id);
        $detailBarangKeluars = DetailBarangKeluar::with('barang')->where('barang_keluar_id', $id)->get();
        return response()->json([
            'success'               => true,
            'barang_keluar'          => $barangKeluar,
            'detail_barang_keluars'  => $detailBarangKeluars,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'tgl_keluar'         => 'required|date',
            'barang_id'         => 'required|array',
            'jumlah_keluar'      => 'required|array',
            'jumlah_keluar.*'    => 'required|integer|min:0',
            'customer_id'       => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $barangKeluar = BarangKeluar::findOrFail($id);
        $barangKeluar->tgl_keluar = $request->tgl_keluar;
        $barangKeluar->customer_id = $request->customer_id;
        $barangKeluar->save();

        // Mengembalikan stok lama sebelum mengupdate detail
        foreach ($request->barang_id as $key => $barangId) {
            $barangKeluarDetail = DetailBarangKeluar::where('barang_keluar_id', $id)
                ->where('barang_id', $barangId)
                ->firstOrFail();
            $barang = Barang::findOrFail($barangId);
            $barang->stok += $barangKeluarDetail->jumlah_keluar;
            $barang->save();
        }

        // Memastikan stok cukup sebelum menyimpan perubahan
        foreach ($request->barang_id as $key => $barangId) {
            $barang = Barang::findOrFail($barangId);
            $jumlah_keluar_baru = $request->jumlah_keluar[$key];
            if ($jumlah_keluar_baru > $barang->stok) {
                return response()->json(['error' => 'Stok tidak cukup untuk barang: ' . $barang->nama_barang], 422);
            }
        }

        // Mengurangi stok sesuai jumlah baru
        foreach ($request->barang_id as $key => $barangId) {
            $barangKeluarDetail = DetailBarangKeluar::where('barang_keluar_id', $id)
                ->where('barang_id', $barangId)
                ->firstOrFail();
            $stokLama = $barangKeluarDetail->jumlah_keluar;
            $stokBaru = $request->jumlah_keluar[$key];

            $barang = Barang::findOrFail($barangId);
            $barang->stok = $barang->stok - $stokBaru;
            $barang->save();

            $barangKeluarDetail->update([
                'jumlah_keluar' => $stokBaru,
            ]);
        }

        return response()->json(['message' => 'Data barang keluar berhasil diupdate!']);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BarangKeluar $barangKeluar)
    {
        $detailBarangKeluars = $barangKeluar->detailBarangKeluars;

        foreach ($detailBarangKeluars as $detail) {
            $barang = $detail->barang;

            $barang->stok += $detail->jumlah_keluar;
            $barang->save();
        }

        $barangKeluar->delete();

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
        $barang = Barang::where('nama_barang', $request->nama_barang)->first();

        if ($barang) {
            return response()->json([
                'nama_barang'   => $barang->nama_barang,
                'stok'          => $barang->stok,
                'satuan_id'     => $barang->satuan_id,
            ]);
        }
    }

    /**
     * Create Autocomplete Data In Update Method
     */

    public function getStok(Request $request)
    {
        $namaBarang = $request->input('nama_barang');
        $barang = Barang::where('nama_barang', $namaBarang)->select('stok', 'satuan_id')->first();

        $response = [
            'stok'          => $barang->stok,
            'satuan_id'     => $barang->satuan_id
        ];

        return response()->json($response);
    }

    public function getSatuan()
    {
        $satuans = Satuan::all();

        return response()->json($satuans);
    }

    public function getBarangs(Request $request)
    {
        if ($request->has('q')) {
            $barangs = Barang::where('nama_barang', 'like', '%' . $request->input('q') . '%')->get();
            return response()->json($barangs);
        }

        return response()->json([]);
    }
}
