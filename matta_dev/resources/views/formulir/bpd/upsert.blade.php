@extends('layouts.simple.master')
@section('title', 'Biaya Perjalanan Dinas')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/sweetalert2.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/select2.css')}}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>
    @if ($data['luar_dalam'] == 'ca_dalam') Cash Advance Perjalanan Dinas Dalam Negeri
    @elseif ($data['luar_dalam'] == 'ca_luar') Cash Advance Perjalanan Dinas Luar Negeri
    @endif
</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Biaya Perjalanan Dinas</li>
<li class="breadcrumb-item active">@if ($data['status'] == 'insert') Add @else Edit @endif</li>
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12 col-xl-12">
            <div class="card">
                @if(Session::has('msg'))
                <div class="alert alert-success dark alert-dismissible fade show" role="alert">
                    <h6>{{ session('msg') }}</h6>
                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                @if(Session::has('error'))
                <div class="alert alert-danger dark alert-dismissible fade show" role="alert">
                    {!! session('error') !!}
                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                @if (count($errors) > 0)
                <div class="alert alert-danger dark alert-dismissible fade show" role="alert">
                    <h6>Upload image is ERROR</h6>
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                <div class="card-body">
                    @if ($data['status'] == 'update')
                    <h5>Catatan Penolakan</h5>
                    <div class="row mb-3">
                        <div class="col-sm-12">
                            <textarea class="form-control" rows="4">{{ $catatan }}</textarea>
                        </div>
                    </div>
                    <hr />
                    @endif
                    <h5>Pelaksana Perjalanan Dinas</h5>
                    @php
                    $pengaju_property = json_decode($data['pengaju_property'], true);
                    $atasan_property = json_decode($data['atasan_property'], true);
                    @endphp
                    <div class="row mb-3">
                        <div class="col-sm-6">
                            <label class="form-label">Nama / NIP Karyawan</label>
                            <input class="form-control" value="{{ $pengaju_property['nama'] }} / {{ $pengaju_property['nik'] }}">
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label">Nama / NIP Atasan Langsung</label>
                            <input class="form-control" value="{{ $atasan_property['nama'] }} / {{ $atasan_property['nik'] }}">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-6">
                            <label class="form-label">Level</label>
                            <input class="form-control" value="{{ $pengaju_property['id_level'] }} - {{ $pengaju_property['level'] }}">
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label">Jabatan</label>
                            <input class="form-control" value="{{ $pengaju_property['jabatan'] }}">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-6">
                            <label class="form-label">Perusahaan</label>
                            <input class="form-control" value="{{ $pengaju_property['perusahaan'] }}">
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label">Direktorat</label>
                            <input class="form-control" value="{{ $pengaju_property['divisi'] }}">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-6">
                            <label class="form-label">Divisi</label>
                            <input class="form-control" value="{{ $pengaju_property['dep'] }}">
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label">Departemen</label>
                            <input class="form-control" value="{{ $pengaju_property['sub_dep'] }}">
                        </div>
                    </div>
                    <hr />
                    <h5>Informasi Perjalanan Dinas</h5>
                    <div class="row mb-3">
                        <div class="col-sm-4">
                            <label class="form-label">Tahun Pengajuan</label>
                            <input class="form-control" value="{{ $data['periode'] }}">
                        </div>
                        <div class="col-sm-4">
                            <label class="form-label">Golongan</label>
                            <input class="form-control" value="@if($data['is_leader']==1) Leader @elseif($data['is_leader']==2) Member @elseif($data['is_leader']==3) Management @endif">
                        </div>
                        <div class="col-sm-4">
                            <label class="form-label">Kelompok Perjalanan Dinas</label>
                            <input class="form-control" value="Project: {{ $data['kelompok']['project'] }} / Corporate: {{ $data['kelompok']['nonproject'] }}">
                        </div>
                    </div>
                    <form action="{{ route('bpd.store') }}" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return confirm('Are you sure want to submit this data?');">
                        @csrf
                        <input type="hidden" id="new" name="new" value="{{ $data['luar_dalam'] }}">
                        <input type="hidden" id="mgt" name="mgt" value="{{ $data['mgt'] }}">
                        <input type="hidden" id="status" name="status" value="{{ $data['status'] }}">
                        @if ($data['status'] == 'update')
                        <input type="hidden" id="id_trx" name="id_trx" value="{{ Crypt::encrypt($trxMaster->id) }}">
                        @endif
                        <!-- <hr /> -->
                        <div class="row mb-3">
                            <div class="table-responsive">
                                <table class="table" id="table_info">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Tujuan</th>
                                            @if ($data['luar_dalam'] == 'ca_dalam')
                                            <th scope="col">&nbsp;</th>
                                            @endif
                                            <th scope="col">Tanggal dari</th>
                                            <th scope="col">Tanggal sampai</th>
                                            <th scope="col">&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($data['status'] == 'update')
                                        @foreach ($trxDetailInfo as $_data)
                                        <tr>
                                            <td>#</td>
                                            @if($trxMaster->luar_dalam=='ca_dalam')
                                            <td>{{ $_data->_tujuan1_dalam->propinsi }} - {{ $_data->_tujuan2_dalam->kota }} ({{ \Carbon\Carbon::parse($_data->tanggal_mulai)->diffInDays(\Carbon\Carbon::parse($_data->tanggal_akhir))+1 }} Hari)</td>
                                            <td>&nbsp;</td>
                                            @elseif($trxMaster->luar_dalam=='ca_luar')
                                            <td>{{ $_data->_tujuan1_luar->negara }} ({{ \Carbon\Carbon::parse($_data->tanggal_mulai)->diffInDays(\Carbon\Carbon::parse($_data->tanggal_akhir))+1 }} Hari)</td>
                                            @endif
                                            <td>{{ \Carbon\Carbon::parse($_data->tanggal_mulai)->format('d-m-Y') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($_data->tanggal_akhir)->format('d-m-Y') }}</td>
                                            <td><a onclick="return confirm('Are you sure want to delete this data?')" href="{{ route('bpd.delete_info', [Crypt::encrypt($trxMaster->id), 'id_data'=>$_data->id, 'view'=>'reject_pa']) }}" class="btn btn-danger"><i class="fa fa-trash"></i></a></td>
                                        </tr>
                                        @endforeach
                                        @else
                                        <tr>
                                            <th scrope="row">1</th>
                                            @if ($data['luar_dalam'] == 'ca_dalam')
                                            <td>
                                                <select onchange="getData(1);" class="form-select js-example-basic-single col-sm-12" id="tujuan1_1" name="tujuan1_1" required="">
                                                    <option selected="" disabled="" value="">Pilih...</option>
                                                    @foreach ($area as $_data)
                                                    <option value="{{ $_data->id }}">{{ $_data->propinsi }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="invalid-feedback txt-danger">Field is required.</div>
                                            </td>
                                            <td>
                                                <select class="form-select js-example-basic-single col-sm-12" id="tujuan2_1" name="tujuan2_1" required=""></select>
                                                <div class="invalid-feedback txt-danger">Field is required.</div>
                                            </td>
                                            @elseif ($data['luar_dalam'] == 'ca_luar')
                                            <td>
                                                <select class="form-select js-example-basic-single col-sm-12" id="tujuan1_1" name="tujuan1_1" required="">
                                                    <option selected="" disabled="" value="">Pilih...</option>
                                                    @foreach ($negara as $_data)
                                                    <option value="{{ $_data->id }}">{{ $_data->negara }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="invalid-feedback txt-danger">Field is required.</div>
                                            </td>
                                            @endif
                                            <td><input class="form-control" type="date" name="tanggal_mulai[]" id="tanggal_mulai[]" placeholder="Tanggal dari" required=""></td>
                                            <td><input class="form-control" type="date" name="tanggal_akhir[]" id="tanggal_akhir[]" placeholder="Tanggal sampai" required=""></td>
                                            <td>&nbsp;</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="mb-3 col-md-2">
                            <a class="btn btn-primary" id="insertRowinfo" href="#">Add new</a>
                        </div>
                        <hr />
                        @if ($data['is_leader']<>2)
                        <h5>Biaya Perjalanan Dinas</h5>
                        <div class="row mb-3">
                            <div class="table-responsive">
                                <table class="table" id="table_biaya">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Biaya</th>
                                            <th scope="col">Satuan</th>
                                            <th scope="col">Nominal</th>
                                            <th scope="col">&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($data['status'] == 'update')
                                        @foreach ($trxDetailBiaya as $_data)
                                        <tr>
                                            <td>#</td>
                                            <td>{{ $_data->biaya }}</td>
                                            <td>{{ $_data->satuan }}</td>
                                            <td>{{ number_format($_data->nominal, 0) }}</td>
                                            <td><a onclick="return confirm('Are you sure want to delete this data?')" href="{{ route('bpd.delete_biaya', [Crypt::encrypt($trxMaster->id), 'id_data'=>$_data->id, 'view'=>'reject_pa']) }}" class="btn btn-danger"><i class="fa fa-trash"></i></a></td>
                                        </tr>
                                        @endforeach
                                        @endif
                                    </tbody>
                                    <!-- <tbody>
                                        <tr>
                                            <th scrope="row">1</th>
                                            <td><input class="form-control" type="text" name="biaya[]" id="biaya[]" placeholder="Biaya" required=""></td>
                                            <td>
                                                <select class="form-select col-sm-12" id="satuan[]" name="satuan[]" required="">
                                                    <option selected="" disabled="" value="">Pilih...</option>
                                                    <option value="IDR">IDR</option>
                                                    <option value="USD">USD</option>
                                                </select>
                                            </td>
                                            <td><input onfocus="use_number(this)" onblur="use_text(this)" min=1 type="number" name="nominal_1" id="nominal_1" class="form-control numbers" required=""></td>
                                            <td>&nbsp;</td>
                                        </tr>
                                    </tbody> -->
                                </table>
                            </div>
                        </div>
                        <div class="mb-3 col-md-2">
                            <a class="btn btn-primary" id="insertRowbiaya" href="#">Add new</a>
                        </div>
                        <hr />
                        @endif
                        <div class="row mb-3">
                            <div class="col-sm-6">
                                <label class="form-label">Uraian Tugas Perjalanan Dinas</label>
                                <textarea class="form-control" id="uraian" name="uraian" rows="2" required="">@if ($data['status'] == 'update') {{$trxMaster->uraian}} @endif</textarea>
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label">Lampiran<br/>*Maksimal 2MB dengan format pdf atau gambar(jpg, jpeg dan png)</label>
                                <input name="lampiran" id="lampiran" class="form-control" type="file" required="">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <button class="btn btn-success" type="submit">Submit</button> | <a href="{{ route('bpd.index') }}" class="btn btn-warning" type="button">Back</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('assets/js/form-validation-custom.js') }}"></script>
