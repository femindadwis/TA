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
                    <h5>Edit Data Jenis Kendaraan</h5>
                </div>@foreach($jenis_kendaraan as $jk)
                <form class="form theme-form" action="/jenis_kendaraan/update " method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">

                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <input type="hidden" name="id" value="{{ $jk->id }}">
                                    <label class="form-label" for="exampleFormControlInput1">Jenis Kendaraan</label>
                                    <input class="form-control" name="jenis_kendaraan" id="jenis_kendaraan" type="text" placeholder="Jenis Kendaraan" value="{{ $jk->jenis_kendaraan }}" required="required">
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
