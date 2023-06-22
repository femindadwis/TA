@include('layout.header')
@include('layout.navbar')
@include('layout.sidebar')

<div class="page-body">
        <div class="container-fluid">
<div class="page-header">
<div class="row">


 <!-- Feature Unable /Disable Order Starts-->
 <div class="col-sm-12">
    <div class="card">
        <div class="card-header">
            <h5>Tabel Jarak           </h5>
            <span>Disabling features that you don't wish to use for a particular table is easily done by setting a variable in the initialisation object</span>
            <span>In the following example only the search feature is left enabled (which it is by default).</span><br>

        <div class="card-body">
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





                        </div></div></div></div></div></div></div>
                        @include('layout.footer')
                        @include('layout.js')

