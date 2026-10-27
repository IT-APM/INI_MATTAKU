@extends('layouts.authentication.master')
@section('title', 'Login-one')

@section('css')
@endsection

@section('style')
@endsection

@section('content')
    <div class="container d-flex align-items-center" style="min-height: 100vh;">
        <div class="row">
            <div class="col-xl-7 d-flex align-items-center justify-content-center">
                <img style="width:90%;float:none;" src="{{ asset('assets/images/login/logo_matta.png') }}" alt="looginpage">
            </div>
            <div class="col-xl-5 d-flex align-items-center justify-content-center">
                <div class="w-100">
                    {{-- <img class="img-fluid rounded m-2 mx-auto d-block" style="width:100%;float:none;" src="{{ asset('assets/images/login/trust1.png') }}" alt="trust"> --}}
                    {{-- <img class="img-fluid rounded m-2 mx-auto d-block" style="width:100%;float:none;" src="{{ asset('assets/images/login/trust2.jpg') }}" alt="trust"> --}}
                    <div class="card">
                        <div class="card-body">
                            @if (Session::has('msg_error'))
                                <div class="alert alert-danger dark alert-dismissible fade show" role="alert" style="text-align: justify;">
                                    <strong>{!! session('msg_error') !!}</strong>
                                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif
                            <div class="login-main">
                                <form class="theme-form" action="{{ route('loginapp') }}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <h4>Sign in to account</h4>
                                    <p>Enter your NIP & Password to login</p>
                                    <div class="form-group">
                                        <label class="col-form-label" for="nik">NIP</label>
                                        <input class="form-control" type="text" name="nik" id="nik" required="" placeholder="example: 0123456789" autofocus>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label" for="password">Password</label>
                                        <input class="form-control" type="password" name="password" id="password" required="" placeholder="*********">
                                        <input type="checkbox" onclick="show_pwd()"> Show Password &nbsp; <a href="{{ route('login-one-reset') }}" class="link-primary" target="_blank">Reset password?</a>
                                    </div>
                                    <div class="form-group mt-0">
                                        <button class="btn btn-primary btn-block" type="submit">Login</button>
                                    </div>
                                    <div class="form-group mt-0">
                                        <a href="{{ asset('assets/apk/app-release.apk') }}" class="link-primary" target="_blank">Click here to get Android MATTA Reborn</a>
                                        <br/><a href="http://matta-reborn.alita.id/download" class="link-primary" target="_blank">Click here to get User Manual</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- <div class="container-fluid">
    <div class="row">
        <div class="col-xl-7 d-flex align-items-center justify-content-center">
            <img style="width:80%;float:none;" src="{{asset('assets/images/login/logo_matta.png')}}" alt="looginpage">
        </div>
        <div class="col-xl-5 p-0">
            <div class="login-card">
                <div>
                    @if (Session::has('msg_error'))
                    <div class="alert alert-danger dark alert-dismissible fade show" role="alert">
                        <strong>{{ session('msg_error') }}</strong>
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    <div class="login-main border">
                        <form class="theme-form" action="{{ route('loginapp') }}" method="post" enctype="multipart/form-data">
                        @csrf
                            <h4>Sign in to account</h4>
                            <p>Enter your NIP & Password to login</p>
                            <div class="form-group">
                                <label class="col-form-label" for="nik">NIP</label>
                                <input class="form-control" type="text" name="nik" id="nik" required="" placeholder="example: 0123456789" autofocus>
                            </div>
                            <div class="form-group">
                                <label class="col-form-label" for="password">Password</label>
                                <input class="form-control" type="password" name="password" id="password" required="" placeholder="*********">
                                <input type="checkbox" onclick="show_pwd()"> Show Password &nbsp; <a href="{{ route('login-one-reset') }}" class="link-primary" target="_blank">Reset password?</a>
                            </div>
                            <div class="form-group mt-0">
                                <button class="btn btn-primary btn-block" type="submit">Login</button>
                                <div class="modal fade bd-example-modal-lg" id="showPDP" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <img src="{{asset('assets/images/matta/uu_pdp.png')}}" class="img-fluid d-flex" alt="Pengumuman">
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-warning" type="button" data-bs-dismiss="modal">Tutup</button>
                                             </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mt-0">
                                <a href="{{asset('assets/apk/app-release.apk')}}" class="link-primary" target="_blank">Click here to get Android MATTA Reborn</a>
                                <br/><a href="http://matta-reborn.alita.id/download" class="link-primary" target="_blank">Click here to get User Manual</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> --}}
<div class="modal fade bd-example-modal-lg" id="showPDP" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0 d-flex align-items-center justify-content-center" style="overflow:hidden;">
                <img src="{{ asset('assets/images/matta/uu_pdp_v2.png') }}" alt="Pengumuman" style="width:100%; height:100%; object-fit:contain;">
            </div>
            <div class="modal-footer">
                <button class="btn btn-warning" type="button" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade bd-example-modal-lg" id="showTrust" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0 d-flex align-items-center justify-content-center" style="overflow:hidden;">
                <img src="{{ asset('assets/images/login/trust2.jpg') }}" alt="Pengumuman" style="width:100%; height:100%; object-fit:contain;">
            </div>
            <div class="modal-footer">
                <button class="btn btn-warning" type="button" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
{{-- <div class="modal fade bd-example-modal-lg" id="showTrust" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <img style="width:80%;float:none;" src="{{ asset('assets/images/login/trust2.jpg') }}" class="img-fluid d-block mx-auto" alt="trust">
            </div>
            <div class="modal-footer">
                <button class="btn btn-warning" type="button" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div> --}}
@endsection

@section('script')
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            var myModal = new bootstrap.Modal(document.getElementById('showTrust'));
            myModal.show();
        });
        @php $is_aktif = App\Models\MtrUtility::find(39)->informasi==1; @endphp
        @if ($is_aktif)
            document.addEventListener('DOMContentLoaded', function() {
                var myModal = new bootstrap.Modal(document.getElementById('showPDP'));
                myModal.show();
            });
        @endif
        //   
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
