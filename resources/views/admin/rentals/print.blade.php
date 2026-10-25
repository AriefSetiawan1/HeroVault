<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Riwayat Penyewaan - HeroVault</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            color: #1a202c;
            background: #fff;
            margin: 20px;
            font-size: 12px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0 0 5px 0;
            font-size: 20px;
            text-transform: uppercase;
        }
        .header p {
            margin: 0;
            color: #555;
            font-size: 12px;
        }
        .meta-box {
            margin-bottom: 15px;
            font-size: 12px;
        }
        .meta-box table {
            width: auto;
            border-collapse: collapse;
        }
        .meta-box td {
            padding: 3px 8px 3px 0;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table.data-table th, table.data-table td {
            border: 1px solid #cbd5e0;
            padding: 8px 10px;
            text-align: left;
        }
        table.data-table th {
            background-color: #f7fafc;
            font-weight: bold;
        }
        .text-right { text-align: right !important; }
        .text-center { text-align: center !important; }
        .total-box {
            margin-top: 15px;
            text-align: right;
            font-size: 14px;
            font-weight: bold;
        }
        .signature-area {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
        }
        .signature-box {
            text-align: center;
            width: 200px;
        }
        .signature-line {
            margin-top: 60px;
            border-top: 1px solid #000;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                margin: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Print Action Bar for Screen -->
    <div class="no-print" style="margin-bottom: 20px; padding: 12px; background: #edf2f7; border-radius: 8px; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <strong>Mode Pratinjau Cetak:</strong> Siap dicetak langsung ke kertas atau diekspor ke PDF via browser.
        </div>
        <div>
            <button onclick="window.print()" style="padding: 8px 16px; background: #3182ce; color: #fff; border: none; border-radius: 6px; font-weight: bold; cursor: pointer;">
                🖨️ Cetak Dokumen (Print)
            </button>
            <button onclick="window.close()" style="padding: 8px 16px; background: #e2e8f0; color: #333; border: 1px solid #cbd5e0; border-radius: 6px; margin-left: 8px; cursor: pointer;">
                Tutup
            </button>
        </div>
    </div>

    <div class="header">
        <h1>HeroVault — Kota Metrodana</h1>
        <p>Laporan Riwayat Penyewaan Kostum dan Senjata Superhero Pensiunan</p>
    </div>

    <div class="meta-box">
        <table>
            <tr>
                <td><strong>Tanggal Cetak</strong></td>
                <td>: {{ now()->translatedFormat('d F Y, H:i') }} WIB</td>
            </tr>
            <tr>
                <td><strong>Dicetak Oleh</strong></td>
                <td>: {{ auth()->user()->name }} (Penjaga Gudang)</td>
            </tr>
            @if(request('start_date') || request('end_date'))
                <tr>
                    <td><strong>Periode Sewa</strong></td>
                    <td>: {{ request('start_date') ? date('d/m/Y', strtotime(request('start_date'))) : 'Awal' }} s/d {{ request('end_date') ? date('d/m/Y', strtotime(request('end_date'))) : 'Sekarang' }}</td>
                </tr>
            @endif
            @if($selectedMember)
                <tr>
                    <td><strong>Penyewa Tertentu</strong></td>
                    <td>: {{ $selectedMember->name }} ({{ $selectedMember->email }})</td>
                </tr>
            @endif
            @if(request('status'))
                <tr>
                    <td><strong>Filter Status</strong></td>
                    <td>: {{ ucfirst(request('status')) }}</td>
                </tr>
            @endif
        </table>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 30px;" class="text-center">No</th>
                <th>Penyewa</th>
                <th>Kode & Perlengkapan</th>
                <th>Tgl Sewa</th>
                <th>Jatuh Tempo</th>
                <th>Tgl Kembali</th>
                <th>Kondisi</th>
                <th>Status</th>
                <th class="text-right">Denda</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rentals as $rental)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $rental->user->name }}</td>
                    <td>[{{ $rental->gear->unit_code }}] {{ $rental->gear->name }}</td>
                    <td>{{ $rental->rented_at->format('d/m/Y') }}</td>
                    <td>{{ $rental->due_at->format('d/m/Y') }}</td>
                    <td>{{ $rental->returned_at ? $rental->returned_at->format('d/m/Y') : '-' }}</td>
                    <td style="text-transform: capitalize;">{{ $rental->return_condition ?? '-' }}</td>
                    <td>{{ $rental->status === 'dipinjam' ? 'Dipinjam' : 'Dikembalikan' }}</td>
                    <td class="text-right">Rp {{ number_format($rental->fine, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center">Tidak ada transaksi ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="total-box">
        Total Denda Keseluruhan: Rp {{ number_format($totalFine, 0, ',', '.') }}
    </div>

    <div class="signature-area">
        <div class="signature-box">
            Mengetahui,<br>
            Kepala Gudang Metrodana
            <div class="signature-line"></div>
        </div>
        <div class="signature-box">
            Petugas Piket,<br>
            {{ auth()->user()->name }}
            <div class="signature-line"></div>
        </div>
    </div>
</body>
</html>
