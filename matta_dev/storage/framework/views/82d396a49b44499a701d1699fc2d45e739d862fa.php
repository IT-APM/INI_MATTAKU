<?php $__env->startSection('title', 'Formulir Izin'); ?>

<?php $__env->startSection('css'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <!--  -->
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h4><img src="<?php echo e(asset('assets/images/matta/form-icon-25.jpg')); ?>" height="30" alt="Alita" />&nbsp;Formulir Izin</h4>
                    </div>
                </div>
            </div>
        </div>
        <!--  -->
        <div class="row">
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Data Karyawan</h5>
                    </div>
                    <div class="card-header">
                        <a href="<?php echo e(asset('assets/images/matta/karyawan/'.$karyawan['nik'].'/'.$karyawan['nik'].'.jpg')); ?>"
                            target="_blank"><img class="img-thumbnail" width="20%"
                                src="<?php echo e(asset('assets/images/matta/karyawan/'.$karyawan['nik'].'/'.$karyawan['nik'].'.jpg')); ?>"
                                alt="user"></a>
                    </div>
                    <div class="card-body typography">
                        <table class="table table-striped">
                            <tbody>
                                <tr>
                                    <td width="30%">Nama</td>
                                    <td width="70%"><?php echo e($karyawan['nama']); ?></td>
                                </tr>
                                <tr>
                                    <td>NIP</td>
                                    <td><?php echo e($karyawan['nik']); ?></td>
                                </tr>
                                <tr>
                                    <td>Jabatan</td>
                                    <td><?php echo e($karyawan['jabatan']); ?></td>
                                </tr>
                                <tr>
                                    <td>Direktorat / Divisi / Departemen</td>
                                    <td><?php echo e($karyawan['divisi'] . '/' . $karyawan['dep'] . '/' . $karyawan['sub_dep']); ?>

                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!--  -->
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Data Izin</h5>
                    </div>
                    <div class="card-body typography">
                        <table class="table table-striped">
                            <tbody>
                                <tr>
                                    <td width="40%">Tipe Izin</td>
                                    <td width="60%"><?php echo e($trx->_tipe->tipe); ?></td>
                                </tr>
                                <tr>
                                    <td>Hari, Tanggal</td>
                                    <td><?php echo e(App\Http\Controllers\TrxIjinController::getTanggalIndonesia($trx->tanggal)); ?></td>
                                </tr>
                                <?php if($trx->tipe == 1 || $trx->tipe == 2): ?>
                                <tr>
                                    <td>Jam Mulai</td>
                                    <td><?php echo e($trx->jam_awal); ?></td>
                                </tr>
                                <tr>
                                    <td>Jam Selesai</td>
                                    <td><?php echo e($trx->jam_akhir); ?></td>
                                </tr>
                                <?php endif; ?>
                                <tr>
                                    <td>Alasan</td>
                                    <td><?php echo e($trx->alasan); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-header">
                        <h5>History</h5>
                    </div>
                    <div class="card-body typography notification">
                        <ul>
                            <?php
                                // status
                                // 1=Submit by Requestor
                                // 2=Approve by L1
                                // 6=Approve by L2
                                // 4=Done by P&C
                                $disable=[1,2,6];
                                if(empty($trx->layer2)){
                                    $disable=[1,2];
                                }
                                $remains = array_diff($disable, $history->pluck('id_status')->toArray());
                                rsort($remains);
                            ?>
                            <?php $__currentLoopData = $remains; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $remain): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="d-flex">
                                <div class="activity-dot-dark"></div>
                                <div class="ms-3">
                                    <h6 class="f-light" style="color: #adb5bd;"><?php echo e(App\Models\IjinStatus::find($remain)->status); ?></h6>
                                    <?php if($remain == 2): ?>
                                    <p class="f-light" style="color: #adb5bd;">Oleh <?php echo e(App\Models\Karyawan::where('nik',$trx->atasan)->first()->nama); ?> / <?php echo e($trx->atasan); ?></p>
                                    <?php elseif($remain == 6): ?>
                                    <p class="f-light" style="color: #adb5bd;">Oleh <?php echo e(App\Models\Karyawan::where('nik',$trx->layer2)->first()->nama); ?> / <?php echo e($trx->layer2); ?></p>
                                    <?php endif; ?>
                                </div>
                            </li>
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
                                    <h6><?php echo e($_history->_status->status); ?><span class="dot-notification"></span></h6>
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
        </div><!--  -->
        <div class="row">
            <div class="col-sm-12">
            <div class="card">
                <div class="card-body typography">
                <form id="myform" name="myform" method="post" enctype="multipart/form-data" action="<?php echo e($linkAction); ?>" target="_self" onsubmit="return confirm('Are you sure want to submit this data?');">
                    <?php echo method_field('PATCH'); ?>
                    <?php echo csrf_field(); ?>
                    <input type="hidden" id="nik" name="nik" value="<?php echo e($nik); ?>">
                    <input type="hidden" id="pic" name="pic" value="<?php echo e($pic); ?>">
                    <h5>Catatan</h5>
                    <textarea class="form-control" id="keterangan" name="keterangan" rows="4" ></textarea>
                    <br/>
                    <button type="submit" name="status" value="<?php echo e($approve); ?>" class="btn btn-success">Approve</button>
                    <button type="submit" name="status" value="<?php echo e($reject); ?>" class="btn btn-danger">Reject</button>
                </form>
                </div>
            </div>
            </div>
        </div>
        <!--  -->
        <!-- <div class="row">
            <div class="col-sm-6 text-end">
                <button class="btn btn-success" type="button" data-bs-toggle="modal"
                    data-bs-target="#modalApprove">Approve</button>
            </div>
            <div class="col-sm-6 text-starts">
                <button class="btn btn-danger" type="button" data-bs-toggle="modal"
                    data-bs-target="#modalReject">Reject</button>
            </div>
        </div> -->
        <!--  -->
        <div class="row g-3">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body typography text-center">
                        <h6>Terima kasih untuk partisipasi dan kerjasamanya untuk melakukan review Formulir Izin ini.</h6>
                        <a href="https://alita.id/"><img src="<?php echo e(asset('assets/images/matta/Alita-New-Log.png')); ?>"
                                height="40" alt="Alita" /></a><br />
                        <h6>&reg; PT Alita Praya Mitra - 2021</h6>
                    </div>
                </div>
            </div>
        </div>
        <!--  -->
    </div>
