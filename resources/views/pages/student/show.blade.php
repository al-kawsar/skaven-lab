@extends('layouts.app-layout')
@section('content')
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-sm-12">
                <div class="page-sub-header">
                    <h3 class="page-title">Detail Siswa</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('student.index') }}">Siswa</a></li>
                        <li class="breadcrumb-item active">Detail Siswa</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="card comman-shadow">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <h5 class="form-title student-info">Informasi Siswa</h5>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-4 mb-3">
                            <div class="student-img-block text-center">
                                <img src="{{ $data['foto_url'] }}" alt="Foto Siswa" class="img-fluid rounded-circle"
                                    style="width: 150px; height: 150px; object-fit: cover;">
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="student-details">
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr>
                                            <td width="30%"><strong>Nama Lengkap</strong></td>
                                            <td width="5%">:</td>
                                            <td>{{ $data['name'] }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>NIS</strong></td>
                                            <td>:</td>
                                            <td>{{ $data['nis'] }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>NISN</strong></td>
                                            <td>:</td>
                                            <td>{{ $data['nisn'] }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Tanggal Lahir</strong></td>
                                            <td>:</td>
                                            <td>{{ $data['tanggal_lahir'] }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Usia</strong></td>
                                            <td>:</td>
                                            <td>{{ $data['usia'] }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Jenis Kelamin</strong></td>
                                            <td>:</td>
                                            <td>{{ $data['jenis_kelamin'] }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Agama</strong></td>
                                            <td>:</td>
                                            <td>{{ $data['agama'] }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Alamat</strong></td>
                                            <td>:</td>
                                            <td>{{ $data['alamat'] }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="student-submit">
                                <a href="{{ route('student.edit', $data['id']) }}" class="btn btn-warning">Edit</a>
                                <a href="{{ route('student.index') }}" class="btn btn-secondary">Kembali</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
