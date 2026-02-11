@extends('layouts.no-nav')

@section('title', 'Tambah Laporan')

@section('content')
@php
    $type = request('type'); // kehilangan | temuan | null
@endphp

<div class="header-nav d-flex align-items-center justify-content-between">
        <div class="nav-left">
            {{-- Kondisi: Jika rute saat ini adalah detail yang dibuka dari my-report --}}
            {{-- Atau lebih aman, cek jika rute sebelumnya adalah my-report --}}
            @php
                $previousRoute = url()->previous();
                $myReportUrl = route('user.report.my-report');

                // Jika URL sebelumnya mengandung 'my-report', maka arahkan balik ke sana
                $backUrl = str_contains($previousRoute, 'my-report') ? $myReportUrl : route('home');
            @endphp

            <a href="{{ $backUrl }}">
                <img src="{{ asset('assets/app/images/icons/ArrowLeft.svg') }}" alt="arrow-left" style="width: 24px;">
            </a>
        </div>
        <div class="nav-center">
            <h1 class="fs-6 mb-0 fw-bold text-truncate">
                Tambah Laporan {{ $type === 'kehilangan' ? 'Kehilangan' : 'Temuan' }}
            </h1>
        </div>
        <div class="nav-right" style="width: 24px;">
            {{-- Kosong untuk keseimbangan flexbox --}}
        </div>
    </div>

<div class="content-wrapper">
<form action="{{ route('user.report.store') }}"
      method="POST"
      class="mt-4"
      enctype="multipart/form-data">
    @csrf
    {{-- TYPE --}}
    <input type="hidden" name="type" value="{{ $type }}">
    {{-- TITLE --}}
    <div class="mb-3">
        <label class="form-label">Judul Laporan</label>
        <input type="text"
               class="form-control @error('title') is-invalid @enderror"
               name="title"
               value="{{ old('title') }}">
        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>


    {{-- IMAGE --}}
    <div class="mb-3">
        <label class="form-label">
            Bukti Laporan
            @if ($type === 'kehilangan')
                <span class="text-danger">*</span>
            @endif
        </label>

        <input type="file"
            class="form-control @error('image') is-invalid @enderror"
            id="image"
            name="image"
            accept="image/*"
            {{ $type === 'kehilangan' ? 'required' : '' }}>

        <img id="image-preview"
            class="img-fluid rounded-2 mt-2 border"
            style="display:none; max-height:250px; width:100%; object-fit:cover;">

        @error('image')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- DESCRIPTION --}}
    <div class="mb-3">
        <label class="form-label">Deskripsi Laporan</label>
        <textarea class="form-control @error('description') is-invalid @enderror"
                  name="description"
                  rows="4">{{ old('description') }}</textarea>
        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- KEHILANGAN --}}
    @if ($type === 'kehilangan')
        <div class="mb-3">
            <input type="hidden" name="latitude" id="lat">
            <input type="hidden" name="longitude" id="lng">
            <label class="form-label">Lokasi Terakhir Terlihat</label>

            <div id="map" class="mb-2"></div>

            <input type="text"
                   class="form-control @error('last_seen_location') is-invalid @enderror"
                   name="last_seen_location"
                   value="{{ old('last_seen_location') }}"
                   placeholder="Nama jalan, patokan, atau ciri tempat...">
            @error('last_seen_location') <div class="invalid-feedback">{{ $message }}</div> @enderror

            <small class="text-muted">Klik/Geser pin pada peta untuk menentukan lokasi tepatnya.</small>
        </div>

        <div class="mb-3">
            <label class="form-label">Ciri-ciri Hewan</label>
            <textarea class="form-control @error('pet_characteristics') is-invalid @enderror"
                      name="pet_characteristics"
                      rows="3">{{ old('pet_characteristics') }}</textarea>
            @error('pet_characteristics') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    @endif

    {{-- TEMUAN --}}
    @if ($type === 'temuan')
        <input type="hidden" id="lat" name="latitude">
        <input type="hidden" id="lng" name="longitude">
        <div class="mb-3">
            <label class="form-label">Kondisi Hewan</label>
            <textarea class="form-control @error('pet_condition') is-invalid @enderror"
                      name="pet_condition"
                      rows="3">{{ old('pet_condition') }}</textarea>
            @error('pet_condition') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Ciri-ciri Hewan</label>
            <textarea class="form-control @error('pet_characteristics') is-invalid @enderror"
                      name="pet_characteristics"
                      rows="3">{{ old('pet_characteristics') }}</textarea>
            @error('pet_characteristics') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        {{-- MAP --}}
        <div class="mb-3">
            <label for="map" class="form-label">Lokasi Ditemukan</label>
            <div id="map"></div>
        </div>
         <input type="text"
                   class="form-control @error('last_seen_location') is-invalid @enderror"
                   name="last_seen_location"
                   value="{{ old('last_seen_location') }}"
                   placeholder="Nama jalan, patokan, atau ciri tempat...">
            @error('last_seen_location') <div class="invalid-feedback">{{ $message }}</div> @enderror

    @endif


    {{-- ADDRESS --}}
    <div class="mb-3">
        <label for="address" class="form-label">Alamat Lengkap</label>
        <textarea class="form-control @error('address') is-invalid @enderror"
                  name="address"
                  id="address"
                  rows="3">{{ old('address') }}</textarea>
        @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
        <p class="text-description">
            Isi form di bawah ini dengan benar agar laporan dapat dipantau dengan baik.
        </p>
    </div>
    <button class="btn btn-primary w-100 mt-2">
        Kirim Laporan
    </button>
</form>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const type = "{{ $type }}";
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('image-preview');

    // =============================
    // JIKA KEHILANGAN → MANUAL INPUT
    // =============================
    if (type === 'kehilangan') {

        imageInput.style.display = 'block';

        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                imagePreview.src = URL.createObjectURL(file);
                imagePreview.style.display = 'block';
            }
        });

        return;
    }

    // =============================
    // JIKA TEMUAN → DARI KAMERA
    // =============================
    const imageBase64 = localStorage.getItem('image');

    if (!imageBase64) {
        imageInput.style.display = 'block';
        return;
    }

    function base64ToBlob(base64, mime) {
        const byteString = atob(base64.split(',')[1]);
        const ab = new ArrayBuffer(byteString.length);
        const ia = new Uint8Array(ab);

        for (let i = 0; i < byteString.length; i++) {
            ia[i] = byteString.charCodeAt(i);
        }

        return new Blob([ab], { type: mime });
    }

    const blob = base64ToBlob(imageBase64, 'image/jpeg');
    const file = new File([blob], 'image.jpg', { type: 'image/jpeg' });

    const dataTransfer = new DataTransfer();
    dataTransfer.items.add(file);
    imageInput.files = dataTransfer.files;

    imagePreview.src = URL.createObjectURL(file);
    imagePreview.style.display = 'block';

    // sembunyikan input karena sudah otomatis
    imageInput.style.display = 'none';
});
</script>
@endsection
@section('style')
<style>
.content-wrapper {
    padding-top: 24px; /* lebih aman dari 64px */
}
</style>
@endsection
