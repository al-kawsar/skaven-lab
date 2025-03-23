@extends('layouts.app-layout')

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
                                <a href="{{ route('lab.index') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar Lab
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-body">
                            <form id="filterForm" action="{{ route('borrow.view') }}" method="GET" class="row">

                                <div class="col-md-3 mb-2">
                                    <label class="form-label"><i class="far fa-calendar-alt me-1"></i>Dari Tanggal</label>
                                    <div class="input-group">
                                        <input type="date" name="start_date" class="form-control date-picker"
                                            value="{{ request('start_date') }}">
                                        <span class="input-group-text bg-light cursor-pointer clear-date"
                                            data-target="start_date">
                                            <i class="fas fa-times-circle text-muted"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <label class="form-label"><i class="far fa-calendar-alt me-1"></i>Sampai Tanggal</label>
                                    <div class="input-group">
                                        <input type="date" name="end_date" class="form-control date-picker"
                                            value="{{ request('end_date') }}">
                                        <span class="input-group-text bg-light cursor-pointer clear-date"
                                            data-target="end_date">
                                            <i class="fas fa-times-circle text-muted"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <label class="form-label"><i class="fas fa-flask me-1"></i>Lab</label>
                                    <div class="input-group">
                                        <input type="text" name="lab" placeholder="Nama Lab" class="form-control"
                                            value="{{ request('lab') }}">
                                        <span class="input-group-text bg-light cursor-pointer clear-input"
                                            data-target="lab">
                                            <i class="fas fa-times-circle text-muted"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="d-flex justify-content-end">
                                        <button type="submit" class="btn btn-sm btn-primary me-2">
                                            <i class="fas fa-filter me-1"></i> Filter
                                        </button>
                                        <a href="{{ route('borrow.view') }}" class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-sync-alt me-1"></i> Reset
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <x-data-table name="borrowTable" :header="[
                        '#',
                        'Lab',
                        'Tanggal Peminjaman',
                        'Waktu Mulai',
                        'Waktu Selesai',
                        'Sisa Waktu',
                        'Keperluan',
                        'Status',
                        'Aksi',
                    ]" />

                    <div class="mt-3 text-center text-muted small">
                        <p><i class="fas fa-info-circle me-1"></i>Peminjaman yang sudah selesai akan otomatis ditandai
                            sebagai dikembalikan.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detail Peminjaman -->
    <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailModalLabel">Detail Peminjaman</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="detailModalBody">
                    <!-- Detail akan diisi oleh JavaScript -->
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
        });

        let table = $('#borrowTable').DataTable({
            ajax: {
                url: "{{ route('borrow.get-data') }}",
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
                    data: 'lab',
                    render: function(data, type, row) {
                        return '<strong>' + data + '</strong>'; // Make text bold
                    }
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
                        return '<i class="far fa-clock me-1"></i>' + data;
                    }
                },
                {
                    data: 'end_time',
                    render: function(data, type, row) {
                        return '<i class="far fa-clock me-1"></i>' + data;
                    }
                },
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function(data, type, full, meta) {
                        // Parse times and dates
                        const [startHour, startMinute] = full.start_time.split(':').map(Number);
                        const [endHour, endMinute] = full.end_time.split(':').map(Number);
                        const [year, month, day] = full.borrow_date.split('-').map(Number);

                        const currentDate = new Date();
                        const borrowDate = new Date(year, month - 1, day);

                        // Set up start and end times on the borrow date
                        const startDateTime = new Date(borrowDate);
                        startDateTime.setHours(startHour, startMinute, 0);

                        const endDateTime = new Date(borrowDate);
                        endDateTime.setHours(endHour, endMinute, 0);

                        // Handle overnight bookings
                        if (endHour < startHour || (endHour === startHour && endMinute < startMinute)) {
                            endDateTime.setDate(endDateTime.getDate() + 1);
                        }

                        // Format today's date for comparison
                        const today = new Date();
                        const formattedToday =
                            `${today.getFullYear()}-${String(today.getMonth() + 1).padStart(2, '0')}-${String(today.getDate()).padStart(2, '0')}`;

                        // Function to format the countdown display
                        function formatRemainingTime() {
                            const now = new Date();
                            const remainingTime = endDateTime.getTime() - now.getTime();

                            // Handle different statuses
                            if (full.status === 'menunggu') {
                                return '<i class="fas fa-pause me-1"></i>Menunggu persetujuan';
                            }

                            if (full.status === 'ditolak' || remainingTime <= 0 || full.borrow_date <
                                formattedToday) {
                                return '<span class="text-danger fw-bold"><i class="fas fa-hourglass-end me-1"></i>Waktu Telah Berakhir</span>';
                            }

                            // Only show countdown for approved bookings that are still active
                            if (full.status === 'disetujui') {
                                const hours = Math.floor(remainingTime / (1000 * 60 * 60));
                                const minutes = Math.floor((remainingTime % (1000 * 60 * 60)) / (1000 *
                                    60));
                                const seconds = Math.floor((remainingTime % (1000 * 60)) / 1000);

                                let timeDisplay = [];
                                if (hours > 0) timeDisplay.push(`${hours} Jam`);
                                if (minutes > 0) timeDisplay.push(`${minutes} Menit`);
                                timeDisplay.push(`${seconds} Detik`);

                                return `<i class="fas fa-hourglass-half me-1"></i>${timeDisplay.join(' ')}`;
                            }

                            return '<span class="text-danger fw-bold"><i class="fas fa-hourglass-end me-1"></i>Waktu Telah Berakhir</span>';
                        }

                        // Set up the countdown timer
                        if (full.status === 'disetujui' && endDateTime > currentDate) {
                            const countdownTimer = setInterval(function() {
                                const cell = $(meta.settings.aoData[meta.row].anCells[meta.col]);

                                // Update the countdown
                                cell.html(formatRemainingTime());

                                // Clear interval if time is up
                                if (endDateTime <= new Date() || full.status !== 'disetujui') {
                                    clearInterval(countdownTimer);
                                    cell.html(
                                        '<span class="text-danger fw-bold"><i class="fas fa-hourglass-end me-1"></i>Waktu Telah Berakhir</span>'
                                    );
                                }
                            }, 1000);
                        }

                        return formatRemainingTime();
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
                        let bgStatus, icon;
                        if (data == 'disetujui') {
                            bgStatus = 'bg-success';
                            icon = 'fas fa-check-circle';
                        }
                        if (data == 'menunggu') {
                            bgStatus = 'bg-warning';
                            icon = 'fas fa-clock';
                        }
                        if (data == 'ditolak') {
                            bgStatus = 'bg-danger';
                            icon = 'fas fa-times-circle';
                        }
                        return `<div class="rounded badge ${bgStatus} text-capitalize"><i class="${icon} me-1"></i>${data}</div>`;
                    }
                },
                {
                    data: null,
                    render: function(data) {
                        let buttons = `
                            <button type="button" class="btn btn-sm text-whie btn-ino view-details me-1" data-id="${data.id}" title="Lihat Detail">
                                <i class="fas fa-eye"></i>
                            </button>
                        `;

                        // Tambahkan tombol cancel hanya jika status 'menunggu'
                        if (data.status === 'menunggu') {
                            buttons += `
                                <button type="button" class="btn btn-sm btn-danger cancel-borrowing" data-id="${data.id}" title="Batalkan">
                                    <i class="fas fa-times"></i>
                                </button>
                            `;
                        }

                        // Tambahkan tombol cetak jika status 'disetujui'
                        if (data.status === 'disetujui') {
                            buttons += `
                                <button type="button" class="btn btn-sm btn-success print-borrow" data-id="${data.id}" title="Cetak">
                                    <i class="fas fa-print"></i>
                                </button>
                            `;
                        }

                        return buttons;
                    }
                }
            ],
            error: function(xhr, status, error) {
                console.log('DataTables error:', error);
            }
        });

        // Handle cancel button click
        $(document).on('click', '.btn-cancel', function() {
            const borrowId = $(this).data('borrow');

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
                        url: `/lab/borrow/${borrowId}/cancel`,
                        type: 'POST',
                        success: function(response) {
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

        // Fungsi untuk membatalkan peminjaman
        $('#borrowTable').on('click', '.cancel-borrowing', function() {
            const borrowId = $(this).data('id');
            alert(borrowId);

            Swal.fire({
                title: 'Batalkan Peminjaman?',
                text: "Anda yakin ingin membatalkan peminjaman ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, batalkan!',
                cancelButtonText: 'Tidak'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/my-borrowing/cancel/' + borrowId,
                        type: 'POST',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            Swal.fire(
                                'Dibatalkan!',
                                'Peminjaman berhasil dibatalkan.',
                                'success'
                            );
                            table.ajax.reload();
                        },
                        error: function(xhr) {
                            Swal.fire(
                                'Error!',
                                xhr.responseJSON.message ||
                                'Terjadi kesalahan saat membatalkan peminjaman.',
                                'error'
                            );
                        }
                    });
                }
            });
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
                url: '/my-borrowing/detail/' + borrowId,
                type: 'GET',
                success: function(response) {
                    const data = response.data;
                    let statusBadge = '';

                    if (data.status === 'menunggu') {
                        statusBadge = '<span class="badge bg-warning">Menunggu</span>';
                    } else if (data.status === 'disetujui') {
                        statusBadge = '<span class="badge bg-success">Disetujui</span>';
                    } else if (data.status === 'ditolak') {
                        statusBadge = '<span class="badge bg-danger">Ditolak</span>';
                    }

                    let html = `
                        <div class="row mb-3">
                            <div class="col-5 fw-bold">Laboratorium</div>
                            <div class="col-7">${data.lab_name}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-5 fw-bold">Tanggal</div>
                            <div class="col-7">${data.borrow_date}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-5 fw-bold">Waktu</div>
                            <div class="col-7">${data.start_time} - ${data.end_time}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-5 fw-bold">Kegiatan</div>
                            <div class="col-7">${data.event}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-5 fw-bold">Status</div>
                            <div class="col-7">${statusBadge}</div>
                        </div>
                    `;

                    if (data.notes) {
                        html += `
                            <div class="row mb-3">
                                <div class="col-5 fw-bold">Catatan</div>
                                <div class="col-7">${data.notes}</div>
                            </div>
                        `;
                    }

                    $('#detailModalBody').html(html);
                },
                error: function(xhr) {
                    $('#detailModalBody').html(`
                        <div class="alert alert-danger mb-0">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            ${xhr.responseJSON.message || 'Terjadi kesalahan saat memuat data detail.'}
                        </div>
                    `);
                }
            });
        });
    </script>
    <style>
        .cursor-pointer {
            cursor: pointer;
        }

        .status-filter.active {
            font-weight: bold;
        }

        .status-filter.active[data-value="menunggu"] {
            background-color: #ffc107;
            border-color: #ffc107;
            color: #000;
        }

        .status-filter.active[data-value="disetujui"] {
            background-color: #198754;
            border-color: #198754;
            color: #fff;
        }

        .status-filter.active[data-value="ditolak"] {
            background-color: #dc3545;
            border-color: #dc3545;
            color: #fff;
        }

        .status-filter.active[data-value=""] {
            background-color: #6c757d;
            border-color: #6c757d;
            color: #fff;
        }
    </style>
@endpush
