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
                    <h5>Jarak Lokasi {{ $driver->user->name }}  </h5>
                </div>
                <div class="card-body" style="padding-top: 2px;">
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
                                                                {{ $distances[$location->id][$destLocation->id] }}
                                                                km
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
                            </div>
                        </div>
                    </div>
                </div>


</div></div>
</div></div>
</div></div>
</div></div>
</div></div>

    @include('layout.footer')
    @include('layout.js')
