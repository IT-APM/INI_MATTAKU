@extends('layouts.simple.master')
@section('title', 'Formulir Karyawan Non Aktif')
@section('content')
{{-- start di --}}
<div class="modal fade" id="modal_edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="{{ route('nonaktif.save_edit', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($trxD->id), 'back_url' => $back_url]) }}"
                method="POST" enctype="multipart/form-data"
                onsubmit="return confirm('Are you sure want to submit this data?');">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel2">Edit</h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                @if($trxD->_into_ == 'di')
                                {{-- start di --}}
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>
                                                <h6>Kepada (PIC Handover)</h6>
                                            </th>
                                            <th>
                                                <h6>Obyek</h6>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <select class="form-select" id="kepada" name="kepada" required="">
                                                    <option selected="" disabled="" value="">Pilih...</option>
                                                    @foreach ($karyawan as $data)
                                                    <option value="{{ $data->nik }}" @if($trxD->kepada == $data->nik) selected @endif>{{ $data->nama }} - {{ $data->nik }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <select class="form-select" id="obyek" name="obyek" required="">
                                                    <option selected="" disabled="" value="">Pilih...</option>
                                                    @foreach ($obyek_di as $data)
                                                    <option value="{{ $data->id }}" @if($trxD->obyek == $data->id) selected @endif>{{ $data->obyek }}</option>
                                                    @endforeach
                                                </select>
                                                <br />
                                                <label class="form-label" for="keterangan">Keterangan</label>
                                                <textarea class="form-control" id="keterangan" name="keterangan" rows="4">{{ $trxD->keterangan }}</textarea>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                {{-- end di --}}
                                @elseif($trxD->_into_ == 'ga')
                                {{-- start ga --}}
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>
                                                <h6>Kepada</h6>
                                            </th>
                                            <th>
                                                <h6>Obyek</h6>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <select class="form-select" id="kepada" name="kepada" required="">
                                                    <option selected="" disabled="" value="">Pilih...</option>
                                                    @foreach ($pic_ga as $data)
                                                    <option value="{{ $data->nik }}" @if($trxD->kepada == $data->nik) selected @endif>{{ $data->nama }} - {{ $data->nik }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <select class="form-select" id="obyek" name="obyek" required="">
                                                    <option selected="" disabled="" value="">Pilih...</option>
                                                    @foreach ($obyek_ga as $data)
                                                    <option value="{{ $data->id }}" @if($trxD->obyek == $data->id) selected @endif>{{ $data->obyek }}</option>
                                                    @endforeach
                                                </select>
                                                <br />
                                                <label class="form-label" for="keterangan">Keterangan</label>
                                                <textarea class="form-control" id="keterangan" name="keterangan" rows="4">{{ $trxD->keterangan }}</textarea>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                {{-- end ga --}}
                                @elseif($trxD->_into_ == 'it')
                                {{-- start it --}}
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>
                                                <h6>Kepada</h6>
                                            </th>
                                            <th>
                                                <h6>Obyek</h6>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <select class="form-select" id="kepada" name="kepada" required="">
                                                    <option selected="" disabled="" value="">Pilih...</option>
                                                    @foreach ($pic_it as $data)
                                                    <option value="{{ $data->nik }}" @if($trxD->kepada == $data->nik) selected @endif>{{ $data->nama }} - {{ $data->nik }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <select class="form-select" id="obyek" name="obyek" required="">
                                                    <option selected="" disabled="" value="">Pilih...</option>
                                                    @foreach ($obyek_it as $data)
                                                    <option value="{{ $data->id }}" @if($trxD->obyek == $data->id) selected @endif>{{ $data->obyek }}</option>
                                                    @endforeach
                                                </select>
                                                <br />
                                                <label class="form-label" for="keterangan">Keterangan</label>
                                                <textarea class="form-control" id="keterangan" name="keterangan" rows="4">{{ $trxD->keterangan }}</textarea>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                {{-- end it --}}
                                @elseif($trxD->_into_ == 'hc')
                                {{-- start hc --}}
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>
                                                <h6>Kepada</h6>
                                            </th>
                                            <th>
                                                <h6>Obyek</h6>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <select class="form-select" id="kepada" name="kepada" required="">
                                                    <option selected="" disabled="" value="">Pilih...</option>
                                                    @foreach ($pic_hc as $data)
                                                    <option value="{{ $data->nik }}" @if($trxD->kepada == $data->nik) selected @endif>{{ $data->nama }} - {{ $data->nik }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <select class="form-select" id="obyek" name="obyek" required="">
                                                    <option selected="" disabled="" value="">Pilih...</option>
                                                    @foreach ($obyek_hc as $data)
                                                    <option value="{{ $data->id }}" @if($trxD->obyek == $data->id) selected @endif>{{ $data->obyek }}</option>
                                                    @endforeach
                                                </select>
                                                <br />
                                                <label class="form-label" for="keterangan">Keterangan</label>
                                                <textarea class="form-control" id="keterangan" name="keterangan" rows="4">{{ $trxD->keterangan }}</textarea>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                {{-- end hc --}}
                                @elseif($trxD->_into_ == 'ko')
                                {{-- start ko --}}
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>
                                                <h6>Kepada</h6>
                                            </th>
                                            <th>
                                                <h6>Obyek</h6>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <select class="form-select" id="kepada" name="kepada" required="">
                                                    <option selected="" disabled="" value="">Pilih...</option>
                                                    @foreach ($pic_ko as $data)
                                                    <option value="{{ $data->nik }}" @if($trxD->kepada == $data->nik) selected @endif>{{ $data->nama }} - {{ $data->nik }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <select class="form-select" id="obyek" name="obyek" required="">
                                                    <option selected="" disabled="" value="">Pilih...</option>
                                                    @foreach ($obyek_ko as $data)
                                                    <option value="{{ $data->id }}" @if($trxD->obyek == $data->id) selected @endif>{{ $data->obyek }}</option>
                                                    @endforeach
                                                </select>
                                                <br />
                                                <label class="form-label" for="keterangan">Keterangan</label>
                                                <textarea class="form-control" id="keterangan" name="keterangan" rows="4">{{ $trxD->keterangan }}</textarea>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                {{-- end ko --}}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a class="btn btn-secondary" href="{{ $back_url }}">Cancel</a>
                    <button class="btn btn-primary" type="submit">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- end di --}}
@endsection
@section('script')
<script type="text/javascript">
    $(document).ready(function () {
        $("#modal_edit").modal('show');
    });
</script>
@endsection
