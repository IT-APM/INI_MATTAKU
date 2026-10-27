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
                        <td width="50%"><div class="center" style="font-weight: bold; font-size: 12px">FORM PENGAJUAN PELATIHAN</div></td>
                        <td width="25%"><div class="center">No: <?php echo e($trx->id); ?></div></td>
                    </tr>
                </table>
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td align="center"><strong>DIAJUKAN OLEH</strong></td>
                    </tr>
                </table>
                <hr/>
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td width="30%">Nama<br/>NIP<br/>Jabatan<br/>Direktorat / Divisi / Departemen</td>
                        <td width="70%">: <?php echo e($pengaju['nama']); ?><br/>: <?php echo e($pengaju['nik']); ?><br/>:
                            <?php echo e($pengaju['jabatan']); ?><br/>: <?php echo e($pengaju['divisi']); ?> / <?php echo e($pengaju['dep']); ?> /
                            <?php echo e($pengaju['sub_dep']); ?></td>
                    </tr>
                </table>
                <hr/>
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
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
                    <?php $__currentLoopData = $arrPeserta; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($value['nama']); ?> / <?php echo e($value['nik']); ?></td>
                        <td><?php echo e($value['divisi']); ?> / <?php echo e($value['dep']); ?> / <?php echo e($value['sub_dep']); ?></td>
                        <td><?php echo e($value['jabatan']); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                        <td width="68%"><?php echo e($trx->ke); ?></td>
                    </tr>
                    <tr>
                        <td>Tahun</td>
                        <td>:</td>
                        <td><?php echo e($trx->tahun); ?></td>
                    </tr>
                    <tr>
                        <td>Jenis</td>
                        <td>:</td>
                        <td><?php $__currentLoopData = $arrJenis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php echo e($value->jenis); ?> <?php if(!$loop->last): ?> <br/> <?php endif; ?> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></td>
                    </tr>
                    <?php if(!empty($trx->int_ext)): ?>
                    <tr>
                        <td>Mode</td>
                        <td>:</td>
                        <td><?php echo e($_int_ext); ?></td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <td>Judul</td>
                        <td>:</td>
                        <td><?php echo e($trx->judul); ?></td>
                    </tr>
                    <tr>
                        <td>Penyelenggara</td>
                        <td>:</td>
                        <td><?php echo e($trx->penyelenggara); ?></td>
                    </tr>
                    <?php
                    // $tanggal_range = [];
                    // if(!empty($trx->tanggal_range)){
                        $tanggal_range = json_decode($trx->tanggal_range, true);
                        $formatted_ranges = array_map(function($range) {
                            [$mulai, $selesai] = array_map('trim', explode('s/d', $range));
                            return Carbon\Carbon::createFromFormat('Y-m-d', $mulai)->format('d-m-Y') . ' s/d ' . Carbon\Carbon::createFromFormat('Y-m-d', $selesai)->format('d-m-Y');
                        }, $tanggal_range);
                    // }
                    ?>
                    <tr>
                        <td>Tanggal Pelaksanaan</td>
                        <td>:</td>
                        
                        <td><?php $__currentLoopData = $formatted_ranges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $_tanggal_range): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php echo e($_tanggal_range); ?> <?php if(!$loop->last): ?> <br/> <?php endif; ?> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></td>
                        
                    </tr>
                    <tr>
                        <td>Tempat</td>
                        <td>:</td>
                        <td><?php echo e($trx->tempat); ?></td>
                    </tr>
                    <tr>
                        <td>Biaya</td>
                        <td>:</td>
                        <td>Rp. <?php echo e(number_format($trx->biaya, 0)); ?></td>
                    </tr>
                    <tr>
                        <td>Benefit</td>
                        <td>:</td>
                        <td><?php $__currentLoopData = $arrBenefit; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php echo e($value->benefit); ?> <?php if(!$loop->last): ?> <br/> <?php endif; ?> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></td>
                    </tr>
                    <tr>
                        <td>Anggaran</td>
                        <td>:</td>
                        <td><?php echo e($trx->_anggaran->anggaran); ?></td>
                    </tr>
                    <tr>
                        <td>Keterangan</td>
                        <td>:</td>
                        <td><?php echo $trx->keterangan; ?></td>
                    </tr>
                    <tr>
                        <td>Catatan</td>
                        <td>:</td>
                        <td><?php echo $trx->catatan; ?></td>
                    </tr>
                </table>
                <hr/>
                <?php
                $log = App\Models\TrxPelatihanLog::where(['id_trx' => $trx->id])->whereNotIn('id_status', [1])->orderBy('id_trx', 'desc')->get();
                ?>
                <?php if(!empty($log)): ?>
                <?php $__currentLoopData = $log; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $_log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if(!empty($_log->keterangan)): ?>
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    
                    <tr>
                        <td width="30%">Catatan</td>
                        <td width="2%">:</td>
                        <td width="68%"><?php echo e(App\Models\Karyawan::where(['nik'=>$_log->nik_pic])->first()->nama); ?> / <?php echo e($_log->nik_pic); ?><br/><?php echo e($_log->keterangan); ?></td>
                    </tr>
                </table>
                <hr/>
                <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
                <?php echo $htmlLog; ?>

            </td>
        </tr>
    </table>
</body>
<!-- finish -->

</html><?php /**PATH C:\xampp\htdocs\matta\resources\views/formulir/pelatihan/pdf.blade.php ENDPATH**/ ?>