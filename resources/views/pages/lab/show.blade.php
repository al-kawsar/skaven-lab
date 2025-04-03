@extends('layouts.app-layout')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card comman-shadow">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-5">
                            <img src="{{ $lab->file->path_name ?? '' }}" alt="{{ $lab->name }}"
                                class="img-fluid rounded-3 w-100" style="height: 250px; object-fit: cover">
                        </div>
                        <div class="col-md-7 position-relative">
                            @if (auth()->user()->role != 'user')
                                <div class="position-absolute end-0">
                                    <a href="{{ url()->previous() }}" class="btn btn-outline-primary btn-sm me-2">
                                        <i class="fas fa-arrow-left"></i> Kembali
                                    </a>
                                    <div class="dropdown dropdown-action p-0 m-0 d-inline-block">
                                        <a href="javascript:;" class="btn btn-sm" data-bs-toggle="dropdown"
                                            aria-expanded="false"><i class="fas fa-ellipsis-v"></i></a>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item" href="{{ route('labs.edit', $lab->id) }}"><i
                                                    class="feather-edit"></i> Edit</a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="d-flex flex-column gap-2">
                                <div class="title-lab fs-4 text-primary fw-bold text-capitalize mt-2 mt-md-0">
                                    {{ $lab->name }}</div>
                                <div class="edit-options">
                                    <div class="edit-delete-btn">
                                        <div class="d-flex gap-2">
                                            <a class="d-block badge text-success text-uppercase">{{ $lab->status }}</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="lab-desc">{!! $lab->facilities !!}</div>
                            </div>

                        </div>
                    </div>
                    <div class="lab-photo mt-4">
                        <div class="fs-3 fw-bold">Foto Ruangan</div>
                        <hr class="mx-0 p-0 my-2 mb-4">

                        <!-- Lab Slider Images -->
                        <div class="row">
                            @foreach ($lab->sliderImages as $slider)
                                <div class="col-md-4">
                                    <div class="mb-4">
                                        <img src="{{ $slider->file->full_path }}" alt="Lab Image"
                                            class="img-fluid rounded-3 w-100" style="height: 250px; object-fit: cover">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
