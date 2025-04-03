@extends('layouts.app-layout')
@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-sub-header">
                    <h3 class="page-title">Inventaris Barang ASLI NIH</h3>
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
                            <p class="mx-0 my-1 p-0 fw-bold fs-5" id="totalData"> 0 </p>
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
                                0</p>
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
                            <p class="mx-0 my-1 p-0 fw-bold fs-5" id="totalMinorDamage">0
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
                            <p class="mx-0 my-1 p-0 fw-bold fs-5" id="totalMajorDamage">0
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
                                </select>
                            </div>
                            <div class="col-md-2 mb-2 mb-md-0">
                                <label for="filterLocation" class="form-label">Lokasi</label>
                                <select class="form-control form-control-md" id="filterLocation">
                                    <option value="">Semua Lokasi</option>
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
                                <button class="btn btn-outline-primary me-2" type="button" data-bs-toggle="dropdown"
                                    aria-expanded="false" title="Export Data">
                                    <i class="fas fa-file-export"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#" id="export-excel">Excel (.xlsx)</a></li>
                                    <li><a class="dropdown-item" href="#" id="export-csv">CSV (.csv)</a></li>
                                    <li><a class="dropdown-item" href="#" id="export-pdf">PDF (.pdf)</a></li>
                                </ul>

                                <button class="btn btn-outline-primary me-2" type="button" data-bs-toggle="dropdown"
                                    aria-expanded="false" title="Import Data">
                                    <i class="fas fa-file-import"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#" id="import-template">Download
                                            Template</a></li>
                                    <li><a class="dropdown-item" href="#" id="import-data">Import Data</a></li>
                                </ul>

                                <button class="btn btn-outline-primary me-2" id="refreshTable" title="Refresh Data">
                                    <i class="fas fa-sync-alt"></i>
                                </button>

                                <button class="btn btn-outline-danger me-2" id="bulk-delete" disabled
                                    title="Hapus Data Terpilih">
                                    <i class="fas fa-trash"></i>
                                </button>

                                <button class="btn btn-outline-danger me-2" id="delete-all" data-total="0"
                                    title="Hapus Semua Data">
                                    <i class="fas fa-trash-alt"></i>
                                </button>

                                <button class="btn btn-primary fw-bold" id="add-equipment">
                                    <i class="fas fa-plus"></i> Tambah Barang
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table border-0 star-student  table-hover table-center mb-0 datatable w-100"
                            id="equipmentTable">
                            <thead class="student-thread">
                                <tr>
                                    <th>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="select-all">
                                            <label class="form-check-label" for="select-all"></label>
                                        </div>
                                    </th>
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

    <!-- Modal Import Data -->
    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">Import Data Barang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="importForm" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <ul class="mb-0">
                                <li>Pastikan data yang diimport menggunakan template yang sesuai</li>
                                <li>File yang didukung: .xlsx, .csv</li>
                                <li>Maksimal ukuran file: 2MB</li>
                                <li>Kode Kategori dan Kode Lokasi harus sesuai dengan yang sudah terdaftar</li>
                            </ul>
                        </div>

                        <div class="mb-3">
                            <label for="import_file" class="form-label">File Import <span
                                    class="text-danger">*</span></label>
                            <input type="file" class="form-control" id="import_file" name="import_file"
                                accept=".xlsx,.csv" required>
                            <div class="invalid-feedback" id="import_file-error"></div>
                            <div class="form-text">Pilih file Excel atau CSV yang berisi data barang</div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="skip_header" name="skip_header"
                                    checked>
                                <label class="form-check-label" for="skip_header">
                                    Lewati baris header (baris pertama)
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Opsi Import</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="import_option"
                                    id="option_add_update" value="add_update" checked>
                                <label class="form-check-label" for="option_add_update">
                                    Tambah baru & perbarui yang sudah ada (berdasarkan kode)
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="import_option" id="option_add_only"
                                    value="add_only">
                                <label class="form-check-label" for="option_add_only">
                                    Tambah data baru saja (lewati yang sudah ada)
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="importBtn">Import Data</button>
                    </div>
                </form>
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
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama Barang <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                    <div class="invalid-feedback" id="name-error"></div>
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
@endsection

