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
                    <h5>Tambah Data User</h5>
                </div>
                <form class="form theme-form" action="/user/store" method="post">
                    @csrf
                    <div class="card-body">

                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label class="form-label" for="exampleFormControlInput1">Nama</label>
                                    <input class="form-control" name="name" id="name" type="text" placeholder="Nama" required="required">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label class="form-label" for="exampleFormControlInput1">Email address</label>
                                    <input class="form-control" id="email" name="email" type="email" placeholder="name@example.com" required="required">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label class="form-label" for="exampleInputPassword2">Password</label>
                                    <input class="form-control" id="password" name="password" type="password" placeholder="Password" required="required">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label class="form-label" for="exampleFormControlSelect9">Role</label>
                                    <select class="form-select digits" name="level" id="level" placeholder="Role" required="required">
                                        @if (Auth::user()->level == 1)
                                        <option value="1" >Super Admin </option>
                                        @endif
                                        <option value="2" >Admin </option>
                                        <option value="3" >Driver </option>

                                    </select>
                                </div>
                            </div>
                        </div>

                    <div class="card-footer text-end">
                        <button class="btn btn-primary" type="submit">Submit</button>
                        <input class="btn btn-light" type="reset" value="Cancel" />
                    </div>
                </form>
            </div>

        </div></div>
    </div></div>
</div></div>
</div></div>
</div></div>

        @include('layout.footer')
        @include('layout.js')
