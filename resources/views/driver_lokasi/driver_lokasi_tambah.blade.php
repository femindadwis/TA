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
                    <h5>Tambah Lokasi ke Driver</h5>
                </div>
                <form class="form theme-form" action="/driver_lokasi/store" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">

                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label class="form-label" for="exampleFormControlSelect9">Nama Driver</label>
                                    <select class="form-select digits" name="user_id" id="user_id" placeholder="Nama" required="required" onchange="populateEmail()">
                                        @foreach ($user as $u)
                                        <option value="{{$u->id}}">{{$u->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    {{-- <div class="col-sm-12"> --}}
                                    <label class="form-label" for="exampleFormControlSelect9">Lokasi</label>
                                    @foreach ($lokasi as $l )
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="lokasi_id[]" value="{{$l->id}}"  id="flexCheckDefault">
                                                <label class="form-check-label" for="flexCheckDefault">{{$l->name}}</label>
                                            </div>
                                        @endforeach
                                          </div>
                                        </div>
                                      </div>


                        </div>

                    <div class="card-footer text-end">
                        <button class="btn btn-primary" type="submit">Submit</button>
                        <input class="btn btn-light" type="reset" value="Cancel" />
                    </div>
                </form>
            </div></div>

        </div></div>
    </div></div>
</div></div>
</div></div>
</div></div>

        @include('layout.footer')
        @include('layout.js')
