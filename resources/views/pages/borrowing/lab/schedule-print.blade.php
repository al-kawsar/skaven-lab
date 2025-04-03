<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Penggunaan Laboratorium</title>
    <style>
        /* Styles for schedule format */
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

        .schedule-title {
            font-size: 16px;
            font-weight: bold;
            margin: 20px 0;
            text-align: center;
            text-transform: uppercase;
        }

        .lab-info {
            margin-bottom: 20px;
        }

        .schedule-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 12px;
        }

        .schedule-table th,
        .schedule-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        .schedule-table th {
            background-color: #f2f2f2;
        }

        .schedule-item {
            margin-bottom: 5px;
            padding: 5px;
            background-color: #f9f9f9;
            border-radius: 3px;
            font-size: 11px;
        }

        .time-header {
            width: 80px;
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
        <button onclick="window.print()">Cetak Jadwal</button>
        <button onclick="window.close()">Tutup</button>
    </div>

    <div class="header">
        <h1>SEKOLAH MENENGAH KEJURUAN NEGERI X KOTA Y</h1>
        <p>Jl. Pendidikan No. 123, Kota Y, Provinsi Z, Kode Pos 12345</p>
    </div>

    <div class="schedule-title">JADWAL PENGGUNAAN LABORATORIUM</div>

    <div class="lab-info">
        <p><strong>Laboratorium:</strong> {{ $lab->name }}</p>
        <p><strong>Lokasi:</strong> {{ $lab->location }}</p>
        <p><strong>Periode:</strong> {{ $start_date->locale('id')->isoFormat('D MMMM Y') }} -
            {{ $end_date->locale('id')->isoFormat('D MMMM Y') }}</p>
    </div>

    <table class="schedule-table">
        <thead>
            <tr>
                <th>Jam</th>
                @foreach ($dates as $date)
                    <th>{{ $date->locale('id')->isoFormat('ddd, D MMM') }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($timeSlots as $slot)
                <tr>
                    <td class="time-header">{{ $slot }}</td>
                    @foreach ($dates as $date)
                        <td>
                            @foreach ($schedule[$date->format('Y-m-d')][$slot] ?? [] as $booking)
                                <div class="schedule-item">
                                    {{ $booking->event }} ({{ $booking->start_time }} - {{ $booking->end_time }})<br>
                                    <small>{{ $booking->user->name }}</small>
                                </div>
                            @endforeach
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Jadwal ini dicetak pada {{ now()->locale('id')->isoFormat('dddd, D MMMM Y HH:mm:ss') }} WIB</p>
        <p>Sistem Manajemen Peminjaman Laboratorium SMKN X</p>
    </div>
</body>

</html>
