@extends('layouts.admin-layout')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card comman-shadow">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-5">
                            <img src="{{ $lab->file->path_name }}" alt="{{ $lab->name }}" class="img-fluid rounded-3 w-100"
                                style="height: 250px; object-fit: cover">
                        </div>
                        <div class="col-md-7">
                            <div class="d-flex flex-column gap-2">
                                <div class="title-lab fs-4 text-primary fw-bold text-capitalize mt-2 mt-md-0">
                                    {{ $lab->name }}</div>
                                <div class="edit-options">
                                    <div class="edit-delete-btn">
                                        <div class="d-flex gap-2">
                                            <a class="d-block badge text-success text-uppercase">{{ $lab->status }}</a>
                                            <a
                                                class="d-block badge bg-primary-light text-uppercase">{{ $lab->location }}</a>
                                            <a class="d-block badge badge-soft-primary text-uppercase">{{ $lab->capacity }}
                                                Orang</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="lab-desc">{{ $lab->facilities }}</div>
                            </div>

                        </div>
                    </div>
                    <div class="lab-photo mt-4">
                        <div class="fs-3 fw-bold">Foto Ruangan</div>
                        <hr class="mx-0 p-0 my-2 mb-4">

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-4">
                                    <img src="{{ $lab->file->path_name }}" alt="{{ $lab->name }}"
                                        class="img-fluid rounded-3 w-100" style="height: 250px; object-fit: cover">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
