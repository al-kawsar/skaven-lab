@extends('layouts.admin-layout')

@section('content')
    {{-- <div class="page-header">
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
    </div> --}}


    {{--     <div class="row">
        <div class="col-xl-3 col-sm-6 col-12 d-flex">
            <div class="card bg-comman w-100">
                <div class="card-body">
                    <div class="db-widgets d-flex justify-content-between align-items-center">
                        <div class="db-info">
                            <h6>Siswa</h6>
                            <h3>{{ $data['siswa'] }}</h3>
                        </div>
                        <div class="db-icon">
                            <img src="/assets/img/icons/dash-icon-01.svg" alt="Dashboard Icon">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 col-12 d-flex">
            <div class="card bg-comman w-100">
                <div class="card-body">
                    <div class="db-widgets d-flex justify-content-between align-items-center">
                        <div class="db-info">
                            <h6>Guru</h6>
                            <h3>{{ $data['guru'] }}</h3>
                        </div>
                        <div class="db-icon">
                            <img src="/assets/img/icons/dash-icon-01.svg" alt="Dashboard Icon">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 col-12 d-flex">
            <div class="card bg-comman w-100">
                <div class="card-body">
                    <div class="db-widgets d-flex justify-content-between align-items-center">
                        <div class="db-info">
                            <h6>Lab</h6>
                            <h3>{{ $data['lab'] }}</h3>
                        </div>
                        <div class="db-icon">
                            <img src="/assets/img/icons/dash-icon-03.svg" alt="Dashboard Icon">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

    <div class="row">
        <div class="col-xl-3 col-sm-6 col-12 d-flex">
            <div class="card bg-comman w-100">
                <div class="card-body">
                    <div class="db-icon">
                        <i class="fas fa-building text-info"></i>
                    </div>
                    <div class="db-widgets mt-3 d-flex justify-content-between align-items-center">
                        <div class="db-info">
                            <h6 class="p-0 m-0 text-muted fw-bold fs-6">Lab Tersedia</h6>
                            <p class="mx-0 my-1 p-0 fw-bold fs-5" id="totalData">{{ $data['totalData'] ?? 0 }}</p>
                            <h6 class="m-0 p-0 text-muted" style="font-size: 14px">Lab tersedia saat ini</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 col-12 d-flex">
            <div class="card bg-comman w-100">
                <div class="card-body">
                    <div class="db-icon">
                        <i class="fas fa-calendar text-warning"></i>
                    </div>
                    <div class="db-widgets mt-3 d-flex justify-content-between align-items-center">
                        <div class="db-info">
                            <h6 class="p-0 m-0 text-muted fw-bold fs-6">Total Peminjaman</h6>
                            <p class="mx-0 my-1 p-0 fw-bold fs-5" id="totalData">{{ $data['totalData'] ?? 0 }}</p>
                            <h6 class="m-0 p-0 text-muted" style="font-size: 14px">Total peminjaman</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 col-12 d-flex">
            <div class="card bg-comman w-100">
                <div class="card-body">
                    <div class="db-icon">
                        <i class="fa fa-calculator text-success"></i>
                    </div>
                    <div class="db-widgets mt-3 d-flex justify-content-between align-items-center">
                        <div class="db-info">
                            <h6 class="p-0 m-0 text-muted fw-bold fs-6">Peminjaman Disetujui</h6>
                            <p class="mx-0 my-1 p-0 fw-bold fs-5" id="totalData">{{ $data['totalData'] ?? 0 }}</p>
                            <h6 class="m-0 p-0 text-muted" style="font-size: 14px">Total peminjaman disetujui</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 col-12 d-flex">
            <div class="card bg-comman w-100">
                <div class="card-body">
                    <div class="db-icon">
                        <i class="fas fa-signal text-danger"></i>
                    </div>
                    <div class="db-widgets mt-3 d-flex justify-content-between align-items-center">
                        <div class="db-info">
                            <h6 class="p-0 m-0 text-muted fw-bold fs-6">Peminjaman Ditolak</h6>
                            <p class="mx-0 my-1 p-0 fw-bold fs-5" id="totalData">{{ $data['totalData'] ?? 0 }}</p>
                            <h6 class="m-0 p-0 text-muted" style="font-size: 14px">Total peminjaman ditolak</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Analytic Chart --}}
    {{-- <div class="row">
        <div class="col-12">

            <div class="card card-chart">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h5 class="card-title">Overview</h5>
                        </div>
                        <div class="col-6">
                            <ul class="chart-list-out">
                                <li><span class="circle-blue"></span>Teacher</li>
                                <li><span class="circle-green"></span>Student</li>
                                <li class="star-menus"><a href="javascript:;"><i class="fas fa-ellipsis-v"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="apexcharts-area"></div>
                </div>
            </div>

        </div>
    </div> --}}
    {{-- <div class="row">
        <div class="col-xl-6 d-flex">

            <div class="card flex-fill student-space comman-shadow">
                <div class="card-header d-flex align-items-center">
                    <h5 class="card-title">Star Students</h5>
                    <ul class="chart-list-out student-ellips">
                        <li class="star-menus"><a href="javascript:;"><i class="fas fa-ellipsis-v"></i></a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table star-student table-hover table-center table-borderless table-striped">
                            <thead class="thead-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th class="text-center">Marks</th>
                                    <th class="text-center">Percentage</th>
                                    <th class="text-end">Year</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-nowrap">
                                        <div>PRE2209</div>
                                    </td>
                                    <td class="text-nowrap">
                                        <a href="profile.html">
                                            <img class="rounded-circle" src="/assets/img/profiles/avatar-02.jpg"
                                                width="25" alt="Star Students">
                                            John Smith
                                        </a>
                                    </td>
                                    <td class="text-center">1185</td>
                                    <td class="text-center">98%</td>
                                    <td class="text-end">
                                        <div>2019</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-nowrap">
                                        <div>PRE1245</div>
                                    </td>
                                    <td class="text-nowrap">
                                        <a href="profile.html">
                                            <img class="rounded-circle" src="/assets/img/profiles/avatar-01.jpg"
                                                width="25" alt="Star Students">
                                            Jolie Hoskins
                                        </a>
                                    </td>
                                    <td class="text-center">1195</td>
                                    <td class="text-center">99.5%</td>
                                    <td class="text-end">
                                        <div>2018</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-nowrap">
                                        <div>PRE1625</div>
                                    </td>
                                    <td class="text-nowrap">
                                        <a href="profile.html">
                                            <img class="rounded-circle" src="/assets/img/profiles/avatar-03.jpg"
                                                width="25" alt="Star Students">
                                            Pennington Joy
                                        </a>
                                    </td>
                                    <td class="text-center">1196</td>
                                    <td class="text-center">99.6%</td>
                                    <td class="text-end">
                                        <div>2017</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-nowrap">
                                        <div>PRE2516</div>
                                    </td>
                                    <td class="text-nowrap">
                                        <a href="profile.html">
                                            <img class="rounded-circle" src="/assets/img/profiles/avatar-04.jpg"
                                                width="25" alt="Star Students">
                                            Millie Marsden
                                        </a>
                                    </td>
                                    <td class="text-center">1187</td>
                                    <td class="text-center">98.2%</td>
                                    <td class="text-end">
                                        <div>2016</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-nowrap">
                                        <div>PRE2209</div>
                                    </td>
                                    <td class="text-nowrap">
                                        <a href="profile.html">
                                            <img class="rounded-circle" src="/assets/img/profiles/avatar-05.jpg"
                                                width="25" alt="Star Students">
                                            John Smith
                                        </a>
                                    </td>
                                    <td class="text-center">1185</td>
                                    <td class="text-center">98%</td>
                                    <td class="text-end">
                                        <div>2015</div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-xl-6 d-flex">

            <div class="card flex-fill comman-shadow">
                <div class="card-header d-flex align-items-center">
                    <h5 class="card-title ">Student Activity </h5>
                    <ul class="chart-list-out student-ellips">
                        <li class="star-menus"><a href="javascript:;"><i class="fas fa-ellipsis-v"></i></a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="activity-groups">
                        <div class="activity-awards">
                            <div class="award-boxs">
                                <img src="/assets/img/icons/award-icon-01.svg" alt="Award">
                            </div>
                            <div class="award-list-outs">
                                <h4>1st place in "Chess”</h4>
                                <h5>John Doe won 1st place in "Chess"</h5>
                            </div>
                            <div class="award-time-list">
                                <span>1 Day ago</span>
                            </div>
                        </div>
                        <div class="activity-awards">
                            <div class="award-boxs">
                                <img src="/assets/img/icons/award-icon-02.svg" alt="Award">
                            </div>
                            <div class="award-list-outs">
                                <h4>Participated in "Carrom"</h4>
                                <h5>Justin Lee participated in "Carrom"</h5>
                            </div>
                            <div class="award-time-list">
                                <span>2 hours ago</span>
                            </div>
                        </div>
                        <div class="activity-awards">
                            <div class="award-boxs">
                                <img src="/assets/img/icons/award-icon-03.svg" alt="Award">
                            </div>
                            <div class="award-list-outs">
                                <h4>Internation conference in "St.John School"</h4>
                                <h5>Justin Leeattended internation conference in "St.John School"</h5>
                            </div>
                            <div class="award-time-list">
                                <span>2 Week ago</span>
                            </div>
                        </div>
                        <div class="activity-awards mb-0">
                            <div class="award-boxs">
                                <img src="/assets/img/icons/award-icon-04.svg" alt="Award">
                            </div>
                            <div class="award-list-outs">
                                <h4>Won 1st place in "Chess"</h4>
                                <h5>John Doe won 1st place in "Chess"</h5>
                            </div>
                            <div class="award-time-list">
                                <span>3 Day ago</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div> --}}
@endsection
