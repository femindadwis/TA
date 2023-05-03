@include('layout.header')
@include('layout.navbar')
@include('layout.sidebar')

<style>
    #map-canvas {
        width: 100%;
        height: 500px;
    }
</style>

<script type="text/javascript">
    var marker;
    var locations
    function initialize() {
        var mapCanvas = document.getElementById('map-canvas');
        var mapOptions = {
            mapTypeId: google.maps.MapTypeId.ROADMAP
        }
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
                "<br> <b>Latitude : </b> " + item.lat +
                "<br> <b>Longtitude : </b>" + item.lng,);
        })

    }
    google.maps.event.addDomListener(window, 'load', initialize);
</script>

<div class="page-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-6">
                <div class="card" style="width: 72rem; height: 40rem;">
                    <div class="card-header pb-0">
                        <h5>Map at a specified location</h5>
                        <span>Display a map at a specified location and zoom level.</span>
                    </div>
                    <div class="card-body">
                        <div id="map-canvas"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB2Xd4GJtDxGPUI7nlMV-I99x5EQqYqhGc&callback=initialize"></script>

@include('layout.footer')
@include('layout.js')
