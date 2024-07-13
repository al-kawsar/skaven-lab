@extends('layouts.admin-layout')

@section('content')
    <div class="page-header">

        <div class="row">
            <div class="col-sm-12">
                <div class="page-sub-header">
                    <h3 class="page-title">Welcome {{ auth()->user()->name }}!</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-4 col-sm-6 col-12 d-flex">
            <div class="card bg-comman w-100">
                <div class="card-body">
                    <div class="db-icon">
                        <i class="fas fa-building text-info"></i>
                    </div>
                    <div class="db-widgets mt-3 d-flex justify-content-between align-items-center">
                        <div class="db-info">
                            <h6 class="p-0 m-0 text-muted fw-bold fs-6">Total Lab</h6>
                            <p class="mx-0 my-1 p-0 fw-bold fs-5" id="totalData">{{ $data['totalLab'] ?? 0 }}</p>
                            <h6 class="m-0 p-0 text-muted" style="font-size: 14px">Total lab yang dimiliki</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-sm-6 col-12 d-flex">
            <div class="card bg-comman w-100">
                <div class="card-body">
                    <div class="db-icon">
                        <i class="fas fa-calendar text-warning"></i>
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
                        <i class="fa fa-calculator text-success"></i>
                    </div>
                    <div class="db-widgets mt-3 d-flex justify-content-between align-items-center">
                        <div class="db-info">
                            <h6 class="p-0 m-0 text-muted fw-bold fs-6">Total Peminjaman</h6>
                            <p class="mx-0 my-1 p-0 fw-bold fs-5" id="totalData">{{ $data['totalBorrowing'] ?? 0 }}</p>
                            <h6 class="m-0 p-0 text-muted" style="font-size: 14px">Total peminjaman dari awal</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
