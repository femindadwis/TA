@include('layout.header')
@include('layout.navbar')
@include('layout.sidebar')

<div class="page-body">
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h5>Edit Lokasi ke Driver</h5>
                                </div>
                                @foreach($driver_lokasi as $dl)
                                <form class="form theme-form" action="/driver_lokasi/update" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col">
                                                <div class="mb-3">
                                                    <input type="hidden" name="id" value="{{ $dl->id }}">
                                                    <label class="form-label" for="exampleFormControlSelect9">Nama Driver</label>
                                                    <select class="form-select digits" name="user_id" id="user_id" placeholder="Nama" required="required" onchange="populateEmail()">
                                                        @foreach ($user as $u)
                                                        <option value="{{$u->id}}" {{ $dl->user_id == $u->id ? 'selected' : '' }}>{{$u->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <div class="mb-3">
                                                    <label class="form-label" for="exampleFormControlSelect9">Lokasi</label>
                                                    @foreach ($lokasi as $l )
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="lokasi_id[]" value="{{$l->id}}" id="flexCheckDefault" {{ in_array($l->id, explode(',', $dl->lokasi_id)) ? 'checked' : '' }}>
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
                                @endforeach
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
