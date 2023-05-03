@include('layout.header')
@include('layout.navbar')
@include('layout.sidebar')

<div class="page-body">
    <!-- Container-fluid starts-->

    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-6">
                <div class="card" style="width: 72rem; height: 40rem;">
                    <div class="card-header pb-0">
                        <h5>Map at a specified location</h5>
                        <span>Display a map at a specified location and zoom level.</span>
                    </div>
                    <div class="card-body">
                        <div class="map-js-height" id="map">
                            <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBmBL3_MRsk7qiOqSXgNr-x59cz_vXU9Fg"></script>
                            <script>
                                function initMap() {
                                    var map = new google.maps.Map(document.getElementById('map'), {
                                        center: {lat: -6.327583, lng: 108.324936},
                                        zoom: 12
                                    });
                                }
                            </script>
                            <script async defer
                            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB2Xd4GJtDxGPUI7nlMV-I99x5EQqYqhGc&callback=initMap">
                        </script>
                    </div>
                </div>
            </div>
        </div>
        {{-- <div id="map"></div>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBmBL3_MRsk7qiOqSXgNr-x59cz_vXU9Fg"></script>
        <script>
            function initMap() {
                var map = new google.maps.Map(document.getElementById('map'), {
                    center: {lat: -6.175392, lng: 106.827153},
                    zoom: 12
                });
            }
        </script>
        <script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBmBL3_MRsk7qiOqSXgNr-x59cz_vXU9Fg&callback=initMap">
    </script> --}}

</div></div></div></div></div></div></div>
@include('layout.footer')
@include('layout.js')
