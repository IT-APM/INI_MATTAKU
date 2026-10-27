<!DOCTYPE html>
<html>

<style>
.center {
    /* margin: auto;
  width: 50%;
  border: 3px solid green;
  padding: 10px; */
  line-height: 25px;
  /* padding-top: 55px; */
  vertical-align: middle;
  text-align: center;
}
.center-text {
  line-height: 55px;
  text-align: center;
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
</style>
<head>
	<title>Matta Reborn</title>
</head>

<body>
	<!-- start -->
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td>
				<table width="100%" border="0.5px" cellspacing="0" cellpadding="2">
					<tr>
						<td width="25%">&nbsp;<img src="{{ public_path('assets/images/matta/alita_new.png') }}" alt="Logo Alita"></td>
                        <td width="50%"><div class="center" style="font-weight: bold; font-size: 12px">SURAT PERSETUJUAN<br/>DATA PRIBADI PELAMAR KERJA</div></td>
                        <td width="25%"><div class="center-text">No: {{ $trx->id }}</div></td>
					</tr>
				</table>
				<br/><br/>
				<table class="border-top border-bottom border-left border-right" width="100%" cellspacing="0" cellpadding="2">
                    <tr align="justify">
                        <td width="3%">&nbsp;</td>
                        <td width="97%">&nbsp;</td>
                    </tr>
                    <tr align="justify">
                        <td>1.</td>
                        <td>Saya bersedia dan menyetujui untuk memberikan Data Pribadi saya yang tertuang dalam Curriculum Vitae (CV) dan Formulir Identitas Pelamar Kerja (untuk selanjutnya disebut sebagai <b>“Data Pribadi”</b>) yang diperlukan dan dipersyaratkan pada saat melamar di PT Alita Praya Mitra (untuk selanjutnya disebut <b>“Perusahaan”</b>).</td>
                    </tr>
                    <tr align="justify">
                        <td>2.</td>
                        <td>Bahwa Perusahaan akan menggunakan Data Pribadi saya untuk kebutuhan Proses Rekrutmen, termasuk di dalamnya Tes Psikologi dan Asesmen, yang dilakukan secara langsung oleh Perusahaan.</td>
                    </tr>
                    <tr align="justify">
                        <td>3.</td>
                        <td>Perusahaan akan menyimpan Data Pribadi saya di Sistem Rekrutmen Perusahaan dan Perusahaan menjamin tidak ada penyalahgunaan atau penggunaan di luar kebutuhan Proses Rekrutmen.</td>
                    </tr>
                    <tr align="justify">
                        <td>4.</td>
                        <td>Perusahaan akan melakukan penyimpanan Data Pribadi saya selama 3 (tiga) tahun sejak saya menyerahkan Data Pribadi saya.</td>
                    </tr>
                    <tr align="justify">
                        <td>5.</td>
                        <td>Perusahaan menjamin hak saya sebagai subjek Data Pribadi sesuai Undang-Undang Perlindungan Data Pribadi No. 27/2022, termasuk dengan Peraturan Perundang-undangan turunannya.</td>
                    </tr>
                    <tr align="justify">
                        <td>6.</td>
                        <td>Saya menyatakan bahwa Data Pribadi dan dokumen yang saya berikan adalah benar dan sesuai dengan kondisi terkini.</td>
                    </tr>
                    <tr align="justify">
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
				</table>
                @php
					// 3=Apply
					$where = ['id_identitas' => $trx->id, 'id_status' => 3];
					$flow1 = App\Models\TrxRecruitmentLog::where($where)->first();
                @endphp
				<table width="100%" class="border-top border-bottom border-left border-right" align="center" cellpadding="2" cellspacing="0">
                    <tr>
                        <td>Kandidat<br/><img src="{{ public_path('assets/images/matta/approved_new.png') }}" height="30" /><br/>{{ $flow1->pic }}<br/>{{ $flow1->created_at }}</td>
                    </tr>
				</table>
			</td>
		</tr>
	</table>
	<!-- finish -->
</body>

</html>