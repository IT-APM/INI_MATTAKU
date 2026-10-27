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
                        <td width="25%">&nbsp;<img src="<?php echo e(public_path('assets/images/matta/alita_new.png')); ?>" alt="Logo Alita"></td>
                        <td width="50%"><div class="center" style="font-weight: bold; font-size: 12px">FORM REIMBURSEMENT</div></td>
                        <td width="25%"><div class="center">No: <?php echo e($trxM->id); ?></div></td>
                    </tr>
                </table>
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td align="center"><strong>DIAJUKAN OLEH</strong></td>
                    </tr>
                </table>
                <hr />
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td width="30%">Nama<br />NIP<br />Jabatan<br />Direktorat / Divisi / Departemen</td>
                        <td width="70%">: <?php echo e($karyawan['nama']); ?><br />: <?php echo e($karyawan['nik']); ?><br />:
                            <?php echo e($karyawan['jabatan']); ?><br />: <?php echo e($karyawan['divisi']); ?> / <?php echo e($karyawan['dep']); ?> /
                            <?php echo e($karyawan['sub_dep']); ?> </td>
                    </tr>
                </table>
                <hr />
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td align="center"><strong>PASIEN</strong></td>
                    </tr>
                </table>
                <table width="100%" border="1" cellspacing="0" cellpadding="2">
                    <tr>
                        <th align="center" width="28%">Nama</th>
                        <th align="center" width="18%">Hubungan Dengan Karyawan</th>
                        <th align="center" width="18%">Layanan</th>
                        <th align="center" width="18%">Tanggal Layanan</th>
                        <th align="center" width="18%">Nominal Klaim</th>
                    </tr>
                    <?php $__currentLoopData = $trxD; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($value->id_layanan != 1): ?>
                    <tr>
                        <td><?php echo e(html_entity_decode($value->nama_pasien)); ?></td>
                        <td><?php echo e($value->_id_hubungan->hubungan); ?></td>
                        <td><?php echo e($value->_id_layanan->layanan); ?></td>
                        <td><?php echo e(date("d-m-Y", strtotime($value->tanggal))); ?></td>
                        <td align="right">Rp. <?php echo e(number_format($value->jumlah_klaim, 0)); ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td colspan="4" align="right">Total Klaim</td>
                        <td align="right">Rp. <?php echo e(number_format($trxM->klaim_non_kesehatan, 0)); ?></td>
                    </tr>
                </table>
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td align="center"><strong>DIISI OLEH <?php echo e(strtoupper(App\Models\MtrUtility::find(51)->informasi)); ?></strong></td>
                    </tr>
                </table>
                <hr />
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td width="30%">Pengajuan Ke</td>
                        <td width="70%">: <?php echo e($ke); ?></td>
                    </tr>
                    <tr>
                        <td>Tahun</td>
                        <td>: <?php echo e($trxM->periode); ?></td>
                    </tr>
                    <?php if($trxM->plafon>0): ?>
                    <tr>
                        <td>Plafon</td>
                        <td>: Ada</td>
                    </tr>
                    <?php endif; ?>
                    <?php if($isHC == true): ?>
                    <?php if($trxM->plafon>0): ?>
                    <tr>
                        <td>Jumlah Plafon</td>
                        <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <?php $__currentLoopData = $trxD; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($value->id_layanan == 2): ?>
                                <tr>
                                    <td width="25%">: <?php echo e($value->_id_layanan->layanan); ?></td>
                                    <td width="75%">: Rp. <?php echo e(number_format($plafon->frame, 0)); ?></td>
                                </tr>
                                <?php endif; ?>
                                <?php if($value->id_layanan == 3): ?>
                                <tr>
                                    <td width="25%">: <?php echo e($value->_id_layanan->layanan); ?></td>
                                    <td width="75%">: Rp. <?php echo e(number_format($plafon->lensa, 0)); ?></td>
                                </tr>
                                <?php endif; ?>
                                <?php if($value->id_layanan == 4): ?>
                                <tr>
                                    <td width="25%">: <?php echo e($value->_id_layanan->layanan); ?></td>
                                    <td width="75%">: Rp. <?php echo e(number_format($plafon->kehamilan, 0)); ?></td>
                                </tr>
                                <?php endif; ?>
                                <?php if($value->id_layanan == 5): ?>
                                <tr>
                                    <td width="25%">: <?php echo e($value->_id_layanan->layanan); ?></td>
                                    <td width="75%">: Rp. <?php echo e(number_format($plafon->persalinan_normal, 0)); ?></td>
                                </tr>
                                <?php endif; ?>
                                <?php if($value->id_layanan == 6): ?>
                                <tr>
                                    <td width="25%">: <?php echo e($value->_id_layanan->layanan); ?></td>
                                    <td width="75%">: Rp. <?php echo e(number_format($plafon->persalinan_caesar, 0)); ?></td>
                                </tr>
                                <?php endif; ?>
                                <?php if($value->id_layanan == 7): ?>
                                <tr>
                                    <td width="25%">: <?php echo e($value->_id_layanan->layanan); ?></td>
                                    <td width="75%">&nbsp;</td>
                                </tr>
                                <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td>Plafon Telah Diambil</td>
                        <td>: Rp. <?php echo e(number_format(0, 0)); ?> (Klaim Non Kesehatan)</td>
                    </tr>
                    <?php endif; ?>
                    <?php endif; ?>
                    <tr>
                        <td>Nominal Klaim Pada Pengajuan ini</td>
                        <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td width="25%">: Non Kesehatan</td>
                                    <td width="75%">: Rp. <?php echo e(number_format($trxM->klaim_non_kesehatan, 0)); ?></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <?php if($isHC == true): ?>
                    <?php if($trxM->plafon>0): ?>
                    <tr>
                        <td>Saldo</td>
                        <td>: Rp. <?php echo e(number_format(0, 0)); ?> (Plafon Non Kesehatan)</td>
                    </tr>
                    <?php endif; ?>
                    <?php endif; ?>
                    <tr>
                        <td>Lampiran Pengajuan</td>
                        <td><?php $__currentLoopData = $lampiran; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							: <?php echo e($value->_id_lampiran->lampiran); ?><br/>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </td>
                    </tr>
                </table>
                <hr />
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td align="center"><strong>KETERANGAN</strong></td>
                    </tr>
                </table>
                <hr />
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td align="left"><?php echo e($trxM->catatan); ?></td>
                    </tr>
                </table>
                <hr />
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td align="center"><strong>CATATAN OLEH <?php echo e(strtoupper(App\Models\MtrUtility::find(51)->informasi)); ?></strong></td>
                    </tr>
                </table>
                <hr />
                <?php
                    $where = ['id_trx' => $trxM->id, 'nik_pic' => App\Models\MtrUtility::find(12)->informasi];
                    $info = App\Models\TrxReimburseLog::where($where)->orderBy('id', 'desc')->first();
                ?>
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td align="left"><?php if(!empty($info)): ?> <?php echo e($info->keterangan); ?> <?php endif; ?></td>
                    </tr>
                </table>
                <hr />
                <?php echo $htmlReimburseLog; ?>

            </td>
        </tr>
    </table>
</body>
<!-- finish -->

</html><?php /**PATH C:\xampp\htdocs\matta\resources\views/formulir/reimburse/pdf_non_kes.blade.php ENDPATH**/ ?>