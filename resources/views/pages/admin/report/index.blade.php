@extends('layouts.admin-layout')
@section('content')
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
        <div class="col-sm-12">
            <div class="card card-table comman-shadow ">
                <div class="card-body">

                    <div class="page-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="page-title">Laporan Lab</h3>
                            </div>
                        </div>
                    </div>
                    <x-data-table name="labTable" :header="['#', 'Nama', 'Lokasi', 'Kapasitas','Status','Action']" />
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
        searching: false,
        serverSide: false,
        columns: [
            { data: 'number' },
             {
                data: 'name',
                render: function(data, type, row) {
                    return '<strong>' + data + '</strong>';  // Make the text bold
                }
            },
            { data: 'location' },
            { data: 'capacity' },
            {
                data: 'status',
                render: function(data, type, row) {
                    return `<div class="badge bg-info text-capitalize">${data}</div>`;
                }
            },
            {
                data: null,
                orderable: false,
                searchable: false,
                render: function(data, type, full, meta) {
                    var editUrl = '{{ route("admin.lab.edit", ":id") }}'.replace(':id', full.id);
                    var deleteUrl = '{{ route("admin.lab.destroy", ":id") }}'.replace(':id', full.id);

                    return `
                        <div class="dropdown dropdown-action">
                            <a href="javascript:;" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="${editUrl}"><i class="far fa-edit me-2"></i>Edit</a>
                                <a class="dropdown-item" href="view-invoice.html"><i class="far fa-eye me-2"></i>View</a>
                                <a class="dropdown-item btn-delete" data-lab="${full.id}" href="javascript:;"><i class="far fa-trash-alt me-2"></i>Delete</a>
                            </div>
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

            if(totalData == 0){
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
                            $('#labTable').DataTable().ajax.reload();
                            $(self).data('total', 0);
                            Swal.fire({
                                title: 'Berhasil!',
                                text: response.message,
                                icon: 'success'
                            })
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
