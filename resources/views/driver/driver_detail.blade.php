@include('layout.header')
@include('layout.navbar')
@include('layout.sidebar')
</head>
<style>
    #map-canvas {
        width: 100%;
        height: 500px;
    }

    .info-window {
        max-width: 200px;
    }

    #map-canvas-pso {
        width: 100%;
        height: 500px;
    }
</style>
<script src="https://code.jquery.com/jquery-3.6.1.min.js"
    integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBmBL3_MRsk7qiOqSXgNr-x59cz_vXU9Fg&callback=initMap" async
    defer></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCBp7OGgLXg44C-9rl05qAvSfc9EcpaPQY&callback=initMapPSO"
    async defer></script>
</head>

<div class="page-body">
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="email-wrap bookmark-wrap">
            <div class="row">
                <div class="col-xl-3">
                    <div class="email-sidebar"><a class="btn btn-primary email-aside-toggle"
                            href="javascript:void(0)">Details</a>
                        <div class="email-left-aside">
                            <div class="card">
                                <div class="card-body">
                                    <div class="email-app-sidebar left-bookmark">
                                        <div class="media">
                                            <div class="media-size-email"><img class="me-3 rounded-circle"
                                                    src="https://laravel.pixelstrap.com/viho/assets/images/user/user.png"
                                                    alt=""></div>
                                            <div class="media-body">

                                                <h6 class="f-w-600">{{ $driver->user->name }}</h6>

                                                <p>{{ $driver->alamat }}</p><br>
                                            </div>
                                        </div>
                                        <div class="media-body">
                                            <h6 class="f-w-600">Lokasi Driver</h6>

                                            @foreach ($driver_lokasi as $key => $lokasi)
                                                <p>{{ $lokasi->lokasi->name }}</p>
                                            @endforeach

                                        </div><br>

                                        <ul class="nav main-menu" role="tablist">
                                            <li class="nav-item"><span class="main-title">Details</span></li><br>
                                            <li class="nav-item">

                                            <li><a class="show" id="pills-favourites-tab" data-bs-toggle="pill"
                                                    href="#pills-favourites" role="tab"
                                                    aria-controls="pills-favourites" aria-selected="false"><span
                                                        class="title">Jarak</span></a></li>
                                            <li><a class="show" id="pills-shared-tab" data-bs-toggle="pill"
                                                    href="#pills-shared" role="tab" aria-controls="pills-shared"
                                                    aria-selected="false"><span class="title">Rute Optimizer</span></a>
                                            </li>
                                            <li><a class="show" id="pills-bookmark-tab" data-bs-toggle="pill"
                                                    href="#pills-bookmark" role="tab" aria-controls="pills-bookmark"
                                                    aria-selected="false"><span class="title">Rute PSO</span></a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- CONTENT --}}
                <div class="col-xl-9 col-md-12 box-col-8">
                    <div class="tab-content">
                        <div class="fade tab-pane fade active show" id="pills-favourites" role="tabpanel"
                            aria-labelledby="pills-favourites-tab">
                            <div class="card mb-0">
                                <div class="card-header d-flex">
                                    <h6 class="mb-0">Jarak</h6>
                                </div>
                                <div class="card-body" style="padding-top: 5px;">
                                    <div class="details-bookmark text-center">
                                        {{-- mulai sini --}}
                                        <div class="row" id="favouriteData"></div>
                                        <div class="no-favourite">

                                            <div class="table-responsive">
                                                <table class="display" id="basic-2">
                                                    <thead>
                                                        <tr>
                                                            <th>Lokasi</th>
                                                            @foreach ($locations as $location)
                                                                <th>{{ $location->name }}</th>
                                                            @endforeach
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($locations as $location)
                                                            <tr>
                                                                <td>{{ $location->name }}</td>
                                                                @foreach ($locations as $destLocation)
                                                                    <td>
                                                                        @if ($location->id === $destLocation->id)
                                                                            -
                                                                        @elseif (isset($distances[$location->id][$destLocation->id]))
                                                                            {{ $distances[$location->id][$destLocation->id] }}
                                                                            km
                                                                        @else
                                                                            -
                                                                        @endif
                                                                    </td>
                                                                @endforeach
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <div class="fade tab-pane" id="pills-shared" role="tabpanel" aria-labelledby="pills-shared-tab">
                            <div class="card mb-0">
                                <div class="card-header d-flex">
                                    <h6 class="mb-0">Rute</h6>
                                </div>
                                <div class="card-body" style="padding-top: 5px;">

                                    <div id="map-canvas"></div>
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

                                        <div lass="mt-3">
                                            @if (isset($totalDistance))
                                                Total Distance: {{ $totalDistance }} km<br>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- akhir --}}

                        {{-- CONTENT PSO  --}}
                        <div class="fade tab-pane" id="pills-bookmark" role="tabpanel"
                            aria-labelledby="pills-bookmark-tab">
                            <div class="card mb-0">
                                <div class="card-header d-flex">
                                    <h6 class="mb-0">PSO</h6>
                                </div>
                                <div class="card-body" style="padding-top: 5px;">

                                    <div id="map-canvas-pso"></div>
                                    <!-- Tampilkan urutan lokasi -->
                                    <div id="route-order" class="mt-3">
                                        @if (isset($optimalRoutePSO))
                                            <p>Optimal Route:</p>
                                            <ol>
                                                @foreach ($optimalRoutePSO ?? [] as $key => $locationId)
                                                <li>
                                                    {{ collect($locations)->where('id', $locationId)->first()['name'] }}
                                                </li>
                                            @endforeach

                                                @if (end($optimalRoutePSO) !== 1)
                                                    <li>
                                                        {{ collect($locations)->where('id', 1)->first()['name'] }}
                                                    </li>
                                                @endif
                                            </ol>
                                        @endif

                                        <div lass="mt-3">
                                            @if (isset($totaljarak))
                                                Total Distance: {{ $totaljarak }} km<br>
                                            @endif
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
</div>
<script>
    function initMap() {
        var map = new google.maps.Map(document.getElementById('map-canvas'), {
            zoom: 12,
            center: new google.maps.LatLng(-6.123456, 106.789012)
        });

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
                title: location.name // Menampilkan nama lokasi saat marker diklik
            });
            markers.push(marker);
        }

        var directionsService = new google.maps.DirectionsService();
        var directionsRenderer = new google.maps.DirectionsRenderer({
            suppressMarkers: true,
            polylineOptions: {
                strokeColor: '#FF0000',
                strokeOpacity: 1.0,
                strokeWeight: 2
            },
            map: map
        });

        var waypoints = locations.slice(1, locations.length - 1).map(function(location) {
            return {
                location: new google.maps.LatLng(location.lat, location.lng),
                stopover: true
            };
        });
        var request = {
            origin: new google.maps.LatLng(locations[0].lat, locations[0].lng),
            destination: new google.maps.LatLng(locations[locations.length - 1].lat, locations[locations.length - 1]
                .lng),
            waypoints: waypoints,
            optimizeWaypoints: true,
            travelMode: google.maps.TravelMode.DRIVING
        };
        directionsService.route(request, function(response, status) {
            if (status == google.maps.DirectionsStatus.OK) {
                directionsRenderer.setDirections(response);
                var route = response.routes[0];
                var legs = route.legs;
                var lastLeg = legs[legs.length - 1];
                var lastLocation = lastLeg.end_location;

                // Menambahkan marker lokasi mulai sebagai waypoint terakhir
                var startMarker = new google.maps.Marker({
                    position: request.origin,
                    map: map
                });
                // Menghubungkan lokasi terakhir ke lokasi mulai
                var routeToStart = new google.maps.DirectionsRenderer({
                    suppressMarkers: true,
                    polylineOptions: {
                        strokeColor: '#FF0000',
                        strokeOpacity: 1.0,
                        strokeWeight: 2
                    }
                });
                routeToStart.setMap(map);

                var routeRequestToStart = {
                    origin: lastLocation,
                    destination: request.origin,
                    travelMode: google.maps.TravelMode.DRIVING
                };

                directionsService.route(routeRequestToStart, function(response, status) {
                    if (status == google.maps.DirectionsStatus.OK) {
                        routeToStart.setDirections(response);
                    }
                });
            }
        });


        // Menambahkan event listener untuk menampilkan info window saat marker diklik
        markers.forEach(function(marker) {
            marker.addListener('click', function() {
                var infoWindow = new google.maps.InfoWindow({
                    content: marker.title
                });
                infoWindow.open(map, marker);
            });
        });

        // Fit map bounds to show all markers and route
        var bounds = new google.maps.LatLngBounds();
        for (var i = 0; i < markers.length; i++) {
            bounds.extend(markers[i].getPosition());
        }
        map.fitBounds(bounds);
    }

    function initMapPSO() {
        var map = new google.maps.Map(document.getElementById('map-canvas-pso'), {
            zoom: 12,
            center: new google.maps.LatLng(-6.123456, 106.789012)
        });

        var locations = [
            @foreach ($optimalRoutePSO as $locationId)
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
                title: location.name // Menampilkan nama lokasi saat marker diklik
            });
            markers.push(marker);
        }

        var directionsService = new google.maps.DirectionsService();
        var directionsRenderer = new google.maps.DirectionsRenderer({
            suppressMarkers: true,
            polylineOptions: {
                strokeColor: '#FF0000',
                strokeOpacity: 1.0,
                strokeWeight: 2
            },
            map: map
        });

        var waypoints = locations.slice(1, locations.length - 1).map(function(location) {
            return {
                location: new google.maps.LatLng(location.lat, location.lng),
                stopover: true
            };
        });
        var request = {
            origin: new google.maps.LatLng(locations[0].lat, locations[0].lng),
            destination: new google.maps.LatLng(locations[locations.length - 1].lat, locations[locations.length - 1]
                .lng),
            waypoints: waypoints,
            optimizeWaypoints: true,
            travelMode: google.maps.TravelMode.DRIVING
        };
        directionsService.route(request, function(response, status) {
            if (status == google.maps.DirectionsStatus.OK) {
                directionsRenderer.setDirections(response);
                var route = response.routes[0];
                var legs = route.legs;
                var lastLeg = legs[legs.length - 1];
                var lastLocation = lastLeg.end_location;

                // Menambahkan marker lokasi mulai sebagai waypoint terakhir
                var startMarker = new google.maps.Marker({
                    position: request.origin,
                    map: map
                });
                // Menghubungkan lokasi terakhir ke lokasi mulai
                var routeToStart = new google.maps.DirectionsRenderer({
                    suppressMarkers: true,
                    polylineOptions: {
                        strokeColor: '#FF0000',
                        strokeOpacity: 1.0,
                        strokeWeight: 2
                    }
                });
                routeToStart.setMap(map);

                var routeRequestToStart = {
                    origin: lastLocation,
                    destination: request.origin,
                    travelMode: google.maps.TravelMode.DRIVING
                };

                directionsService.route(routeRequestToStart, function(response, status) {
                    if (status == google.maps.DirectionsStatus.OK) {
                        routeToStart.setDirections(response);
                    }
                });
            }
        });


        // Menambahkan event listener untuk menampilkan info window saat marker diklik
        markers.forEach(function(marker) {
            marker.addListener('click', function() {
                var infoWindow = new google.maps.InfoWindow({
                    content: marker.title
                });
                infoWindow.open(map, marker);
            });
        });

        // Fit map bounds to show all markers and route
        var bounds = new google.maps.LatLngBounds();
        for (var i = 0; i < markers.length; i++) {
            bounds.extend(markers[i].getPosition());
        }
        map.fitBounds(bounds);
    }


    // buat manggil fungsi keduanya
    window.addEventListener('load', function() {
        initMap();
        initMapPSO();
    });
</script>


@include('layout.footer')
@include('layout.js')
