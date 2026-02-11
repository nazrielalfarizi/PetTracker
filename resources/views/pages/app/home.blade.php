@extends('layouts.app')

@section('title', 'Home')

@section('content')

    {{-- Greeting --}}
    @auth
        <h6 class="greeting">Hi, {{ Auth::user()->name }} 👋</h6>
    @endauth

    @guest
        <h6 class="greeting">Hi, Selamat Datang</h6>
    @endguest

    <h4 class="home-headline">
        Pantau, Laporkan, dan Temukan Hewan Peliharaanmu.
    </h4>
    <br>

    <p class="align-center">
        <small>Pilih terlebih dahulu laporan apa yang mau dibuat?</small>
    </p>
    {{-- Tabs Navigation --}}
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
    </ul>

    {{-- Tab Content --}}
    <div class="tab-content py-3" id="filter-tabContent">

        {{-- ===================== PANE: KEHILANGAN ===================== --}}
        <div class="tab-pane fade {{ request('type', 'kehilangan') === 'kehilangan' ? 'show active' : '' }}"
             id="kehilangan-tab-pane" role="tabpanel" aria-labelledby="kehilangan-tab">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0">Laporan kehilangan terbaru</h6>
                <a href="{{ route('user.report.index', ['type' => 'kehilangan']) }}"
                   class="text-primary text-decoration-none show-more small">
                    Lihat semua
                </a>
            </div>

            <div class="d-flex flex-column gap-3">
                @forelse ($reportsKehilangan as $report)
                    <div class="card card-report border-0 shadow-none">
                        <a href="{{ route('user.report.show', $report->code) }}" class="text-decoration-none text-dark">
                            <div class="card-body p-0">
                                <div class="card-report-image position-relative mb-2">
                                    <img src="{{ asset('storage/' . $report->image) }}" alt="report image" class="rounded-3 w-100">
                                    <div class="badge-status {{ $report->status === 'selesai' ? 'done' : 'on-process' }}">
                                        {{ ucfirst($report->status) }}
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <div class="d-flex align-items-center text-primary">
                                        <img src="{{ asset('assets/app/images/icons/MapPin.png') }}" alt="pin" class="icon me-1" style="width: 14px;">
                                        <small class="city">{{ \Str::limit($report->address, 20) }}...</small>
                                    </div>
                                    <small class="text-secondary">{{ $report->created_at->format('d M Y, H:i') }}</small>
                                </div>
                                <h6 class="card-title fw-bold">{{ $report->title }}</h6>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="text-center py-4">
                        <p class="text-muted italic">Belum ada laporan kehilangan.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- ===================== PANE: TEMUAN ===================== --}}
        <div class="tab-pane fade {{ request('type') === 'temuan' ? 'show active' : '' }}"
             id="temuan-tab-pane" role="tabpanel" aria-labelledby="temuan-tab">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0">Laporan temuan terbaru</h6>
                <a href="{{ route('user.report.index', ['type' => 'temuan']) }}"
                   class="text-primary text-decoration-none show-more small">
                    Lihat semua
                </a>
            </div>

            <div class="d-flex flex-column gap-3">
                @forelse ($reportsTemuan as $report)
                    <div class="card card-report border-0 shadow-none">
                        <a href="{{ route('user.report.show', $report->code) }}" class="text-decoration-none text-dark">
                            <div class="card-body p-0">
                                <div class="card-report-image position-relative mb-2">
                                    <img src="{{ asset('storage/' . $report->image) }}" alt="report image" class="rounded-3 w-100">
                                    <div class="badge-status {{ $report->status === 'selesai' ? 'done' : 'on-process' }}">
                                        {{ ucfirst($report->status) }}
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <div class="d-flex align-items-center text-primary">
                                        <img src="{{ asset('assets/app/images/icons/MapPin.png') }}" alt="pin" class="icon me-1" style="width: 14px;">
                                        <small class="city">{{ \Str::limit($report->address, 20) }}</small>
                                    </div>
                                    <small class="text-secondary">{{ $report->created_at->format('d M Y, H:i') }}</small>
                                </div>
                                <h6 class="card-title fw-bold">{{ $report->title }}</h6>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="text-center py-4">
                        <p class="text-muted italic">Belum ada laporan temuan.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection

@push('scripts')
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
@endpush
