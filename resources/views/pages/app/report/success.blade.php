@extends('layouts.no-nav')

@section('title', 'Laporan Berhasil Dikirim')

@section('content')
<div class="d-flex flex-column justify-content-center align-items-center vh-100 px-4">
    {{-- Tambahkan ukuran pada lottie agar muncul --}}
    <div id="lottie" style="width: 250px; height: 250px;"></div>

    <h6 class="fw-bold text-center mb-2">Yeay! Laporan kamu berhasil dibuat</h6>
    <p class="text-center text-secondary small mb-4">
        Kamu bisa melihat laporan {{ request('type') }} yang dibuat di halaman laporan pribadi kamu.
    </p>

    {{-- KONDISI DINAMIS PADA TOMBOL --}}
    @php
        // Jika type adalah temuan, arahkan ke tab temuan, jika tidak default ke kehilangan
        $targetType = request('type') === 'temuan' ? 'temuan' : 'kehilangan';
    @endphp

    <a href="{{ route('user.report.my-report', ['type' => $targetType, 'status' => 'aktif']) }}"
       class="btn btn-primary py-2 px-5 rounded-pill">
        Lihat Laporan {{ ucfirst($targetType) }}
    </a>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bodymovin/5.12.2/lottie.min.js"></script>
<script>
    // Pastikan elemen dimuat dulu
    document.addEventListener('DOMContentLoaded', function() {
        var animation = bodymovin.loadAnimation({
            container: document.getElementById('lottie'),
            renderer: 'svg',
            loop: true,
            autoplay: true,
            path: '{{ asset("assets/app/lottie/success.json") }}'
        });
    });
</script>
@endsection
