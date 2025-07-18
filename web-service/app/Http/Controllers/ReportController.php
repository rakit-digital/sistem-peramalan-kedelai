<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SoybeanStock;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf; // <-- Import facade PDF

class ReportController extends Controller
{
    /**
     * Menampilkan halaman filter dan hasil laporan di web.
     */
    public function index(Request $request)
    {
        // Panggil helper function untuk mendapatkan data
        $data = $this->getReportData($request);

        // Kirim semua data yang diperlukan ke view
        return view('pages.report.index', [
            'results' => $data['results'],
            'reportType' => $data['reportType'],
            'startDate' => $data['startDate'],
            'endDate' => $data['endDate'],
        ]);
    }

    /**
     * Men-generate dan men-download laporan dalam format PDF.
     */
    public function export(Request $request)
    {
        // Validasi input untuk keamanan
        $request->validate([
            'report_type' => 'required|in:usage,stock,purchase',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        // Panggil helper function yang sama untuk mendapatkan data
        $data = $this->getReportData($request);

        // Jika tidak ada hasil, jangan buat PDF, kembalikan dengan pesan error
        if ($data['results']->isEmpty()) {
            return redirect()->route('laporan', $request->query())->with('error', 'Tidak ada data untuk diekspor pada periode yang dipilih.');
        }

        // Load view PDF dengan data yang sudah disiapkan
        $pdf = PDF::loadView('pages.report.report-pdf', $data);
        
        // Buat nama file yang dinamis dan deskriptif
        $filename = 'laporan_' . $data['reportType'] . '_' . $data['startDate']->format('Y-m-d') . '_sampai_' . $data['endDate']->format('Y-m-d') . '.pdf';
        
        // Download file PDF
        return $pdf->download($filename);
    }

    /**
     * Helper function untuk mengambil data laporan dari database.
     * Dapat digunakan oleh index() dan exportPdf().
     */
    private function getReportData(Request $request)
    {
        // Tentukan nilai default jika tidak ada input
        $startDate = $request->get('start_date') ? Carbon::parse($request->get('start_date')) : now()->startOfMonth();
        $endDate = $request->get('end_date') ? Carbon::parse($request->get('end_date')) : now();
        $reportType = $request->get('report_type');

        $results = collect(); // Defaultnya adalah collection kosong

        // Hanya jalankan query jika jenis laporan dipilih
        if ($reportType) {
            $query = SoybeanStock::whereBetween('date', [$startDate, $endDate])->orderBy('date', 'asc');

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
        
        // Kembalikan semua data dalam satu array agar mudah dikelola
        return [
            'results' => $results,
            'reportType' => $reportType,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ];
    }
}