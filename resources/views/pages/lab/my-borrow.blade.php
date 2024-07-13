@extends('layouts.admin-layout')
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card card-table comman-shadow ">
                <div class="card-body">

                    <div class="page-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="page-title">Peminjaman Saya</h3>
                            </div>
                        </div>
                    </div>
                    <x-data-table name="borrowTable" :header="[
                        '#',
                        'Lab',
                        'Tanggal Peminjaman',
                        'Waktu Mulai',
                        'Waktu Selesai',
                        'Sisa Waktu',
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
                url: "{{ route('borrow.get-data') }}",
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
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function(data, type, full, meta) {
                        const startTime = full.start_time; // Example start time (HH:mm:ss format)
                        const endTime = full.end_time; // Example end time (HH:mm:ss format)

                        const [startHour, startMinute, startSecond] = startTime.split(':').map(Number);
                        const [endHour, endMinute, endSecond] = endTime.split(':').map(Number);

                        const currentDate = new Date();
                        const currentYear = currentDate.getFullYear();
                        const currentMonth = currentDate.getMonth();
                        const currentDay = currentDate.getDate();

                        let startDate = new Date(currentYear, currentMonth, currentDay, startHour,
                            startMinute, startSecond);
                        let endDate = new Date(currentYear, currentMonth, currentDay, endHour, endMinute,
                            endSecond);

                        if (startDate > endDate) {
                            endDate.setDate(endDate.getDate() + 1); // Move endDate to the next day
                        }

                        let remainingTime = endDate.getTime() - currentDate.getTime();

                        // Mengambil tahun, bulan, dan hari dari objek Date
                        let amonth = String(currentDate.getMonth() + 1).padStart(2,
                            '0'); // Menambahkan 1 karena bulan dimulai dari 0
                        let aday = String(currentDate.getDate()).padStart(2, '0');

                        // Menggabungkan tahun, bulan, dan hari dalam format YYYY-MM-DD
                        let formattedDate = `${currentYear}-${amonth}-${aday}`;

                        function formatRemainingTime() {
                            remainingTime = endDate.getTime() - Date.now();
                            if (full.status == 'menunggu' && remainingTime >= 0) {
                                clearInterval(countdownTimer);
                                return '-';
                            }
                            if (remainingTime <= 0 || full.status == 'ditolak' || full.borrow_date <
                                formattedDate) {
                                clearInterval(countdownTimer);
                                return 'Waktu habis';
                            }

                            const hours = Math.floor(remainingTime / (1000 * 60 * 60));
                            const minutes = Math.floor((remainingTime % (1000 * 60 * 60)) / (1000 * 60));
                            const seconds = Math.floor((remainingTime % (1000 * 60)) / 1000);

                            let displayTime =
                                `${hours != 0 ? hours + ' Jam' : ''} ${minutes != 0 ? minutes + ' Menit' : ''} ${seconds} Detik`
                            return displayTime;
                        }

                        const countdownTimer = setInterval(function() {
                            const remainingTimeString = formatRemainingTime();
                            $(meta.settings.aoData[meta.row].anCells[meta.col]).text(
                                remainingTimeString);
                        }, 1000);

                        return formatRemainingTime();
                    }
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
                        return `<div class="rounded badge ${bgStatus} text-capitalize">${data}</div>`;
                    }
                },
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function(data, type, full, meta) {
                        const startTime = full.start_time; // Example start time (HH:mm:ss format)
                        const endTime = full.end_time; // Example end time (HH:mm:ss format)
                        const borrowDate = full.borrow_date; // Example borrow date (YYYY-MM-DD format)

                        const [startHour, startMinute, startSecond] = startTime.split(':').map(Number);
                        const [endHour, endMinute, endSecond] = endTime.split(':').map(Number);

                        const [year, month, day] = borrowDate.split('-').map(Number);

                        const currentDate = new Date();
                        const borrowEndDate = new Date(year, month - 1, day, endHour, endMinute, endSecond);

                        const now = currentDate.getTime();

                        let actionBtn = "";

                        if (now > borrowEndDate.getTime()) {
                            if (full.status === 'disetujui') {
                                actionBtn = `
            <div class="aksi">
                <div class="text-center py-2 badge rounded fw-bold badge-success text-white p-1" style="font-size: 14px;">Dikembalikan</div>
            </div>`;
                            } else if (full.status === 'menunggu') {
                                actionBtn = `
            <div class="aksi">
                <div data-borrow="${full.id}" class="btn-cancel text-center py-2 badge rounded fw-bold bg-danger text-white p-1" style="font-size: 14px;">Batalkan</div>
            </div>`;
                            }
                        } else {
                            if (full.status === 'disetujui') {
                                actionBtn = `
            <div class="aksi">
                <div data-borrow="${full.id}" style="cursor: pointer" class="btn-back text-center py-2 badge rounded fw-bold bg-info text-white p-1" style="font-size: 14px;">Kembalikan</div>
            </div>`;
                            } else if (full.status === 'menunggu') {
                                actionBtn = `
            <div class="aksi">
                <div data-borrow="${full.id}" style="cursor: pointer" class="btn-cancel text-center py-2 badge rounded fw-bold bg-danger text-white p-1" style="font-size: 14px;">Batalkan</div>
            </div>`;
                            }
                        }

                        return actionBtn;
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
