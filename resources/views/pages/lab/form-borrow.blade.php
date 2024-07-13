@extends('layouts.admin-layout')
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
                                    <input id="name" required class="form-control" name="event" type="text"
                                        placeholders="Enter First Name">
                                </div>
                            </div>
                            <div class="col-12 col-lg-6">
                                <div class="form-group local-forms">
                                    <label class="text-dark" for="capacity">Jumlah Peserta <span
                                            class="login-danger">*</span></label>
                                    <input id="capacity" required class="form-control" name="participant_count"
                                        type="number" placeholders="Enter Roll Number">
                                </div>
                            </div>
                            <div class="col-12 col-lg-6">
                                <div class="form-group local-forms">
                                    <label class="text-dark" for="date">Tanggal Pinjam <span
                                            class="login-danger">*</span></label>
                                    <input id="date" required class="form-control datetimepicker" name="borrow_date"
                                        type="text" placeholder="DD-MM-YY">
                                </div>
                            </div>
                            <div class="col-12 col-lg-6">
                                <div class="form-group local-forms">
                                    <label class="text-dark" for="start_time">Waktu Mulai <span
                                            class="login-danger">*</span></label>
                                    <input id="start_time" required class="form-control" name="start_time" type="time"
                                        placeholders="Enter Roll Number">
                                </div>
                            </div>
                            <div class="col-12 col-lg-6">
                                <div class="form-group local-forms">
                                    <label class="text-dark" for="end_time">Waktu Selesai <span
                                            class="login-danger">*</span></label>
                                    <input id="end_time" required class="form-control" name="end_time" type="time"
                                        placeholders="Enter First Name">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group local-forms">
                                    <label class="text-dark" for="notes">Catatan</label>
                                    <textarea name="notes" id="notes" rows="10" class="form-control"></textarea>
                                </div>
                            </div>
                            <div class="d-flex gap-2 align-items-center justify-content-center">
                                <button type="submit" id="btn-submit" class="btn btn-primary px-4">Submit</button>
                                <a href="{{ route('lab.index') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-5 col-lg-4 order-1 order-md-2">
            <div class="card comman-shadow">
                <div class="card-body">
                    <h4 class="text-center text-primary p-0 m-0">Info Lab</h4>

                    {{-- <p class="p-0 mx-0 mt-3 text-muted text-center">Data Ruangan<p> --}}
                    <div class="lab-content d-flex flex-column gap-3">
                        <div class="bg-secondary rounded " style="height: 200px;">
                            <img class="img-fluid h-100 rounded w-100" src="{{ $labData['thumbnail'] }}"
                                alt="Thumbnail Image | {{ $labData['name'] }}" style="object-fit: cover">
                        </div>

                        <h5 class="p-0 m-0">{{ $labData['name'] }}</h5>
                        <div class=" d-flex gap-2">
                            <a class="d-block badge bg-primary-light text-uppercase">{{ $labData['location'] }}</a>
                            <a class="d-block badge badge-soft-primary text-uppercase">{{ $labData['capacity'] }}
                                Orang</a>
                        </div>
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
                            const redirectUrl = `{{ route('admin.lab.index') }}`
                            window.location.href =
                                redirectUrl; // Redirect atau refresh halaman setelah berhasil
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
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
        });
    </script>
@endpush
