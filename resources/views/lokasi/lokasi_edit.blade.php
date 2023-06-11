@include('layout.header')
@include('layout.navbar')
@include('layout.sidebar')

<div class="page-body">
    <!-- Container-fluid starts-->
        <div class="container-fluid">
<div class="page-header">
<div class="row">

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header pb-0">
                    <h5>Edit Data Lokasi</h5>
                </div>@foreach($lokasi as $l)
                <form class="form theme-form" action="/lokasi/update " method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">

                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <input type="hidden" name="id" value="{{ $l->id }}">
                                    <label class="form-label" for="exampleFormControlInput1">Nama</label>
                                    <input class="form-control" name="name" id="name" type="text" placeholder="Nama" value="{{ $l->name }}" required="required">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label class="form-label" for="exampleFormControlInput1">Alamat</label>
                                    <input class="form-control" id="alamat" name="alamat" type="alamat" placeholder="Alamat" value="{{ $l->alamat }}" required="required">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label class="form-label" for="exampleFormControlInput1">Longitude</label>
                                    <input class="form-control" name="lng" id="lng" type="text" placeholder="lng" value="{{ $l->lng }}" required="required">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label class="form-label" for="exampleFormControlInput1">Latitude</label>
                                    <input class="form-control" name="lat" id="lat" type="text" placeholder="lat" value="{{ $l->lat }}" required="required">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label class="form-label" for="exampleFormControlInput1">Foto</label>
                                    <input class="form-control" name="foto" id="foto" type="file" placeholder="foto" value="{{ $l->foto }}" required="required">
                                </div>
                            </div>
                        </div>
                        </div>

                    <div class="card-footer text-end">
                        <button class="btn btn-primary" type="submit">Submit</button>
                        <input class="btn btn-light" type="reset" value="Cancel" />
                    </div>
                </form>
                @endforeach
            </div>

        </div></div>
    </div></div>
</div></div>
</div></div>
</div></div>

        @include('layout.footer')
        @include('layout.js')
