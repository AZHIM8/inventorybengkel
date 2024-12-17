<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Barang;
use App\Models\KotakSaran;
use App\Models\BarangMasuk;
use App\Models\OrderBarang;
use App\Models\BarangKeluar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $barangCount        = Barang::all()->count();
        $barangMasukCount   = BarangMasuk::all()->count();
        $barangKeluarCount  = BarangKeluar::all()->count();
        $userCount          = User::all()->count();
        $orderBarangCount   = OrderBarang::count();
        $kotakSaranCount    = KotakSaran::count();

        $barangMasukPerBulan = DB::table('barang_masuks')
            ->join('detail_barang_masuks', 'barang_masuks.id', '=', 'detail_barang_masuks.barang_masuk_id')
            ->selectRaw('DATE_FORMAT(barang_masuks.tgl_masuk, "%Y-%m") as date, SUM(detail_barang_masuks.jumlah_masuk) as total')
            ->groupBy('date')
            ->get()
            ->map(function ($data) {
                $data->date = date('Y-m', strtotime($data->date));
                $data->total = (int) $data->total;
                return $data;
            });

        // Mendapatkan total barang keluar per bulan
        $barangKeluarPerBulan = DB::table('barang_keluars')
            ->join('detail_barang_keluars', 'barang_keluars.id', '=', 'detail_barang_keluars.barang_keluar_id')
            ->selectRaw('DATE_FORMAT(barang_keluars.tgl_keluar, "%Y-%m") as date, SUM(detail_barang_keluars.jumlah_keluar) as total')
            ->groupBy('date')
            ->get()
            ->map(function ($data) {
                $data->date = date('Y-m', strtotime($data->date));
                $data->total = (int) $data->total;
                return $data;
            });

        $barangMinimum = Barang::with('rak')->where('stok', '<=', 10)->get();


        return view('dashboard', [
            'barang'            => $barangCount,
            'barangMasuk'       => $barangMasukCount,
            'barangKeluar'      => $barangKeluarCount,
            'user'              => $userCount,
            'barangMasukData'   => $barangMasukPerBulan,
            'barangKeluarData'  => $barangKeluarPerBulan,
            'barangMinimum'     => $barangMinimum,
            'orderBarang'       => $orderBarangCount,
            'kotakSaran'        => $kotakSaranCount
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
