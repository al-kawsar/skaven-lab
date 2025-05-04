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
                                <select class="form-select select2-container--default" id="filterCategory">
                                    <option value="">Semua Kategori</option>
                                </select>
                            </div>
                            <div class="col-md-2 mb-2 mb-md-0">
                                <label for="filterLocation" class="form-label">Lokasi</label>
                                <select class="form-select select2-container--default" id="filterLocation">
                                    <option value="">Semua Lokasi</option>
                                </select>
                            </div>
                            <div class="col-md-2 mb-2 mb-md-0">
                                <label for="filterCondition" class="form-label">Kondisi</label>
                                <select class="form-select select2-container--default" id="filterCondition">
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
                                        <li><a class="dropdown-item" href="#" id="export-excel">Excel (.xlsx)</a>
                                        </li>
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
                        <div class="table-responsive d-none d-md-block">
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

        <!-- Setelah table-responsive div -->
        <div class="mobile-view d-md-none">
            <div class="mobile-items" id="mobile-equipment-list">
                <!-- Mobile cards will be rendered here -->
            </div>

            <!-- Mobile loading indicator -->
            <div id="mobile-loader" class="text-center py-4 d-none">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>

            <!-- Mobile empty state -->
            <div id="mobile-empty-state" class="text-center py-4 d-none">
                <div class="mb-3">
                    <i class="fas fa-box-open text-muted" style="font-size: 4rem;"></i>
                </div>
                <h6 class="text-muted">Tidak ada data</h6>
                <p class="small text-muted mb-3">Belum ada barang yang ditambahkan</p>
                <button class="btn btn-primary btn-sm" id="mobile-add-btn">
                    <i class="fas fa-plus me-1"></i> Tambah Barang
                </button>
            </div>

            <!-- Mobile load more button -->
            <div id="mobile-load-more" class="text-center py-3 d-none">
                <button class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-sync me-1"></i> Muat Lebih Banyak
                </button>
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
                                        <input type="text" class="form-control" id="name" name="name"
                                            required>
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
                                <!-- Replace the existing category select -->
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="category_id" class="form-label">Kategori</label>
                                        <select class="form-control select2-with-create" id="category_id"
                                            name="category_id">
                                            <option value="">Pilih atau buat kategori baru</option>
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
                                        <select class="form-control select2-with-create" id="location_id"
                                            name="location_id">
                                            <option value="">Pilih atau buat lokasi baru</option>
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

            function readURL(input) {
                console.log('readURL function called', input);

                if (input.files && input.files[0]) {
                    console.log('File detected:', input.files[0].name, 'Size:', input.files[0].size);

                    var reader = new FileReader();

                    reader.onload = function(e) {
                        console.log('FileReader onload event triggered');
                        $('#preview-image').attr('src', e.target.result);
                        $('#preview-image-container').show();
                        console.log('Preview image updated and container shown');
                    }

                    reader.onerror = function(e) {
                        console.error('FileReader error:', e);
                    }

                    console.log('Starting to read file as Data URL');
                    reader.readAsDataURL(input.files[0]);
                } else {
                    console.log('No file selected or input invalid');
                }
            }

            // Reset file input when modal is hidden
            // $('#equipmentModal').on('hidden.bs.modal', function() {
            //     $('#image').val('');
            //     $('#preview-image').attr('src', '');
            //     $('#preview-image-container').hide();
            // });

            // Add these functions at the beginning of your script
            function loadCategories(selectElement) {
                $.ajax({
                    url: "{{ route('admin.barang.categories') }}",
                    type: 'GET',
                    success: function(response) {
                        if (response.success) {
                            const select = $(selectElement);
                            select.empty();
                            select.append('<option value=""></option>');
                            response.data.forEach(function(category) {
                                select.append(`<option value="${category.id}">${category.name}</option>`);
                            });
                            select.trigger('change');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching categories:', error);
                        toastr.error('Gagal memuat data kategori');
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
                            select.append('<option value=""></option>');
                            response.data.forEach(function(location) {
                                select.append(`<option value="${location.id}">${location.name}</option>`);
                            });
                            select.trigger('change');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching locations:', error);
                        toastr.error('Gagal memuat data lokasi');
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
                // Handle image file selection for preview
                $('#image').on('change', function() {
                    // When a new image is selected, call the readURL function to display preview
                    if (this.files && this.files[0]) {
                        // Pass the input element to the readURL function
                        readURL(this);
                        console.log('Image selected for preview: ' + this.files[0].name);
                    } else {
                        console.log('No image file selected or selection canceled');
                    }
                });

                // Filter events
                $('#applyFilter').on('click', function() {
                    equipmentTable.ajax.reload();
                });

                $('#resetFilter').on('click', function() {
                    $('#filterCategory, #filterLocation, #filterCondition').val(null).trigger('change');
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

                    $('#saveBtn').html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...').prop('disabled',
                        true);
                    isProcessing = true;

                    var formData = new FormData(this);
                    const equipmentId = $('#equipment_id').val();
                    let url = "{{ route('admin.barang.store') }}";
                    let method = 'POST';

                    if (equipmentId) {
                        url = "{{ route('admin.barang.update', '') }}/" + equipmentId;
                        method = 'POST';
                        formData.append('_method', 'PUT');
                    }

                    $.ajax({
                        url: url,
                        type: method,
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            if (response.success) {
                                $('#equipmentModal').modal('hide');
                                resetModalForm();

                                // Reload both DataTable and mobile view
                                if ($(window).width() < 768) {
                                    currentPage = 1;
                                    loadMobileItems();
                                } else {
                                    equipmentTable.ajax.reload();
                                }

                                toastr.success(equipmentId ? 'Barang berhasil diperbarui' :
                                    'Barang berhasil ditambahkan');
                            } else {
                                toastr.error(response.message ||
                                    'Terjadi kesalahan saat menyimpan data');
                            }
                        },
                        error: function(xhr) {
                            if (xhr.status === 422) {
                                displayErrors(xhr.responseJSON.errors);
                                const errorMessage = xhr.responseJSON.message ||
                                    'Harap periksa kembali isian form';
                                toastr.error(errorMessage);
                            } else {
                                const errorMessage = xhr.responseJSON?.message ||
                                    'Terjadi kesalahan server saat menyimpan data';
                                toastr.error(errorMessage);
                            }
                        },
                        complete: function() {
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

                // Inisialisasi Select2 untuk filter dengan auto focus
                $('#filterCategory, #filterLocation, #filterCondition').select2({
                    width: '100%',
                    placeholder: function() {
                        return $(this).data('placeholder');
                    },
                    allowClear: true
                }).on('select2:open', function() {
                    setTimeout(function() {
                        $('.select2-search__field').focus();
                    }, 0);
                });

                // Custom style untuk container Select2 agar sesuai dengan desain
                $('.select2-container--default .select2-selection--single').css({
                    'height': 'calc(1.5em + 0.75rem + 2px)',
                    'padding': '0.375rem 0.75rem',
                    'border-radius': '0.25rem',
                    'border-color': '#ced4da'
                });

                // Penyesuaian style untuk dropdown
                $('.select2-container--default .select2-selection--single .select2-selection__arrow').css({
                    'height': '100%'
                });

                // Mobile filter handling
                $('#mobile-filter-btn').on('click', function() {
                    $('.filter-section').addClass('show');
                    $('body').addClass('filter-open');
                });

                $('#close-filter').on('click', function() {
                    $('.filter-section').removeClass('show');
                    $('body').removeClass('filter-open');
                });

                // Close filter when clicking outside
                $(document).on('click', function(e) {
                    if (!$(e.target).closest('.filter-section').length &&
                        !$(e.target).closest('#mobile-filter-btn').length) {
                        $('.filter-section').removeClass('show');
                        $('body').removeClass('filter-open');
                    }
                });

                // Mobile view handling
                let currentPage = 1;
                let isLoading = false;
                let hasMoreData = true;

                // Helper functions for mobile view
                function showMobileLoader() {
                    $('#mobile-loader').removeClass('d-none');
                }

                function hideMobileLoader() {
                    $('#mobile-loader').addClass('d-none');
                }

                function showMobileEmptyState() {
                    $('#mobile-empty-state').removeClass('d-none');
                    $('#mobile-load-more').addClass('d-none');
                }

                function hideMobileEmptyState() {
                    $('#mobile-empty-state').addClass('d-none');
                }

                function updateFilterBadge(count) {
                    const badge = $('#mobile-filter-btn .mobile-filter-badge');
                    if (count > 0) {
                        if (badge.length === 0) {
                            $('#mobile-filter-btn').append(`<span class="mobile-filter-badge">${count}</span>`);
                        } else {
                            badge.text(count);
                        }
                    } else {
                        badge.remove();
                    }
                }

                function getConditionClass(condition) {
                    switch (condition) {
                        case 'baik':
                            return 'bg-success text-white';
                        case 'rusak ringan':
                            return 'bg-warning text-dark';
                        case 'rusak berat':
                            return 'bg-danger text-white';
                        default:
                            return 'bg-secondary text-white';
                    }
                }

                function loadMobileItems(page = 1, isLoadMore = false) {
                    if (isLoading || (!isLoadMore && !hasMoreData)) return;

                    isLoading = true;
                    if (!isLoadMore) {
                        $('#mobile-equipment-list').html('');
                        showMobileLoader();
                    }

                    // Format data sesuai dengan yang diharapkan repository
                    const filters = {
                        draw: 1,
                        columns: [{
                                data: 'id'
                            },
                            {
                                data: 'id'
                            },
                            {
                                data: 'name'
                            },
                            {
                                data: 'code'
                            },
                            {
                                data: 'stock'
                            },
                            {
                                data: 'category_id'
                            },
                            {
                                data: 'location_id'
                            },
                            {
                                data: 'condition'
                            }
                        ],
                        start: (page - 1) * 10,
                        length: 10,
                        search: {
                            value: $('#filterName').val() || '',
                            regex: false
                        },
                        // Filter tambahan
                        name: $('#filterName').val(),
                        category_id: $('#filterCategory').val(),
                        condition: $('#filterCondition').val()
                    };

                    $.ajax({
                        url: "{{ route('admin.barang.getData') }}",
                        type: 'GET',
                        data: filters,
                        success: function(response) {
                            console.log('Server response:', response); // Untuk debugging

                            if (response.data && Array.isArray(response.data)) {
                                if (response.data.length === 0 && page === 1) {
                                    showMobileEmptyState();
                                    return;
                                }

                                hideMobileEmptyState();
                                renderMobileItems(response.data, isLoadMore);

                                hasMoreData = response.data.length === filters.length;
                                $('#mobile-load-more').toggleClass('d-none', !hasMoreData);

                                // Update statistik jika tersedia
                                if (response.meta) {
                                    $('#totalData').text(response.meta.total || 0);
                                    $('#totalGoodCondition').text(response.meta.goodCondition || 0);
                                    $('#totalMinorDamage').text(response.meta.minorDamage || 0);
                                    $('#totalMajorDamage').text(response.meta.majorDamage || 0);
                                }
                            } else {
                                console.error('Invalid response format:', response);
                                toastr.error('Format data tidak valid');
                                showMobileEmptyState();
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Ajax error:', {
                                xhr,
                                status,
                                error
                            });
                            toastr.error('Gagal memuat data');
                            showMobileEmptyState();
                        },
                        complete: function() {
                            isLoading = false;
                            hideMobileLoader();
                        }
                    });
                }

                function renderMobileItems(items, append = false) {
                    if (!Array.isArray(items)) {
                        console.error('Invalid items data:', items);
                        return;
                    }

                    const container = $('#mobile-equipment-list');
                    let html = '';

                    items.forEach(item => {
                        // Tambahkan pengecekan data
                        if (!item || typeof item !== 'object') {
                            console.error('Invalid item data:', item);
                            return;
                        }

                        try {
                            html += `
                            <div class="mobile-item-card card shadow-sm mb-3 border-0" data-id="${item.id || ''}">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div class="d-flex gap-3">
                                            <div class="mobile-item-image rounded-3 bg-light d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                                ${item.image && item.image.url 
                                                    ? `<img src="${item.image.url}" alt="${item.name || ''}" class="img-fluid rounded-3" style="object-fit: cover; width: 100%; height: 100%;">` 
                                                    : `<i class="fas fa-box fa-2x text-secondary"></i>`}
                                            </div>
                                            <div>
                                                <h5 class="card-title mb-1 fw-bold">${item.name || 'Unnamed'}</h5>
                                                <div class="text-muted small">
                                                    <i class="fas fa-barcode me-1"></i>
                                                    ${item.code || 'No Code'}
                                                </div>
                                            </div>
                                        </div>
                                        <span class="badge ${getConditionClass(item.condition?.value || '')} rounded-pill px-3 py-2">
                                            ${item.condition?.label || 'Unknown'}
                                        </span>
                                    </div>
                                    
                                    <div class="row g-3 mb-3">
                                        <div class="col-6">
                                            <div class="p-3 bg-light rounded-3">
                                                <div class="text-muted small mb-1">
                                                    <i class="fas fa-boxes me-1"></i> Stok
                                                </div>
                                                <div class="fw-bold ${(item.stock?.value > 0) ? 'text-success' : 'text-danger'}">
                                                    ${item.stock?.formatted || '0'}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="p-3 bg-light rounded-3">
                                                <div class="text-muted small mb-1">
                                                    <i class="fas fa-tag me-1"></i> Kategori
                                                </div>
                                                <div class="fw-bold text-truncate">
                                                    ${item.category?.name || 'Tidak ada'}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex gap-2 justify-content-end">
                                        <a href="{{ route('admin.barang.detail', '') }}/${item.id}" class="btn btn-outline-primary btn-sm" 
                                                data-bs-toggle="tooltip" title="Lihat Detail">
                                            <i class="far fa-eye"></i>
                                        </a>
                                        <button class="btn btn-outline-warning btn-sm mobile-edit-btn" data-id="${item.id || ''}"
                                                data-bs-toggle="tooltip" title="Edit">
                                            <i class="far fa-edit"></i>
                                        </button>
                                        <button class="btn btn-outline-danger btn-sm mobile-delete-btn" data-id="${item.id || ''}"
                                                data-bs-toggle="tooltip" title="Hapus">
                                            <i class="far fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        `;
                        } catch (error) {
                            console.error('Error rendering item:', error, item);
                        }
                    });

                    if (append) {
                        container.append(html);
                    } else {
                        container.html(html);
                    }
                }

                // Tambahkan ini di dalam $(document).ready
                $(document).ready(function() {
                    // Fungsi untuk menangani pergantian tampilan
                    function handleViewportChange() {
                        if ($(window).width() < 768) {
                            // Sembunyikan DataTable dan tampilkan mobile view
                            $('.table-responsive').hide();
                            $('.mobile-view').show();
                            // Load mobile items jika belum ada
                            if ($('#mobile-equipment-list').children().length === 0) {
                                loadMobileItems();
                            }
                        } else {
                            // Tampilkan DataTable dan sembunyikan mobile view
                            $('.table-responsive').show();
                            $('.mobile-view').hide();
                        }
                    }

                    // Panggil saat halaman dimuat
                    handleViewportChange();

                    // Panggil saat ukuran window berubah
                    $(window).on('resize', handleViewportChange);

                    // Event handlers untuk mobile view
                    $('#mobile-load-more button').on('click', function() {
                        currentPage++;
                        loadMobileItems(currentPage, true);
                    });

                    // Refresh data setelah filter
                    $('#applyFilter').on('click', function() {
                        currentPage = 1;
                        hasMoreData = true;
                        loadMobileItems();
                        $('.filter-section').removeClass('show');
                    });

                    // Mobile action handlers
                    $(document).on('click', '.mobile-edit-btn', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        const id = $(this).data('id');
                        // Panggil fungsi edit langsung daripada trigger click
                        resetModalForm();
                        $('#equipmentModalLabel').text('Edit Barang');
                        $('#equipment_id').val(id);

                        // Load data barang
                        $.ajax({
                            url: "{{ route('admin.barang.show', ':id') }}".replace(':id', id),
                            type: 'GET',
                            success: function(response) {
                                if (response.success) {
                                    const equipment = response.data;
                                    $('#name').val(equipment.name);
                                    $('#stock').val(equipment.stock.value);
                                    $('#category_id').val(equipment.category?.id);
                                    $('#location_id').val(equipment.location?.id);
                                    $('#condition').val(equipment.condition.value);
                                    $('#description').val(equipment.description);

                                    if (equipment.image && equipment.image.url) {
                                        $('#preview-image').attr('src', equipment.image
                                            .url);
                                        $('#preview-image-container').show();
                                    }

                                    $('#equipmentModal').modal('show');
                                }
                            },
                            error: function(xhr) {
                                toastr.error('Gagal memuat data barang');
                            }
                        });
                    });

                    $(document).on('click', '.mobile-delete-btn', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        const id = $(this).data('id');

                        // Implementasi delete langsung
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
                                return $.ajax({
                                    url: "{{ route('admin.barang.destroy', ':id') }}"
                                        .replace(':id', id),
                                    type: 'DELETE',
                                    data: {
                                        _token: "{{ csrf_token() }}"
                                    }
                                });
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Reload data mobile
                                currentPage = 1;
                                loadMobileItems();

                                // Show success message
                                Swal.fire(
                                    'Terhapus!',
                                    'Data barang berhasil dihapus',
                                    'success'
                                );
                            }
                        });
                    });

                    // Tambah barang handler
                    $('#mobile-add-btn').on('click', function(e) {
                        e.preventDefault();
                        resetModalForm();
                        $('#equipmentModal').modal('show');
                    });
                });
            });

            // Initialize Select2 with tags for category and location
            function initializeSelect2WithCreate() {
                $('.select2-with-create').each(function() {
                    const $select = $(this);
                    const isCategory = $select.attr('id') === 'category_id';
                    const placeholder = isCategory ? 'Pilih atau buat kategori baru' : 'Pilih atau buat lokasi baru';
                    const createNewText = isCategory ? 'Buat kategori baru' : 'Buat lokasi baru';
                    const noResultsText = isCategory ? 'Ketik untuk membuat kategori baru' :
                        'Ketik untuk membuat lokasi baru';

                    $select.select2({
                        theme: 'bootstrap-5', // Ubah ke bootstrap-5
                        width: '100%',
                        placeholder: placeholder,
                        allowClear: true,
                        tags: true,
                        createTag: function(params) {
                            const term = $.trim(params.term);
                            if (term === '') return null;

                            return {
                                id: `new:${term}`,
                                text: term,
                                newTag: true
                            };
                        },
                        templateResult: function(data) {
                            if (!data.id) return data.text;

                            if (data.newTag) {
                                return $(`<span>
                                    <i class="fas fa-plus-circle me-1"></i> 
                                    ${createNewText}: "${data.text}"
                                </span>`);
                            }

                            return data.text;
                        },
                        templateSelection: function(data) {
                            return data.newTag ? data.text : data.text;
                        },
                        language: {
                            noResults: function() {
                                return noResultsText;
                            }
                        }
                    });
                });
            }

            // Function to load categories
            function loadCategories(selectElement) {
                return $.ajax({
                    url: "{{ route('admin.barang.categories') }}",
                    type: 'GET',
                    success: function(response) {
                        if (response.success) {
                            const select = $(selectElement);
                            select.empty();
                            select.append('<option value=""></option>');
                            response.data.forEach(function(category) {
                                select.append(`<option value="${category.id}">${category.name}</option>`);
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching categories:', error);
                        toastr.error('Gagal memuat data kategori');
                    }
                });
            }

            // Function to load locations
            function loadLocations(selectElement) {
                return $.ajax({
                    url: "{{ route('admin.barang.locations') }}",
                    type: 'GET',
                    success: function(response) {
                        if (response.success) {
                            const select = $(selectElement);
                            select.empty();
                            select.append('<option value=""></option>');
                            response.data.forEach(function(location) {
                                select.append(`<option value="${location.id}">${location.name}</option>`);
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching locations:', error);
                        toastr.error('Gagal memuat data lokasi');
                    }
                });
            }

            // Initialize when document is ready
            $(document).ready(function() {
                // Load initial data and initialize Select2
                Promise.all([
                    loadCategories('#category_id'),
                    loadLocations('#location_id')
                ]).then(() => {
                    initializeSelect2WithCreate();
                });

                // Reinitialize Select2 when the modal is shown
                $('#equipmentModal').on('shown.bs.modal', function() {
                    initializeSelect2WithCreate();
                });

                // Destroy Select2 when modal is hidden to prevent duplicates
                $('#equipmentModal').on('hidden.bs.modal', function() {
                    $('.select2-with-create').select2('destroy');
                });
            });
        </script>
        @endpush

        @push('styles')
        <style>
            /* Custom style untuk Select2 */
            .select2-container--default .select2-selection--single {
                background-color: #fff;
                border: 1px solid #ced4da;
                border-radius: 0.25rem;
                height: calc(1.5em + 0.75rem + 2px);
                padding: 0.375rem 0.75rem;
            }

            .select2-container--default .select2-selection--single .select2-selection__rendered {
                line-height: 1.5;
                padding-left: 0;
                color: #495057;
            }

            .select2-container--default .select2-selection--single .select2-selection__placeholder {
                color: #6c757d;
            }

            .select2-container--default .select2-selection--single .select2-selection__arrow {
                height: 100%;
                right: 8px;
            }

            .select2-container--default .select2-results__option--highlighted[aria-selected] {
                background-color: #007bff;
            }

            .select2-dropdown {
                border-color: #ced4da;
            }

            /* Responsive adjustments */
            @media (max-width: 768px) {
                .select2-container {
                    width: 100% !important;
                }
            }

            /* Mobile Optimizations */
            @media (max-width: 768px) {
                .mobile-view {
                    padding: 1rem;
                    margin-bottom: 60px;
                    /* Ruang untuk floating filter button */
                }

                #mobile-equipment-list {
                    margin-top: 1rem;
                }

                .mobile-item-card {
                    margin-bottom: 1rem;
                    background: #fff;
                    border: 1px solid rgba(0, 0, 0, 0.1);
                }

                /* Pastikan tombol filter tetap terlihat */
                #mobile-filter-btn {
                    position: fixed !important;
                    z-index: 1050 !important;
                }

                /* Sembunyikan elemen desktop yang tidak diperlukan */
                .dataTables_wrapper {
                    display: none;
                }
            }

            /* Mobile View Styles */
            .mobile-item-card {
                background: #fff;
                border-radius: 10px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
                margin-bottom: 1rem;
                transition: transform 0.2s;
            }

            .mobile-item-card:active {
                transform: scale(0.98);
            }

            .mobile-item-header {
                padding: 0.75rem;
                border-bottom: 1px solid #f0f0f0;
            }

            .mobile-item-body {
                padding: 0.75rem;
            }

            .mobile-item-footer {
                padding: 0.75rem;
                border-top: 1px solid #f0f0f0;
                background: #fafafa;
                border-radius: 0 0 10px 10px;
            }

            .mobile-item-image {
                width: 50px;
                height: 50px;
                border-radius: 8px;
                object-fit: cover;
                background: #f8f9fa;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .mobile-item-title {
                font-size: 1rem;
                font-weight: 600;
                margin-bottom: 0.25rem;
                color: #2c2c2c;
            }

            .mobile-item-code {
                font-size: 0.8rem;
                color: #6c757d;
            }

            .mobile-item-stock {
                font-size: 0.9rem;
                font-weight: 500;
            }

            .mobile-action-buttons {
                display: flex;
                gap: 0.5rem;
            }

            .mobile-action-buttons .btn {
                flex: 1;
                padding: 0.4rem;
                font-size: 0.875rem;
            }

            /* Badge styles */
            .status-badge {
                padding: 0.25rem 0.5rem;
                border-radius: 50px;
                font-size: 0.75rem;
                font-weight: 500;
            }

            /* Skeleton loading animation */
            .skeleton {
                background: linear-gradient(90deg, #f0f0f0 25%, #f8f8f8 50%, #f0f0f0 75%);
                background-size: 200% 100%;
                animation: loading 1.5s infinite;
            }

            @keyframes loading {
                0% {
                    background-position: 200% 0;
                }

                100% {
                    background-position: -200% 0;
                }
            }

            /* Mobile filter adjustments */
            .mobile-filter-badge {
                position: absolute;
                top: -5px;
                right: -5px;
                background: #dc3545;
                color: white;
                border-radius: 50%;
                width: 20px;
                height: 20px;
                font-size: 0.75rem;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            #mobile-empty-state {
                padding: 2rem 1rem;
            }

            #mobile-empty-state .fas.fa-box-open {
                opacity: 0.5;
            }

            #mobile-empty-state h6 {
                margin-top: 1rem;
                font-weight: 600;
            }

            #mobile-empty-state p {
                color: #6c757d;
            }

            #mobile-empty-state .btn {
                padding: 0.5rem 1rem;
                font-weight: 500;
            }

            /* Select2 Bootstrap 5 Theme */
            .select2-container--bootstrap-5 .select2-selection {
                min-height: calc(1.5em + 0.75rem + 2px);
                padding: 0.375rem 0.75rem;
                font-size: 1rem;
                font-weight: 400;
                line-height: 1.5;
                color: #212529;
                background-color: #fff;
                border: 1px solid #ced4da;
                border-radius: 0.375rem;
                transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
            }

            .select2-container--bootstrap-5 .select2-selection--single {
                padding-right: 2.25rem;
            }

            .select2-container--bootstrap-5.select2-container--focus .select2-selection,
            .select2-container--bootstrap-5.select2-container--open .select2-selection {
                border-color: #86b7fe;
                box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
            }

            .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
                padding: 0;
                color: #212529;
            }

            .select2-container--bootstrap-5 .select2-selection--single .select2-selection__arrow {
                position: absolute;
                top: 50%;
                right: 0.75rem;
                width: 0.75rem;
                height: 0.75rem;
                transform: translateY(-50%);
            }

            .select2-container--bootstrap-5 .select2-selection--single .select2-selection__arrow b {
                border-color: #212529 transparent transparent;
                border-style: solid;
                border-width: 0.3rem 0.3rem 0;
                height: 0;
                left: 50%;
                margin-left: -0.3rem;
                margin-top: -0.15rem;
                position: absolute;
                top: 50%;
                width: 0;
            }

            .select2-container--bootstrap-5.select2-container--open .select2-selection--single .select2-selection__arrow b {
                border-color: transparent transparent #212529;
                border-width: 0 0.3rem 0.3rem;
            }

            .select2-container--bootstrap-5 .select2-dropdown {
                border-color: #86b7fe;
                border-radius: 0.375rem;
                box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
                z-index: 9999;
            }

            .select2-container--bootstrap-5 .select2-results__option {
                padding: 0.375rem 0.75rem;
                color: #212529;
            }

            .select2-container--bootstrap-5 .select2-results__option--highlighted[aria-selected] {
                background-color: #0d6efd;
                color: #fff;
            }

            .select2-container--bootstrap-5 .select2-results__option[aria-selected=true] {
                background-color: #e9ecef;
                color: #212529;
            }

            .select2-container--bootstrap-5 .select2-search--dropdown .select2-search__field {
                padding: 0.375rem 0.75rem;
                border: 1px solid #ced4da;
                border-radius: 0.375rem;
            }

            .select2-container--bootstrap-5 .select2-search--dropdown .select2-search__field:focus {
                border-color: #86b7fe;
                box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
                outline: 0;
            }

            /* Mobile Responsive */
            @media (max-width: 576px) {
                .select2-container--bootstrap-5 {
                    width: 100% !important;
                }

                .select2-container--bootstrap-5 .select2-selection {
                    height: calc(1.5em + 1rem + 2px);
                    padding: 0.5rem 1rem;
                    font-size: 1.1rem;
                }
            }
        </style>
    @endpush
