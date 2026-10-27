@extends('layouts.simple.master')
@section('title', 'Formulir Penilaian Karyawan')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/sweetalert2.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/select2.css') }}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
    <h3>Revisi</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Penilaian Karyawan</li>
    <li class="breadcrumb-item active">Revisi</li>
@endsection
<!-- https://makitweb.com/auto-populate-dropdown-with-jquery-ajax-in-laravel/ -->
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Formulir Evaluasi Karyawan</h5>
                    </div>
                    <div class="card-body typography">
                        <table class="table table-striped">
                            <tbody>
                                <tr>
                                    <td width="40%">Nama Karyawan</td>
                                    <td width="60%">{{ $_karyawan['nama'] }}</td>
                                </tr>
                                <tr>
                                    <td>NIP</td>
                                    <td>{{ $_karyawan['nik'] }}</td>
                                </tr>
                                <tr>
                                    <td>Jabatan</td>
                                    <td>{{ $_karyawan['jabatan'] }}</td>
                                </tr>
                                <tr>
                                    <td>Direktorat / Divisi / Departemen</td>
                                    <td>{{ $_karyawan['divisi'] }} / {{ $_karyawan['dep'] }} / {{ $_karyawan['sub_dep'] }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Perusahaan</td>
                                    <td>{{ $_karyawan['perusahaan'] }}</td>
                                </tr>
                                <tr>
                                    <td>Nama Atasan Langsung</td>
                                    <td>{{ $penilai1['nama'] }}</td>
                                </tr>
                                <tr>
                                    <td>Tanggal Bergabung</td>
                                    <td>
                                        @if (!empty($_karyawan['tanggal_bergabung']))
                                            {{ Carbon\Carbon::parse($_karyawan['tanggal_bergabung'])->format('d-m-Y') }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Tanggal Berakhir</td>
                                    <td>
                                        @if (!empty($_karyawan['tanggal_berakhir']))
                                            {{ Carbon\Carbon::parse($_karyawan['tanggal_berakhir'])->format('d-m-Y') }}
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-body">
                        @if ($penilaian->id_tipe == 6)
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td width="50%"><strong>Obyek Penilaian</strong></td>
                                        <td width="50%">
                                            <div align="center"><strong>Penilai 1</strong></div>
                                        </td>
                                    </tr>
                                    @foreach ($angka_skor as $data)
                                        <tr>
                                            <td>{{ $data->id_nilai3 }}. {{ $data->penilaian_1 }}</td>
                                            <td>
                                                <div align="center">{{ $data->skor_penilai_1 }}</div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @if ($angka_rata > 0)
                                        <tr>
                                            <td>
                                                <div align="right"><strong>Total Score</strong></div>
                                            </td>
                                            <td>
                                                <div align="center"><strong>{{ $angka_rata }}</div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                            @if (count($angka_skor) == 14)
                                                <td>
                                                    <div align="center"><strong>Result: @if ($angka_rata > 35)
                                                                Extend
                                                            @else
                                                                Not Extend
                                                            @endif
                                                        </strong>
                                                    </div>
                                                </td>
                                            @else
                                                <td>
                                                    <div align="center"><strong>Result: @if ($angka_rata > 40)
                                                                Extend
                                                            @else
                                                                Not Extend
                                                            @endif
                                                        </strong></div>
                                                </td>
                                            @endif
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        @else
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td width="70%"><strong>Obyek Penilaian</strong></td>
                                        <td width="15%">
                                            <div align="center"><strong>Karyawan</strong></div>
                                        </td>
                                        <td width="15%">
                                            <div align="center"><strong>Penilai 1</strong></div>
                                        </td>
                                    </tr>
                                    {!! $angka_skor !!}
                                    {!! $angka_total !!}
                                    {!! $angka_rata !!}
                                </tbody>
                            </table>
                        @endif
                    </div>
                    <div class="card-body">
                        {!! $isian_kes_rek !!}
                    </div>
                </div>
            </div>
            <form action="{{ route('revisi_penilaian_save') }}" method="post" enctype="multipart/form-data"
                onsubmit="return confirm('Are you sure want to submit this data?');">
                @csrf
                <input type="hidden" id="id" name="id" value="{{ $penilaian->id }}">
                <div class="col-sm-12">
                    <div class="card">
                        <!-- penilai1 -->
                        @if (!empty($penilaian->nik_penilai_1))
                            @php
                                $where = [
                                    'id_trx' => $penilaian->id,
                                    'nik_pic' => $penilaian->nik_penilai_1,
                                ];
                                $trx = App\Models\TrxPenilaianLog::where($where)
                                    ->whereNotNull('is_perpanjang')
                                    ->orderBy('created_at', 'desc')
                                    ->first();
                                $kes_rek = $penilaian->kes_rek_1;
                                $is_perpanjang = 0;
                                $bulan = 0;
                                if (!empty($trx) && $trx->is_perpanjang) {
                                    $is_perpanjang = $trx->is_perpanjang;
                                    $bulan = $trx->bulan;
                                }
                            @endphp
                            @if(!empty($trx))
                                <input type="hidden" id="logid_1" name="logid_1" value="{{ $trx->id }}">
                                <div class="card-header">
                                    <h5>Revisi Penilai 1</h5>
                                </div>
                                <div class="card-body typography">
                                    <table class="table table-striped">
                                        <tbody>
                                            <tr>
                                                <td width="40%">Nama / NIP</td>
                                                <td width="60%">{{ $penilai1['nama'] }} / {{ $penilai1['nik'] }}</td>
                                            </tr>
                                            <tr>
                                                <td>Jabatan</td>
                                                <td>{{ $penilai1['jabatan'] }}</td>
                                            </tr>
                                            <tr>
                                                <td>Direktorat / Divisi / Departemen</td>
                                                <td>{{ $penilai1['divisi'] }} / {{ $penilai1['dep'] }} /
                                                    {{ $penilai1['sub_dep'] }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    {{-- Kesimpulan dan Rekomendasi --}}
                                    <div class="row mt-2 mb-2">
                                        <div class="col-md-8">
                                            <label class="form-label" for="kesrek_1">Kesimpulan dan Rekomendasi</label>
                                            <textarea class="form-control" name="kesrek_1" id="kesrek_1" rows="3">{{ $kes_rek }}</textarea>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-md-4" id="div_isperpanjang_1">
                                            <label class="form-label" for="isperpanjang_1">Status Perpanjangan</label>
                                            <select class="form-select" id="isperpanjang_1" name="isperpanjang_1"
                                                required="">
                                                <option selected="" disabled="" value="">Choose...</option>
                                                <option value="0" @if ($is_perpanjang == 0) selected="" @endif>
                                                    Tidak
                                                    Diperpanjang</option>
                                                <option value="1" @if ($is_perpanjang == 1) selected="" @endif>
                                                    Diperpanjang</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4" id="div_bulan_1">
                                            <label class="form-label" for="bulan_1">&nbsp;</label>
                                            <input id="bulan_1" name="bulan_1" required="" class="form-control numbers"
                                                type="number" min="1" oninput="validity.valid||(value='');"
                                                placeholder="Jumlah Bulan" value="{{ $bulan }}">
                                        </div>
                                    </div>
                                    <br />
                                    <h5>History</h5>
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Status Perpanjangan</th>
                                                <th>Kesimpulan dan Rekomendasi</th>
                                                <th>Waktu</th>
                                            </tr>
                                        </thead>
                                        @php
                                            $where = [
                                                'id_trx' => $penilaian->id,
                                                'nik_pic' => $penilaian->nik_penilai_1,
                                            ];
                                            $trx = App\Models\TrxPenilaianLogHistory::where($where)
                                                ->whereNotNull('is_perpanjang')
                                                ->orderBy('created_at', 'desc')
                                                ->get();
                                        @endphp
                                        <tbody>
                                            @foreach ($trx as $_trx)
                                                <tr>
                                                    <td>
                                                        @if ($_trx->is_perpanjang == 1)
                                                            Diperpanjang {{ $_trx->bulan }} bulan
                                                        @else
                                                            Tidak Diperpanjang
                                                        @endif
                                                    </td>
                                                    <td>{{ $_trx->keterangan }}</td>
                                                    <td>{{ $_trx->created_at }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        @endif
                        <!-- penilai2 -->
                        @if (!empty($penilaian->nik_penilai_2))
                            @php
                                $where = [
                                    'id_trx' => $penilaian->id,
                                    'nik_pic' => $penilaian->nik_penilai_2,
                                ];
                                $trx = App\Models\TrxPenilaianLog::where($where)
                                    ->whereNotNull('is_perpanjang')
                                    ->orderBy('created_at', 'desc')
                                    ->first();
                                $kes_rek = $penilaian->kes_rek_2;
                                $is_perpanjang = 0;
                                $bulan = 0;
                                if (!empty($trx) && $trx->is_perpanjang) {
                                    $is_perpanjang = $trx->is_perpanjang;
                                    $bulan = $trx->bulan;
                                }
                            @endphp
                            @if(!empty($trx))
                                <input type="hidden" id="logid_2" name="logid_2" value="{{ $trx->id }}">
                                <div class="card-header">
                                    <h5>Revisi Penilai 2</h5>
                                </div>
                                <div class="card-body typography">
                                    <table class="table table-striped">
                                        <tbody>
                                            <tr>
                                                <td width="40%">Nama / NIP</td>
                                                <td width="60%">{{ $penilai2['nama'] }} / {{ $penilai2['nik'] }}</td>
                                            </tr>
                                            <tr>
                                                <td>Jabatan</td>
                                                <td>{{ $penilai2['jabatan'] }}</td>
                                            </tr>
                                            <tr>
                                                <td>Direktorat / Divisi / Departemen</td>
                                                <td>{{ $penilai2['divisi'] }} / {{ $penilai2['dep'] }} /
                                                    {{ $penilai2['sub_dep'] }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    {{-- Kesimpulan dan Rekomendasi --}}
                                    <div class="row mt-2 mb-2">
                                        <div class="col-md-8">
                                            <label class="form-label" for="kesrek_2">Kesimpulan dan Rekomendasi</label>
                                            <textarea class="form-control" name="kesrek_2" id="kesrek_2" rows="3">{{ $kes_rek }}</textarea>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-md-4" id="div_isperpanjang_2">
                                            <label class="form-label" for="isperpanjang_2">Status Perpanjangan</label>
                                            <select class="form-select" id="isperpanjang_2" name="isperpanjang_2"
                                                required="">
                                                <option selected="" disabled="" value="">Choose...</option>
                                                <option value="0" @if ($is_perpanjang == 0) selected="" @endif>
                                                    Tidak
                                                    Diperpanjang</option>
                                                <option value="1" @if ($is_perpanjang == 1) selected="" @endif>
                                                    Diperpanjang</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4" id="div_bulan_2">
                                            <label class="form-label" for="bulan_2">&nbsp;</label>
                                            <input id="bulan_2" name="bulan_2" required="" class="form-control numbers"
                                                type="number" min="1" oninput="validity.valid||(value='');"
                                                placeholder="Jumlah Bulan" value="{{ $bulan }}">
                                        </div>
                                    </div>
                                    <br />
                                    <h5>History</h5>
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Status Perpanjangan</th>
                                                <th>Kesimpulan dan Rekomendasi</th>
                                                <th>Waktu</th>
                                            </tr>
                                        </thead>
                                        @php
                                            $where = [
                                                'id_trx' => $penilaian->id,
                                                'nik_pic' => $penilaian->nik_penilai_2,
                                            ];
                                            $trx = App\Models\TrxPenilaianLogHistory::where($where)
                                                ->whereNotNull('is_perpanjang')
                                                ->orderBy('created_at', 'desc')
                                                ->get();
                                        @endphp
                                        <tbody>
                                            @foreach ($trx as $_trx)
                                                <tr>
                                                    <td>
                                                        @if ($_trx->is_perpanjang == 1)
                                                            Diperpanjang {{ $_trx->bulan }} bulan
                                                        @else
                                                            Tidak Diperpanjang
                                                        @endif
                                                    </td>
                                                    <td>{{ $_trx->keterangan }}</td>
                                                    <td>{{ $_trx->created_at }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        @endif
                        <!-- penilai3 -->
                        @if (!empty($penilaian->nik_penilai_3))
                            @php
                                $where = [
                                    'id_trx' => $penilaian->id,
                                    'nik_pic' => $penilaian->nik_penilai_3,
                                ];
                                $trx = App\Models\TrxPenilaianLog::where($where)
                                    ->whereNotNull('is_perpanjang')
                                    ->orderBy('created_at', 'desc')
                                    ->first();
                                $kes_rek = $penilaian->kes_rek_3;
                                $is_perpanjang = 0;
                                $bulan = 0;
                                if (!empty($trx) && $trx->is_perpanjang) {
                                    $is_perpanjang = $trx->is_perpanjang;
                                    $bulan = $trx->bulan;
                                }
                            @endphp
                            @if(!empty($trx))
                                <input type="hidden" id="logid_3" name="logid_3" value="{{ $trx->id }}">
                                <div class="card-header">
                                    <h5>Revisi Penilai 3</h5>
                                </div>
                                <div class="card-body typography">
                                    <table class="table table-striped">
                                        <tbody>
                                            <tr>
                                                <td width="40%">Nama / NIP</td>
                                                <td width="60%">{{ $penilai3['nama'] }} / {{ $penilai3['nik'] }}</td>
                                            </tr>
                                            <tr>
                                                <td>Jabatan</td>
                                                <td>{{ $penilai3['jabatan'] }}</td>
                                            </tr>
                                            <tr>
                                                <td>Direktorat / Divisi / Departemen</td>
                                                <td>{{ $penilai3['divisi'] }} / {{ $penilai3['dep'] }} /
                                                    {{ $penilai3['sub_dep'] }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    {{-- Kesimpulan dan Rekomendasi --}}
                                    <div class="row mt-2 mb-2">
                                        <div class="col-md-8">
                                            <label class="form-label" for="kesrek_3">Kesimpulan dan Rekomendasi</label>
                                            <textarea class="form-control" name="kesrek_3" id="kesrek_3" rows="3">{{ $kes_rek }}</textarea>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-md-4" id="div_isperpanjang_3">
                                            <label class="form-label" for="isperpanjang_3">Status Perpanjangan</label>
                                            <select class="form-select" id="isperpanjang_3" name="isperpanjang_3"
                                                required="">
                                                <option selected="" disabled="" value="">Choose...</option>
                                                <option value="0" @if ($is_perpanjang == 0) selected="" @endif>
                                                    Tidak
                                                    Diperpanjang</option>
                                                <option value="1" @if ($is_perpanjang == 1) selected="" @endif>
                                                    Diperpanjang</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4" id="div_bulan_3">
                                            <label class="form-label" for="bulan_3">&nbsp;</label>
                                            <input id="bulan_3" name="bulan_3" required="" class="form-control numbers"
                                                type="number" min="1" oninput="validity.valid||(value='');"
                                                placeholder="Jumlah Bulan" value="{{ $bulan }}">
                                        </div>
                                    </div>
                                    <br />
                                    <h5>History</h5>
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Status Perpanjangan</th>
                                                <th>Kesimpulan dan Rekomendasi</th>
                                                <th>Waktu</th>
                                            </tr>
                                        </thead>
                                        @php
                                            $where = [
                                                'id_trx' => $penilaian->id,
                                                'nik_pic' => $penilaian->nik_penilai_3,
                                            ];
                                            $trx = App\Models\TrxPenilaianLogHistory::where($where)
                                                ->whereNotNull('is_perpanjang')
                                                ->orderBy('created_at', 'desc')
                                                ->get();
                                        @endphp
                                        <tbody>
                                            @foreach ($trx as $_trx)
                                                <tr>
                                                    <td>
                                                        @if ($_trx->is_perpanjang == 1)
                                                            Diperpanjang {{ $_trx->bulan }} bulan
                                                        @else
                                                            Tidak Diperpanjang
                                                        @endif
                                                    </td>
                                                    <td>{{ $_trx->keterangan }}</td>
                                                    <td>{{ $_trx->created_at }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        @endif
                        <!-- hco -->
                        @if ($penilaian->id_status == 4 || $penilaian->id_status == 8)
                            @php
                                $kes_rek = $penilaian->kes_rek_final;
                                $is_perpanjang = $penilaian->is_perpanjang;
                                $bulan = $penilaian->bulan;
                                //
                                $where = [
                                    'id_trx' => $penilaian->id,
                                ];
                                $trx = App\Models\TrxPenilaianLog::where($where)
                                    ->whereIn('id_status', [4, 8])
                                    ->orderBy('created_at', 'desc')
                                    ->first();
                            @endphp
                            @if(!empty($trx))
                                <input type="hidden" id="logid_hco" name="logid_hco" value="{{ $trx->id }}">
                                <div class="card-header">
                                    <h5>Revisi {{ App\Models\MtrUtility::find(51)->informasi }}</h5>
                                </div>
                                <div class="card-body typography">
                                    {{-- Kesimpulan dan Rekomendasi --}}
                                    <div class="row mt-2 mb-2">
                                        <div class="col-md-8">
                                            <label class="form-label" for="kesrek_hco">Kesimpulan dan Rekomendasi</label>
                                            <textarea class="form-control" name="kesrek_hco" id="kesrek_hco" rows="3">{{ $kes_rek }}</textarea>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-md-4" id="div_isperpanjang_hco">
                                            <label class="form-label" for="isperpanjang_hco">Status Perpanjangan</label>
                                            <select class="form-select" id="isperpanjang_hco" name="isperpanjang_hco"
                                                required="">
                                                <option selected="" disabled="" value="">Choose...</option>
                                                <option value="0" @if ($is_perpanjang == 2) selected="" @endif>
                                                    Tidak
                                                    Diperpanjang</option>
                                                <option value="1" @if ($is_perpanjang == 1) selected="" @endif>
                                                    Diperpanjang</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4" id="div_bulan_hco">
                                            <label class="form-label" for="bulan_hco">&nbsp;</label>
                                            <input id="bulan_hco" name="bulan_hco" required=""
                                                class="form-control numbers" type="number" min="1"
                                                oninput="validity.valid||(value='');" placeholder="Jumlah Bulan"
                                                value="{{ $bulan }}">
                                        </div>
                                    </div>
                                    <br />
                                    <h5>History</h5>
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Status Perpanjangan</th>
                                                <th>Kesimpulan dan Rekomendasi</th>
                                                <th>Waktu</th>
                                            </tr>
                                        </thead>
                                        @php
                                            $where = [
                                                'id_trx' => $penilaian->id,
                                            ];
                                            $trx = App\Models\TrxPenilaianLogHistory::where($where)
                                                ->whereIn('id_status', [4, 8])
                                                ->whereNotNull('is_perpanjang')
                                                ->orderBy('created_at', 'desc')
                                                ->get();
                                        @endphp
                                        <tbody>
                                            @foreach ($trx as $_trx)
                                                <tr>
                                                    <td>
                                                        @if ($_trx->is_perpanjang == 1)
                                                            Diperpanjang {{ $_trx->bulan }} bulan
                                                        @else
                                                            Tidak Diperpanjang
                                                        @endif
                                                    </td>
                                                    <td>{{ $_trx->keterangan }}</td>
                                                    <td>{{ $_trx->created_at }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        @endif
                        @can('hc')
                        <div class="card-body typography">
                            <button class="btn btn-success" type="submit">Save</button> | <a href="{{ route('penilaian.index') }}" class="btn btn-warning" type="button">Back</a>
                        </div>
                        @endcan
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('assets/js/form-validation-custom.js') }}"></script>
    <script src="{{ asset('assets/js/sweet-alert/sweetalert.min.js') }}"></script>
    <script src="{{ asset('assets/js/sweet-alert/app.js') }}"></script>
    <script src="{{ asset('assets/js/select2/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/js/select2/select2-custom.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $("#bulan_1").prop('disabled', true);
            $('#div_bulan_1').hide();
            if ($("#isperpanjang_1").val() == 1) {
                $("#bulan_1").prop('disabled', false);
                $('#div_bulan_1').show();
            }
        });
        $("#isperpanjang_1").change(function() {
            var valueStatus = $(this).val();
            if (valueStatus == 1) {
                $("#bulan_1").prop('disabled', false);
                $('#div_bulan_1').show();
            } else {
                $("#bulan_1").prop('disabled', true);
                $('#div_bulan_1').hide();
            }
        });
        // 
        $(document).ready(function() {
            $("#bulan_2").prop('disabled', true);
            $('#div_bulan_2').hide();
            if ($("#isperpanjang_2").val() == 1) {
                $("#bulan_2").prop('disabled', false);
                $('#div_bulan_2').show();
            }
        });
        $("#isperpanjang_2").change(function() {
            var valueStatus = $(this).val();
            if (valueStatus == 1) {
                $("#bulan_2").prop('disabled', false);
                $('#div_bulan_2').show();
            } else {
                $("#bulan_2").prop('disabled', true);
                $('#div_bulan_2').hide();
            }
        });
        // 
        $(document).ready(function() {
            $("#bulan_3").prop('disabled', true);
            $('#div_bulan_3').hide();
            if ($("#isperpanjang_3").val() == 1) {
                $("#bulan_3").prop('disabled', false);
                $('#div_bulan_3').show();
            }
        });
        $("#isperpanjang_3").change(function() {
            var valueStatus = $(this).val();
            if (valueStatus == 1) {
                $("#bulan_3").prop('disabled', false);
                $('#div_bulan_3').show();
            } else {
                $("#bulan_3").prop('disabled', true);
                $('#div_bulan_3').hide();
            }
        });
        // 
        $(document).ready(function() {
            $("#bulan_hco").prop('disabled', true);
            $('#div_bulan_hco').hide();
            if ($("#isperpanjang_hco").val() == 1) {
                $("#bulan_hco").prop('disabled', false);
                $('#div_bulan_hco').show();
            }
        });
        $("#isperpanjang_hco").change(function() {
            var valueStatus = $(this).val();
            if (valueStatus == 1) {
                $("#bulan_hco").prop('disabled', false);
                $('#div_bulan_hco').show();
            } else {
                $("#bulan_hco").prop('disabled', true);
                $('#div_bulan_hco').hide();
            }
        });
    </script>
@endsection
