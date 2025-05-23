@extends('layouts.app-layout')
@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-sub-header">
                    <h3 class="page-title">Guru</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">All Guru</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{--
<div class="student-group-form">
    <form class="row" method="GET" action="{{ route('teacher.index') }}">
        <div class="col">
            <div class="form-group">
                <input type="text" class="form-control" name="name" value="{{ request()->name }}" placeholder="Search by Name ...">
            </div>
        </div>
        <div class="col">
            <div class="form-group">
                <div class="form-group local-forms">
                    <select class="form-control select" name="status" required>
                        <option value="all" @selected(true) >Semua</option>
                        <option value="unavaigurule" @selected(request()->status == 'unavaigurule')>Tidak Tersedia</option>
                        <option value="avaigurule" @selected(request()->status == 'avaigurule')>Tersedia</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-auto">
            <div class="search-student-btn">
                <button type="btn" class="btn btn-primary">Search</button>
            </div>
        </div>
    </div>
</div>
 --}}

    <div class="row">
        <div class="col-sm-12">
            <div class="card card-table comman-shadow ">
                <div class="card-body">
                    <div class="page-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="page-title">Guru</h3>
                            </div>
                            <div class="col-auto text-end float-end ms-auto download-grp">
                                <button class="btn btn-outline-danger fw-bold" id="delete-all"
                                    data-total="{{ $data['totalData'] }}">
                                    <i class="fas fa-trash"></i> HAPUS SEMUA DATA
                                </button>
                                <a href="{{ route('teacher.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Tambah Guru
                                </a>
                            </div>
                        </div>
                    </div>

                    <x-data-table name="guruTable" :header="[
                        '#',
                        'NAMA LENGKAP',
                        'NIP',
                        'TANGGAL LAHIR',
                        'AGAMA',
                        'ALAMAT',
                        'JENIS KELAMIN',
                        'ACTION',
                    ]" />
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        var counter = 0;

        $('#guruTable').DataTable({
            "language": {
                "sProcessing": "Sedang memproses...",
                "sLengthMenu": "Tampilkan _MENU_ entri",
                "sZeroRecords": "Tidak ditemukan data yang sesuai",
                "sInfo": "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                "sInfoEmpty": "Menampilkan 0 sampai 0 dari 0 entri",
                "sInfoFiltered": "(disaring dari _MAX_ entri keseluruhan)",
                "sInfoPostFix": "",
                "sSearch": "Cari:",
                "sUrl": "",
                "oPaginate": {
                    "sFirst": "Pertama",
                    "sPrevious": "Sebelumnya",
                    "sNext": "Selanjutnya",
                    "sLast": "Terakhir"
                }
            },
            ajax: {
                url: "{{ route('teacher.ajax') }}",
                type: 'GET',
                dataType: 'json',
                dataSrc: 'data'
            },
            processing: true,
            searching: true,
            serverSide: true,
            columns: [{
                    data: 'number'
                },
                {
                    data: 'name',
                    render: function(data, type, row) {
                        return '<strong>' + data + '</strong>'; // Make the text bold
                    }
                },
                {
                    data: 'nip'
                },
                {
                    data: 'tanggal_lahir'
                },
                {
                    data: 'agama'
                },
                {
                    data: 'alamat'
                },
                {
                    data: 'jenis_kelamin'
                },
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function(data, type, full, meta) {
                        var editUrl = '{{ route('teacher.edit', ':id') }}'.replace(':id', full.id);
                        var deleteUrl = '{{ route('teacher.destroy', ':id') }}'.replace(':id', full
                            .id);

                        return `
                        <div class="actions">
                            <a href="javascript:;" class="btn btn-sm text-white bg-secondary"><i class="feather-eye"></i></a>
                            <a href="${editUrl}" class="btn btn-sm text-white bg-info mx-2"><i class="feather-edit"></i></a>
                            <button class="btn btn-sm text-white bg-danger btn-delete" data-guru="${full.id}"><i class="feather-trash"></i></button>
                        </div>
                    `;
                    }
                }
            ],
            error: function(xhr, status, error) {
                console.log('DataTables error:', error);
            }
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });


        $("#delete-all").on("click", function(e) {
            e.preventDefault();
            const totalData = $(this).data('total');

            if (totalData == 0) {
                Swal.fire({
                    title: 'Tidak Ada Data!',
                    text: 'Tidak ada data yang dapat dihapus.',
                    icon: 'info'
                });
                return;
            }

            const self = this;

            Swal.fire({
                title: "Konfirmasi Penghapusan",
                text: `Anda akan menghapus ${totalData} data secara permanen. Lanjutkan?`,
                icon: 'warning',
                confirmButtonText: "Ya, Lanjutkan",
                showCancelButton: true,
                cancelButtonText: "Batal",
                customClass: {
                    confirmButton: 'btn btn-warning',
                    cancelButton: 'btn btn-secondary',
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: "Ketik 'HAPUS' untuk konfirmasi",
                        input: "text",
                        inputPlaceholder: "Ketik HAPUS",
                        showCancelButton: true,
                        cancelButtonText: "Batal",
                        confirmButtonText: "Hapus Permanen",
                        icon: 'error',
                        inputValidator: (value) => {
                            return value !== "HAPUS" ?
                                "Anda harus mengetik 'HAPUS' untuk melanjutkan." : null;
                        }
                    }).then((confirmResult) => {
                        if (confirmResult.isConfirmed) {
                            $.ajax({
                                url: `{{ route('teacher.destroy.all') }}`,
                                type: 'DELETE',
                                success: function(response) {
                                    $('#guruTable').DataTable().ajax.reload();
                                    $(self).data('total', 0);
                                    Swal.fire({
                                        title: 'Berhasil!',
                                        text: 'Semua data telah dihapus.',
                                        icon: 'success'
                                    }).then(() => {
                                        location.reload();
                                    });
                                },
                                error: function(xhr) {
                                    Swal.fire({
                                        title: 'Terjadi Kesalahan!',
                                        text: 'Gagal menghapus data. Silakan coba lagi.',
                                        icon: 'error'
                                    });
                                }
                            });
                        }
                    });
                }
            });
        });


        $('#guruTable').on('click', '.btn-delete', function(e) {
            e.preventDefault();
            const guruId = $(this).data('guru');
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
                        url: `{{ route('teacher.destroy', ':id') }}`.replace(':id', guruId),
                        type: 'DELETE',
                        success: function(response) {
                            $('#guruTable').DataTable().ajax.reload();
                            Swal.fire({
                                title: 'Berhasil!',
                                text: response.message,
                                icon: 'success'
                            })
                        },
                        error: function(xhr) {
                            console.info(xhr)
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
    </script>
@endpush
