<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Supplier;
use App\Models\OrderBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderBarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('order-barang.index', [
            'barangs'   => Barang::all(),
            'suppliers' => Supplier::all()
        ]);
    }

    public function getDataOrderBarang()
    {
        return response()->json([
            'success'   => true,
            'data'      => OrderBarang::with('supplier')
                ->orderBy('id', 'DESC')
                ->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('order-barang.create', [
            'barangs'   => Barang::all(),
            'suppliers' => Supplier::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tanggal'               => 'required',
            'nama_barang'           => 'required',
            'jumlah'                => 'required',
            'keterangan'            => 'required',
            'supplier_id'           => 'required'
        ], [
            'tanggal.required'               => 'Form wajib diisi !',
            'nama_barang.required'           => 'Form wajib diisi !',
            'jumlah.required'                => 'Form wajib diisi !',
            'keterangan.required'            => 'Form wajib diisi !',
            'supplier_id.required'           => 'Form wajib diisi !'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $orderBarang = OrderBarang::create([
            'tanggal'               => $request->tanggal,
            'nama_barang'           => $request->nama_barang,
            'jumlah'                => $request->jumlah,
            'keterangan'            => $request->keterangan,
            'supplier_id'           => $request->supplier_id
        ]);

        return response()->json([
            'success'   => true,
            'message'   => 'Data Berhasil Disimpan !',
            'data'      => $orderBarang
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $orderBarang = OrderBarang::find($id);
        return response()->json([
            'data'      => $orderBarang,
            'barangs'   => Barang::all(),
            'suppliers' => Supplier::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $orderBarang = OrderBarang::find($id);
        $validator = Validator::make($request->all(), [
            'nama_barang'           => 'required',
            'jumlah'                => 'required',
            'keterangan'            => 'required',
            'supplier_id'           => 'required'
        ], [
            'nama_barang.required'           => 'Form wajib diisi !',
            'jumlah.required'                => 'Form wajib diisi !',
            'keterangan.required'            => 'Form wajib diisi !',
            'supplier_id.required'           => 'Form wajib diisi !'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $orderBarang->update([
            'nama_barang'           => $request->nama_barang,
            'jumlah'                => $request->jumlah,
            'keterangan'            => $request->keterangan,
            'supplier_id'           => $request->supplier_id
        ]);

        return response()->json([
            'success'   => true,
            'message'   => 'Data Berhasil Terupdate',
            'data'      => $orderBarang
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $orderBarang = OrderBarang::find($id);
        $orderBarang->delete();
        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Dihapus!'
        ]);
    }
}
