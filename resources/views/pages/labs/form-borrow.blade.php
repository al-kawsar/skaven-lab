@extends('layouts.app-layout')
@section('content')
    <div class="row">
        <div class="col-12 col-md-7 col-lg-8 order-2 order-md-1">
            <div class="card comman-shadow">
                <div class="card-body">
                    <h4 class="text-center text-primary p-0  mb-4">{{ $labData['name'] }}</h4>
                    <form id="addBorrowLab" enctype="multipart/form-data">
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
                                    <input id="name" required class="form-control" name="event" type="text"
                                        placeholder="Masukkan keperluan peminjaman">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group local-forms">
                                    <label class="text-dark" for="date">Tanggal Pinjam <span
                                            class="login-danger">*</span></label>
                                    <input id="date" required class="form-control datetimepicker-date"
                                        name="borrow_date" type="text" placeholder="Pilih Tanggal">
                                </div>
                            </div>
                            <div class="col-12 col-lg-6">
                                <div class="form-group local-forms">
                                    <label class="text-dark" for="start_time">Waktu Mulai <span
                                            class="login-danger">*</span></label>
                                    <input id="start_time" required class="form-control timepicker" name="start_time"
                                        type="text" placeholder="Pilih Waktu">
                                </div>
                            </div>
                            <div class="col-12 col-lg-6">
                                <div class="form-group local-forms">
                                    <label class="text-dark" for="end_time">Waktu Selesai <span
                                            class="login-danger">*</span></label>
                                    <input id="end_time" required class="form-control timepicker" name="end_time"
                                        type="text" placeholder="Pilih Waktu">
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
                                <a href="{{ route('lab.index') }}" class="btn btn-secondary">Batal</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-5 col-lg-4 order-1 order-md-2">
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
                        {{-- <div class=" d-flex gap-2">
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
            // Inisialisasi datepicker untuk input tanggal
            $('.datetimepicker-date').datetimepicker({
                format: 'DD-MM-YYYY',
                useCurrent: true,
                icons: {
                    up: 'fa fa-chevron-up',
                    down: 'fa fa-chevron-down',
                    previous: 'fa fa-chevron-left',
                    next: 'fa fa-chevron-right',
                    calendar: 'fa fa-calendar'
                },
                minDate: moment().startOf('day')
            });

            // Set tanggal default ke hari ini
            $('.datetimepicker-date').val(moment().format('DD-MM-YYYY'));

            // Inisialisasi timepicker untuk input waktu
            $('.timepicker').datetimepicker({
                format: 'HH:mm',
                stepping: 15,
                icons: {
                    up: 'fa fa-chevron-up',
                    down: 'fa fa-chevron-down',
                    previous: 'fa fa-chevron-left',
                    next: 'fa fa-chevron-right'
                }
            });

            // Set default waktu mulai dan selesai
            // $('#start_time').val('08:00');
            // $('#end_time').val('10:00');

            // Menangani perubahan pada dropdown keperluan
            $('#purpose-select').change(function() {
                const selectedValue = $(this).val();
                if (selectedValue === 'custom') {
                    // Jika pilihan "Keperluan Lainnya", kosongkan input dan fokus ke sana
                    $('#name').val('').focus();
                } else if (selectedValue !== '') {
                    // Jika pilihan lain, isi input dengan nilai yang dipilih
                    $('#name').val(selectedValue);
                }
            });

            $('#addBorrowLab').submit(function(event) {
                event.preventDefault(); // Mencegah pengiriman form default
                $('#btn-submit').text("Loading...");
                const formData = new FormData($(this)[0]); // Mengambil data form
                const url = "{{ route('lab.borrow.store', $labData['id']) }}";

                formData.append('_method', 'POST'); // Menambahkan _method dengan nilai POST

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: response.message,
                            icon: 'success'
                        }).then(() => {
                            const redirectUrl = `{{ route('borrow.view') }}`
                            window.location.href =
                                redirectUrl; // Redirect atau refresh halaman setelah berhasil
                        });
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            title: 'Error!',
                            text: xhr.responseJSON.message,
                            icon: 'error'
                        });
                    },
                    complete: function() {
                        $('#btn-submit').text(
                            "Submit"); // Kembalikan teks tombol setelah selesai
                    }
                });
                $('#btn-submit').val = "Submit";
            });

            $('#borrowForm').validate({
                rules: {
                    borrow_date: {
                        required: true,
                        date: true
                    },
                    start_time: {
                        required: true
                    },
                    end_time: {
                        required: true
                    },
                    event: {
                        required: true,
                        minlength: 5
                    }
                },
                messages: {
                    borrow_date: {
                        required: "Tanggal peminjaman harus diisi",
                        date: "Format tanggal tidak valid"
                    },
                    start_time: {
                        required: "Jam mulai harus diisi"
                    },
                    end_time: {
                        required: "Jam selesai harus diisi"
                    },
                    event: {
                        required: "Nama kegiatan harus diisi",
                        minlength: "Nama kegiatan minimal 5 karakter"
                    }
                },
                errorElement: 'div',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function(element) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element) {
                    $(element).removeClass('is-invalid');
                }
            });

            $('#borrowingTable').on('click', '.print-borrow', function() {
                const borrowId = $(this).data('id');
                const printWindow = window.open(`/my-borrowing/print/${borrowId}`, '_blank');

                if (!printWindow) {
                    toastr.warning('Pop-up blocker aktif! Mohon izinkan pop-up untuk situs ini.');
                }
            });
        });
    </script>
@endpush
