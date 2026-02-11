@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
       <div class="d-flex flex-column justify-content-center align-items-center gap-2">
            {{-- KONDISI AVATAR --}}
            @if(Auth::user()->resident && Auth::user()->resident->avatar)
                <img src="{{ asset('storage/' . Auth::user()->resident->avatar) }}" alt="avatar" class="avatar">
            @else
                {{-- Pastikan Anda memiliki gambar default di folder assets --}}
                <img src="{{ asset('assets/app/images/default.jpg') }}" alt="avatar default" class="avatar">
            @endif

            <h5>{{ Auth::user()->name }}</h5>
            <h6>{{ Auth::user()->email }}</h5>
            <p>No Telp : {{ Auth::user()->resident->phone_number }}</p>
        </div>

        <div class="row mt-4">
            <div class="col-6">
                <div class="card profile-stats">
                    <div class="card-body">
                        <h5 class="card-title">{{ $activeReportsCount }}</h5>
                        <p class="card-text">Laporan Aktif</p>
                    </div>
                </div>
            </div>

            <div class="col-6">
                <div class="card profile-stats">
                    <div class="card-body">
                        <h5 class="card-title">{{ $finishedReportsCount }}</h5>
                        <p class="card-text">Laporan Selesai</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <div class="list-group list-group-flush">
                <a href="{{ route('profile.edit') }}"
                    class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-3">
                        <i class="fa-solid fa-user"></i>
                        <p class="fw-light">Edit Profil</p>
                    </div>
                    <i class="fa-solid fa-chevron-right"></i>
                </a>
            </div>

            <div class="mt-4">

                <button class="btn btn-outline-danger w-100 rounded-pill" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                    Keluar
                </button>
            </div>
        </div>
@endsection
