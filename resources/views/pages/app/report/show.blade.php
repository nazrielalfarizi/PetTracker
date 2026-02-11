@extends('layouts.no-nav')

@section('title', 'Detail Laporan ' . $report->title)

@section('content')
    <div class="header-nav d-flex align-items-center justify-content-between">
    <div class="nav-left">
    @php
        $from = request('from');
        $previousRoute = url()->previous();
        $myReportUrl = route('user.report.my-report', [
            'status' => 'aktif',
            'type' => $report->type
        ]);

        if ($from === 'my-report') {
            // Jika datang dari my-report (termasuk setelah edit)
            $backUrl = $myReportUrl;
        } elseif (str_contains($previousRoute, 'search')) {
            $backUrl = $previousRoute;
        } else {
            $backUrl = route('home');
        }
    @endphp

    <a href="{{ $backUrl }}">
        <img src="{{ asset('assets/app/images/icons/ArrowLeft.svg') }}"
            alt="arrow-left" style="width: 24px;">
    </a>
    </div>

    <div class="nav-center">
        <h1 class="fs-6 mb-0 fw-bold text-truncate">
            Detail Laporan {{ $report->type === 'kehilangan' ? 'Kehilangan' : 'Temuan' }}
        </h1>
    </div>

    <div class="nav-right" style="width: 24px;">
        {{-- Kosong untuk keseimbangan flexbox --}}
    </div>
</div>

    {{-- Image Report --}}
    <img src="{{ asset('storage/' . $report->image) }}" alt="pet image" class="report-image mt-5">

    <h1 class="report-title mt-4 fw-bold">{{ $report->title }}</h1>
    <p class="text-secondary">{{ $report->description }}</p>

    <div class="card card-report-information mt-4 border-0 shadow-sm rounded-4">
        <div class="card-body">
            <div class="card-title mb-4 fw-bold text-primary">Informasi Laporan</div>

            {{-- Row: Tanggal --}}
            <div class="row mb-3">
                <div class="col-4 text-secondary small">Waktu</div>
                <div class="col-8 d-flex small">
                    <span class="me-2">:</span>
                    <p class="mb-0">{{ $report->created_at->format('d M Y, H:i') }} WIB</p>
                </div>
            </div>
            <div class="card-body">
                    <div class="card-title mb-3 fw-bold text-primary">
                        {{-- Kondisi Judul Map --}}
                        @if($report->type === 'kehilangan')
                            Titik Terakhir Terlihat
                        @else
                            Titik Hewan Ditemukan
                        @endif
                    </div>
                    <div id="map"></div>
                </div>
            <br>
             <div class="row mb-3">
                <div class="col-4 text-secondary small">Patokan</div>
                <div class="col-8 d-flex small">
                    <span class="me-2">:</span>
                    <p class="mb-0">{{ $report->last_seen_location }}</p>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-4 text-secondary small">
                    {{-- Kondisi Label Lokasi --}}
                    @if($report->type === 'kehilangan')
                        Lokasi Terakhir
                    @else
                        Lokasi Ditemukan
                    @endif
                </div>
                <div class="col-8 d-flex small">
                    <span class="me-2">:</span>
                    <p class="mb-0 text-truncate-2">
                        {{ $report->address }}
                    </p>
                </div>
            </div>

            {{-- Info Spesifik Kehilangan --}}
            @if($report->type === 'kehilangan')
            <div class="row mb-3">
                <div class="col-4 text-secondary small">Ciri-ciri</div>
                <div class="col-8 d-flex small">
                    <span class="me-2">:</span>
                    <p class="mb-0">{{ $report->pet_characteristics }}</p>
                </div>
            </div>
            @endif

            {{-- Info Spesifik Temuan --}}
            @if($report->type === 'temuan')
            <div class="row mb-3">
                <div class="col-4 text-secondary small">Ciri-ciri</div>
                <div class="col-8 d-flex small">
                    <span class="me-2">:</span>
                    <p class="mb-0">{{ $report->pet_characteristics }}</p>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-4 text-secondary small">Kondisi</div>
                <div class="col-8 d-flex small">
                    <span class="me-2">:</span>
                    <p class="mb-0">{{ $report->pet_condition }}</p>
                </div>
            </div>
            @endif

            {{-- Row: Status --}}
            <div class="row mb-1">
                <div class="col-4 text-secondary small">Status</div>
                <div class="col-8 d-flex small align-items-center">
                    <span class="me-2">:</span>
                    @if($report->status === 'Aktif' || $report->status === 'Selesai')
                        <span class="badge bg-light-warning text-warning px-2 py-1 rounded-pill">
                            Aktif
                        </span>
                    @else
                        <span class="badge bg-light-success text-success px-2 py-1 rounded-pill">
                            Selesai
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Tambahan Tombol Kontak --}}
    {{-- Tombol hanya muncul JIKA backUrl BUKAN ke halaman my-report --}}
    @php
    // Cek apakah pelapor punya nomor HP
    $hasPhone = !empty($report->resident?->phone_number);

    // Cek apakah user adalah pemilik laporan (hanya jika sudah login)
    $isOwner = auth()->check() && auth()->user()->resident && ($report->resident_id === auth()->user()->resident->id);
