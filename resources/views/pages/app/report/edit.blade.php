@extends('layouts.no-nav')

@section('title', 'Edit Laporan')

@section('content')

@php
    $type = $report->type;
@endphp

<div class="header-nav d-flex align-items-center justify-content-between">
    <div class="nav-left">
        @php
            $previousRoute = url()->previous();
            $myReportUrl = route('user.report.my-report');
            $backUrl = str_contains($previousRoute, 'my-report') ? $myReportUrl : route('home');
        @endphp

        <a href="{{ $backUrl }}">
            <img src="{{ asset('assets/app/images/icons/ArrowLeft.svg') }}" style="width:24px;">
        </a>
    </div>
    <div class="nav-center">
        <h1 class="fs-6 mb-0 fw-bold">
            Edit Laporan {{ $type === 'kehilangan' ? 'Kehilangan' : 'Temuan' }}
        </h1>
    </div>
    <div class="nav-right" style="width:24px;"></div>
</div>

<div class="content-wrapper">

<form action="{{ route('user.report.update', $report->code) }}"
      method="POST"
      class="mt-4"
      enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <input type="hidden" name="type" value="{{ $type }}">

    {{-- TITLE --}}
    <div class="mb-3">
        <label class="form-label">Judul Laporan</label>
        <input type="text"
               class="form-control"
               name="title"
               value="{{ old('title', $report->title) }}">
    </div>

    {{-- IMAGE --}}
    <div class="mb-3">
        <label class="form-label">Bukti Laporan</label>

        @if ($report->image)
            <img src="{{ asset('storage/' . $report->image) }}"
                 class="img-fluid rounded-2 mb-2 border">
        @endif

        <input type="file" class="form-control" name="image">
        <small class="text-muted">Kosongkan jika tidak ingin mengganti gambar</small>
    </div>

    {{-- DESCRIPTION --}}
    <div class="mb-3">
        <label class="form-label">Deskripsi Laporan</label>
        <textarea class="form-control"
                  name="description"
                  rows="4">{{ old('description', $report->description) }}</textarea>
    </div>

    {{-- ================== MAP SECTION ================== --}}
    <input type="hidden" name="latitude" id="lat"
           value="{{ old('latitude', $report->latitude) }}">
    <input type="hidden" name="longitude" id="lng"
           value="{{ old('longitude', $report->longitude) }}">

    <div class="mb-3">
        <label class="form-label">
            {{ $type === 'kehilangan' ? 'Lokasi Terakhir Terlihat' : 'Lokasi Ditemukan' }}
        </label>

        <div id="map" style="height:250px;" class="mb-2 rounded"></div>

        <button type="button"
                class="btn btn-sm btn-outline-primary"
                onclick="getCurrentLocation()">
            Gunakan Lokasi Saat Ini
        </button>
    </div>

    {{-- FIELD TAMBAHAN --}}
    @if ($type === 'kehilangan')
         <div class="mb-3">
            <label class="form-label">Lokasi Terakhir Terlihat</label>
            <input type="text"
                class="form-control"
                name="last_seen_location"
                value="{{ old('last_seen_location', $report->last_seen_location) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Ciri-ciri Hewan</label>
            <textarea class="form-control"
                      name="pet_characteristics"
                      rows="3">{{ old('pet_characteristics', $report->pet_characteristics) }}</textarea>
        </div>
    @else
        <div class="mb-3">
            <label class="form-label">Kondisi Hewan</label>
            <textarea class="form-control"
                      name="pet_condition"
                      rows="3">{{ old('pet_condition', $report->pet_condition) }}</textarea>
        </div>
    @endif

    {{-- ADDRESS --}}
    <div class="mb-3">
        <label class="form-label">Alamat Lengkap</label>
        <textarea class="form-control"
                  name="address"
                  id="address"
                  rows="3">{{ old('address', $report->address) }}</textarea>
    </div>

    <button class="btn btn-primary w-100 mt-2">
        Simpan Perubahan
    </button>
</form>
</div>
@endsection
@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const latInput = document.getElementById('lat');
    const lngInput = document.getElementById('lng');
    const addressInput = document.getElementById('address');

    let lat = parseFloat(latInput.value) || -6.9147;
    let lng = parseFloat(lngInput.value) || 107.6098;

    const map = L.map('map').setView([lat, lng], 15);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    let marker = L.marker([lat, lng], { draggable: true }).addTo(map);

    // ==============================
    // FUNGSI AMBIL ALAMAT OTOMATIS
    // ==============================
    function updateAddress(lat, lng) {
        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
            .then(res => res.json())
            .then(data => {
                if (data.display_name) {
                    addressInput.value = data.display_name;
                }
            })
            .catch(() => {
                console.log("Gagal mengambil alamat");
            });
    }

    // ==============================
    // MARKER DIGESER
    // ==============================
    marker.on('dragend', function() {
        const position = marker.getLatLng();
        latInput.value = position.lat;
        lngInput.value = position.lng;

        updateAddress(position.lat, position.lng);
    });

    // ==============================
    // KLIK PETA
    // ==============================
    map.on('click', function(e) {
        marker.setLatLng(e.latlng);
        latInput.value = e.latlng.lat;
        lngInput.value = e.latlng.lng;

        updateAddress(e.latlng.lat, e.latlng.lng);
    });

    // ==============================
    // LOKASI TERKINI
    // ==============================
    window.getCurrentLocation = function() {
        if (!navigator.geolocation) {
            alert("Browser tidak mendukung lokasi");
            return;
        }

        navigator.geolocation.getCurrentPosition(function(position) {
            const newLat = position.coords.latitude;
            const newLng = position.coords.longitude;

            marker.setLatLng([newLat, newLng]);
            map.setView([newLat, newLng], 16);

            latInput.value = newLat;
            lngInput.value = newLng;

            updateAddress(newLat, newLng);
        });
    };

});
</script>
@endsection
@section('style')
<style>
.content-wrapper {
    padding-top: 70px;
}
</style>
@endsection
