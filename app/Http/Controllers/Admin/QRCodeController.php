<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use Barryvdh\DomPDF\Facade\Pdf;

class QRCodeController extends Controller
{
    public function exportPdf()
    {
        $bukus = Buku::all();

        $pdf = Pdf::loadView('filament.admin.buku.qr-pdf', compact('bukus'))->setPaper('A4', 'portrait');
        return $pdf->stream('daftar-qrcode-buku.pdf');
    }
}
