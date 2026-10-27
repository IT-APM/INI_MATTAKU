<?php $__env->startSection('title', 'Formulir Permohonan Izin Sakit'); ?>

<?php $__env->startSection('css'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <!--  -->
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h4><img src="<?php echo e(asset('assets/images/matta/form-icon-25.jpg')); ?>" height="30" alt="Alita" />&nbsp;Formulir Permohonan Izin Sakit</h4>
                </div>
            </div>
        </div>
        <!--  -->
        <div class="col-sm-12 col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h5>DIAJUKAN OLEH</h5>
                </div>
                <div class="card-header">
                    <a href="<?php echo e(asset('assets/images/matta/karyawan/'.$data['nik'].'/'.$data['nik'].'.jpg')); ?>" target="_blank"><img class="img-thumbnail" width="20%" src="<?php echo e(asset('assets/images/matta/karyawan/'.$data['nik'].'/'.$data['nik'].'.jpg')); ?>" alt="user"></a>
                </div>
                <div class="card-body typography">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <td width="30%">Nama</td>
                                <td width="70%"><?php echo e($data['nama']); ?></td>
                            </tr>
                            <tr>
                                <td>NIP</td>
                                <td><?php echo e($data['nik']); ?></td>
                            </tr>
                            <tr>
                                <td>Jabatan</td>
                                <td><?php echo e($data['jabatan']); ?></td>
                            </tr>
                            <tr>
                                <td>Direktorat / Divisi / Departemen</td>
                                <td><?php echo e($data['unit_kerja']); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!--  -->
                <div class="card-header">
                    <h5>IZIN SAKIT</h5>
                </div>
                <div class="card-body typography">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <td width="30%">Tahun Izin Sakit</td>
                                <td width="70%"><?php echo e($trxSakit->periode_sakit); ?></td>
                            </tr>
                            <tr>
                                <td>Mengajukan izin sakit pada</td>
                                <td><?php echo e($arrTanggal['tanggal_mulai']); ?><br />sampai dengan<br /><?php echo e($arrTanggal['tanggal_akhir']); ?></td>
                            </tr>
                            <tr>
                                <td>Tanggal izin sakit</td>
                                <td>
                                    <?php $__currentLoopData = $arrTanggal['arrTanggalSakit']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php echo e($value); ?><br />
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Masuk bekerja kembali</td>
                                <td><?php echo e($arrTanggal['tanggal_masuk']); ?></td>
                            </tr>
                            <tr>
                                <td>Alasan pengajuan izin sakit</td>
                                <td><?php echo e($trxSakit->alasan); ?></td>
                            </tr>
                            <tr>
                                <td>Alamat</td>
                                <td><?php echo e($trxSakit->alamat); ?></td>
                            </tr>
                            <tr>
                                <td>Nomor telepon</td>
                                <td><?php echo e($trxSakit->telepon); ?></td>
                            </tr>
                            <tr>
                                <td>Lampiran</td>
                                <td><a href="<?php echo e(asset('assets/sakit/'.$trxSakit->lampiran)); ?>" target="_blank"><i class="icon-file"></i>&nbsp;Download</a></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!--  -->
            </div>
        </div>
        <!--  -->
        <div class="col-sm-12 col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h5>PERSONEL PENGGANTI</h5>
                </div>
                <?php $__currentLoopData = $arrPicProperty; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="card-header">
                    <a href="<?php echo e(asset('assets/images/matta/karyawan/'.$value['nik'].'/'.$value['nik'].'.jpg')); ?>" target="_blank"><img class="img-thumbnail" width="20%" src="<?php echo e(asset('assets/images/matta/karyawan/'.$value['nik'].'/'.$value['nik'].'.jpg')); ?>" alt="user"></a>
                </div>
                <div class="card-body typography">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <td width="30%">Nama</td>
                                <td width="70%"><?php echo e($value['nama']); ?></td>
                            </tr>
                            <tr>
                                <td>NIP</td>
                                <td><?php echo e($value['nik']); ?></td>
                            </tr>
                            <tr>
                                <td>Email</td>
                                <td><?php echo e($value['email']); ?></td>
                            </tr>
                            <tr>
                                <td>Telepon</td>
                                <td><?php echo e($value['telepon1']); ?></td>
                            </tr>
                            <tr>
                                <td>Jabatan</td>
                                <td><?php echo e($value['jabatan']); ?></td>
                            </tr>
                            <tr>
                                <td>Direktorat / Divisi / Departemen</td>
                                <td><?php echo e($value['divisi']); ?> / <?php echo e($value['dep']); ?> / <?php echo e($value['sub_dep']); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <div class="card-header">
                    <h5>History</h5>
                </div>
                <div class="card-body typography notification">
                    <ul>
                        <?php
                            // status
                            // 1=Pengajuan oleh Karyawan
                            // 2=Disetujui oleh PIC Pengganti
                            // 4=Disetujui oleh Layer 1
                            // 6=Selesai
                            $remains = [];
                            array_push($remains, [4=>$trxSakit->nik_atasan]);
                            foreach(explode(';', $trxSakit->pic_pengganti) as $value){
                                array_push($remains, [2=>$value]);
                            }
                            array_push($remains, [1=>$trxSakit->nik_karyawan]);
                        ?>
                        <?php $__currentLoopData = $remains; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $remain): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php $__currentLoopData = $remain; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($history->where('id_status', $key)->where('nik_pic', $value)->isEmpty()): ?>
                            <li class="d-flex">
                                <div class="activity-dot-dark"></div>
                                <div class="ms-3">
                                    <h6 class="f-light" style="color: #adb5bd;"><?php echo e(App\Models\SakitStatus::find($key)->status_sakit); ?></h6>
                                    <p class="f-light" style="color: #adb5bd;">Oleh <?php echo e(App\Models\Karyawan::where('nik',$value)->first()->nama); ?> / <?php echo e($value); ?></p>
                                </div>
                            </li>
                            <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                    <ul>
                        <?php $__currentLoopData = $history; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $_history): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li class="d-flex">
                            <?php if($loop->first): ?>
                            <div class="activity-dot-success"></div>
                            <?php elseif($loop->last): ?>
                            <div class="activity-dot-warning"></div>
                            <?php else: ?>
                            <div class="activity-dot-primary"></div>
                            <?php endif; ?>
                            <div class="ms-3">
                                <p class="d-flex justify-content-between mb-2"><span
                                        class="date-content light-background"><?php echo e($_history->created_at); ?></span>
                                </p>
                                <h6><?php echo e($_history->_status->status_sakit); ?><span class="dot-notification"></span>
                                </h6>
                                <p class="f-light">Oleh <?php echo e($_history->_pic->nama); ?> /
                                    <?php echo e($_history->nik_pic); ?></p>
                            </div>
                        </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            </div>
        </div>
        <!--  -->
        <?php if($pic == 'pic_pengganti'): ?>
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body typography">
                <form id="myform" name="myform" method="post" enctype="multipart/form-data" action="<?php echo e($linkAction); ?>" target="_self" onsubmit="return confirm('Are you sure want to submit this data?');">
                    <?php echo method_field('PATCH'); ?>
                    <?php echo csrf_field(); ?>
                    <input type="hidden" id="nik" name="nik" value="<?php echo e($nik); ?>">
                    <input type="hidden" id="pic" name="pic" value="<?php echo e($pic); ?>">
                    <button type="submit" name="status_sakit" value="2" class="btn btn-success">Approve</button>
                    <button type="submit" name="status_sakit" value="7" class="btn btn-danger">Reject</button>
                </form>
                </div>
            </div>
        </div>
        <?php elseif($pic == 'nik_atasan'): ?>
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body typography">
                <form id="myform" name="myform" method="post" enctype="multipart/form-data" action="<?php echo e($linkAction); ?>" target="_self" onsubmit="return confirm('Are you sure want to submit this data?');">
                    <?php echo method_field('PATCH'); ?>
                    <?php echo csrf_field(); ?>
                    <input type="hidden" id="nik" name="nik" value="<?php echo e($nik); ?>">
                    <input type="hidden" id="pic" name="pic" value="<?php echo e($pic); ?>">
                    <button type="submit" name="status_sakit" value="4" class="btn btn-success">Approve</button>
                    <button type="submit" name="status_sakit" value="5" class="btn btn-danger">Reject</button>
                </form>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <!--  -->
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body typography text-center">
                    <h6>Terima kasih untuk partisipasi dan kerjasamanya untuk melakukan review Formulir Permohonan Izin Sakit ini.</h6>
                    <a href="https://alita.id/"><img src="<?php echo e(asset('assets/images/matta/Alita-New-Log.png')); ?>" height="40" alt="Alita" /></a><br />
                    <h6>&reg; PT Alita Praya Mitra - 2021</h6>
                </div>
            </div>
        </div>
        <!--  -->
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.errors.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/emails/sakit.blade.php ENDPATH**/ ?>