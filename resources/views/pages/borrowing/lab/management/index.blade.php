@extends('layouts.app-layout')
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card card-table comman-shadow">
                <div class="card-body">
                    <div class="page-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="page-title"><i class="fas fa-clipboard-list me-2"></i>Data Peminjaman</h3>
                            </div>
                        </div>
                    </div>
                    <x-data-table name="borrowTable" :header="[
                        '#',
                        'Peminjam',
                        'Tanggal Peminjaman',
                        'Waktu Mulai',
                        'Waktu Berakhir',
                        'Keperluan',
                        'Status',
                        'Aksi',
                    ]" />
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detail Peminjaman -->
    <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
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

    <!-- Tambahkan modal ini di bawah modal detail -->
    <div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tolak Peminjaman</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="rejectForm">
                        <input type="hidden" id="reject_borrowing_id">
                        <div class="form-group mb-3">
                            <label for="rejection_reason" class="form-label">Alasan Penolakan <span
                                    class="text-danger">*</span></label>
                            <textarea class="form-control" id="rejection_reason" rows="3" required></textarea>
                            <div class="form-text text-muted">Alasan penolakan akan ditampilkan kepada peminjam</div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" id="submitReject">
                        <i class="fas fa-times-circle me-1"></i>Tolak Peminjaman
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        var counter = 0;

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
                url: "{{ route('borrowing.lab.management.data') }}",
                type: 'GET',
                dataType: 'json',
                dataSrc: 'data'
            },
            searching: true,
            serverSide: false,
            columns: [{
                    data: 'number'
                },
                {
                    data: 'peminjam',
                    render: function(data, type, row) {
                        return '<strong>' + data + '</strong>'; // Make the text bold
                    }
                },

                {
                    data: 'borrow_date',
                    render: function(data, type, row) {
                        return '<i class="far fa-calendar-alt me-1"></i>' + data;
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
                    data: 'event',
                    render: function(data, type, row) {
                        return '<i class="fas fa-tasks me-1"></i>' + data;
                    }
                },
                {
                    data: 'status',
                    render: function(data, type, row) {
                        let bgStatus;
                        let icon = '';

                        switch (data) {
                            case 'disetujui':
                                bgStatus = 'bg-success';
                                icon = '<i class="fas fa-check-circle me-1"></i>';
                                break;
                            case 'menunggu':
                                bgStatus = 'bg-warning';
                                icon = '<i class="fas fa-clock me-1"></i>';
                                break;
                            case 'ditolak':
                                bgStatus = 'bg-danger';
                                icon = '<i class="fas fa-times-circle me-1"></i>';
                                break;
                            case 'selesai':
                                bgStatus = 'bg-secondary';
                                icon = '<i class="fas fa-check-double me-1"></i>';
                                break;
                            case 'dibatalkan':
                                bgStatus = 'bg-danger bg-opacity-50';
                                icon = '<i class="fas fa-ban me-1"></i>';
                                break;
                            case 'digunakan':
                                bgStatus = 'bg-info';
                                icon = '<i class="fas fa-sync-alt me-1"></i>';
                                break;
                            case 'kadaluarsa':
                                bgStatus = 'bg-dark';
                                icon = '<i class="fas fa-calendar-times me-1"></i>';
                                break;
                        }

                        // Ubah nama status ke Indonesia dengan huruf kapital di awal
                        const statusText = data.charAt(0).toUpperCase() + data.slice(1);

                        return `<div class="badge ${bgStatus} rounded-pill py-2 px-3 text-capitalize">${icon}${statusText}</div>`;
                    }
                },
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function(data, type, full, meta) {
                        let actionBtn = `<div class="d-flex gap-1">`;

                        // View details button for all statuses - tetap dipertahankan
                        actionBtn += `
                            <button class="btn btn-sm bg-danger-light view-details" data-id="${full.id}" title="Lihat Detail">
                                <i class="far fa-eye"></i>
                            </button>`;

                        // Hanya tampilkan tombol aksi untuk status "menunggu" saja
                        if (full.status === 'menunggu') {
                            actionBtn += `
                            <button data-borrow="${full.id}" class="btn btn-sm btn-danger btn-reject" title="Tolak Peminjaman">
                                <i class="fas fa-times-circle"></i>
                            </button>
                            <button data-borrow="${full.id}" class="btn btn-sm btn-success btn-approve" title="Setujui Peminjaman">
                                <i class="fas fa-check-circle"></i>
                            </button>`;
                        }
                        // Hapus tombol status yang disabled (tidak perlu)

                        // Tambahkan tombol print untuk status disetujui
                        if (full.status === 'disetujui') {
                            actionBtn += `
                            <button class="btn btn-sm btn-success text-white print-borrow" data-id="${full.id}" title="Cetak Bukti">
                                <i class="fas fa-print"></i>
                            </button>`;
                        }

                        actionBtn += `</div>`;
                        return actionBtn;
                    }
                }
            ],
            error: function(xhr, status, error) {
                console.log('DataTables error:', error);
            }
        });

        // View details functionality
        $('#borrowTable').on('click', '.view-details', function() {
            const borrowId = $(this).data('id');

            // Show loading in modal
            $('#detailModalBody').html(
                '<div class="text-center py-4"><i class="fas fa-spinner fa-spin fa-2x"></i><p class="mt-2">Memuat data...</p></div>'
            );
            $('#detailModal').modal('show');

            // Fetch borrow details - implement the endpoint
            $.ajax({
                url: "{{ route('borrowing.lab.show', ':id') }}".replace(':id', borrowId),
                type: 'GET',
                success: function(response) {
                    const data = response.data;

                    // Generate status badge dengan warna dan ikon yang sesuai
                    let statusBadge = '';
                    switch (data.status) {
                        case 'menunggu':
                            statusBadge =
                                '<span class="badge bg-warning py-2 px-3"><i class="fas fa-clock me-1"></i>Menunggu</span>';
                            break;
                        case 'disetujui':
                            statusBadge =
                                '<span class="badge bg-success py-2 px-3"><i class="fas fa-check-circle me-1"></i>Disetujui</span>';
                            break;
                        case 'ditolak':
                            statusBadge =
                                '<span class="badge bg-danger py-2 px-3"><i class="fas fa-times-circle me-1"></i>Ditolak</span>';
                            break;
                        case 'selesai':
                            statusBadge =
                                '<span class="badge bg-secondary py-2 px-3"><i class="fas fa-check-double me-1"></i>Selesai</span>';
                            break;
                        case 'dibatalkan':
                            statusBadge =
                                '<span class="badge bg-danger py-2 px-3 bg-opacity-75"><i class="fas fa-ban me-1"></i>Dibatalkan</span>';
                            break;
                        default:
                            statusBadge =
                                `<span class="badge bg-secondary py-2 px-3"><i class="fas fa-question-circle me-1"></i>${data.status}</span>`;
                    }

                    // Format waktu peminjaman dengan lebih baik
                    const startTime = formatTimeForDisplay(data.start_time);
                    const endTime = formatTimeForDisplay(data.end_time);

                    // Generate card header dengan informasi utama
                    let html = `
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-header bg-primary bg-opacity-10 py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 text-primary">
                                    <i class="fas fa-info-circle me-2"></i>Informasi Peminjaman
                                </h5>
                                ${statusBadge}
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-5 fw-bold text-muted">
                                    <i class="fas fa-user me-2"></i>Peminjam
                                </div>
                                <div class="col-7 text-dark">
                                    ${data.borrower}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-5 fw-bold text-muted">
                                    <i class="fas fa-flask me-2"></i>Laboratorium
                                </div>
                                <div class="col-7 text-dark">
                                    ${data.lab_name}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-5 fw-bold text-muted">
                                    <i class="fas fa-calendar-alt me-2"></i>Tanggal
                                </div>
                                <div class="col-7 text-dark">
                                    ${data.borrow_date}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-5 fw-bold text-muted">
                                    <i class="fas fa-clock me-2"></i>Waktu
                                </div>
                                <div class="col-7 text-dark">
                                    <span class="text-primary fw-medium">${startTime}</span>
                                    <i class="fas fa-arrow-right mx-2 text-muted"></i>
                                    <span class="text-danger fw-medium">${endTime}</span>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-5 fw-bold text-muted">
                                    <i class="fas fa-tasks me-2"></i>Kegiatan
                                </div>
                                <div class="col-7 text-dark">
                                    ${data.event}
                                </div>
                            </div>
                    `;

                    // Display optional notes if available
                    if (data.notes) {
                        html += `
                            <div class="row mb-3">
                                <div class="col-5 fw-bold text-muted">
                                    <i class="fas fa-sticky-note me-2"></i>Catatan
                                </div>
                                <div class="col-7">
                                    <div class="p-2 bg-light rounded">
                                        ${data.status === 'ditolak'
                                            ? '<small class="text-danger fw-medium">Alasan Penolakan:</small><br>'
                                            : ''}
                                        ${data.notes}
                                    </div>
                                </div>
                            </div>
                        `;
                    }

                    // Close the card
                    html += `</div></div>`;

                    // Display timeline if provided in response
                    if (response.timelineHtml) {
                        html += `
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-info bg-opacity-10 py-3">
                                <h5 class="mb-0 text-info">
                                    <i class="fas fa-history me-2"></i>Riwayat Status
                                </h5>
                            </div>
                            <div class="card-body">
                                ${response.timelineHtml}
                            </div>
                        </div>
                        `;
                    }

                    // Add action buttons at bottom for pending requests
                    if (data.status === 'menunggu') {
                        html += `
                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <button type="button" class="btn btn-danger" id="detailRejectBtn" data-borrow="${data.id}">
                                <i class="fas fa-times-circle me-1"></i> Tolak
                            </button>
                            <button type="button" class="btn btn-success" id="detailApproveBtn" data-borrow="${data.id}">
                                <i class="fas fa-check-circle me-1"></i> Setujui
                            </button>
                        </div>
                        `;
                    }

                    $('#detailModalBody').html(html);

                    // Attach event handlers to the new buttons in the modal
                    if (data.status === 'menunggu') {
                        $('#detailRejectBtn').on('click', function() {
                            const borrowId = $(this).data('borrow');
                            $('#detailModal').modal('hide');
                            $('#reject_borrowing_id').val(borrowId);
                            $('#rejectModal').modal('show');
                        });

                        $('#detailApproveBtn').on('click', function() {
                            const borrowId = $(this).data('borrow');
                            $('#detailModal').modal('hide');
                            $('.btn-approve[data-borrow="' + borrowId + '"]').trigger('click');

                        });
                    }
                },
                error: function(xhr) {
                    $('#detailModalBody').html(`
                        <div class="alert alert-danger mb-0">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-exclamation-circle me-3 fa-2x"></i>
                                <div>
                                    <h5 class="alert-heading">Error!</h5>
                                    <p class="mb-0">${xhr.responseJSON?.message || 'Terjadi kesalahan saat memuat data detail.'}</p>
                                </div>
                            </div>
                        </div>
                    `);
                }
            });
        });

        // Approve borrow request
        $('#borrowTable').on('click', '.btn-approve', function() {
            const borrowId = $(this).data('borrow');
            Swal.fire({
                title: 'Setujui Peminjaman?',
                text: "Anda yakin ingin menyetujui peminjaman ini?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fas fa-check me-1"></i>Ya, Setujui!',
                cancelButtonText: '<i class="fas fa-times me-1"></i>Tidak'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('borrowing.lab.admin.approve', ':id') }}".replace(':id',
                            borrowId),
                        type: 'POST',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            Swal.fire(
                                '<i class="fas fa-check-circle text-success me-2"></i>Disetujui!',
                                'Peminjaman berhasil disetujui.',
                                'success'
                            );
                            table.ajax.reload();
                        },
                        error: function(xhr) {
                            Swal.fire(
                                '<i class="fas fa-times-circle text-danger me-2"></i>Error!',
                                xhr.responseJSON?.message ||
                                'Terjadi kesalahan saat menyetujui peminjaman.',
                                'error'
                            );
                        }
                    });
                }
            });
        });


        // Reject borrow request
        $('#borrowTable').on('click', '.btn-reject', function() {
            const borrowId = $(this).data('borrow');
            $('#reject_borrowing_id').val(borrowId);
            $('#rejectModal').modal('show');
        });

        // Tambahkan handler submit reject
        $('#submitReject').on('click', function() {
            const borrowId = $('#reject_borrowing_id').val();
            const reason = $('#rejection_reason').val();

            if (!reason) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Alasan penolakan harus diisi',
                    icon: 'error'
                });
                return;
            }

            $.ajax({
                url: "{{ route('borrowing.lab.admin.reject', ':id') }}".replace(':id', borrowId),
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    notes: reason
                },
                success: function(response) {
                    $('#rejectModal').modal('hide');
                    Swal.fire(
                        '<i class="fas fa-check-circle text-success me-2"></i>Berhasil!',
                        'Peminjaman berhasil ditolak.',
                        'success'
                    );
                    table.ajax.reload();

                    // Reset form
                    $('#rejectForm')[0].reset();
                },
                error: function(xhr) {
                    Swal.fire(
                        '<i class="fas fa-times-circle text-danger me-2"></i>Error!',
                        xhr.responseJSON?.message || 'Terjadi kesalahan saat menolak peminjaman.',
                        'error'
                    );
                }
            });
        });

        // Print borrowing bukti
        $('#borrowTable').on('click', '.print-borrow', function() {
            const borrowId = $(this).data('id');

            // Buka halaman print di tab baru
            window.open("{{ route('borrowing.lab.print', ':id') }}".replace(':id', borrowId), '_blank');
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function loadBorrowingHistory(id) {
            $.ajax({
                url: `/borrowing/lab/${id}/history`,
                method: 'GET',
                success: function(response) {
                    $('#borrowing-history').html(response.timelineHtml);
                },
                error: function(xhr) {
                    $('#borrowing-history').html(
                        '<div class="alert alert-warning">Gagal memuat riwayat aktivitas</div>');
                }
            });
        }
        // Di dalam fungsi renderActions atau setupDataTable
        function renderActions(data, type, row) {
            let html = `
        <div class="btn-group" role="group">
            <button class="btn btn-sm btn-info" onclick="showBorrowingDetail('${row.id}')">
                <i class="fas fa-eye"></i>
            </button>

            <a href="/borrowing/lab/${row.id}/history" class="btn btn-sm btn-secondary">
                <i class="fas fa-history"></i>
            </a>

            <!-- tombol lainnya -->
        </div>
    `;
            return html;
        }
        $('[title]').tooltip();
    </script>
@endpush
