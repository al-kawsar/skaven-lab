@extends('layouts.admin-layout')
@section('content')
    <div class="row">

        @foreach ($data as $lab)
            <div class="col-md-6 col-xl-4 col-sm-12">
                <div class="blog grid-blog flex-fill">
                    <div class="blog-image">
                        <img class="img-fluid" src="{{ $lab['thumbnail'] }}" alt="Thumbnail Image | {{ $lab['name'] }}">
                    </div>
                    <div class="blog-content d-flex flex-column gap-2">
                        <h3 class="blog-title p-0 m-0">{{ $lab['name'] }}</h3>
                        <div class="row">
                            <div class="edit-options">
                                <div class="edit-delete-btn">
                                    <a
                                        class="d-inline-block badge {{ $lab['status'] == 'tersedia' ? 'text-success' : 'badge-danger' }}  py-2 mb-2 text-uppercase">{{ $lab['status'] }}</a>
                                    <div class="d-flex gap-2">
                                        <a class="d-block badge bg-primary-light text-uppercase">{{ $lab['location'] }}</a>
                                        <a class="d-block badge badge-soft-primary text-uppercase">{{ $lab['capacity'] }}
                                            Orang</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- <ul class="entry-meta meta-item">
            <li>
                <div class="post-author">
                <a href="profile.html">
                <img src="assets/img/profiles/avatar-01.jpg" alt="Post Author">
                <span>
                <span class="post-title">Vincent</span>
                <span class="post-date"><i class="far fa-clock"></i> 4 Dec 2022</span>
                </span>
                </a>
                </div>
            </li>
        </ul> --}}
                        <div class="row">
                            <div class="edit-options">
                                <div class="edit-delete-btn d-flex gap-2 flex-column w-100">
                                    <a @if ($lab['status'] == 'tersedia') href="{{ route('lab.borrow', $lab['id']) }}" @endif
                                        class="text-center bg-info text-white fs-6 py-2 d-block">{{ $lab['status'] == 'tersedia' ? 'Pinjam Sekarang' : 'Sedang Dipinjam' }}</a>
                                    <a href="{{ route('lab.show', $lab['id']) }}"
                                        class="text-center bg-light text-dark fs-6 py-2 d-block">Detail</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach



        <div class="row ">
            <div class="col-md-12">
                <div class="pagination-tab  d-flex justify-content-center">
                    <ul class="pagination mb-0">
                        <li class="page-item disabled">
                            <a class="page-link" hrefl="#" tabindex="-1"><i
                                    class="feather-chevron-left mr-2"></i>Previous</a>
                        </li>
                        <li class="page-item"><a class="page-link" href="#">1</a></li>
                        <li class="page-item active">
                            <a class="page-link" href="#">2 <span class="sr-only">(current)</span></a>
                        </li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item"><a class="page-link" href="#">4</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#">Next<i class="feather-chevron-right ml-2"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    @endsection
