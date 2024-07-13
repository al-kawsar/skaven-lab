@extends('layouts.admin-layout')
@section('content')
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-sm-12">
                <div class="page-sub-header">
                    <h3 class="page-title">Tambah Lab</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.lab.index') }}">Lab</a></li>
                        <li class="breadcrumb-item active">Tambah Lab</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card comman-shadow">
                <div class="card-body">
                    <form id="addLabForm" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group local-forms">
                                    <label for="name">Nama Lab <span class="login-danger">*</span></label>
                                    <input id="name" required class="form-control" name="name" type="text"
                                        value="{{ old('name') }}" placeholders="Enter First Name">
                                </div>
                            </div>
                            <div class="col-12 col-lg-6">
                                <div class="form-group local-forms">
                                    <label for="capacity">Kapasitas <span class="login-danger">*</span></label>
                                    <input id="capacity" required class="form-control" name="capacity" type="text"
                                        value="{{ old('capacity') }}" placeholders="Enter Roll Number">
                                </div>
                            </div>
                            <div class="col-12 col-lg-6">
                                <div class="form-group local-forms">
                                    <label for="location">Lokasi <span class="login-danger">*</span></label>
                                    <input id="location" required class="form-control" name="location" type="text"
                                        value="{{ old('location') }}" placeholders="Enter First Name">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group local-forms">
                                    <label for="description">Deskripsi <span class="login-danger">*</span></label>
                                    <textarea id="description" class="form-control" rows="5" name="facilities" required></textarea>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="row align-items-center justify-content-center p-0 m-0">
                                    <div
                                        class="form-group service-upload w-50 position-relative d-flex flex-column gap-2 align-items-center">
                                        <label class="text-muted">Foto Thumbnail Lab</label>
                                        <input type="file" accept=".png,.jpeg,.jpg,.webp" name="thumbnail"
                                            id="thumbnail">
                                        <img id="thumbnail-preview" src="" alt="Thumbnail Preview"
                                            style="object-fit: cover; display: none; width: 100%; height: 250px">
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="student-submit">
                                    <button type="submit" id="btn-submit" class="btn btn-primary">Submit</button>
                                </div>
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
            $('#thumbnail').change(function(event) {
                console.log('anjay');
                var input = event.target;

                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function(e) {
                        $('#thumbnail-preview').attr('src', e.target.result).show();
                    }

                    reader.readAsDataURL(input.files[0]);
                } else {
                    $('#thumbnail-preview').hide();
                }
            });

            $('#addLabForm').submit(function(event) {
                event.preventDefault(); // Mencegah pengiriman form default
                $('#btn-submit').text("Loading...");
                const formData = new FormData($(this)[0]); // Mengambil data form
                const url = "{{ route('admin.lab.store') }}";

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
