@extends('layouts.app-layout')
@section('content')
<div class="page-header">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-sub-header">
                <h3 class="page-title">Lokasi Penyimpanan</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.barang.index') }}">Manajemen Barang</a></li>
                    <li class="breadcrumb-item active">Lokasi Penyimpanan</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Statistik Lokasi -->
<div class="row">
    <div class="col-xl-4 col-md-6 col-12 d-flex">
        <div class="card bg-comman w-100">
            <div class="card-body">
                <div class="db-widgets d-flex justify-content-between align-items-center">
                    <div class="db-info">
                        <h5 class="p-0 m-0 text-muted">Total Lokasi</h5>
                        <p class="mx-0 my-1 p-0 fw-bold fs-5" id="totalLocations">0</p>
                        <p class="m-0 p-0 text-muted fw-semibold" style="font-size: 14px">Jumlah lokasi terdaftar</p>
                    </div>
                    <div class="db-icon">
                        <i class="fas fa-map-marker-alt text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-6 col-12 d-flex">
        <div class="card bg-comman w-100">
            <div class="card-body">
                <div class="db-widgets d-flex justify-content-between align-items-center">
                    <div class="db-info">
                        <h5 class="p-0 m-0 text-muted">Barang Terdaftar</h5>
                        <p class="mx-0 my-1 p-0 fw-bold fs-5" id="totalEquipment">0</p>
                        <p class="m-0 p-0 text-muted fw-semibold" style="font-size: 14px">Total barang di semua lokasi
                        </p>
                    </div>
                    <div class="db-icon">
                        <i class="fas fa-box text-warning"></i>
                    </div>
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
                            <h3 class="page-title">Daftar Lokasi Penyimpanan</h3>
                        </div>
                        <div class="col-auto text-end float-end ms-auto download-grp">


                            <button class="btn btn-outline-primary me-2" id="refreshTable" title="Refresh Data">
                                <i class="fas fa-sync-alt"></i>
                            </button>
                            <button class="btn btn-primary fw-bold" id="add-location">
                                <i class="fas fa-plus"></i> Tambah Lokasi
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Filter Section -->
                <div class="filter-section mb-3">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="d-flex justify-content-end">
                                <button class="btn btn-outline-danger me-2" id="bulk-delete" disabled>
                                    <i class="fas fa-trash"></i> Hapus Terpilih
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table border-0 star-student  table-hover table-center mb-0 datatable"
                    id="locationTable">
                    <thead class="student-thread">
                        <tr>
                            <th>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="select-all">
                                    <label class="form-check-label" for="select-all"></label>
                                </div>
                            </th>
                            <th>#</th>
                            <th>Nama Lokasi</th>
                            <th>Kode</th>
                            <th>Gedung</th>
                            <th>Lantai/Ruang</th>
                            <th>Jumlah Barang</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>

<!-- Modal Tambah/Edit Lokasi -->
<div class="modal fade" id="locationModal" tabindex="-1" aria-labelledby="locationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="locationModalLabel">Tambah Lokasi Penyimpanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="locationForm">
                <div class="modal-body">
                    <input type="hidden" id="location_id" name="location_id">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Lokasi <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" required>
                        <div class="invalid-feedback" id="name-error"></div>
                    </div>
                    <div class="mb-3">
                        <label for="code" class="form-label">Kode Lokasi</label>
                        <input type="text" class="form-control" id="code" name="code">
                        <div class="invalid-feedback" id="code-error"></div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="building" class="form-label">Gedung</label>
                                <input type="text" class="form-control" id="building" name="building"
                                placeholder="Nama gedung">
                                <div class="invalid-feedback" id="building-error"></div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="floor" class="form-label">Lantai</label>
                                <input type="text" class="form-control" id="floor" name="floor"
                                placeholder="Nomor lantai">
                                <div class="invalid-feedback" id="floor-error"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="room" class="form-label">Ruang</label>
                                <input type="text" class="form-control" id="room" name="room"
                                placeholder="Nomor/nama ruang">
                                <div class="invalid-feedback" id="room-error"></div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        <div class="invalid-feedback" id="description-error"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="saveBtn">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Detail Lokasi -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModalLabel">Detail Lokasi Penyimpanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <div class="location-icon bg-light rounded-circle mx-auto d-flex align-items-center justify-content-center"
                    style="width: 80px; height: 80px;">
                    <i class="fas fa-map-marker-alt fa-2x text-primary"></i>
                </div>
                <h4 id="detail-name" class="mt-3 fw-bold"></h4>
                <p id="detail-code" class="text-muted"></p>
            </div>

            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="card border">
                        <div class="card-body">
                            <h5 class="card-title">Informasi Lokasi</h5>
                            <div class="row g-3">
                                <div class="col-sm-12">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-building text-primary me-2"></i>
                                        </div>
                                        <div>
                                            <div class="fw-medium">Gedung</div>
                                            <div id="detail-building" class="text-muted">-</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-layer-group text-primary me-2"></i>
                                        </div>
                                        <div>
                                            <div class="fw-medium">Lantai</div>
                                            <div id="detail-floor" class="text-muted">-</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-door-open text-primary me-2"></i>
                                        </div>
                                        <div>
                                            <div class="fw-medium">Ruang</div>
                                            <div id="detail-room" class="text-muted">-</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 mt-3">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-info-circle text-primary me-2"></i>
                                        </div>
                                        <div>
                                            <div class="fw-medium">Deskripsi</div>
                                            <div id="detail-description" class="text-muted">-</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card border">
                        <div class="card-body">
                            <h5 class="card-title">Statistik</h5>
                            <div class="d-flex justify-content-between align-items-center">
                                <span>Jumlah Barang:</span>
                                <span id="detail-equipment-count" class="badge bg-primary">0</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            <button type="button" class="btn btn-primary" id="editFromDetail">Edit Lokasi</button>
        </div>
    </div>
