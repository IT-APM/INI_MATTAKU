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

<body>
    <table width="100%" border="1" cellspacing="0" cellpadding="0">
        <tr>
            <td><table width="100%" border="1" cellspacing="0" cellpadding="2">
                    <tr>
                        <td width="25%">&nbsp;<img src="<?php echo e(public_path('assets/images/matta/alita_new.png')); ?>" alt="Logo Alita"></td>
                        <td width="50%"><div class="center" style="font-weight: bold; font-size: 12px">FORM PENGAJUAN KEBUTUHAN PEGAWAI</div></td>
                        <td width="25%"><div class="center">No: <?php echo e($trxM->id); ?></div></td>
                    </tr>
                </table>
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td align="center"><strong>PENGAJU</strong></td>
                    </tr>
                </table>
                <hr />
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td width="30%">Nama<br/>NIP<br/>Jabatan<br/>Direktorat / Divisi / Departemen</td>
                        <td width="70%">: <?php echo e($pengaju['nama']); ?><br/>: <?php echo e($pengaju['nik']); ?><br/>: <?php echo e($pengaju['jabatan']); ?><br/>: <?php echo e($pengaju['divisi']); ?> / <?php echo e($pengaju['dep']); ?> / <?php echo e($pengaju['sub_dep']); ?></td>
                    </tr>
                </table>
                <hr />
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td align="center"><strong>KEBUTUHAN REKRUITMEN</strong></td>
                    </tr>
                </table>
                <hr />
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td width="30%">Nama Jabatan/posisi</td>
                        <td width="70%">: <?php echo e($trxM->_pekerjaan->pekerjaan); ?></td>
                    </tr>
                    <tr>
                        <td>Level</td>
                        <td>: <?php echo e($trxM->_level->level); ?></td>
                    </tr>
                    <tr>
                        <td>Area Kerja</td>
                        <td>: Propinsi: <?php echo e($trxM->_area->propinsi); ?><?php if(!empty($trxM->id_kota)): ?>, Kota: <?php echo e($trxM->_kota->kota); ?> <?php endif; ?></td>
                    </tr>
                    <tr>
                        <td>Direktorat / Divisi / Departemen</td>
                        <td>: <?php echo e($trxM->_divisi_kandidat->nama); ?> / <?php echo e($trxM->_dep_kandidat->nama); ?> / <?php echo e($trxM->_sub_dep_kandidat->nama); ?></td>
                    </tr>
                    <?php
                        $atasan_property = $trxM->atasan_property;
                        $atasan_property = json_decode($atasan_property, true);
                    ?>
                    <tr>
                        <td>Atasan Langsung Kandidat</td>
                        <td>: <?php if(!empty($atasan_property)): ?> <?php echo e($atasan_property['nama']); ?> / <?php echo e($atasan_property['nik']); ?> <?php endif; ?></td>
                    </tr>
                    <tr>
                        <td>Jabatan Atasan Langsung</td>
                        <td>: <?php if(!empty($atasan_property)): ?> <?php echo e($atasan_property['jabatan']); ?> <?php endif; ?></td>
                    </tr>
                    <tr>
                        <td>Direktorat / Divisi / Departemen</td>
                        <td>: <?php echo e($pengaju['divisi']); ?> / <?php echo e($pengaju['dep']); ?> / <?php echo e($pengaju['sub_dep']); ?></td>
                    </tr>
                    <tr>
                        <td>Status Kerja</td>
                        <td>: <?php echo e($trxM->_status_kerja->status_kerja); ?></td>
                    </tr>
                    <?php if(!empty($trxM->waktu_kontrak)): ?>
                    <tr>
                        <td>Waktu Kontrak</td>
                        <td>: <?php echo e($trxM->waktu_kontrak); ?> (Bulan)</td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <td>Jumlah</td>
                        <td>: <?php echo e($trxM->jumlah); ?> (Orang) <?php echo e($trxM->_kelamin->kelamin); ?></td>
                    </tr>
                    <tr>
                        <td>Jenis Permintaan</td>
                        <td>: <?php echo e($trxM->_jenis_permintaan->jenis); ?></td>
                    </tr>
                    <?php if(!empty($trxM->nik_karyawan)): ?>
                    <tr>
                        <td>Karyawan Penggantian</td>
                        <td><?php $__currentLoopData = $arr_nik_karyawan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php echo e($loop->iteration); ?>. <?php echo e($value['nama']); ?> / <?php echo e($value['nik']); ?> <?php if(next($arr_nik_karyawan)): ?> <br/> <?php endif; ?> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <td colspan="2">Apakah sudah masuk dalam rencana <?php if($trxM->id_jenis_permintaan == 1): ?> penggantian <?php else: ?> penambahan <?php endif; ?> pegawai? <?php if($trxM->id_rencana == 0): ?> Belum <?php else: ?> Sudah <?php endif; ?></td>
                    </tr>
                    <tr>
                        <td>Alasan Permintaan</td>
                        <td>: <?php echo nl2br($trxM->penjelasan); ?></td>
                    </tr>
                </table>
                <hr />
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td align="center"><strong>FINDIM / COST ALLOCATION</strong></td>
                    </tr>
                </table>
                <hr />
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td width="30%">Project</td>
                        <td width="70%">: <?php echo e($trxM->_findim_project->project_id); ?> - <?php echo e($trxM->_findim_project->project_desc); ?></td>
                    </tr>
                    <tr>
                        <td>Divisi</td>
                        <td>: <?php echo e($trxM->_findim_divisi->divisi_id); ?> - <?php echo e($trxM->_findim_divisi->divisi_desc); ?></td>
                    </tr>
                    <tr>
                        <td>Departemen</td>
                        <td>: <?php echo e($trxM->_findim_departemen->departemen_id); ?> - <?php echo e($trxM->_findim_departemen->departemen_desc); ?></td>
                    </tr>
                    <tr>
                        <td>Sub Departemen</td>
                        <td>: <?php echo e($trxM->_findim_subdepartemen->subdepartemen_id); ?> - <?php echo e($trxM->_findim_subdepartemen->subdepartemen_desc); ?></td>
                    </tr>
                </table>
                <hr />
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td align="center"><strong>TUGAS, WEWENANG DAN TANGGUNG JAWAB</strong></td>
                    </tr>
                </table>
                
                <table width="100%" border="1" cellspacing="0" cellpadding="2">
                    <tr>
                        <td width="30%">Tugas</td>
                        <td width="70%"><?php echo nl2br($trxM->tugas); ?></td>
                    </tr>
                    <tr>
                        <td>Wewenang</td>
                        <td><?php echo nl2br($trxM->wewenang); ?></td>
                    </tr>
                    <tr>
                        <td>Tanggung jawab</td>
                        <td><?php echo nl2br($trxM->tanggung_jawab); ?></td>
                    </tr>
                </table>
                
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td align="center"><strong>KUALIFIKASI / SPESIFIKASI JABATAN</strong></td>
                    </tr>
                </table>
                <hr />
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td width="30%">Pendidikan Minimum</td>
                        <td width="70%">: <?php echo e($trxM->_jenjang_pendidikan->jenjang); ?></td>
                    </tr>
                    <?php if(!empty($trxM->id_jurusan_pendidikan)): ?>
                    <tr>
                        <td>Jurusan</td>
                        <td>: 
                        <?php if($trxM->id_jurusan_pendidikan <> '0'): ?>
                            <?php $__currentLoopData = explode(',', $trxM->id_jurusan_pendidikan); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php echo e($index+1); ?>. <?php echo e(App\Models\PendidikanJurusan::find($value)->jurusan); ?> <?php if(!$loop->last): ?> <br/>&nbsp; <?php endif; ?> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                        </td>
                        
                    </tr>
                    <?php endif; ?>
                    <?php if(!empty($trxM->nilai_ipk)): ?>
                    <tr>
                        <td>Nilai IPK</td>
                        <td>: <?php echo e($trxM->nilai_ipk); ?></td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <td>Usia Minimum</td>
                        <td>: <?php echo e($trxM->umur_minimum); ?> (Tahun)</td>
                    </tr>
                    <tr>
                        <td>Usia Maksimum</td>
                        <td>: <?php echo e($trxM->umur_maksimum); ?> (Tahun)</td>
                    </tr>
                    <tr>
                        <td>Pengalaman Kerja (Tahun)</td>
                        <td>: <?php echo e($trxM->_pengalaman->pengalaman); ?></td>
                    </tr>
                    <hr/>
                    <tr>
                        <td>Training yang seharusnya sudah diikuti</td>
                        <td>: 
                        <?php if(!empty($trxM->id_pelatihan)): ?>
                            <?php $__currentLoopData = $pelatihan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php echo e($index+1); ?>. <?php echo e($value->sub_pelatihan); ?> <?php if(!$loop->last): ?> <br/>&nbsp; <?php endif; ?> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                        </td>
                        
                    </tr>
                    <hr/>
                    <tr>
                        <td>Kursus/sertifikasi yang seharusnya sudah diperoleh</td>
                        <td>: 
                        <?php if(!empty($trxM->id_sertifikasi)): ?>
                            <?php $__currentLoopData = $sertifikasi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php echo e($index+1); ?>. <?php echo e($value->sub_sertifikasi); ?> <?php if(!$loop->last): ?> <br/>&nbsp; <?php endif; ?> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                        </td>
                        
                    </tr>
                </table>
                <hr />
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td align="center"><strong>KEMAMPUAN PRIBADI</strong></td>
                    </tr>
                </table>
                <hr />
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td width="30%">Ketrampilan yang harus sudah dipunyai</td>
                        <td width="70%">: 
                        <?php if(!empty($trxM->id_keterampilan)): ?>
                            <?php $__currentLoopData = $keterampilan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php echo e($index+1); ?>. <?php echo e($value->sub_keterampilan); ?> <?php if(!$loop->last): ?> <br/>&nbsp; <?php endif; ?> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                        </td>
                        
                    </tr>
                    <hr/>
                    <tr>
                        <td>Kepribadian dasar yang harus dimiliki</td>
                        <td>: 
                        <?php if(!empty($trxM->id_kepribadian)): ?>
                            <?php $__currentLoopData = $kepribadian; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php echo e($index+1); ?>. <?php echo e($value->sub_kepribadian); ?> <?php if(!$loop->last): ?> <br/>&nbsp; <?php endif; ?> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                        </td>
                        
                    </tr>
                </table>
                <hr />
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td><strong>Diperkirakan Waktu Mulai Bekerja : Tanggal: <?php echo e(Carbon\Carbon::createFromFormat('Y-m-d', $trxM->tanggal)->format('d-m-Y')); ?></strong></td>
                    </tr>
                </table>
                <hr />
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td>Keterangan Lain:<br/><?php echo nl2br($trxM->keterangan_lain); ?></td>
                    </tr>
                </table>
                <hr />
                <?php echo $htmlLog; ?>

            </td>
        </tr>
    </table>
</body>

</html><?php /**PATH C:\xampp\htdocs\matta\resources\views/formulir/kebutuhan/pdf.blade.php ENDPATH**/ ?>