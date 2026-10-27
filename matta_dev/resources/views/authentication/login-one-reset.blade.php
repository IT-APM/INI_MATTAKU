@extends('layouts.authentication.master')
@section('title', 'Login-one')

@section('css')
@endsection

@section('style')
@endsection

@section('content')
    <div class="container d-flex align-items-center" style="min-height: 100vh;">
        <div class="row">
            <div class="col-xl-5 d-flex align-items-center justify-content-center">
                <div class="w-100">
                    {{-- <img class="img-fluid rounded mb-3 mx-auto d-block" style="width:80%;float:none;" src="{{ asset('assets/images/login/trust1.png') }}" alt="trust"> --}}
                    <div class="card">
                        <div class="card-body">
                            @if (Session::has('msg_success'))
                                <div class="alert alert-success dark alert-dismissible fade show" role="alert" style="text-align: justify;">
                                    <strong>{!! session('msg_success') !!}</strong>
                                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif
                            @if (Session::has('msg_error'))
                                <div class="alert alert-danger dark alert-dismissible fade show" role="alert" style="text-align: justify;">
                                    <strong>{!! session('msg_error') !!}</strong>
                                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif
                            <div class="login-main">
                                <form class="theme-form" action="{{ route('resetpwd') }}" method="post"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <h4>Reset Password</h4>
                                    <div class="form-group">
                                        <label class="col-form-label" for="nik">Enter your NIP</label>
                                        <input class="form-control" type="text" name="nik" id="nik"
                                            required="" placeholder="example: 0123456789" autofocus>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label" for="email">Email receive</label>
                                        <select class="form-select" id="email" name="email" required="">
                                            <option selected="" disabled="" value="">Pilih...</option>
                                            <option value="own">Email Sendiri</option>
                                            <option value="hco">Email {{ App\Models\MtrUtility::find(51)->informasi }}</option>
                                        </select>
                                    </div>
                                    <div class="form-group mt-2">
                                        <button class="btn btn-primary btn-block" type="submit">Reset</button> | <a
                                            href="{{ route('login-one') }}" class="btn btn-warning">Back</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-7 d-flex align-items-center justify-content-center">
                <img style="width:90%;float:none;" src="{{ asset('assets/images/login/logo_matta.png') }}" alt="looginpage">
            </div>
        </div>
    </div>
    {{-- <div class="container-fluid">
    <div class="row">
        <div class="col-xl-5 p-0">
            <div class="login-card">
                <div>
                    @if (Session::has('msg_success'))
                    <div class="alert alert-success dark alert-dismissible fade show" role="alert">
                        <strong>{{ session('msg_success') }}</strong>
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    @if (Session::has('msg_error'))
                    <div class="alert alert-danger dark alert-dismissible fade show" role="alert">
                        <strong>{{ session('msg_error') }}</strong>
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    <div class="login-main border">
                        <form class="theme-form" action="{{ route('resetpwd') }}" method="post" enctype="multipart/form-data">
                        @csrf
                            <h4>Reset Password</h4>
                            <div class="form-group">
                                <label class="col-form-label" for="nik">Enter your NIP</label>
                                <input class="form-control" type="text" name="nik" id="nik" required="" placeholder="example: 0123456789" autofocus>
                            </div>
                            <div class="form-group">
                                <label class="col-form-label" for="email">Email receive</label>
                                <select class="form-select" id="email" name="email" required="">
                                    <option selected="" disabled="" value="">Pilih...</option>
                                    <option value="own">Email Sendiri</option>
                                    <option value="hco">Email {{ App\Models\MtrUtility::find(51)->informasi }}</option>
                                </select>
                            </div>
                            <div class="form-group mt-0">
                                <button class="btn btn-primary btn-block" type="submit">Reset</button> | <a href="{{ route('login-one') }}" class="btn btn-warning">Back</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-7 d-flex align-items-center justify-content-center">
            <img style="width:80%;float:none;" src="{{asset('assets/images/login/logo_matta.png')}}" alt="looginpage">
        </div>
    </div>
</div> --}}
@endsection

@section('script')
    <script type="text/javascript">
        function show_pwd() {
            var x = document.getElementById("password");
            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }
        }
    </script>
@endsection
