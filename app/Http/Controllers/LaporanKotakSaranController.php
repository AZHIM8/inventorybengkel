<?php

namespace App\Http\Controllers;

use Dompdf\Dompdf;
use App\Models\KotakSaran;
use App\Models\BarangMasuk;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LaporanKotakSaranController extends Controller
{
    public function index()
    {
        return view('laporan-kotak-saran.index', [
            'kotakSarans'   => KotakSaran::with('customer')->orderBy('id', 'DESC')->get()
        ]);
    }

    public function getData(Request $request)
    {
        $tanggalMulai = $request->input('tanggal_mulai');
        $tanggalSelesai = $request->input('tanggal_selesai');

        $kotakSaran = KotakSaran::query();

        if ($tanggalMulai && $tanggalSelesai) {
            $kotakSaran->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai]);
        }

        $data = $kotakSaran->with('customer')->orderBy('id', 'DESC')->get();

        if (empty($tanggalMulai) && empty($tanggalSelesai)) {
            $data = KotakSaran::with('customer')->orderBy('id', 'DESC')->get();
        }

        return response()->json($data);
    }

    /**
     * Print DomPDF
     */
    public function printKotakSaran(Request $request)
    {
        $tanggalMulai = $request->input('tanggal_mulai');
        $tanggalSelesai = $request->input('tanggal_selesai');

        $kotakSaran = KotakSaran::query();

        if ($tanggalMulai && $tanggalSelesai) {
            $kotakSaran->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai]);
        }

        if ($tanggalMulai !== null && $tanggalSelesai !== null) {
            $data = $kotakSaran->with('customer')->orderBy('id', 'DESC')->get();
        } else {
            $data = KotakSaran::with('customer')->orderBy('id', 'DESC')->get();
        }

        //Generate PDF
        $dompdf = new Dompdf();
        $html = view('/laporan-kotak-saran/print-kotak-saran', compact('data', 'tanggalMulai', 'tanggalSelesai'))->render();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream('print-kotak-saran.pdf', ['Attachment' => false]);
    }
}
