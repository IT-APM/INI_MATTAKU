@extends('layouts.authentication.master')
@section('title', 'Login-one')

@section('css')
@endsection

@section('style')
<style>
/* Style all input fields */
input {
  padding: 12px;
  border: 1px solid #ccc;
  border-radius: 4px;
  box-sizing: border-box;
  margin-top: 6px;
  margin-bottom: 16px;
}

/* The message box is shown when the user clicks on the password field */
#message {
  display:none;
  background: #f1f1f1;
  color: #000;
  position: relative;
  padding: 5px;
  margin-top: 10px;
  font-size: 18px;
}

#message div {
  padding: 5px 5px;
  font-size: 18px;
}

/* Add a green text color and a checkmark when the requirements are right */
.valid {
  color: green;
}

.valid:before {
  position: relative;
  left: -5px;
  content: "✔";
}

/* Add a red text color and an "x" when the requirements are wrong */
.invalid {
  color: red;
}

.invalid:before {
  position: relative;
  left: -5px;
  content: "✖";
}
</style>
@endsection

@section('content')
<div class="container d-flex align-items-center" style="min-height: 100vh;">
    <div class="row">
        <div class="col-xl-5 d-flex align-items-center justify-content-center">
            <div class="w-100">
                {{-- <img class="img-fluid rounded mb-3 mx-auto d-block" style="width:80%;float:none;" src="{{ asset('assets/images/login/trust1.png') }}" alt="trust"> --}}
                <div class="card">
                    <div class="card-body">
                        @if(Session::has('msg_success'))
                        <div class="alert alert-success dark alert-dismissible fade show" role="alert" style="text-align: justify;">
                            <strong>{!! session('msg_success') !!}</strong>
                            <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif
                        @if(Session::has('msg_error'))
                        <div class="alert alert-danger dark alert-dismissible fade show" role="alert" style="text-align: justify;">
                            <strong>{!! session('msg_error') !!}</strong>
                            <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif
                        <div class="login-main">
                            <form class="theme-form" action="{{ route('resetaction') }}" onSubmit="return checkPassword(this)" method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" id="nik" name="nik" value="{{ $nik }}">
                            <input type="hidden" id="pwd" name="pwd" value="{{ $pwd }}">
                            <input type="hidden" id="email" name="email" value="{{ $email }}">
                                <h4>New Password</h4>
                                <div class="form-group">
                                    <label class="col-form-label" for="nik">Enter New Password</label>
                                    <input class="form-control" type="password" name="password" id="password" required="" autofocus pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}" title="Harus terdiri dari Satu huruf kecil, Satu huruf besar, Satu angka dan Minimum 6 karakter.">
                                    <input type="checkbox" onclick="show_pwd()"> Show Password
                                </div>
                                <div class="form-group">
                                    <label class="col-form-label" for="nik">Confirm New Password</label>
                                    <input class="form-control" type="password" name="confirmPassword" id="confirmPassword" required="">
                                    <input type="checkbox" onclick="show_confirm()"> Show Confirm Password
                                </div>
                                <div class="form-group mt-0">
                                    <button class="btn btn-primary btn-block" type="submit">Submit</button>
                                </div>
                                <div id="message">
                                    <b>Password harus terdiri dari:</b><br/>
                                    <div id="letter" class="invalid"><b>Satu huruf kecil</b></div>
                                    <div id="capital" class="invalid"><b>Satu huruf besar</b></div>
                                    <div id="number" class="invalid"><b>Satu angka</b></div>
                                    <div id="length" class="invalid"><b>Minimum 6 karakter</b></div>
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
                    @if(Session::has('msg_success'))
                    <div class="alert alert-success dark alert-dismissible fade show" role="alert">
                        <strong>{{ session('msg_success') }}</strong>
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    @if(Session::has('msg_error'))
                    <div class="alert alert-danger dark alert-dismissible fade show" role="alert">
                        <strong>{{ session('msg_error') }}</strong>
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    <div class="login-main border">
                        <!-- <form class="theme-form"> -->
                        <form class="theme-form" action="{{ route('resetaction') }}" onSubmit="return checkPassword(this)" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="nik" name="nik" value="{{ $nik }}">
                        <input type="hidden" id="pwd" name="pwd" value="{{ $pwd }}">
                        <input type="hidden" id="email" name="email" value="{{ $email }}">
                            <h4>New Password</h4>
                            <div class="form-group">
                                <label class="col-form-label" for="nik">Enter New Password</label>
                                <input class="form-control" type="password" name="password" id="password" required="" autofocus pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}" title="Harus terdiri dari Satu huruf kecil, Satu huruf besar, Satu angka dan Minimum 6 karakter.">
                                <input type="checkbox" onclick="show_pwd()"> Show Password
                            </div>
                            <div class="form-group">
                                <label class="col-form-label" for="nik">Confirm New Password</label>
                                <input class="form-control" type="password" name="confirmPassword" id="confirmPassword" required="">
                                <input type="checkbox" onclick="show_confirm()"> Show Confirm Password
                            </div>
                            <div class="form-group mt-0">
                                <button class="btn btn-primary btn-block" type="submit">Submit</button>
                            </div>
                            <div id="message">
                                <b>Password harus terdiri dari:</b><br/>
                                <div id="letter" class="invalid"><b>Satu huruf kecil</b></div>
                                <div id="capital" class="invalid"><b>Satu huruf besar</b></div>
                                <div id="number" class="invalid"><b>Satu angka</b></div>
                                <div id="length" class="invalid"><b>Minimum 6 karakter</b></div>
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
// 
var myInput = document.getElementById("password");
var letter = document.getElementById("letter");
var capital = document.getElementById("capital");
var number = document.getElementById("number");
var length = document.getElementById("length");

