@include('layout.header')
@include('layout.navbar')
@include('layout.sidebar')

<style type="text/css">
    #map {
        height: 400px;
    }
</style>


<script type="text/javascript">
    var dataLoc = [];
    //     window.onload = function() {
    //     onLoad(0);
    // };

    document.addEventListener('DOMContentLoaded', function() {
        onLoad(0); // Jalankan fungsi fetchData saat halaman selesai dimuat
    });

    function onLoad(dataDriver = null) {
        event.preventDefault(); // Mencegah reload halaman

        // console.log(dataDriver, 'dataDriver');
        fetch('/maps/data/' + dataDriver)
            .then(function(response) {
                return response.json();
            })
            .then(function(data) {
                // Handle the response data
                // console.log(data);
                dataLoc = data;
                initMap(dataLoc)
                addButton(dataLoc)

                // Perform any additional actions or update the UI based on the data
            })
            .catch(function(error) {
                // Handle any errors
                console.error('Error:', error);
            });
        // Perform actions or initialization tasks when the page is loaded
        // ...
    }


    function addButton(data) {
        // console.log(data)

        var btndriverContainer = document.getElementById('btndriver');

        data.locations.forEach(function(driver, index) {
            // Check if a button with the same data-driver attribute value already exists
            var existingButton = btndriverContainer.querySelector('[data-driver="' + index + '"]');

            if (!existingButton) {
                var button = document.createElement('button');
                button.type = 'button';
                button.classList.add('btn', 'btn-primary');
                button.innerText = 'Driver ' + parseInt(index + 1);
                button.setAttribute('data-driver', parseInt(index));
                button.addEventListener('click', function() {
                    // Handle button click event
                    var driverNumber = button.getAttribute('data-driver');
                    console.log('Button clicked for Driver ' + driverNumber);

                    // Call a function when the button is clicked
                    onLoad(driverNumber);
                });
                btndriverContainer.appendChild(button);
            }
        });
    }

    function initMap(data) {


        console.log(data, 'data');

        const myLatLng = {
            lat: -6.340748,
            lng: 108.315415
        };
        const map = new google.maps.Map(document.getElementById("map"), {
            zoom: 13,
            center: myLatLng,
        });


        var locations = data.locations[data.driver];
        var lines = data.lines[data.driver];

        var infowindow = new google.maps.InfoWindow();

        var marker, i;

        const linesPath = new google.maps.Polyline({
            path: lines,
            geodesic: true,
            strokeColor: "#FF0000",
            strokeOpacity: 1.0,
            strokeWeight: 2,
        });

        linesPath.setMap(map);
        console.log(locations.length, 'length')
        for (i = 0; i < locations.length; i++) {
            marker = new google.maps.Marker({
                position: new google.maps.LatLng(locations[i][1], locations[i][2]),
                map: map
            });

            google.maps.event.addListener(marker, 'click', (function(marker, i) {
                return function() {
                    infowindow.setContent(locations[i][0]);
                    infowindow.open(map, marker);
                }
            })(marker, i));

        }


    }
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
                        <div id="map"></div>
                    </div>
                    <!-- untuk admin -->
                    <!-- <button>driver a</button>
                    <button>driver b</button>
                    <button>driver c</button> -->
                    <!-- untuk admin -->


                    @if(auth()->user()->level == 1)
                    <div class="container-fluid ">

                        <div class="row justify-center">
                            <div id="btndriver"></div>
                        </div>
                    </div>
                    @endif


                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.1.min.js"
    integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBmBL3_MRsk7qiOqSXgNr-x59cz_vXU9Fg&callback=initMap" async
    defer></script>
@include('layout.footer')
@include('layout.js')
