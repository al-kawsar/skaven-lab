@extends('layouts.app-layout')
@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-sub-header">
                    <h3 class="page-title">Siswa</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">All Siswa</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="card card-table comman-shadow ">
                <div class="card-body">
                    <div class="page-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="page-title">Siswa</h3>
                            </div>
                            <div class="col-auto text-end float-end ms-auto download-grp">
                                <a href="{{ route('admin.student.statistics.view') }}" class="btn btn-info text-white me-2">
                                    <i class="fas fa-chart-bar me-1"></i> Statistik
                                </a>
                                <div class="btn-group">
                                    <button class="btn btn-primary dropdown-toggle" type="button" id="exportDropdown"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-download me-1"></i> Export
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="exportDropdown">
                                        <li><a class="dropdown-item" href="#" id="export-excel">Excel</a></li>
                                        <li><a class="dropdown-item" href="#" id="export-pdf">PDF</a></li>
                                        <li><a class="dropdown-item" href="#" id="export-print">Print</a></li>
                                    </ul>
                                </div>
                                <div class="btn-group me-2">
                                    <button class="btn btn-success dropdown-toggle" type="button" id="importDropdown"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-upload me-1"></i> Import
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="importDropdown">
                                        <li><a class="dropdown-item" href="#" id="show-import-modal">Import Data</a>
                                        </li>
                                        <li><a class="dropdown-item"
                                                href="{{ route('admin.student.import.template') }}">Download Template</a>
                                        </li>
                                    </ul>
                                </div>
                                <button class="btn btn-secondary" id="show-filter-modal"><i class="fas fa-filter me-1"></i>
                                    Filter Lanjutan</button>
                                <a href="{{ route('admin.student.create') }}" class="btn btn-primary"><i
                                        class="fas fa-plus"></i></a>
                            </div>
                        </div>
                    </div>

                    <!-- Panel Filter Utama -->
                    <div class="filter-panel mb-3">
                        <div class="row">
                            <div class="col-md-3 mb-2">
                                <label class="form-label small">Jenis Kelamin</label>
                                <select class="form-control" id="filter-gender">
                                    <option value=" ">Semua Jenis Kelamin</option>
                                    <option value="l">Laki-laki</option>
                                    <option value="p">Perempuan</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-2">
                                <label class="form-label small">Agama</label>
                                <select class="form-control" id="filter-agama">
                                    <option value="">Semua Agama</option>
                                    <option value="Islam">Islam</option>
                                    <option value="Kristen">Kristen</option>
                                    <option value="Katolik">Katolik</option>
                                    <option value="Hindu">Hindu</option>
                                    <option value="Buddha">Buddha</option>
                                    <option value="Konghucu">Konghucu</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-2">
                                <label class="form-label small">Usia</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="filter-usia-min" placeholder="Min">
                                    <span class="input-group-text">-</span>
                                    <input type="number" class="form-control" id="filter-usia-max" placeholder="Max">
                                </div>
                            </div>
                            <div class="col-md-3 mb-2">
                                <label class="form-label small">Huruf Awal</label>
                                <div class="d-flex">
                                    <select class="form-control" id="filter-initial">
                                        <option value="">Semua</option>
                                        @foreach (range('A', 'Z') as $letter)
                                            <option value="{{ $letter }}">{{ $letter }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-8 mb-2">
                                <label class="form-label small">Pencarian</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="search-input"
                                        placeholder="Cari nama, nis, atau nisn...">
                                    <button class="btn btn-primary" id="btn-search"><i
                                            class="fas fa-search"></i></button>
                                    <button class="btn btn-danger ms-1" id="btn-reset-filter"><i
                                            class="fas fa-times"></i>
                                        Reset</button>
                                </div>
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="form-label small">Urutkan Berdasarkan</label>
                                <select class="form-control" id="filter-sort">
                                    <option value="name|asc">Nama (A-Z)</option>
                                    <option value="name|desc">Nama (Z-A)</option>
                                    <option value="usia|asc">Usia (Termuda)</option>
                                    <option value="usia|desc">Usia (Tertua)</option>
                                    <option value="created_at|desc">Terbaru Ditambahkan</option>
                                    <option value="created_at|asc">Terlama Ditambahkan</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Status & Info Panel -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div id="filter-status" class="text-muted small"></div>
                        </div>
                        <div class="col-md-6 text-end">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-sm btn-outline-secondary" id="toggle-columns">
                                    <i class="fas fa-columns me-1"></i> Atur Kolom
                                </button>
                            </div>
                        </div>
                    </div>

                    <x-data-table name="siswaTable" :header="[
                        '#',
                        'NAMA LENGKAP',
                        'NIS',
                        'NISN',
                        'TANGGAL LAHIR',
                        'USIA',
                        'JENIS KELAMIN',
                        'AGAMA',
                        'AKSI',
                    ]" />
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Filter Lanjutan -->
    <div class="modal fade" id="filterAdvancedModal" tabindex="-1" aria-labelledby="filterAdvancedModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="filterAdvancedModalLabel">Filter Lanjutan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Lahir</label>
                            <div class="input-group">
                                <input type="date" class="form-control" id="filter-tgl-lahir-start">
                                <span class="input-group-text">hingga</span>
                                <input type="date" class="form-control" id="filter-tgl-lahir-end">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kelahiran Di Bulan</label>
                            <select class="form-control" id="filter-bulan-lahir">
                                <option value="">Semua Bulan</option>
                                <option value="01">Januari</option>
                                <option value="02">Februari</option>
                                <option value="03">Maret</option>
                                <option value="04">April</option>
                                <option value="05">Mei</option>
                                <option value="06">Juni</option>
                                <option value="07">Juli</option>
                                <option value="08">Agustus</option>
                                <option value="09">September</option>
                                <option value="10">Oktober</option>
                                <option value="11">November</option>
                                <option value="12">Desember</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status Data</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="filter-has-foto" value="1">
                                <label class="form-check-label" for="filter-has-foto">Memiliki Foto</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="filter-complete-data"
                                    value="1">
                                <label class="form-check-label" for="filter-complete-data">Data Lengkap</label>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Alamat Mengandung Kata</label>
                            <input type="text" class="form-control" id="filter-alamat">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="apply-advanced-filter">Terapkan Filter</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Pengaturan Kolom -->
    <div class="modal fade" id="columnToggleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Atur Kolom</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="column-toggle-list">
                        <div class="form-check">
                            <input class="form-check-input toggle-column" type="checkbox" id="toggle-col-1"
                                data-column="1" checked>
                            <label class="form-check-label" for="toggle-col-1">Nama Lengkap</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input toggle-column" type="checkbox" id="toggle-col-2"
                                data-column="2" checked>
                            <label class="form-check-label" for="toggle-col-2">NIS</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input toggle-column" type="checkbox" id="toggle-col-3"
                                data-column="3" checked>
                            <label class="form-check-label" for="toggle-col-3">NISN</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input toggle-column" type="checkbox" id="toggle-col-4"
                                data-column="4" checked>
                            <label class="form-check-label" for="toggle-col-4">Tanggal Lahir</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input toggle-column" type="checkbox" id="toggle-col-5"
                                data-column="5">
                            <label class="form-check-label" for="toggle-col-5">Usia</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input toggle-column" type="checkbox" id="toggle-col-6"
                                data-column="6" checked>
                            <label class="form-check-label" for="toggle-col-6">Jenis Kelamin</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input toggle-column" type="checkbox" id="toggle-col-7"
                                data-column="7" checked>
                            <label class="form-check-label" for="toggle-col-7">Agama</label>
                        </div>
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
                    <h5 class="modal-title" id="importModalLabel">Import Data Siswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.student.import') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">File Excel/CSV</label>
                            <input type="file" class="form-control" name="file" accept=".xlsx,.xls,.csv" required>
                            <div class="form-text">
                                Format yang didukung: .xlsx, .xls, .csv. Ukuran maksimal: 10MB
                            </div>
                        </div>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i> Pastikan data sesuai dengan format template.
                            <a href="{{ route('admin.student.import.template') }}" class="alert-link">Download
                                Template</a>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Import</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        var counter = 0;
        var filterActive = false;

        // Inisialisasi DataTable
        var siswaTable = $('#siswaTable').DataTable({
            ajax: {
                url: "{{ route('admin.student.get-data') }}",
                type: 'GET',
                data: function(d) {
                    // Filter standar
                    d.jenis_kelamin = $('#filter-gender').val();
                    d.search_value = $('#search-input').val();
                    d.agama = $('#filter-agama').val();
                    d.usia_min = $('#filter-usia-min').val();
                    d.usia_max = $('#filter-usia-max').val();
                    d.initial = $('#filter-initial').val();

                    // Pengurutan
                    if ($('#filter-sort').val()) {
                        var sortData = $('#filter-sort').val().split('|');
                        d.sort_by = sortData[0];
                        d.sort_dir = sortData[1];
                    }

                    // Filter lanjutan
                    d.tgl_lahir_start = $('#filter-tgl-lahir-start').val();
                    d.tgl_lahir_end = $('#filter-tgl-lahir-end').val();
                    d.bulan_lahir = $('#filter-bulan-lahir').val();
                    d.has_foto = $('#filter-has-foto').is(':checked') ? 1 : 0;
                    d.complete_data = $('#filter-complete-data').is(':checked') ? 1 : 0;
                    d.alamat = $('#filter-alamat').val();

                    return d;
                }
            },
            dom: 'Bfrtip',
            buttons: [{
                    extend: 'excel',
                    text: 'Excel',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'pdf',
                    text: 'PDF',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'print',
                    text: 'Print',
                    exportOptions: {
                        columns: ':visible'
                    }
                }
            ],
            processing: true,
            searching: false,
            serverSide: true,
            paging: true,
            pageLength: 10,
            lengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, "Semua"]
            ],
            columns: [{
                    data: 'number'
                },
                {
                    data: 'name',
                    render: function(data, type, row) {
                        return '<strong>' + data + '</strong>';
                    }
                },
                {
                    data: 'nis'
                },
                {
                    data: 'nisn'
                },
                {
                    data: 'tanggal_lahir'
                },
                {
                    data: 'usia'
                },
                {
                    data: 'jenis_kelamin'
                },
                {
                    data: 'agama'
                },
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function(data, type, full, meta) {
                        var editUrl = '{{ route('admin.student.edit', ':id') }}'.replace(':id', full.id);
                        var showUrl = '{{ route('admin.student.show', ':id') }}'.replace(':id', full.id);

                        return `
                        <div class="actions">
                            <a target="_blank" href="${showUrl}" class="btn btn-sm" title="Lihat Detail">
                                <i class="feather-eye"></i>
                            </a>
                            <a href="${editUrl}" class="btn btn-sm mx-1" title="Edit">
                                <i class="feather-edit"></i>
                            </a>
                            <button class="btn btn-sm delete" data-siswa="${full.id}" title="Hapus">
                                <i class="feather-trash"></i>
                            </button>
                        </div>
                    `;
                    }
                }
            ],
            drawCallback: function() {
                updateFilterStatus();
            },
            error: function(xhr, status, error) {
                console.log('DataTables error:', error);
            },
            initComplete: function() {
                // Sembunyikan tombol export DataTables bawaan
                $('.dt-buttons').hide();

                // Sembunyikan kolom usia secara default
                siswaTable.column(5).visible(false);
            }
        });

        // Event handlers untuk filter biasa
        $('#filter-gender, #filter-agama, #filter-initial, #filter-sort').change(function() {
            siswaTable.ajax.reload();
            filterActive = true;
        });

        $('#filter-usia-min, #filter-usia-max').on('change keyup', function() {
            // Delay untuk mencegah terlalu banyak request
            clearTimeout($(this).data('timeout'));
            $(this).data('timeout', setTimeout(function() {
                siswaTable.ajax.reload();
                filterActive = true;
            }, 500));
        });

        // Tombol pencarian
        $('#btn-search').click(function() {
            siswaTable.ajax.reload();
            filterActive = true;
        });

        // Enter key untuk pencarian
        $('#search-input').keypress(function(e) {
            if (e.which === 13) {
                $('#btn-search').click();
            }
        });

        // Modal filter lanjutan
        $('#show-filter-modal').click(function() {
            $('#filterAdvancedModal').modal('show');
        });

        // Modal import
        $('#show-import-modal').click(function() {
            $('#importModal').modal('show');
        });

        // Terapkan filter lanjutan
        $('#apply-advanced-filter').click(function() {
            $('#filterAdvancedModal').modal('hide');
            siswaTable.ajax.reload();
            filterActive = true;
        });

        // Toggle kolom
        $('#toggle-columns').click(function() {
            $('#columnToggleModal').modal('show');
        });

        $('.toggle-column').change(function() {
            var column = siswaTable.column($(this).data('column'));
            column.visible($(this).is(':checked'));
        });

        // Export data
        $('#export-excel').click(function(e) {
            e.preventDefault();

            // Ambil semua parameter filter yang aktif
            var params = {
                jenis_kelamin: $('#filter-gender').val(),
                agama: $('#filter-agama').val(),
                usia_min: $('#filter-usia-min').val(),
                usia_max: $('#filter-usia-max').val(),
                initial: $('#filter-initial').val(),
                search_value: $('#search-input').val(),
                tgl_lahir_start: $('#filter-tgl-lahir-start').val(),
                tgl_lahir_end: $('#filter-tgl-lahir-end').val(),
                bulan_lahir: $('#filter-bulan-lahir').val(),
                alamat: $('#filter-alamat').val()
            };

            // Buat URL dengan parameter
            var url = "{{ route('admin.student.export.excel') }}?" + $.param(params);

            // Redirect ke URL export
            window.location.href = url;
        });

        $('#export-pdf').click(function() {
            siswaTable.button('.buttons-pdf').trigger();
        });

        $('#export-print').click(function() {
            siswaTable.button('.buttons-print').trigger();
        });

        // Reset filter
        $('#btn-reset-filter').click(function() {
            // Reset semua filter
            $('#filter-gender').val(' ');
            $('#filter-agama').val('');
            $('#search-input').val('');
            $('#filter-usia-min').val('');
            $('#filter-usia-max').val('');
            $('#filter-initial').val('');
            $('#filter-sort').val('name|asc');

            // Reset filter lanjutan
            $('#filter-tgl-lahir-start').val('');
            $('#filter-tgl-lahir-end').val('');
            $('#filter-bulan-lahir').val('');
            $('#filter-has-foto').prop('checked', false);
            $('#filter-complete-data').prop('checked', false);
            $('#filter-alamat').val('');

            // Reset kolom usia ke default (tersembunyi)
            $('#toggle-col-5').prop('checked', false);
            siswaTable.column(5).visible(false);

            // Reload table
            siswaTable.ajax.reload();
            filterActive = false;

            // Update status
            updateFilterStatus();
        });

        // Update status filter yang aktif
        function updateFilterStatus() {
            var activeFilters = [];

            if ($('#filter-gender').val() && $('#filter-gender').val() !== ' ') {
                activeFilters.push('Jenis Kelamin: ' + ($('#filter-gender').val() === 'l' ? 'Laki-laki' : 'Perempuan'));
            }

            if ($('#filter-agama').val()) {
                activeFilters.push('Agama: ' + $('#filter-agama').val());
            }

            if ($('#filter-usia-min').val() || $('#filter-usia-max').val()) {
                var usiaText = 'Usia: ';
                if ($('#filter-usia-min').val()) usiaText += 'min ' + $('#filter-usia-min').val();
                if ($('#filter-usia-min').val() && $('#filter-usia-max').val()) usiaText += ' - ';
                if ($('#filter-usia-max').val()) usiaText += 'max ' + $('#filter-usia-max').val();
                activeFilters.push(usiaText);
            }

            if ($('#filter-initial').val()) {
                activeFilters.push('Huruf Awal: ' + $('#filter-initial').val());
            }

            if ($('#search-input').val()) {
                activeFilters.push('Pencarian: "' + $('#search-input').val() + '"');
            }

            // Filter lanjutan
            if ($('#filter-tgl-lahir-start').val() || $('#filter-tgl-lahir-end').val()) {
                activeFilters.push('Rentang Tanggal Lahir');
            }

            if ($('#filter-bulan-lahir').val()) {
                const bulanNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September',
                    'Oktober', 'November', 'Desember'
                ];
                const bulanIndex = parseInt($('#filter-bulan-lahir').val()) - 1;
                activeFilters.push('Bulan Lahir: ' + bulanNames[bulanIndex]);
            }

            if ($('#filter-has-foto').is(':checked')) {
                activeFilters.push('Memiliki Foto');
            }

            if ($('#filter-complete-data').is(':checked')) {
                activeFilters.push('Data Lengkap');
            }

            if ($('#filter-alamat').val()) {
                activeFilters.push('Alamat: "' + $('#filter-alamat').val() + '"');
            }

            // Tampilkan atau sembunyikan status filter
            if (activeFilters.length > 0) {
                $('#filter-status').html('<i class="fas fa-filter me-1"></i> Filter aktif: ' + activeFilters.join(', '));
                $('#filter-status').show();
            } else {
                $('#filter-status').hide();
            }
        }

        // Setup AJAX headers
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Handler untuk tombol delete
        $('#siswaTable').on('click', '.btn-delete', function(e) {
            e.preventDefault();
            const siswaId = $(this).data('siswa');
            Swal.fire({
                title: "Anda yakin?",
                text: "Data akan dihapus permanen!",
                icon: 'warning',
                confirmButtonText: "Ya, Hapus!",
                showCancelButton: true,
                customClass: {
                    confirmButton: 'btn btn-danger',
                    cancelButton: 'btn btn-primary',
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `{{ route('admin.student.destroy', ':id') }}`.replace(':id', siswaId),
                        type: 'DELETE',
                        success: function(response) {
                            siswaTable.ajax.reload();
                            Swal.fire({
                                title: 'Berhasil!',
                                text: response.message,
                                icon: 'success'
                            });
                        },
                        error: function(xhr) {
                            Swal.fire({
                                title: 'Error!',
                                text: xhr.responseJSON.message,
                                icon: 'error'
                            });
                        }
                    });
                }
            });
        });

        // Import data siswa
        $('#importModal form').submit(function(e) {
            e.preventDefault();

            var formData = new FormData(this);

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    $('#importModal').modal('hide');

                    Swal.fire({
                        title: 'Berhasil!',
                        text: response.message,
                        icon: 'success'
                    }).then(function() {
                        // Reload data table
                        siswaTable.ajax.reload();
                    });
                },
                error: function(xhr) {
                    var errorMessage = 'Terjadi kesalahan pada server';

                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }

                    Swal.fire({
                        title: 'Error!',
                        text: errorMessage,
                        icon: 'error'
                    });
                }
            });
        });
    </script>
@endpush
