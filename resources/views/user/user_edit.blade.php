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
                    <h5>Edit Data User</h5>
                </div>@foreach($user as $u)
                <form class="form theme-form" action="/user/update " method="post">
                    @csrf
                    <div class="card-body">

                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <input type="hidden" name="id" value="{{ $u->id }}">
                                    <label class="form-label" for="exampleFormControlInput1">Nama</label>
                                    <input class="form-control" name="name" id="name" type="text" placeholder="Nama" value="{{ $u->name }}" required="required">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label class="form-label" for="exampleFormControlInput1">Username</label>
                                    <input class="form-control" id="username" name="username" type="username" placeholder="name@example.com" value="{{ $u->username }}" required="required">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label class="form-label" for="exampleInputPassword2">Password</label>
                                    <input class="form-control" id="password" name="password" type="text" placeholder="">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label class="form-label" for="exampleFormControlSelect9">Role</label>
                                    <select class="form-select digits" name="level" id="level" value="{{ $u->level }}" placeholder="Role" required="required">
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
                @endforeach
            </div>

        </div></div>
    </div></div>
</div></div>
</div></div>
</div></div>

        @include('layout.footer')
        @include('layout.js')
