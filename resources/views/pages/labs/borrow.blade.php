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

        let table = $('#borrowTable').DataTable({
            ajax: {
                url: "{{ route('admin.borrow.get-data') }}",
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

                        if (data == 'disetujui') {
                            bgStatus = 'bg-success';
                            icon = '<i class="fas fa-check-circle me-1"></i>';
                        }
                        if (data == 'menunggu') {
                            bgStatus = 'bg-warning';
                            icon = '<i class="fas fa-clock me-1"></i>';
                        }
                        if (data == 'ditolak') {
                            bgStatus = 'bg-danger';
                            icon = '<i class="fas fa-times-circle me-1"></i>';
                        }

                        return `<div class="badge ${bgStatus} rounded-pill py-2 px-3 text-capitalize">${icon}${data}</div>`;
                    }
                },
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function(data, type, full, meta) {
                        let actionBtn = `<div class="d-flex gap-1">`;

                        // View details button for all statuses
                        actionBtn += `<button class="btn btn-sm btn-info view-details" data-id="${full.id}">
                            <i class="far fa-eye"></i>
                        </button>`;

                        if (full.status === 'disetujui') {
                            actionBtn += `<button class="btn btn-sm btn-success" disabled>
                                <i class="fas fa-check-circle me-1"></i>Disetujui
                            </button>`;
                        } else if (full.status === 'menunggu') {
                            actionBtn += `
                            <button data-borrow="${full.id}" class="btn btn-sm btn-danger btn-reject">
                                <i class="fas fa-times-circle me-1"></i>Tolak
                            </button>
                            <button data-borrow="${full.id}" class="btn btn-sm btn-success btn-approve">
                                <i class="fas fa-check-circle me-1"></i>Setujui
                            </button>`;
                        } else if (full.status === 'ditolak') {
                            actionBtn += `<button class="btn btn-sm btn-danger" disabled>
                                <i class="fas fa-times-circle me-1"></i>Ditolak
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
                url: `/lab/detail/${borrowId}`,
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
                            <div class="col-5 fw-bold">Peminjam</div>
                            <div class="col-7">${data.borrower}</div>
                        </div>
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
                            ${xhr.responseJSON?.message || 'Terjadi kesalahan saat memuat data detail.'}
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
                        url: `/admin/borrow/${borrowId}/approve`,
                        type: 'POST',
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

            Swal.fire({
                title: 'Tolak Peminjaman?',
                text: "Anda yakin ingin menolak peminjaman ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fas fa-times me-1"></i>Ya, Tolak!',
                cancelButtonText: '<i class="fas fa-undo me-1"></i>Tidak'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/admin/borrow/${borrowId}/reject`,
                        type: 'POST',
                        success: function(response) {
                            Swal.fire(
                                '<i class="fas fa-check-circle text-success me-2"></i>Ditolak!',
                                'Peminjaman berhasil ditolak.',
                                'success'
                            );
                            table.ajax.reload();
                        },
                        error: function(xhr) {
                            Swal.fire(
                                '<i class="fas fa-times-circle text-danger me-2"></i>Error!',
                                xhr.responseJSON?.message ||
                                'Terjadi kesalahan saat menolak peminjaman.',
                                'error'
                            );
                        }
                    });
                }
            });
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
@endpush
