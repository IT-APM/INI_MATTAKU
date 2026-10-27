@extends('layouts.errors.master')
@section('title', 'Formulir Kebutuhan Karyawan')

@section('css')
@endsection

@section('style')
@endsection


@section('content')
<div class="container-fluid">
    <div class="row">
        <!--  -->
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h4><img src="{{ asset('assets/images/matta/form-icon-25.jpg') }}" height="30"
                                alt="Alita" />&nbsp;Formulir Kebutuhan Karyawan</h4>
                    </div>
                </div>
            </div>
        </div>
        <!--  -->
        <div class="row">
            <div class="col-sm-4">
                <div class="card">
                    <div class="card-header">
                        <h5>Karyawan Pengaju</h5>
                    </div>
                    <div class="card-header">
                        <a href="{{ asset('assets/images/matta/karyawan/'.$pengaju['nik'].'/'.$pengaju['nik'].'.jpg') }}"
                            target="_blank"><img class="img-thumbnail" width="20%"
                                src="{{ asset('assets/images/matta/karyawan/'.$pengaju['nik'].'/'.$pengaju['nik'].'.jpg') }}"
                                alt="user"></a>
                    </div>
                    <div class="card-body typography">
                        <table class="table table-striped">
                            <tbody>
                                <tr>
                                    <td width="40%">Nama</td>
                                    <td width="60%">{{ $pengaju['nama'] }}</td>
                                </tr>
                                <tr>
                                    <td>NIP</td>
                                    <td>{{ $pengaju['nik'] }}</td>
                                </tr>
                                <tr>
                                    <td>Jabatan</td>
                                    <td>{{ $pengaju['jabatan'] }}</td>
                                </tr>
                                <tr>
                                    <td>Direktorat / Divisi / Departemen</td>
                                    <td>{{ $pengaju['divisi'] . '/' . $pengaju['dep'] . '/' . $pengaju['sub_dep'] }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!--  -->
            <div class="col-sm-4">
                <div class="card">
                    <div class="card-header">
                        <h5>Data Pengajuan</h5>
                    </div>
                    <div class="card-body typography">
                        <table class="table table-striped">
                            <tbody>
                                <tr>
                                    <td width="40%">Nama Jabatan</td>
                                    <td width="60%">{{ $trxM->_pekerjaan->pekerjaan }}</td>
                                </tr>
                                <tr>
                                    <td>Tugas</td>
                                    <td>{!! nl2br($trxM->tugas) !!}</td>
                                </tr>
                                <tr>
                                    <td>Wewenang</td>
                                    <td>{!! nl2br($trxM->wewenang) !!}</td>
                                </tr>
                                <tr>
                                    <td>Tanggung Jawab</td>
                                    <td>{!! nl2br($trxM->tanggung_jawab) !!}</td>
                                </tr>
                                <tr>
                                    <td>Level</td>
                                    <td>{{ $trxM->_level->level }}</td>
                                </tr>
                                <tr>
                                    <td>Status Kerja</td>
                                    <td>{{ $trxM->_status_kerja->status_kerja }}</td>
                                </tr>
                                @if(!empty($trxM->waktu_kontrak))
                                <tr>
                                    <td>Waktu Kontrak</td>
                                    <td>{{ $trxM->waktu_kontrak }} (Bulan)</td>
                                </tr>
                                @endif
                                <tr>
                                    <td>Jumlah</td>
                                    <td>{{ $trxM->jumlah }} (Orang)</td>
                                </tr>
                                <tr>
                                    <td>Jenis Kelamin</td>
                                    <td>{{ $trxM->_kelamin->kelamin }}</td>
                                </tr>
                                <tr>
                                    <td>Area Kerja</td>
                                    <td>{{ $trxM->_area->propinsi }}</td>
                                </tr>
                                @if(!empty($trxM->id_kota))
                                <tr>
                                    <td>Area Kerja Kota</td>
                                    <td>{{ $trxM->_kota->kota }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td>Jenis Permintaan</td>
                                    <td>{{ $trxM->_jenis_permintaan->jenis }}</td>
                                </tr>
                                @if(!empty($trxM->nik_karyawan))
                                <tr>
                                    <td>Karyawan Penggantian</td>
                                    <td>@foreach ($arr_nik_karyawan as $value){{ $loop->iteration }}. {{ $value['nama'] }} / {{ $value['nik'] }} @if(next($arr_nik_karyawan)) <br/> @endif @endforeach</td>
                                </tr>
                                @endif
                                <tr>
                                    <td colspan="2">Apakah sudah masuk dalam rencana penambahan pegawai? @if($trxM->id_rencana == 0) Belum @else Sudah @endif</td>
                                </tr>
                                <tr>
                                    <td>Alasan Permintaan</td>
                                    <td>{{ $trxM->penjelasan }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-header">
                        <h5>FinDim / Cost Allocation</h5>
                    </div>
                    <div class="card-body typography">
                        <table class="table table-striped">
                            <tbody>
                                <tr>
                                    <td width="40%">Project</td>
                                    <td width="60%">{{ $trxM->_findim_project->project_id }} - {{ $trxM->_findim_project->project_desc }}</td>
                                </tr>
                                <tr>
                                    <td>Divisi</td>
                                    <td>{{ $trxM->_findim_divisi->divisi_id }} - {{ $trxM->_findim_divisi->divisi_desc }}</td>
                                </tr>
                                <tr>
                                    <td>Departemen</td>
                                    <td>{{ $trxM->_findim_departemen->departemen_id }} - {{ $trxM->_findim_departemen->departemen_desc }}</td>
                                </tr>
                                <tr>
                                    <td>Sub Departemen</td>
                                    <td>{{ $trxM->_findim_subdepartemen->subdepartemen_id }} - {{ $trxM->_findim_subdepartemen->subdepartemen_desc }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    {{--  --}}
                    <!--  -->
                    <div class="card-header">
                        <h5>Tanggal</h5>
                    </div>
                    <div class="card-body typography">
                        Tanggal Mulai Kerja {{ Carbon\Carbon::createFromFormat('Y-m-d', $trxM->tanggal)->format('d-m-Y') }}
                    </div>
                    <!--  -->
                    <div class="card-header">
                        <h5>Keterangan Lain</h5>
                    </div>
                    <div class="card-body typography">
                        {{ $trxM->keterangan_lain }}
                    </div>
                    <!--  -->
                </div>
            </div>
            <!--  -->
            <div class="col-sm-4">
                <div class="card">
                    <div class="card-header">
                        <h5>Kualifikasi / Spesifikasi Jabatan</h5>
                    </div>
                    <div class="card-body typography">
                        <table class="table table-striped">
                            <tbody>
                                <tr>
                                    <td width="40%">Pendidikan Minimum</td>
                                    <td width="60%">{{ $trxM->_jenjang_pendidikan->jenjang }}</td>
                                </tr>
                                @if(!empty($trxM->id_jurusan_pendidikan))
                                <tr>
                                    <td>Jurusan</td>
                                    {{-- <td>{{ $trxM->_jurusan_pendidikan->jurusan }} ({{ $trxM->_jurusan_pendidikan->_kelompok->kelompok }})</td> --}}
                                    <td>
                                    @if($trxM->id_jurusan_pendidikan <> '0')
                                        @foreach (explode(',', $trxM->id_jurusan_pendidikan) as $index => $value){{ $index+1 }}. {{ App\Models\PendidikanJurusan::find($value)->jurusan }} @if (!$loop->last) <br/> @endif @endforeach
                                    @endif
                                    </td>
                                </tr>
                                @endif
                                @if(!empty($trxM->nilai_ipk))
                                <tr>
                                    <td>Nilai IPK</td>
                                    <td>{{ $trxM->nilai_ipk }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td>Usia Minimum</td>
                                    <td>{{ $trxM->umur_minimum }} (Tahun)</td>
                                </tr>
                                <tr>
                                    <td>Usia Maksimum</td>
                                    <td>{{ $trxM->umur_maksimum }} (Tahun)</td>
                                </tr>
                                <tr>
                                    <td>Pengalaman Kerja (Tahun)</td>
                                    <td>{{ $trxM->_pengalaman->pengalaman }}</td>
                                </tr>
                                <tr>
                                    <td>Pelatihan</td>
                                    {{-- <td><ol>
                                    @if(!empty($trxM->id_pelatihan))
                                        @foreach ($pelatihan as $value)
                                        <li>{{ $value->sub_pelatihan }}</li>
                                        @endforeach
                                    @endif
                                    </ol></td> --}}
                                    <td>@if(!empty($trxM->id_pelatihan)) @foreach ($pelatihan as $index => $value){{ $index+1 }}. {{ $value->sub_pelatihan }} @if (!$loop->last) <br/> @endif @endforeach @endif</td>
                                </tr>
                                <tr>
                                    <td>Sertifikasi</td>
                                    {{-- <td><ol>
                                    @if(!empty($trxM->id_sertifikasi))
                                        @foreach ($sertifikasi as $value)
                                        <li>{{ $value->sub_sertifikasi }}</li>
                                        @endforeach
                                    @endif
                                    </ol></td> --}}
                                    <td>@if(!empty($trxM->id_sertifikasi)) @foreach ($sertifikasi as $index => $value){{ $index+1 }}. {{ $value->sub_sertifikasi }} @if (!$loop->last) <br/> @endif @endforeach @endif</td>
                                </tr>
                                <tr>
                                    <td>Keterampilan</td>
                                    <td>@if(!empty($trxM->id_keterampilan)) @foreach ($keterampilan as $index => $value){{ $index+1 }}. {{ $value->sub_keterampilan }} @if (!$loop->last) <br/> @endif @endforeach @endif</td>
                                    {{-- <td><ol>
                                    @if(!empty($trxM->id_keterampilan))
                                        @foreach ($keterampilan as $value)
                                        <li>{{ $value->sub_keterampilan }}</li>
                                        @endforeach
                                    @endif
                                    </ol></td> --}}
                                </tr>
                                <tr>
                                    <td>Kepribadian</td>
                                    <td>@if(!empty($trxM->id_kepribadian)) @foreach ($kepribadian as $index => $value){{ $index+1 }}. {{ $value->sub_kepribadian }} @if (!$loop->last) <br/> @endif @endforeach @endif</td>
                                    {{-- <td><ol>
                                    @if(!empty($trxM->id_kepribadian))
                                        @foreach ($kepribadian as $value)
                                        <li>{{ $value->sub_kepribadian }}</li>
                                        @endforeach
                                    @endif
                                    </ol></td> --}}
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-header">
                        <h5>History</h5>
                    </div>
                    <div class="card-body typography notification">
                        <ul>
                            @php
                                // 1=Requested by Requestor
                                // 2=Approved by L1
                                // 9=Approved by L2 to P&C
                                // 10=Done by P&C
                                // $disable=[1,2,9,10];
                                $disable=[1,2,9];
                                $remains = array_diff($disable, $history->pluck('id_status')->toArray());
                                rsort($remains);
                                // $nik_hco = App\Models\MtrUtility::find(37)->informasi;
                            @endphp
                            @foreach ($remains as $remain)
                            <li class="d-flex">
                                <div class="activity-dot-dark"></div>
                                <div class="ms-3">
                                    <h6 class="f-light" style="color: #adb5bd;">{{ App\Models\KebutuhanStatus::find($remain)->status_pengajuan }}</h6>
                                    {{-- @if($remain == 10)
                                    <p class="f-light" style="color: #adb5bd;">Oleh {{ App\Models\Karyawan::where('nik',$nik_hco)->first()->nama }} / {{ $nik_hco }}</p> --}}
                                    @if($remain == 9)
                                    <p class="f-light" style="color: #adb5bd;">Oleh {{ App\Models\Karyawan::where('nik',$trxM->layer2)->first()->nama }} / {{ $trxM->layer2 }}</p>
                                    @elseif($remain == 2)
                                    <p class="f-light" style="color: #adb5bd;">Oleh {{ App\Models\Karyawan::where('nik',$trxM->layer1)->first()->nama }} / {{ $trxM->layer1 }}</p>
                                    @endif
                                </div>
                            </li>
                            @endforeach
                        </ul>
                        <ul>
                            @foreach ($history as $_history)
                            <li class="d-flex">
                                @if ($loop->first)
                                <div class="activity-dot-success"></div>
                                @elseif ($loop->last)
                                <div class="activity-dot-warning"></div>
                                @else
                                <div class="activity-dot-primary"></div>
                                @endif
                                <div class="ms-3">
                                    <p class="d-flex justify-content-between mb-2"><span class="date-content light-background">{{ $_history->created_at }}</span></p>
                                    <h6>{{ $_history->_status->status_pengajuan }}<span class="dot-notification"></span></h6>
                                    <p class="f-light">Oleh {{ $_history->_pic->nama }} / {{ $_history->nik_pic }}</p>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!--  -->
        <div class="row">
            <div class="col-sm-12">
            <div class="card">
                <div class="card-body typography">
                <h5>Catatan</h5>
                <form id="myform" name="myform" method="post" enctype="multipart/form-data" action="{{ $linkAction }}" target="_self" onsubmit="return confirm('Are you sure want to submit this data?');">
                    @method('PATCH')
                    @csrf
                    <input type="hidden" id="nik" name="nik" value="{{ $nik }}">
                    <input type="hidden" id="pic" name="pic" value="{{ $pic }}">
                    <textarea class="form-control" id="catatan" name="catatan" rows="4" ></textarea>
                    <br/>
                    <button type="submit" name="status_pengajuan" value="{{ $approve }}" class="btn btn-success">Approve</button>
                    <button type="submit" name="status_pengajuan" value="{{ $reject }}" class="btn btn-danger">Reject</button>
                </form>
                </div>
            </div>
            </div>
        </div>
        <!--  -->
        <div class="row g-3">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body typography text-center">
                        <h6>Terima kasih untuk partisipasi dan kerjasamanya untuk melakukan review Formulir Kebutuhan
                            Karyawan
                            ini.</h6>
                        <a href="https://alita.id/"><img src="{{ asset('assets/images/matta/Alita-New-Log.png') }}"
                                height="40" alt="Alita" /></a><br />
                        <h6>&reg; PT Alita Praya Mitra - 2021</h6>
                    </div>
                </div>
            </div>
        </div>
        <!--  -->
    </div>
</div>
@endsection

@section('script')
@endsection