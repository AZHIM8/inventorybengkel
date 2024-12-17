<?php

namespace App\Http\Controllers;

use App\Models\Jenis;
use App\Models\Barang;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Dompdf\Dompdf;

class LaporanKategoriController extends Controller
{
    public function index()
    {
        return view('laporan-kategori.index', [
            'jenises'   => Jenis::all()
        ]);
    }

    public function getData(Request $request)
    {
        $selectedOption = $request->input('opsi');

        if ($selectedOption == 'semua') {
            $barang = Barang::with('jenis')->get();
        } else {
            $barang = Barang::with('jenis')->where('jenis_id', $selectedOption)->get();
        }

        return response()->json($barang);
    }

    public function printKategori(Request $request)
    {
        $selectedOption = $request->input('opsi');

        if ($selectedOption == 'semua') {
            $barangs = Barang::with('jenis')->get();
            $jenisBarang = 'Semua Kategori'; // Keterangan default untuk semua kategori
        } else {
            $barangs = Barang::with('jenis')->where('jenis_id', $selectedOption)->get();
            $jenis = Jenis::find($selectedOption);
            $jenisBarang = $jenis ? $jenis->jenis_barang : 'Kategori tidak ditemukan';
        }

        // Generate PDF
        $dompdf = new Dompdf();
        $html = view('laporan-kategori.print-kategori', compact('barangs', 'jenisBarang'))->render();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream('print-kategori.pdf', ['Attachment' => false]);
    }
}