<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penggunaan Laboratorium</title>
    <style>
        /* CSS styles similar to above, with adjustments for report format */
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }

        .report-title {
            font-size: 16px;
            font-weight: bold;
            margin: 20px 0;
            text-align: center;
            text-transform: uppercase;
        }

        .report-info {
            margin-bottom: 20px;
            font-size: 12px;
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 12px;
        }

        table.data-table th,
        table.data-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        table.data-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        table.data-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .summary {
            margin-top: 20px;
            font-weight: bold;
        }

        .footer {
            margin-top: 30px;
            font-size: 10px;
            text-align: center;
            color: #777;
        }

        @media print {
            .no-print {
                display: none;
            }

            @page {
                size: landscape;
                margin: 1cm;
            }
        }
    </style>
</head>

<body>
    <div class="no-print" style="text-align: center; margin-bottom: 20px;">
        <button onclick="window.print()">Cetak Laporan</button>
        <button onclick="window.close()">Tutup</button>
    </div>

    <div class="header">
        <h1>SEKOLAH MENENGAH KEJURUAN NEGERI X KOTA Y</h1>
        <p>Jl. Pendidikan No. 123, Kota Y, Provinsi Z, Kode Pos 12345</p>
    </div>

    <div class="report-title">LAPORAN PENGGUNAAN LABORATORIUM</div>

    <div class="report-info">
        <p><strong>Periode:</strong> {{ $start_date->locale('id')->isoFormat('D MMMM Y') }} -
            {{ $end_date->locale('id')->isoFormat('D MMMM Y') }}</p>
        <p><strong>Laboratorium:</strong> {{ $lab ? $lab->name : 'Semua Laboratorium' }}</p>
        <p><strong>Dicetak oleh:</strong> {{ auth()->user()->name }} ({{ auth()->user()->role }})</p>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Laboratorium</th>
                <th>Peminjam</th>
                <th>Waktu</th>
                <th>Kegiatan</th>
                <th>Status</th>
                <th>Durasi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($borrowings as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->borrow_date)->locale('id')->isoFormat('D MMM Y') }}</td>
                    <td>{{ $item->lab->name }}</td>
                    <td>{{ $item->user->name }}</td>
                    <td>{{ $item->start_time }} - {{ $item->end_time }}</td>
                    <td>{{ $item->event }}</td>
                    <td>{{ ucfirst($item->status) }}</td>
                    <td>
                        @php
                            $start = \Carbon\Carbon::parse($item->borrow_date . ' ' . $item->start_time);
                            $end = \Carbon\Carbon::parse($item->borrow_date . ' ' . $item->end_time);
                            $duration = $start->diffInMinutes($end);
                            echo floor($duration / 60) . ' jam ' . $duration % 60 . ' menit';
                        @endphp
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary">
        <p>Total Peminjaman: {{ count($borrowings) }}</p>
        <p>Disetujui: {{ $borrowings->where('status', 'disetujui')->count() }}</p>
        <p>Ditolak: {{ $borrowings->where('status', 'ditolak')->count() }}</p>
        <p>Dibatalkan: {{ $borrowings->where('status', 'dibatalkan')->count() }}</p>
        <p>Selesai: {{ $borrowings->where('status', 'selesai')->count() }}</p>
    </div>

    <div class="footer">
        <p>Dicetak pada {{ now()->locale('id')->isoFormat('dddd, D MMMM Y HH:mm:ss') }} WIB</p>
        <p>Sistem Manajemen Peminjaman Laboratorium SMKN X</p>
    </div>
</body>

</html>
