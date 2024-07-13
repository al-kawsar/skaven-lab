@extends('layouts.admin-layout')
@section('title', 'Pengaturan | Akun')
@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col">
                <h3 class="page-title">Pengaturan</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active">Pengaturan</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-3 col-md-4">
            <div class="widget settings-menu">
                <ul>
                    <li class="nav-item">
                        <a href="{{ route('settings.general') }}" class="nav-link">General</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('settings.security') }}" class="nav-link active">Keamanan</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="col-xl-9 col-md-8">
            <div class="card invoices-settings-card">
                <div class="card-header">
                    <h5 class="card-title">Ganti Password</h5>
                </div>
                <div class="card py-0 my-0">
                    <div class="card-body py-0 my-0">
                        @if (session()->has('type') && session('type') == 'alert' && session()->has('status'))
                            @switch(session('status'))
                                @case('success')
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        {{ session('message') }}.
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                                    </div>
                                @break
                            @endswitch
                        @endif

                        @if (session()->has('errors'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ $errors->first() }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="card-body">

                    <form method="post" action="{{ route('auth.change-password') }}" class="invoices-settings-form">
                        @csrf
                        @method('POST')
                        <div class="row align-items-center form-group">
                            <label for="name" class="col-sm-3 col-form-label input-label">Kata Sandi Saat Ini</label>
                            <div class="col-sm-9">
                                <input required type="password" class="form-control" name="old-password">
                            </div>
                        </div>
                        <div class="row align-items-center form-group">
                            <label for="name" class="col-sm-3 col-form-label input-label">Password</label>
                            <div class="col-sm-9">
                                <input required type="password" class="form-control" name="password">
                            </div>
                        </div>
                        <div class="row align-items-center form-group">
                            <label for="name" class="col-sm-3 col-form-label input-label">Konfirmasi Password</label>
                            <div class="col-sm-9">
                                <input required type="password" name="password_confirmation" class="form-control">
                            </div>
                        </div>
                        <div class="invoice-setting-btn text-end">
                            <button type="submit" class="btn btn-primary-save-bg">Simpan Perubahan</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
