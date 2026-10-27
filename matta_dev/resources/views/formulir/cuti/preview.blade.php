@extends('layouts.errors.master')
@section('title', 'Formulir Permohonan Cuti')

@section('css')
@endsection

@section('style')
@endsection


@section('content')
<div class="container-fluid">
    <div class="row">
        <!--  -->
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h4><img src="{{ asset('assets/images/matta/form-icon-25.jpg') }}" height="30" alt="Alita" />&nbsp;Preview Formulir Permohonan Cuti</h4>
                </div>
            </div>
        </div>
        {{--  --}}
        <div class="col-sm-12">
            <div class="card">
                {{-- <div class="card-body"> --}}
                    @if(!$cek)
                    <div class="alert alert-danger dark alert-dismissible fade show" role="alert">
                        <!-- <img class="img-50" src="{{asset('assets/images/other-images/sad.png')}}" alt="error"> -->
                        <h4>ERROR:</h4>
                        @if ($arr[0]['status']) <h5>
                            {{ $arr[0]['message'] }} - OK</h5>
                        @else <h5>{{ $arr[0]['message'] }} - NOK</h5> @endif
                        @if ($arr[1]['status']) <h5>
                            {{ $arr[1]['message'] }} - OK</h5>
                        @else <h5>{{ $arr[1]['message'] }} - NOK</h5> @endif
                        @if ($arr[2]['status']) <h5>
                            {{ $arr[2]['message'] }} - OK</h5>
                        @else <h5>{{ $arr[2]['message'] }} - NOK</h5> @endif
                        @if ($arr[3]['status']) <h5>
                            {{ $arr[3]['message'] }} - OK</h5>
                        @else <h5>{{ $arr[3]['message'] }} - NOK</h5> @endif
                        @if ($arr[4]['status']) <h5>
                            {{ $arr[4]['message'] }} - OK</h5>
                        @else <h5>{{ $arr[4]['message'] }} - NOK</h5> @endif
                        @if ($arr[5]['status']) <h5>
                            {{ $arr[5]['message'] }} - OK</h5>
                        @else <h5>{{ $arr[5]['message'] }} - NOK</h5> @endif
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                {{-- </div> --}}
            </div>
        </div>
        @php $data = json_decode($trx->karyawan_property, true); @endphp
        <div class="col-sm-12 col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h5>DIAJUKAN OLEH</h5>
                </div>
                <div class="card-header">
                    <a href="{{ asset('assets/images/matta/karyawan/'.$data['nik'].'/'.$data['nik'].'.jpg') }}"
                        target="_blank"><img class="img-thumbnail" width="20%"
                            src="{{ asset('assets/images/matta/karyawan/'.$data['nik'].'/'.$data['nik'].'.jpg') }}"
                            alt="user"></a>
                </div>
                <div class="card-body typography">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <td width="30%">Nama</td>
                                <td width="70%">{{ $data['nama'] }}</td>
                            </tr>
                            <tr>
                                <td>NIP</td>
                                <td>{{ $data['nik'] }}</td>
                            </tr>
                            <tr>
                                <td>Jabatan</td>
                                <td>{{ $data['jabatan'] }}</td>
                            </tr>
                            <tr>
                                <td>Direktorat / Divisi / Departemen</td>
                                <td>{{ $data['divisi'] . '/' . $data['dep'] . '/' . $data['sub_dep'] }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!--  -->
                <div class="card-header">
                    <h5>TIPE CUTI</h5>
                </div>
                <div class="card-body typography">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <td width="30%">Tipe Cuti</td>
                                <td width="70%">{{ $trx->_tipe->tipe_cuti }}</td>
                            </tr>
                            <tr>
                                <td>Tahun Periode Cuti</td>
                                <td>{{ $trx->periode_cuti }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!--  -->
                <div class="card-header">
                    <h5>MASA CUTI</h5>
                </div>
                <div class="card-body typography">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <td width="30%">Mengajukan cuti pada</td>
                                <td width="70%">{{ $arrTanggal['tanggal_mulai'] }}<br />sampai dengan<br />{{ $arrTanggal['tanggal_akhir'] }}</td>
                            </tr>
                            <tr>
                                <td>Jumlah hari cuti</td>
                                <td>{{ $trx->jumlah_hari }}</td>
                            </tr>
                            {{-- 12=Ibadah Umroh (7 hari) --}}
                            @if($trx->tipe_cuti == 12)
                            {{-- 5 hari sebelum --}}
                            <tr>
                                <td>Tanggal sebelum</td>
                                <td>
                                    @for ($x = 0; $x <5; $x++)
                                        {{ $arrTanggal['arrTanggalCuti'][$x] }}<br />
                                    @endfor
                                    {{-- @foreach ($arrTanggal['arrTanggalCuti'] as $value)
                                    {{ $value }}<br />
                                    @endforeach --}}
                                </td>
                            </tr>
                            {{-- 7 hari umroh --}}
                            <tr>
                                <td>Tanggal umroh</td>
                                <td>
                                    @for ($x = 5; $x <12; $x++)
                                        {{ $arrTanggal['arrTanggalCuti'][$x] }}<br />
                                    @endfor
                                    {{-- @foreach ($arrTanggal['arrTanggalCuti'] as $value)
                                    {{ $value }}<br />
                                    @endforeach --}}
                                </td>
                            </tr>
                            {{-- 3 hari sesudah --}}
                            <tr>
                                <td>Tanggal sesudah</td>
                                <td>
                                    @for ($x = 12; $x <15; $x++)
                                        {{ $arrTanggal['arrTanggalCuti'][$x] }}<br />
                                    @endfor
                                    {{-- @foreach ($arrTanggal['arrTanggalCuti'] as $value)
                                    {{ $value }}<br />
                                    @endforeach --}}
                                </td>
                            </tr>
                            @else
                            <tr>
                                <td>Tanggal cuti</td>
                                <td>
                                    @foreach ($arrTanggal['arrTanggalCuti'] as $value)
                                    {{ $value }}<br />
                                    @endforeach
                                </td>
                            </tr>
                            @endif
                            <tr>
                                <td>Masuk bekerja kembali</td>
                                <td>{{ $arrTanggal['tanggal_masuk'] }}</td>
                            </tr>
                            <tr>
                                <td>Alasan pengajuan cuti</td>
                                <td>{{ $trx->alasan }}</td>
                            </tr>
                            <tr>
                                <td>Alamat</td>
                                <td>{{ $trx->alamat }}</td>
                            </tr>
                            <tr>
                                <td>Nomor telepon</td>
                                <td>{{ $trx->telepon }}</td>
                            </tr>
                            <tr>
                                <td>Keterangan</td>
                                <td>{!! $trx->keterangan !!}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!--  -->
                <div class="card-header">
                    <h5>RESUME</h5>
                </div>
                <div class="card-body typography">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <td width="30%">Tipe Cuti</td>
                                <td width="70%">{{ $trx->_tipe->tipe_cuti }}</td>
                            </tr>
                            <tr>
                                <td>Tahun Periode Cuti</td>
                                <td>{{ $trx->periode_cuti }}</td>
                            </tr>
                            <tr>
                                <td>Jumlah hari cuti</td>
                                <td>{{ $trx->jumlah_hari }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!--  -->
        <div class="col-sm-12 col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h5>PERSONEL PENGGANTI</h5>
                </div>
                @foreach ($arrPicProperty as $value)
                <div class="card-header">
                    <a href="{{ asset('assets/images/matta/karyawan/'.$value['nik'].'/'.$value['nik'].'.jpg') }}"
                        target="_blank"><img class="img-thumbnail" width="20%"
                            src="{{ asset('assets/images/matta/karyawan/'.$value['nik'].'/'.$value['nik'].'.jpg') }}"
                            alt="user"></a>
                </div>
                <div class="card-body typography">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <td width="30%">Nama</td>
                                <td width="70%">{{ $value['nama'] }}</td>
                            </tr>
                            <tr>
                                <td>NIP</td>
                                <td>{{ $value['nik'] }}</td>
                            </tr>
                            <tr>
                                <td>Email</td>
                                <td>{{ $value['email'] }}</td>
                            </tr>
                            <tr>
                                <td>Telepon</td>
                                <td>{{ $value['telepon1'] }}</td>
                            </tr>
                            <tr>
                                <td>Jabatan</td>
                                <td>{{ $value['jabatan'] }}</td>
                            </tr>
                            <tr>
                                <td>Direktorat / Divisi / Departemen</td>
                                <td>{{ $value['divisi'] }} / {{ $value['dep'] }} / {{ $value['sub_dep'] }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                @endforeach
            </div>
        </div>
        {{--  --}}
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body typography">
                    <a href="javascript:window.close()" class="btn btn-warning" type="button">Close</a>
                </div>
            </div>
        </div>
        <!--  -->
    </div>
</div>
@endsection

@section('script')

@endsection