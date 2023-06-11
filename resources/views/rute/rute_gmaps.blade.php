@include('layout.header')
@include('layout.navbar')
@include('layout.sidebar')

<style>
    #map-canvas {
        width: 100%;
        height: 500px;
    }
</style>

<div class="page-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-6">
                <div class="card" style="width: 72rem; height: 40rem;">
                    <div class="card-header pb-0">
                        <h5>RUTE GOOGLE MAPS OPTIMIZER</h5>
                        <span>Tampilan Rute optimasi Google Maps</span>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div id="map-canvas"></div>
                            </div>
                            <div class="col-md-12">
                                <div id="list-direction"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var marker;
    var locations;
    var directionsService;
    var directionsRenderer;

    function initialize() {
        var mapCanvas = document.getElementById('map-canvas');
        var mapOptions = {
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        var map = new google.maps.Map(mapCanvas, mapOptions);
        var infoWindow = new google.maps.InfoWindow;
        var bounds = new google.maps.LatLngBounds();

        function bindInfoWindow(marker, map, infoWindow, html) {
            google.maps.event.addListener(marker, 'click', function() {
                infoWindow.setContent(html);
                infoWindow.open(map, marker);
            });
        }

        function addMarker(lat, lng, info) {
            var pt = new google.maps.LatLng(lat, lng);
            bounds.extend(pt);
            var marker = new google.maps.Marker({
                map: map,
                position: pt
            });
            map.fitBounds(bounds);
            bindInfoWindow(marker, map, infoWindow, info);
        }

        const items = @json($locations);

        items.forEach(item => {
            addMarker(
                item.lat,
                item.lng,
                "<b>Nama Lokasi : </b>" + item.name +
                "<br> <b>Latitude : </b>" + item.lat +
                "<br> <b>Longitude : </b>" + item.lng,
            );
        });

        var directionsService = new google.maps.DirectionsService();
        var directionsRenderer = new google.maps.DirectionsRenderer({
            suppressMarkers: true,
            polylineOptions: {
                strokeColor: '#FF0000',
                strokeOpacity: 0.8,
                strokeWeight: 2
            }
        });

        directionsRenderer.setMap(map);

        var startLocation = items.find(item => item.id === 1); // Lokasi awal dengan ID 1
        var endLocation = items.find(item => item.id === 1); // Lokasi tujuan dengan ID 1

        if (startLocation && endLocation) {
            var waypoints = items
                .filter(item => item.id !== 1) // Exclude start location from waypoints
                .map(item => ({
                    location: new google.maps.LatLng(item.lat, item.lng),
                    stopover: true
                }));

            var request = {
                origin: new google.maps.LatLng(startLocation.lat, startLocation.lng),
                destination: new google.maps.LatLng(endLocation.lat, endLocation.lng), // Kembali lagi ke lokasi aw
                waypoints: waypoints,
optimizeWaypoints: true,
travelMode: google.maps.TravelMode.DRIVING
};
directionsService.route(request, function(result, status) {
                if (status == google.maps.DirectionsStatus.OK) {
                    directionsRenderer.setDirections(result);
                    var route = result.routes[0];
                    var routeLegs = route.legs;
                    var order = 1;

                    var directionsList = document.getElementById('list-direction');

                    routeLegs.forEach(function(leg) {
                        var locationName = leg.start_address.split(',')[1].trim();
                        var description = "<b>Urutan Lokasi:</b> " + order + "<br>" +
                            "<b>Lokasi:</b> " + locationName;

                        var listItem = document.createElement('p');
                        listItem.innerHTML = description;
                        directionsList.appendChild(listItem);
                        order++;
                    });
                }
            });
        }
    }

    google.maps.event.addDomListener(window, 'load', initialize);
</script>

<script src="https://code.jquery.com/jquery-3.6.1.min.js"
    integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBmBL3_MRsk7qiOqSXgNr-x59cz_vXU9Fg&callback=initialize"></script>
@include('layout.footer')
@include('layout.js')