</div>
</div>
@endsection

@push('script')
<script>
    var locationTable;
    var isProcessing = false;
    var selectedLocation = null;
    var selectedIds = [];

        // Warn user before leaving page during processing
    window.onbeforeunload = function() {
        if (isProcessing) {
            return "Proses sedang berjalan. Apakah Anda yakin ingin meninggalkan halaman ini?";
        }
    };

        // Reset form errors
    function resetFormErrors() {
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('');
    }

        // Reset modal form
    function resetModalForm() {
        $('#locationForm')[0].reset();
        $('#location_id').val('');
        resetFormErrors();
        $('#locationModalLabel').text('Tambah Lokasi Penyimpanan');
    }

        // Display errors on form
    function displayErrors(errors) {
        resetFormErrors();
        $.each(errors, function(field, messages) {
            $('#' + field).addClass('is-invalid');
            $('#' + field + '-error').text(messages[0]);
        });
    }

        // Update bulk delete button state
    function updateBulkDeleteButton() {
        if (selectedIds.length > 0) {
            $('#bulk-delete').prop('disabled', false);
        } else {
            $('#bulk-delete').prop('disabled', true);
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
        locationTable = $('#locationTable').DataTable({
            ajax: {
                url: "{{ route('admin.lokasi.getData') }}",
                type: 'GET',
                dataType: 'json',
                dataSrc: 'data',
                complete: function(data) {
                        // Update statistics from response
                    try {
                        const responseData = data.responseJSON;
                        if (responseData && responseData.meta) {
                            $('#totalLocations').text(responseData.meta.totalLocations || 0);
                            $('#totalBuildings').text(responseData.meta.totalBuildings || 0);
                            $('#totalEquipment').text(responseData.meta.totalEquipment || 0);
                        }
                    } catch (e) {
                        console.error('Error updating statistics:', e);
                    }
                }
            },
            searching: true,
            serverSide: false,
            processing: true,
            columns: [{
                data: 'id',
                orderable: false,
                searchable: false,
                render: function(data) {
                    return `<div class="form-check">
                                <input class="form-check-input row-checkbox" type="checkbox" value="${data}" id="check-${data}">
                                <label class="form-check-label" for="check-${data}"></label>
                    </div>`;
                }
            },
            {
                data: null,
                name: 'DT_RowIndex',
                searchable: false,
                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {
                data: 'name',
                render: function(data, type, row) {
                    return `<div class="d-flex align-items-center">
                                    <span class="avatar-sm me-2 bg-light d-inline-flex align-items-center justify-content-center rounded-circle">
                                        <i class="fas fa-map-marker-alt text-primary"></i>
                                    </span>
                                    <strong>${data}</strong>
                    </div>`;
                }
            },
            {
                data: 'code',
                render: function(data) {
                    return data || '<span class="text-muted">-</span>';
                }
            },
            {
                data: 'building',
                render: function(data) {
                    return data || '<span class="text-muted">-</span>';
                }
            },
            {
                data: null,
                render: function(data, type, row) {
                    let floor = row.floor ? `Lantai ${row.floor}` : '';
                    let room = row.room ? `Ruang ${row.room}` : '';

                    if (floor && room) {
                        return `${floor}, ${room}`;
                    } else if (floor) {
                        return floor;
                    } else if (room) {
                        return room;
                    } else {
                        return '<span class="text-muted">-</span>';
                    }
                }
            },
            {
                data: 'equipment_count',
                className: 'text-center',
                render: function(data) {
                    return `<span class="badge fw-bold badge-soft-dark fs-6 p-1">${data}</span>`;
                }
            },
            {
                data: null,
                orderable: false,
                searchable: false,
                render: function(data, type, full, meta) {
                    return `
                            <div class="d-flex p-0 m-0 align-items-center justify-content-center">
                                <button class="btn bg-success-light btn-sm me-1 btn-detail" data-id="${full.id}" title="Lihat Detail">
                                        <i class="far fa-eye"></i>
                                </button>
                                <button class="btn bg-success-light btn-sm me-1 btn-edit" data-id="${full.id}" title="Edit Lokasi">
                                        <i class="feather-edit"></i>
                                </button>
                                <button class="btn btn-sm bg-success-light btn-delete" data-id="${full.id}" ${isProcessing ? 'disabled' : ''} title="Hapus Lokasi">
                                        <i class="feather-trash"></i>
                                </button>
                            </div>
                    `;
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

                    // Reset selected checkboxes
            selectedIds = [];
            updateBulkDeleteButton();
            $('#select-all').prop('checked', false);
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

            // Select all checkboxes
$('#select-all').on('change', function() {
    const isChecked = $(this).prop('checked');

    $('.row-checkbox').prop('checked', isChecked);

    selectedIds = [];
    if (isChecked) {
        $('.row-checkbox:checked').each(function() {
            selectedIds.push($(this).val());
        });
    }

    updateBulkDeleteButton();
});

            // Handle individual checkbox changes
$('#locationTable').on('change', '.row-checkbox', function() {
    const id = $(this).val();

    if ($(this).prop('checked')) {
        if (!selectedIds.includes(id)) {
            selectedIds.push(id);
        }
    } else {
        selectedIds = selectedIds.filter(item => item !== id);
    }

                // Update "select all" checkbox state
    if ($('.row-checkbox:checked').length === $('.row-checkbox').length) {
        $('#select-all').prop('checked', true);
    } else {
        $('#select-all').prop('checked', false);
    }

    updateBulkDeleteButton();
});

            // Bulk delete button handler
$('#bulk-delete').on('click', function() {
    if (selectedIds.length === 0) return;

    Swal.fire({
        title: "Hapus Lokasi Terpilih?",
        text: `Anda akan menghapus ${selectedIds.length} lokasi. Tindakan ini tidak dapat dibatalkan!`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: "Ya, Hapus!",
        cancelButtonText: "Batal",
        showLoaderOnConfirm: true,
        customClass: {
            confirmButton: 'btn btn-danger',
            cancelButton: 'btn btn-secondary',
        },
        preConfirm: () => {
            isProcessing = true;
            return $.ajax({
                url: "{{ route('admin.lokasi.bulkDestroy') }}",
                type: 'POST',
                data: {
                    ids: selectedIds
                }
            }).then(response => {
                return response;
            }).catch(error => {
                Swal.showValidationMessage(
            `Gagal: ${error.responseJSON?.message || 'Terjadi kesalahan pada server'}`
            );
            });
        },
        allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
        isProcessing = false;
        if (result.isConfirmed) {
            locationTable.ajax.reload();
            selectedIds = [];
            updateBulkDeleteButton();

            Swal.fire({
                title: 'Berhasil!',
                text: result.value.message ||
                'Lokasi terpilih berhasil dihapus',
                icon: 'success'
            });
        }
    });
});



            // Refresh button click handler
$('#refreshTable').on('click', function() {
    const $icon = $(this).find('i');
    $icon.addClass('refreshing');

                // Reload the table
    locationTable.ajax.reload(function() {
                    // After reload is complete, remove the spinning animation
        setTimeout(function() {
            $icon.removeClass('refreshing');
        }, 500);

                    // Show success toast
        toastr.success('Data berhasil diperbarui', 'Sukses');
    });
});

            // Add Location modal
$('#add-location').on('click', function() {
    resetModalForm();
    $('#locationModal').modal('show');
});

            // Edit button click handler
$('#locationTable').on('click', '.btn-edit', function() {
    const locationId = $(this).data('id');
    loadLocationData(locationId);
});

            // Edit from detail modal
$('#editFromDetail').on('click', function() {
    if (selectedLocation) {
        $('#detailModal').modal('hide');
        loadLocationData(selectedLocation);
    }
});

            // Detail button click handler
$('#locationTable').on('click', '.btn-detail', function() {
    const locationId = $(this).data('id');
    selectedLocation = locationId;
    loadLocationDetail(locationId);
});




            // Location form submit handler
$('#locationForm').on('submit', function(e) {
    e.preventDefault();

    resetFormErrors();

    const formData = new FormData(this);
    const locationId = $('#location_id').val();
    let url = "{{ route('admin.lokasi.store') }}";
    let method = 'POST';

    if (locationId) {
        url = "{{ route('admin.lokasi.update', ':id') }}".replace(':id', locationId);
        method = 'POST';
        formData.append('_method', 'PUT');
    }

    $.ajax({
        url: url,
        type: method,
        data: formData,
        contentType: false,
        processData: false,
        beforeSend: function() {
            $('#saveBtn').html(
                '<i class="fas fa-spinner fa-spin"></i> Menyimpan...');
            $('#saveBtn').prop('disabled', true);
            isProcessing = true;
        },
        success: function(response) {
            $('#locationModal').modal('hide');
            locationTable.ajax.reload();

            if (locationId) {
                toastr.success('Lokasi berhasil diperbarui', 'Sukses');
            } else {
                toastr.success('Lokasi berhasil ditambahkan', 'Sukses');
            }
        },
        error: function(response) {
            if (response.status === 422) {
                displayErrors(response.responseJSON.errors);
            } else {
                toastr.error('Terjadi kesalahan saat menyimpan data', 'Error');
                console.error(response);
            }
        },
        complete: function() {
            $('#saveBtn').html('Simpan');
            $('#saveBtn').prop('disabled', false);
            isProcessing = false;
        }
    });
});

            // Delete location button handler
$('#locationTable').on('click', '.btn-delete', function(e) {
    e.preventDefault();
    if (isProcessing) {
        Swal.fire({
            title: 'Proses Sedang Berjalan',
            text: 'Harap tunggu hingga proses selesai',
            icon: 'warning'
        });
        return;
    }

    const locationId = $(this).data('id');

    Swal.fire({
        title: "Anda yakin?",
        text: "Data lokasi akan dihapus permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: "Ya, Hapus!",
        cancelButtonText: "Batal",
        showLoaderOnConfirm: true,
        customClass: {
            confirmButton: 'btn btn-danger',
            cancelButton: 'btn btn-secondary',
        },
        preConfirm: () => {
            isProcessing = true;
            return $.ajax({
                url: "{{ route('admin.lokasi.destroy', ':id') }}".replace(
                    ':id', locationId),
                type: 'DELETE'
            }).then(response => {
                return response;
            }).catch(error => {
                Swal.showValidationMessage(
            `Gagal: ${error.responseJSON?.message || 'Terjadi kesalahan pada server'}`
            );
            });
        },
        allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
        isProcessing = false;
        if (result.isConfirmed) {
            locationTable.ajax.reload();
            Swal.fire({
                title: 'Berhasil!',
                text: result.value.message || 'Lokasi berhasil dihapus',
                icon: 'success'
            });
        }
    });
});
});


        // Load location data for editing
function loadLocationData(id) {
    $.ajax({
        url: "{{ route('admin.lokasi.show', ':id') }}".replace(':id', id),
        type: 'GET',
        beforeSend: function() {
            isProcessing = true;
        },
        success: function(response) {
            resetModalForm();

            const location = response.data;
            $('#locationModalLabel').text('Edit Lokasi Penyimpanan');
            $('#location_id').val(location.id);
            $('#name').val(location.name);
            $('#code').val(location.code);
            $('#building').val(location.building);
            $('#floor').val(location.floor);
            $('#room').val(location.room);
            $('#description').val(location.description);

            $('#locationModal').modal('show');
        },
        error: function(xhr) {
            toastr.error('Gagal memuat data lokasi', 'Error');
            console.error(xhr);
        },
        complete: function() {
            isProcessing = false;
        }
    });
}

        // Load location detail
function loadLocationDetail(id) {
    $.ajax({
        url: "{{ route('admin.lokasi.show', ':id') }}".replace(':id', id),
        type: 'GET',
        beforeSend: function() {
            isProcessing = true;
        },
        success: function(response) {
            const location = response.data;

                    // Set data to detail modal
            $('#detail-name').text(location.name);
            $('#detail-code').text(location.code ? `Kode: ${location.code}` : '');
            $('#detail-building').text(location.building || '-');
            $('#detail-floor').text(location.floor || '-');
            $('#detail-room').text(location.room || '-');
            $('#detail-description').text(location.description || 'Tidak ada deskripsi.');
            $('#detail-equipment-count').text(location.equipment_count || 0);

                    // Show modal
            $('#detailModal').modal('show');
        },
        error: function(xhr) {
            toastr.error('Gagal memuat detail lokasi', 'Error');
            console.error(xhr);
        },
        complete: function() {
            isProcessing = false;
        }
    });
}
</script>
@endpush
