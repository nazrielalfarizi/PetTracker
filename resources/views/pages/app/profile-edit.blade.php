@extends('layouts.no-nav')

@section('title', 'Edit Profil')

@section('content')

{{-- HEADER NAV --}}
<div class="header-nav d-flex align-items-center justify-content-between">
    <div class="nav-left">
        <a href="{{ route('profile') }}">
            <img src="{{ asset('assets/app/images/icons/ArrowLeft.svg') }}"
                 alt="arrow-left"
                 style="width: 24px;">
        </a>
    </div>
    <div class="nav-center">
        <h1 class="fs-6 mb-0 fw-bold text-truncate">
            Edit Profil
        </h1>
    </div>
    <div class="nav-right" style="width: 24px;"></div>
</div>

<div class="content-wrapper">
    <p class="text-muted mt-2">Perbarui data profil kamu di bawah ini</p>

    <form action="{{ route('profile.update') }}"
          method="POST"
          enctype="multipart/form-data"
          class="mt-4">
        @csrf
        @method('PUT')

        {{-- EMAIL --}}
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email"
                   class="form-control @error('email') is-invalid @enderror"
                   id="email"
                   name="email"
                   value="{{ old('email', auth()->user()->email) }}"
                   readonly>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- NAMA --}}
        <div class="mb-3">
            <label for="name" class="form-label">Nama Lengkap</label>
            <input type="text"
                   class="form-control @error('name') is-invalid @enderror"
                   id="name"
                   name="name"
                   value="{{ old('name', auth()->user()->name) }}">
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- AVATAR --}}
        <div class="mb-3">
            <label for="avatar" class="form-label">Foto Profil</label>

            @if(auth()->user()->resident?->avatar)
                <img src="{{ asset('storage/' . auth()->user()->resident->avatar) }}"
                     class="img-fluid rounded mb-2"
                     style="max-height:120px;">
            @endif

            <input type="file"
                   class="form-control @error('avatar') is-invalid @enderror"
                   id="avatar"
                   name="avatar">
            @error('avatar')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- PHONE --}}
        <div class="mb-3">
            <label for="phone_number" class="form-label">Nomor Telepon</label>
            <input type="text"
                   class="form-control @error('phone_number') is-invalid @enderror"
                   id="phone_number"
                   name="phone_number"
                   value="{{ old('phone_number', auth()->user()->resident?->phone_number) }}">
            @error('phone_number')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- PASSWORD BARU --}}
        <div class="mb-3">
            <label for="password" class="form-label">Password Baru (Opsional)</label>
            <input type="password"
                   class="form-control @error('password') is-invalid @enderror"
                   id="password"
                   name="password">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <small class="text-muted">
                Kosongkan jika tidak ingin mengubah password.
            </small>
        </div>

        <button class="btn btn-primary w-100 mt-2" type="submit">
            Simpan Perubahan
        </button>
    </form>
</div>
@endsection

@section('style')
<style>
.content-wrapper {
    padding-top: 44px; /* supaya form tidak ketimpa header */
}
</style>
@endsection
