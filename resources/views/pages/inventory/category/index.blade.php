@extends('layouts.app-layout')
@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-sub-header">
                    <h3 class="page-title">Kategori Barang</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.barang.index') }}">Manajemen Barang</a></li>
                        <li class="breadcrumb-item active">Kategori</li>
                    </ul>
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
                                <h3 class="page-title">Daftar Kategori</h3>
                            </div>
                            <div class="col-auto text-end float-end ms-auto download-grp">
                                <button class="btn btn-outline-primary me-2" id="refreshTable" title="Refresh Data">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                                <button class="btn btn-primary fw-bold" id="add-category">
                                    <i class="fas fa-plus"></i> Tambah Kategori
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table border-0 star-student  table-hover table-center mb-0 datatable"
                            id="categoryTable">
                            <thead class="student-thread">
                                <tr>
                                    <th>#</th>
                                    <th>Nama Kategori</th>
                                    <th>Kode</th>
                                    <th>Warna</th>
                                    <th>Deskripsi</th>
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

    <!-- Modal Tambah/Edit Kategori -->
    <div class="modal fade" id="categoryModal" tabindex="-1" aria-labelledby="categoryModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="categoryModalLabel">Tambah Kategori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="categoryForm">
                    <div class="modal-body">
                        <input type="hidden" id="category_id" name="category_id">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Kategori <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" required>
                            <div class="invalid-feedback" id="name-error"></div>
                        </div>
                        <div class="mb-3">
                            <label for="code" class="form-label">Kode Kategori</label>
                            <input type="text" class="form-control" id="code" name="code">
                            <div class="invalid-feedback" id="code-error"></div>
                        </div>
                        <div class="mb-3">
                            <label for="color" class="form-label">Warna (HEX/Nama warna)</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="color" name="color"
                                    placeholder="Contoh: #3498db atau blue">
                                <span class="input-group-text p-1">
                                    <input type="color" class="form-control form-control-color p-0 border-0"
                                        id="colorPicker" value="#3498db" title="Pilih warna">
                                </span>
                            </div>
                            <div class="invalid-feedback" id="color-error"></div>
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

    <!-- Modal Detail Kategori -->
    <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailModalLabel">Detail Kategori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-12 text-center">
                            <div id="category-color-badge" class="badge mb-3"
                                style="font-size: 1rem; padding: 10px 20px;">
                                Kategori
                            </div>
                            <h4 id="detail-name" class="fw-bold"></h4>
                            <p id="detail-code" class="text-muted"></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card border">
                                <div class="card-body">
                                    <h5 class="card-title">Deskripsi</h5>
                                    <p id="detail-description" class="card-text">-</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
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
                    <button type="button" class="btn btn-primary" id="editFromDetail">Edit Kategori</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        var categoryTable;
        var isProcessing = false;
        var selectedCategory = null;

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
            $('#categoryForm')[0].reset();
            $('#category_id').val('');
            resetFormErrors();
            $('#categoryModalLabel').text('Tambah Kategori');
            $('#colorPicker').val('#3498db');
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

            // Sync color picker with text input
            $('#colorPicker').on('input', function() {
                $('#color').val($(this).val());
            });

            $('#color').on('input', function() {
                // If it's a valid hex color
                if (/^#[0-9A-F]{6}$/i.test($(this).val())) {
                    $('#colorPicker').val($(this).val());
                }
            });

            // Initialize DataTable
            categoryTable = $('#categoryTable').DataTable({
                ajax: {
                    url: "{{ route('admin.kategori.getData') }}",
                    type: 'GET',
                    dataType: 'json',
                    dataSrc: 'data',
                },
                searching: true,
                serverSide: false,
                processing: true,
                columns: [{
                        data: 'number'
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
                        data: 'code',
                        render: function(data) {
                            return data || '<span class="text-muted">-</span>';
                        }
                    },
                    {
                        data: 'color',
                        render: function(data, type, row) {
                            if (!data) return '<span class="text-muted">-</span>';
                            return `<span class="badge rounded-pill py-2 px-3" style="background-color: ${data}; color: white;">${row.name}</span>`;
                        }
                    },
                    {
                        data: 'description',
                        render: function(data) {
                            return data || '<span class="text-muted">-</span>';
                        }
                    },
                    {
                        data: 'equipment_count',
                        render: function(data) {
                            return `<span class="badge bg-info-light rounded-pill py-2 px-3">${data} barang</span>`;
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `
                            <div class="d-flex p-0 m-0 align-items-center justify-content-center">
                                <button class="btn bg-success-light btn-sm me-1 btn-detail" data-id="${row.id}" title="Lihat Detail">
                                    <i class="far fa-eye"></i>
                                </button>
                                <button class="btn bg-success-light btn-sm me-1 btn-edit" data-id="${row.id}" title="Edit Kategori">
                                    <i class="feather-edit"></i>
                                </button>
                                <button class="btn btn-sm bg-success-light btn-delete" data-id="${row.id}" ${isProcessing ? 'disabled' : ''} title="Hapus Kategori">
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

            // Refresh button click handler
            $('#refreshTable').on('click', function() {
                const $icon = $(this).find('i');
                $icon.addClass('refreshing');

                // Reload the table
                categoryTable.ajax.reload(function() {
                    // After reload is complete, remove the spinning animation
                    setTimeout(function() {
                        $icon.removeClass('refreshing');
                    }, 500);

                    // Show success toast
                    toastr.success('Data berhasil diperbarui', 'Sukses');
                });
            });

            // Add Category modal
            $('#add-category').on('click', function() {
                resetModalForm();
                $('#categoryModal').modal('show');
            });

            // Edit button click handler
            $('#categoryTable').on('click', '.btn-edit', function() {
                const categoryId = $(this).data('id');
                loadCategoryData(categoryId);
            });

            // Edit from detail modal
            $('#editFromDetail').on('click', function() {
                if (selectedCategory) {
                    $('#detailModal').modal('hide');
                    loadCategoryData(selectedCategory);
                }
            });

            // Detail button click handler
            $('#categoryTable').on('click', '.btn-detail', function() {
                const categoryId = $(this).data('id');
                selectedCategory = categoryId;
                loadCategoryDetail(categoryId);
            });

            // Category form submit handler
            $('#categoryForm').on('submit', function(e) {
                e.preventDefault();

                resetFormErrors();

                const formData = new FormData(this);
                const categoryId = $('#category_id').val();
                let url = "{{ route('admin.kategori.store') }}";
                let method = 'POST';

                if (categoryId) {
                    url = "{{ route('admin.kategori.update', ':id') }}".replace(':id', categoryId);
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
                        $('#categoryModal').modal('hide');
                        categoryTable.ajax.reload();

                        if (categoryId) {
                            toastr.success('Kategori berhasil diperbarui', 'Sukses');
                        } else {
                            toastr.success('Kategori berhasil ditambahkan', 'Sukses');
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

            // Delete category button handler
            $('#categoryTable').on('click', '.btn-delete', function(e) {
                e.preventDefault();
                if (isProcessing) {
                    Swal.fire({
                        title: 'Proses Sedang Berjalan',
                        text: 'Harap tunggu hingga proses selesai',
                        icon: 'warning'
                    });
                    return;
                }

                const categoryId = $(this).data('id');

                Swal.fire({
                    title: "Anda yakin?",
                    text: "Data kategori akan dihapus permanen!",
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
                            url: "{{ route('admin.kategori.destroy', ':id') }}".replace(
                                ':id', categoryId),
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
                        categoryTable.ajax.reload();
                        Swal.fire({
                            title: 'Berhasil!',
                            text: result.value.message || 'Kategori berhasil dihapus',
                            icon: 'success'
                        });
                    }
                });
            });
        });

        // Load category data for editing
        function loadCategoryData(id) {
            $.ajax({
                url: "{{ route('admin.kategori.show', ':id') }}".replace(':id', id),
                type: 'GET',
                beforeSend: function() {
                    isProcessing = true;
                },
                success: function(response) {
                    resetModalForm();

                    const category = response.data;
                    $('#categoryModalLabel').text('Edit Kategori');
                    $('#category_id').val(category.id);
                    $('#name').val(category.name);
                    $('#code').val(category.code);
                    $('#description').val(category.description);

                    if (category.color) {
                        $('#color').val(category.color);
                        $('#colorPicker').val(category.color);
                    }

                    $('#categoryModal').modal('show');
                },
                error: function(xhr) {
                    toastr.error('Gagal memuat data kategori', 'Error');
                    console.error(xhr);
                },
                complete: function() {
                    isProcessing = false;
                }
            });
        }

        // Load category detail
        function loadCategoryDetail(id) {
            $.ajax({
                url: "{{ route('admin.kategori.show', ':id') }}".replace(':id', id),
                type: 'GET',
                beforeSend: function() {
                    isProcessing = true;
                },
                success: function(response) {
                    const category = response.data;

                    // Set data to detail modal
                    $('#detail-name').text(category.name);
                    $('#detail-code').text(category.code ? `Kode: ${category.code}` : '');
                    $('#detail-description').text(category.description || 'Tidak ada deskripsi.');
                    $('#detail-equipment-count').text(category.equipment_count || 0);

                    // Set color badge
                    if (category.color) {
                        $('#category-color-badge').text(category.name);
                        $('#category-color-badge').css({
                            'background-color': category.color,
                            'color': '#fff'
                        });
                    } else {
                        $('#category-color-badge').text(category.name);
                        $('#category-color-badge').css({
                            'background-color': '#6c757d',
                            'color': '#fff'
                        });
                    }

                    // Show modal
                    $('#detailModal').modal('show');
                },
                error: function(xhr) {
                    toastr.error('Gagal memuat detail kategori', 'Error');
                    console.error(xhr);
                },
                complete: function() {
                    isProcessing = false;
                }
            });
        }
    </script>
@endpush
