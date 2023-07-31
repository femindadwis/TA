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

                        <h5 class="pull-left">Rute {{ $driver->user->name }}</h5>

                    </div>
                    <div class="card-body">
                        <div class="tabbed-card">
                            <ul class="pull-right nav nav-pills nav-primary" id="pills-clrtab1" role="tablist">
                                <li class="nav-item"><a class="nav-link active" id="pills-clrhome-tab1"
                                        data-bs-toggle="pill" href="#pills-clrhome1" role="tab"
                                        aria-controls="pills-clrhome1" aria-selected="true">PSO</a></li>
                                <li class="nav-item"><a class="nav-link" id="pills-clrprofile-tab1"
                                        data-bs-toggle="pill" href="#pills-clrprofile1" role="tab"
                                        aria-controls="pills-clrprofile1" aria-selected="false">Nearest Neighbor</a></li>
                            </ul>
                            <div class="tab-content" id="pills-clrtabContent1">
                                <div class="tab-pane fade show active" id="pills-clrhome1" role="tabpanel"
                                    aria-labelledby="pills-clrhome-tab1">
                                    {{-- <div class="card-body" style="padding-top: 5px;"> --}}

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
                                <div class="tab-pane fade" id="pills-clrprofile1" role="tabpanel"
                                    aria-labelledby="pills-clrprofile-tab1">

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
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>


<script>
    mapboxgl.accessToken =
        'pk.eyJ1Ijoicnl0b2RldiIsImEiOiJjbGtncDB3a3YwMXV3M2VvOHFqdmd2NWY4In0.pag9rpV51QYupsyPdSFfOw';
    var minLat = Infinity;
    var maxLat = -Infinity;
    var minLng = Infinity;
    var maxLng = -Infinity;

    function initMapPSO() {
        var map = new mapboxgl.Map({
            container: 'map-canvas-pso',
            style: 'mapbox://styles/mapbox/streets-v11',
            center: [108.280413, -6.408218], // indramayu
            zoom: 12
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

    function initMap() {
        var map = new mapboxgl.Map({
            container: 'map-canvas',
            style: 'mapbox://styles/mapbox/streets-v11',
            center: [108.280413, -6.408218], // indramayu
            zoom: 12
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

    async function getRoute(map, locations) {
        var waypoints = locations.map(location => `${location.lng},${location.lat}`).join(';');
        const query = await fetch(
            `https://api.mapbox.com/directions/v5/mapbox/driving/${waypoints}?&geometries=geojson&access_token=${mapboxgl.accessToken}`, {
                method: 'GET'
            }
        );
        console.log(waypoints);
        const json = await query.json();
        const data = json.routes[0];
        const route = data.geometry.coordinates;
        const geojson = {
            type: 'Feature',
            properties: {},
            geometry: {
                type: 'LineString',
                coordinates: route
            }
        };
        // if the route already exists on the map, we'll reset it using setData
        if (map.getSource('route')) {
            map.getSource('route').setData(geojson);
        }
        // otherwise, we'll make a new request
        else {
            map.addLayer({
                id: 'route',
                type: 'line',
                source: {
                    type: 'geojson',
                    data: geojson
                },
                layout: {
                    'line-join': 'round',
                    'line-cap': 'round'
                },
                paint: {
                    'line-color': '#3887be',
                    'line-width': 10,
                }
            });
        }
    }

    // buat manggil fungsi keduanya
    window.addEventListener('load', function() {
        initMapPSO();
        initMap();
    });
</script>
@include('layout.footer')
@include('layout.js')
