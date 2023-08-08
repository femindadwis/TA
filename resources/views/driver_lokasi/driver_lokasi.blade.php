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

                        </div>
                        <div class="card-body"  style="padding-top: 5px;">
                            <div class="table-responsive">
                                <table class="display" id="basic-2">
                                    <thead>
                                        <tr>
                                            <?php $no = 1;    $driver_lokasi = $driver_lokasi->sortBy('user_id');?>
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

                                                    <?php $prevUserId = $dl->users->id; ?>
                                                @else

                                                    <td></td>
                                                @endif
                                                <td>{{ $dl->users->name }}</td>
                                                <td>{{ $dl->lokasi->name }}</td>
                                                <td>
                                                    <a class="fa fa-edit" href="{{ route('driver_lokasi.edit', $dl->user_id) }}" title="Edit"></a> &nbsp;&nbsp;
                                                    <a class="fa fa-trash-o" href="/driver_lokasi/hapus/{{ $dl->id }}"  title="Hapus"></a> &nbsp;&nbsp;
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
