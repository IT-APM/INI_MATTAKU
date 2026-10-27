<!DOCTYPE html>
<html>
<style>
.center {
  /* display: flex;
  align-items: center; */
  text-align: right;
  vertical-align: middle;
}
.border-left {
  border-left: 0.5px thin black;
}
.border-right {
  border-right: 0.5px thin black;
}
.border-top {
    border-top: 0.5px thin black;
}
.border-bottom {
    border-bottom: 0.5px thin black;
}
.underline {
    border-bottom: 0.5px thin black;
    font-weight: bold;
    /* text-decoration: underline; */
}
.bold-text {
    font-weight: bold;
}
.align-right {
    text-align: right;
}
.double-line {
    border-top: 0.5px thin black;
    line-height: 0.1px;
    border-bottom: 0.5px thin black;
}
.jaga-jarak {
    line-height: 0.2;
}
.jaga-jarak-2 {
    line-height: 2;
}
.jaga-jarak-1 {
    line-height: 1;
}
</style>
<head>
    <title>Slip Gaji</title>
</head>
@php
$karyawan = json_decode($payroll->nik_property, true);
$bulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
@endphp
<body>
    <table class="border-top border-bottom border-left border-right" width="100%" cellspacing="0" cellpadding="0">
        {{-- header --}}
		<tr>
			<td width="2%">&nbsp;</td>
			<td width="96%"><table width="100%" cellspacing="1" cellpadding="1">
					<tr>
                        <td colspan="12" class="jaga-jarak">&nbsp;</td>
					</tr>
					<tr>
                        <td class="bold-text" colspan="6">SLIP GAJI PT. ALITA PRAYA MITRA</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td class="center" colspan="2" rowspan="8"><img width="100" src="{{ public_path('assets/images/matta/alita_new.png') }}" alt="Logo Alita"></td>
					</tr>
					<tr>
                        <td colspan="12" style="line-height: 0.5;">&nbsp;</td>
					</tr>
					<tr>
                        <td width="4%">BULAN</td>
                        <td width="1%">:</td>
                        <td width="23%" class="underline">{{ $bulan[$payroll->bulan - 1] }} {{ $payroll->tahun }}</td>
                        <td width="5%">&nbsp;</td>
                        <td width="6%">NAMA</td>
                        <td width="1%">:</td>
                        <td width="26%" class="underline">{{ $karyawan['nama'] }}</td>
                        <td width="5%">&nbsp;</td>
                        <td width="3%">NIP</td>
                        <td width="1%">:</td>
                        <td width="10%" class="underline">{{ $karyawan['nik'] }}</td>
						<td width="15%">&nbsp;</td>
					</tr>
					<tr>
                        <td colspan="12" style="line-height: 0.5;">&nbsp;</td>
					</tr>
					<tr>
                        <td>LEVEL</td>
						<td>:</td>
                        <td class="underline">{{ $karyawan['level'] }}</td>
						<td>&nbsp;</td>
                        <td>JABATAN</td>
						<td>:</td>
                        <td class="underline">{{ $karyawan['jabatan'] }}</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
                        <td colspan="12" class="jaga-jarak">&nbsp;</td>
					</tr>
					<tr>
                        <td colspan="12" class="double-line"></td>
					</tr>
				</table>
			</td>
			<td width="2%">&nbsp;</td>
		</tr>
        {{-- body --}}
        <tr>
			<td colspan="3"><table width="100%" cellspacing="0" cellpadding="0">
					<tr>
                        {{-- start --}}
                        <td width="2%">&nbsp;</td>
                        {{-- left coloumn --}}
                        <td width="47%"><table width="100%" cellspacing="1" cellpadding="1">
                                <tr class="double-line">
                                    <td width="2%">&nbsp;</td>
                                    <td width="48%">&nbsp;</td>
                                    <td width="2%">&nbsp;</td>
                                    <td width="23%">&nbsp;</td>
                                    <td width="2%">&nbsp;</td>
                                    <td width="23%">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td class="bold-text" colspan="6">A. GAJI / SALARY</td>
                                </tr>
                                <tr>
                                    <td colspan="4">A.1. GAJI POKOK</td>
                                    <td>:</td>
                                    <td class="align-right underline">{{ number_format($payroll->gapok, 0) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="6" class="jaga-jarak">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td colspan="6">A.2. TUNJANGAN TETAP</td>
                                </tr>
                                @php
                                $arr_abjad = range('a', 'z');
                                $where = ['tipe'=>'add'];
                                $arr_id = [1,2,3,4,5];
                                $add = App\Models\MtrPayrollAddsub::where($where)->whereIn('id', $arr_id)->orderBy('id', 'asc')->get();
                                $total_tunjangan = 0;
                                @endphp
                                @foreach ($add as $key => $value)
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>{{ $arr_abjad[$key] }}. {{ $value->nama }}</td>
                                    <td>:</td>
                                    @php
                                    $where = ['id_addsub'=>$value->id, 'nik'=>$payroll->nik, 'tipe'=>'add', 'bulan'=>$payroll->bulan, 'tahun'=>$payroll->tahun];
                                    $add_angka = App\Models\TrxPayrollAddsub::where($where)->first();
                                    if($_view == 'pdf_h'){
                                        $add_angka = App\Models\TrxPayrollAddsubLog::where($where)->first();
                                    }
                                    $total_tunjangan += $add_angka->angka;
                                    @endphp
                                    <td class="align-right underline">{{ number_format($add_angka->angka, 0) }}</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                </tr>
                                @endforeach
                                <tr>
                                    <td colspan="4">Total Tunjangan Tetap (A.2)</td>
                                    <td>:</td>
                                    <td class="align-right underline">{{ number_format($total_tunjangan, 0) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="6" class="jaga-jarak">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td class="bold-text" colspan="4">Gaji / Salary Yang Diterima Per Bulan</td>
                                    <td>:</td>
                                    <td class="align-right underline">{{ number_format($payroll->gapok + $total_tunjangan, 0) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="6" class="jaga-jarak">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td colspan="6" class="jaga-jarak">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td colspan="6">A.3. TUNJANGAN TIDAK TETAP</td>
                                </tr>
                                @php
                                $where = ['tipe'=>'add'];
                                $arr_id = [6,7,8,9,10,11,21];
                                $add = App\Models\MtrPayrollAddsub::where($where)->whereIn('id', $arr_id)->orderBy('id', 'asc')->get();
                                $_total_tunjangan = 0;
                                @endphp
                                {{-- Overtime --}}
                                @php
                                // overtime data bulan lalu
                                $date_from = Carbon\Carbon::createFromFormat('Y-m-d', "{$payroll->tahun}-{$payroll->bulan}-01")->subMonth()->startOfMonth()->format('Y-m-d');
                                $date_to = Carbon\Carbon::createFromFormat('Y-m-d', "{$payroll->tahun}-{$payroll->bulan}-01")->subMonth()->endOfMonth()->format('Y-m-d');
                                $where = ['ot_maker_by'=>$payroll->nik, 'ot_approver_status'=>'Approve'];
                                $overtime = App\Models\Overtime::select(DB::raw('SUM(ROUND(ot_length_rp_adj,-3)) as ot_length_rp_adj'))->where($where)->whereBetween(DB::raw('DATE(ot_start_at)'), [$date_from, $date_to])->orderBy( 'ot_start_at', 'desc' )->groupBy('ot_maker_by')->get();
                                $total_overtime = $overtime->sum('ot_length_rp_adj');
                                if($_view == 'pdf_h'){
                                    $total_overtime = $payroll->overtime;
                                }
                                $_total_tunjangan += $total_overtime;
                                @endphp
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>a. Overtime</td>
                                    <td>:</td>
                                    <td class="align-right underline">{{ number_format($total_overtime, 0) }}</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                </tr>
                                @foreach ($add as $key => $value)
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>{{ $arr_abjad[$key+1] }}. {{ $value->nama }}</td>
                                    <td>:</td>
                                    @php
                                    $where = ['id_addsub'=>$value->id, 'nik'=>$payroll->nik, 'tipe'=>'add', 'bulan'=>$payroll->bulan, 'tahun'=>$payroll->tahun];
                                    $add_angka = App\Models\TrxPayrollAddsub::where($where)->first();
                                    if($_view == 'pdf_h'){
                                        $add_angka = App\Models\TrxPayrollAddsubLog::where($where)->first();
                                    }
                                    $_total_tunjangan += $add_angka->angka;
                                    @endphp
                                    <td class="align-right underline">{{ number_format($add_angka->angka, 0) }}</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                </tr>
                                @endforeach
                                <tr>
                                    <td colspan="4">Total Tunjangan Tidak Tetap (A.3)</td>
                                    <td>:</td>
                                    <td class="align-right underline">{{ number_format($_total_tunjangan, 0) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="6" class="jaga-jarak">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td class="bold-text" colspan="4">TAKE HOME PAY (A)</td>
                                    <td>:</td>
                                    <td class="align-right underline">{{ number_format($payroll->gapok + $total_tunjangan + $_total_tunjangan, 0) }}</td>
                                </tr>
				            </table>
                        </td>
                        {{-- space --}}
                        <td width="2%">&nbsp;</td>
                        {{-- right coloumn --}}
                        <td width="47%"><table width="100%" cellspacing="1" cellpadding="1">
                                <tr class="double-line">
                                    <td width="2%">&nbsp;</td>
                                    <td width="48%">&nbsp;</td>
                                    <td width="2%">&nbsp;</td>
                                    <td width="23%">&nbsp;</td>
                                    <td width="2%">&nbsp;</td>
                                    <td width="23%">&nbsp;</td>
                                </tr>
                                {{--  --}}
                                <tr>
                                    <td colspan="6"><table class="border-left border-right border-bottom border-top" width="100%" cellspacing="1" cellpadding="1">
                                            <tr class="double-line">
                                                <td width="2%">&nbsp;</td>
                                                <td width="2%">&nbsp;</td>
                                                <td width="46%">&nbsp;</td>
                                                <td width="2%">&nbsp;</td>
                                                <td width="23%">&nbsp;</td>
                                                <td width="2%">&nbsp;</td>
                                                <td width="21%">&nbsp;</td>
                                                <td width="2%">&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td colspan="6">A.4. Tunjangan yang tidak diterimakan ke karyawan (ditanggung Perusahaan)</td>
                                                <td>&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>a. PPh 21</td>
                                                <td>:</td>
                                                <td class="align-right underline">{{ number_format($payroll->pajak, 0) }}</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                            </tr>
                                            @php
                                            $where = ['nik'=>$payroll->nik, 'bulan'=>$payroll->bulan, 'tahun'=>$payroll->tahun];
                                            $bpjskes = App\Models\TrxPayrollBpjskes::where($where)->orderBy('id_bpjskes', 'asc')->get();
                                            if($_view == 'pdf_h'){
                                                $bpjskes = App\Models\TrxPayrollBpjskesLog::where($where)->orderBy('id_bpjskes', 'asc')->get();
                                            }
                                            $potongan_bpjskes = 0;
                                            foreach ($bpjskes as $_data){
                                                // tidak digunakan, menjadi upload angka bpjsks
                                                // $angka = round($_data->angka*$_data->persen/100, 0);
                                                $angka = $_data->angka;
                                                $potongan_bpjskes += $angka;
                                            }
                                            @endphp
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>b. BPJS Kesehatan</td>
                                                <td>:</td>
                                                <td class="align-right underline">{{ number_format($potongan_bpjskes, 0) }}</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                            </tr>
                                            @php
                                            $where = ['nik'=>$payroll->nik, 'bulan'=>$payroll->bulan, 'tahun'=>$payroll->tahun];
                                            $bpjstk = App\Models\TrxPayrollBpjstk::where($where)->orderBy('id_bpjstk', 'asc')->get();
                                            if($_view == 'pdf_h'){
                                                $bpjstk = App\Models\TrxPayrollBpjstkLog::where($where)->orderBy('id_bpjstk', 'asc')->get();
                                            }
                                            $potongan_bpjstk = 0;
                                            foreach ($bpjstk as $_data){
                                                // tidak digunakan, menjadi upload angka bpjsks
                                                // $angka = round($_data->angka*$_data->persen/100, 0);
                                                $angka = $_data->angka;
                                                $potongan_bpjstk += $angka;
                                            }
                                            @endphp
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>c. BPJS Ketenagakerjaan</td>
                                                <td>:</td>
                                                <td class="align-right underline">{{ number_format($potongan_bpjstk, 0) }}</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td class="bold-text" colspan="4">Total (A.4)</td>
                                                <td>:</td>
                                                <td class="align-right underline">{{ number_format($potongan_bpjskes + $potongan_bpjstk + $payroll->pajak, 0) }}</td>
                                                <td>&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td colspan="6" class="jaga-jarak">&nbsp;</td>
                                                <td>&nbsp;</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                {{--  --}}
                                <tr>
                                    <td colspan="6" class="jaga-jarak">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td colspan="6" class="jaga-jarak">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td class="bold-text" colspan="6">B. POTONGAN</td>
                                </tr>
                                @php
                                $where = ['tipe'=>'sub'];
                                $arr_id = [13,14,15,16,17,18,19,20];
                                $add = App\Models\MtrPayrollAddsub::where($where)->whereIn('id', $arr_id)->orderBy('id', 'asc')->get();
                                $total_potongan = 0;
                                @endphp
                                @foreach ($add as $key => $value)
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>{{ $arr_abjad[$key] }}. {{ $value->nama }}</td>
                                    <td>:</td>
                                    @php
                                    $where = ['id_addsub'=>$value->id, 'nik'=>$payroll->nik, 'tipe'=>'sub', 'bulan'=>$payroll->bulan, 'tahun'=>$payroll->tahun];
                                    $add_angka = App\Models\TrxPayrollAddsub::where($where)->first();
                                    if($_view == 'pdf_h'){
                                        $add_angka = App\Models\TrxPayrollAddsubLog::where($where)->first();
                                    }
                                    $total_potongan += $add_angka->angka;
                                    @endphp
                                    <td class="align-right underline">{{ number_format($add_angka->angka, 0) }}</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                </tr>
                                @endforeach
                                <tr>
                                    <td colspan="4">Total Potongan (B)</td>
                                    <td>:</td>
                                    <td class="align-right underline">{{ number_format($total_potongan, 0) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="6" class="jaga-jarak">&nbsp;</td>
                                </tr>
                                @php $total_diterima = $payroll->gapok + $total_tunjangan + $_total_tunjangan - $total_potongan; @endphp
                                <tr>
                                    <td class="bold-text" colspan="4">TOTAL DITERIMA = (A-B)</td>
                                    <td>:</td>
                                    <td class="align-right underline">{{ number_format($total_diterima, 0) }}</td>
                                </tr>
                                {{-- <tr>
                                    <td colspan="6" class="jaga-jarak">&nbsp;</td>
                                </tr> --}}
                                <tr>
                                    <td colspan="6"><table width="100%" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td width="22%">&nbsp;</td>
                                                <td width="2%">&nbsp;</td>
                                                <td width="15%">&nbsp;</td>
                                                <td width="1%">&nbsp;</td>
                                                <td width="5%">&nbsp;</td>
                                                <td width="55%">&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td colspan="6">Terbilang :<br/><span style="font-style: italic;">{{ (new App\Http\Controllers\TrxPayrollController)->terbilang($total_diterima) }} Rupiah</span></td>
                                            </tr>
                                            {{-- <tr>
                                                <td colspan="6">{{ (new App\Http\Controllers\TrxPayrollController)->terbilang($total_diterima) }} Rupiah</td>
                                            </tr> --}}
                                            <tr>
                                                <td colspan="6">&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td>Dibayarkan Tanggal<br>Via</td>
                                                <td>:<br/>:</td>
                                                {{-- <td colspan="3">@if(!empty())25 {{ $bulan[$payroll->bulan - 1] }} {{ $payroll->tahun }}<br/>Transfer</td> --}}
                                                <td colspan="3">@if(!empty($payroll->paid_at)){{ Carbon\Carbon::parse($payroll->paid_at)->format("d") }} {{ $bulan[Carbon\Carbon::parse($payroll->paid_at)->format("m") - 1] }} {{ Carbon\Carbon::parse($payroll->paid_at)->format("Y") }}@else &nbsp; @endif<br/>Transfer</td>
                                                <td align="right"><br/><br/><br/><br/>PT Alita Praya Mitra - People & Culture<br/>{{ Carbon\Carbon::parse(now())->format("d-m-Y H:i:s") }}</td>
                                                {{-- <td>Paraf {{ App\Models\MtrUtility::find(51)->informasi }}<br/><img width="50" src="{{ public_path('assets/images/matta/paraf_hco.png') }}" alt="Paraf {{ App\Models\MtrUtility::find(51)->informasi }}"><br/>{{ $pic_paraf }}</td> --}}
                                            </tr>
				                        </table>
                                    </td>
                                </tr>
				            </table>
                        </td>
                        <td width="2%">&nbsp;</td>
                        {{-- end --}}
					</tr>
				</table>
			</td>
		</tr>
	</table>
</body>
</html>
