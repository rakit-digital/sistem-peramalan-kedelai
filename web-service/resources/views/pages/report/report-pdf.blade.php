<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Kedelai</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            color: #333;
        }
        .container {
            width: 100%;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #2c3e50;
        }
        .header p {
            margin: 5px 0 0;
            font-size: 14px;
        }
        .info {
            margin-bottom: 20px;
        }
        .info table {
            width: 100%;
            border-collapse: collapse;
        }
        .info th, .info td {
            padding: 5px;
            text-align: left;
        }
        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .report-table th, .report-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .report-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #888;
        }
        .total {
            font-weight: bold;
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    @php
        // Logika untuk judul dan nama kolom
        $reportTitle = '';
        $columnHeader = '';
        $totalValue = 0;
        $valueField = '';

        if ($reportType == 'usage') {
            $reportTitle = 'Laporan Penggunaan Harian';
            $columnHeader = 'Penggunaan (kg)';
            $valueField = 'usage_kg';
        } elseif ($reportType == 'stock') {
            $reportTitle = 'Laporan Stok Harian';
            $columnHeader = 'Stok Akhir (kg)';
            $valueField = 'closing_stock_kg';
        } elseif ($reportType == 'purchase') {
            $reportTitle = 'Laporan Pembelian';
            $columnHeader = 'Pembelian (kg)';
            $valueField = 'purchase_kg';
        }
        // Hitung total
        if($valueField) {
            $totalValue = $results->sum($valueField);
        }
    @endphp

    <div class="container">
        <div class="header">
            <h1>Pabrik Tahu Melati</h1>
            <p>Laporan Stok Kedelai</p>
        </div>

        <div class="info">
            <table>
                <tr>
                    <th style="width: 120px;">Jenis Laporan</th>
                    <td>: {{ $reportTitle }}</td>
                </tr>
                <tr>
                    <th>Periode</th>
                    <td>: {{ $startDate->isoFormat('D MMMM Y') }} - {{ $endDate->isoFormat('D MMMM Y') }}</td>
                </tr>
                 <tr>
                    <th>Tanggal Cetak</th>
                    <td>: {{ now()->isoFormat('D MMMM Y, HH:mm') }}</td>
                </tr>
            </table>
        </div>

        <table class="report-table">
            <thead>
                <tr>
                    <th style="width: 40px;">No.</th>
                    <th>Tanggal</th>
                    <th class="text-right">{{ $columnHeader }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($results as $index => $result)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $result->date->isoFormat('dddd, D MMMM Y') }}</td>
                    <td class="text-right">
                        {{-- Tampilkan nilai berdasarkan jenis laporan --}}
                        @if ($reportType == 'usage')
                            {{ number_format($result->usage_kg, 2) }}
                        @elseif ($reportType == 'stock')
                            {{-- Untuk laporan stok, tidak ada total, hanya nilai harian --}}
                            {{ number_format($result->closing_stock_kg, 2) }}
                        @elseif ($reportType == 'purchase')
                            {{ number_format($result->purchase_kg, 2) }}
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" style="text-align: center; padding: 20px;">Tidak ada data ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
            {{-- Tampilkan baris total hanya untuk laporan penggunaan dan pembelian --}}
            @if(in_array($reportType, ['usage', 'purchase']) && $results->isNotEmpty())
            <tfoot>
                <tr class="total">
                    <td colspan="2" style="font-weight: bold; text-align: right;">TOTAL</td>
                    <td class="text-right" style="font-weight: bold;">{{ number_format($totalValue, 2) }}</td>
                </tr>
            </tfoot>
            @endif
        </table>

        <div class="footer">
            Dicetak oleh Sistem Peramalan Pabrik Tahu Melati
        </div>
    </div>
</body>
</html>