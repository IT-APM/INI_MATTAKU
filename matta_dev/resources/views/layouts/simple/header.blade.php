<div class="page-header">
    <div class="header-wrapper row m-0">
        <div class="header-logo-wrapper col-auto p-0">
            <div class="toggle-sidebar"><i class="status_toggle middle sidebar-toggle" data-feather="align-center"></i>
            </div>
        </div>
        {{-- <div class="left-header col-xxl-5 col-xl-6 col-lg-5 col-md-4 col-sm-3 p-0 text-start"> --}}
        <div class="nav-right col-xxl-5 col-xl-5 col-md-5 col-5 pull-right right-header p-0 ms-auto">
            {{-- <img style="width:20%;float:none;" src="{{ asset('assets/images/login/trust1.png') }}" alt="trust"> --}}
            <img src="{{ asset('assets/images/login/trust1.png') }}" alt="trust" height="70">
        </div>
        <div class="nav-right col-xxl-7 col-xl-7 col-md-7 col-7 pull-right right-header p-0 ms-auto">
            <ul class="nav-menus">
                <div class="lang" data-value="en"><a href="lang/en"><i class="flag-icon flag-icon-gb"></i><span class="lang-txt">EN</span></a></div>
                &nbsp;<div class="lang" data-value="id"><a href="lang/id"><i class="flag-icon flag-icon-id"></i><span class="lang-txt">ID</span></a></div>
                <li class="profile-nav onhover-dropdown pe-0 py-0">
                    <div class="media profile-media">
                        <img src="{{ asset('assets/images/matta/karyawan/' . auth()->user()->nik . '/' . auth()->user()->nik . '.jpg') }}" alt="" height="45">
                        <div class="media-body"><span>Welcome, {{ auth()->user()->name }} / {{ auth()->user()->nik }}</span>
                            <p class="mb-0 font-roboto">Drop Menu <i class="middle fa fa-angle-down"></i></p>
                        </div>
                    </div>
                    <ul class="profile-dropdown onhover-show-div">
                        <a class="btn btn-default" href="{{ route('karyawan.show', (new App\Http\Controllers\OpenDataController)->encryptWithKey(auth()->user()->nik)) }}"><i data-feather="user"></i><span> Profile</span></a>
                        <form action="{{ route('logoutapp') }}" method="post" enctype="multipart/form-data">
                            @method('POST')
                            @csrf
                            <button class="btn btn-default" type="submit"><i data-feather="log-out"></i><span>Logout</span></button>
                        </form>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>
