@extends('layouts.admin-layout')
@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-sub-header">
                    <h3 class="page-title">My Profile</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="students.html">Admin</a></li>
                        <li class="breadcrumb-item active">Profile</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="student-profile-head">
                        <div class="row align-items-center justify-content-between">
                            <div class="col-md-12">
                                <div class="profile-user-box p-2">
                                    <div class="profile-user-img">
                                        <img src="{{ auth()->user()->photo ?? 'https://www.gravatar.com/avatar/ddc96cd95c37f4aaf1e1d4d4f891d6cf?s=200&d=mp' }}"
                                            alt="Profile">
                                        {{-- <div class="form-group students-up-files profile-edit-icon mb-0">
                                            <div class="uplod d-flex">
                                                <label class="file-upload profile-upbtn mb-0">
                                                    <i class="feather-edit-3"></i><input required type="file">
                                                </label>
                                            </div>
                                        </div> --}}
                                    </div>
                                    <div class="names-profiles">
                                        <h4>{{ auth()->user()->name }}</h4>
                                        <h5>{{ auth()->user()->email }}</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
