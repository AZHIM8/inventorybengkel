<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Customer;
use App\Models\KotakSaran;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use COM;
use Illuminate\Support\Facades\Validator;

class KotakSaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('kotak-saran.index', [
            'barangs'       => Barang::all(),
            'customers'     => Customer::all(),
            'kotakSarans'   => KotakSaran::with(['barang', 'customer'])
                ->orderBy('id', 'DESC')
                ->get()
        ]);
    }

    public function getDataKotakSaran()
    {
        return response()->json([
            'success'   => true,
            'data'      => KotakSaran::with(['barang', 'customer'])
                ->orderBy('id', 'DESC')
                ->get(),
            'barangs'       => Barang::all(),
            'customers'     => Customer::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('kotak-saran.create', [
            'barangs'       => Barang::all(),
            'customers'     => Customer::all(),
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
            'ide_gagasan'           => 'required',
            'inovasi'               => 'required',
            'keluhan_operasional'   => 'required',
            'customer_id'           => 'required'
        ], [
            'tanggal.required'               => 'Form wajib diisi !',
            'nama_barang.required'           => 'Form wajib diisi !',
            'ide_gagasan.required'           => 'Form wajib diisi !',
            'inovasi.required'               => 'Form wajib diisi !',
            'keluhan_operasional.required'   => 'Form wajib diisi !',
            'customer_id.required'           => 'Form wajib diisi !'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $kotakSaran = KotakSaran::create([
            'tanggal'               => $request->tanggal,
            'nama_barang'           => $request->nama_barang,
            'ide_gagasan'           => $request->ide_gagasan,
            'inovasi'               => $request->inovasi,
            'keluhan_operasional'   => $request->keluhan_operasional,
            'customer_id'           => $request->customer_id
        ]);

        return response()->json([
            'success'   => true,
            'message'   => 'Data Berhasil Disimpan !',
            'data'      => $kotakSaran
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $kotakSaran = KotakSaran::find($id);
        return response()->json([
            'data'          => $kotakSaran,
            'barangs'       => Barang::all(),
            'customers'     => Customer::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $kotakSaran = KotakSaran::find($id);
        $validator = Validator::make($request->all(), [
            'nama_barang'           => 'required',
            'ide_gagasan'           => 'required',
            'inovasi'               => 'required',
            'keluhan_operasional'   => 'required',
            'customer_id'           => 'required'
        ], [
            'nama_barang.required'           => 'Form wajib diisi !',
            'ide_gagasan.required'           => 'Form wajib diisi !',
            'inovasi.required'               => 'Form wajib diisi !',
            'keluhan_operasional.required'   => 'Form wajib diisi !',
            'customer_id.required'           => 'Form wajib diisi !'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $kotakSaran->update([
            'nama_barang'           => $request->nama_barang,
            'ide_gagasan'           => $request->ide_gagasan,
            'inovasi'               => $request->inovasi,
            'keluhan_operasional'   => $request->keluhan_operasional,
            'customer_id'           => $request->customer_id
        ]);

        return response()->json([
            'success'   => true,
            'message'   => 'Data Berhasil Terupdate',
            'data'      => $kotakSaran
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $kotakSaran = KotakSaran::find($id);
        $kotakSaran->delete();
        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Dihapus!'
        ]);
    }
}
