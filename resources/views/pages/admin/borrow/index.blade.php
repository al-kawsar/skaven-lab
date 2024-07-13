@extends('layouts.admin-layout')
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card card-table comman-shadow ">
                <div class="card-body">

                    <div class="page-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="page-title">Data Peminjaman</h3>
                            </div>
                        </div>
                    </div>
                    <x-data-table name="borrowTable" :header="[
                        '#',
                        'Peminjam',
                        'Lab',
                        'Tanggal Peminjaman',
                        'Waktu Mulai',
                        'Waktu Berakhir',
                        'Keperluan',
                        'Status',
                        'Aksi',
                    ]" />
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        var counter = 0;

        let table = $('#borrowTable').DataTable({
            ajax: {
                url: "{{ route('admin.borrow.get-data') }}",
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
                    data: 'peminjam',
                    render: function(data, type, row) {
                        return '<strong>' + data + '</strong>'; // Make the text bold
                    }
                },
                {
                    data: 'lab',
                    render: function(data, type, row) {
                        return '<strong>' + data + '</strong>'; // Make the text bold
                    }
                },
                {
                    data: 'borrow_date'
                },
                {
                    data: 'start_time'
                },
                {
                    data: 'end_time'
                },
                {
                    data: 'event'
                },
                {
                    data: 'status',
                    render: function(data, type, row) {
                        let bgStatus;
                        if (data == 'disetujui') bgStatus = 'bg-success';
                        if (data == 'menunggu') bgStatus = 'bg-warning';
                        if (data == 'ditolak') bgStatus = 'bg-danger';
                        return `<div class="badge ${bgStatus} rounded py-2 text-capitalize">${data}</div>`;
                    }
                },
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function(data, type, full, meta) {
                        var bgStatus, text;
                        if (full.status == 'disetujui') {
                            bgStatus = 'bg-info';
                            text = 'Dikembalikan'
                        }
                        if (full.status == 'menunggu') {
                            bgStatus = 'bg-danger';
                            text = 'Tolak'
                        }
                        if (full.status == 'ditolak') {
                            bgStatus = 'bg-info';
                            text = 'Dikembalikan'
                        }
                        return `<div class="rounded ${bgStatus} text-center fw-bold text-white p-1" style="font-size: 14px;"> ${text}</div>`;
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
    </script>
@endpush