@push('script')
    <script>
        var equipmentTable;
        var isProcessing = false;
        var selectedEquipment = null;
        var selectedIds = [];

        // Tambahkan CSS untuk indikator auto-generate
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
                .auto-code-field {
                    background-color: rgba(0, 123, 255, 0.05);
                    border-color: #cce5ff;
                }
                #code {
                    transition: all 0.3s ease;
                }
            `)
            .appendTo('head');

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

        // Function to update bulk delete button state
        function updateBulkDeleteButton() {
            if (selectedIds.length > 0) {
                $('#bulk-delete').prop('disabled', false);
            } else {
                $('#bulk-delete').prop('disabled', true);
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

            // Reset kode placeholder
            $('#code').val('');
            $('#code').attr('placeholder', 'Akan dibuat otomatis saat menyimpan');
        }

        // Display errors on form
        function displayErrors(errors) {
            resetFormErrors();
            $.each(errors, function(field, messages) {
                $('#' + field).addClass('is-invalid');
                $('#' + field + '-error').text(messages[0]);
            });
        }


        // Add these functions at the beginning of your script
        function loadCategories(selectElement) {
            $.ajax({
                url: "{{ route('admin.barang.categories') }}",
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        const select = $(selectElement);
                        select.empty();
                        select.append('<option value="">Pilih Kategori</option>');
                        response.data.forEach(function(category) {
                            select.append(`<option value="${category.id}">${category.name}</option>`);
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching categories:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Gagal memuat data kategori'
                    });
                }
            });
        }

        function loadLocations(selectElement) {
            $.ajax({
                url: "{{ route('admin.barang.locations') }}",
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        const select = $(selectElement);
                        select.empty();
                        select.append('<option value="">Pilih Lokasi</option>');
                        response.data.forEach(function(location) {
                            select.append(`<option value="${location.id}">${location.name}</option>`);
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching locations:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Gagal memuat data lokasi'
                    });
                }
            });
        }

        // Initialize DataTable
        $(document).ready(function() {
            // Initialize DataTable
            equipmentTable = $('#equipmentTable').DataTable({
                ajax: {
                    url: "{{ route('admin.barang.getData') }}",
                    type: 'GET',
                    data: function(d) {
                        d.name = $('#filterName').val();
                        d.code = $('#filterCode').val();
                        d.category_id = $('#filterCategory').val();
                        d.location_id = $('#filterLocation').val();
                        d.condition = $('#filterCondition').val();
                        return d;
                    }
                },
                processing: true,
                serverSide: true,
                responsive: true,
                lengthMenu: [10, 25, 50, 100],
                columnDefs: [{
                        orderable: false,
                        className: 'select-checkbox',
                        targets: 0
                    },
                    {
                        orderable: false,
                        targets: [0, 8]
                    }
                ],
                columns: [{
                        data: null,
                        defaultContent: '',
                        render: function(data, type, row) {
                            return `<div class="form-check">
                                <input class="form-check-input select-item" type="checkbox" data-id="${row.id}" id="select-${row.id}">
                                <label class="form-check-label" for="select-${row.id}"></label>
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
                        data: null,
                        render: function(data, type, row) {
                            let img = '';
                            if (row.image && row.image.url) {
                                img =
                                    `<img src="${row.image.url}" alt="${row.name}" class="avatar-sm me-2">`;
                            } else {
                                img =
                                    `<div class="avatar-sm me-2 bg-light d-inline-flex align-items-center justify-content-center rounded-circle"><i class="fas fa-box"></i></div>`;
                            }
                            return `<div class="d-flex align-items-center">
                                ${img}
                                <strong>${row.name}</strong>
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
                        className: 'text-center',
                        render: function(data, type, row) {
                            return `<span class="badge p-1 fs-6 ${row.stock.value > 0 ? 'badge-soft-dark' : 'badge-soft-danger'}">${row.stock.formatted}</span>`;
                        }
                    },
                    {
                        data: 'category',
                        render: function(data, type, row) {
                            if (!data || !data.name) {
                                return `<span class="badge py-2 px-3 text-capitalize rounded-pill bg-light text-muted">Tidak ada</span>`;
                            }

                            // Fungsi untuk menghasilkan warna HSL yang konsisten berdasarkan string
                            function stringToColor(str) {
                                let hash = 0;
                                for (let i = 0; i < str.length; i++) {
                                    hash = str.charCodeAt(i) + ((hash << 5) - hash);
                                }
                                const h = Math.abs(hash) % 360;
                                const s = 65 + (hash % 6);
                                const l = 75 + (hash % 6);
                                return `hsl(${h}, ${s}%, ${l}%)`;
                            }

                            function shouldUseBlackText(hslColor) {
                                const l = parseInt(hslColor.split(',')[2].replace('%)', ''));
                                return l > 70;
                            }

                            const bgColor = stringToColor(data.name);
                            const textColor = shouldUseBlackText(bgColor) ? '#2c2c2c' : '#ffffff';

                            return `<span class="badge py-2 px-3 text-capitalize rounded-pill"
                                      style="background-color: ${bgColor}; color: ${textColor}">
                                    ${data.name}
                                </span>`;
                        }
                    },
                    {
                        data: 'location',
                        render: function(data, type, row) {
                            if (!data || !data.name) {
                                return `<span class="badge rounded-pill py-2 px-3 text-capitalize bg-light text-muted">Tidak ada</span>`;
                            }
                            return `<span class="badge rounded-pill py-2 px-3 text-capitalize bg-info-light">${data.name}</span>`;
                        }
                    },
                    {
                        data: 'condition',
                        render: function(data, type, row) {
                            let badgeClass = 'bg-success';
                            if (data.value === 'rusak ringan') {
                                badgeClass = 'bg-warning';
                            } else if (data.value === 'rusak berat') {
                                badgeClass = 'bg-danger';
                            }
                            return `<div class="badge ${badgeClass} rounded-pill py-2 px-3 text-capitalize">${data.label}</div>`;
                        }
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            var showUrl = '{{ route('admin.barang.detail', ':id') }}'.replace(
                                ':id', row.id);
                            return `
                                <div class="d-flex p-0 m-0 align-items-center justify-content-center">
                                    <a href="${showUrl}" class="btn bg-success-light btn-sm me-1" title="Lihat Detail">
                                        <i class="far fa-eye"></i>
                                    </a>
                                    <a href="javascript:;" class="btn bg-success-light btn-sm me-1 btn-edit" data-id="${row.id}" title="Edit Barang">
                                        <i class="feather-edit"></i>
                                    </a>
                                    <button class="btn btn-sm bg-success-light btn-delete" data-id="${row.id}" ${isProcessing ? 'disabled' : ''} title="Hapus Barang">
                                        <i class="feather-trash"></i>
                                    </button>
                                </div>
                            `;
                        }
                    }
                ],
                drawCallback: function(settings) {
                    try {
                        const responseJson = settings.json;
                        if (responseJson && responseJson.meta) {
                            const meta = responseJson.meta;
                            $('#totalData').text(meta.total || 0);
                            $('#delete-all').data('total', meta.total || 0);
                            $('#totalGoodCondition').text(meta.goodCondition || 0);
                            $('#totalMinorDamage').text(meta.minorDamage || 0);
                            $('#totalMajorDamage').text(meta.majorDamage || 0);
                            updateDeleteAllButtonState(meta.total || 0);
                        }
                    } catch (error) {
                        console.error('Error updating counters:', error);
                    }

                    selectedIds = [];
                    updateBulkDeleteButton();
                    $('#select-all').prop('checked', false);
                    $('[title]').tooltip();
                },
                language: {
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


            // Image preview
            $('#image').on('change', function() {
                readURL(this);
            });

            // Filter events
            $('#applyFilter').on('click', function() {
                equipmentTable.ajax.reload();
            });

            $('#resetFilter').on('click', function() {
                $('#filterForm')[0].reset();
                equipmentTable.ajax.reload();
            });

            // Refresh table button
            $('#refreshTable').on('click', function() {
                // Add rotating animation
                $(this).find('i').addClass('refreshing');

                // Reload the table
                equipmentTable.ajax.reload(function() {
                    // Remove animation when reload is complete
                    $('#refreshTable').find('i').removeClass('refreshing');
                });
            });

            // Open add equipment modal
            $('#add-equipment').on('click', function() {
                resetModalForm();
                $('#equipmentModal').modal('show');
            });

            // Handle form submission
            $('#equipmentForm').on('submit', function(e) {
                e.preventDefault();

                // Disable submit button to prevent multiple submissions
                $('#saveBtn').html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...').prop('disabled',
                    true);
                isProcessing = true;

                // Create FormData object for file upload
                var formData = new FormData(this);

                // Get equipment ID
                const equipmentId = $('#equipment_id').val();

                // Set URL and method based on whether we're adding or editing
                let url = "{{ route('admin.barang.store') }}";
                let method = 'POST';

                if (equipmentId) {
                    // If equipment ID exists, we're editing
                    url = "{{ route('admin.barang.update', '') }}/" + equipmentId;
                    method = 'POST';
                    formData.append('_method', 'PUT'); // Laravel method spoofing for PUT
                }

                // Send AJAX request
                $.ajax({
                    url: url,
                    type: method,
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            // Close modal and reset form
                            $('#equipmentModal').modal('hide');
                            resetModalForm();

                            // Reload table
                            equipmentTable.ajax.reload();

                            // Show success toast with appropriate message
                            toastr.success(equipmentId ? 'Barang berhasil diperbarui' :
                                'Barang berhasil ditambahkan');
                        } else {
                            toastr.error(response.message ||
                                'Terjadi kesalahan saat menyimpan data');
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            // Validation errors
                            displayErrors(xhr.responseJSON.errors);
                            toastr.error('Harap periksa kembali isian form');
                        } else {
                            toastr.error('Terjadi kesalahan server saat menyimpan data');
                            console.error(xhr);
                        }
                    },
                    complete: function() {
                        // Re-enable submit button
                        $('#saveBtn').html('Simpan').prop('disabled', false);
                        isProcessing = false;
                    }
                });
            });

            // Handle select-all checkbox
            $('#select-all').on('change', function() {
                const isChecked = $(this).prop('checked');

                // Check/uncheck all checkboxes
                $('.select-item').prop('checked', isChecked);

                // Update selected IDs array
                selectedIds = [];
                if (isChecked) {
                    $('.select-item').each(function() {
                        selectedIds.push($(this).data('id'));
                    });
                }

                // Update bulk delete button state
                updateBulkDeleteButton();
            });

            // Handle individual checkbox selection
            $(document).on('change', '.select-item', function() {
                const id = $(this).data('id');

                if ($(this).prop('checked')) {
                    // Add ID to selected IDs array if not already exists
                    if (!selectedIds.includes(id)) {
                        selectedIds.push(id);
                    }
                } else {
                    // Remove ID from selected IDs array
                    selectedIds = selectedIds.filter(item => item !== id);

                    // Uncheck select-all if any item is unchecked
                    $('#select-all').prop('checked', false);
                }

                // Check select-all if all items are checked
                if ($('.select-item:checked').length === $('.select-item').length) {
                    $('#select-all').prop('checked', true);
                }

                // Update bulk delete button state
                updateBulkDeleteButton();
            });

            // Handle view button click
            $(document).on('click', '.btn-view', function() {
                const id = $(this).data('id');
                $('#detailModal').modal('show');
                $('#detail-name').html('<i class="fas fa-spinner fa-spin"></i> Memuat...');

                $.ajax({
                    url: "{{ route('admin.barang.show', ':id') }}".replace(':id', id),
                    type: 'GET',
                    success: function(response) {
                        if (response.success) {
                            const equipment = response.data;
                            selectedEquipment = equipment;

                            $('#detail-name').text(equipment.name);
                            $('#detail-code').text('Kode: ' + (equipment.code || '-'));
                            $('#detail-stock').text(equipment.stock.formatted);

                            let badgeClass = 'bg-success';
                            if (equipment.condition.value === 'rusak ringan') {
                                badgeClass = 'bg-warning';
                            } else if (equipment.condition.value === 'rusak berat') {
                                badgeClass = 'bg-danger';
                            }
                            $('#detail-condition-badge').html(
                                `<span class="badge ${badgeClass}">${equipment.condition.label}</span>`
                            );

                            $('#detail-category').text(equipment.category?.name || 'Tidak ada');
                            $('#detail-location').text(equipment.location?.name || 'Tidak ada');
                            $('#detail-description').text(equipment.description ||
                                'Tidak ada deskripsi');

                            if (equipment.image && equipment.image.url) {
                                $('#detail-image').attr('src', equipment.image.url);
                            } else {
                                $('#detail-image').attr('src',
                                    "{{ asset('assets/img/no-image.png') }}");
                            }
                        } else {
                            toastr.error(response.message || 'Gagal memuat detail barang');
                            $('#detailModal').modal('hide');
                        }
                    },
                    error: function(xhr) {
                        toastr.error('Terjadi kesalahan saat memuat detail barang');
                        $('#detailModal').modal('hide');
                        console.error(xhr);
                    }
                });
            });

            // Edit from detail modal
            $('#editFromDetail').on('click', function() {
                if (selectedEquipment) {
                    // Close detail modal and open edit modal
                    $('#detailModal').modal('hide');
                    resetModalForm();

                    // Set form values
                    $('#equipment_id').val(selectedEquipment.id);
                    $('#name').val(selectedEquipment.name);
                    $('#stock').val(selectedEquipment.stock.value);
                    $('#category_id').val(selectedEquipment.category?.id);
                    $('#location_id').val(selectedEquipment.location?.id);
                    $('#condition').val(selectedEquipment.condition.value);
                    $('#description').val(selectedEquipment.description);

                    // Set image preview if exists
                    if (selectedEquipment.image && selectedEquipment.image.url) {
                        $('#preview-image').attr('src', selectedEquipment.image.url);
                        $('#preview-image-container').show();
                    }

                    // Update modal title
                    $('#equipmentModalLabel').text('Edit Barang');

                    // Show modal
                    $('#equipmentModal').modal('show');
                }
            });

            // Handle edit button click
            $(document).on('click', '.btn-edit', function() {
                const id = $(this).data('id');
                resetModalForm();
                $('#equipmentModalLabel').text('Edit Barang');
                $('#equipment_id').val(id);

                // Show loading skeleton
                const loadingHtml = `
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary mb-3" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <h5 class="text-muted">Memuat data barang...</h5>
                        <p class="text-muted small">Mohon tunggu sebentar</p>
                    </div>
                `;

                // Store original form content
                const originalForm = $('#equipmentModal .modal-body').html();

                // Show loading state
                $('#equipmentModal .modal-body').html(loadingHtml);
                $('#equipmentModal').modal('show');

                // Load categories and locations first
                Promise.all([
                    // Convert AJAX calls to promises
                    new Promise((resolve, reject) => {
                        $.ajax({
                            url: "{{ route('admin.barang.categories') }}",
                            type: 'GET',
                            success: resolve,
                            error: reject
                        });
                    }),
                    new Promise((resolve, reject) => {
                        $.ajax({
                            url: "{{ route('admin.barang.locations') }}",
                            type: 'GET',
                            success: resolve,
                            error: reject
                        });
                    }),
                    // Get equipment data
                    new Promise((resolve, reject) => {
                        $.ajax({
                            url: "{{ route('admin.barang.show', ':id') }}".replace(
                                ':id', id),
                            type: 'GET',
                            success: resolve,
                            error: reject
                        });
                    })
                ]).then(([categoriesResponse, locationsResponse, equipmentResponse]) => {
                    // Restore original form
                    $('#equipmentModal .modal-body').html(originalForm);

                    // Populate categories dropdown
                    const categorySelect = $('#category_id');
                    categorySelect.empty();
                    categorySelect.append('<option value="">Pilih Kategori</option>');
                    categoriesResponse.data.forEach(function(category) {
                        categorySelect.append(
                            `<option value="${category.id}">${category.name}</option>`);
                    });

                    // Populate locations dropdown
                    const locationSelect = $('#location_id');
                    locationSelect.empty();
                    locationSelect.append('<option value="">Pilih Lokasi</option>');
                    locationsResponse.data.forEach(function(location) {
                        locationSelect.append(
                            `<option value="${location.id}">${location.name}</option>`);
                    });

                    // Now set the equipment data
                    if (equipmentResponse.success) {
                        const equipment = equipmentResponse.data;

                        $('#name').val(equipment.name);
                        $('#stock').val(equipment.stock.value);
                        $('#category_id').val(equipment.category?.id); // Use optional chaining
                        $('#location_id').val(equipment.location?.id); // Use optional chaining
                        $('#condition').val(equipment.condition.value);
                        $('#description').val(equipment.description);

                        if (equipment.image && equipment.image.url) {
                            $('#preview-image').attr('src', equipment.image.url);
                            $('#preview-image-container').show();
                        }
                    }

                    $('#saveBtn').html('Simpan').prop('disabled', false);
                }).catch(error => {
                    console.error('Error loading data:', error);
                    toastr.error('Terjadi kesalahan saat memuat data');
                    $('#equipmentModal').modal('hide');
                });
            });

            // Handle delete button click
            $(document).on('click', '.btn-delete', function() {
                const id = $(this).data('id');

                // Confirm deletion with preConfirm
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data barang akan dihapus dan tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal',
                    showLoaderOnConfirm: true,
                    preConfirm: () => {
                        isProcessing = true;
                        return $.ajax({
                            url: "{{ route('admin.barang.destroy', ':id') }}".replace(
                                ':id', id),
                            type: 'DELETE',
                            data: {
                                _token: "{{ csrf_token() }}"
                            }
                        }).then(response => {
                            return response;
                        }).catch(error => {
                            Swal.showValidationMessage(
                                `Gagal menghapus data: ${error.responseJSON?.message || 'Terjadi kesalahan server'}`
                            );
                        });
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Reload table
                        equipmentTable.ajax.reload();

                        // Show success message
                        Swal.fire(
                            'Terhapus!',
                            result.value.message || 'Data barang berhasil dihapus',
                            'success'
                        );
                    }
                });
            });

            // Handle bulk delete with preConfirm
            $('#bulk-delete').on('click', function() {
                if (selectedIds.length === 0) {
                    toastr.warning('Pilih minimal satu barang untuk dihapus');
                    return;
                }

                // Confirm bulk deletion
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: `${selectedIds.length} barang yang dipilih akan dihapus dan tidak dapat dikembalikan!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus semua!',
                    cancelButtonText: 'Batal',
                    showLoaderOnConfirm: true,
                    preConfirm: () => {
                        isProcessing = true;
                        return $.ajax({
                            url: "{{ route('admin.barang.bulkDestroy') }}",
                            type: 'POST',
                            data: {
                                _token: "{{ csrf_token() }}",
                                ids: selectedIds
                            }
                        }).then(response => {
                            return response;
                        }).catch(error => {
                            Swal.showValidationMessage(
                                `Gagal menghapus data: ${error.responseJSON?.message || 'Terjadi kesalahan server'}`
                            );
                        });
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Reload table
                        equipmentTable.ajax.reload();

                        // Reset selected IDs
                        selectedIds = [];
                        updateBulkDeleteButton();

                        // Show success message
                        Swal.fire(
                            'Terhapus!',
                            result.value.message || 'Data barang berhasil dihapus',
                            'success'
                        );
                    }
                });
            });

            // Handle delete all with preConfirm
            $('#delete-all').on('click', function() {
                const totalCount = $(this).data('total');

                if (totalCount <= 0) {
                    toastr.warning('Tidak ada data untuk dihapus');
                    return;
                }

                // First confirmation
                Swal.fire({
                    title: 'PERINGATAN!',
                    html: `<div class="text-danger fw-bold">Tindakan ini sangat berbahaya!</div>
                           <p>Semua data barang (${totalCount} items) akan dihapus permanen dari database!</p>
                           <p>Apakah Anda benar-benar yakin?</p>`,
                    icon: 'error',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus Semua!',
                    cancelButtonText: 'Batal',
                    focusCancel: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Second confirmation with preConfirm
                        Swal.fire({
                            title: 'Konfirmasi Final',
                            html: `<p>Ketik <strong>HAPUS SEMUA</strong> untuk melanjutkan:</p>`,
                            input: 'text',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#3085d6',
                            confirmButtonText: 'Lanjutkan Hapus',
                            cancelButtonText: 'Batal',
                            showLoaderOnConfirm: true,
                            preConfirm: (inputValue) => {
                                if (inputValue !== 'HAPUS SEMUA') {
                                    Swal.showValidationMessage(
                                        'Konfirmasi tidak sesuai!');
                                    return false;
                                }
                                isProcessing = true;
                                return $.ajax({
                                    url: "{{ route('admin.barang.destroyAll') }}",
                                    type: 'DELETE',
                                    data: {
                                        _token: "{{ csrf_token() }}",
                                        _method: 'DELETE'
                                    }
                                }).then(response => {
                                    return response;
                                }).catch(error => {
                                    Swal.showValidationMessage(
                                        `Gagal menghapus data: ${error.responseJSON?.message || 'Terjadi kesalahan server'}`
                                    );
                                });
                            },
                            allowOutsideClick: () => !Swal.isLoading()
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Reload table
                                equipmentTable.ajax.reload();

                                // Show success message
                                Swal.fire(
                                    'Terhapus!',
                                    result.value.message ||
                                    'Semua data barang berhasil dihapus',
                                    'success'
                                );
                            }
                        });
                    }
                });
            });

            // Handle import data button click
            $('#import-data').on('click', function() {
                $('#importModal').modal('show');
            });

            // Handle import form submission
            $('#importForm').on('submit', function(e) {
                e.preventDefault();

                // Disable submit button
                $('#importBtn').html('<i class="fas fa-spinner fa-spin"></i> Mengimport...').prop(
                    'disabled', true);
                isProcessing = true;

                // Create FormData object
                var formData = new FormData(this);

                // Send AJAX request
                $.ajax({
                    url: "{{ route('admin.barang.import') }}",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            // Close modal and reset form
                            $('#importModal').modal('hide');
                            $('#importForm')[0].reset();

                            // Reload table
                            equipmentTable.ajax.reload();

                            // Show success message
                            Swal.fire(
                                'Berhasil!',
                                response.message,
                                'success'
                            );
                        } else {
                            Swal.fire(
                                'Gagal!',
                                response.message ||
                                'Terjadi kesalahan saat mengimport data',
                                'error'
                            );
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            // Validation errors
                            displayErrors(xhr.responseJSON.errors);
                            toastr.error(xhr.responseJSON.message ||
                                'Harap periksa kembali file import');
                        } else {
                            toastr.error('Terjadi kesalahan server saat mengimport data');
                            console.error(xhr);
                        }
                    },
                    complete: function() {
                        // Re-enable submit button
                        $('#importBtn').html('Import Data').prop('disabled', false);
                        isProcessing = false;
                    }
                });
            });

            // Handle download template button click
            $('#import-template').on('click', function() {
                window.location.href = "{{ route('admin.barang.downloadTemplate') }}";
            });

            // Handle export buttons
            $('#export-excel').on('click', function() {
                window.location.href = "{{ route('admin.barang.export', 'xlsx') }}";
            });

            $('#export-csv').on('click', function() {
                window.location.href = "{{ route('admin.barang.export', 'csv') }}";
            });

            $('#export-pdf').on('click', function() {
                window.location.href = "{{ route('admin.barang.export', 'pdf') }}";
            });

            // Load initial data for filters
            loadCategories('#filterCategory');
            loadLocations('#filterLocation');

            // Refresh filter data when clicking refresh button
            $('#refreshTable').click(function() {
                loadCategories('#filterCategory');
                loadLocations('#filterLocation');
                equipmentTable.ajax.reload();
            });
        });
    </script>
@endpush
