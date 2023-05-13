@include('layout.header')
@include('layout.navbar')
@include('layout.sidebar')

<div class="page-body">
    <!-- Container-fluid starts-->
        <div class="container-fluid">
<div class="page-header">
<div class="row">

 <!-- Feature Unable /Disable Order Starts-->
 <div class="col-sm-12">
    <div class="card">
        <div class="card-header">
            <h5>Tabel Lokasi       <a class="fa fa-plus-square-o" href="/lokasi/tambah" title="Edit"></a>    </h5>
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
                            <th>Nama</th>
                            <th>Alamat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($lokasi as $l)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $l->name }} </td>
                            <td>{{ $l->alamat }} </td>


                            <td>
                                <a class="fa fa-edit" href="{{route('lokasi.edit', $l->id)}}" title="Edit"></span></a>
                                <a class="btn btn-sm btn-success-outline" href="/lokasi/hapus/{{ $l->id }}" title="Hapus"><span class="fa fa-trash-o"></span></a>
                              </td>
                        </tr>
                    </tbody>
                    @endforeach
                    </table>
                </div>
            </div>

</div></div></div></div></div></div></div>
@include('layout.footer')
@include('layout.js')
