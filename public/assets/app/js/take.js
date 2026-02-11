var video = document.querySelector("#video-webcam");

navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia ||
    navigator.msGetUserMedia || navigator.oGetUserMedia;

if (navigator.getUserMedia) {
    navigator.getUserMedia({
        video: true
    }, handleVideo, videoError);
}

function handleVideo(stream) {
    // Tambahkan pengecekan agar tidak error "null" di console
    if (video) {
        video.srcObject = stream;
    }
}

function videoError(e) {
    alert("Izinkan menggunakan webcam!");
}

function takeSnapshot() {
    var canvas = document.createElement('canvas');
    var context = canvas.getContext('2d');
    var video = document.getElementById('video-webcam');

    if (!video) return;

    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    context.drawImage(video, 0, 0);

    var dataURL = canvas.toDataURL('image/png');

    // 2. Simpan ke LocalStorage
    localStorage.setItem('image', dataURL);
    localStorage.setItem('report_type', currentType);

    // 3. REDIRECT DENGAN PARAMETER (PENTING!)
    // Ini agar Controller preview bisa membaca tipenya
    window.location.href = '/preview-report?type=' + currentType;
}
