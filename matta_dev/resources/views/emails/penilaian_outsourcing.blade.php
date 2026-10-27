@extends('layouts.errors.master')
@section('title', 'Formulir Evaluasi Karyawan')

@section('css')
@endsection

@section('style')
@endsection


@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h4><img src="{{ asset('assets/images/matta/form-icon-25.jpg') }}" height="30" alt="Alita" />&nbsp;Formulir Evaluasi Karyawan</h4>
                    <!-- <h4>Formulir Evaluasi Karyawan</h4> -->
                </div>
                <div class="card-body">
                    <table width="100%" bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                            <td>
                                <table bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0">
                                    <tr>
                                        <td>
                                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <tr>
                                                    <td>
                                                        <h5>Semangat Pagi,</h5>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Berikut ini adalah Formulir Evaluasi Karyawan yang harus diisi dan dilengkapi. Terima kasih.</td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <tr>
                                                    <td>
                                                        <hr />
                                                        Nama Karyawan: {{ $data['nama'] }}
                                                        <hr />
                                                        Jabatan: {{ $data['jabatan'] }}
                                                        <hr />
                                                        Direktorat / Divisi / Departemen: {{ $data['unit_kerja'] }}
                                                        <hr />
                                                        Nama Atasan Langsung: {{ $data['atasan'] }}
                                                        <hr />
                                                        Tanggal Bergabung: @if(!empty($data['tanggal_bergabung'])) {{ Carbon\Carbon::parse($data['tanggal_bergabung'])->format('d-m-Y') }} @endif<br/>
                                                        Tanggal Berakhir: @if(!empty($data['tanggal_berakhir'])) {{ Carbon\Carbon::parse($data['tanggal_berakhir'])->format('d-m-Y') }} @endif
                                                        <hr />
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <tr>
                                                    <td>
                                                        <table width="100%" border="0" cellspacing="1" cellpadding="1">
                                                            <tr>
                                                                <td>
                                                                    <h5>Petunjuk Pengisian:</h5>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>1) Penilaian dilakukan oleh atasan langsung (minimal level Assistant Manager atau jabatan Project Manager atau Kepala Departemen).</td>
                                                            </tr>
                                                            <tr>
                                                                <td>2) Obyek penilaian adalah kompetensi karyawan (soft skill).</td>
                                                            </tr>
                                                            <tr>
                                                                <td>3) Cara pemberian skor:</td>
                                                            </tr>
                                                            <tr>
                                                                <td>3.1 Penilai diwajibkan untuk memberikan feedback atas beberapa obyek penilaian dengan menggunakan 5 (lima) poin skala penilaian.</td>
                                                            </tr>
                                                            <tr>
                                                                <td>3.2 Semakin tinggi poin skala, semakin positif kemampuan/perilaku yang ditunjukkan oleh karyawan.</td>
                                                            </tr>
                                                            <tr>
                                                                <td>3.3 Apabila ada pertanyaan yang tidak diisi, maka skor dihitung nol dan tidak akan diperhitungkan dalam rata-rata penilaian.</td>
                                                            </tr>
                                                            <tr>
                                                                <td>3.4 Diisi dengan pilihan skor 1,2,3,4, atau 5.</td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <table width="100%" border="0" cellspacing="1"
                                                                        cellpadding="1">
                                                                        <tr>
                                                                            <td width="25%">
                                                                                <div align="center">SKOR</div>
                                                                            </td>
                                                                            <td width="15%">
                                                                                <div align="center">1</div>
                                                                            </td>
                                                                            <td width="15%">
                                                                                <div align="center">2</div>
                                                                            </td>
                                                                            <td width="15%">
                                                                                <div align="center">3</div>
                                                                            </td>
                                                                            <td width="15%">
                                                                                <div align="center">4</div>
                                                                            </td>
                                                                            <td width="15%">
                                                                                <div align="center">5</div>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>FREKUENSI</td>
                                                                            <td>Hampir tidak pernah</td>
                                                                            <td>Kadang kadang</td>
                                                                            <td>Cukup Sering</td>
                                                                            <td>Sering</td>
                                                                            <td>Hampir selalu ada</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>KEMAMPUAN</td>
                                                                            <td>Sangat kurang</td>
                                                                            <td>Kurang</td>
                                                                            <td>Cukup Baik</td>
                                                                            <td>Baik</td>
                                                                            <td>Sangat baik</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>EFEKTIVITAS</td>
                                                                            <td>1-20%</td>
                                                                            <td>21-40%</td>
                                                                            <td>41-60%</td>
                                                                            <td>61-80%</td>
                                                                            <td>81-100%</td>
                                                                        </tr>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>4) Memberikan kesimpulan dan rekomendasi sesuai kebutuhan.</td>
                                                            </tr>
                                                            <tr>
                                                                <td>5) Wajib memilih status perpanjangan dan durasi perpanjangan kontrak (jika memilih diperpanjang).</td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <hr />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <form id="myform" name="myform" method="post" enctype="multipart/form-data" action="{{ $linkAction }}" target="_self" onsubmit="return confirm('Are you sure want to submit this data?');">
                                                @method('PATCH')
                                                @csrf
                                                <input type="hidden" id="pic" name="pic" value="{{ $pic }}">
                                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                    <tr>
                                                        <td>
                                                            <table width="100%" border="0" cellspacing="0"
                                                                cellpadding="0">
                                                                <tr>
                                                                    <td>
                                                                        <h5>FORMULIR PENILAIAN</h5>
                                                                        <hr />
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        <!--  -->
                                                                        {!! $isian_penilaian !!}
                                                                        <!--  -->
                                                                        <hr />
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                    @if($jumlah_nilai_outsourcing>0)
                                                                        @if($jumlah_soal_outsourcing==14)
                                                                            <b>Total Score: {{ $jumlah_nilai_outsourcing }} (@if($jumlah_nilai_outsourcing>35) Extend @else Not Extend @endif)</b> 
                                                                        @else
                                                                            <b>Total Score: {{ $jumlah_nilai_outsourcing }} (@if($jumlah_nilai_outsourcing>40) Extend @else Not Extend @endif)</b> 
                                                                        @endif
                                                                        <hr />
                                                                    @endif
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        <b>Tanggal Berakhir: @if(!empty($data['tanggal_berakhir'])) {{ Carbon\Carbon::parse($data['tanggal_berakhir'])->format('d-m-Y') }} @endif </b>
                                                                        <hr />
                                                                    </td>
                                                                </tr>
                                                                <!--  -->
                                                                {!! $isian_kes_rek !!}
                                                                <!--  -->
                                                                {!! $kes_rek !!}
                                                                <!--  -->
                                                                @if($pic != 'nik_karyawan')
                                                                <tr>
                                                                    <td>
                                                                        <div class="row g-3">
                                                                            <div class="col-md-4" id="div_is_perpanjang">
                                                                                <label class="form-label" for="is_perpanjang">Status Perpanjangan</label>
                                                                                <select class="form-select" id="is_perpanjang" name="is_perpanjang" required="">
                                                                                    <option selected="" disabled="" value="">Choose...</option>
                                                                                    <option value="0" >Tidak Diperpanjang</option>
                                                                                    <option value="1" >Diperpanjang</option>
                                                                                </select>
                                                                            </div>
                                                                            <div class="col-md-4" id="div_bulan">
                                                                                <label class="form-label" for="bulan">&nbsp;</label>
                                                                                <input id="bulan" name="bulan" required="" class="form-control numbers" type="number" min="1" oninput="validity.valid||(value='');" placeholder="Jumlah Bulan">
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                @endif
                                                                <!--  -->
                                                                <tr>
                                                                    <td>
                                                                        <table border="0" cellspacing="0"
                                                                            cellpadding="0">
                                                                            <tr>
                                                                                <td>
                                                                                    <br /><button type="submit" class="btn btn-primary">SUBMIT</button>
                                                                                    <!-- <input type="submit" class="btn btn-primary"> -->
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </form>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <!--  -->
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body typography text-center">
                    <h6>Terima kasih untuk partisipasi dan kerjasamanya dalam pengisian Formulir Evaluasi Karyawan ini.
                    </h6>
                    <a href="https://alita.id/"><img src="{{ asset('assets/images/matta/Alita-New-Log.png') }}"
                            height="40" alt="Alita" /></a><br />
                    <h6>&reg; PT Alita Praya Mitra - 2021</h6>
                </div>
            </div>
        </div>
        <!--  -->
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('assets/images/matta/jquery-3.5.1.min.js') }}"></script>
<script src="{{ asset('assets/images/matta/jquery.validate.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $("#bulan").prop('disabled', true);
        $('#div_bulan').hide();
    });
    $("#is_perpanjang").change(function() {
        var valueStatus = $(this).val();
        if (valueStatus == 1) {
            $("#bulan").prop('disabled', false);
            $('#div_bulan').show();
        } else {
            $("#bulan").prop('disabled', true);
            $('#div_bulan').hide();
        }
    });
</script>
@endsection