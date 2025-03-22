@extends('layouts.app-layout')
@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-sub-header">
                    <h3 class="page-title">User</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">All User</li>
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
                                <h3 class="page-title">User</h3>
                            </div>
                            <div class="col-auto text-end float-end ms-auto download-grp">
                                <a href="{{ route('admin.user.create') }}" class="btn btn-primary"><i
                                        class="fas fa-plus"></i></a>
                            </div>
                        </div>
                    </div>
                    <x-data-table name="userTable" :header="['#', 'NAMA', 'EMAIL', 'ROLE', 'AKSI']" />
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        var counter = 0;

        $('#userTable').DataTable({
            ajax: {
                url: "{{ route('admin.user.get-data') }}",
                type: 'GET',
                dataType: 'json',
                dataSrc: 'data'
            },
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
                    data: 'email'
                },
                {
                    data: 'role'
                },
                {
                    data: null,
                    pagingType: 'numbers',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, full, meta) {
                        var editUrl = '{{ route('admin.user.edit', ':id') }}'.replace(':id', full.id);
                        return `
                        <div class="d-flex p-0 m-0 align-items-center justify-content-center">
                            <a href="${editUrl}" class="btn btn-sm"><i class="feather-edit"></i></a>
                            <button class="btn btn-sm btn-delete" data-user="${full.id}"><i class="feather-trash"></i></button>
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
                        url: `{{ route('admin.user.destroy.all') }}`,
                        type: 'DELETE',
                        success: function(response) {
                            $('#userTable').DataTable().ajax.reload();
                            $(self).data('total', 0);
                            Swal.fire({
                                title: 'Berhasil!',
                                text: response.message,
                                icon: 'success'
                            }).then(() => {
                                location.reload()
                            });
                        },
                        error: function(xhr) {
                            Swal.fire({
                                title: 'Error!',
                                text: response.message,
                                icon: 'error'
                            });
                        }
                    });
                }
            });
        });

        $('#userTable').on('click', '.btn-delete', function(e) {
            e.preventDefault();
            const userId = $(this).data('user');
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
                        url: `{{ route('admin.user.destroy', ':id') }}`.replace(':id', userId),
                        type: 'DELETE',
                        success: function(response) {
                            $('#userTable').DataTable().ajax.reload();
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
