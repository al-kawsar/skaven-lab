<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Peminjaman Lab - {{ $borrowing->lab->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }

        .school-logo {
            max-width: 80px;
            height: auto;
        }

        .school-name {
            font-size: 18px;
            font-weight: bold;
            margin: 10px 0 5px;
        }

        .school-address {
            font-size: 12px;
            margin: 0;
        }

        .document-title {
            font-size: 16px;
            font-weight: bold;
            margin: 20px 0;
            text-align: center;
            text-transform: uppercase;
        }

        .content {
            margin-bottom: 30px;
        }

        .content-section {
            margin-bottom: 20px;
        }

        .content-title {
            font-weight: bold;
            margin-bottom: 10px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 8px 5px;
            vertical-align: top;
        }

        .info-table td:first-child {
            width: 150px;
            font-weight: bold;
        }

        .signature-section {
            display: flex;
            justify-content: space-between;
            margin-top: 50px;
        }

        .signature-box {
            width: 45%;
            text-align: center;
        }

        .signature-name {
            margin-top: 60px;
            font-weight: bold;
            border-top: 1px solid #333;
            padding-top: 5px;
            display: inline-block;
        }

        .note-section {
            margin-top: 30px;
            border: 1px solid #ddd;
            padding: 10px;
            font-size: 12px;
            background-color: #f9f9f9;
        }

        .qr-code {
            text-align: right;
            margin-top: 20px;
        }

        .qr-code img {
            max-width: 100px;
            height: auto;
        }

        .footer {
            margin-top: 30px;
            font-size: 10px;
            text-align: center;
            color: #777;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }

        @media print {
            body {
                padding: 0;
                margin: 0;
            }

            .no-print {
                display: none;
            }

            @page {
                size: A4;
                margin: 1.5cm;
            }
        }
    </style>
</head>

<body>
    <div class="no-print" style="text-align: center; margin-bottom: 20px;">
        <button onclick="window.print()">Cetak Dokumen</button>
        <button onclick="window.close()">Tutup</button>
    </div>

    <div class="header">
        <img src="{{ $school['logo'] }}" alt="Logo Sekolah" class="school-logo">
        <h1 class="school-name">{{ $school['name'] }}</h1>
        <p class="school-address">{{ $school['address'] }}</p>
        <p class="school-address">Telp: {{ $school['phone'] }} | Email: {{ $school['email'] }} | Website:
            {{ $school['website'] }}</p>
    </div>

    <div class="document-title">BUKTI PEMINJAMAN LABORATORIUM</div>

    <div class="content">
        <div class="content-section">
            <div class="content-title">Informasi Peminjam</div>
            <table class="info-table">
                <tr>
                    <td>Nama</td>
                    <td>: {{ $borrowing->user->name }}</td>
                </tr>
                <tr>
                    <td>Status</td>
                    <td>: {{ $borrowing->user->roles->first()->name ?? 'Pengguna' }}</td>
                </tr>
                <tr>
                    <td>NIS/NIP</td>
                    <td>: {{ $borrowing->user->identity_number ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Kontak</td>
                    <td>: {{ $borrowing->user->phone ?? '-' }}</td>
                </tr>
            </table>
        </div>

        <div class="content-section">
            <div class="content-title">Informasi Peminjaman</div>
            <table class="info-table">
                <tr>
                    <td>Nomor Peminjaman</td>
                    <td>: {{ substr($borrowing->id, 0, 8) }}</td>
                </tr>
                <tr>
                    <td>Tanggal Peminjaman</td>
                    <td>:
                        {{ \Carbon\Carbon::parse($borrowing->borrow_date)->locale('id')->isoFormat('dddd, D MMMM Y') }}
                    </td>
                </tr>
                <tr>
                    <td>Waktu Penggunaan</td>
                    <td>: {{ $borrowing->start_time }} - {{ $borrowing->end_time }} WIB</td>
                </tr>
                <tr>
                    <td>Status</td>
                    <td>: <strong>{{ strtoupper($borrowing->status) }}</strong></td>
                </tr>
                <tr>
                    <td>Disetujui Pada</td>
                    <td>: {{ $borrowing->updated_at->locale('id')->isoFormat('D MMMM Y HH:mm') }} WIB</td>
                </tr>
            </table>
        </div>

        <div class="content-section">
            <div class="content-title">Informasi Laboratorium</div>
            <table class="info-table">
                <tr>
                    <td>Nama Lab</td>
                    <td>: {{ $borrowing->lab->name }}</td>
                </tr>
            </table>
        </div>

        <div class="content-section">
            <div class="content-title">Kegiatan</div>
            <p>{{ $borrowing->event }}</p>
            @if ($borrowing->notes)
                <div style="margin-top: 10px;">
                    <strong>Catatan:</strong>
                    <p>{{ $borrowing->notes }}</p>
                </div>
            @endif
        </div>
    </div>

    <div class="signature-section">
        <div class="signature-box">
            <p>Peminjam</p>
            <div class="signature-name">{{ $borrowing->user->name }}</div>
        </div>
        <div class="signature-box">
            <p>Kepala Laboratorium</p>
            <div class="signature-name">
                {{ auth()->user()->hasRole(['admin', 'superadmin'])? auth()->user()->name: 'Admin Lab' }}</div>
        </div>
    </div>

    <div class="note-section">
        <strong>Ketentuan Peminjaman:</strong>
        <ol style="margin: 5px 0; padding-left: 25px;">
            <li>Peminjam bertanggung jawab atas kebersihan dan keutuhan lab selama penggunaan</li>
            <li>Kerusakan akibat kelalaian peminjam menjadi tanggung jawab peminjam</li>
            <li>Peminjam wajib mematikan semua peralatan elektronik setelah selesai</li>
            <li>Dokumen ini wajib dibawa saat menggunakan lab</li>
            <li>Dalam kondisi darurat, pihak sekolah berhak membatalkan peminjaman</li>
        </ol>
    </div>

    <div class="qr-code">
        <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ url('/verify/' . $borrowing->id) }}"
            alt="QR Code Verifikasi">
    </div>

    <div class="footer">
        <p>Dokumen ini dicetak pada {{ now()->locale('id')->isoFormat('dddd, D MMMM Y HH:mm:ss') }} WIB</p>
        <p>Sistem Manajemen Peminjaman Laboratorium {{ $school['name'] }}</p>
    </div>
</body>

</html>
