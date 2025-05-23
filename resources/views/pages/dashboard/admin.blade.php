@extends('layouts.app-layout')

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-sub-header">
                    <h3 class="page-title fw-bold">Welcome {{ auth()->user()->name }}!</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    {{-- @dump($errors->any()) --}}
    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-xl-4 col-sm-6 col-12 d-flex">
            <div class="card bg-comman w-100">
                <div class="card-body">
                    <div class="db-icon">
                        <i class="fas fa-box-open text-info"></i>
                    </div>
                    <div class="db-widgets mt-3 d-flex justify-content-between align-items-center">
                        <div class="db-info">
                            <h6 class="p-0 m-0 text-muted fw-bold fs-6">Total Barang</h6>
                            <p class="mx-0 my-1 p-0 fw-bold fs-5">{{ $data['totalItemLoan'] }}</p>
                            <h6 class="m-0 p-0 text-muted" style="font-size: 14px">Total barang yang dimiliki</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-sm-6 col-12 d-flex">
            <div class="card bg-comman w-100">
                <div class="card-body">
                    <div class="db-icon">
                        <i class="fas fa-user-friends text-secondary"></i>
                    </div>
                    <div class="db-widgets mt-3 d-flex justify-content-between align-items-center">
                        <div class="db-info">
                            <h6 class="p-0 m-0 text-muted fw-bold fs-6">Total Pengguna</h6>
                            <p class="mx-0 my-1 p-0 fw-bold fs-5" id="totalData">{{ $data['totalUser'] ?? 0 }}</p>
                            <h6 class="m-0 p-0 text-muted" style="font-size: 14px">Total pengguna saat ini</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-sm-6 col-12 d-flex">
            <div class="card bg-comman w-100">
                <div class="card-body">
                    <div class="db-icon">
                        <i class="fa fa-calendar-check text-success"></i>
                    </div>
                    <div class="db-widgets mt-3 d-flex justify-content-between align-items-center">
                        <div class="db-info">
                            <h6 class="p-0 m-0 text-muted fw-bold fs-6">Total Peminjaman Ruangan</h6>
                            <p class="mx-0 my-1 p-0 fw-bold fs-5" id="totalData">{{ $data['totalRoomBookings'] ?? 0 }}</p>
                            <h6 class="m-0 p-0 text-muted" style="font-size: 14px">Total peminjaman dari awal</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-sm-6 col-12 d-flex">
            <div class="card bg-comman w-100">
                <div class="card-body">
                    <div class="db-icon">
                        <i class="fas fa-tags text-warning"></i>
                    </div>
                    <div class="db-widgets mt-3 d-flex justify-content-between align-items-center">
                        <div class="db-info">
                            <h6 class="p-0 m-0 text-muted fw-bold fs-6">Total Guru</h6>
                            <p class="mx-0 my-1 p-0 fw-bold fs-5" id="totalData">{{ $data['totalTeacher'] ?? 0 }}</p>
                            <h6 class="m-0 p-0 text-muted" style="font-size: 14px">Total pengguna saat ini</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-sm-6 col-12 d-flex">
            <div class="card bg-comman w-100">
                <div class="card-body">
                    <div class="db-icon">
                        <i class="fas fa-tags text-warning"></i>
                    </div>
                    <div class="db-widgets mt-3 d-flex justify-content-between align-items-center">
                        <div class="db-info">
                            <h6 class="p-0 m-0 text-muted fw-bold fs-6">Total Siswa</h6>
                            <p class="mx-0 my-1 p-0 fw-bold fs-5" id="totalData">{{ $data['totalStudent'] ?? 0 }}</p>
                            <h6 class="m-0 p-0 text-muted" style="font-size: 14px">Total pengguna saat ini</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-sm-6 col-12 d-flex">
            <div class="card bg-comman w-100">
                <div class="card-body">
                    <div class="db-icon">
                        <i class="fas fa-hand-holding text-danger"></i>
                    </div>
                    <div class="db-widgets mt-3 d-flex justify-content-between align-items-center">
                        <div class="db-info">
                            <h6 class="p-0 m-0 text-muted fw-bold fs-6">Total Peminjaman Barang</h6>
                            <p class="mx-0 my-1 p-0 fw-bold fs-5" id="totalData">{{ $data['totalEquipmentLoan'] ?? 0 }}</p>
                            <h6 class="m-0 p-0 text-muted" style="font-size: 14px">Total peminjaman barang</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions & Alerts -->
    <div class="row">
        <!-- Quick Actions -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Aksi Cepat</h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <a href="{{ route('borrowing.lab.index') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-calendar-plus me-2 text-primary"></i> Ajukan Peminjaman Ruangan
                        </a>
                        <a href="{{ route('borrowing.lab.admin.index') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-tasks me-2 text-success"></i> Kelola Peminjaman Ruangan
                        </a>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Notifikasi Penting</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <span class="fw-bold">Pemeliharaan:</span> Beberapa ruangan akan ditutup untuk pemeliharaan pada
                        akhir pekan.
                    </div>
                    <div class="alert alert-info" role="alert">
                        <i class="fas fa-info-circle me-2"></i>
                        <span class="fw-bold">Pengumuman:</span> Jadwal peminjaman baru untuk semester depan telah
                        tersedia.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Statistik</h5>
                </div>
                <div class="card-body">
                </div>
            </div>
        </div>


        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Barang Terpopuler</h5>
                </div>
                <div class="card-body">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Aktivitas Terbaru</h5>
                    </div>
                    <div class="card-body">
                    </div>
                </div>
            </div>
        </div>
    @endsection
