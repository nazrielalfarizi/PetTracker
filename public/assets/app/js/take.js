// var video = document.querySelector("#video-webcam");

// navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia ||
//     navigator.msGetUserMedia || navigator.oGetUserMedia;

// if (navigator.getUserMedia) {
//     navigator.getUserMedia({
//         video: true
//     }, handleVideo, videoError);
// }

// function handleVideo(stream) {
//     // Tambahkan pengecekan agar tidak error "null" di console
//     if (video) {
//         video.srcObject = stream;
//     }
// }

// function videoError(e) {
//     alert("Izinkan menggunakan webcam!");
// }

// function takeSnapshot() {
//     var canvas = document.createElement('canvas');
//     var context = canvas.getContext('2d');
//     var video = document.getElementById('video-webcam');

//     if (!video) return;

//     canvas.width = video.videoWidth;
//     canvas.height = video.videoHeight;
//     context.drawImage(video, 0, 0);

//     var dataURL = canvas.toDataURL('image/png');

//     // 2. Simpan ke LocalStorage
//     localStorage.setItem('image', dataURL);
//     localStorage.setItem('report_type', currentType);

//     // 3. REDIRECT DENGAN PARAMETER (PENTING!)
//     // Ini agar Controller preview bisa membaca tipenya
//     window.location.href = '/preview-report?type=' + currentType;
// }

let video = document.querySelector("#video-webcam");
let currentStream = null;
let useRearCamera = true; // Default kamera belakang

// Fungsi untuk menyalakan kamera
function startCamera() {
    // Hentikan stream sebelumnya jika ada (PENTING untuk switch camera)
    if (currentStream) {
        currentStream.getTracks().forEach(track => track.stop());
    }

    const constraints = {
        video: {
            facingMode: useRearCamera ? "environment" : "user"
        }
    };

    // Gunakan API modern (mediaDevices)
    if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
        navigator.mediaDevices.getUserMedia(constraints)
            .then(function (stream) {
                currentStream = stream;
                if (video) {
                    video.srcObject = stream;
                }
            })
            .catch(function (error) {
                console.error("Error akses kamera:", error);
                alert("Gagal mengakses kamera. Pastikan koneksi HTTPS aktif.");
            });
    } else {
        alert("Browser tidak mendukung akses kamera.");
    }
}

// Fungsi untuk pindah kamera (panggil ini dari tombol di Blade)
function switchCamera() {
    useRearCamera = !useRearCamera;
    startCamera();
}

function takeSnapshot() {
    if (!video) return;

    var canvas = document.createElement('canvas');
    var context = canvas.getContext('2d');

    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    context.drawImage(video, 0, 0);

    var dataURL = canvas.toDataURL('image/png');

    // Simpan ke LocalStorage
    localStorage.setItem('image', dataURL);
    localStorage.setItem('report_type', currentType);

    // Redirect ke preview
    window.location.href = '/preview-report?type=' + currentType;
}

// Jalankan kamera saat halaman load
document.addEventListener("DOMContentLoaded", function() {
    startCamera();
});
