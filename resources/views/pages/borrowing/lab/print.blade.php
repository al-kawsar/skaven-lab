<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Peminjaman Laboratorium</title>
    <style>
        @page {
            margin: 2.5cm 2cm;
        }

        body {
            font-family: "Times New Roman", Times, serif;
            margin: 0;
            padding: 0;
            position: relative;
            color: #000;
            line-height: 1.6;
            font-size: 12pt;
        }

        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.08;
            z-index: -1;
            width: 70%;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px double #000;
            padding-bottom: 15px;
            position: relative;
        }

        .logo {
            width: 80px;
            height: auto;
            position: absolute;
            left: 0;
            top: 0;
        }

        h1 {
            font-size: 16pt;
            font-weight: bold;
            margin: 8px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .school-meta {
            font-size: 11pt;
            margin-bottom: 6px;
        }

        .title {
            text-align: center;
            font-size: 14pt;
            font-weight: bold;
            margin: 30px 0;
            text-decoration: underline;
            letter-spacing: 1px;
        }

        .content {
            margin-top: 30px;
        }

        .section {
            margin-bottom: 20px;
        }

        .section p {
            text-align: justify;
            margin: 10px 0;
            line-height: 1.8;
        }

        .section-title {
            font-weight: bold;
            margin-bottom: 10px;
            text-transform: uppercase;
            font-size: 12pt;
        }

        table.details {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0 25px 0;
        }

        table.details td {
            padding: 8px 5px;
            vertical-align: top;
            line-height: 1.5;
        }

        table.details td:first-child {
            width: 35%;
        }

        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 60px;
        }

        .signature-box {
            width: 45%;
            text-align: center;
        }

        .sign-area {
            height: 80px;
            margin-bottom: 10px;
        }

        .notes {
            margin: 30px 0;
            font-size: 11pt;
            border: 1px solid #000;
            padding: 15px;
            background-color: #f9f9f9;
        }

        .notes .section-title {
            text-align: center;
            margin-bottom: 10px;
        }

        .qr-code {
            position: absolute;
            right: 0;
            bottom: 60px;
        }

        .footer {
            margin-top: 60px;
            font-size: 10pt;
            text-align: center;
            border-top: 1px solid #000;
            padding-top: 15px;
        }

        .colon-format td:nth-child(2) {
            width: 15px;
            text-align: center;
            padding: 8px 0;
        }

        p {
            margin: 10px 0;
        }

        .stamp-area {
            font-style: italic;
            font-size: 10pt;
            margin-top: 5px;
        }

        .reg-number {
            font-family: "Courier New", Courier, monospace;
            font-weight: bold;
        }
    </style>
</head>

<body>
    {{-- @if (isset($watermarkBase64))
        <div class="watermark">
            <img src="{{ $watermarkBase64 }}" alt="Watermark">
        </div>
    @endif --}}

    <div class="header">
        @if (isset($logoBase64))
            <img src="{{ $logoBase64 }}" alt="Logo" class="logo">
        @endif
        <h1>{{ settings('school_name') }}</h1>
        <div class="school-meta">{{ settings('school_address') }}, {{ settings('school_postal_code') }}</div>
        <div class="school-meta">Telepon: {{ settings('school_phone') }} | Email: {{ settings('school_email') }}</div>
        <div class="school-meta">Website: {{ settings('school_website') }}</div>
    </div>

    <div class="title">BUKTI PEMINJAMAN LABORATORIUM</div>

    <div class="content">
        <div class="section">
            <p>Yang bertanda tangan di bawah ini, Kepala Laboratorium {{ $borrowing->lab->name }}
                {{ settings('school_name') }} menerangkan bahwa:</p>
        </div>

        <div class="section">
            <table class="details colon-format">
                <tr>
                    <td>Nama Lengkap</td>
                    <td>:</td>
                    <td>{{ $borrowing->user->name }}</td>
                </tr>
                <tr>
                    <td>Jabatan/Status</td>
                    <td>:</td>
                    <td>{{ $borrowing->user->role }}</td>
                </tr>
                <tr>
                    <td>NIP/NIS/Nomor Identitas</td>
                    <td>:</td>
                    <td>{{ $borrowing->user->identity_number ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Kontak</td>
                    <td>:</td>
                    <td>{{ $borrowing->user->phone ?? '-' }}</td>
                </tr>
            </table>
        </div>

        <div class="section">
            <p>Telah melakukan peminjaman laboratorium dengan detail sebagai berikut:</p>
            <table class="details colon-format">
                <tr>
                    <td>Laboratorium</td>
                    <td>:</td>
                    <td>{{ $borrowing->lab->name }}</td>
                </tr>
                <tr>
                    <td>Tanggal Peminjaman</td>
                    <td>:</td>
                    <td>{{ \Carbon\Carbon::parse($borrowing->borrow_date)->locale('id')->isoFormat('dddd, D MMMM Y') }}
                    </td>
                </tr>
                <tr>
                    <td>Waktu Penggunaan</td>
                    <td>:</td>
                    <td>Pukul {{ \Carbon\Carbon::parse($borrowing->start_time)->format('H:i') }} -
                        {{ \Carbon\Carbon::parse($borrowing->end_time)->format('H:i') }} {{ settings('time_format') }}
                    </td>
                </tr>
                <tr>
                    <td>Keperluan Kegiatan</td>
                    <td>:</td>
                    <td>{{ $borrowing->event }}</td>
                </tr>
            </table>
        </div>

        <div class="notes">
            <div class="section-title">KETENTUAN PEMINJAMAN</div>
            {!! nl2br(settings('borrowing_terms')) !!}
        </div>

        <div class="signatures">
            <div class="signature-box">
                <p>Peminjam,</p>
                <div class="sign-area"></div>
                <p><strong>{{ $borrowing->user->name }}</strong></p>
            </div>
            <div class="signature-box">
                <p>{{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM Y') }}</p>
                <p>Kepala Laboratorium,</p>
                <div class="sign-area"></div>
                <p><strong>{{ settings('lab_head_name') }}</strong></p>
                <p>NIP. {{ settings('lab_head_nip') }}</p>
                <p class="stamp-area">(Stempel)</p>
            </div>
        </div>

        @if (isset($qrCode))
            <div class="qr-code">
                <img src="data:image/png;base64,{{ $qrCode }}" alt="QR Code Verifikasi" style="width: 100px;">
                <p style="font-size: 10px;">Scan untuk verifikasi</p>
            </div>
        @endif
    </div>

    <div class="footer">
        <p>Dokumen ini diterbitkan pada {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y') }}</p>
        <p>No. Registrasi: <span
                class="reg-number">{{ $borrowing->id }}/LAB/{{ \Carbon\Carbon::now()->format('Y') }}</span></p>
        <p style="font-style: italic; font-size: 10px; margin-top: 15px;">Dokumen ini sah tanpa tanda tangan dalam
            bentuk elektronik dan berstempel basah.</p>
        <p>{{ settings('print_footer_text') }}</p>
    </div>
</body>

</html>
