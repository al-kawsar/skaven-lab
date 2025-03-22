@extends('layouts.app-layout')
@section('content')
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-sm-12">
                <div class="page-sub-header">
                    <h3 class="page-title">Add user</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.user.index') }}">user</a></li>
                        <li class="breadcrumb-item active">Add user</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card comman-shadow">
                <div class="card-body">
                    <form id="adduserForm">
                        <div class="row">
                            <div class="col-12">
                                <h5 class="form-title student-info">User Information <span><a href="javascript:;"><i
                                                class="feather-more-vertical"></i></a></span></h5>
                            </div>
                            <div class="col-12">
                                <div class="form-group local-forms">
                                    <label>Name <span class="login-danger">*</span></label>
                                    <input required class="form-control" value="{{ old('name') }}" name="name"
                                        type="text" placeholders="Enter First Name">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group local-forms">
                                    <label>Email <span class="login-danger">*</span></label>
                                    <input required class="form-control" value="{{ old('email') }}" name="email"
                                        type="email" placeholders="Enter Roll Number">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group local-forms">
                                    <label>Password <span class="login-danger">*</span></label>
                                    <input required class="form-control" value="{{ old('password') }}" name="password"
                                        type="password" placeholders="Enter Roll Number">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group local-forms">
                                    <label>Role <span class="login-danger">*</span></label>
                                    <select class="form-control select" name="role_id" required>
                                        <option disabled selected>Pilih Role</option>
                                        @foreach ($roles as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
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
            $('#adduserForm').submit(function(event) {
                event.preventDefault();
                $('#btn-submit').text("Loading...").prop('disabled', true);
                const formData = new FormData($(this)[0]); // Mengambil data form
                const url = "{{ route('admin.user.store') }}";

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
                            const redirectUrl = `{{ route('admin.user.index') }}`
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
                        $('#btn-submit').text("Submit").prop('disabled', false);
                    }
                });
                $('#btn-submit').val = "Submit";
            });
        });
    </script>
@endpush
