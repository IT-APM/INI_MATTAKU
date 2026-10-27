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
                    <h4><img src="<?php echo e(asset('assets/images/matta/form-icon-25.jpg')); ?>" height="30" alt="Alita" />&nbsp;Preview Formulir Permohonan Cuti</h4>
                </div>
            </div>
        </div>
        
        <div class="col-sm-12">
            <div class="card">
                
                    <?php if(!$cek): ?>
                    <div class="alert alert-danger dark alert-dismissible fade show" role="alert">
                        <!-- <img class="img-50" src="<?php echo e(asset('assets/images/other-images/sad.png')); ?>" alt="error"> -->
                        <h4>ERROR:</h4>
                        <?php if($arr[0]['status']): ?> <h5>
                            <?php echo e($arr[0]['message']); ?> - OK</h5>
                        <?php else: ?> <h5><?php echo e($arr[0]['message']); ?> - NOK</h5> <?php endif; ?>
                        <?php if($arr[1]['status']): ?> <h5>
                            <?php echo e($arr[1]['message']); ?> - OK</h5>
                        <?php else: ?> <h5><?php echo e($arr[1]['message']); ?> - NOK</h5> <?php endif; ?>
                        <?php if($arr[2]['status']): ?> <h5>
                            <?php echo e($arr[2]['message']); ?> - OK</h5>
                        <?php else: ?> <h5><?php echo e($arr[2]['message']); ?> - NOK</h5> <?php endif; ?>
                        <?php if($arr[3]['status']): ?> <h5>
                            <?php echo e($arr[3]['message']); ?> - OK</h5>
                        <?php else: ?> <h5><?php echo e($arr[3]['message']); ?> - NOK</h5> <?php endif; ?>
                        <?php if($arr[4]['status']): ?> <h5>
                            <?php echo e($arr[4]['message']); ?> - OK</h5>
                        <?php else: ?> <h5><?php echo e($arr[4]['message']); ?> - NOK</h5> <?php endif; ?>
                        <?php if($arr[5]['status']): ?> <h5>
                            <?php echo e($arr[5]['message']); ?> - OK</h5>
                        <?php else: ?> <h5><?php echo e($arr[5]['message']); ?> - NOK</h5> <?php endif; ?>
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php endif; ?>
                
            </div>
        </div>
        <?php $data = json_decode($trx->karyawan_property, true); ?>
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
                                <td><?php echo e($data['divisi'] . '/' . $data['dep'] . '/' . $data['sub_dep']); ?></td>
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
                                <td width="70%"><?php echo e($trx->_tipe->tipe_cuti); ?></td>
                            </tr>
                            <tr>
                                <td>Tahun Periode Cuti</td>
                                <td><?php echo e($trx->periode_cuti); ?></td>
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
                                <td width="70%"><?php echo e($arrTanggal['tanggal_mulai']); ?><br />sampai dengan<br /><?php echo e($arrTanggal['tanggal_akhir']); ?></td>
                            </tr>
                            <tr>
                                <td>Jumlah hari cuti</td>
                                <td><?php echo e($trx->jumlah_hari); ?></td>
                            </tr>
                            
                            <?php if($trx->tipe_cuti == 12): ?>
                            
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
                                <td><?php echo e($trx->alasan); ?></td>
                            </tr>
                            <tr>
                                <td>Alamat</td>
                                <td><?php echo e($trx->alamat); ?></td>
                            </tr>
                            <tr>
                                <td>Nomor telepon</td>
                                <td><?php echo e($trx->telepon); ?></td>
                            </tr>
                            <tr>
                                <td>Keterangan</td>
                                <td><?php echo $trx->keterangan; ?></td>
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
                                <td width="70%"><?php echo e($trx->_tipe->tipe_cuti); ?></td>
                            </tr>
                            <tr>
                                <td>Tahun Periode Cuti</td>
                                <td><?php echo e($trx->periode_cuti); ?></td>
                            </tr>
                            <tr>
                                <td>Jumlah hari cuti</td>
                                <td><?php echo e($trx->jumlah_hari); ?></td>
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
            </div>
        </div>
        
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body typography">
                    <a href="javascript:window.close()" class="btn btn-warning" type="button">Close</a>
                </div>
            </div>
        </div>
        <!--  -->
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.errors.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/formulir/cuti/preview.blade.php ENDPATH**/ ?>