// When the user clicks on the password field, show the message box
myInput.onfocus = function() {
  document.getElementById("message").style.display = "block";
}

// When the user clicks outside of the password field, hide the message box
myInput.onblur = function() {
  document.getElementById("message").style.display = "none";
}

// When the user starts to type something inside the password field
myInput.onkeyup = function() {
  // Validate lowercase letters
  var lowerCaseLetters = /[a-z]/g;
  if(myInput.value.match(lowerCaseLetters)) {  
    letter.classList.remove("invalid");
    letter.classList.add("valid");
  } else {
    letter.classList.remove("valid");
    letter.classList.add("invalid");
  }
  
  // Validate capital letters
  var upperCaseLetters = /[A-Z]/g;
  if(myInput.value.match(upperCaseLetters)) {  
    capital.classList.remove("invalid");
    capital.classList.add("valid");
  } else {
    capital.classList.remove("valid");
    capital.classList.add("invalid");
  }

  // Validate numbers
  var numbers = /[0-9]/g;
  if(myInput.value.match(numbers)) {  
    number.classList.remove("invalid");
    number.classList.add("valid");
  } else {
    number.classList.remove("valid");
    number.classList.add("invalid");
  }
  
  // Validate length
  if(myInput.value.length >= 6) {
    length.classList.remove("invalid");
    length.classList.add("valid");
  } else {
    length.classList.remove("valid");
    length.classList.add("invalid");
  }
}
// 
function show_pwd() {
  var x = document.getElementById("password");
  if (x.type === "password") {
    x.type = "text";
  } else {
    x.type = "password";
  }
}
// 
function show_confirm() {
  var x = document.getElementById("confirmPassword");
  if (x.type === "password") {
    x.type = "text";
  } else {
    x.type = "password";
  }
}
// 
function checkPassword(form) {
    const password = form.password.value;
    const confirmPassword = form.confirmPassword.value;
    if (password != confirmPassword) {
        alert("Error! Password did not match.");
        return false;
    } else {
        // alert("Password Match. Congratulations!");
        return true;
    }
}
// 
</script>
@endsection