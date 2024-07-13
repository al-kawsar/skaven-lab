@extends('layouts.admin-layout')
@section('content')
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-sm-12">
                <div class="page-sub-header">
                    <h3 class="page-title">Update Siswa</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.siswa.index') }}">Siswa</a></li>
                        <li class="breadcrumb-item active">Update Siswa</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="card comman-shadow">
                <div class="card-body">
                    <form action="{{ route('admin.siswa.update', $siswa->id) }}" method="post">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-12">
                                <h5 class="form-title student-info">Siswa Information <span><a href="javascript:;"><i
                                                class="feather-more-vertical"></i></a></span></h5>
                            </div>
                            <div class="col-12">
                                <div class="form-group local-forms">
                                    <label>Name <span class="login-danger">*</span></label>
                                    <input required class="form-control" name="name" type="text"
                                       value="{{ old('name', $siswa->name) }}" placeholders="Enter First Name">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group local-forms">
                                    <label>Nis <span class="login-danger">*</span></label>
                                    <input required class="form-control" name="nis" type="text"
                                       value="{{ old('nis', $siswa->nis) }}" placeholders="Enter First Name">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group local-forms">
                                    <label>Nisn </label>
                                    <input required class="form-control" name="nisn" type="text"
                                       value="{{ old('nisn', $siswa->nisn) }}" placeholders="Enter Roll Number">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group local-forms">
                                    <label>Alamat </label>
                                    <input required class="form-control" name="alamat" type="text"
                                       value="{{ old('alamat', $siswa->alamat) }}" placeholders="Enter Roll Number">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group local-forms">
                                    <label>Jenis Kelamin <span class="login-danger">*</span></label>
                                    <select class="form-control select" name="jenis_kelamin" required>
                                        <option value="1" @selected($siswa->status == 'l')>Laki Laki</option>
                                        <option value="2" @selected($siswa->status == 'p')>Perempuan</option>
                                    </select>
                                </div>
                            </div>
                              <div class="col-12">
                                <div class="form-group local-forms">
                                    <label>Agama </label>
                                    <input required class="form-control" name="agama" type="text"
                                       value="{{ old('agama', $siswa->agama) }}" placeholders="Enter Roll Number">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group local-forms calendar-icon">
                                    <label>Tanggal Lahir <span class="login-danger">*</span></label>
                                    <input class="form-control datetimepicker" required name="tgl_lahir" type="text" value="{{old('tgl_lahir', $siswa->tgl_lahir)}}" placeholder="DD-MM-YYYY">
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