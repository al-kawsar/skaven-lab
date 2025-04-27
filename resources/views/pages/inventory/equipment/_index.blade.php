@extends('layouts.app-layout')
@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-sub-header">
                    <h3 class="page-title">Inventaris Barang</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Manajemen Barang</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistik Barang -->
    <div class="row">
        <div class="col-xl-3 col-sm-6 col-12 d-flex">
            <div class="card bg-comman w-100">
                <div class="card-body">
                    <div class="db-widgets d-flex justify-content-between align-items-center">
                        <div class="db-info">
                            <h5 class="p-0 m-0 text-muted">Total Barang</h5>
                            <p class="mx-0 my-1 p-0 fw-bold fs-5" id="totalData">{{ $data['totalData'] ?? 0 }}</p>
                            <p class="m-0 p-0 text-muted fw-semibold" style="font-size: 14px">Jenis barang keseluruhan</p>
                        </div>
                        <div class="db-icon">
                            <i class="fas fa-box text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 col-12 d-flex">
            <div class="card bg-comman w-100">
                <div class="card-body">
                    <div class="db-widgets d-flex justify-content-between align-items-center">
                        <div class="db-info">
                            <h5 class="p-0 m-0 text-muted">Kondisi Baik</h5>
                            <p class="mx-0 my-1 p-0 fw-bold fs-5" id="totalGoodCondition">
                                {{ $data['totalGoodCondition'] ?? 0 }}</p>
                            <p class="m-0 p-0 text-muted fw-semibold" style="font-size: 14px">Barang dalam kondisi baik</p>
                        </div>
                        <div class="db-icon">
                            <i class="fas fa-check-circle text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 col-12 d-flex">
            <div class="card bg-comman w-100">
                <div class="card-body">
                    <div class="db-widgets d-flex justify-content-between align-items-center">
                        <div class="db-info">
                            <h5 class="p-0 m-0 text-muted">Rusak Ringan</h5>
                            <p class="mx-0 my-1 p-0 fw-bold fs-5" id="totalMinorDamage">{{ $data['totalMinorDamage'] ?? 0 }}
                            </p>
                            <p class="m-0 p-0 text-muted fw-semibold" style="font-size: 14px">Barang dengan kerusakan ringan
                            </p>
                        </div>
                        <div class="db-icon">
                            <i class="fas fa-exclamation-circle text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 col-12 d-flex">
            <div class="card bg-comman w-100">
                <div class="card-body">
                    <div class="db-widgets d-flex justify-content-between align-items-center">
                        <div class="db-info">
                            <h5 class="p-0 m-0 text-muted">Rusak Berat</h5>
                            <p class="mx-0 my-1 p-0 fw-bold fs-5" id="totalMajorDamage">{{ $data['totalMajorDamage'] ?? 0 }}
                            </p>
                            <p class="m-0 p-0 text-muted fw-semibold" style="font-size: 14px">Barang dengan kerusakan berat
                            </p>
                        </div>
                        <div class="db-icon">
                            <i class="fas fa-times-circle text-danger"></i>
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
                            <div class="col-md-2 mb-2 mb-md-0">
                                <label for="filterName" class="form-label">Nama Barang</label>
                                <input type="text" class="form-control form-control-md" id="filterName"
                                    placeholder="Cari berdasarkan nama">
                            </div>
                            <div class="col-md-2 mb-2 mb-md-0">
                                <label for="filterCode" class="form-label">Kode Barang</label>
                                <input type="text" class="form-control form-control-md" id="filterCode"
                                    placeholder="Cari berdasarkan kode">
                            </div>
                            <div class="col-md-2 mb-2 mb-md-0">
                                <label for="filterCategory" class="form-label">Kategori</label>
                                <select class="form-control form-control-md" id="filterCategory">
                                    <option value="">Semua Kategori</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 mb-2 mb-md-0">
                                <label for="filterLocation" class="form-label">Lokasi</label>
                                <select class="form-control form-control-md" id="filterLocation">
                                    <option value="">Semua Lokasi</option>
                                    @foreach ($locations as $location)
                                        <option value="{{ $location->id }}">{{ $location->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 mb-2 mb-md-0">
                                <label for="filterCondition" class="form-label">Kondisi</label>
                                <select class="form-control form-control-md" id="filterCondition">
                                    <option value="">Semua Kondisi</option>
                                    <option value="baik">Baik</option>
                                    <option value="rusak ringan">Rusak Ringan</option>
                                    <option value="rusak berat">Rusak Berat</option>
                                </select>
                            </div>
                            <div class="col-md-2 mb-2 mb-md-0">
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
                                <h3 class="page-title">Daftar Barang</h3>
                            </div>
                            <div class="col-auto text-end float-end ms-auto download-grp">
                                <button class="btn btn-outline-primary me-2" id="refreshTable" title="Refresh Data">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                                <button class="btn btn-outline-danger me-2 fw-bold" id="delete-all"
                                    data-total="{{ $data['totalData'] }}" {{ $data['totalData'] <= 0 ? 'disabled' : '' }}>
                                    <i class="fas fa-trash"></i> Hapus Semua
                                </button>
                                <button class="btn btn-primary fw-bold" id="add-equipment">
                                    <i class="fas fa-plus"></i> Tambah Barang
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table border-0 star-student  table-hover table-center mb-0 datatable "
                            id="equipmentTable">
                            <thead class="student-thread">
                                <tr>
                                    <th>#</th>
                                    <th>Nama Barang</th>
                                    <th>Kode</th>
                                    <th>Stok</th>
                                    <th>Kategori</th>
                                    <th>Lokasi</th>
                                    <th>Kondisi</th>
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

    <!-- Modal Tambah/Edit Barang -->
    <div class="modal fade" id="equipmentModal" tabindex="-1" aria-labelledby="equipmentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="equipmentModalLabel">Tambah Barang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="equipmentForm" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" id="equipment_id" name="equipment_id">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama Barang <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                    <div class="invalid-feedback" id="name-error"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="code" class="form-label">Kode Barang</label>
                                    <input type="text" class="form-control" id="code" name="code">
                                    <div class="invalid-feedback" id="code-error"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="stock" class="form-label">Stok <span
                                            class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="stock" name="stock"
                                        min="0" required>
                                    <div class="invalid-feedback" id="stock-error"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="category_id" class="form-label">Kategori</label>
                                    <select class="form-control" id="category_id" name="category_id">
                                        <option value="">Pilih Kategori</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback" id="category_id-error"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="condition" class="form-label">Kondisi <span
                                            class="text-danger">*</span></label>
                                    <select class="form-control" id="condition" name="condition" required>
                                        <option value="baik">Baik</option>
                                        <option value="rusak ringan">Rusak Ringan</option>
                                        <option value="rusak berat">Rusak Berat</option>
                                    </select>
                                    <div class="invalid-feedback" id="condition-error"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="location_id" class="form-label">Lokasi Penyimpanan</label>
                                    <select class="form-control" id="location_id" name="location_id">
                                        <option value="">Pilih Lokasi</option>
                                        @foreach ($locations as $location)
                                            <option value="{{ $location->id }}">{{ $location->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback" id="location_id-error"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="image" class="form-label">Gambar Barang</label>
                                    <input type="file" class="form-control" id="image" name="image"
                                        accept="image/*">
                                    <div class="invalid-feedback" id="image-error"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="description" class="form-label">Deskripsi</label>
                                    <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                                    <div class="invalid-feedback" id="description-error"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2 mb-2" id="preview-image-container" style="display: none;">
                            <div class="col-md-12">
                                <label class="form-label">Preview Gambar:</label>
                                <div class="text-center">
                                    <img id="preview-image" src="" alt="Preview" class="img-fluid"
                                        style="max-height: 200px;">
                                </div>
                            </div>
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

    <!-- Modal Detail Barang -->
    <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-header">
            <h5 class="modal-title" id="detailModalLabel">Detail Barang</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-4 text-center mb-3">
                    <div class="img-thumbnail p-2"
                        style="height: 200px; display: flex; align-items: center; justify-content: center;">
                        <img id="detail-image" src="" alt="Gambar Barang" class="img-fluid"
                            style="max-height: 180px;">
                    </div>
                    <div class="mt-2">
                        <span class="badge bg-primary-light" id="detail-category">-</span>
                        <span class="badge bg-info-light" id="detail-location">-</span>
                    </div>
                </div>
                <div class="col-md-8">
                    <h4 id="detail-name" class="fw-bold"></h4>
                    <p id="detail-code" class="text-muted"></p>

                    <div class="row mt-3">
                        <div class="col-6">
                            <p class="mb-1 text-muted">Stok:</p>
                            <h5 id="detail-stock" class="fw-bold"></h5>
                        </div>
                        <div class="col-6">
                            <p class="mb-1 text-muted">Kondisi:</p>
                            <div id="detail-condition-badge"></div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <p class="mb-1 text-muted">Deskripsi:</p>
                        <p id="detail-description" class="text-justify"></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            <button type="button" class="btn btn-primary" id="editFromDetail">Edit Barang</button>
        </div>
    </div>
    </div>
    </div>
@endsection

@push('script')
    <script>
        var equipmentTable;
        var isProcessing = false;
        var selectedEquipment = null;

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

        // Preview image when selected
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#preview-image').attr('src', e.target.result);
                    $('#preview-image-container').show();
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        // Reset form errors
        function resetFormErrors() {
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').text('');
        }

        // Reset modal form
        function resetModalForm() {
            $('#equipmentForm')[0].reset();
            $('#equipment_id').val('');
            $('#preview-image-container').hide();
            resetFormErrors();
            $('#equipmentModalLabel').text('Tambah Barang');
        }

        // Display errors on form
        function displayErrors(errors) {
            resetFormErrors();
            $.each(errors, function(field, messages) {
                $('#' + field).addClass('is-invalid');
                $('#' + field + '-error').text(messages[0]);
            });
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
            equipmentTable = $('#equipmentTable').DataTable({
                ajax: {
                    url: "{{ route('admin.barang.getData') }}",
                    type: 'GET',
                    dataType: 'json',
                    data: function(d) {
                        d.name = $('#filterName').val();
                        d.code = $('#filterCode').val();
                        d.category_id = $('#filterCategory').val();
                        d.location_id = $('#filterLocation').val();
                        d.condition = $('#filterCondition').val();
                        return d;
                    },
                    dataSrc: 'data',
                    complete: function(data) {
                        // Update counters based on the returned data
                        try {
                            const responseData = data.responseJSON;
                            if (responseData && responseData.meta) {
                                const totalCount = responseData.meta.total || 0;
                                const goodCondition = responseData.meta.goodCondition || 0;
                                const minorDamage = responseData.meta.minorDamage || 0;
                                const majorDamage = responseData.meta.majorDamage || 0;
                                const totalStock = responseData.meta.totalStock || 0;

                                $('#totalData').text(totalCount);
                                $('#totalGoodCondition').text(goodCondition);
                                $('#totalMinorDamage').text(minorDamage);
                                $('#totalMajorDamage').text(majorDamage);

                                $('#delete-all').data('total', totalCount);

                                // Update button state based on count
                                updateDeleteAllButtonState(totalCount);
                            }
                        } catch (e) {
                            console.error('Error updating counters:', e);
                        }
                    }
                },
                searching: true,
                serverSide: false,
                processing: true,
                columns: [{
                        data: 'number',
                        name: 'number'
                    },
                    {
                        data: 'name',
                        name: 'name',
                        render: function(data, type, row) {
                            let img = '';
                            if (row.image) {
                                img =
                                    `<img src="${row.image}" alt="${data}" class="avatar-sm me-2">`;
                            } else {
                                img =
                                    `<div class="avatar-sm me-2 bg-light d-inline-flex align-items-center justify-content-center rounded-circle"><i class="fas fa-box"></i></div>`;
                            }
                            return `<div class="d-flex align-items-center">
                            ${img}
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
                        data: 'stock',
                        render: function(data) {
                            return `<span class="badge bg-info-light">${data}</span>`;
                        }
                    },
                    {
                        data: 'category',
                        render: function(data) {
                            return data || '<span class="text-muted">-</span>';
                        }
                    },
                    {
                        data: 'location',
                        render: function(data) {
                            return data || '<span class="text-muted">-</span>';
                        }
                    },
                    {
                        data: 'condition',
                        render: function(data, type, row) {
                            let badgeClass = 'bg-primary-light';
                            let label = data;

                            if (data === 'baik') {
                                badgeClass = 'bg-success-light';
                                label = 'Baik';
                            } else if (data === 'rusak ringan') {
                                badgeClass = 'bg-warning-light';
                                label = 'Rusak Ringan';
                            } else if (data === 'rusak berat') {
                                badgeClass = 'bg-danger-light';
                                label = 'Rusak Berat';
                            }

                            return `<div class="badge ${badgeClass} rounded-pill text-capitalize">${label}</div>`;
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
                        <button class="btn bg-success-light btn-sm me-1 btn-edit" data-id="${full.id}" title="Edit Barang">
                            <i class="feather-edit"></i>
                        </button>
                        <button class="btn btn-sm bg-success-light btn-delete" data-id="${full.id}" ${isProcessing ? 'disabled' : ''} title="Hapus Barang">
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
                    $('[title]').tooltip();
                },
                language: {
                    sProcessing: "Sedang diproses...",
                    sLengthMenu: "Menampilkan _MENU_ data per halaman",
                    sZeroRecords: "Data tidak ditemukan",
                    sInfo: "Menampilkan data _START_ hingga _END_ dari total _TOTAL_ data",
                    sInfoEmpty: "Tidak ada data yang dapat ditampilkan",
                    sInfoFiltered: "(difilter dari _MAX_ total data)",
                    sInfoPostFix: "",
                    sSearch: "Pencarian:",
                    sUrl: "",
                    oPaginate: {
                        sFirst: "Awal",
                        sPrevious: "Kembali",
                        sNext: "Lanjut",
                        sLast: "Akhir"
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
                equipmentTable.ajax.reload(function() {
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
                equipmentTable.ajax.reload();
            });

            // Filter reset button click handler
            $('#resetFilter').on('click', function() {
                $('#filterName').val('');
                $('#filterCode').val('');
                $('#filterCategory').val('').trigger('change');
                $('#filterLocation').val('').trigger('change');
                $('#filterCondition').val('').trigger('change');
                equipmentTable.ajax.reload();
            });

            // Image preview on file input change
            $('#image').change(function() {
                readURL(this);
            });

            // Add Equipment modal
            $('#add-equipment').on('click', function() {
                resetModalForm();
                $('#equipmentModal').modal('show');
            });

            // Edit button click handler
            $('#equipmentTable').on('click', '.btn-edit', function() {
                const equipmentId = $(this).data('id');
                loadEquipmentData(equipmentId);
            });

            // Edit from detail modal
            $('#editFromDetail').on('click', function() {
                if (selectedEquipment) {
                    $('#detailModal').modal('hide');
                    loadEquipmentData(selectedEquipment);
                }
            });

            // Detail button click handler
            $('#equipmentTable').on('click', '.btn-detail', function() {
                const equipmentId = $(this).data('id');
                selectedEquipment = equipmentId;
                loadEquipmentDetail(equipmentId);
            });

            // Equipment form submit handler
            $('#equipmentForm').on('submit', function(e) {
                e.preventDefault();

                resetFormErrors();

                const formData = new FormData(this);
                const equipmentId = $('#equipment_id').val();
                let url = "{{ route('admin.barang.store') }}";
                let method = 'POST';

                if (equipmentId) {
                    url = "{{ route('admin.barang.update', ':id') }}".replace(':id', equipmentId);
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
                        $('#equipmentModal').modal('hide');
                        equipmentTable.ajax.reload();

                        if (equipmentId) {
                            toastr.success('Barang berhasil diperbarui', 'Sukses');
                        } else {
                            toastr.success('Barang berhasil ditambahkan', 'Sukses');
                        }
                    },
                    error: function(response) {
                        if (response.status === 422) {
                            displayErrors(response.responseJSON.errors);
                        } else {
                            toastr.error('Terjadi kesalahan saat menyimpan data', 'Error');
                        }
                    },
                    complete: function() {
                        $('#saveBtn').html('Simpan');
                        $('#saveBtn').prop('disabled', false);
                        isProcessing = false;
                    }
                });
            });

            // Delete equipment button handler
            $('#equipmentTable').on('click', '.btn-delete', function(e) {
                e.preventDefault();
                if (isProcessing) {
                    Swal.fire({
                        title: 'Proses Sedang Berjalan',
                        text: 'Harap tunggu hingga proses selesai',
                        icon: 'warning'
                    });
                    return;
                }

                const equipmentId = $(this).data('id');

                Swal.fire({
                    title: "Anda yakin?",
                    text: "Data barang akan dihapus permanen!",
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
                            url: "{{ route('admin.barang.destroy', ':id') }}"
                                .replace(
                                    ':id', equipmentId),
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
                        equipmentTable.ajax.reload();
                        Swal.fire({
                            title: 'Berhasil!',
                            text: result.value.message || 'Barang berhasil dihapus',
                            icon: 'success'
                        });
                    }
                });
            });

            // Delete all equipment button handler
            $("#delete-all").on("click", function(e) {
                e.preventDefault();

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
                        text: 'Tidak ada data barang untuk dihapus',
                        icon: 'error'
                    });
                    return;
                }

                Swal.fire({
                    title: "Anda yakin?",
                    text: "Semua data barang akan dihapus permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: "Ya, Hapus Semua!",
                    cancelButtonText: "Batal",
                    showLoaderOnConfirm: true,
                    customClass: {
                        confirmButton: 'btn btn-danger',
                        cancelButton: 'btn btn-secondary',
                    },
                    preConfirm: () => {
                        isProcessing = true;
                        return $.ajax({
                            url: "{{ route('admin.barang.destroyAll') }}",
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
                        $(this).data('total', 0);
                        $('#totalData').text(0);
                        $('#totalGoodCondition').text(0);
                        $('#totalMinorDamage').text(0);
                        $('#totalMajorDamage').text(0);

                        updateDeleteAllButtonState(0);
                        equipmentTable.ajax.reload();

                        Swal.fire({
                            title: 'Berhasil!',
                            text: result.value.message ||
                                'Semua barang berhasil dihapus',
                            icon: 'success'
                        });
                    }
                });
            });
        });

        // Load equipment data for editing
        function loadEquipmentData(id) {
            $.ajax({
                url: "{{ route('admin.barang.show', ':id') }}".replace(':id', id),
                type: 'GET',
                beforeSend: function() {
                    isProcessing = true;
                },
                success: function(response) {
                    resetModalForm();

                    const equipment = response.data;
                    $('#equipmentModalLabel').text('Edit Barang');
                    $('#equipment_id').val(equipment.id);
                    $('#name').val(equipment.name);
                    $('#code').val(equipment.code);
                    $('#stock').val(equipment.stock);
                    $('#description').val(equipment.description);
                    $('#condition').val(equipment.condition);
                    $('#category_id').val(equipment.category_id);
                    $('#location_id').val(equipment.location_id);

                    if (equipment.file) {
                        $('#preview-image').attr('src', equipment.file.path_name);
                        $('#preview-image-container').show();
                    }

                    $('#equipmentModal').modal('show');
                },
                error: function(xhr) {
                    toastr.error('Gagal memuat data barang', 'Error');
                },
                complete: function() {
                    isProcessing = false;
                }
            });
        }

        // Load equipment detail
        function loadEquipmentDetail(id) {
            $.ajax({
                url: "{{ route('admin.barang.show', ':id') }}".replace(':id', id),
                type: 'GET',
                beforeSend: function() {
                    isProcessing = true;
                },
                success: function(response) {
                    const equipment = response.data;

                    // Set data to detail modal
                    $('#detail-name').text(equipment.name);
                    $('#detail-code').text(equipment.code ? `Kode: ${equipment.code}` : 'Kode: -');
                    $('#detail-stock').text(equipment.stock);

                    // Set condition badge
                    let conditionClass = 'bg-primary';
                    let conditionText = equipment.condition;

                    if (equipment.condition === 'baik') {
                        conditionClass = 'bg-success';
                        conditionText = 'Baik';
                    } else if (equipment.condition === 'rusak ringan') {
                        conditionClass = 'bg-warning';
                        conditionText = 'Rusak Ringan';
                    } else if (equipment.condition === 'rusak berat') {
                        conditionClass = 'bg-danger';
                        conditionText = 'Rusak Berat';
                    }

                    $('#detail-condition-badge').html(
                        `<span class="badge ${conditionClass}">${conditionText}</span>`);

                    // Set description
                    $('#detail-description').text(equipment.description || 'Tidak ada deskripsi.');

                    // Set category and location
                    $('#detail-category').text(equipment.category ? equipment.category.name :
                        'Tanpa Kategori');
                    $('#detail-location').text(equipment.location ? equipment.location.name :
                        'Lokasi Tidak Ditentukan');

                    // Set image
                    if (equipment.file) {
                        $('#detail-image').attr('src', equipment.file.path_name);
                        $('#detail-image').closest('div').show();
                    } else {
                        $('#detail-image').attr('src', '/assets/img/no-image.png');
                        $('#detail-image').closest('div').show();
                    }

                    // Show modal
                    $('#detailModal').modal('show');
                },
                error: function(xhr) {
                    toastr.error('Gagal memuat detail barang', 'Error');
                },
                complete: function() {
                    isProcessing = false;
                }
            });
        }
    </script>
@endpush
