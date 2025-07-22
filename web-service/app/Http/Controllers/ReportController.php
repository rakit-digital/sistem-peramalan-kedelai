<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SoybeanStock;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    /**
     * Menampilkan halaman filter dan hasil laporan di web.
     */
    public function index(Request $request)
    {
        // Panggil helper function untuk mendapatkan data
        // Helper ini sudah dimodifikasi untuk paginasi dan statistik
        $data = $this->getReportData($request, true);

        // Kirim semua data yang diperlukan ke view
        return view('pages.report.index', $data);
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

        // Panggil helper function, tapi tanpa paginasi untuk PDF
        $data = $this->getReportData($request, false);

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
     * Dapat digunakan oleh index() dan export().
     * @param bool $isPaginated Menentukan apakah hasil perlu dipaginasi.
     */
    private function getReportData(Request $request, bool $isPaginated = false): array
    {
        // Tentukan nilai default jika tidak ada input
        $startDate = $request->get('start_date') ? Carbon::parse($request->get('start_date')) : now()->startOfMonth();
        $endDate = $request->get('end_date') ? Carbon::parse($request->get('end_date')) : now();
        $reportType = $request->get('report_type');

        $results = collect(); // Defaultnya adalah collection kosong
        $stats = []; // Array untuk menyimpan statistik
        $chartData = ['labels' => [], 'values' => []]; // Array untuk data grafik

        // Hanya jalankan query jika jenis laporan dipilih
        if ($reportType) {
            $query = SoybeanStock::whereBetween('date', [$startDate, $endDate])->orderBy('date', 'asc');

            // Ambil semua data untuk kalkulasi statistik dan grafik sebelum paginasi
            $allResults = $query->get();

            if ($allResults->isNotEmpty()) {
                switch ($reportType) {
                    case 'usage':
                        $field = 'usage_kg';
                        $stats = $this->calculateStats($allResults, $field);
                        $chartData = $this->prepareChartData($allResults, $field);
                        break;
                    case 'stock':
                        $field = 'closing_stock_kg';
                        $stats = $this->calculateStats($allResults, $field, false); // Jangan hitung total untuk stok
                        $chartData = $this->prepareChartData($allResults, $field);
                        break;
                    case 'purchase':
                        // Filter lagi untuk pembelian karena hanya data > 0 yang relevan
                        $allResults = $allResults->where('purchase_kg', '>', 0);
                        $field = 'purchase_kg';
                        if ($allResults->isNotEmpty()) {
                            $stats = $this->calculateStats($allResults, $field);
                            $chartData = $this->prepareChartData($allResults, $field);
                        }
                        break;
                }
            }

            // Terapkan paginasi jika diperlukan
            if ($isPaginated) {
                // Kita perlu membuat instance Paginator secara manual karena kita sudah memanipulasi collection
                $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
                    $allResults->forPage(\Illuminate\Pagination\Paginator::resolveCurrentPage(), 10),
                    $allResults->count(),
                    10,
                    \Illuminate\Pagination\Paginator::resolveCurrentPage(),
                    ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
                );
                $results = $paginator;
            } else {
                $results = $allResults; // Untuk PDF, gunakan semua hasil
            }
        }
        
        return [
            'results' => $results,
            'stats' => $stats,
            'chartData' => $chartData,
            'reportType' => $reportType,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ];
    }

    /**
     * Helper untuk menghitung statistik dari collection data.
     * @param bool $includeTotal Menentukan apakah 'total' harus dihitung.
     */
    private function calculateStats(\Illuminate\Support\Collection $collection, string $field, bool $includeTotal = true): array
    {
        if ($collection->isEmpty()) {
            return [];
        }

        $stats = [
            'average' => $collection->avg($field),
            'highest' => $collection->max($field),
            'lowest' => $collection->min($field),
        ];

        if ($includeTotal) {
            $stats['total'] = $collection->sum($field);
        }

        return $stats;
    }

    /**
     * Helper untuk menyiapkan data untuk Chart.js.
     */
    private function prepareChartData(\Illuminate\Support\Collection $collection, string $field): array
    {
        if ($collection->isEmpty()) {
            return ['labels' => [], 'values' => []];
        }
        
        return [
            'labels' => $collection->pluck('date')->map(fn($date) => $date->format('d M'))->toArray(),
            'values' => $collection->pluck($field)->toArray(),
        ];
    }
}