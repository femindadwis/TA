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

    #map {
        width: 100%;
        height: 500px;
    }
</style>
<script src="https://code.jquery.com/jquery-3.6.1.min.js"
    integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
{{-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBmBL3_MRsk7qiOqSXgNr-x59cz_vXU9Fg&callback=initialize"> --}}
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCfDg7Rknio90wPC0XaxJ6-l9JKppBygpU&callback=initMap">
</script>
</head>
<div class="page-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-6 xl-100 col-lg-12 box-col-12">
                <div class="card">
                    <div class="card-header pb-0">

                        <h5 class="pull-left">Maps</h5>

                    </div>
                    <div class="card-body">
                        <div class="tabbed-card">
                            <ul class="pull-right nav nav-pills nav-primary" id="pills-clrtab1" role="tablist">
                                <li class="nav-item"><a class="nav-link active" id="pills-clrhome-tab1"
                                        data-bs-toggle="pill" href="#pills-clrhome1" role="tab"
                                        aria-controls="pills-clrhome1" aria-selected="true">Semua</a></li>
                                <li class="nav-item"><a class="nav-link" id="pills-clrprofile-tab1"
                                        data-bs-toggle="pill" href="#pills-clrprofile1" role="tab"
                                        aria-controls="pills-clrprofile1" aria-selected="false">Driver</a></li>
                            </ul>
                            <div class="tab-content" id="pills-clrtabContent1">
                                <div class="tab-pane fade show active" id="pills-clrhome1" role="tabpanel"
                                    aria-labelledby="pills-clrhome-tab1">
                                    {{-- <div class="card-body" style="padding-top: 5px;"> --}}
                                    <div id="map-canvas"></div>
                                </div>

                                <div class="tab-pane fade" id="pills-clrprofile1" role="tabpanel"
                                    aria-labelledby="pills-clrprofile-tab1">
                                    <div id="map"></div>
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
</div>
</div>

<script>
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
                "<br> <b>Longtitude : </b>" + item.lng, );
        })

    }
    var marker;
    var lokasi = @json($lokasi);

    function initMap() {
        var mapCanvas = document.getElementById('map');
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

        function addMarker(lat, lng, info, color) {
            var pt = new google.maps.LatLng(lat, lng);
            bounds.extend(pt);

            var marker = new google.maps.Marker({
                map: map,
                position: pt,
                icon: getCustomMarkerIcon(color),

            });
            map.fitBounds(bounds);
            bindInfoWindow(marker, map, infoWindow, info);
        }

        function getCustomMarkerIcon(color) {
            // Buat ikon marker dengan latar belakang sesuai warna yang diinginkan.
            return {
                path: google.maps.SymbolPath.CIRCLE,
                fillColor: color, // Warna latar belakang
                fillOpacity: 1,
                strokeColor: '#000',
                strokeWeight: 0,
                scale: 8
            };
        }
        const items = @json($lokasi);

        items.forEach(item => {
            addMarker(
                item.lat,
                item.lng,
                "<b>Nama Lokasi : </b>" + item.name +
                "<br> <b>Latitude : </b> " + item.lat +
                "<br> <b>Driver : </b> " + item.driver_name +
                "<br> <b>Longtitude : </b>" + item.lng,
                item.color,
            );
        });
    }
    // function getMarkerIcon(color) {
    //     return {
    //     url: 'https://maps.google.com/mapfiles/ms/icons/' + lokasi.color + '-dot.png', // Use HTTPS URL
    //     scaledSize: new google.maps.Size(32, 32), // Size of the pin icon
    //     fillColor: color, // Customize the color
    // };

    //     }
    // buat manggil fungsi keduanya
    window.addEventListener('load', function() {
        initialize();
        initMap();
    });
</script>
@include('layout.footer')
@include('layout.js')
