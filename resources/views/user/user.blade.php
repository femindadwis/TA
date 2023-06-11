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
            <h5>Tabel User       <a class="fa fa-plus-square-o" href="/user/tambah" title="Edit"></a>    </h5>
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
                            <th>Email</th>
                            <th>Role</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($user as $u)
                        {{-- @if ($u->level != 1 && !(Auth::user()->level == 2 && $u->level == 1)) --}}
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $u->name }} </td>
                            <td>{{ $u->email }} </td>
                            <td>
                                @php
                            if($u->level==1)
                            {
                                echo 'Super Admin';
                            }
                            if($u->level==2)
                            {
                                echo 'Admin';
                            }
                            if($u->level==3)
                            {
                                echo 'Driver';
                            }
                            @endphp
                            </td>

                            <td>
                                <a class="fa fa-edit" href="/user/edit/{{ $u->id }}" title="Edit"></span></a>
                                <a class="btn btn-sm btn-success-outline" href="/user/hapus/{{ $u->id }}" title="Hapus"><span class="fa fa-trash-o"></span></a>
                            </td>
                        </tr>
                    </tbody>
                    {{-- @endif --}}
                    @endforeach
                    </table>
                </div>
            </div>

</div></div></div></div></div></div></div>
@include('layout.footer')
@include('layout.js')
