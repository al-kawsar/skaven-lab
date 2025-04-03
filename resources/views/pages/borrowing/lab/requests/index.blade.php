@extends('layouts.app-layout')
<style>
    .cursor-pointer {
        cursor: pointer;
    }

    .time-display {
        font-weight: 500;
    }

    .time-display small {
        font-size: 80%;
        opacity: 0.8;
    }

    /* Efek blink untuk timer yang sedang berjalan */
    @keyframes pulse {
        0% {
            opacity: 1;
        }

        50% {
            opacity: 0.7;
        }

        100% {
            opacity: 1;
        }
    }

    .timer-active {
        animation: pulse 2s infinite;
    }
</style>

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card card-table comman-shadow">
                <div class="card-body">
                    <div class="page-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="page-title"><i class="fas fa-clipboard-list me-2"></i>Peminjaman Saya</h3>
                            </div>
                            <div class="col-auto">
                                {{-- <div class="dropdown">
                                    <button class="btn btn-outline-secondary dropdown-toggle" type="button"
                                        id="historyDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-history me-1"></i> Riwayat Peminjaman
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="historyDropdown">
                                        <li><a class="dropdown-item history-period" href="#" data-period="month">Bulan
                                                ini</a></li>
                                        <li><a class="dropdown-item history-period" href="#" data-period="3months">3
                                                Bulan Terakhir</a></li>
                                        <li><a class="dropdown-item history-period" href="#" data-period="6months">6
                                                Bulan Terakhir</a></li>
                                        <li><a class="dropdown-item history-period" href="#" data-period="year">1
                                                Tahun Terakhir</a></li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li><a class="dropdown-item history-period" href="#" data-period="all">Semua
                                                Riwayat</a></li>
                                    </ul>
                                </div> --}}
                                <a href="{{ route('borrowing.lab.index') }}" class="btn btn-outline-primary ms-2">
                                    <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar Lab
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- <div class="card">
                        <div class="card-body">
                            <ul class="nav nav-pills nav-fill" id="borrowingStatusTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="all-tab" data-status="" type="button">
                                        <i class="fas fa-list me-1"></i> Semua
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="active-tab" data-status="aktif" type="button">
                                        <i class="fas fa-play-circle me-1"></i> Aktif
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="pending-tab" data-status="menunggu" type="button">
                                        <i class="fas fa-clock me-1"></i> Menunggu
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="completed-tab" data-status="selesai" type="button">
                                        <i class="fas fa-check-circle me-1"></i> Selesai
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="rejected-tab" data-status="ditolak" type="button">
                                        <i class="fas fa-times-circle me-1"></i> Ditolak
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div> --}}

                    {{-- <div class="card">
                        <div class="card-body">
                        </div>
                    </div> --}}

                    <x-data-table name="borrowTable" :header="[
                        '#',
                        'Tanggal Peminjaman',
                        'Waktu Mulai',
                        'Waktu Selesai',
                        'Sisa Waktu',
                        'Keperluan',
                        'Status',
                        'Aksi',
                    ]" />

                    {{-- <div class="mt-3 text-center text-muted small">
                        <p><i class="fas fa-info-circle me-1"></i>Peminjaman yang sudah selesai akan otomatis ditandai
                            sebagai dikembalikan.</p>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detail Peminjaman -->
    <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable ">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailModalLabel">Detail Peminjaman</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="detailModalBody">

                    <!-- Tab untuk timeline dan history -->
                    <ul class="nav nav-tabs" id="borrowingDetailTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="timeline-tab" data-bs-toggle="tab"
                                data-bs-target="#timeline" type="button" role="tab" aria-controls="timeline"
                                aria-selected="true">Timeline</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="history-tab" data-bs-toggle="tab" data-bs-target="#history"
                                type="button" role="tab" aria-controls="history" aria-selected="false">Riwayat
                                Aktivitas</button>
                        </li>
                    </ul>

                    <div class="tab-content p-3 border border-top-0 rounded-bottom" id="borrowingDetailTabsContent">
                        <div class="tab-pane fade show active" id="timeline" role="tabpanel"
                            aria-labelledby="timeline-tab">
                            <div id="borrowing-timeline"></div>
                        </div>
                        <div class="tab-pane fade" id="history" role="tabpanel" aria-labelledby="history-tab">
                            <div id="borrowing-history"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        var counter = 0;

        // Filter UX improvements
        $(document).ready(function() {
            // Status filter buttons
            $('.status-filter').on('click', function() {
                $('.status-filter').removeClass('active');
                $(this).addClass('active');
                $('#statusInput').val($(this).data('value'));
                $('#filterForm').submit();
            });

            // Clear date inputs
            $('.clear-date').on('click', function() {
                const targetName = $(this).data('target');
                $(`input[name="${targetName}"]`).val('');
                $('#filterForm').submit();
            });

            // Clear text input
            $('.clear-input').on('click', function() {
                const targetName = $(this).data('target');
                $(`input[name="${targetName}"]`).val('');
                $('#filterForm').submit();
            });

            // Auto-submit on date change
            $('.date-picker').on('change', function() {
                $('#filterForm').submit();
            });

            // Prevent default form submission and handle filtering via DataTable
            $('#filterForm').on('submit', function(e) {
                e.preventDefault();
                table.ajax.reload();
            });


            let table = $('#borrowTable').DataTable({
                "language": {
                    "sProcessing": "Sedang diproses...",
                    "sLengthMenu": "Menampilkan _MENU_ data per halaman",
                    "sZeroRecords": "Data tidak ditemukan",
                    "sInfo": "Menampilkan data _START_ hingga _END_ dari total _TOTAL_ data",
                    "sInfoEmpty": "Tidak ada data yang dapat ditampilkan",
                    "sInfoFiltered": "(difilter dari _MAX_ total data)",
                    "sInfoPostFix": "",
                    "sSearch": "Pencarian:",
                    "sUrl": "",
                    "oPaginate": {
                        "sFirst": "Awal",
                        "sPrevious": "Kembali",
                        "sNext": "Lanjut",
                        "sLast": "Akhir"
                    }
                },
                ajax: {
                    url: "{{ route('borrowing.ajax') }}",
                    type: 'GET',
                    dataType: 'json',
                    dataSrc: 'data',
                    data: function(d) {
                        d.status = $('input[name="status"]').val();
                        d.start_date = $('input[name="start_date"]').val();
                        d.end_date = $('input[name="end_date"]').val();
                        d.lab = $('input[name="lab"]').val();
                    }
                },
                searching: true,
                serverSide: false,
                columns: [{
                        data: 'number'
                    },
                    {
                        data: 'borrow_date',
                        render: function(data, type, row) {
                            return data;
                        }
                    },
                    {
                        data: 'start_time',
                        render: function(data, type, row) {
                            // Format time to remove seconds and add friendly context
                            const formattedTime = formatTimeForDisplay(data);
                            return '<span class="time-display"><i class="far fa-clock me-1 text-primary"></i>' +
                                formattedTime + '</span>';
                        }
                    },
                    {
                        data: 'end_time',
                        render: function(data, type, row) {
                            // Format time to remove seconds and add friendly context
                            const formattedTime = formatTimeForDisplay(data);
                            return '<span class="time-display"><i class="far fa-clock me-1 text-danger"></i>' +
                                formattedTime + '</span>';
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        render: function(data, type, full, meta) {
                            // Format tanggal peminjaman dari data yang ada
                            try {
                                // Periksa format tanggal yang diterima - asumsi full.borrow_date sudah dalam format yang benar
                                let borrowDate;

                                // Cek apakah format tanggal seperti "2023-07-10" (dari database) atau "Senin, 10 Juli 2023" (sudah diformat)
                                if (full.borrow_date.includes('-')) {
                                    // Format database YYYY-MM-DD
                                    borrowDate = new Date(full.borrow_date);
                                } else {
                                    // Format yang sudah dilocalize seperti "Senin, 10 Juli 2023"
                                    const dateParts = full.borrow_date.split(', ')[1].split(' ');
                                    const day = parseInt(dateParts[0]);
                                    const monthNames = ['januari', 'februari', 'maret', 'april',
                                        'mei', 'juni', 'juli', 'agustus', 'september',
                                        'oktober', 'november', 'desember'
                                    ];
                                    const month = monthNames.indexOf(dateParts[1].toLowerCase());
                                    const year = parseInt(dateParts[2]);

                                    if (month === -1) {
                                        // Jika bulan tidak dikenali, tampilkan pesan error yang user-friendly
                                        return '<span class="text-danger"><i class="fas fa-exclamation-circle me-1"></i>Format tanggal tidak valid</span>';
                                    }

                                    borrowDate = new Date(year, month, day);
                                }

                                // Parse waktu mulai dan selesai
                                const [startHour, startMinute] = full.start_time.split(':').map(
                                    Number);
                                const [endHour, endMinute] = full.end_time.split(':').map(Number);

                                // Set waktu mulai dan selesai pada tanggal peminjaman
                                const startDateTime = new Date(borrowDate);
                                startDateTime.setHours(startHour, startMinute, 0);

                                const endDateTime = new Date(borrowDate);
                                endDateTime.setHours(endHour, endMinute, 0);

                                // Handle overnight bookings (jika waktu selesai lebih awal dari waktu mulai)
                                if (endHour < startHour || (endHour === startHour && endMinute <
                                        startMinute)) {
                                    endDateTime.setDate(endDateTime.getDate() + 1);
                                }

                                const currentDate = new Date();

                                // Function to format the countdown display
                                function formatRemainingTime() {
                                    // Handle status menunggu
                                    if (full.status === 'menunggu') {
                                        return '<span class="fw-bold"><i class="fas fa-pause-circle me-1"></i>Menunggu persetujuan</span>';
                                    }

                                    // Handle status ditolak
                                    if (full.status === 'ditolak') {
                                        return '<span class="fw-bold"><i class="fas fa-hourglass-end me-1"></i>Waktu Telah Berakhir</span>';
                                        // return '<span class="fw-bold"><i class="fas fa-times-circle me-1"></i>Ditolak</span>';
                                    }

                                    // Handle status dibatalkan
                                    if (full.status === 'dibatalkan') {
                                        return '<span class="fw-bold"><i class="fas fa-hourglass-end me-1"></i>Waktu Telah Berakhir</span>';
                                        // return '<span class="fw-bold"><i class="fas fa-ban me-1"></i>Dibatalkan</span>';
                                    }

                                    // Handle status selesai
                                    if (full.status === 'selesai') {
                                        return '<span class="fw-bold"><i class="fas fa-hourglass-end me-1"></i>Waktu Telah Berakhir</span>';
                                        // return '<span class="fw-bold"><i class="fas fa-check-double me-1"></i>Selesai</span>';
                                    }

                                    // Untuk status disetujui, cek apakah sudah lewat waktu
                                    const now = new Date();

                                    // Jika waktu saat ini sebelum waktu mulai
                                    if (now < startDateTime && full.status === 'disetujui') {
                                        const remainingTime = startDateTime.getTime() - now
                                            .getTime();
                                        const days = Math.floor(remainingTime / (1000 * 60 * 60 *
                                            24));
                                        const hours = Math.floor((remainingTime % (1000 * 60 * 60 *
                                            24)) / (1000 * 60 * 60));
                                        const minutes = Math.floor((remainingTime % (1000 * 60 *
                                            60)) / (1000 * 60));
                                        const seconds = Math.floor((remainingTime % (1000 * 60)) /
                                            1000);

                                        let timeDisplay = [];
                                        if (days > 0) timeDisplay.push(`${days} Hari`);
                                        if (hours > 0) timeDisplay.push(`${hours} Jam`);
                                        if (minutes > 0) timeDisplay.push(`${minutes} Menit`);
                                        timeDisplay.push(`${seconds} Detik`);

                                        return `<span class="fw-bold"><i class="fas fa-hourglass-start me-1"></i>Mulai dalam: ${timeDisplay.join(' ')}</span>`;
                                    }

                                    // Jika masih dalam rentang waktu peminjaman
                                    if (now >= startDateTime && now <= endDateTime && full
                                        .status === 'disetujui') {
                                        const remainingTime = endDateTime.getTime() - now.getTime();
                                        const hours = Math.floor(remainingTime / (1000 * 60 * 60));
                                        const minutes = Math.floor((remainingTime % (1000 * 60 *
                                            60)) / (1000 * 60));
                                        const seconds = Math.floor((remainingTime % (1000 * 60)) /
                                            1000);

                                        let timeDisplay = [];
                                        if (hours > 0) timeDisplay.push(`${hours} Jam`);
                                        if (minutes > 0) timeDisplay.push(`${minutes} Menit`);
                                        timeDisplay.push(`${seconds} Detik`);

                                        return `<span class="fw-bold timer-active"><i class="fas fa-hourglass-half me-1"></i>Berlangsung: ${timeDisplay.join(' ')}</span>`;
                                    }

                                    // Jika sudah lewat waktu
                                    return '<span class=" fw-bold"><i class="fas fa-hourglass-end me-1"></i>Waktu Telah Berakhir</span>';
                                }

                                // Set up the countdown timer for approved bookings
                                if (full.status === 'disetujui' &&
                                    ((currentDate >= startDateTime && currentDate <= endDateTime) ||
                                        currentDate < startDateTime)) {

                                    const countdownTimer = setInterval(function() {
                                        const cell = $(meta.settings.aoData[meta.row]
                                            .anCells[meta.col]);
                                        cell.html(formatRemainingTime());

                                        const now = new Date();
                                        if (now > endDateTime || full.status !==
                                            'disetujui') {
                                            clearInterval(countdownTimer);
                                            cell.html(
                                                '<span class=" fw-bold"><i class="fas fa-hourglass-end me-1"></i>Waktu Telah Berakhir</span>'
                                            );
                                        }
                                    }, 1000);
                                }

                                return formatRemainingTime();
                            } catch (error) {
                                console.error("Error parsing date/time:", error);
                                return '<span class="text-danger"><i class="fas fa-exclamation-circle me-1"></i>Error: Format waktu tidak valid</span>';
                            }
                        }
                    },
                    {
                        data: 'event',
                        render: function(data, type, row) {
                            return data;
                        }
                    },
                    {
                        data: 'status',
                        render: function(data, type, row) {
                            let bgStatus;
                            let icon = '';
                            let statusText;

                            switch (data) {
                                case 'disetujui':
                                    bgStatus = 'bg-success';
                                    icon = '<i class="fas fa-check-circle me-1"></i>';
                                    statusText = 'Disetujui';
                                    break;
                                case 'menunggu':
                                    bgStatus = 'bg-warning';
                                    icon = '<i class="fas fa-clock me-1"></i>';
                                    statusText = 'Menunggu';
                                    break;
                                case 'ditolak':
                                    bgStatus = 'bg-danger';
                                    icon = '<i class="fas fa-times-circle me-1"></i>';
                                    statusText = 'Ditolak';
                                    break;
                                case 'selesai':
                                    bgStatus = 'bg-secondary';
                                    icon = '<i class="fas fa-check-double me-1"></i>';
                                    statusText = 'Selesai';
                                    break;
                                case 'dibatalkan':
                                    bgStatus = 'bg-danger bg-opacity-50';
                                    icon = '<i class="fas fa-ban me-1"></i>';
                                    statusText = 'Dibatalkan';
                                    break;
                                case 'digunakan':
                                    bgStatus = 'bg-info';
                                    icon = '<i class="fas fa-sync-alt me-1"></i>';
                                    statusText = 'Digunakan';
                                    break;
                                case 'kadaluarsa':
                                    bgStatus = 'bg-dark';
                                    icon = '<i class="fas fa-calendar-times me-1"></i>';
                                    statusText = 'Kadaluarsa';
                                    break;
                                default:
                                    bgStatus = 'bg-secondary';
                                    icon = '<i class="fas fa-question-circle me-1"></i>';
                                    statusText = data.charAt(0).toUpperCase() + data.slice(1);
                            }

                            return `<div class="badge ${bgStatus} rounded-pill py-2 px-3 text-capitalize">${icon}${statusText}</div>`;
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: function(data) {
                            let buttons = `<div class="d-flex gap-1">`;

                            // View details button for all statuses
                            buttons += `
                                <button type="button" class="btn btn-sm bg-danger-light view-details" data-id="${data.id}" title="Lihat Detail">
                                    <i class="far fa-eye"></i>
                                </button>
                            `;

                            // Menambahkan tombol lain sesuai status
                            if (data.status === 'menunggu') {
                                buttons += `
                                    <button type="button" class="btn btn-sm btn-danger cancel-borrowing" data-id="${data.id}" title="Batalkan Peminjaman">
                                        <i class="fas fa-times"></i>
                                    </button>
                                `;
                            }

                            if (data.status === 'disetujui') {
                                const printUrl =
                                    "{{ route('borrowing.lab.print', ['borrowing' => ':id']) }}"
                                    .replace(':id', data.id);
                                buttons += `
                                    <a href="${printUrl}" target="_blank" class="btn btn-sm btn-success text-white print-borrow" data-id="${data.id}" title="Cetak Bukti">
                                        <i class="fas fa-print"></i>
                                    </a>
                                `;
                            }

                            if (data.status === 'ditolak') {
                                buttons += `
                                    <button type="button" class="btn btn-sm btn-warning text-white resubmit-borrowing"
                                        data-id="${data.id}" data-lab-id="${data.lab_id}" title="Ajukan Ulang">
                                        <i class="fas fa-redo"></i>
                                    </button>
                                `;
                            }

                            buttons += `</div>`;
                            return buttons;
                        }
                    }
                ],
                error: function(xhr, status, error) {
                    console.log('DataTables error:', error);
                }
            });

            // Handle cancel button click
            $(document).on('click', '.cancel-borrowing', function() {
                const borrowId = $(this).data('id');

                Swal.fire({
                    title: '<i class="fas fa-exclamation-triangle text-warning me-2"></i>Konfirmasi Pembatalan',
                    text: "Apakah Anda yakin ingin membatalkan peminjaman ini?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: '<i class="fas fa-check me-1"></i>Ya, Batalkan!',
                    cancelButtonText: '<i class="fas fa-times me-1"></i>Tidak'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('borrowing.lab.cancel', ':id') }}".replace(':id',
                                borrowId),
                            type: 'POST',
                            data: {
                                _method: 'PUT',
                                _token: $('meta[name="csrf-token"]').attr('content'),
                            },
                            success: function(response) {
                                $('#detailModal').modal('hide');
                                Swal.fire(
                                    '<i class="fas fa-check-circle text-success me-2"></i>Dibatalkan!',
                                    'Peminjaman berhasil dibatalkan.',
                                    'success'
                                );
                                table.ajax.reload();
                            },
                            error: function(xhr) {
                                Swal.fire(
                                    '<i class="fas fa-times-circle text-danger me-2"></i>Error!',
                                    xhr.responseJSON.message ||
                                    'Terjadi kesalahan saat membatalkan peminjaman.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });

            // Handle return button click
            $(document).on('click', '.btn-back', function() {
                const borrowId = $(this).data('borrow');

                Swal.fire({
                    title: '<i class="fas fa-question-circle text-info me-2"></i>Konfirmasi Pengembalian',
                    text: "Apakah Anda yakin ingin mengembalikan lab ini?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: '<i class="fas fa-check me-1"></i>Ya, Kembalikan!',
                    cancelButtonText: '<i class="fas fa-times me-1"></i>Tidak'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/lab/borrow/${borrowId}/return`,
                            type: 'POST',
                            success: function(response) {
                                Swal.fire(
                                    '<i class="fas fa-check-circle text-success me-2"></i>Dikembalikan!',
                                    'Lab berhasil dikembalikan.',
                                    'success'
                                );
                                table.ajax.reload();
                            },
                            error: function(xhr) {
                                Swal.fire(
                                    '<i class="fas fa-times-circle text-danger me-2"></i>Error!',
                                    xhr.responseJSON.message ||
                                    'Terjadi kesalahan saat mengembalikan lab.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });

            // Perbaikan filter tab agar berfungsi dengan benar
            $('.nav-tabs .nav-link').on('shown.bs.tab', function(e) {
                const tabId = $(this).attr('id');

                if (tabId === 'all-tab') {
                    table.search('').draw();
                } else if (tabId === 'pending-tab') {
                    table.columns(5).search('menunggu', true, false).draw();
                } else if (tabId === 'approved-tab') {
                    table.columns(5).search('disetujui', true, false).draw();
                } else if (tabId === 'rejected-tab') {
                    table.columns(5).search('ditolak', true, false).draw();
                }
            });

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Fungsi untuk melihat detail peminjaman
            $('#borrowTable').on('click', '.view-details', function() {
                const borrowId = $(this).data('id');

                // Tampilkan loading
                $('#detailModalBody').html(
                    '<div class="text-center py-4"><i class="fas fa-spinner fa-spin fa-2x"></i><p class="mt-2">Memuat data...</p></div>'
                );
                $('#detailModal').modal('show');

                // Ambil data detail peminjaman
                $.ajax({
                    url: `{{ route('borrowing.lab.show', ':borrowId') }}`.replace(':borrowId',
                        borrowId),
                    type: 'GET',
                    success: function(response) {
                        const data = response.data;

                        // Generate status badge
                        let statusBadge = '';
                        let statusClass = '';
                        let statusIcon = '';

                        switch (data.status) {
                            case 'menunggu':
                                statusClass = 'text-warning';
                                statusIcon = 'clock';
                                statusText = 'Menunggu';
                                break;
                            case 'disetujui':
                                statusClass = 'text-success';
                                statusIcon = 'check-circle';
                                statusText = 'Disetujui';
                                break;
                            case 'ditolak':
                                statusClass = 'text-danger';
                                statusIcon = 'times-circle';
                                statusText = 'Ditolak';
                                break;
                            case 'selesai':
                                statusClass = 'text-success';
                                statusIcon = 'check-double';
                                statusText = 'Selesai';
                                break;
                            case 'dibatalkan':
                                statusClass = 'text-secondary';
                                statusIcon = 'ban';
                                statusText = 'Dibatalkan';
                                break;
                            default:
                                statusClass = 'text-secondary';
                                statusIcon = 'question-circle';
                                statusText = data.status;
                        }

                        statusBadge =
                            `<span class="${statusClass}"><i class="fas fa-${statusIcon} me-1"></i>${statusText}</span>`;

                        // Format waktu peminjaman
                        const startTime = data.start_time;
                        const endTime = data.end_time;

                        // Generate informasi lab
                        let html = `
                        <div class="card mb-3 border">
                            <div class="card-header bg-light">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">
                                        <i class="fas fa-flask me-2"></i>${data.lab_name}
                                    </h5>
                                    <strong>${statusBadge}</strong>
                                </div>
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr>
                                            <td style="width: 30%;" class="text-secondary">
                                                <i class="fas fa-calendar-alt me-2"></i>Tanggal
                                            </td>
                                            <td>
                                                ${data.borrow_date}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-secondary">
                                                <i class="fas fa-clock me-2"></i>Waktu
                                            </td>
                                            <td>
                                                ${startTime} - ${endTime}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-secondary">
                                                <i class="fas fa-tasks me-2"></i>Kegiatan
                                            </td>
                                            <td>
                                                ${data.event}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-secondary">
                                                <i class="fas fa-info-circle me-2"></i>Status
                                            </td>
                                            <td>
                                                ${statusBadge}
                                            </td>
                                        </tr>`;

                        // Tampilkan catatan jika ada (terutama untuk penolakan)
                        if (data.notes) {
                            html += `
                                <tr>
                                    <td class="text-secondary">
                                        <i class="fas fa-sticky-note me-2"></i>Catatan
                                    </td>
                                    <td>
                                        <div class="border p-2 rounded bg-light text-break">
                                            ${data.status === 'ditolak' ? '<strong class="text-danger">Alasan Penolakan:</strong><br>' : ''}
                                            ${data.notes}
                                        </div>

                                    </td>
                                </tr>`;
                        }

                        // Tutup tabel dan card
                        html += `
                            </tbody>
                        </table>
                    </div>
                </div>`;

                        // Tampilkan timeline jika ada
                        if (response.timelineHtml) {
                            html += `
                            <div class="card border">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">
                                        <i class="fas fa-history me-2"></i>Riwayat Status
                                    </h5>
                                </div>
                                <div class="card-body">
                                    ${response.timelineHtml}
                                </div>
                            </div>
                            `;
                        }

                        $('#detailModalBody').html(html);

                        // Tambahkan tombol aksi di footer modal sesuai status
                        let footerButtons = `
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        `;

                        if (data.status === 'menunggu') {
                            footerButtons += `
                                <button type="button" class="btn btn-danger cancel-borrowing" data-id="${data.id}">
                                    <i class="fas fa-times me-1"></i> Batalkan Peminjaman
                                </button>
                            `;
                        } else if (data.status === 'disetujui') {
                            const printUrl =
                                "{{ route('borrowing.lab.print', ['borrowing' => ':id']) }}"
                                .replace(':id', data.id);
                            footerButtons += `
                                <a href="${printUrl}" target="_blank" class="btn btn-primary">
                                    <i class="fas fa-print me-1"></i> Cetak Bukti
                                </a>
                            `;
                        } else if (data.status === 'ditolak') {
                            footerButtons += `
                                <button type="button" class="btn btn-primary resubmit-modal" data-id="${data.id}" data-lab-id="${data.lab_id}">
                                    <i class="fas fa-redo me-1"></i> Ajukan Ulang
                                </button>
                            `;
                        }

                        $('.modal-footer').html(footerButtons);

                        // Handler untuk tombol ajukan ulang dari dalam modal
                        $('.resubmit-modal').on('click', function() {
                            const labId = $(this).data('lab-id');
                            const borrowId = $(this).data('id');
                            $('#detailModal').modal('hide');

                            // Konfirmasi sebelum redirect
                            Swal.fire({
                                title: 'Ajukan Ulang Peminjaman?',
                                text: "Anda akan diarahkan ke form peminjaman dengan lab yang sama",
                                icon: 'question',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Ya, ajukan ulang',
                                cancelButtonText: 'Batal'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href =
                                        `/borrowing/lab/${labId}/resubmit?reference=${borrowId}`;
                                }
                            });
                        });
                    },
                    error: function(xhr) {
                        $('#detailModalBody').html(`
                            <div class="alert alert-danger mb-0">
                                <div class="d-flex">
                                    <div class="me-3">
                                        <i class="fas fa-exclamation-circle fa-2x"></i>
                                    </div>
                                    <div>
                                        <h5 class="alert-heading">Terjadi Kesalahan!</h5>
                                        <p class="mb-0">${xhr.responseJSON?.message || 'Terjadi kesalahan saat memuat data detail.'}</p>
                                    </div>
                                </div>
                            </div>
                        `);
                    }
                });
            });

            // Filter tabs functionality
            $('#borrowingStatusTabs .nav-link').on('click', function() {
                const status = $(this).data('status');
                $('#borrowingStatusTabs .nav-link').removeClass('active');
                $(this).addClass('active');

                // Filter DataTable berdasarkan status
                if (status === 'aktif') {
                    table.column(7).search('disetujui').draw();
                } else if (status === '') {
                    table.column(7).search('').draw();
                } else {
                    table.column(7).search(status).draw();
                }
            });

            // History period filter
            $('.history-period').on('click', function(e) {
                e.preventDefault();
                const period = $(this).data('period');
                let startDate = null;
                const now = new Date();

                // Calculate start date based on period
                switch (period) {
                    case 'month':
                        startDate = new Date(now.getFullYear(), now.getMonth(), 1);
                        break;
                    case '3months':
                        startDate = new Date(now.getFullYear(), now.getMonth() - 3, 1);
                        break;
                    case '6months':
                        startDate = new Date(now.getFullYear(), now.getMonth() - 6, 1);
                        break;
                    case 'year':
                        startDate = new Date(now.getFullYear() - 1, now.getMonth(), 1);
                        break;
                    default:
                        // 'all' - no filter
                        break;
                }

                // Convert to YYYY-MM-DD format for filtering
                let formattedStartDate = '';
                if (startDate) {
                    formattedStartDate = startDate.toISOString().split('T')[0];
                }

                // Apply filter
                table.column(2).search(formattedStartDate, true, false, true).draw();

                // Update dropdown button text
                const periodText = $(this).text();
                $('#historyDropdown').html(`<i class="fas fa-history me-1"></i> ${periodText}`);
            });

            // Handle resubmit click
            $('#borrowTable').on('click', '.resubmit-borrowing', function() {
                const borrowId = $(this).data('id');
                const labId = $(this).data('lab-id');

                // Confirm before proceeding
                Swal.fire({
                    title: 'Ajukan Ulang Peminjaman?',
                    text: "Anda akan diarahkan ke form peminjaman dengan lab yang sama",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, ajukan ulang',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href =
                            "{{ route('borrowing.lab.resubmit', ['lab' => ':labId']) }}?reference=:borrowId"
                            .replace(':labId', labId)
                            .replace(':borrowId', borrowId);
                    }
                });
            });
        });
    </script>
@endpush
