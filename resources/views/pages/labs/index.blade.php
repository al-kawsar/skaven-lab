@extends('layouts.app-layout')
@section('content')
    `
    <div class="page-header">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-sub-header">
                    <h3 class="page-title">Lab</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">All Lab</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!--
                                                                <div class="student-group-form">
                                                                <form class="row" method="GET" action="{{ route('admin.lab.index') }}">
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
                                                                                <option value="unavailable" @selected(request()->status == 'unavailable')>Tidak Tersedia</option>
                                                                                <option value="available" @selected(request()->status == 'available')>Tersedia</option>
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
                                                            -->
    <div class="row">
        <div class="col-xl-4 col-sm-6 col-12 d-flex">
            <div class="card bg-comman w-100">
                <div class="card-body">
                    <div class="db-widgets d-flex justify-content-between align-items-center">
                        <div class="db-info">
                            <h5 class="p-0 m-0 text-muted">Lab</h5>
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

    <div class="row">
        <div class="col-sm-12">
            <div class="card card-table comman-shadow ">
                <div class="card-body">

                    <div class="page-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="page-title">Lab</h3>
                            </div>
                            <div class="col-auto text-end float-end ms-auto download-grp">
                                <a class="btn btn-outline-danger me-2" id="delete-all"
                                    data-total="{{ $data['totalData'] }}">
                                    <i class="fas fa-trash"></i> Hapus Semua Data Lab
                                </a>
                                <a href="{{ route('admin.lab.create') }}" class="btn btn-primary"><i
                                        class="fas fa-plus"></i></a>
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

        $('#labTable').DataTable({
            ajax: {
                url: "{{ route('admin.lab.get-data') }}",
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
                    data: 'name',
                    render: function(data, type, row) {
                        return '<strong>' + data + '</strong>'; // Make the text bold
                    }
                },
                {
                    data: 'facilities'
                },

                {
                    data: 'status',
                    render: function(data, type, row) {
                        return `<div class="badge bg-primary-light rounded-pill text-capitalize">${data}</div>`;
                    }
                },
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function(data, type, full, meta) {
                        var editUrl = '{{ route('admin.lab.edit', ':id') }}'.replace(':id', full.id);
                        var showUrl = '{{ route('lab.show', ':id') }}'.replace(':id', full.id);

                        return `
                        <div class="d-flex p-0 m-0 align-items-center justify-content-center">
                                <a href="${showUrl}" class="btn btn-sm btn-ifo text-whte me-1"><i class="far fa-eye"></i></a>
                                <a href="${editUrl}" class="btn btn-sm btn-warnin tet-white me-1"><i class="feather-edit"></i></a>
                                <button class="btn btn-sm btn-dangr btn-delete" data-lab="${full.id}"><i class="feather-trash"></i></button>
                            
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
                    title: 'Error!',
                    text: 'Data masih kosong',
                    icon: 'error'
                });
                return;
            }

            const self = this;

            Swal.fire({
                title: "Anda yakin?",
                text: "Semua Data akan dihapus permanen!",
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
                        url: `{{ route('admin.lab.destroy.all') }}`,
                        type: 'DELETE',
                        success: function(response) {
                            $(self).data('total', 0);
                            Swal.fire({
                                title: 'Berhasil!',
                                text: response.message,
                                icon: 'success'
                            }).then(() => {
                                $('#totalData').text(0);
                                $('#totalAvailable').text(0);
                                $('#totalUnavailable').text(0);
                                $('#labTable').DataTable().ajax.reload();
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

        // Tombol hapus di klik (1)
        $('#labTable').on('click', '.btn-delete', function(e) {
            e.preventDefault();
            const labId = $(this).data('lab');
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
                        url: `{{ route('admin.lab.destroy', ':id') }}`.replace(':id', labId),
                        type: 'DELETE',
                        success: function(response) {
                            $('#labTable').DataTable().ajax.reload();
                            Swal.fire({
                                title: 'Berhasil!',
                                text: response.message,
                                icon: 'success'
                            })
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
    </script>
@endpush
