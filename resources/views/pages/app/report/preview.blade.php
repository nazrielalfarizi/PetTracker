@extends('layouts.no-nav')

@section('title', 'Preview Foto')

@section('content')

@php
    // Tetap ambil dari request untuk inisialisasi awal
    $type = request('type', 'kehilangan');
@endphp

<div class="d-flex flex-column justify-content-center align-items-center">
    <h4 class="home-headline">
        Preview
    </h4>
    <img id="image-preview" class="img-fluid rounded-2 mb-4" style="max-height: 400px; object-fit: cover;">

    {{-- Di preview.blade.php --}}
    <p class="text-muted">
        Foto akan digunakan untuk laporan
        <strong id="type-text" class="text-capitalize {{ $type === 'kehilangan' ? 'text-danger' : 'text-success' }}">
            {{ $type }}
        </strong>
    </p>
    <br>
    <div class="d-flex justify-content-center gap-3 w-100 px-4">
        {{-- Gunakan variabel $type untuk link awal --}}
        <a href="{{ route('user.report.take', ['type' => $type]) }}" id="btn-ulangi" class="btn btn-outline-primary w-50">
            Ulangi Foto
        </a>
        <a href="{{ route('user.report.create', ['type' => $type]) }}" id="btn-gunakan" class="btn btn-primary w-50">
            Gunakan Foto
        </a>
    </div>
</div>
@endsection

@section('scripts')
@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // 1. Ambil data dari localStorage
        const imageData = localStorage.getItem('image');

        // 2. Ambil type dari URL (seperti yang kamu kirim dari halaman take)
        const urlParams = new URLSearchParams(window.location.search);
        let currentType = urlParams.get('type') || localStorage.getItem('report_type') || 'kehilangan';

        // 3. Masukkan gambar ke elemen <img>
        const imgPreview = document.getElementById('image-preview');
        if (imageData && imgPreview) {
            imgPreview.src = imageData;
        } else {
            // Jika foto hilang, balikkan ke halaman ambil foto
            window.location.href = "{{ route('user.report.take') }}?type=" + currentType;
        }

        // 4. Update teks tipe (Kehilangan/Temuan)
        const typeText = document.getElementById('type-text');
        if (typeText) {
            typeText.innerText = currentType;
            // Beri warna: Merah untuk kehilangan, Hijau untuk temuan
            typeText.classList.add(currentType === 'kehilangan' ? 'text-danger' : 'text-success');
        }

        // 5. Update link tombol Gunakan & Ulangi
        document.getElementById('btn-ulangi').href = "{{ route('user.report.take') }}?type=" + currentType;
        document.getElementById('btn-gunakan').href = "{{ route('user.report.create') }}?type=" + currentType;
    });
</script>
@endsection
@endsection
