<!DOCTYPE html>
<html>

<style>
.center {
  line-height: 55px;
  text-align: center;
}
</style>
<head>
    <title>Matta Reborn</title>
</head>

<!-- start -->

<body>
    <table width="100%" border="1" cellspacing="0" cellpadding="0">
        <tr>
            <td><table width="100%" border="1" cellspacing="0" cellpadding="2">
                    <tr>
                        <td width="25%">&nbsp;<img src="{{ public_path('assets/images/matta/alita_new.png') }}" alt="Logo Alita"></td>
                        <td width="50%"><div class="center" style="font-weight: bold; font-size: 12px">FORM EVALUASI PELATIHAN</div></td>
                        <td width="25%"><div class="center">No: {{ $trx->id }}</div></td>
                    </tr>
                </table>
                {{-- @php
                    $data = $materi->firstWhere('urutan', 1);
                    $nik_property = json_decode($_trx->nik_property, true);
                @endphp
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td align="center"><strong>{{ $data->label}}. {{ $data->materi1}}</strong></td>
                    </tr>
                </table>
                <hr/>
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td width="30%">Nama Peserta</td>
                        <td width="2%">:</td>
                        <td width="68%">{{ $nik_property['nama'] }}</td>
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
                        <td>{{ $_int_ext }}</td>
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
                        $tanggal_range = json_decode($trx->tanggal_range, true);
                        $formatted_ranges = array_map(function($range) {
                            [$mulai, $selesai] = array_map('trim', explode('s/d', $range));
                            return Carbon\Carbon::createFromFormat('Y-m-d', $mulai)->format('d-m-Y') . ' s/d ' . Carbon\Carbon::createFromFormat('Y-m-d', $selesai)->format('d-m-Y');
                        }, $tanggal_range);
                    @endphp
                    <tr>
                        <td>Tanggal Pelatihan</td>
                        <td>:</td>
                        <td>@foreach($formatted_ranges as $_tanggal_range){{ $_tanggal_range }} @if(!$loop->last) <br/> @endif @endforeach</td>
                    </tr>
                </table>
                <hr/> --}}
                @php
                    // $data = $materi->firstWhere('urutan', 2);
                    $_materi = $materi->where('label', 'B')->sortBy('nomor')->values();
                    $options = [
                        4 => 'Sangat Baik',
                        3 => 'Baik',
                        2 => 'Cukup',
                        1 => 'Kurang',
                    ];
                @endphp
                {{-- <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td align="center"><strong>{{ $data->label}}. {{ $data->materi1}}</strong></td>
                    </tr>
                </table>
                <hr/>
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <th width="4%">No</th>
                        <th width="50%">Pernyataan</th>
                        <th width="46%">Jawaban</th>
                    </tr>
                    @foreach($_materi as $_data)
                    <tr>
                        <td>{{ $_data->nomor }}</td>
                        <td>{{ $_data->materi2 }}</td>
                        <td>
                            @foreach ($options as $val => $text)
                                <input type="radio" name="jawaban_{{ $_data->id }}" id="jawaban_{{ $_data->id }}" value="{{ $val }}" @if($val == $_data->jawaban)checked="checked"@endif>
                                <label for="jawaban_{{ $_data->id }}">{{ $text }}</label>
                            @endforeach
                        </td>
                    </tr>
                    @endforeach
                </table>
                <hr/>
                @php
                    $data = $materi->firstWhere('urutan', 8);
                    $_materi = $materi->where('label', 'C')->sortBy('nomor')->values();
                @endphp
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td align="center"><strong>{{ $data->label}}. {{ $data->materi1}}</strong></td>
                    </tr>
                </table>
                <hr/>
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <th width="4%">No</th>
                        <th width="50%">Pernyataan</th>
                        <th width="46%">Jawaban</th>
                    </tr>
                    @foreach($_materi as $_data)
                    <tr>
                        <td>{{ $_data->nomor }}</td>
                        <td>{{ $_data->materi2 }}</td>
                        <td>
                            @foreach ($options as $val => $text)
                                <input type="radio" name="jawaban_{{ $_data->id }}" id="jawaban_{{ $_data->id }}" value="{{ $val }}" @if($val == $_data->jawaban)checked="checked"@endif>
                                <label for="jawaban_{{ $_data->id }}">{{ $text }}</label>
                            @endforeach
                        </td>
                    </tr>
                    @endforeach
                </table>
                <hr/>
                @php
                    $data = $materi->firstWhere('urutan', 13);
                    $_materi = $materi->where('label', 'D')->sortBy('nomor')->values();
                @endphp
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td align="center"><strong>{{ $data->label}}. {{ $data->materi1}}</strong></td>
                    </tr>
                </table>
                <hr/>
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <th width="4%">No</th>
                        <th width="50%">Pernyataan</th>
                        <th width="46%">Jawaban</th>
                    </tr>
                    @foreach($_materi as $_data)
                    <tr>
                        <td>{{ $_data->nomor }}</td>
                        <td>{{ $_data->materi2 }}</td>
                        <td>
                            @foreach ($options as $val => $text)
                                <input type="radio" name="jawaban_{{ $_data->id }}" id="jawaban_{{ $_data->id }}" value="{{ $val }}" @if($val == $_data->jawaban)checked="checked"@endif>
                                <label for="jawaban_{{ $_data->id }}">{{ $text }}</label>
                            @endforeach
                        </td>
                    </tr>
                    @endforeach
                </table>
                <hr/> --}}
                @php
                    $data = $materi->firstWhere('urutan', 18);
                    $_materi = $materi->where('label', 'E')->sortBy('nomor')->values();
                @endphp
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td align="center"><strong>{{ $data->label}}. {{ $data->materi1}}</strong></td>
                    </tr>
                </table>
                <hr/>
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <th width="4%">No</th>
                        <th width="50%">Pernyataan</th>
                        <th width="46%">Jawaban</th>
                    </tr>
                    @foreach($_materi as $_data)
                    <tr>
                        <td>{{ $_data->nomor }}</td>
                        <td>{{ $_data->materi2 }}</td>
                        <td>
                            @foreach ($options as $val => $text)
                                <input type="radio" name="jawaban_{{ $_data->id }}" id="jawaban_{{ $_data->id }}" value="{{ $val }}" @if($val == $_data->jawaban)checked="checked"@endif>
                                <label for="jawaban_{{ $_data->id }}">{{ $text }}</label>
                            @endforeach
                        </td>
                    </tr>
                    @endforeach
                </table>
                <hr/>
                @php
                    $data = $materi->firstWhere('urutan', 22);
                    $_materi = $materi->where('label', 'F')->sortBy('nomor')->values();
                @endphp
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td align="center"><strong>{{ $data->label}}. {{ $data->materi1}}</strong></td>
                    </tr>
                </table>
                <hr/>
                @foreach($_materi as $_data)
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <th width="54%">{{ $_data->nomor }}. {{ $_data->materi2 }}</th>
                        <td width="46%">{{ $_data->jawaban }}</td>
                    </tr>
                </table>
                @endforeach
                <hr/>
                @php
                    $data = $materi->firstWhere('urutan', 25);
                    $_materi = $materi->where('label', 'G')->sortBy('nomor')->values();
                @endphp
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td align="center"><strong>{{ $data->label}}. {{ $data->materi1}}</strong></td>
                    </tr>
                </table>
                <hr/>
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    @foreach($_materi as $_data)
                    <tr>
                        <td width="54%">{{ $_data->materi2 }}</td>
                        <td width="46%">
                            @foreach ($options as $val => $text)
                                <input type="radio" name="jawaban_{{ $_data->id }}" id="jawaban_{{ $_data->id }}" value="{{ $val }}" @if($val == $_data->jawaban)checked="checked"@endif>
                                <label for="jawaban_{{ $_data->id }}">{{ $text }}</label>
                            @endforeach
                        </td>
                    </tr>
                    @endforeach
                </table>
                {{-- <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td align="center"><strong>PESERTA</strong></td>
                    </tr>
                </table>
                <table width="100%" border="1" cellspacing="0" cellpadding="2">
                    <tr>
                        <th width="30%">Nama / NIP</th>
                        <th width="45%">Direktorat / Divisi / Departemen</th>
                        <th width="25%">Jabatan</th>
                    </tr>
                    @foreach($arrPeserta as $value)
                    <tr>
                        <td>{{ $value['nama'] }} / {{ $value['nik'] }}</td>
                        <td>{{ $value['divisi'] }} / {{ $value['dep'] }} / {{ $value['sub_dep'] }}</td>
                        <td>{{ $value['jabatan'] }}</td>
                    </tr>
                    @endforeach
                </table>
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td align="center"><strong>PELATIHAN</strong></td>
                    </tr>
                </table>
                <hr/>
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td width="30%">Pengajuan Pelatihan ke</td>
                        <td width="2%">:</td>
                        <td width="68%">{{ $trx->ke }}</td>
                    </tr>
                    <tr>
                        <td>Tahun</td>
                        <td>:</td>
                        <td>{{ $trx->tahun }}</td>
                    </tr>
                    <tr>
                        <td>Jenis</td>
                        <td>:</td>
                        <td>@foreach($arrJenis as $value){{ $value->jenis }} @if(!$loop->last) <br/> @endif @endforeach</td>
                    </tr>
                    @if(!empty($trx->int_ext))
                    <tr>
                        <td>Mode</td>
                        <td>:</td>
                        <td>{{ $_int_ext }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td>Judul</td>
                        <td>:</td>
                        <td>{{ $trx->judul }}</td>
                    </tr>
                    <tr>
                        <td>Penyelenggara</td>
                        <td>:</td>
                        <td>{{ $trx->penyelenggara }}</td>
                    </tr>
                    @php
                        $tanggal_range = json_decode($trx->tanggal_range, true);
                        $formatted_ranges = array_map(function($range) {
                            [$mulai, $selesai] = array_map('trim', explode('s/d', $range));
                            return Carbon\Carbon::createFromFormat('Y-m-d', $mulai)->format('d-m-Y') . ' s/d ' . Carbon\Carbon::createFromFormat('Y-m-d', $selesai)->format('d-m-Y');
                        }, $tanggal_range);
                    @endphp
                    <tr>
                        <td>Tanggal Pelaksanaan</td>
                        <td>:</td>
                        <td>@foreach($formatted_ranges as $_tanggal_range){{ $_tanggal_range }} @if(!$loop->last) <br/> @endif @endforeach</td>
                    </tr>
                    <tr>
                        <td>Tempat</td>
                        <td>:</td>
                        <td>{{ $trx->tempat }}</td>
                    </tr>
                    <tr>
                        <td>Biaya</td>
                        <td>:</td>
                        <td>Rp. {{ number_format($trx->biaya, 0) }}</td>
                    </tr>
                    <tr>
                        <td>Benefit</td>
                        <td>:</td>
                        <td>@foreach($arrBenefit as $value){{ $value->benefit }} @if(!$loop->last) <br/> @endif @endforeach</td>
                    </tr>
                    <tr>
                        <td>Anggaran</td>
                        <td>:</td>
                        <td>{{ $trx->_anggaran->anggaran }}</td>
                    </tr>
                    <tr>
                        <td>Keterangan</td>
                        <td>:</td>
                        <td>{!! $trx->keterangan !!}</td>
                    </tr>
                </table>
                <hr/>
                @php
                $log = App\Models\TrxPelatihanLog::where(['id_trx' => $trx->id])->whereNotIn('id_status', [1])->orderBy('id_trx', 'desc')->get();
                @endphp
                @if(!empty($log))
                @foreach($log as $_log)
                @if(!empty($_log->keterangan))
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td width="30%">Catatan</td>
                        <td width="2%">:</td>
                        <td width="68%">{{ App\Models\Karyawan::where(['nik'=>$_log->nik_pic])->first()->nama }} / {{ $_log->nik_pic }}<br/>{{ $_log->keterangan }}</td>
                    </tr>
                </table>
                <hr/>
                @endif
                @endforeach
                @endif
                {!! $htmlLog !!} --}}
            </td>
        </tr>
    </table>
</body>
<!-- finish -->

</html>