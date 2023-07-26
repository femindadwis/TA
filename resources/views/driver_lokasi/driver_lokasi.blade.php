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
                            <h5>Tabel Lokasi Driver <a class="fa fa-plus-square-o" href="/driver_lokasi/tambah"
                                    title="Edit"></a> </h5>
                            <span>Disabling features that you don't wish to use for a particular table is easily done by
                                setting a variable in the initialisation object</span>
                            <span>In the following example only the search feature is left enabled (which it is by
                                default).</span>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="display" id="basic-2">
                                    <thead>
                                        <tr>
                                            <?php $no = 1; ?>
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th>Lokasi</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 1;
                                        $prevUserId = null; ?>
                                        @foreach ($driver_lokasi as $key => $dl)
                                            <tr>
                                                @if ($prevUserId != $dl->users->id)
                                                    <td>{{ $no++ }}</td>
                                                    <td>{{ $dl->users->name }}</td>
                                                    <?php $prevUserId = $dl->users->id; ?>
                                                @else
                                                    <td></td>
                                                    <td></td>
                                                @endif
                                                <td>{{ $dl->lokasi->name }}</td>
                                                <td>
                                                    <a class="fa fa-edit"
                                                        href="{{ route('driver_lokasi.edit', $dl->user_id) }}"
                                                        title="Edit"></a>
                                                    <a class="btn btn-sm btn-success-outline"
                                                        href="/driver_lokasi/hapus/{{ $dl->id }}"
                                                        title="Hapus"><span class="fa fa-trash-o"></span></a>
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
@include('layout.footer')
@include('layout.js')
