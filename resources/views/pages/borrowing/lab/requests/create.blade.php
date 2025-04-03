@extends('layouts.app-layout')
@section('content')
    <div class="row">
        <div class="col-12 col-md-7 col-lg-8 ">
            <div class="card comman-shadow">
                <div class="card-body">
                    <h4 class="text-center text-primary p-0  mb-4">{{ $labData['name'] }}</h4>
                    <form id="addBorrowLab" enctype="multipart/form-data"
                        action="{{ route('borrowing.lab.store', $labData['id']) }}" method="POST">
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
        <div class="col-12 col-md-5 col-lg-4 ">
            <div class="card comman-shadow">
                <div class="card-body">
                    <h4 class="pb-2 text-center text-primary p-0 m-0">Info Lab</h4>

                    {{-- <p class="p-0 mx-0 mt-3 text-muted text-center">Data Ruangan<p> --}}
                    <div class="lab-content d-flex flex-column gap-3">
                        <div class="bg-secondary rounded " style="height: 200px;">
                            <img class="img-fluid h-100 rounded w-100" src="{{ $labData['thumbnail'] }}"
                                alt="Thumbnail Image | {{ $labData['name'] }}" style="object-fit: cover">
                        </div>

                        <h5 class="p-0 m-0">{{ $labData['name'] }}</h5>
                        {{-- <div class="d-flex gap-2">
                            <a class="d-block badge bg-primary-light text-uppercase">{{ $labData['location'] }}</a>
                            <a class="d-block badge badge-soft-primary text-uppercase">{{ $labData['capacity'] }}
                                Orang</a>
                        </div> --}}
                        <div class="text-muted">{{ $labData['facilities'] }}</div>
                    </div>
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
        });
    </script>
@endpush
