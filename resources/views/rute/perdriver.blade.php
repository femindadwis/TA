@include('layout.header')
@include('layout.navbar')
@include('layout.sidebar')
<style>
    #map-canvas {
        width: 100%;
        height: 500px;
    }

    .info-window {
        max-width: 200px;
    }
</style>
<script src="https://code.jquery.com/jquery-3.6.1.min.js"
    integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBmBL3_MRsk7qiOqSXgNr-x59cz_vXU9Fg&callback=initMap" async
    defer></script>
</head>

<body>
    <div class="page-body">
        <div class="container-fluid">
            <div class="card" style="width: auto; height: auto;">
                <div class="card-header pb-0">
                    <h5>RUTE GOOGLE MAPS OPTIMIZER</h5>
                    <span>Tampilan Rute optimasi Google Maps</span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div id="map-canvas"></div>
                            <div class="card mt-3">
                                <div class="card-header">
                                    <h5>Urutan Lokasi</h5>
                                </div>
                                    <ol>
                                        @foreach($locations[$driver->id - 1] as $key => $location)
                                        @if ($key === 0 || $key === count($locations[$driver->id - 1]) )
                                            <li>{{ $tpaPecuk['name'] }}</li>
                                        @else
                                            <li>{{ $location['name'] }}</li>
                                        @endif

                                    @endforeach
                                    <li>{{ $tpaPecuk['name'] }}</li>
                                </ol>
                                </div>
                                <div class="card">
                                    <div class="card-body">
                                        <p>Total Jarak {{ $driver->name }}: {{ $totalJarakPerDriver[$driver->id - 1] }} km</p>
                                    </div>
                                </div>


                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function initMap() {
            var locations = @json($locations);
            var map = new google.maps.Map(document.getElementById('map-canvas'), {
                center: {
                    lat: {{ $tpaPecuk['lat'] ?? 0 }},
                    lng: {{ $tpaPecuk['lng'] ?? 0 }}
                },
                zoom: 10
            });

            var selectedDriverId = {{ $driver->id ?? 0 }} - 1; // Adjusted indexing

            if (selectedDriverId >= 0 && selectedDriverId < locations.length) {
                var selectedLocations = locations[selectedDriverId];

                var directionsService = new google.maps.DirectionsService();
                var directionsRenderer = new google.maps.DirectionsRenderer({
                    map: map,
                    suppressMarkers: true,
                    polylineOptions: {
                        strokeColor: '#FF0000',
                        strokeOpacity: 1.0,
                        strokeWeight: 2
                    }
                });

                var waypoints = [];
                for (var i = 1; i < selectedLocations.length; i++) {
                    waypoints.push({
                        location: new google.maps.LatLng(parseFloat(selectedLocations[i].lat), parseFloat(
                            selectedLocations[i].lng)),
                        stopover: true
                    });
                }

                var request = {
                    origin: new google.maps.LatLng(parseFloat(selectedLocations[0].lat), parseFloat(selectedLocations[0]
                        .lng)),
                    destination: new google.maps.LatLng(parseFloat(selectedLocations[selectedLocations.length - 1].lat),
                        parseFloat(selectedLocations[selectedLocations.length - 1].lng)),
                    waypoints: waypoints,
                    travelMode: 'DRIVING'
                };

                directionsService.route(request, function(result, status) {
                    if (status == 'OK') {
                        directionsRenderer.setDirections(result);
                    }
                });

                for (var j = 0; j < selectedLocations.length; j++) {
                    var location = selectedLocations[j];
                    var marker = new google.maps.Marker({
                        position: new google.maps.LatLng(parseFloat(location.lat), parseFloat(location.lng)),
                        map: map,
                        title: location.name
                    });

                    marker.addListener('click', function() {
                        var infoWindow = new google.maps.InfoWindow({
                            content: this.getTitle()
                        });
                        infoWindow.open(map, this);
                    });
                }
            }
        }
    </script>
    @include('layout.footer')
    @include('layout.js')
