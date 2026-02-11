@extends('layouts.app')

@section('title','Laporanmu')

@section('content')

{{-- FILTER TABS (URL-BASED) --}}
<ul class="nav nav-tabs mb-2">

    <li class="nav-item">
        <a href="{{ route('user.report.my-report', ['status' => 'aktif', 'type' => request('type')]) }}"
           class="nav-link {{ request('status','aktif') === 'aktif' ? 'active' : '' }}">
            Aktif
        </a>
    </li>

    <li class="nav-item">
        <a href="{{ route('user.report.my-report', ['status' => 'selesai', 'type' => request('type')]) }}"
           class="nav-link {{ request('status') === 'selesai' ? 'active' : '' }}">
            Selesai
        </a>
    </li>

</ul>

<ul class="nav nav-tabs mb-3 border-bottom-0">

    <li class="nav-item">
        <a href="{{ route('user.report.my-report', ['type' => 'kehilangan', 'status' => request('status')]) }}"
           class="nav-link {{ request('type','kehilangan') === 'kehilangan' ? 'active' : '' }}">
            Kehilangan
        </a>
    </li>

    <li class="nav-item">
        <a href="{{ route('user.report.my-report', ['type' => 'temuan', 'status' => request('status')]) }}"
           class="nav-link {{ request('type') === 'temuan' ? 'active' : '' }}">
            Temuan
        </a>
    </li>

</ul>

{{-- LIST LAPORAN --}}
<div class="d-flex flex-column gap-3">

    @forelse ($reports as $report)
    <div class="card card-report border-0 shadow-sm mb-3">
        <div class="card-body p-3">
            <a href="{{ route('user.report.show', [
                    'code' => $report->code,
                    'from' => 'my-report'
                ]) }}" class="text-decoration-none text-dark">
                {{-- IMAGE + STATUS --}}
                <div class="card-report-image position-relative mb-2">
                    @if ($report->image)
                        <img src="{{ asset('storage/' . $report->image) }}" alt="" style="width: 100%; height: 200px; object-fit: cover; border-radius: 12px;">
                    @endif

                    @if ($report->status === 'aktif')
                        <div class="badge-status on-process">Aktif</div>
                    @else
                        <div class="badge-status done">Selesai</div>
                    @endif
                </div>

                {{-- TITLE --}}
                <h1 class="card-title fs-5 fw-bold mb-2">{{ $report->title }}</h1>
            </a>

            <hr class="my-2 text-secondary opacity-25">

            {{-- ACTION BUTTONS --}}
            <div class="d-flex justify-content-between align-items-center mt-3 gap-2">
                {{-- Update Status Button --}}
                @if($report->status === 'aktif')
                    <form action="{{ route('user.report.update-status', $report->code) }}" method="POST" class="flex-grow-1 form-selesai">
                        @csrf
                        @method('PATCH')
                        <button type="button" class="btn btn-sm btn-outline-success w-100 rounded-pill py-2 btn-selesai">
                            <i class="fas fa-check-circle me-1"></i> Selesaikan
                        </button>
                    </form>
                @endif

                {{-- Edit Button --}}
                <a href="{{ route('user.report.edit', $report->code) }}" class="btn btn-outline-primary btn-sm rounded-pill flex-fill py-2 shadow-none">
                    <i class="fas fa-edit me-1"></i> Edit
                </a>

                {{-- Delete Button --}}
                <form action="{{ route('user.report.destroy', $report->code) }}" method="POST" class="flex-grow-1 form-hapus">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-sm btn-outline-danger w-100 rounded-pill py-2 btn-hapus">
                        <i class="fas fa-trash me-1"></i> Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
@empty
        <div class="text-center text-muted py-5">

            <div class="d-flex flex-column justify-content-center align-items-center" style="height: 75vh" id="no-reports">

                <div id="lottie" style="width: 200px; height: 200px;"></div>



                {{-- KONDISI STATUS AKTIF --}}

                @if (request('status', 'aktif') === 'aktif')
                    <h5 class="mt-3 fw-bold">Belum ada laporan {{ request('type', 'kehilangan') }}</h5>
                    <p class="small text-secondary px-4">Laporan yang kamu buat akan muncul di sini agar kamu bisa memantau perkembangannya.</p>

                    {{-- Tombol Buat Laporan --}}

                    <a href="{{ route('home') }}" class="btn btn-primary py-2 px-4 mt-3 rounded-pill">
                        Buat Laporan
                    </a>



                {{-- KONDISI STATUS SELESAI --}}

                @else

                    <h5 class="mt-3 fw-bold">Belum ada laporan selesai</h5>
                    <p class="small text-secondary px-4">Histori laporan {{ request('type', 'kehilangan') }} yang sudah tuntas akan tersimpan secara otomatis di sini.</p>



                    {{-- Tombol Kembali ke Laporan Aktif --}}

                    <a href="{{ route('user.report.my-report', ['status' => 'aktif', 'type' => request('type', 'kehilangan')]) }}" class="btn btn-outline-success py-2 px-4 mt-3 rounded-pill">
                        Cek Laporan Aktif
                    </a>
                @endif
            </div>
