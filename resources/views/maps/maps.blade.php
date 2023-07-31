@include('layout.header')
@include('layout.navbar')
@include('layout.sidebar')
<link href="https://api.mapbox.com/mapbox-gl-js/v2.6.1/mapbox-gl.css" rel="stylesheet">
<link rel="stylesheet"
    href="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-directions/v4.1.0/mapbox-gl-directions.css"
    type="text/css" />
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
<script src="https://api.mapbox.com/mapbox-gl-js/v2.6.1/mapbox-gl.js"></script>
<script src="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-directions/v4.1.0/mapbox-gl-directions.js"></script>

</head>
<div class="page-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-6 xl-100 col-lg-12 box-col-12">
                <div class="card">
                    <div class="card-header pb-0">

                        <h5 class="pull-left">MAPS</h5>

                    </div>
                    <div class="card-body">
                        <div class="tabbed-card">
                            <ul class="pull-right nav nav-pills nav-primary" id="pills-clrtab1" role="tablist">
                                <li class="nav-item"><a class="nav-link active" id="pills-clrhome-tab1"
                                        data-bs-toggle="pill" href="#pills-clrhome1" role="tab"
                                        aria-controls="pills-clrhome1" aria-selected="true">SEMUA</a></li>
                                <li class="nav-item"><a class="nav-link" id="pills-clrprofile-tab1"
                                        data-bs-toggle="pill" href="#pills-clrprofile1" role="tab"
                                        aria-controls="pills-clrprofile1" aria-selected="false">DRIVER</a></li>
                            </ul>
                            <div class="tab-content" id="pills-clrtabContent1">
                                <div class="tab-pane fade show active" id="pills-clrhome1" role="tabpanel"
                                    aria-labelledby="pills-clrhome-tab1">
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
<script>
    mapboxgl.accessToken =
        'pk.eyJ1Ijoicnl0b2RldiIsImEiOiJjbGtncDB3a3YwMXV3M2VvOHFqdmd2NWY4In0.pag9rpV51QYupsyPdSFfOw';

    const locations = @json($locations);
    var minLat = Infinity;
    var maxLat = -Infinity;
    var minLng = Infinity;
    var maxLng = -Infinity;


    function initMap() {
        var map = new mapboxgl.Map({
            container: 'map-canvas',
            style: 'mapbox://styles/mapbox/streets-v11',
            center: [108.280413, -6.408218], // indramayu
            zoom: 12
        });

        for (var i = 0; i < locations.length; i++) {
            var location = locations[i];
            var marker = new mapboxgl.Marker({
                    color: 'red'
                })
                .setLngLat([location.lng, location.lat])
                .setPopup(new mapboxgl.Popup({
                    offset: 25
                }).setHTML("<b>Nama Lokasi : </b>" + location.name +
                    "<br> <b>Latitude : </b> " + location.lat +
                    "<br> <b>Longtitude : </b>" + location.lng))
                .addTo(map);

            minLat = Math.min(minLat, location.lat);
            maxLat = Math.max(maxLat, location.lat);
            minLng = Math.min(minLng, location.lng);
            maxLng = Math.max(maxLng, location.lng);
        }

        map.fitBounds([
            [minLng, minLat], // southwestern corner of the bounds
            [maxLng, maxLat] // northeastern corner of the bounds
        ], {
            padding: 50
        });
        getRoute(map, locations);
    }



    // buat manggil fungsi keduanya
    window.addEventListener('load', function() {
        initMap();
    });
</script>
@include('layout.footer')
@include('layout.js')
