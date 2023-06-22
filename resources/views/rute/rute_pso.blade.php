@include('layout.header')
@include('layout.navbar')
@include('layout.sidebar')
<style>
    #map {
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
                    <h5>RUTE PSO</h5>
                    <span>Tampilan Rute optimasi Google Maps</span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
    <div id="map"></div></div>

    <div id="route-order" class="mt-3">
        @if(isset($optimalRoute))
            Optimal Route:
            <ol>
                @foreach($optimalRoute as $locationId)
                    <li>{{ $locations[$locationId-1]['name'] }}</li>
                @endforeach
            </ol>
        @endif
    </div>
</div>
</div>
</div>
</div>


    <script>

        function initMap() {
            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 12,
                center: { lat: {{ $locations[$optimalRoute[0]-1]['lat'] }}, lng: {{ $locations[$optimalRoute[0]-1]['lng'] }} }
            });

            var locations = [
                @foreach($optimalRoute as $locationId)
                    {
                        name: "{{ $locations[$locationId-1]['name'] }}",
                        lat: {{ $locations[$locationId-1]['lat'] }},
                        lng: {{ $locations[$locationId-1]['lng'] }}
                    },
                @endforeach
            ];

            var markers = [];
            for (var i = 0; i < locations.length; i++) {
                var location = locations[i];
                var marker = new google.maps.Marker({
                    position: { lat: location.lat, lng: location.lng },
                    map: map,
                    // label: (i + 1).toString()
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

            var waypoints = locations.slice(1, -1).map(function (location) {
                return {
                    location: new google.maps.LatLng(location.lat, location.lng),
                    stopover: true
                };
            });

            var request = {
                origin: new google.maps.LatLng(locations[0].lat, locations[0].lng),
                destination: new google.maps.LatLng(locations[locations.length - 1].lat, locations[locations.length - 1].lng),
                waypoints: waypoints,
                optimizeWaypoints: true,
                travelMode: google.maps.TravelMode.DRIVING
            };

            directionsService.route(request, function (response, status) {
                if (status == google.maps.DirectionsStatus.OK) {
                    directionsRenderer.setDirections(response);
                }
            });
        }
    </script>

<script src="https://code.jquery.com/jquery-3.6.1.min.js"
    integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBmBL3_MRsk7qiOqSXgNr-x59cz_vXU9Fg&callback=initialize">
</script>

@include('layout.footer')
@include('layout.js')

