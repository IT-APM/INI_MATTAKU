<!DOCTYPE html>
<html lang="en">

<head>
    <title>Surat Pemberitahuan Penyesuaian Upah</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{asset('assets/images/favicon.png')}}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
    /* .table-no-border,
    .table-no-border th,
    .table-no-border td {
        border: none;
    }, */
    </style>
</head>
@php $spu = json_decode($trx->spu, true); @endphp
<body>
    <section class="p-2 p-md-2 p-xl-2">
        <div class="container-fluid">
            <div class="card border-light-subtle shadow-sm">
                <div class="row px-2">
                    <div class="col-12 col-md-12 col-sm-12"><img class="img-fluid rounded-start w-194 h-73 object-fit-cover" loading="lazy" src="{{ asset('assets/images/matta/alita.png') }}" alt="Alita"></div>
                </div>
                <hr class="mt-2" />
                <div class="row px-2">
                    <div class="col-md-12 col-sm-12">
                        <h4 class="ms-2 text-uppercase fw-bold text-danger" style="text-decoration: underline;">Confidential</h4>
                    </div>
                </div>
                <div class="row g-0">
                    <h4 class="text-center text-uppercase" style="text-decoration: underline;">Surat Pemberitahuan Penyesuaian Upah</h4>
                </div>
                <div class="row g-0">
                    <div class="col-12 col-md-12 col-sm-12">
                        <table class="table table-no-border">
                            <tbody>
                                <tr>
                                    <td width="10%">No</td>
                                    <td width="60%">: {{ $spu['no_surat'] }}</td>
                                    <td width="30%" class="text-end fw-bold">NIP : {{ $spu['nik'] }}</td>
                                </tr>
                                <tr>
                                    <td>Kepada</td>
                                    <td>: {{ $spu['nama'] }}</td>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td>Dari</td>
                                    <td>: {{ $spu['departemen'] }}</td>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td>Perihal</td>
                                    <td>: Penyesuaian Upah</td>
                                    <td>&nbsp;</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row px-2">
                    <div class="col-md-12 col-sm-12">
                        <p style="text-align: justify;">Berdasarkan {{ $spu['redaksi'] }}, bersama ini disampaikan bahwa terhitung mulai bulan <b>{{ $spu['bulan_tahun_present'] }}</b> posisi level, grade dan gaji Saudara/i adalah sebagai berikut:</p>
                    </div>
                </div>
                <div class="row g-0">
                    <div class="col-12 col-md-12 col-sm-12">
                        <table class="table table-no-border">
                        {{-- <table class="table table-bordered"> --}}
                             {{-- style="border-right: 1px solid #000;" --}}
                            <tbody>
                                <tr>
                                    <td width="2%" style="border-left: 1px solid #000; border-top: 1px solid #000;">&nbsp;</td>
                                    <td width="18%" style="border-top: 1px solid #000;">&nbsp;</td>
                                    <td width="40%" class="text-uppercase fw-bold" style="border-left: 1px solid #000; border-top: 1px solid #000;">Sampai {{ $spu['bulan_tahun_past'] }}</td>
                                    <td width="40%" class="text-uppercase fw-bold" style="border-left: 1px solid #000; border-top: 1px solid #000; border-right: 1px solid #000;">Setelah Penyesuaian</td>
                                </tr>
                                <tr>
                                    <td style="border-left: 1px solid #000;">A.</td>
                                    <td>Level</td>
                                    <td style="border-left: 1px solid #000;">{{ $spu['level_past'] }}</td>
                                    <td style="border-left: 1px solid #000; border-right: 1px solid #000;">{{ $spu['level_present'] }}</td>
                                </tr>
                                <tr>
                                    <td style="border-left: 1px solid #000;">B.</td>
                                    <td>Grade</td>
                                    <td style="border-left: 1px solid #000;">{{ $spu['grade_past'] }}</td>
                                    <td style="border-left: 1px solid #000; border-right: 1px solid #000;">{{ $spu['grade_present'] }}</td>
                                </tr>
                                <tr>
                                    <td style="border-left: 1px solid #000;">C.</td>
                                    <td>Gaji Pokok</td>
                                    <td style="text-align: right; border-left: 1px solid #000;"><b>{{ number_format($spu['gapok_past'], 0) }}</b></td>
                                    <td style="text-align: right; border-left: 1px solid #000; border-right: 1px solid #000;"><b>{{ number_format($spu['gapok_present'], 0) }}</b></td>
                                </tr>
                                <tr>
                                    <td style="border-left: 1px solid #000;">D.</td>
                                    <td>Tunjangan</td>
                                    <td style="text-align: right; border-left: 1px solid #000;">&nbsp;</td>
                                    <td style="text-align: right; border-left: 1px solid #000; border-right: 1px solid #000;">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td style="border-left: 1px solid #000;">&nbsp;</td>
                                    <td>Makan</td>
                                    <td style="text-align: right; border-left: 1px solid #000;">{{ number_format($spu['t_makan_past'], 0) }}</td>
                                    <td style="text-align: right; border-left: 1px solid #000; border-right: 1px solid #000;">{{ number_format($spu['t_makan_present'], 0) }}</td>
                                </tr>
                                <tr>
                                    <td style="border-left: 1px solid #000;">&nbsp;</td>
                                    <td>Transport</td>
                                    <td style="text-align: right; border-left: 1px solid #000;">{{ number_format($spu['t_transport_past'], 0) }}</td>
                                    <td style="text-align: right; border-left: 1px solid #000; border-right: 1px solid #000;">{{ number_format($spu['t_transport_present'], 0) }}</td>
                                </tr>
                                <tr>
                                    <td style="border-left: 1px solid #000;">&nbsp;</td>
                                    <td>Jabatan</td>
                                    <td style="text-align: right; border-left: 1px solid #000;">{{ number_format($spu['t_jabatan_past'], 0) }}</td>
                                    <td style="text-align: right; border-left: 1px solid #000; border-right: 1px solid #000;">{{ number_format($spu['t_jabatan_present'], 0) }}</td>
                                </tr>
                                <tr>
                                    <td style="border-left: 1px solid #000;">&nbsp;</td>
                                    <td>Komunikasi</td>
                                    <td style="text-align: right; border-left: 1px solid #000;">{{ number_format($spu['t_komunikasi_past'], 0) }}</td>
                                    <td style="text-align: right; border-left: 1px solid #000; border-right: 1px solid #000;">{{ number_format($spu['t_komunikasi_present'], 0) }}</td>
                                </tr>
                                <tr>
                                    <td style="border-left: 1px solid #000;">&nbsp;</td>
                                    <td>Lain-lain</td>
                                    <td style="text-align: right; border-left: 1px solid #000;">{{ number_format($spu['t_lain_past'], 0) }}</td>
                                    <td style="text-align: right; border-left: 1px solid #000; border-right: 1px solid #000;">{{ number_format($spu['t_lain_present'], 0) }}</td>
                                </tr>
                                <tr>
                                    <td style="border-left: 1px solid #000;">&nbsp;</td>
                                    <td>Kesehatan</td>
                                    <td style="text-align: right; border-left: 1px solid #000;">*</td>
                                    <td style="text-align: right; border-left: 1px solid #000; border-right: 1px solid #000;">*</td>
                                </tr>
                                <tr>
                                    <td style="border-left: 1px solid #000;">&nbsp;</td>
                                    <td>BPJS TK</td>
                                    <td style="text-align: right; border-left: 1px solid #000;">**</td>
                                    <td style="text-align: right; border-left: 1px solid #000; border-right: 1px solid #000;">**</td>
                                </tr>
                                <tr>
                                    <td style="border-left: 1px solid #000;">&nbsp;</td>
                                    <td>PPH 21</td>
                                    <td style="text-align: right; border-left: 1px solid #000;">***</td>
                                    <td style="text-align: right; border-left: 1px solid #000; border-right: 1px solid #000;">***</td>
                                </tr>
                                <tr>
                                    <td style="border-left: 1px solid #000;">&nbsp;</td>
                                    <td>Total Tunjangan</td>
                                    <td style="text-align: right; border-left: 1px solid #000;"><b>{{ number_format($spu['total_tunjangan_past'], 0) }}</b></td>
                                    <td style="text-align: right; border-left: 1px solid #000; border-right: 1px solid #000;"><b>{{ number_format($spu['total_tunjangan_present'], 0) }}</b></td>
                                </tr>
                                <tr>
                                    <td style="border-left: 1px solid #000; border-bottom: 1px solid #000;">&nbsp;</td>
                                    <td style="border-bottom: 1px solid #000;"><b>Take Home Pay (THP)</b></td>
                                    <td style="text-align: right; border-left: 1px solid #000; border-bottom: 1px solid #000;"><b>{{ number_format($spu['upah_past'], 0) }}</b></td>
                                    <td style="text-align: right; border-left: 1px solid #000; border-right: 1px solid #000; border-bottom: 1px solid #000;"><b>{{ number_format($spu['upah_present'], 0) }}</b></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row px-2">
                    <div class="col-md-12 col-sm-12">
                        <p style="text-align: justify;"><u>Catatan</u><br/>*,**, dan ***, ditanggung oleh perusahaan</p>
                    </div>
                </div>
                <div class="row px-2">
                    <div class="col-md-12 col-sm-12">
                        <p style="text-align: justify;">Demikian agar maklum adanya.<br/>Terima kasih.</p>
                    </div>
                </div>
                <div class="row g-0">
                    <div class="col-12 col-md-12 col-sm-12 text-center mb-2">
                        <form id="form" name="form" method="post" enctype="multipart/form-data" action="{{ route('payroll.review_post') }}" target="_self" onsubmit="return confirm('Are you sure want to submit this data?');">
                            @csrf
                            <input type="hidden" id="id" name="id" value="{{ $id }}">
                            <input type="hidden" id="pic" name="pic" value="{{ $pic }}">
                            <button type="submit" id="id_status" name="id_status" value="{{ $id_status }}" class="btn btn-success">Approve</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>
<script type="text/javascript">
// 
</script>

</html>
