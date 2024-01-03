<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css" type="text/css" media="all" />
    <script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB8B04MTIk7abJDVESr6SUF6f3Hgt1DPAY&callback=initMap">

    </script>
    <title>Document</title>
</head>

<body>
    <div class="container">
        <div id="header">Peta Fotocopy di Kecamatan Padang Utara </div>
        <div id="menu">
            <a href="index.html">Home</a>
            <a href="map.html">koneksi</a>
        </div>
        <div id="content">
            <div style="width: 45%; height: 500px; float:left; border-style:ridge" id="map"></div>
        </div>
        <div style="width: 45%; min-height: 420px; float:left; border-style:ridge">
            Oleh :
            Nurul Afani (2211521015)
            Rifqi Asverian Putra (2211522021)
            Talitha Zulfa Amirah (2211522023)
            Chelviery Anggoro Tahary (2211522025)
            Muhammad Dani Noar (2211522037)
            Aqima Adalahita (2211522027)
            Niken Khalilah Hamuti (2211523019)
            Rahmatul Fa Dilla (2211523037)
        </div>
        <div id="footer"></div>
    </div>
    <script>
        var map;
        var show_digitation;
        var info_window;

        function initMap() {

            var elbaf = {
                lat: 0.89481,
                lng: 100.34546
            };
            map = new google.maps.Map(document.getElementById('map'), {
                zoom: 15,
                center: elbaf
            });

            // Membuat InfoWindow untuk peta
            infoWindow = new google.maps.InfoWindow({
                map: map
            });

            // Panggil fungsi untuk menampilkan digitasi
            showDigitasi(infoWindow);

            // Kodingan Geolokasi
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    var pos = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };

                    // Menambahkan marker geolokasi pada peta
                    var marker = new google.maps.Marker({
                        position: pos,
                        map: map,
                        title: 'Lokasi Saya',
                        icon: 'http://maps.google.com/mapfiles/ms/icons/green-dot.png',
                        draggable: true,
                        animation: google.maps.Animation.DROP
                    });

                    var marker1 = new google.maps.Marker({
                        position: elbaf,
                        map: map,
                        title: 'Dhilla Fotocopy',
                        icon: 'http://maps.google.com/mapfiles/ms/icons/green-dot.png',
                        draggable: true,
                        animation: google.maps.Animation.DROP
                    });

                    // Menambahkan event listener untuk marker geolokasi
                    marker.addListener('click', toggleBounce);

                    function toggleBounce() {
                        if (marker.getAnimation() !== null) {
                            marker.setAnimation(null);
                        } else {
                            marker.setAnimation(google.maps.Animation.BOUNCE);
                        }
                    }

                    // Menampilkan InfoWindow pada lokasi geolokasi
                    infoWindow.setPosition(pos);
                    infoWindow.setContent('Kamu Disini');
                    map.setCenter(pos);
                }, function() {
                    handleLocationError(true, infoWindow, map.getCenter());
                });
            } else {
                // Jika browser tidak mendukung geolokasi
                handleLocationError(false, infoWindow, map.getCenter());
            }
        }

        // Fungsi untuk menampilkan digitasi
        function showDigitasi(infoWindow) {
            // Menggunakan AJAX untuk mengambil data digitasi dari 'data.php'
            $.ajax({
                url: 'data.php',
                dataType: 'json',
                cache: false,
                success: function(arrays) {
                    for (i = 0; i < arrays.features.length; i++) {
                        var data = arrays.features[i];
                        var arrayGeometries = data.geometry.coordinates;
                        var p1 = '<p> GID : ' + data.properties.gid + '</p>';
                        var p2 = p1 + ' &nbsp ';

                        var idTitik = 0;
                        var hitungTitik = [];
                        while (idTitik < arrayGeometries[0][0].length) {
                            var aa = arrayGeometries[0][0][idTitik][0];
                            var bb = arrayGeometries[0][0][idTitik][1];
                            hitungTitik[idTitik] = {
                                lat: bb,
                                lng: aa
                            };
                            idTitik += 1;
                        }

                        // Membuat poligon dan menambahkannya ke peta
                        show_digitation = new google.maps.Polygon({
                            paths: hitungTitik,
                            strokeColor: 'red',
                            strokeOpacity: 1,
                            strokeWeight: 0.5,
                            fillColor: 'green',
                            fillOpacity: 0.35,
                        });

                        // Menambahkan atribut data-content ke objek show_digitation
                        show_digitation.set("data-content", p2);
                        show_digitation.setMap(map);

                        // Menambahkan event listener untuk menampilkan InfoWindow saat diklik
                        show_digitation.addListener('click', function(event) {
                            var lat = event.latLng.lat();
                            var lng = event.latLng.lng();
                            var info = {
                                lat: lat,
                                lng: lng
                            };
                            var content = this.get("data-content"); // Mengambil konten dari atribut data-content
                            infoWindow.setContent(content);
                            infoWindow.setPosition(info);
                            map.setCenter(info);
                            infoWindow.open(map);
                        });
                    }
                }
            });
        }
        // Menangani kesalahan geolokasi
        function handleLocationError(browserHasGeolocation, infoWindow, pos) {
            infoWindow.setPosition(pos);
            infoWindow.setContent('Kesalahan Layanan geolokasi.' + 'Error:<i>Browser</i> anda tidak mendukung geolokasi.');
        }
    </script>
</body>

</html>