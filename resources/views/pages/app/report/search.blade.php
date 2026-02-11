@extends('layouts.app')

@section('title', 'Home')

@section('content')

{{-- GREETING --}}
@auth
    <h6 class="greeting">Hi, {{ Auth::user()->name }} 👋</h6>
@endauth

@guest
    <h6 class="greeting">Hi, Selamat Datang</h6>
@endguest

{{-- <h4 class="home-headline">
    Pantau, Laporkan, dan Temukan Hewan Peliharaanmu.
</h4> --}}

<p class="text-muted mb-4">
    Gunakan pencarian untuk menemukan laporan di sekitarmu.
</p>
{{-- ================= SEARCH FORM ================= --}}
<form action="{{ route('user.report.search') }}" method="GET" class="mb-3" id="search-form">

    {{-- TYPE FILTER --}}
    <p class="text-center">
        <small>Pilih terlebih dahulu laporan apa yang mau dicari?</small>
    </p>

    <ul class="nav nav-tabs border-bottom-0" id="filter-tab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ request('type', 'kehilangan') === 'kehilangan' ? 'active' : '' }}"
                id="kehilangan-tab"
                data-bs-toggle="tab"
                data-bs-target="#kehilangan-tab-pane"
                type="button"
                role="tab"
                aria-selected="{{ request('type', 'kehilangan') === 'kehilangan' ? 'true' : 'false' }}">
                Laporan Kehilangan
            </button>
        </li>

        <li class="nav-item" role="presentation">
            <button class="nav-link {{ request('type') === 'temuan' ? 'active' : '' }}"
                id="temuan-tab"
                data-bs-toggle="tab"
                data-bs-target="#temuan-tab-pane"
                type="button"
                role="tab"
                aria-selected="{{ request('type') === 'temuan' ? 'true' : 'false' }}">
                Laporan Temuan
            </button>
        </li>
        <br>
    {{-- ID TAMBAHAN: type-input-home --}}
    <input type="hidden" name="type" id="type-input-home" value="{{ request('type','kehilangan') }}">

    <br>
    <div class="input-group mb-2 gap-2">
        <input type="text"
               name="q"
               class="form-control"
               placeholder="Cari hewan, ciri, atau lokasi..."
               value="{{ request('q') }}">
        <button class="btn btn-primary" type="submit">
            Cari
        </button>
    </div>
</form>
<br>
{{-- BUTTON NEARBY --}}
<div class="d-grid mb-2">
    <button class="btn btn-outline-primary" onclick="searchNearby()">
        Cari Laporan Terdekat
    </button>
</div>

{{-- ================= EMPTY STATE ================= --}}
@if (!request()->has('q') && !request()->has('nearby'))
    <div class="text-center py-5">
        <p class="text-muted">
            Belum ada data yang ditampilkan.
            Gunakan pencarian di atas untuk mulai mencari laporan.
        </p>
    </div>
@endif

{{-- ================= SEARCH RESULT ================= --}}
@if (request()->has('q') || request()->has('nearby'))
    {{-- Tambahkan w-100 dan pastikan tidak ada padding samping yang menghambat --}}
    <div class="d-flex flex-column gap-3 w-100">
        @forelse ($reports as $report)
            {{-- Tambahkan w-100 pada card --}}
            <div class="card card-report border-0 shadow-none w-100">
                <a href="{{ route('user.report.show', $report->code) }}" class="text-decoration-none text-dark d-block">
                    <div class="card-body p-0">
                        {{-- Image Container --}}
                        <div class="card-report-image position-relative mb-2">
                            <img src="{{ asset('storage/' . $report->image) }}"
                                 alt="report image"
                                 class="rounded-3 w-100"
                                 style="height: 200px; object-fit: cover;"> {{-- Tambahkan height & cover agar seragam --}}

                            <div class="badge-status {{ strtolower($report->status) === 'selesai' ? 'done' : 'on-process' }}">
                                {{ ucfirst($report->status) }}
                            </div>
                        </div>

                        {{-- Content --}}
                        <div class="px-1"> {{-- Beri sedikit padding agar teks tidak mentok ke pinggir gambar --}}
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <div class="d-flex align-items-center text-primary">
                                    <img src="{{ asset('assets/app/images/icons/MapPin.png') }}" alt="pin" class="icon me-1" style="width: 14px;">
                                    <small class="city">{{ \Str::limit($report->address, 30) }}</small>
                                </div>
                                <small class="text-secondary" style="font-size: 10px;">{{ $report->created_at->format('d M Y') }}</small>
                            </div>
                            <h6 class="card-title fw-bold mb-0">{{ $report->title }}</h6>
                        </div>
                    </div>
                </a>
    </div>
        @empty
            <div class="text-center py-5">
                <p class="text-muted">
                    Tidak ditemukan laporan yang sesuai.
                </p>
            </div>
        @endforelse

    </div>
@endif

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const typeInput = document.getElementById('type-input-home');

    document.querySelectorAll('button[data-bs-toggle="tab"]').forEach(el => {
        el.addEventListener('shown.bs.tab', e => {

            const type = e.target.id.replace('-tab', '');

            // 1. Update hidden input
            typeInput.value = type;

            // 2. Update URL tanpa reload
            const url = new URL(window.location);
            url.searchParams.set('type', type);
            window.history.pushState({}, '', url);
        });
    });
});

function searchNearby() {
    if (!navigator.geolocation) {
        alert('Browser tidak mendukung lokasi');
        return;
    }

    navigator.geolocation.getCurrentPosition(
        function (position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;

            const params = new URLSearchParams(window.location.search);
            const type = params.get('type') || 'kehilangan';

            const url = new URL("{{ route('user.report.search') }}");
            url.searchParams.set('nearby', 1);
            url.searchParams.set('latitude', lat);
            url.searchParams.set('longitude', lng);
            url.searchParams.set('type', type);

            window.location.href = url.toString();
        },
        function () {
            alert('Gagal mendapatkan lokasi');
        }
    );
}
</script>
@endsection
