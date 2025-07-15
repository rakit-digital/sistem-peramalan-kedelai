<?php

namespace App\Http\Controllers;

use App\Models\SoybeanStock;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon; // <-- Pastikan Carbon di-import
use Illuminate\Support\Facades\Auth;

class SoybeanStockController extends Controller
{
    /**
     * Menampilkan daftar semua data stok.
     * Halaman: index.blade.php
     */
    public function index()
    {
        // Ambil data dari yang terbaru dengan paginasi
        $stocks = SoybeanStock::with('user')->latest('date')->paginate(15);
        
        // Kalkulasi stok awal untuk setiap baris di tabel agar lebih akurat, terutama di halaman > 1
        $stocks->getCollection()->transform(function ($stock, $key) use ($stocks) {
            $previousStockOnPage = $stocks->get($key + 1);

            if ($previousStockOnPage) {
                // Jika ada data sebelumnya di halaman ini, gunakan stok akhirnya
                $stock->opening_stock = $previousStockOnPage->closing_stock_kg;
            } else {
                // Jika ini item pertama di halaman, cari di database
                $previousStockInDB = SoybeanStock::where('date', '<', $stock->date)->orderBy('date', 'desc')->first();
                $stock->opening_stock = $previousStockInDB ? $previousStockInDB->closing_stock_kg : 0;
            }
            return $stock;
        });

        return view('pages.data-kedelai.index', compact('stocks'));
    }

    /**
     * Menampilkan form untuk membuat data baru.
     * Halaman: create.blade.php
     */
    public function create()
    {
        $latestStock = SoybeanStock::latest('date')->first();
        $openingStockForNewEntry = $latestStock ? $latestStock->closing_stock_kg : 0;

        return view('pages.data-kedelai.create', compact('openingStockForNewEntry'));
    }

    /**
     * Menyimpan data baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'date' => ['required', 'date', 'unique:soybean_stocks,date'],
            'purchase_kg' => ['required', 'numeric', 'min:0'],
            'usage_kg' => ['required', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($request) {
            $previousDayStock = SoybeanStock::where('date', '<', $request->date)->orderBy('date', 'desc')->first();
            $openingStock = $previousDayStock ? $previousDayStock->closing_stock_kg : 0;
            $closingStock = $openingStock + $request->purchase_kg - $request->usage_kg;

            SoybeanStock::create([
                'user_id' => Auth::id(),
                'date' => $request->date,
                'purchase_kg' => $request->purchase_kg,
                'usage_kg' => $request->usage_kg,
                'closing_stock_kg' => $closingStock,
                'notes' => $request->notes,
            ]);

            // Hitung ulang semua hari setelah tanggal baru ini
            $this->recalculateStockFrom($request->date);
        });

        return redirect()->route('data.kedelai.index')->with('status', 'Data harian berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit data.
     * Halaman: edit.blade.php
     */
    public function edit(SoybeanStock $data_kedelai) // Menggunakan Route Model Binding
    {
        $previousDayStock = SoybeanStock::where('date', '<', $data_kedelai->date)->orderBy('date', 'desc')->first();
        $openingStock = $previousDayStock ? $previousDayStock->closing_stock_kg : 0;

        return view('pages.data-kedelai.edit', [
            'stock' => $data_kedelai,
            'openingStock' => $openingStock,
        ]);
    }

    /**
     * Memperbarui data yang ada di database.
     */
    public function update(Request $request, SoybeanStock $data_kedelai)
    {
        $request->validate([
            'date' => ['required', 'date', Rule::unique('soybean_stocks')->ignore($data_kedelai->id)],
            'purchase_kg' => ['required', 'numeric', 'min:0'],
            'usage_kg' => ['required', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($request, $data_kedelai) {
            $originalDate = $data_kedelai->date;

            $data_kedelai->update([
                'date' => $request->date, // Mengizinkan update tanggal
                'purchase_kg' => $request->purchase_kg,
                'usage_kg' => $request->usage_kg,
                'notes' => $request->notes,
            ]);

            // Tentukan tanggal mana yang menjadi awal perhitungan ulang
            $recalculationStartDate = min($originalDate, $request->date);
            $this->recalculateStockFrom(Carbon::parse($recalculationStartDate)->subDay()->toDateString());
        });

        return redirect()->route('data.kedelai.index')->with('status', 'Data berhasil diperbarui.');
    }

    /**
     * Menghapus data dari database.
     */
    public function destroy(SoybeanStock $data_kedelai)
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('data.kedelai.index')->with('error', 'Anda tidak memiliki hak akses untuk menghapus data.');
        }

        DB::transaction(function () use ($data_kedelai) {
            $dateBefore = Carbon::parse($data_kedelai->date)->subDay()->toDateString();
            $data_kedelai->delete();
            // Hitung ulang semua stok setelah data yang dihapus
            $this->recalculateStockFrom($dateBefore);
        });

        return redirect()->route('data.kedelai.index')->with('status', 'Data berhasil dihapus.');
    }

    /**
     * Helper function untuk menghitung ulang stok.
     */
    protected function recalculateStockFrom($date)
    {
        $stocksToUpdate = SoybeanStock::where('date', '>', $date)->orderBy('date', 'asc')->get();
        $previousDayStock = SoybeanStock::where('date', '<=', $date)->orderBy('date', 'desc')->first();
        $currentStock = $previousDayStock ? $previousDayStock->closing_stock_kg : 0;
        
        foreach ($stocksToUpdate as $stock) {
            $closingStock = $currentStock + $stock->purchase_kg - $stock->usage_kg;
            
            // Hanya update jika nilainya berubah untuk efisiensi
            if ($stock->closing_stock_kg != $closingStock) {
                $stock->closing_stock_kg = $closingStock;
                $stock->save(['timestamps' => false]);
            }

            $currentStock = $closingStock;
        }
    }
}