@endphp

<div class="mt-4 mb-5">
    @if ($isOwner)
        {{-- Kondisi jika yang buka adalah pemilik laporan --}}
        <p class="text-center text-muted small">
            Ini adalah laporan yang kamu buat.
        </p>
    @elseif ($hasPhone)
        {{-- Kondisi untuk Guest ATAU User lain --}}
        @php
            // Format nomor WA
            $phone = '62' . ltrim($report->resident->phone_number, '0');

            // Pesan otomatis berdasarkan tipe laporan
            if ($report->type === 'kehilangan') {
                $message = "Halo, saya melihat laporan kehilangan dengan judul \"{$report->title}\". "
                        . "Apakah hewan ini sudah ditemukan? Saya ingin membantu.";
            } else {
                $message = "Halo, saya melihat laporan temuan dengan judul \"{$report->title}\". "
                        . "Sepertinya ini hewan saya. Apakah masih ada?";
            }

            // Encode pesan untuk URL
            $encodedMessage = urlencode($message);
        @endphp
        <a href="https://wa.me/{{ $phone }}?text={{ $encodedMessage }}"
        class="btn btn-success w-100 rounded-pill py-2 d-flex align-items-center justify-content-center gap-2"
        target="_blank">
            <i class="fab fa-whatsapp fs-5"></i>
            <span>Hubungi Pelapor</span>
        </a>
    @else
        {{-- Kondisi cadangan jika pelapor tidak mencantumkan nomor HP --}}
        <p class="text-center text-muted small">
            Pelapor tidak mencantumkan nomor kontak.
        </p>
    @endif
</div>
@endsection

@section('scripts')
<style>
    #map {
        height: 300px; /* Ubah angka ini sesuai keinginan */
        width: 100%;
        border-radius: 15px;
        z-index: 1;
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Ambil data koordinat dari database via Laravel Blade
        const lat = {{ $report->latitude }};
        const lng = {{ $report->longitude }};

        // Inisialisasi Map
        const map = L.map('map').setView([lat, lng], 15);

        // Tambahkan Tile Layer (Tampilan Peta)
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        // Tambahkan Marker (Pin)
        const marker = L.marker([lat, lng]).addTo(map);

        // Tambahkan Popup pada Marker
        marker.bindPopup("<b>Lokasi {{ ucfirst($report->type) }}</b><br>{{ $report->title }}").openPopup();

        // Fix untuk map yang kadang tidak muncul sempurna jika di dalam kontainer hidden/tab
        setTimeout(function() {
            map.invalidateSize();
        }, 500);
    });
    </script>

@endsection
