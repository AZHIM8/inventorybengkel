<?php

namespace App\Http\Controllers;

use Dompdf\Dompdf;
use App\Models\OrderBarang;
use Illuminate\Http\Request;

class LaporanOrderBarangController extends Controller
{
    public function index()
    {
        return view('laporan-order-barang.index', [
            'kotakSarans'   => OrderBarang::with('supplier')->orderBy('id', 'DESC')->get()
        ]);
    }

    public function getData(Request $request)
    {
        $tanggalMulai = $request->input('tanggal_mulai');
        $tanggalSelesai = $request->input('tanggal_selesai');

        $orderBarang = OrderBarang::query();

        if ($tanggalMulai && $tanggalSelesai) {
            $orderBarang->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai]);
        }

        $data = $orderBarang->with('supplier')->orderBy('id', 'DESC')->get();

        if (empty($tanggalMulai) && empty($tanggalSelesai)) {
            $data = OrderBarang::with('supplier')->orderBy('id', 'DESC')->get();
        }

        return response()->json($data);
    }

    /**
     * Print DomPDF
     */
    public function printorderBarang(Request $request)
    {
        $tanggalMulai = $request->input('tanggal_mulai');
        $tanggalSelesai = $request->input('tanggal_selesai');

        $orderBarang = OrderBarang::query();

        if ($tanggalMulai && $tanggalSelesai) {
            $orderBarang->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai]);
        }

        if ($tanggalMulai !== null && $tanggalSelesai !== null) {
            $data = $orderBarang->with('supplier')->orderBy('id', 'DESC')->get();
        } else {
            $data = OrderBarang::with('supplier')->orderBy('id', 'DESC')->get();
        }

        //Generate PDF
        $dompdf = new Dompdf();
        $html = view('/laporan-order-barang/print-order-barang', compact('data', 'tanggalMulai', 'tanggalSelesai'))->render();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream('print-order-barang.pdf', ['Attachment' => false]);
    }
}