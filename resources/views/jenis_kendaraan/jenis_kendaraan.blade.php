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
            <h5>Tabel Jenis Kendaraan       <a class="fa fa-plus-square-o" href="/jenis_kendaraan/tambah" title="Edit"></a>    </h5>
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
                            <th>Jenis Kendaraan</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach($jenis_kendaraan as $jk)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $jk->jenis_kendaraan }} </td>


                            <td>
                                <a class="fa fa-edit" href="{{route('jenis_kendaraan.edit', $jk->id)}}" title="Edit"></span></a>
                                <a class="btn btn-sm btn-success-outline" href="/jenis_kendaraan/hapus/{{ $jk->id }}" title="Hapus"><span class="fa fa-trash-o"></span></a>
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
