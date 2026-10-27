<!DOCTYPE html>
<html>

<style>
.center {
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
				<table width="100%" border="0.5" cellspacing="0" cellpadding="2">
					<tr>
						<td width="25%">&nbsp;<img src="<?php echo e(public_path('assets/images/matta/alita_new.png')); ?>" alt="Logo Alita"></td>
                        <td width="50%"><div class="center" style="font-weight: bold; font-size: 12px">FORM PENERIMAAN KARYAWAN BARU</div></td>
                        <td width="25%"><div class="center">No: <?php echo e($_trx->id); ?></div></td>
					</tr>
				</table>
				<br/><br/>
				<table width="100%" border="0.5" cellspacing="0" cellpadding="2">
					<tr>
						<td align="center"><b>PERSONAL</b></td>
					</tr>
				</table>
				<table class="border-top border-bottom border-left border-right" width="100%" cellspacing="0" cellpadding="2">
                    <tr>
                        <td width="29%">Nama Lengkap</td>
                        <td width="2%">:</td>
                        <td width="69%"><?php echo e($_trx->nama); ?></td>
                    </tr>
                    <tr>
                        <td>Alamat</td>
                        <td>:</td>
                        <td><?php echo e($_trx->alamat_ktp); ?></td>
                    </tr>
                    <tr>
                        <td>Tempat/Tanggal Lahir</td>
                        <td>:</td>
                        <td><?php echo e($_trx->tempat_lahir); ?>/<?php echo e(Carbon\Carbon::parse($_trx->tanggal_lahir)->format('d-m-Y')); ?></td>
                    </tr>
                    <tr>
                        <td>Pendidikan</td>
                        <td>:</td>
                        <td><?php echo e($_trx->pendidikan); ?></td>
                    </tr>
					<?php
					$formal = App\Models\TrxRecruitmentFormal::where(['id_identitas'=>$_trx->id, 'jenjang'=>$_trx->pendidikan])->first();
					$kebutuhan_atribute = json_decode($_trx->kebutuhan_atribute, true);
					$kebutuhan_atribute_atasan_property = json_decode($kebutuhan_atribute['atasan_property'], true);
					?>
					<?php if(!empty($formal)): ?>
                    <tr>
                        <td>Jurusan</td>
                        <td>:</td>
                        <td><?php echo e($formal->jurusan); ?></td>
                    </tr>
					<?php endif; ?>
                </table>
				<br/><br/>
				<table width="100%" border="0.5" cellspacing="0" cellpadding="2">
					<tr>
						<td align="center"><b>KELENGKAPAN DOKUMEN</b></td>
					</tr>
				</table>
				<table width="100%" class="border-top border-bottom border-left border-right" cellspacing="0" cellpadding="2">
					
					
					<tr>
                        <td width="50%"><?php if(!empty($_trx->cv)): ?> <img src="<?php echo e(public_path('assets/images/matta/check.png')); ?>" alt="check_uncheck" width="9px"> <?php else: ?> <img src="<?php echo e(public_path('assets/images/matta/uncheck.png')); ?>" alt="check_uncheck" width="9px"> <?php endif; ?> CV Lengkap</td>
						<td width="50%" style="border-left: 0.5px thin black;"><?php if(!empty($_trx->file_ijazah)): ?> <img src="<?php echo e(public_path('assets/images/matta/check.png')); ?>" alt="check_uncheck" width="9px"> <?php else: ?> <img src="<?php echo e(public_path('assets/images/matta/uncheck.png')); ?>" alt="check_uncheck" width="9px"> <?php endif; ?> Copy Ijazah</td>
                    </tr>
					<tr>
                        <td><?php if(!empty($_trx->file_sertifikat_keahlian)): ?> <img src="<?php echo e(public_path('assets/images/matta/check.png')); ?>" alt="check_uncheck" width="9px"> <?php else: ?> <img src="<?php echo e(public_path('assets/images/matta/uncheck.png')); ?>" alt="check_uncheck" width="9px"> <?php endif; ?> Copy Sertifikat Keahlian</td>
						<td style="border-left: 0.5px thin black;"><?php if(!empty($_trx->file_skck)): ?> <img src="<?php echo e(public_path('assets/images/matta/check.png')); ?>" alt="check_uncheck" width="9px"> <?php else: ?> <img src="<?php echo e(public_path('assets/images/matta/uncheck.png')); ?>" alt="check_uncheck" width="9px"> <?php endif; ?> Surat Keterangan Catatan Kepolisian (SKCK)</td>
                    </tr>
					<tr>
                        <td><?php if(!empty($_trx->file_ktp)): ?> <img src="<?php echo e(public_path('assets/images/matta/check.png')); ?>" alt="check_uncheck" width="9px"> <?php else: ?> <img src="<?php echo e(public_path('assets/images/matta/uncheck.png')); ?>" alt="check_uncheck" width="9px"> <?php endif; ?> Copy Kartu Tanda Penduduk (KTP)</td>
						<td style="border-left: 0.5px thin black;"><?php if(!empty($_trx->file_bpjs_tk)): ?> <img src="<?php echo e(public_path('assets/images/matta/check.png')); ?>" alt="check_uncheck" width="9px"> <?php else: ?> <img src="<?php echo e(public_path('assets/images/matta/uncheck.png')); ?>" alt="check_uncheck" width="9px"> <?php endif; ?> Copy Kartu BPJS Ketenagakerjaan (BPJSTK)</td>
                    </tr>
					<tr>
                        <td><?php if(!empty($_trx->file_kk)): ?> <img src="<?php echo e(public_path('assets/images/matta/check.png')); ?>" alt="check_uncheck" width="9px"> <?php else: ?> <img src="<?php echo e(public_path('assets/images/matta/uncheck.png')); ?>" alt="check_uncheck" width="9px"> <?php endif; ?> Copy Kartu Keluarga (KK)</td>
						<td style="border-left: 0.5px thin black;"><?php if(!empty($_trx->file_bpjs_kes)): ?> <img src="<?php echo e(public_path('assets/images/matta/check.png')); ?>" alt="check_uncheck" width="9px"> <?php else: ?> <img src="<?php echo e(public_path('assets/images/matta/uncheck.png')); ?>" alt="check_uncheck" width="9px"> <?php endif; ?> Copy Kartu BPJS Kesehatan (BPJSKS)</td>
                    </tr>
					<tr>
                        <td><?php if(!empty($_trx->file_npwp)): ?> <img src="<?php echo e(public_path('assets/images/matta/check.png')); ?>" alt="check_uncheck" width="9px"> <?php else: ?> <img src="<?php echo e(public_path('assets/images/matta/uncheck.png')); ?>" alt="check_uncheck" width="9px"> <?php endif; ?> Copy Kartu NPWP</td>
						<td style="border-left: 0.5px thin black;"><?php if(!empty($_trx->file_buku_tabungan)): ?> <img src="<?php echo e(public_path('assets/images/matta/check.png')); ?>" alt="check_uncheck" width="9px"> <?php else: ?> <img src="<?php echo e(public_path('assets/images/matta/uncheck.png')); ?>" alt="check_uncheck" width="9px"> <?php endif; ?> Copy Buku Tabungan (No. Rekening Bank Mandiri)</td>
                    </tr>
					<tr>
                        <td><?php if(!empty($_trx->file_ket_sehat)): ?> <img src="<?php echo e(public_path('assets/images/matta/check.png')); ?>" alt="check_uncheck" width="9px"> <?php else: ?> <img src="<?php echo e(public_path('assets/images/matta/uncheck.png')); ?>" alt="check_uncheck" width="9px"> <?php endif; ?> Surat Keterangan Sehat</td>
						<td style="border-left: 0.5px thin black;"><?php if(!empty($_trx->file_covid)): ?> <img src="<?php echo e(public_path('assets/images/matta/check.png')); ?>" alt="check_uncheck" width="9px"> <?php else: ?> <img src="<?php echo e(public_path('assets/images/matta/uncheck.png')); ?>" alt="check_uncheck" width="9px"> <?php endif; ?> Sertifikat Vaksin Covid-19</td>
                    </tr>
				</table>
				<br/><br/>
				<table width="100%" border="0.5" cellspacing="0" cellpadding="2">
					<tr>
						<td align="center"><b>ORGANISASI</b></td>
					</tr>
				</table>
				<table class="border-top border-bottom border-left border-right" width="100%" cellspacing="0" cellpadding="2">
                    <tr>
                        <td width="29%">Posisi / Jabatan</td>
                        <td width="2%">:</td>
						
                        <td width="69%"><?php echo e(App\Models\PekerjaanSub::find($kebutuhan_atribute['id_pekerjaan'])->pekerjaan); ?></td>
                    </tr>
                    <tr>
                        <td>Level</td>
                        <td>:</td>
						
                        <td><?php echo e(App\Models\Level::find($kebutuhan_atribute['id_level'])->level); ?></td>
                    </tr>
                    <tr>
                        <td>Area Kerja</td>
                        <td>:</td>
						
                        <td><?php echo e(App\Models\Propinsi::find($kebutuhan_atribute['id_area'])->propinsi); ?></td>
                    </tr>
                    <tr>
                        <td>Direktorat / Divisi / Departemen Kandidat</td>
                        <td>:</td>
						
                        <td><?php echo e(App\Models\Divisi::find($kebutuhan_atribute['id_divisi_kandidat'])->nama); ?> / <?php echo e(App\Models\Departemen::find($kebutuhan_atribute['id_dep_kandidat'])->nama); ?> / <?php echo e(App\Models\SubDepartemen::find($kebutuhan_atribute['id_sub_dep_kandidat'])->nama); ?></td>
                    </tr>
					<?php
					$atasan = json_decode($trxM->atasan_property, true);
					?>
                    <tr>
                        <td>Atasan Langsung Kandidat</td>
                        <td>:</td>
                        
                        <td><?php echo e($kebutuhan_atribute_atasan_property['nama']); ?> / <?php echo e($kebutuhan_atribute_atasan_property['nik']); ?></td>
                    </tr>
                    <tr>
                        <td>Direktorat / Divisi / Departemen</td>
                        <td>:</td>
                        
						<td><?php echo e($kebutuhan_atribute_atasan_property['divisi']); ?> / <?php echo e($kebutuhan_atribute_atasan_property['dep']); ?> / <?php echo e($kebutuhan_atribute_atasan_property['sub_dep']); ?></td>
                    </tr>
                </table>
				<br/><br/>
				<table width="100%" border="0.5" cellspacing="0" cellpadding="2">
					<tr>
						<td align="center"><b>BENEFIT</b></td>
					</tr>
				</table>
				<table width="100%" class="border-top border-bottom border-left border-right" cellspacing="0" cellpadding="2">
					<?php $benefit_utama = explode(',', $trx->benefit_utama); ?>
					<tr>
                        <td width="55%"><?php if(!empty($trx->thp)): ?> <img src="<?php echo e(public_path('assets/images/matta/check.png')); ?>" alt="check_uncheck" width="9px"> <?php else: ?> <img src="<?php echo e(public_path('assets/images/matta/uncheck.png')); ?>" alt="check_uncheck" width="9px"> <?php endif; ?> Take Home Pay (THP): Rp. <?php echo e(number_format($trx->thp, 0)); ?></td>
						
						<td width="45%" style="border-left: 0.5px thin black;"><?php if(in_array(9, $benefit_utama)): ?> <img src="<?php echo e(public_path('assets/images/matta/check.png')); ?>" alt="check_uncheck" width="9px"> <?php else: ?> <img src="<?php echo e(public_path('assets/images/matta/uncheck.png')); ?>" alt="check_uncheck" width="9px"> <?php endif; ?> Cuti Setelah 1 tahun</td>
                    </tr>
					<tr>
						
                        <td><?php if(in_array(1, $benefit_utama)): ?> <img src="<?php echo e(public_path('assets/images/matta/check.png')); ?>" alt="check_uncheck" width="9px"> <?php else: ?> <img src="<?php echo e(public_path('assets/images/matta/uncheck.png')); ?>" alt="check_uncheck" width="9px"> <?php endif; ?> Upah Lembur</td>
						
						<td style="border-left: 0.5px thin black;"><?php if(in_array(10, $benefit_utama)): ?> <img src="<?php echo e(public_path('assets/images/matta/check.png')); ?>" alt="check_uncheck" width="9px"> <?php else: ?> <img src="<?php echo e(public_path('assets/images/matta/uncheck.png')); ?>" alt="check_uncheck" width="9px"> <?php endif; ?> Asuransi</td>
                    </tr>
					<tr>
						
                        <td><?php if(in_array(2, $benefit_utama)): ?> <img src="<?php echo e(public_path('assets/images/matta/check.png')); ?>" alt="check_uncheck" width="9px"> <?php else: ?> <img src="<?php echo e(public_path('assets/images/matta/uncheck.png')); ?>" alt="check_uncheck" width="9px"> <?php endif; ?> Medical Reimbursement</td>
						
						<td style="border-left: 0.5px thin black;"><?php if(in_array(10, $benefit_utama)): ?> <img src="<?php echo e(public_path('assets/images/matta/check.png')); ?>" alt="check_uncheck" width="9px"> <?php else: ?> <img src="<?php echo e(public_path('assets/images/matta/uncheck.png')); ?>" alt="check_uncheck" width="9px"> <?php endif; ?> Tunjangan Corporate</td>
                    </tr>
					<tr>
						
						
                        <td><?php if(in_array(3, $benefit_utama)||in_array(4, $benefit_utama)): ?> <img src="<?php echo e(public_path('assets/images/matta/check.png')); ?>" alt="check_uncheck" width="9px"> <?php else: ?> <img src="<?php echo e(public_path('assets/images/matta/uncheck.png')); ?>" alt="check_uncheck" width="9px"> <?php endif; ?> PPh 21 <?php if(in_array(3, $benefit_utama)): ?> ditanggung: <img src="<?php echo e(public_path('assets/images/matta/check.png')); ?>" alt="check_uncheck" width="9px"> Perusahaan <img src="<?php echo e(public_path('assets/images/matta/uncheck.png')); ?>" alt="check_uncheck" width="9px"> Karyawan <?php elseif(in_array(4, $benefit_utama)): ?> ditanggung: <img src="<?php echo e(public_path('assets/images/matta/check.png')); ?>" alt="check_uncheck" width="9px"> Perusahaan <img src="<?php echo e(public_path('assets/images/matta/check.png')); ?>" alt="check_uncheck" width="9px"> Karyawan <?php endif; ?></td>
						
						<td style="border-left: 0.5px thin black;"><?php if(in_array(12, $benefit_utama)): ?> <img src="<?php echo e(public_path('assets/images/matta/check.png')); ?>" alt="check_uncheck" width="9px"> <?php else: ?> <img src="<?php echo e(public_path('assets/images/matta/uncheck.png')); ?>" alt="check_uncheck" width="9px"> <?php endif; ?> Tunjangan Project / Operasional</td>
                    </tr>
					<tr>
						
						
                        <td><?php if(in_array(5, $benefit_utama)||in_array(6, $benefit_utama)): ?> <img src="<?php echo e(public_path('assets/images/matta/check.png')); ?>" alt="check_uncheck" width="9px"> <?php else: ?> <img src="<?php echo e(public_path('assets/images/matta/uncheck.png')); ?>" alt="check_uncheck" width="9px"> <?php endif; ?> BPJS Ketenagakerjaan <?php if(in_array(5, $benefit_utama)): ?> ditanggung: <img src="<?php echo e(public_path('assets/images/matta/check.png')); ?>" alt="check_uncheck" width="9px"> Perusahaan <img src="<?php echo e(public_path('assets/images/matta/uncheck.png')); ?>" alt="check_uncheck" width="9px"> Karyawan <?php elseif(in_array(6, $benefit_utama)): ?> ditanggung: <img src="<?php echo e(public_path('assets/images/matta/check.png')); ?>" alt="check_uncheck" width="9px"> Perusahaan <img src="<?php echo e(public_path('assets/images/matta/check.png')); ?>" alt="check_uncheck" width="9px"> Karyawan <?php endif; ?></td>
						
						<td style="border-left: 0.5px thin black;"><?php if(in_array(12, $benefit_utama)): ?> <img src="<?php echo e(public_path('assets/images/matta/check.png')); ?>" alt="check_uncheck" width="9px"> <?php else: ?> <img src="<?php echo e(public_path('assets/images/matta/uncheck.png')); ?>" alt="check_uncheck" width="9px"> <?php endif; ?> Alat kerja (laptop / notebook)</td>
                    </tr>
					<tr>
						
						
                        <td><?php if(in_array(7, $benefit_utama)||in_array(8, $benefit_utama)): ?> <img src="<?php echo e(public_path('assets/images/matta/check.png')); ?>" alt="check_uncheck" width="9px"> <?php else: ?> <img src="<?php echo e(public_path('assets/images/matta/uncheck.png')); ?>" alt="check_uncheck" width="9px"> <?php endif; ?> BPJS Kesehatan <?php if(in_array(7, $benefit_utama)): ?> ditanggung: <img src="<?php echo e(public_path('assets/images/matta/check.png')); ?>" alt="check_uncheck" width="9px"> Perusahaan <img src="<?php echo e(public_path('assets/images/matta/uncheck.png')); ?>" alt="check_uncheck" width="9px"> Karyawan <?php elseif(in_array(8, $benefit_utama)): ?> ditanggung: <img src="<?php echo e(public_path('assets/images/matta/check.png')); ?>" alt="check_uncheck" width="9px"> Perusahaan <img src="<?php echo e(public_path('assets/images/matta/check.png')); ?>" alt="check_uncheck" width="9px"> Karyawan <?php endif; ?></td>
						
						<td style="border-left: 0.5px thin black;">&nbsp;</td>
                    </tr>
					<tr>
						
						<td colspan="2" style="border-top: 0.5px thin black;">Keterangan:<br/><?php if(!empty($trx->benefit_lainnya)): ?><?php echo nl2br($trx->benefit_lainnya); ?><?php endif; ?></td>
                    </tr>
				</table>
				<br/><br/>
				<table width="100%" border="0.5" cellspacing="0" cellpadding="2">
					<tr>
						<td align="center"><b>KEPEGAWAIAN</b></td>
					</tr>
				</table>
				<table class="border-top border-bottom border-left border-right" width="100%" cellspacing="0" cellpadding="2">
                    <tr>
                        <td width="29%">Mulai Bergabung</td>
                        <td width="2%">:</td>
                        <td width="69%"><?php echo e(Carbon\Carbon::parse($trx->tgl_bergabung)->format('d-m-Y')); ?></td>
                    </tr>
                    <tr>
                        <td>Pencatatan Kepegawaian</td>
                        <td>:</td>
                        
						<td><table width="100%" cellspacing="0" cellpadding="0"><tr><td width="20%"><?php if($trx->id_catat_pegawai == 1): ?><img src="<?php echo e(public_path('assets/images/matta/check.png')); ?>" alt="check_uncheck" width="9px"><?php else: ?><img src="<?php echo e(public_path('assets/images/matta/uncheck.png')); ?>" alt="check_uncheck" width="9px"><?php endif; ?> ALITA</td><td width="20%"><?php if($trx->id_catat_pegawai == 2): ?><img src="<?php echo e(public_path('assets/images/matta/check.png')); ?>" alt="check_uncheck" width="9px"><?php else: ?><img src="<?php echo e(public_path('assets/images/matta/uncheck.png')); ?>" alt="check_uncheck" width="9px"><?php endif; ?> Outsource</td></tr></table></td>
                    </tr>
                    <tr>
                        <td>Status Kepegawaian</td>
                        <td>:</td>
                        
						<td><table width="100%" cellspacing="0" cellpadding="0"><tr><td width="20%"><?php if($trx->id_status_pegawai == 1): ?><img src="<?php echo e(public_path('assets/images/matta/check.png')); ?>" alt="check_uncheck" width="9px"><?php else: ?><img src="<?php echo e(public_path('assets/images/matta/uncheck.png')); ?>" alt="check_uncheck" width="9px"><?php endif; ?> Kontrak</td><td width="15%"><?php if($trx->id_status_pegawai == 2): ?><img src="<?php echo e(public_path('assets/images/matta/check.png')); ?>" alt="check_uncheck" width="9px"><?php else: ?><img src="<?php echo e(public_path('assets/images/matta/uncheck.png')); ?>" alt="check_uncheck" width="9px"><?php endif; ?> Tetap</td><td width="40%"><?php if($trx->id_status_pegawai == 3): ?><img src="<?php echo e(public_path('assets/images/matta/check.png')); ?>" alt="check_uncheck" width="9px"><?php else: ?><img src="<?php echo e(public_path('assets/images/matta/uncheck.png')); ?>" alt="check_uncheck" width="9px"><?php endif; ?> Tenaga Lepas Harian (TLH)</td><td width="25%"><?php if($trx->id_status_pegawai == 4): ?><img src="<?php echo e(public_path('assets/images/matta/check.png')); ?>" alt="check_uncheck" width="9px"><?php else: ?><img src="<?php echo e(public_path('assets/images/matta/uncheck.png')); ?>" alt="check_uncheck" width="9px"><?php endif; ?> Konsultan</td></tr></table></td>
                    </tr>
                    <tr>
                        <td>Masa Perjanjian Kerja</td>
                        <td>:</td>
                        <td><?php echo e($trx->masa_kerja); ?> bulan</td>
                    </tr>
                </table>
				<br/><br/>
				<table width="100%" class="border-top border-bottom border-left border-right" align="center" cellpadding="2" cellspacing="0">
					<?php
					// 17=Negotiation Deal by Candidate
					// 18=Negotiation No Deal by Candidate
					$where = ['id_identitas' => $trx->id_identitas];
					$flow1 = App\Models\TrxRecruitmentLog::where($where)->whereIn('id_status', [17,18])->orderBy('id', 'desc')->first();
					// 12=Negotiation Process
					$where = ['id_identitas' => $trx->id_identitas, 'id_status' => 12];
					$flow2 = App\Models\TrxRecruitmentLog::where($where)->first();
					// 26=Approval By Leader
					$where = ['id_identitas' => $trx->id_identitas, 'id_status' => 26];
					$flow3 = App\Models\TrxRecruitmentLog::where($where)->first();
					?>
                    <tr>
                        <td>
							Kandidat<br/>
							<?php if(!empty($flow1)): ?>
							<?php if($flow1->id_status == 17): ?>
							<img src="<?php echo e(public_path('assets/images/matta/approved_new.png')); ?>" height="30" /><br/><?php echo e($flow1->pic); ?><br/><?php echo e($flow1->created_at); ?>

							<?php elseif($flow1->id_status == 18): ?>
							<img src="<?php echo e(public_path('assets/images/matta/rejected_new.png')); ?>" height="30" /><br/><?php echo e($flow1->pic); ?><br/><?php echo e($flow1->created_at); ?>

							<?php endif; ?>
							<?php endif; ?>
						</td>
                        <?php if(!empty($flow2)): ?><td><?php echo e(App\Models\MtrUtility::find(51)->informasi); ?><br/><img src="<?php echo e(public_path('assets/images/matta/verified_new.png')); ?>" height="30" /><br/><?php echo e($flow2->pic); ?><br/><?php echo e($flow2->created_at); ?></td><?php endif; ?>
						
                        <?php if(!empty($flow3)): ?><td>Mengetahui/Menyetujui<br/><img src="<?php echo e(public_path('assets/images/matta/approved_new.png')); ?>" height="30" /><br/><?php echo e($flow3->pic); ?><br/><?php echo e($flow3->created_at); ?></td><?php endif; ?>
                        
                    </tr>
                </table>
			</td>
		</tr>
	</table>
	<!-- finish -->
</body>

</html><?php /**PATH C:\xampp\htdocs\matta\resources\views/recruitment/pdf_offering.blade.php ENDPATH**/ ?>