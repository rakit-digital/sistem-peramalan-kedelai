<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SoybeanStock;

class ReportController extends Controller
{
    /**
     * Menampilkan halaman filter dan hasil laporan.
     */
    public function index(Request $request)
    {
        $results = null;
        $reportType = $request->input('report_type');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Jika ada input dari form filter, proses datanya
        if ($reportType && $startDate && $endDate) {
            
            // Bangun query dasar
            $query = SoybeanStock::whereBetween('date', [$startDate, $endDate])->orderBy('date', 'asc');

            // Sesuaikan query berdasarkan jenis laporan
            switch ($reportType) {
                case 'usage':
                    $results = $query->get(['date', 'usage_kg']);
                    break;
                case 'stock':
                    $results = $query->get(['date', 'closing_stock_kg']);
                    break;
                case 'purchase':
                    $results = $query->where('purchase_kg', '>', 0)->get(['date', 'purchase_kg']);
                    break;
            }
        }

        // Kirim data ke view
        // 'request' dikirim agar nilai filter sebelumnya tetap ada di form
        return view('pages.laporan', [
            'results' => $results,
            'reportType' => $reportType,
            'request' => $request,
        ]);
    }
}