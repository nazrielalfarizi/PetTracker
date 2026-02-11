var mapElement = document.getElementById('map');
var latitudeInput = document.getElementById('lat');
var longitudeInput = document.getElementById('lng');
var addressInput = document.getElementById('address');

navigator.geolocation.getCurrentPosition(function (position) {
    var lat = position.coords.latitude;
    var lng = position.coords.longitude;

    // Set nilai awal
    latitudeInput.value = lat;
    longitudeInput.value = lng;

    var mymap = L.map('map').setView([lat, lng], 13);

    // TAMBAHKAN {draggable: true} agar pin bisa digeser manual
    var marker = L.marker([lat, lng], {
        draggable: true
    }).addTo(mymap);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: 'Map data &copy; OpenStreetMap contributors',
        maxZoom: 18,
    }).addTo(mymap);

    // Fungsi Reusable untuk Reverse Geocoding (Mendapatkan Nama Alamat)
    function fetchAddress(lat, lng) {
        var geocodingUrl = `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`;

        fetch(geocodingUrl)
            .then(response => response.json())
            .then(data => {
                addressInput.value = data.display_name;
                marker.bindPopup(`<b>Lokasi Terpilih</b><br />${data.display_name}`).openPopup();
            })
            .catch(error => console.error('Error fetching address:', error));
    }

    // Jalankan pertama kali untuk lokasi saat ini
    fetchAddress(lat, lng);

    // FITUR 1: Update lokasi saat marker SELESAI DIGESER
    marker.on('dragend', function (e) {
        var position = marker.getLatLng();
        latitudeInput.value = position.lat;
        longitudeInput.value = position.lng;
        fetchAddress(position.lat, position.lng);
    });

    // FITUR 2: Update lokasi saat PETA DIKLIK (Pin pindah otomatis ke titik klik)
    mymap.on('click', function (e) {
        var newLat = e.latlng.lat;
        var newLng = e.latlng.lng;

        marker.setLatLng([newLat, newLng]); // Pindahkan marker ke titik klik
        latitudeInput.value = newLat;
        longitudeInput.value = newLng;
        fetchAddress(newLat, newLng); // Update nama alamat
    });

}, function(error) {
    alert("Gagal mengakses lokasi. Mohon aktifkan GPS atau berikan izin browser.");
});