</div>
<!-- Approve -->
<!-- <form id="myform" name="myform" method="post" enctype="multipart/form-data" action="<?php echo e($linkAction); ?>" target="_self">
    <?php echo method_field('PATCH'); ?>
    <?php echo csrf_field(); ?>
    <input type="hidden" id="nik" name="nik" value="<?php echo e($nik); ?>">
    <input type="hidden" id="pic" name="pic" value="<?php echo e($pic); ?>">
    <input type="hidden" id="status" name="status" value="<?php echo e($approve); ?>">
    <div class="modal fade" id="modalApprove" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel2">Catatan</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <textarea class="form-control" id="keterangan" name="keterangan" rows="4"
                            required=""></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-warning" type="button" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Approve</button>
                </div>
            </div>
        </div>
    </div>
</form> -->
<!-- Reject -->
<!-- <form id="myform" name="myform" method="post" enctype="multipart/form-data" action="<?php echo e($linkAction); ?>" target="_self">
    <?php echo method_field('PATCH'); ?>
    <?php echo csrf_field(); ?>
    <input type="hidden" id="nik" name="nik" value="<?php echo e($nik); ?>">
    <input type="hidden" id="pic" name="pic" value="<?php echo e($pic); ?>">
    <input type="hidden" id="status" name="status" value="<?php echo e($reject); ?>">
    <div class="modal fade" id="modalReject" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel2">Catatan</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <textarea class="form-control" id="keterangan" name="keterangan" rows="4"
                            required=""></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-warning" type="button" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject</button>
                </div>
            </div>
        </div>
    </div>
</form> -->
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script type="text/javascript">
// $(document).ready(function() {
//     $("#modalApprove").on('shown.bs.modal', function() {
//         $(this).find('#keterangan').focus();
//     });
//     $("#modalReject").on('shown.bs.modal', function() {
//         $(this).find('#keterangan').focus();
//     });
// });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.errors.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/emails/izin.blade.php ENDPATH**/ ?>