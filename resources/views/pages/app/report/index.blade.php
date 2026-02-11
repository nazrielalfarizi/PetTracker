@extends('layouts.app')

@section('title', 'Daftar Laporan')

@section('content')
<div class="py-3" id="reports">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <p class="text-muted mb-0">
                {{ $reports->count() }} List Laporan
            </p>

            @if (!empty($type))
                <small class="text-muted">
                    Filter:
                    <strong class="text-capitalize">{{ $type }}</strong>
                </small>
            @endif
        </div>
    </div>

    {{-- List --}}
    <div class="d-flex flex-column gap-3 mt-3">
        @forelse ($reports as $report)
            <div class="card card-report border-0 shadow-none">
                <a href="{{ route('user.report.show', $report->code) }}"
                   class="text-decoration-none text-dark">

                    <div class="card-body p-0">

                        {{-- Image + Status --}}
                        <div class="card-report-image position-relative mb-2">
                            @if ($report->image)
                                <img src="{{ asset('storage/' . $report->image) }}" alt="report image">
                            @else
                                <img src="{{ asset('img/no-image.png') }}" alt="no image">
                            @endif

                            {{-- Status --}}
                            @if ($report->status === 'aktif')
                                <div class="badge-status on-process">
                                    Aktif
                                </div>
                            @elseif ($report->status === 'selesai')
                                <div class="badge-status done">
                                    Selesai
                                </div>
                            @endif
                        </div>

                        {{-- Location & Date --}}
                        <div class="d-flex justify-content-between align-items-end mb-2">
                            <div class="d-flex align-items-center">
                                <img src="{{ asset('assets/app/images/icons/MapPin.png') }}"
                                     alt="map pin"
                                     class="icon me-2">
                                <p class="text-primary city">
                                    <small class="city">{{ \Str::limit($report->address, 20) }}</small>
                                </p>
                            </div>

                            <p class="text-secondary date">
                                {{ $report->created_at->format('d M Y H:i') }}
                            </p>
                        </div>

                        {{-- Title --}}
                        <h1 class="card-title">
                            {{ $report->title }}
                        </h1>
                    </div>
                </a>
            </div>
        @empty
            <div class="text-center text-muted py-5">
                Belum ada laporan.
            </div>
        @endforelse
    </div>

</div>
@endsection
