<!DOCTYPE html>
<html lang="en">

<head>
    <title>Formulir Evaluasi Pelatihan</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('assets/images/favicon.png') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <style>
        table tr td {
            vertical-align: top;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <form action="{{ route('pelatihan.update', (new App\Http\Controllers\OpenDataController)->encryptWithKey($_trx->id)) }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
        @method('PATCH')
        @csrf
        <input type="hidden" id="type" name="type" value="evaluasi">
        <div class="col-md-12 mt-2">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-6 text-start">
                            <h4><img src="{{ asset('assets/images/matta/form-icon-25.jpg') }}" height="30" alt="Alita"/>&nbsp;Formulir Evaluasi Pelatihan</h4>
                        </div>
                        {{-- <div class="col-md-6 text-end">
                            <img height="50" src="{{ asset('assets/images/matta/Alita-New-Log.png') }}" alt="Alita">
                        </div> --}}
                    </div>
                </div>
                <hr class="m-2" />
                @php
                    $data = $materi->firstWhere('urutan', 1);
                    $nik_property = json_decode($_trx->nik_property, true);
                @endphp
                <div class="card-body">
                    <h5 class="ms-1">{{ $data->label}}. {{ $data->materi1}}</h5>
                    {{-- <table class="table"> --}}
                    {{-- <table class="table table-borderless"> --}}
                    <table width="100%" border="0" cellspacing="0" cellpadding="2">
                        <tr>
                            <th width="30%">Keterangan</th>
                            <th width="1%">&nbsp;</th>
                            <th width="69%">Isi</th>
                        </tr>
                        <tr>
                            <td>Nama Peserta</td>
                            <td>:</td>
                            <td>{{ $nik_property['nama'] }}</td>
                        </tr>
                        <tr>
                            <td>NIP</td>
                            <td>:</td>
                            <td>{{ $nik_property['nik'] }}</td>
                        </tr>
                        <tr>
                            <td>Jabatan</td>
                            <td>:</td>
                            <td>{{ $nik_property['jabatan'] }}</td>
                        </tr>
                        <tr>
                            <td>Direktorat / Divisi / Departemen</td>
                            <td>:</td>
                            <td>{{ $nik_property['divisi'] }} / {{ $nik_property['dep'] }} / {{ $nik_property['sub_dep'] }}</td>
                        </tr>
                        <tr>
                            <td>Jenis Pelatihan</td>
                            <td>:</td>
                            <td>@foreach($arrJenis as $value){{ $value->jenis }} @if(!$loop->last) <br/> @endif @endforeach</td>
                        </tr>
                        @if(!empty($trx->int_ext))
                        <tr>
                            <td>Mode Pelatihan</td>
                            <td>:</td>
                            <td>
                                @foreach($int_ext as $key => $value)
                                @if($key == $trx->int_ext) {{ $value }} @endif
                                @endforeach
                            </td>
                        </tr>
                        @endif
                        <tr>
                            <td>Judul Pelatihan</td>
                            <td>:</td>
                            <td>{{ $trx->judul }}</td>
                        </tr>
                        <tr>
                            <td>Penyelenggara</td>
                            <td>:</td>
                            <td>{{ $trx->penyelenggara }}</td>
                        </tr>
                        @php
                        // $tanggal_range = [];
                        // if(!empty($trx->tanggal_range)){
                            $tanggal_range = json_decode($trx->tanggal_range, true);
                            $formatted_ranges = array_map(function($range) {
                                [$mulai, $selesai] = array_map('trim', explode('s/d', $range));
                                return Carbon\Carbon::createFromFormat('Y-m-d', $mulai)->format('d-m-Y') . ' s/d ' . Carbon\Carbon::createFromFormat('Y-m-d', $selesai)->format('d-m-Y');
                            }, $tanggal_range);
                        // }
                        @endphp
                        <tr>
                            <td>Tanggal Pelatihan</td>
                            <td>:</td>
                            <td>@foreach($formatted_ranges as $_tanggal_range){{ $_tanggal_range }} @if(!$loop->last) <br/> @endif @endforeach</td>
                        </tr>
                    </table>
                </div>
                <hr class="m-2" />
                @php
                    $data = $materi->firstWhere('urutan', 2);
                    $_materi = $materi->where('label', 'B')->sortBy('nomor')->values();
                    $options = [
                        4 => 'Sangat Baik',
                        3 => 'Baik',
                        2 => 'Cukup',
                        1 => 'Kurang',
                    ];
                @endphp
                <div class="card-body">
                    <h5>{{ $data->label}}. {{ $data->materi1}}</h5>
                    <table width="100%" border="0" cellspacing="0" cellpadding="2">
                        <tr>
                            <th width="2%">No</th>
                            <th width="29%">Pernyataan</th>
                            <th width="69%">Jawaban</th>
                        </tr>
                        @foreach($_materi as $_data)
                        <tr>
                            <td>{{ $_data->nomor }}</td>
                            <td>{{ $_data->materi2 }}</td>
                            <td>
                                @foreach ($options as $val => $text)
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="jawaban_{{ $_data->id }}" id="jawaban_{{ $_data->id }}" value="{{ $val }}" required>
                                        <label class="form-check-label" for="jawaban_{{ $_data->id }}">{{ $text }}</label>
                                    </div>
                                @endforeach
                            </td>
                        </tr>
                        @endforeach
                    </table>
                </div>
                <hr class="m-2" />
                @php
                    $data = $materi->firstWhere('urutan', 8);
                    $_materi = $materi->where('label', 'C')->sortBy('nomor')->values();
                @endphp
                <div class="card-body">
                    <h5>{{ $data->label}}. {{ $data->materi1}}</h5>
                    <table width="100%" border="0" cellspacing="0" cellpadding="2">
                        <tr>
                            <th width="2%">No</th>
                            <th width="29%">Pernyataan</th>
                            <th width="69%">Jawaban</th>
                        </tr>
                        @foreach($_materi as $_data)
                        <tr>
                            <td>{{ $_data->nomor }}</td>
                            <td>{{ $_data->materi2 }}</td>
                            <td>
                                @foreach ($options as $val => $text)
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="jawaban_{{ $_data->id }}" id="jawaban_{{ $_data->id }}" value="{{ $val }}" required>
                                        <label class="form-check-label" for="jawaban_{{ $_data->id }}">{{ $text }}</label>
                                    </div>
                                @endforeach
                            </td>
                        </tr>
                        @endforeach
                    </table>
                </div>
                <hr class="m-2" />
                @php
                    $data = $materi->firstWhere('urutan', 13);
                    $_materi = $materi->where('label', 'D')->sortBy('nomor')->values();
                @endphp
                <div class="card-body">
                    <h5>{{ $data->label}}. {{ $data->materi1}}</h5>
                    <table width="100%" border="0" cellspacing="0" cellpadding="2">
                        <tr>
                            <th width="2%">No</th>
                            <th width="29%">Pernyataan</th>
                            <th width="69%">Jawaban</th>
                        </tr>
                        @foreach($_materi as $_data)
                        <tr>
                            <td>{{ $_data->nomor }}</td>
                            <td>{{ $_data->materi2 }}</td>
                            <td>
                                @foreach ($options as $val => $text)
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="jawaban_{{ $_data->id }}" id="jawaban_{{ $_data->id }}" value="{{ $val }}" required>
                                        <label class="form-check-label" for="jawaban_{{ $_data->id }}">{{ $text }}</label>
                                    </div>
                                @endforeach
                            </td>
                        </tr>
                        @endforeach
                    </table>
                </div>
                <hr class="m-2" />
                @php
                    $data = $materi->firstWhere('urutan', 18);
                    $_materi = $materi->where('label', 'E')->sortBy('nomor')->values();
                @endphp
                <div class="card-body">
                    <h5>{{ $data->label}}. {{ $data->materi1}}</h5>
                    <table width="100%" border="0" cellspacing="0" cellpadding="2">
                        <tr>
                            <th width="2%">No</th>
                            <th width="29%">Pernyataan</th>
                            <th width="69%">Jawaban</th>
                        </tr>
                        @foreach($_materi as $_data)
                        <tr>
                            <td>{{ $_data->nomor }}</td>
                            <td>{{ $_data->materi2 }}</td>
                            <td>
                                @foreach ($options as $val => $text)
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="jawaban_{{ $_data->id }}" id="jawaban_{{ $_data->id }}" value="{{ $val }}" required>
                                        <label class="form-check-label" for="jawaban_{{ $_data->id }}">{{ $text }}</label>
                                    </div>
                                @endforeach
                            </td>
                        </tr>
                        @endforeach
                    </table>
                </div>
                <hr class="m-2" />
                @php
                    $data = $materi->firstWhere('urutan', 22);
                    $_materi = $materi->where('label', 'F')->sortBy('nomor')->values();
                @endphp
                <div class="card-body">
                    <h5>{{ $data->label}}. {{ $data->materi1}}</h5>
                    @foreach($_materi as $_data)
                    <label class="form-label" for="jawaban_{{ $_data->id }}">{{ $_data->nomor }}. {{ $_data->materi2 }}</label>
                    <textarea class="form-control mb-2" id="jawaban_{{ $_data->id }}" name="jawaban_{{ $_data->id }}" rows="4" required></textarea>
                    @endforeach
                </div>
                <hr class="m-2" />
                @php
                    $data = $materi->firstWhere('urutan', 25);
                    $_materi = $materi->where('label', 'G')->sortBy('nomor')->values();
                @endphp
                <div class="card-body">
                    <h5>{{ $data->label}}. {{ $data->materi1}}</h5>
                    {{-- <span>{{ $_materi[0]->materi2 }}</span>
                    @foreach($_materi as $_data)
                        @foreach ($options as $val => $text)
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="jawaban_{{ $_data->id }}" id="jawaban_{{ $_data->id }}" value="{{ $val }}" required>
                                <label class="form-check-label" for="jawaban_{{ $_data->id }}">{{ $text }}</label>
                            </div>
                        @endforeach
                    @endforeach --}}
                    <table width="100%" border="0" cellspacing="0" cellpadding="2">
                        @foreach($_materi as $_data)
                        <tr>
                            <td width="31%">{{ $_data->materi2 }}</td>
                            <td width="69%">
                                @foreach ($options as $val => $text)
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="jawaban_{{ $_data->id }}" id="jawaban_{{ $_data->id }}" value="{{ $val }}" required>
                                        <label class="form-check-label" for="jawaban_{{ $_data->id }}">{{ $text }}</label>
                                    </div>
                                @endforeach
                            </td>
                        </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-12 mt-2 mb-2">
            {{-- <div class="card"> --}}
                <div class="card-body">
                    <button class="btn btn-primary" type="submit">Submit</button>
                </div>
            {{-- </div> --}}
        </div>
        </form>
        <div class="col-md-12 mt-2 mb-2">
            {{-- <div class="card"> --}}
                <div class="card-body text-center">
                    <h6>Terima kasih untuk partisipasi dan kerjasamanya dalam pengisian Formulir Evaluasi Pelatihan ini.</h6>
                    <a href="https://alita.id/"><img src="{{ asset('assets/images/matta/Alita-New-Log.png') }}" height="40" alt="Alita" /></a><br/>
                    {{-- <a href="https://alita.id/" target="_blank"></a> --}}
                    <h6>&reg;PT Alita Praya Mitra - {{ \Carbon\Carbon::now()->year }}</h6>
                </div>
            {{-- </div> --}}
        </div>
    </div>
</div>
</body>
<script type="text/javascript">
// 
</script>
</html>
