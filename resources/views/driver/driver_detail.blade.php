@include('layout.header')
@include('layout.navbar')
@include('layout.sidebar')

<div class="page-body">
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="email-wrap bookmark-wrap">
            <div class="row">
                <div class="col-xl-3">
                    <div class="email-sidebar"><a class="btn btn-primary email-aside-toggle"
                            href="javascript:void(0)">bookmark filter</a>
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
                                        <ul class="nav main-menu" role="tablist">
                                            <li class="nav-item"><span class="main-title"> Details</span></li><br>
                                            <li class="nav-item">

                                            <li><a class="show" id="pills-favourites-tab" data-bs-toggle="pill"
                                                    href="#pills-favourites" role="tab"
                                                    aria-controls="pills-favourites" aria-selected="false"><span
                                                        class="title"> Rute</span></a></li>
                                            <li><a class="show" id="pills-shared-tab" data-bs-toggle="pill"
                                                    href="#pills-shared" role="tab" aria-controls="pills-shared"
                                                    aria-selected="false"><span class="title"> Jarak</span></a></li>
                                            <li><a class="show" id="pills-bookmark-tab" data-bs-toggle="pill"
                                                    href="#pills-bookmark" role="tab" aria-controls="pills-bookmark"
                                                    aria-selected="false"><span class="title"> My bookmark</span></a>
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
                <div class="fade tab-pane fade active show" id="pills-favourites" role="tabpanel" aria-labelledby="pills-favourites-tab">
                    <div class="card mb-0">
                        <div class="card-header d-flex">
                            <h6 class="mb-0">Rute</h6>
                        </div>
                        <div class="card-body">
                            <div class="details-bookmark text-center">
{{-- mulai --}}
<div id="map-canvas"></div>

<p>Optimal Route:</p>
<ol>
    @foreach ($optimalRoute as $key => $locationId)
        <li>{{ collect($locations)->where('id', $locationId)->first()['name'] }}</li>
    @endforeach
</ol>

<p>Total Distance: {{ $totalDistance }} km</p>

<script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&callback=initMap" async defer></script>
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
                map: map
            });
            markers.push(marker);
        }

        var routeCoordinates = [];
        for (var i = 0; i < markers.length; i++) {
            routeCoordinates.push(markers[i].getPosition());
        }

        var route = new google.maps.Polyline({
            path: routeCoordinates,
            geodesic: true,
            strokeColor: '#FF0000',
            strokeOpacity: 1.0,
            strokeWeight: 2
        });

        route.setMap(map);

        var bounds = new google.maps.LatLngBounds();
        for (var i = 0; i < markers.length; i++) {
            bounds.extend(markers[i].getPosition());
        }
        map.fitBounds(bounds);
    }
</script>
{{-- selesai --}}


                            </div>  </div></div></div>
                <div class="fade tab-pane" id="pills-shared" role="tabpanel" aria-labelledby="pills-shared-tab">
                    <div class="card mb-0">
                        <div class="card-header d-flex">
                            <h6 class="mb-0">Jarak</h6>
                        </div>
                        <div class="card-body">
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
                                                                    {{ $distances[$location->id][$destLocation->id] }} km
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
                        </div></div></div>
                    </div>
                </div>
                {{-- akhir --}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="fade tab-pane" id="pills-shared" role="tabpanel" aria-labelledby="pills-shared-tab">
              <div class="card mb-0">
                <div class="card-header d-flex">
                  <h6 class="mb-0">Shared with me</h6>
                  <ul>
                    <li><a class="grid-bookmark-view" href="javascript:void(0)"><i data-feather="grid"></i></a></li>
                    <li><a class="list-layout-view" href="javascript:void(0)"><i data-feather="list"></i></a></li>
                  </ul>
                </div>
                <div class="card-body">
                  <div class="details-bookmark text-center"><span>No Bookmarks Found.</span></div>
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
@include('layout.footer')
@include('layout.js')
