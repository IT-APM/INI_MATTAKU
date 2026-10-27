<?php $__env->startSection('title', 'Formulir Permohonan Cuti'); ?>

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
                    <h4><img src="<?php echo e(asset('assets/images/matta/form-icon-25.jpg')); ?>" height="30" alt="Alita" />&nbsp;Formulir Permohonan Cuti</h4>
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
                    <a href="<?php echo e(asset('assets/images/matta/karyawan/'.$data['nik'].'/'.$data['nik'].'.jpg')); ?>"
                        target="_blank"><img class="img-thumbnail" width="20%"
                            src="<?php echo e(asset('assets/images/matta/karyawan/'.$data['nik'].'/'.$data['nik'].'.jpg')); ?>"
                            alt="user"></a>
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
                    <h5>TIPE CUTI</h5>
                </div>
                <div class="card-body typography">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <td width="30%">Tipe Cuti</td>
                                <td width="70%"><?php echo e($trxCuti->_tipe->tipe_cuti); ?></td>
                            </tr>
                            <tr>
                                <td>Tahun Periode Cuti</td>
                                <td><?php echo e($trxCuti->periode_cuti); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!--  -->
                <div class="card-header">
                    <h5>MASA CUTI</h5>
                </div>
                <div class="card-body typography">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <td width="30%">Mengajukan cuti pada</td>
                                <td width="70%"><?php echo e($arrTanggal['tanggal_mulai']); ?><br />sampai
                                    dengan<br /><?php echo e($arrTanggal['tanggal_akhir']); ?></td>
                            </tr>
                            <tr>
                                <td>Jumlah hari cuti</td>
                                <td><?php echo e($trxCuti->jumlah_hari); ?></td>
                            </tr>
                            
                            <?php if($trxCuti->tipe_cuti == 12): ?>
                            
                            <tr>
                                <td>Tanggal sebelum</td>
                                <td>
                                    <?php for($x = 0; $x <5; $x++): ?>
                                        <?php echo e($arrTanggal['arrTanggalCuti'][$x]); ?><br />
                                    <?php endfor; ?>
                                    
                                </td>
                            </tr>
                            
                            <tr>
                                <td>Tanggal umroh</td>
                                <td>
                                    <?php for($x = 5; $x <12; $x++): ?>
                                        <?php echo e($arrTanggal['arrTanggalCuti'][$x]); ?><br />
                                    <?php endfor; ?>
                                    
                                </td>
                            </tr>
                            
                            <tr>
                                <td>Tanggal sesudah</td>
                                <td>
                                    <?php for($x = 12; $x <15; $x++): ?>
                                        <?php echo e($arrTanggal['arrTanggalCuti'][$x]); ?><br />
                                    <?php endfor; ?>
                                    
                                </td>
                            </tr>
                            <?php else: ?>
                            <tr>
                                <td>Tanggal cuti</td>
                                <td>
                                    <?php $__currentLoopData = $arrTanggal['arrTanggalCuti']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php echo e($value); ?><br />
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </td>
                            </tr>
                            <?php endif; ?>
                            <tr>
                                <td>Masuk bekerja kembali</td>
                                <td><?php echo e($arrTanggal['tanggal_masuk']); ?></td>
                            </tr>
                            <tr>
                                <td>Alasan pengajuan cuti</td>
                                <td><?php echo e($trxCuti->alasan); ?></td>
                            </tr>
                            <tr>
                                <td>Alamat</td>
                                <td><?php echo e($trxCuti->alamat); ?></td>
                            </tr>
                            <tr>
                                <td>Nomor telepon</td>
                                <td><?php echo e($trxCuti->telepon); ?></td>
                            </tr>
                            <tr>
                                <td>Keterangan</td>
                                <td><?php echo $trxCuti->keterangan; ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!--  -->
                <div class="card-header">
                    <h5>RESUME</h5>
                </div>
                <div class="card-body typography">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <td width="30%">Tipe Cuti</td>
                                <td width="70%"><?php echo e($trxCuti->_tipe->tipe_cuti); ?></td>
                            </tr>
                            <tr>
                                <td>Tahun Periode Cuti</td>
                                <td><?php echo e($trxCuti->periode_cuti); ?></td>
                            </tr>
                            <tr>
                                <td>Jumlah hari cuti</td>
                                <td><?php echo e($trxCuti->jumlah_hari); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
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
                    <a href="<?php echo e(asset('assets/images/matta/karyawan/'.$value['nik'].'/'.$value['nik'].'.jpg')); ?>"
                        target="_blank"><img class="img-thumbnail" width="20%"
                            src="<?php echo e(asset('assets/images/matta/karyawan/'.$value['nik'].'/'.$value['nik'].'.jpg')); ?>"
                            alt="user"></a>
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
                            array_push($remains, [4=>$trxCuti->nik_atasan]);
                            foreach(explode(';', $trxCuti->pic_pengganti) as $value){
                                array_push($remains, [2=>$value]);
                            }
                            array_push($remains, [1=>$trxCuti->nik_karyawan]);
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
                                <p class="d-flex justify-content-between mb-2"><span class="date-content light-background"><?php echo e($_history->created_at); ?></span></p>
                                <h6><?php echo e($_history->_status->status_cuti); ?><span class="dot-notification"></span></h6>
                                <p class="f-light">Oleh <?php echo e($_history->_pic->nama); ?> / <?php echo e($_history->nik_pic); ?></p>
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
                    <button type="submit" name="status_cuti" value="2" class="btn btn-success">Approve</button>
                    <button type="submit" name="status_cuti" value="7" class="btn btn-danger">Reject</button>
                </form>
                </div>
            </div>
        </div>
        <?php elseif($pic == 'nik_atasan'): ?>
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body typography">
                    <h5>Catatan</h5>
                    <form id="myform" name="myform" method="post" enctype="multipart/form-data" action="<?php echo e($linkAction); ?>" target="_self" onsubmit="return confirm('Are you sure want to submit this data?');">
                        <?php echo method_field('PATCH'); ?>
                        <?php echo csrf_field(); ?>
                        <input type="hidden" id="nik" name="nik" value="<?php echo e($nik); ?>">
                        <input type="hidden" id="pic" name="pic" value="<?php echo e($pic); ?>">
                        <textarea class="form-control" id="catatan" name="catatan" rows="4" ></textarea>
                        <br/>
                        <button type="submit" name="status_cuti" value="4" class="btn btn-success">Approve</button>
                        <button type="submit" name="status_cuti" value="5" class="btn btn-danger">Reject</button>
                    </form>
                </div>
            </div>
        </div>
        <?php elseif($pic == 'nik_hco'): ?>
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body typography">
                <form id="myform" name="myform" method="post" enctype="multipart/form-data" action="<?php echo e($linkAction); ?>" target="_self" onsubmit="return confirm('Are you sure want to submit this data?');">
                    <?php echo method_field('PATCH'); ?>
                    <?php echo csrf_field(); ?>
                    <input type="hidden" id="nik" name="nik" value="<?php echo e($nik); ?>">
                    <input type="hidden" id="pic" name="pic" value="<?php echo e($pic); ?>">
                    <button type="submit" name="status_cuti" value="6" class="btn btn-success">Informed</button>
                </form>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <!--  -->
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body typography text-center">
                    <h6>Terima kasih untuk partisipasi dan kerjasamanya untuk melakukan review Formulir Permohonan Cuti
                        ini.</h6>
                    <a href="https://alita.id/"><img src="<?php echo e(asset('assets/images/matta/Alita-New-Log.png')); ?>"
                            height="40" alt="Alita" /></a><br />
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
<?php echo $__env->make('layouts.errors.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/emails/cuti.blade.php ENDPATH**/ ?>