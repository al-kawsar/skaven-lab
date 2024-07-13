@extends('layouts.admin-layout')
@section('content')
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-sm-12">
                <div class="page-sub-header">
                    <h3 class="page-title">Add Siswa</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.siswa.index') }}">Siswa</a></li>
                        <li class="breadcrumb-item active">Add Siswa</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card comman-shadow">
                <div class="card-body">
                    <form id="addsiswaForm">
                        <div class="row">
                            <div class="col-12">
                                <h5 class="form-title student-info">Siswa Information <span><a href="javascript:;"><i
                                                class="feather-more-vertical"></i></a></span></h5>
                            </div>
                            <div class="col-12">
                                <div class="form-group local-forms">
                                    <label>Name <span class="login-danger">*</span></label>
                                    <input required class="form-control" name="name" type="text"
                                        placeholders="Enter First Name">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group local-forms">
                                    <label>Nis <span class="login-danger">*</span></label>
                                    <input required class="form-control" name="nis" type="text"
                                        placeholders="Enter First Name">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group local-forms">
                                    <label>Nisn </label>
                                    <input required class="form-control" name="nisn" type="text"
                                        placeholders="Enter Roll Number">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group local-forms">
                                    <label>Alamat </label>
                                    <input required class="form-control" name="alamat" type="text"
                                        placeholders="Enter Roll Number">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group local-forms">
                                    <label>Jenis Kelamin <span class="login-danger">*</span></label>
                                    <select class="form-control select" name="jenis_kelamin" required>
                                        <option value="1">Laki Laki</option>
                                        <option value="2">Perempuan</option>
                                    </select>
                                </div>
                            </div>
                              <div class="col-12">
                                <div class="form-group local-forms">
                                    <label>Agama </label>
                                    <input required class="form-control" name="agama" type="text"
                                        placeholders="Enter Roll Number">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group local-forms calendar-icon">
                                    <label>Tanggal Lahir <span class="login-danger">*</span></label>
                                    <input class="form-control datetimepicker" required name="tgl_lahir" type="text" placeholder="DD-MM-YYYY">
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
$(document).ready(function () {
        $('#addsiswaForm').submit(function (event) {
            event.preventDefault();
            $('#btn-submit').text("Loading...").prop('disabled', true);
            const formData = new FormData($(this)[0]); // Mengambil data form
            const url = "{{ route('admin.siswa.store') }}";

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
                success: function (response) {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: response.message,
                        icon: 'success'
                    }).then(() => {
                        const redirectUrl = `{{ route('admin.siswa.index') }}`
                        window.location.href = redirectUrl; // Redirect atau refresh halaman setelah berhasil
                    });
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                    Swal.fire({
                        title: 'Error!',
                        text: xhr.responseJSON.message,
                        icon: 'error'
                    });
                },
                complete: function () {
                    $('#btn-submit').text("Submit").prop('disabled', false);
                }
            });
            $('#btn-submit').val = "Submit";
        });
    });
</script>
@endpush
