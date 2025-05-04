@extends('layouts.app-layout')
@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-md-8">
            <div class="card comman-shadow">
                <div class="card-body">
                    <h4 class="text-center text-primary p-0 mb-4">Form Pengajuan Peminjaman Ruangan</h4>
                    <form id="addBorrowLab" enctype="multipart/form-data" action="{{ route('borrowing.lab.store') }}"
                        method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group local-forms">
                                    <label class="text-dark" for="name">Keperluan <span
                                            class="login-danger">*</span></label>
                                    <select id="purpose-select" class="form-select mb-2">
                                        <option value="">-- Pilih Keperluan --</option>
                                        <option value="Praktikum">Praktikum</option>
                                        <option value="Kelas Pengganti">Kelas Pengganti</option>
                                        <option value="Ujian Praktik">Ujian Praktik</option>
                                        <option value="Ujian Teori">Ujian Teori</option>
                                        <option value="UKK">UKK (Uji Kompetensi Keahlian)</option>
                                        <option value="LSP">LSP (Lembaga Sertifikasi Profesi)</option>
                                        <option value="Ekstrakurikuler">Ekstrakurikuler</option>
                                        <option value="LKS">LKS (Lomba Kompetensi Siswa)</option>
                                        <option value="custom">Keperluan Lainnya</option>
                                    </select>
                                    <input id="event" required class="form-control" name="event" type="text"
                                        placeholder="Masukkan keperluan peminjaman">
                                </div>
                            </div>
                            <div class="col-12">
                                <x-datetime-picker type="date" id="date" name="borrow_date" label="Tanggal Pinjam"
                                    placeholder="Pilih Tanggal" required="true" />
                            </div>
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-12 col-lg-6">
                                        <x-datetime-picker type="time" id="start_time" name="start_time"
                                            label="Waktu Mulai" placeholder="Pilih Waktu Mulai" required="true" />
                                    </div>
                                    <div class="col-12 col-lg-6">
                                        <x-datetime-picker type="time" id="end_time" name="end_time"
                                            label="Waktu Selesai" placeholder="Pilih Waktu Selesai" required="true" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="card border">
                                    <div class="card-body p-0 px-2 pt-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="enable_recurring"
                                                name="is_recurring" value="1">
                                            <label class="form-check-label fw-bold" for="enable_recurring">
                                                Jadikan Peminjaman Berulang
                                            </label>
                                        </div>
                                    </div>
                                    <div class="card-body recurring-options" style="display: none;">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="recurrence_type">Jenis Perulangan</label>
                                                    <select name="recurrence_type" id="recurrence_type" class="form-select">
                                                        <option value="daily">Harian</option>
                                                        <option value="weekly" selected>Mingguan</option>
                                                        <option value="monthly">Bulanan</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="recurrence_interval">Interval</label>
                                                    <select name="recurrence_interval" id="recurrence_interval"
                                                        class="form-select">
                                                        @for ($i = 1; $i <= 12; $i++)
                                                            <option value="{{ $i }}">{{ $i }}</option>
                                                        @endfor
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-3">
                                            <div class="col-12">
                                                <label>Akhiri Perulangan</label>
                                                <div class="form-check mt-2">
                                                    <input class="form-check-input" type="radio" name="ends_option"
                                                        id="ends_never" value="never" checked>
                                                    <label class="form-check-label" for="ends_never">
                                                        Tidak pernah berakhir
                                                    </label>
                                                </div>
                                                <div class="form-check mt-2">
                                                    <input class="form-check-input" type="radio" name="ends_option"
                                                        id="ends_after" value="after">
                                                    <label class="form-check-label" for="ends_after">
                                                        Setelah
                                                        <input type="number" name="recurrence_count"
                                                            id="recurrence_count"
                                                            class="form-control form-control-sm d-inline-block mx-2"
                                                            style="width: 70px;" min="1" max="52"
                                                            value="12" disabled>
                                                        kali
                                                    </label>
                                                </div>
                                                <div class="form-check mt-2">
                                                    <input class="form-check-input" type="radio" name="ends_option"
                                                        id="ends_on" value="on">
                                                    <label class="form-check-label" for="ends_on">
                                                        Pada tanggal
                                                        <input type="date" name="recurrence_ends_at"
                                                            id="recurrence_ends_at"
                                                            class="form-control form-control-sm d-inline-block mx-2"
                                                            style="width: 150px;" disabled>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="alert alert-info mt-3">
                                            <i class="fas fa-info-circle me-2"></i>
                                            <span id="recurrence_summary">Peminjaman akan berulang setiap minggu</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group local-forms">
                                    <label class="text-dark" for="notes">Catatan</label>
                                    <textarea name="notes" id="notes" rows="5" class="form-control"
                                        placeholder="Tambahkan catatan jika diperlukan"></textarea>
                                </div>
                            </div>
                            <div class="d-flex gap-2 align-items-center justify-content-center mt-3">
                                <button type="submit" id="btn-submit" class="btn btn-primary px-4">Kirim</button>
                                <a href="{{ route('borrowing.lab.index') }}" class="btn btn-secondary">Batal</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        $(document).ready(function() {
            // Menangani perubahan pada dropdown keperluan
            $('#purpose-select').change(function() {
                const selectedValue = $(this).val();
                const eventInput = $('#event');

                if (selectedValue === 'custom') {
                    // Jika pilihan "Keperluan Lainnya", enable input dan kosongkan
                    eventInput.val('').focus();
                } else if (selectedValue !== '') {
                    // Jika pilihan lain, disable input dan isi dengan nilai yang dipilih
                    eventInput.val(selectedValue);
                } else {
                    // Jika tidak ada pilihan, disable dan kosongkan input
                    eventInput.val('');
                }
            });

            // Menangani perubahan pada input keperluan
            $('#event').on('input', function() {
                const purposeSelect = $('#purpose-select');
                const inputValue = $(this).val();
                const options = purposeSelect.find('option');

                // Cek semua option untuk mencari yang cocok dengan input
                let matchFound = false;
                options.each(function() {
                    if ($(this).val() === inputValue && $(this).val() !== 'custom') {
                        purposeSelect.val(inputValue);
                        matchFound = true;
                        return false; // Break loop
                    }
                });

                // Jika tidak ada yang cocok, set ke custom
                if (!matchFound) {
                    purposeSelect.val('custom');
                }
            });

            // Form submission handler
            $('#addBorrowLab').submit(function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const submitBtn = $('#btn-submit');

                // Disable submit button
                submitBtn.prop('disabled', true);
                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        // Show success message
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            // Redirect to borrowing list
                            window.location.href = "{{ route('borrowing.lab.index') }}";
                        });
                    },
                    error: function(xhr) {
                        // Show error message
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: xhr.responseJSON.message || 'Terjadi kesalahan!',
                        });

                        // Re-enable submit button
                        submitBtn.prop('disabled', false);
                    }
                });
            });

            // Date and time validation
            $('#date, #start_time, #end_time').change(function() {
                const date = $('#date').val();
                const startTime = $('#start_time').val();
                const endTime = $('#end_time').val();

                if (date && startTime && endTime) {
                    // Get current date and time
                    const now = new Date();
                    const selectedDate = new Date(date);
                    selectedDate.setHours(startTime.split(':')[0], startTime.split(':')[1]);

                    // If selected date is today, validate time is not in the past
                    if (selectedDate.toDateString() === now.toDateString()) {
                        const currentTime = now.getHours() * 60 + now.getMinutes();
                        const selectedTime = parseInt(startTime.split(':')[0]) * 60 + parseInt(startTime
                            .split(':')[1]);

                        if (selectedTime < currentTime) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Invalid Time',
                                text: 'Waktu mulai tidak boleh di masa lalu!'
                            });
                            $('#start_time').val('');
                            $('#end_time').val('');
                            return;
                        }
                    }
                    // Validate date is not in the past
                    else if (selectedDate < now) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Invalid Date',
                            text: 'Tanggal peminjaman tidak boleh di masa lalu!'
                        });
                        $('#date').val('');
                        return;
                    }

                    // Validate end time is after start time
                    if (startTime >= endTime) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Invalid Time',
                            text: 'Waktu selesai harus setelah waktu mulai!'
                        });
                        $('#end_time').val('');
                    }
                }
            });

            // Check availability
            $('#date, #start_time, #end_time').change(function() {
                const date = $('#date').val();
                const startTime = $('#start_time').val();
                const endTime = $('#end_time').val();

                if (date && startTime && endTime && startTime < endTime) {
                    // Show loading indicator
                    const submitBtn = $('#btn-submit');
                    submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Checking...');
                    submitBtn.prop('disabled', true);

                    // Check availability
                    $.ajax({
                        url: "{{ route('borrowing.lab.check') }}",
                        type: 'POST',
                        data: {
                            borrow_date: date,
                            start_time: startTime,
                            end_time: endTime,
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.available) {
                                // Time slot available
                                submitBtn.html('Kirim');
                                submitBtn.prop('disabled', false);
                            } else {
                                // Time slot not available
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Waktu Tidak Tersedia',
                                    html: `Waktu yang Anda pilih sudah dipesan oleh <strong>${response.conflict.user}</strong><br>untuk keperluan: <strong>${response.conflict.event}</strong><br>pada waktu: <strong>${response.conflict.time}</strong>`,
                                    confirmButtonText: 'Pilih Waktu Lain'
                                });
                                $('#end_time').val('');
                                submitBtn.html('Kirim');
                                submitBtn.prop('disabled', true);
                            }
                        },
                        error: function() {
                            // Error checking availability
                            submitBtn.html('Kirim');
                            submitBtn.prop('disabled', false);
                        }
                    });
                }
            });

            // Toggle recurring options visibility
            $('#enable_recurring').change(function() {
                if ($(this).is(':checked')) {
                    $('.recurring-options').slideDown();
                } else {
                    $('.recurring-options').slideUp();
                }
            });

            // Handle end options
            $('input[name="ends_option"]').change(function() {
                const endOption = $(this).val();

                // Reset and disable all end option inputs
                $('#recurrence_count, #recurrence_ends_at').prop('disabled', true);

                // Enable the selected end option input
                if (endOption === 'after') {
                    $('#recurrence_count').prop('disabled', false);
                } else if (endOption === 'on') {
                    $('#recurrence_ends_at').prop('disabled', false);
                }

                updateRecurrenceSummary();
            });

            // Update summary when options change
            $('#recurrence_type, #recurrence_interval, #recurrence_count, #recurrence_ends_at').change(function() {
                updateRecurrenceSummary();
            });

            // Initialize picker for recurrence end date
            if ($('#recurrence_ends_at').length) {
                const today = new Date();
                const oneYearLater = new Date();
                oneYearLater.setFullYear(today.getFullYear() + 1);

                $('#recurrence_ends_at').val(formatDate(oneYearLater));
            }

            function updateRecurrenceSummary() {
                const type = $('#recurrence_type').val();
                const interval = $('#recurrence_interval').val();
                const endOption = $('input[name="ends_option"]:checked').val();

                let typeText = 'minggu';
                if (type === 'daily') typeText = 'hari';
                if (type === 'monthly') typeText = 'bulan';

                let summary = `Peminjaman akan berulang setiap `;
                if (interval > 1) {
                    summary += `${interval} ${typeText}`;
                } else {
                    summary += typeText;
                }

                if (endOption === 'after') {
                    const count = $('#recurrence_count').val();
                    summary += ` sampai ${count} kali jadwal`;
                } else if (endOption === 'on') {
                    const endDate = $('#recurrence_ends_at').val();
                    summary += ` sampai tanggal ${formatDateDisplay(endDate)}`;
                }

                $('#recurrence_summary').text(summary);
            }

            function formatDate(date) {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            }

            function formatDateDisplay(dateString) {
                const date = new Date(dateString);
                const options = {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                };
                return date.toLocaleDateString('id-ID', options);
            }

            // Call once to initialize
            updateRecurrenceSummary();
        });
    </script>
@endpush
