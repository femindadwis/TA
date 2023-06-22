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
                    <h5>Edit Data Driver</h5>
                </div>@foreach($driver as $d)
                <form class="form theme-form" action="/driver/update" method="post"  enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">

                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <input type="hidden" name="id" value="{{ $d->id }}">
                                    <label class="form-label" for="exampleFormControlInput1">Nama</label>
                                    <select class="form-select digits" name="user_id" id="user_id" placeholder="Nama" required="required" onchange="populateusername()">
                                        @foreach ($user as $u)
                                        <option value="{{$u->id}}" {{ $d->user_id == $u->id ? 'selected' : '' }}>{{$u->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label class="form-label" for="exampleFormControlInput1">Username</label>
                                    <input class="form-control" id="username" name="username" type="username" placeholder="name@example.com" value="{{ $d->username }}" required="required">
                                </div>
                            </div>
                        </div>
                        <script>
                            function populateusername() {
                                var selectElement = document.getElementById("user_id");
                                var selectedOption = selectElement.options[selectElement.selectedIndex];
                                var usernameInput = document.getElementById("username");
                                usernameInput.value = selectedOption.getAttribute("data-username");
                            }
                        </script>
                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label class="form-label" for="exampleFormControlInput1">Alamat</label>
                                    <input class="form-control" name="alamat" id="alamat" type="text" placeholder="Alamat" value="{{ $d->alamat }}" required="required">
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
