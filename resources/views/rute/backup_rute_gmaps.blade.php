@include('layout.header')
@include('layout.navbar')
@include('layout.sidebar')
<style>
    #map-canvas {
        width: 100%;
        height: 500px;
    }
</style>
<script src="https://code.jquery.com/jquery-3.6.1.min.js"
    integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBmBL3_MRsk7qiOqSXgNr-x59cz_vXU9Fg&callback=initMap" async defer></script>
</head>

<body>
<div class="page-body">
    <div class="container-fluid">
        <div class="card" style="width: 72rem; height: 55rem;">
            <div class="card-header pb-0">
                <h5>BACKUP</h5>
                <span>Tampilan Rute optimasi Google Maps</span>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div id="map-canvas"></div>
                    </div>
                </div>
                <!-- Tampilkan urutan lokasi -->
                <div id="route-order" class="mt-3">
                    @if (isset($optimalRoute))
                        <p>Optimal Route:</p>
                        <ol>
                            @foreach ($optimalRoute as $key => $locationId)
                                <li>
                                    {{ collect($locations)->where('id', $locationId)->first()['name'] }}
                                </li>
                            @endforeach
                            @if (end($optimalRoute) !== 1)
                                <li>
                                    {{ collect($locations)->where('id', 1)->first()['name'] }}
                                </li>
                            @endif
                        </ol>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>



<script>
    // Membuat objek peta
    function initMap() {
        var map = new google.maps.Map(document.getElementById('map-canvas'), {
            zoom: 12,
            center: {
                lat: -6.123456,
                lng: 106.789012
            } // Ganti dengan koordinat latitude dan longitude yang sesuai
        });

        // Menambahkan marker dan polyline untuk setiap lokasi dalam rute terbaik
        var locations = [
            @foreach ($optimalRoute as $locationId)
                {
                    name: "{{ collect($locations)->where('id', $locationId)->first()['name'] }}",
                    lat: {{ collect($locations)->where('id', $locationId)->first()['lat'] }},
                    lng: {{ collect($locations)->where('id', $locationId)->first()['lng'] }}
                },
            @endforeach
        ];

        var markers = [];
        for (var i = 0; i < locations.length; i++) {
            var location = locations[i];
            var marker = new google.maps.Marker({
                position: {
                    lat: location.lat,
                    lng: location.lng
                },
                map: map,
                label: (i + 1).toString()
            });
            markers.push(marker);
        }

        // Membuat polyline yang menghubungkan semua marker
        var polyline = new google.maps.Polyline({
            path: locations.map(function(location) {
                return {
                    lat: location.lat,
                    lng: location.lng
                };
            }),
            geodesic: true,
            strokeColor: '#FF0000',
            strokeOpacity: 1.0,
            strokeWeight: 2
        });

        // Menampilkan polyline pada peta
        polyline.setMap(map);

        // Menghubungkan kembali ke lokasi awal
        var firstLocation = locations[0];
        polyline.getPath().push(new google.maps.LatLng(firstLocation.lat, firstLocation.lng));

        // Membuat batas pandang peta agar semua marker terlihat
        var bounds = new google.maps.LatLngBounds();
        for (var i = 0; i < markers.length; i++) {
            bounds.extend(markers[i].getPosition());
        }
        map.fitBounds(bounds);
    }

</script>

<script src="https://code.jquery.com/jquery-3.6.1.min.js"
    integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBmBL3_MRsk7qiOqSXgNr-x59cz_vXU9Fg&callback=initialize">
</script>

@include('layout.footer')
@include('layout.js')
