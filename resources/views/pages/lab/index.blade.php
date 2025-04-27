@extends('layouts.app-layout')
@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-sub-header">
                    <h3 class="page-title fw-bold text-uppercase">Lab</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Semua Lab</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-4 col-sm-6 col-12 d-flex">
            <div class="card bg-comman w-100">
                <div class="card-body">
                    <div class="db-widgets d-flex justify-content-between align-items-center">
                        <div class="db-info">
                            <h5 class="p-0 m-0 text-muted">Total Lab</h5>
                            <p class="mx-0 my-1 p-0 fw-bold fs-5" id="totalData">{{ $data['totalData'] ?? 0 }}</p>
                            <p class="m-0 p-0 text-muted fw-semibold" style="font-size: 14px">Total lab keseluruhan</p>
                        </div>
                        <div class="db-icon">
                            <i class="fas fa-building text-dark"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-sm-6 col-12 d-flex">
            <div class="card bg-comman w-100">
                <div class="card-body">
                    <div class="db-widgets d-flex justify-content-between align-items-center">
                        <div class="db-info">
                            <h5 class="p-0 m-0 text-muted">Tersedia</h5>
                            <p class="mx-0 my-1 p-0 fw-bold fs-5" id="totalAvailable">{{ $data['totalAvailable'] ?? 0 }}</p>
                            <p class="m-0 p-0 text-muted fw-semibold" style="font-size: 14px">Lab yang tersedia</p>
                        </div>
                        <div class="db-icon">
                            <i class="fas fa-building text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-sm-12 col-12 d-flex">
            <div class="card bg-comman w-100">
                <div class="card-body">
                    <div class="db-widgets d-flex justify-content-between align-items-center">
                        <div class="db-info">
                            <h5 class="p-0 m-0 text-muted">Tidak Tersedia</h5>
                            <p class="mx-0 my-1 p-0 fw-bold fs-5" id="totalUnavailable">{{ $data['totalUnavailable'] ?? 0 }}
                            </p>
                            <p class="m-0 p-0 text-muted fw-semibold" style="font-size: 14px">Lab yang tidak tersedia</p>
                        </div>
                        <div class="db-icon">
                            <i class="fas fa-building text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="filter-section">
                        <form id="filterForm" class="row align-items-center">
                            <div class="col-md-3 mb-2 mb-md-0">
                                <label for="filterName" class="form-label">Nama Lab</label>
                                <input type="text" class="form-control form-control-md" id="filterName"
                                    placeholder="Cari berdasarkan nama">
                            </div>
                            <div class="col-md-3 mb-2 mb-md-0">
                                <label for="filterStatus" class="form-label">Status</label>
                                <select class="form-control form-control-md" id="filterStatus">
                                    <option value="">Semua Status</option>
                                    <option value="tersedia">Tersedia</option>
                                    <option value="tidak tersedia">Tidak Tersedia</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-2 mb-md-0">
                                <label for="filterFacility" class="form-label">Fasilitas</label>
                                <input type="text" class="form-control form-control-md" id="filterFacility"
                                    placeholder="Cari berdasarkan fasilitas">
                            </div>
                            <div class="col-md-3 mb-2 mb-md-0">
                                <label class="form-label">&nbsp;</label>
                                <div>
                                    <button type="button" id="applyFilter" class="btn btn-primary btn-md me-2">
                                        <i class="fas fa-filter"></i> Filter
                                    </button>
                                    <button type="button" id="resetFilter" class="btn btn-outline-secondary btn-md">
                                        <i class="fas fa-sync"></i> Reset
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card card-table comman-shadow">
                <div class="card-body">
                    <div class="page-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="page-title fw-bold text-uppercase">Lab</h3>
                            </div>
                            <div class="col-auto text-end float-end ms-auto download-grp">
                                <button class="btn btn-outline-primary me-2" id="refreshTable" title="Refresh Data">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                                <button class="btn btn-outline-danger me-2" id="delete-all" title="Hapus Seluruh Lab"
                                    data-total="{{ $data['totalData'] }}" {{ $data['totalData'] <= 0 ? 'disabled' : '' }}>
                                    <i class="fas fa-trash"></i>
                                </button>
                                <a href="{{ route('labs.create') }}" class="btn btn-primary fw-bold">
                                    <i class="fas fa-plus"></i> Tambah Lab
                                </a>
                            </div>
                        </div>
                    </div>
                    <x-data-table name="labTable" :header="['#', 'Nama', 'Fasilitas', 'Status', '']" />
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        var counter = 0;
        var isProcessing = false;
        var labTable;

        // Warn user before leaving page during processing
        window.onbeforeunload = function() {
            if (isProcessing) {
                return "Proses sedang berjalan. Apakah Anda yakin ingin meninggalkan halaman ini?";
            }
        };

        // Function to update the delete all button state
        function updateDeleteAllButtonState(totalCount) {
            const deleteAllBtn = $('#delete-all');

            if (totalCount <= 0) {
                deleteAllBtn.prop('disabled', true)
                    .addClass('disabled')
                    .css('cursor', 'not-allowed');
            } else {
                deleteAllBtn.prop('disabled', false)
                    .removeClass('disabled')
                    .css('cursor', 'pointer');
            }
        }

        // Initialize DataTable
        $(document).ready(function() {
            // Add animation styles for the refresh button
            $('<style>')
                .prop('type', 'text/css')
                .html(`
                    @keyframes spin {
                        from { transform: rotate(0deg); }
                        to { transform: rotate(360deg); }
                    }
                    .refreshing {
                        animation: spin 1s linear infinite;
                    }
                `)
                .appendTo('head');

            // Initialize DataTable
            labTable = $('#labTable').DataTable({
                ajax: {
                    url: "{{ route('labs.ajax') }}",
                    type: 'GET',
                    dataType: 'json',
                    data: function(d) {
                        d.name = $('#filterName').val();
                        d.status = $('#filterStatus').val();
                        d.facilities = $('#filterFacility').val();
                        return d;
                    },
                    dataSrc: 'data',
                    complete: function(data) {
                        // Update counters based on the returned data
                        try {
                            const responseData = data.responseJSON;
                            if (responseData && responseData.meta) {
                                // Pastikan setiap nilai adalah numerik dan valid, jika tidak gunakan 0
                                const totalCount = parseInt(responseData.meta.total) || 0;
                                const availableCount = parseInt(responseData.meta.available) || 0;
                                const unavailableCount = parseInt(responseData.meta.unavailable) || 0;

                                $('#totalData').text(totalCount);
                                $('#totalAvailable').text(availableCount);
                                $('#totalUnavailable').text(unavailableCount);

                                $('#delete-all').data('total', totalCount);

                                // Update button state based on count
                                updateDeleteAllButtonState(totalCount);
                            } else {
                                // Jika tidak ada meta, atur semua total ke 0
                                $('#totalData').text('0');
                                $('#totalAvailable').text('0');
                                $('#totalUnavailable').text('0');
                                updateDeleteAllButtonState(0);
                            }
                        } catch (error) {
                            console.error('Error updating counters:', error);
                            // Jika terjadi error, atur semua total ke 0
                            $('#totalData').text('0');
                            $('#totalAvailable').text('0');
                            $('#totalUnavailable').text('0');
                            updateDeleteAllButtonState(0);
                        }
                    }
                },
                searching: true,
                serverSide: false,
                processing: true,
                columns: [{
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {
                        data: 'name',
                        render: function(data, type, row) {
                            return `<div class="d-flex align-items-center">
                                <strong>${data}</strong>
                            </div>`;
                        }
                    },
                    {
                        data: 'facilities',
                        render: function(data) {
                            return `<span class="text-wrap">${data}</span>`;
                        }
                    },
                    {
                        data: 'status',
                        render: function(data, type, row) {
                            let badgeClass = data === 'tersedia' ? 'bg-success-light' :
                                'bg-danger-light';
                            let textClass = data === 'tersedia' ? 'text-success' : 'text-danger';
                            return `<span class="badge rounded-pill py-2 px-3 ${badgeClass} ${textClass} text-capitalize">${data}</span>`;
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            var editUrl = '{{ route('labs.edit', ':id') }}'.replace(':id', row.id);
                            var showUrl = '{{ route('labs.show', ':id') }}'.replace(':id', row.id);

                            return `
                            <div class="d-flex p-0 m-0 align-items-center justify-content-center">
                                <a href="${showUrl}" class="btn bg-success-light btn-sm me-1" title="Lihat Detail">
                                    <i class="far fa-eye"></i>
                                </a>
                                <a href="${editUrl}" class="btn bg-success-light btn-sm me-1" title="Edit Lab">
                                    <i class="feather-edit"></i>
                                </a>
                                <button class="btn btn-sm bg-success-light btn-delete" data-lab="${row.id}" ${isProcessing ? 'disabled' : ''} title="Hapus Lab">
                                    <i class="feather-trash"></i>
                                </button>
                            </div>`;
                        }
                    }
                ],
                dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                    '<"row"<"col-sm-12"t>>' +
                    '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
                error: function(xhr, status, error) {
                    console.log('DataTables error:', error);
                },
                drawCallback: function() {
                    // Add tooltips to buttons after table draw
                    $('[title]').tooltip();
                },
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
                }
            });

            // Setup AJAX headers
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Check the delete all button state on page load
            const totalData = $('#delete-all').data('total');
            updateDeleteAllButtonState(totalData);

            // Refresh button click handler
            $('#refreshTable').on('click', function() {
                const $icon = $(this).find('i');
                $icon.addClass('refreshing');

                // Reload the table
                labTable.ajax.reload(function() {
                    // After reload is complete, remove the spinning animation
                    setTimeout(function() {
                        $icon.removeClass('refreshing');
                    }, 500);

                    // Show success toast
                    toastr.success('Data berhasil diperbarui', 'Sukses');
                });
            });

            // Filter apply button click handler
            $('#applyFilter').on('click', function() {
                labTable.ajax.reload();
            });

            // Filter reset button click handler
            $('#resetFilter').on('click', function() {
                $('#filterName').val('');
                $('#filterStatus').val('').trigger('change');
                $('#filterFacility').val('');
                labTable.ajax.reload();
            });

            // Add filter functionality to all inputs with debounce
            let filterTimeout;
            $('.filter-section input, .filter-section select').on('keyup change', function() {
                clearTimeout(filterTimeout);
                filterTimeout = setTimeout(function() {
                    // Only reload if there are actual filter values
                    if ($('#filterName').val() || $('#filterStatus').val() || $('#filterFacility')
                        .val()) {
                        labTable.ajax.reload();
                    }
                }, 500);
            });
        });

        // Delete all button handler
        $("#delete-all").on("click", function(e) {
            e.preventDefault();

            // If button is disabled, don't proceed
            if ($(this).prop('disabled')) {
                return;
            }

            if (isProcessing) {
                Swal.fire({
                    title: 'Proses Sedang Berjalan',
                    text: 'Harap tunggu hingga proses selesai',
                    icon: 'warning'
                });
                return;
            }

            const totalData = $(this).data('total');

            if (totalData <= 0) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Tidak ada data lab untuk dihapus',
                    icon: 'error'
                });
                return;
            }

            const self = this;

            Swal.fire({
                title: "Anda yakin?",
                text: "Semua Data lab akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: "Ya, Hapus!",
                cancelButtonText: "Batal",
                showLoaderOnConfirm: true,
                customClass: {
                    confirmButton: 'btn btn-danger',
                    cancelButton: 'btn btn-primary',
                },
                preConfirm: () => {
                    isProcessing = true;
                    return $.ajax({
                        url: `{{ route('labs.destroy.all') }}`,
                        type: 'DELETE'
                    }).then(response => {
                        return response;
                    }).catch(error => {
                        Swal.showValidationMessage(
                            `Request failed: ${error.responseJSON.message}`);
                    });
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                isProcessing = false;
                if (result.isConfirmed) {
                    // Set total to 0
                    $(self).data('total', 0);
                    $('#totalData').text(0);
                    $('#totalAvailable').text(0);
                    $('#totalUnavailable').text(0);

                    // Update the button state
                    updateDeleteAllButtonState(0);

                    // Reload the table
                    labTable.ajax.reload();

                    Swal.fire({
                        title: 'Berhasil!',
                        text: result.value.message,
                        icon: 'success'
                    });
                }
            });
        });

        // Delete single lab button handler
        $('#labTable').on('click', '.btn-delete', function(e) {
            e.preventDefault();
            if (isProcessing) {
                Swal.fire({
                    title: 'Proses Sedang Berjalan',
                    text: 'Harap tunggu hingga proses selesai',
                    icon: 'warning'
                });
                return;
            }

            const labId = $(this).data('lab');

            Swal.fire({
                title: "Anda yakin?",
                text: "Data akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: "Ya, Hapus!",
                cancelButtonText: "Batal",
                showLoaderOnConfirm: true,
                customClass: {
                    confirmButton: 'btn btn-danger',
                    cancelButton: 'btn btn-primary',
                },
                preConfirm: () => {
                    isProcessing = true;
                    return $.ajax({
                        url: `{{ route('labs.destroy', ':id') }}`.replace(':id', labId),
                        type: 'DELETE'
                    }).then(response => {
                        return response;
                    }).catch(error => {
                        Swal.showValidationMessage(
                            `Request failed: ${error.responseJSON.message}`);
                    });
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                isProcessing = false;
                if (result.isConfirmed) {
                    // Reload the table to refresh the data including updated counts
                    labTable.ajax.reload();

                    Swal.fire({
                        title: 'Berhasil!',
                        text: result.value.message,
                        icon: 'success'
                    });
                }
            });
        });
    </script>
@endpush
