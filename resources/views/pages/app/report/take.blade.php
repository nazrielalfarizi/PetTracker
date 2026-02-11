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

                {{-- Tombol Ganti Kamera --}}
                <button class="btn btn-secondary shadow"
                        onclick="switchCamera()"
                        style="width: 50px; height: 50px; border-radius: 50%;">
                    <i class="fas fa-sync"></i>
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

    let currentStream;
    let useRearCamera = true; // Default kamera belakang
    const video = document.querySelector("#video-webcam");

    // Fungsi utama untuk menyalakan kamera
    function startCamera() {
        // Hentikan stream yang sedang berjalan jika ada
        if (currentStream) {
            currentStream.getTracks().forEach(track => track.stop());
        }

        const constraints = {
            video: {
                facingMode: useRearCamera ? "environment" : "user"
            }
        };

        navigator.mediaDevices.getUserMedia(constraints)
            .then(function (stream) {
                currentStream = stream;
                video.srcObject = stream;
            })
            .catch(function (error) {
                alert("Error kamera: " + error);
            });
    }

    // Fungsi untuk pindah kamera
    function switchCamera() {
        useRearCamera = !useRearCamera;
        startCamera();
    }

    // Jalankan kamera saat pertama kali load
    startCamera();

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
