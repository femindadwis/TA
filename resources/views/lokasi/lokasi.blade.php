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
            <h5>Tabel Lokasi       <a class="fa fa-plus-square-o" href="/lokasi/tambah" title="Edit"></a>    </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="display" id="basic-2">
                    <thead>
                        <tr>
                            <?php $no=1; ?>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Alamat</th>
                            <th>Latitude</th>
                            <th>Longitude</th>
                            <th>Foto</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($lokasi as $l)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $l->name }} </td>
                            <td>{{ $l->alamat }} </td>
                            <td>{{ $l->lat }} </td>
                            <td>{{ $l->lng }} </td>
                            <td><img src="{{ asset('storage/'.$l->foto) }}" alt="" height="100px" width="100px"> </td>


                            <td>
                                <a class="fa fa-edit" href="{{route('lokasi.edit', $l->id)}}" title="Edit"></span></a>
                                <a class="btn btn-sm btn-success-outline" href="/lokasi/hapus/{{ $l->id }}" title="Hapus"><span class="fa fa-trash-o"></span></a>
                            </td>
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
</div>
</div>

</div></div></div></div></div></div></div>
@include('layout.footer')
@include('layout.js')
