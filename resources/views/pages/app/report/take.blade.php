@extends('layouts.no-nav')

@section('title', 'Ambil Foto')

@section('content')
    <div class="d-flex flex-column justify-content-center align-items-center">
        <h4 class="home-headline">
        Ambil Gambar
    </h4>
        {{-- Video Webcam --}}
        <video autoplay="true" id="video-webcam">
            Browsermu tidak mendukung bro, upgrade donk!
        </video>
        <div class="d-flex justify-content-center align-items-center gap-3 position-absolute bottom-0 start-50 translate-middle-x mb-5">

            {{-- Tombol Batal --}}
            <button class="btn btn-danger px-4 py-2 shadow"
                    onclick="goBack()">
                Batal
            </button>

            {{-- Tombol Ambil Foto --}}
            <button class="btn btn-primary btn-snap shadow-lg"
                    onclick="takeSnapshot()"
                    style="width: 70px; height: 70px; border-radius: 50%;">
                <i class="fas fa-camera fa-2x"></i>
            </button>

        </div>


        {{-- Canvas Hidden (Untuk proses capture) --}}
        <canvas id="canvas" style="display:none;"></canvas>
    </div>
@endsection

@section('scripts')
<script>
    // Ambil type dari PHP (Controller)
    // Ambil type langsung dari URL (paling aman)
    const urlParams = new URLSearchParams(window.location.search);
    const currentType = urlParams.get('type') || localStorage.getItem('report_type') || 'kehilangan';


    function takeSnapshot() {
    const context = canvas.getContext('2d');
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;

    context.drawImage(video, 0, 0, canvas.width, canvas.height);

    const dataFoto = canvas.toDataURL('image/png');

    // Simpan foto
    localStorage.setItem('image', dataFoto);

    // Simpan type sebagai cadangan
    localStorage.setItem('report_type', currentType);

    // Redirect ke preview + type
    window.location.href =
        "{{ route('user.report.preview') }}" + "?type=" + currentType;
}

function goBack() {
    // kembali ke home dengan type yang sama
    window.location.href = "{{ route('home') }}?type=" + currentType;
}


</script>
@endsection
