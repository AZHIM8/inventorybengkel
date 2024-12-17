<?php

namespace App\Http\Controllers;

use App\Models\Rak;
use App\Models\KodeRak;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class KodeRakController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('rak.index', [
            'Raks'  => Rak::orderBy('id', 'DESC')->get()
        ]);
    }

    public function getDataKodeRak()
    {
        return response()->json([
            'success' => true,
            'data'    => Rak::orderBy('id', 'DESC')->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('rak.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kd_rak'            => 'required',
            'nm_rak'            => 'required',
        ], [
            'kd_rak.required'   => 'Form Wajib Di Isi !',
            'nm_rak.required'   => 'Form Wajib Di Isi !',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $rak = Rak::create([
            'kd_rak'    => $request->kd_rak,
            'nm_rak'    => $request->nm_rak,
        ]);

        return response()->json([
            'success'   => true,
            'message'   => 'Data Berhasil Disimpan !',
            'data'      => $rak
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $rak = Rak::findOrFail($id);
        return response()->json([
            'success' => true,
            'message' => 'Edit Data Barang',
            'data'    => $rak
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $rak = Rak::find($id);
        $validator = Validator::make($request->all(), [
            'kd_rak'            => 'required',
            'nm_rak'            => 'required',
        ], [
            'kd_rak.required'   => 'Form Wajib Di Isi !',
            'nm_rak.required'   => 'Form Wajib Di Isi !',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $rak->update([
            'kd_rak'    => $request->kd_rak,
            'nm_rak'    => $request->nm_rak,
        ]);

        return response()->json([
            'success'   => true,
            'message'   => 'Data Berhasil Terupdate',
            'data'      => $rak
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $rak = Rak::find($id);
        $rak->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Dihapus!'
        ]);
    }
}