<script src="{{asset('assets/js/sweet-alert/sweetalert.min.js')}}"></script>
<script src="{{asset('assets/js/sweet-alert/app.js')}}"></script>
<script src="{{asset('assets/js/select2/select2.full.min.js')}}"></script>
<script src="{{asset('assets/js/select2/select2-custom.js')}}"></script>
<script type="text/javascript">
    // get data kota filter by propinsi
    function getData(_id) {
        // alert(_id);
        $('#tujuan2_' + _id).find('option').remove();
        $("#tujuan2_" + _id).append('<option selected="" disabled="" value="">Pilih...</option>');
        var id = $("#tujuan1_" + _id).val();
        $.ajax({
            // url: _url + '/matta/kebutuhan/kota/' + id,
            url: "{{ route('kebutuhan_kota') }}" + "/" + id,
            type: 'get',
            dataType: 'json',
            success: function (response) {
                // alert(response);
                var len = 0;
                if (response['kota'] != null) {
                    len = response['kota'].length;
                }
                if (len > 0) {
                    // Read data and create <option >
                    for (var i = 0; i < len; i++) {
                        var id = response['kota'][i].id;
                        var kota = response['kota'][i].kota;
                        var option = "<option value='" + id + "'>" + kota + "</option>";
                        $("#tujuan2_" + _id).append(option);
                    }
                }
            }
        });
    }
    // 
    $(document).ready(function () {
        // Biaya Perjalanan Dinas
        var counter_biaya = 1;
        $("#insertRowbiaya").on("click", function (event) {
            event.preventDefault();
            var newRow = $('<tr  id="tr_biaya">');
            var cols = '';
            // Table columns
            cols += '<th scrope="row">' + counter_biaya + '</th>';
            cols += '<td><input class="form-control" type="text" name="biaya[]" id="biaya[]" placeholder="Biaya" required=""></td>';
            cols += '<td><select class="form-select col-sm-12" id="satuan[]" name="satuan[]" required=""><option selected="" disabled="" value="">Pilih...</option><option value="IDR">IDR</option><option value="USD">USD</option></select></td>';
            cols += '<td><input onfocus="use_number(this)" onblur="use_text(this)" min=1 type="number" name="nominal_' + counter_biaya + '" id="nominal_' + counter_biaya + '" class="form-control numbers" required=""></td>';
            cols += '<td><button class="btn btn-danger" id ="deleteRowbiaya"><i class="fa fa-trash"></i></button></td>';
            // Insert the columns inside a row
            newRow.append(cols);
            // Insert the row inside a table
            $("#table_biaya").append(newRow);
            // Increase counter_biaya after each row insertion
            counter_biaya++;
        });
        // Remove row when delete btn is clicked
        $("#table_biaya").on("click", "#deleteRowbiaya", function (event) {
            $(this).closest("tr").remove();
            // counter_biaya -= 1;
        });
        // 
        // Informasi Perjalanan Dinas
        var counter_info = 2;
        @if ($data['status'] == 'update')
            counter_info = 1;
        @endif
        $("#insertRowinfo").on("click", function (event) {
            event.preventDefault();
            var newRow = $('<tr  id="tr_info">');
            var cols = '';
            // Table columns
            cols += '<th scrope="row">' + counter_info + '</th>';
            // ca_dalam
            @if ($data['luar_dalam'] == 'ca_dalam')
            cols += '<td><select onchange="getData(' + counter_info + ');" class="form-select js-example-basic-single col-sm-12" id="tujuan1_' + counter_info + '" name="tujuan1_' + counter_info + '" required=""><option selected="" disabled="" value="">Pilih...</option>@foreach ($area as $_data)<option value="{{ $_data->id }}">{{ $_data->propinsi }}</option>@endforeach</select><div class="invalid-feedback txt-danger">Field is required.</div></td>';
            cols += '<td><select class="form-select js-example-basic-single col-sm-12" id="tujuan2_' + counter_info + '" name="tujuan2_' + counter_info + '" required=""></select><div class="invalid-feedback txt-danger">Field is required.</div></td>';
            // ca_luar
            @elseif ($data['luar_dalam'] == 'ca_luar')
            cols += '<td><select class="form-select js-example-basic-single col-sm-12" id="tujuan1_' + counter_info + '" name="tujuan1_' + counter_info + '" required=""><option selected="" disabled="" value="">Pilih...</option>@foreach ($negara as $_data)<option value="{{ $_data->id }}">{{ $_data->negara }}</option> @endforeach</select><div class="invalid-feedback txt-danger">Field is required.</div></td>';
            @endif
            cols += '<td><input class="form-control" type="date" name="tanggal_mulai[]" id="tanggal_mulai[]" placeholder="Tanggal dari" required=""></td>';
            cols += '<td><input class="form-control" type="date" name="tanggal_akhir[]" id="tanggal_akhir[]" placeholder="Tanggal sampai" required=""></td>';
            cols += '<td><button class="btn btn-danger" id ="deleteRowinfo"><i class="fa fa-trash"></i></button></td>';
            // Insert the columns inside a row
            newRow.append(cols);
            // Insert the row inside a table
            $("#table_info").append(newRow);
            @if ($data['luar_dalam'] == 'ca_dalam')
            $('#tujuan1_' + counter_info).select2();
            $('#tujuan2_' + counter_info).select2();
            @elseif ($data['luar_dalam'] == 'ca_luar')
            $('#tujuan1_' + counter_info).select2();
            @endif
            // Increase counter_info after each row insertion
            counter_info++;
            // if (counter_info > 6) {
            //     alert('Maksimal hanya 5 record!');
            //     // $("tr").remove();
            //     $('table#table_info tr#tr_info').remove();
            //     counter_info = 2;
            // }
        });
        // Remove row when delete btn is clicked
        $("#table_info").on("click", "#deleteRowinfo", function (event) {
            $(this).closest("tr").remove();
            // counter_info -= 1;
        });
    });
    // 
    function use_number(node) {
        var empty_val = false;
        const value = node.value;
        if (node.value == '')
            empty_val = true;
        node.type = 'number';
        if (!empty_val)
            node.value = Number(value.replace(/,/g, '')); // or equivalent per locale
    }

    function use_text(node) {
        var empty_val = false;
        const value = Number(node.value);
        if (node.value == '')
            empty_val = true;
        node.type = 'text';
        if (!empty_val)
            node.value = value.toLocaleString('en'); // or other formatting
    }

</script>
@endsection
