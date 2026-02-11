{{-- @php
    $type = request('type', 'kehilangan');
@endphp

<div id="floating-report-btn"
     class="floating-button-container d-flex"
     style="cursor: pointer;"
     data-url-kehilangan="{{ route('user.report.take', ['type' => 'kehilangan']) }}"
     data-url-temuan="{{ route('user.report.take', ['type' => 'temuan']) }}"
     onclick="window.location.href='{{ route('user.report.take', ['type' => $type]) }}'">
    <button class="floating-button {{ $type === 'temuan' ? 'bg-success' : 'bg-danger' }}" id="cam-btn">
        <i class="fa-solid fa-camera"></i>
    </button>
</div> --}}

@php
    $type = request('type', 'kehilangan');
@endphp

{{-- TAMPILKAN HANYA DI HALAMAN HOME --}}
@if (request()->routeIs('home'))
<div id="floating-report-btn"
     class="floating-button-container d-flex"
     style="cursor: pointer;"
     data-type="{{ $type }}">
    <button class="floating-button {{ $type === 'temuan' ? 'bg-success' : 'bg-danger' }}" id="cam-btn">
        <i class="fa-solid fa-camera"></i>
    </button>
</div>
@endif

<nav class="nav-mobile d-flex">
    <a href="{{ route('home') }}" class="{{ request()->is('/') ? 'active' : '' }}">
        <i class="fas fa-house"></i> Beranda
    </a>
    <a href="{{ route('user.report.search', ['type' => $type]) }}"
        class="{{ request()->is('reports/search*') ? 'active' : '' }}">
        <i class="fas fa-search"></i> Cari Laporan
    </a>

    {{-- Spacer --}}
    <div></div><div></div><div></div><div></div>

    {{-- PERBAIKAN DI SINI: Tambahkan parameter status dan type --}}
    <a href="{{ route('user.report.my-report', ['status' => 'aktif', 'type' => 'kehilangan']) }}"
       class="{{ request()->is('my-reports*') || request()->query('status') ? 'active' : '' }}">
        <i class="fas fa-solid fa-clipboard-list"></i> Laporanmu
    </a>

    @auth
        <a href="{{ route('profile') }}" class="{{ request()->is('profile') ? 'active' : '' }}">
            <i class="fas fa-user"></i> Profil
        </a>
    @else
        <a href="{{ route('login') }}" class="">
            <i class="fas fa-right-to-bracket"></i> Daftar/Masuk
        </a>
    @endauth
</nav>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {

    const floatingBtn = document.getElementById('floating-report-btn');
    const camBtn = document.getElementById('cam-btn');

    function updateFloatingButton(type) {
        // Set warna tombol
        if (type === 'temuan') {
            camBtn.classList.remove('bg-danger');
            camBtn.classList.add('bg-success');

            floatingBtn.onclick = () => {
                window.location.href = "/take-report?type=temuan";
            };
        } else {
            camBtn.classList.remove('bg-success');
            camBtn.classList.add('bg-danger');

            floatingBtn.onclick = () => {
                window.location.href = "/create-report?type=kehilangan";
            };
        }
    }

    // Ambil type awal dari URL
    const params = new URLSearchParams(window.location.search);
    const initialType = params.get('type') || 'kehilangan';
    updateFloatingButton(initialType);

    // Saat tab diganti
    document.querySelectorAll('button[data-bs-toggle="tab"]').forEach(el => {
        el.addEventListener('shown.bs.tab', e => {
            const type = e.target.id.replace('-tab', '');

            const url = new URL(window.location);
            url.searchParams.set('type', type);
            window.history.pushState({}, '', url);

            updateFloatingButton(type);
        });
    });

});
</script>
@endsection
