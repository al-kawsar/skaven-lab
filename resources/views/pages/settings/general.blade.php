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
                        <a href="{{ route('settings.general') }}" class="nav-link active">General</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('settings.security') }}" class="nav-link">Keamanan</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="col-xl-9 col-md-8">
            <div class="card invoices-settings-card">
                <div class="card-header">
                    <h5 class="card-title">Informasi Profil</h5>
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

                        @if (count($errors) > 1)
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <ul class="m-0 p-0">
                                    @foreach ($errors->all() as $message)
                                        <li class="p-0 m-0">• {{ $message }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @elseif(session()->has('errors'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ $errors->first() }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="card-body">

                    <form method="post" action="{{ route('admin.profile.update') }}" class="invoices-settings-form">
                        @csrf
                        @method('PUT')

                        <div class="row align-items-center form-group">
                            <label for="name" class="col-sm-3 col-form-label input-label">Nama</label>
                            <div class="col-sm-9">
                                <input id="name" type="text" class="form-control" name="name"
                                    value="{{ auth()->user()->name }}">
                            </div>
                        </div>
                        <div class="row align-items-center form-group">
                            <label for="email" class="col-sm-3 col-form-label input-label">Email</label>
                            <div class="col-sm-9">
                                <input id="email" type="email" class="form-control" name="email"
                                    value="{{ auth()->user()->email }}">
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