@endforelse

</div>
@endsection

@section('scripts')
<script>
    // Script opsional untuk sinkronisasi URL saat tab diklik
    document.querySelectorAll('button[data-bs-toggle="tab"]').forEach(el => {
        el.addEventListener('shown.bs.tab', e => {
            const type = e.target.id.replace('-tab', '');
            const url = new URL(window.location);
            url.searchParams.set('type', type);
            window.history.pushState({}, '', url);
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {

        // KONFIRMASI SELESAIKAN
        document.querySelectorAll('.btn-selesai').forEach(button => {
            button.addEventListener('click', function(e) {
                const form = this.closest('.form-selesai');
                Swal.fire({
                    title: 'Selesaikan Laporan?',
                    text: "Laporan akan dipindahkan ke tab Selesai.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#198754', // Warna Hijau Success
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Selesai!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    borderRadius: '15px'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        // KONFIRMASI HAPUS
        document.querySelectorAll('.btn-hapus').forEach(button => {
            button.addEventListener('click', function(e) {
                const form = this.closest('.form-hapus');
                Swal.fire({
                    title: 'Hapus Laporan?',
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545', // Warna Merah Danger
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

    });
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bodymovin/5.12.2/lottie.min.js"></script>
    <script>
        var animationPath = '{{ request("status") === "selesai" ? asset("assets/app/lottie/not-found.json") : asset("assets/app/lottie/not-found2.json") }}';
        var animation = bodymovin.loadAnimation({
            container: document.getElementById('lottie'),
            renderer: 'svg',
            loop: true,
            autoplay: true,
            path: animationPath // Menggunakan variabel path yang dinamis
        });
</script>
@endsection

@section('style')
<style>

.btn-outline-primary {
    color: #0d6efd;
    border-color: #0d6efd;
    background-color: transparent;
    transition: all 0.3s ease; /* Biar transisinya halus */
}
    /* Mengatur tombol Edit (Primary) */
.btn-outline-primary:hover {
    color: #fff !important; /* Memaksa teks jadi putih */
    background-color: #0d6efd; /* Warna biru primary */
    border-color: #0d6efd;
}

/* Mengatur tombol Selesai (Success) */
.btn-outline-success:hover {
    color: #fff !important;
    background-color: #198754; /* Warna hijau success */
}

/* Mengatur tombol Hapus (Danger) */
.btn-outline-danger:hover {
    color: #fff !important;
    background-color: #dc3545; /* Warna merah danger */
}

/* 1. Paksa tombol Edit (Primary) tetap biru saat ditekan/active */
.btn-outline-primary:hover,
.btn-outline-primary:active,
.btn-outline-primary:focus,
.btn-outline-primary.active {
    background-color: #0d6efd !important; /* Biru Primary */
    border-color: #0d6efd !important;      /* Garis tetap Biru */
    color: #ffffff !important;             /* Teks tetap Putih */
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25) !important; /* Shadow Biru */
}

/* 2. Hilangkan shadow hijau bawaan browser/Bootstrap jika ada */
.btn-outline-primary:focus {
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25) !important;
}

/* 3. Pastikan Ikon di dalamnya tidak berubah warna */
.btn-outline-primary:active i,
.btn-outline-primary:focus i {
    color: #ffffff !important;
}

/* Keadaan Hover, Ditekan (Active), dan Fokus */
.btn-outline-danger:hover,
.btn-outline-danger:active,
.btn-outline-danger:focus,
.btn-outline-danger.active {
    background-color: #dc3545 !important; /* Paksa Merah */
    border-color: #dc3545 !important;      /* Garis Merah */
    color: #ffffff !important;             /* Teks Putih */
    box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25) !important; /* Shadow Merah */
}

/* Pastikan Ikon di dalam tombol Danger ikut putih */
.btn-outline-danger:hover i,
.btn-outline-danger:active i,
.btn-outline-danger:focus i {
    color: #ffffff !important;
}
</style>
@endsection
