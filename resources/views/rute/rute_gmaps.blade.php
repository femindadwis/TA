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
<script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBmBL3_MRsk7qiOqSXgNr-x59cz_vXU9Fg&callback=initMap" async defer></script>
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
                        </div>
                    </div>

<br>
                    @foreach ( $driver as $d )
                    <button><a href="/rute/rute_gmaps/perdriver/{{ $d->id }}">{{ $d->name }}</a></button>
                    @endforeach
                </div></div></div></div>
                        <script>
                            function initMap() {
                                // Array of locations and lines
                                var locations = @json($locations);
                                var lines = @json($lines);

                                // Create a map centered at TPA Pecuk
                                var map = new google.maps.Map(document.getElementById('map-canvas'), {
                                    center: { lat: {{ $tpaPecuk['lat'] }}, lng: {{ $tpaPecuk['lng'] }} },
                                    zoom: 13
                                });

                                // Add markers for each location
                                for (var i = 0; i < locations.length; i++) {
                                    var driverLocations = locations[i];
                                    var color = getRandomColor(); // Generate a random color for each driver

                                    for (var j = 0; j < driverLocations.length; j++) {
                                        var location = driverLocations[j];

                                        var marker = new google.maps.Marker({
                                            position: { lat: parseFloat(location[2]), lng: parseFloat(location[1]) },
                                            map: map,
                                            title: location[0],
                                            icon: {
                                                path: google.maps.SymbolPath.CIRCLE,
                                                fillColor: color,
                                                fillOpacity: 0.5, // Set the fill opacity to a low value
                                                strokeWeight: 0,
                                                scale: 6
                                            }
                                        });

                                        // Add an event listener to display the location name when marker is clicked
                                        marker.addListener('click', function() {
                                            var infoWindow = new google.maps.InfoWindow({
                                                content: this.getTitle()
                                            });
                                            infoWindow.open(map, this);
                                        });
                                    }

                                    // Add a polyline to connect the locations and return to TPA Pecuk
                                    var driverLine = lines[i];
                                    var lineCoordinates = [];
                                    for (var k = 0; k < driverLine.length; k++) {
                                        var line = driverLine[k];
                                        lineCoordinates.push({ lat: parseFloat(line.lat), lng: parseFloat(line.lng) });
                                    }

                                    lineCoordinates.push({ lat: {{ $tpaPecuk['lat'] }}, lng: {{ $tpaPecuk['lng'] }} }); // Add TPA Pecuk as the last point

                                    var polyline = new google.maps.Polyline({
                                        path: lineCoordinates,
                                        geodesic: true,
                                        strokeColor: color,
                                        strokeOpacity: 1.0,
                                        strokeWeight: 2,
                                        map: map
                                    });
                                }
                            }

                            // Generate a random color
                            function getRandomColor() {
                                var letters = '0123456789ABCDEF';
                                var color = '#';
                                for (var i = 0; i < 6; i++) {
                                    color += letters[Math.floor(Math.random() * 16)];
                                }
                                return color;
                            }
                        </script>
    @include('layout.footer')
    @include('layout.js')
