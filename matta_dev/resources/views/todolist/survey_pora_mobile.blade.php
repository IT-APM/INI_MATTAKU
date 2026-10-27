<!DOCTYPE html>
<html lang="en">

<head>
    <title>Survey PORA 2026</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    @if(now()->lessThanOrEqualTo(Carbon\Carbon::createFromFormat('Y-m-d H:i:s', '2026-02-05 19:00:00')))
    <div class="row m-1">
        <h4 class="text-center">SURVEY CABANG OLAHRAGA PORA 2026</h4>
        <hr />
        @if (Session::has('msg_error'))
            <div class="alert alert-danger dark alert-dismissible fade show" role="alert" style="text-align: justify;">
                <strong>{!! session('msg_error') !!}</strong>
                <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if(Session::has('msg_success'))
        <div class="alert alert-success dark alert-dismissible fade show" role="alert" style="text-align: justify;">
            <strong>{!! session('msg_success') !!}</strong>
            <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        <div class="col-12">
            <div style="text-align: justify;font-size: 15px;">
                <p>Dear Insan ALITA,<br />Semangat Pagi!!!</p>
                <p>Menjelang gelaran acara Pekan Olahraga Alita (PORA) 2026, mohon partisipasi dari seluruh Insan
                    ALITA untuk mengikuti survey pemilihan 4 (empat) cabang olahraga yang akan diadakan dalam PORA
                    2026. Survey ini akan dibuka dari <b>Senin 02 Februari s/d Kamis 05 Februari
                        2026</b>.<br />Terimakasih atas partisipasinya.</p>
            </div>
        </div>
        <div class="col-12">
            <div style="text-align: justify;font-size: 15px;">
                <form id="survey_pora" action="{{ route('survey_mobile_post') }}" method="post"
                    enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                    @csrf
                    <table class="table table-bordered table-striped">
                        <tbody>
                            <tr>
                                <td width="5%"><b>1</b></td>
                                <td><b>Cabor GROUP (Pilih 2)</b><br />
                                    <div class="form-check">
                                        <input {{ in_array('futsal', old('opsi1', [])) ? 'checked' : '' }}
                                            class="form-check-input" type="checkbox" value="futsal" id="opsi1[]"
                                            name="opsi1[]">Futsal (5 vs 5)
                                    </div>
                                    <div class="form-check">
                                        <input {{ in_array('voli', old('opsi1', [])) ? 'checked' : '' }}
                                            class="form-check-input" type="checkbox" value="voli" id="opsi1[]"
                                            name="opsi1[]">Voli (6 vs 6)
                                    </div>
                                    <div class="form-check">
                                        <input {{ in_array('basket', old('opsi1', [])) ? 'checked' : '' }}
                                            class="form-check-input" type="checkbox" value="basket" id="opsi1[]"
                                            name="opsi1[]">Basket (3 on 3)
                                    </div>
                                    <small id="error_opsi1" class="text-danger" style="display:none;">Wajib Pilih
                                        2</small>
                                </td>
                            </tr>
                            <tr>
                                <td><b>2</b></td>
                                <td><b>Cabor INDIVIDU / DUO (Pilih 1)</b><br />
                                    <div class="form-check">
                                        <input {{ in_array('badminton', old('opsi2', [])) ? 'checked' : '' }}
                                            class="form-check-input" type="checkbox" value="badminton" id="opsi2[]"
                                            name="opsi2[]">Badminton (Ganda Putra & Ganda Putri)
                                    </div>
                                    <div class="form-check">
                                        <input {{ in_array('tenis', old('opsi2', [])) ? 'checked' : '' }}
                                            class="form-check-input" type="checkbox" value="tenis" id="opsi2[]"
                                            name="opsi2[]">Tenis Meja (Ganda Putra)
                                    </div>
                                    <div class="form-check">
                                        <input {{ in_array('renang', old('opsi2', [])) ? 'checked' : '' }}
                                            class="form-check-input" type="checkbox" value="renang" id="opsi2[]"
                                            name="opsi2[]">Renang (Individu Putra & Putri)
                                    </div>
                                    <small id="error_opsi2" class="text-danger" style="display:none;">Wajib Pilih
                                        1</small>
                                </td>
                            </tr>
                            <tr>
                                <td><b>3</b></td>
                                <td><b>Cabor E-SPORT (Pilih 1)</b><br />
                                    <div class="form-check">
                                        <input {{ in_array('ml', old('opsi3', [])) ? 'checked' : '' }}
                                            class="form-check-input" type="checkbox" value="ml" id="opsi3[]"
                                            name="opsi3[]">Mobile Legend (5 vs 5)
                                    </div>
                                    <div class="form-check">
                                        <input {{ in_array('fifa', old('opsi3', [])) ? 'checked' : '' }}
                                            class="form-check-input" type="checkbox" value="fifa" id="opsi3[]"
                                            name="opsi3[]">FIFA 26 (2 vs 2)
                                    </div>
                                    <small id="error_opsi3" class="text-danger" style="display:none;">Wajib Pilih
                                        1</small>
                                </td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>
                                    <div class="row">
                                        <div class="col-12">
                                            <input type="text" class="form-control" id="nik" name="nik"
                                                value="{{ old('nik') }}" placeholder="Input NIP">
                                            <small id="error_nik" class="text-danger" style="display:none;">Wajib
                                                diisi</small>
                                            <input type="password" class="form-control mt-2" id="password" name="password"
                                                value="{{ old('password') }}" placeholder="Input Password">
                                            <small id="error_password" class="text-danger"
                                                style="display:none;">Wajib
                                                diisi</small>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <button class="btn btn-warning mb-2" type="submit">Submit</button>
                </form>
            </div>
        </div>
    </div>
    @endif
</body>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript">
    document.getElementById('survey_pora').addEventListener('submit', function(e) {
        e.preventDefault();
        const checked_opsi1 = document.querySelectorAll('input[name="opsi1[]"]:checked');
        const checked_opsi2 = document.querySelectorAll('input[name="opsi2[]"]:checked');
        const checked_opsi3 = document.querySelectorAll('input[name="opsi3[]"]:checked');
        const nik = document.getElementById('nik');
        const password = document.getElementById('password');
        let valid = true;
        if (nik.value.trim() === '') {
            document.getElementById('error_nik').style.display = 'inline';
            valid = false;
        } else {
            document.getElementById('error_nik').style.display = 'none';
        }
        if (password.value.trim() === '') {
            document.getElementById('error_password').style.display = 'inline';
            valid = false;
        } else {
            document.getElementById('error_password').style.display = 'none';
        }
        if (checked_opsi1.length !== 2) {
            document.getElementById('error_opsi1').style.display = 'inline';
            valid = false;
        } else {
            document.getElementById('error_opsi1').style.display = 'none';
        }
        if (checked_opsi2.length !== 1) {
            document.getElementById('error_opsi2').style.display = 'inline';
            valid = false;
        } else {
            document.getElementById('error_opsi2').style.display = 'none';
        }
        if (checked_opsi3.length !== 1) {
            document.getElementById('error_opsi3').style.display = 'inline';
            valid = false;
        } else {
            document.getElementById('error_opsi3').style.display = 'none';
        }
        if (valid) {
            e.target.submit();
        }
    });
</script>

</html>
