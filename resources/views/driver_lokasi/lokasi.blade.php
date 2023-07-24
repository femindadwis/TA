@include('layout.header')
@include('layout.navbar')
@include('layout.sidebar')

<div class="page-body">
    <!-- Container-fluid starts-->
        <div class="container-fluid">
<div class="page-header">
<div class="row">


    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h5>Tabel Lokasi Driver {{ Auth::user()->name }}</h5>
                <span>Disabling features that you don't wish to use for a particular table is easily done by setting a variable in the initialisation object</span>
                <span>In the following example only the search feature is left enabled (which it is by default).</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="display" id="basic-2">
                        <thead>
                            <tr>
                                <?php $no=1; ?>
                                <th>No</th>
                                <th>Lokasi</th>
                                <th>Alamat</th>
                                <th>Longitude</th>
                                <th>Latitude</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ( $driver_lokasi as $dl )
<tr>

                                             <td>{{ $no++ }}</td>
                                            <td>{{ $dl->lokasi->name }}</td>
                                            <td>{{ $dl->lokasi->alamat }}</td>
                                            <td>{{ $dl->lokasi->lng }}</td>
                                            <td>{{ $dl->lokasi->lat }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div></div>
@include('layout.footer')
@include('layout.js')
