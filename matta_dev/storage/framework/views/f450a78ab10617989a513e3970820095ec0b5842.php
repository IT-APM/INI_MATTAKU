<?php $__env->startSection('title', 'Formulir Pengajuan Pelatihan'); ?>

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
                        <h4><img src="<?php echo e(asset('assets/images/matta/form-icon-25.jpg')); ?>" height="30" alt="Alita" />&nbsp;Formulir Pengajuan Pelatihan</h4>
                    </div>
                </div>
            </div>
        </div>
        <!--  -->
        <div class="row">
            <div class="col-sm-4">
                <div class="card">
                    <div class="card-header">
                        <h5>Data Pengaju</h5>
                    </div>
                    <div class="card-header">
                        <a href="<?php echo e(asset('assets/images/matta/karyawan/'.$pengaju['nik'].'/'.$pengaju['nik'].'.jpg')); ?>"
                            target="_blank"><img class="img-thumbnail" width="20%"
                                src="<?php echo e(asset('assets/images/matta/karyawan/'.$pengaju['nik'].'/'.$pengaju['nik'].'.jpg')); ?>"
                                alt="user"></a>
                    </div>
                    <div class="card-body typography">
                        <table class="table table-striped">
                            <tbody>
                                <tr>
                                    <td width="30%">Nama</td>
                                    <td width="70%"><?php echo e($pengaju['nama']); ?></td>
                                </tr>
                                <tr>
                                    <td>NIP</td>
                                    <td><?php echo e($pengaju['nik']); ?></td>
                                </tr>
                                <tr>
                                    <td>Jabatan</td>
                                    <td><?php echo e($pengaju['jabatan']); ?></td>
                                </tr>
                                <tr>
                                    <td>Divisi/SBU/Unit Kerja</td>
                                    <td><?php echo e($pengaju['divisi'] . '/' . $pengaju['dep'] . '/' . $pengaju['sub_dep']); ?>

                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!--  -->
            <div class="col-sm-4">
                <div class="card">
                    <div class="card-header">
                        <h5>Data Peserta</h5>
                    </div>
                    <?php $__currentLoopData = $arrPeserta; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <!-- start -->
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
                                    <td>Jabatan</td>
                                    <td><?php echo e($value['jabatan']); ?></td>
                                </tr>
                                <tr>
                                    <td>Direktorat/Divisi/Departemen</td>
                                    <td><?php echo e($value['divisi'] . '/' . $value['dep'] . '/' . $value['sub_dep']); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- end -->
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <!--  -->
            <div class="col-sm-4">
                <div class="card">
                    <div class="card-header">
                        <h5>Pelatihan</h5>
                    </div>
                    <div class="card-body typography">
                        <table class="table table-striped">
                            <tbody>
                                <tr>
                                    <td width="30%">Pengajuan Pelatihan ke</td>
                                    <td width="70%"><?php echo e($trx->ke); ?></td>
                                </tr>
                                <tr>
                                    <td>Tahun</td>
                                    <td><?php echo e($trx->tahun); ?></td>
                                </tr>
                                <tr>
                                    <td>Jenis</td>
                                    <td>
                                        <?php $__currentLoopData = $arrJenis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php echo e($value->jenis); ?> <?php if(!$loop->last): ?> <br/> <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </td>
                                </tr>
                                <?php if(!empty($trx->int_ext)): ?>
                                <tr>
                                    <td>Mode</td>
                                    <td>
                                        <?php $__currentLoopData = $int_ext; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($key == $trx->int_ext): ?> <?php echo e($value); ?> <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </td>
                                </tr>
                                <?php endif; ?>
                                <tr>
                                    <td>Judul</td>
                                    <td><?php echo e($trx->judul); ?></td>
                                </tr>
                                <tr>
                                    <td>Penyelenggara</td>
                                    <td><?php echo e($trx->penyelenggara); ?></td>
                                </tr>
                                <?php
                                $tanggal_range = [];
                                if(!empty($trx->tanggal_range)){
                                    $tanggal_range = json_decode($trx->tanggal_range, true);
                                    $formatted_ranges = array_map(function($range) {
                                        [$mulai, $selesai] = array_map('trim', explode('s/d', $range));
                                        return Carbon\Carbon::createFromFormat('Y-m-d', $mulai)->format('d-m-Y') . ' s/d ' . Carbon\Carbon::createFromFormat('Y-m-d', $selesai)->format('d-m-Y');
                                    }, $tanggal_range);
                                }
                                ?>
                                <tr>
                                    <td>Tanggal Pelaksanaan</td>
                                    
                                    <?php if(empty($trx->tanggal_range)): ?>
                                    <td><?php echo e(Carbon\Carbon::createFromFormat('Y-m-d', $trx->tanggal_mulai)->format('d-m-Y')); ?> s/d <?php echo e(Carbon\Carbon::createFromFormat('Y-m-d', $trx->tanggal_akhir)->format('d-m-Y')); ?></td>
                                    <?php else: ?>
                                    <td><?php $__currentLoopData = $formatted_ranges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $_tanggal_range): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php echo e($_tanggal_range); ?> <?php if(!$loop->last): ?> <br/> <?php endif; ?> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></td>
                                    <?php endif; ?>
                                </tr>
                                <tr>
                                    <td>Tempat</td>
                                    <td><?php echo e($trx->tempat); ?></td>
                                </tr>
                                <tr>
                                    <td>Biaya</td>
                                    <td>Rp. <?php echo e(number_format($trx->biaya, 0)); ?></td>
                                </tr>
                                <tr>
                                    <td>Benefit</td>
                                    <td>
                                        <?php $__currentLoopData = $arrBenefit; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php echo e($value->benefit); ?> <?php if(!$loop->last): ?> <br/> <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Anggaran</td>
                                    <td><?php echo e($trx->_anggaran->anggaran); ?></td>
                                </tr>
                                <tr>
                                    <td>Keterangan</td>
                                    <td><?php echo e($trx->keterangan); ?></td>
                                </tr>
                                <?php $filePath = public_path('assets/pelatihan/' . $trx->lampiran); ?>
                                <tr>
                                    <td>Lampiran</td>
                                    <td>
                                        <?php if(!empty($trx->lampiran) && file_exists($filePath)): ?>
                                            <a href="<?php echo e(asset('assets/pelatihan/' . $trx->lampiran)); ?>" target="_blank">Lihat Lampiran</a>
                                        <?php else: ?>
                                            Tidak ada lampiran
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Catatan</td>
                                    <td><?php echo $trx->catatan; ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-header">
                        <h5>Pelatihan</h5>
                    </div>
                    <div class="card-body typography notification">
                        <ul>
                            <?php
                                // status
                                // 1=Submit by Requestor
                                // 2=Approve by L1 Requestor
                                // 5=Approve by L1 Participants
                                // 10=Approve by Reviewer 1
                                // 14=Approve by Reviewer 2
                                // 11=Done by HC
                                $remains = [];
                                if(!empty($trx->reviewer2)){
                                    array_push($remains, [14=>$trx->reviewer2]);
                                }
                                if(!empty($trx->reviewer1)){
                                    array_push($remains, [10=>$trx->reviewer1]);
                                }
                                foreach(explode(';', $trx->atasan_peserta) as $value){
                                    array_push($remains, [5=>$value]);
                                }
                                array_push($remains, [2=>$trx->atasan_pengaju]);
                            ?>
                            <?php $__currentLoopData = $remains; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $remain): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php $__currentLoopData = $remain; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($history->where('id_status', $key)->where('nik_pic', $value)->isEmpty()): ?>
                                <li class="d-flex">
                                    <div class="activity-dot-dark"></div>
                                    <div class="ms-3">
                                        <h6 class="f-light" style="color: #adb5bd;"><?php echo e(App\Models\PelatihanStatus::find($key)->status_pengajuan); ?></h6>
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
                                    <h6><?php echo e($_history->_status->status_pengajuan); ?><span
                                            class="dot-notification"></span></h6>
                                    <p class="f-light">Oleh <?php echo e($_history->_pic->nama); ?> /
                                        <?php echo e($_history->nik_pic); ?></p>
                                </div>
                            </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!--  -->
        <div class="row">
            <div class="col-sm-12">
            <div class="card">
                <div class="card-body typography">
                <h5>Catatan</h5>
                <form id="myform" name="myform" method="post" enctype="multipart/form-data" action="<?php echo e($linkAction); ?>" target="_self" onsubmit="return confirm('Are you sure want to submit this data?');">
                    <?php echo method_field('PATCH'); ?>
                    <?php echo csrf_field(); ?>
                    <input type="hidden" id="nik" name="nik" value="<?php echo e($nik); ?>">
                    <input type="hidden" id="pic" name="pic" value="<?php echo e($pic); ?>">
                    
                    <textarea class="form-control mb-2" id="keterangan" name="keterangan" rows="4" ></textarea>
                    
                    <button type="submit" name="status_pengajuan" value="<?php echo e($approve); ?>" class="btn btn-success">Approve</button>
                    <button type="submit" name="status_pengajuan" value="<?php echo e($reject); ?>" class="btn btn-danger">Reject</button>
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
                        <h6>Terima kasih untuk partisipasi dan kerjasamanya untuk melakukan review Formulir Rekomendasi
                            Karyawan
                            ini.</h6>
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
    <input type="hidden" id="status_pengajuan" name="status_pengajuan" value="<?php echo e($approve); ?>">
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
    <input type="hidden" id="status_pengajuan" name="status_pengajuan" value="<?php echo e($reject); ?>">
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
<?php echo $__env->make('layouts.errors.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/emails/pelatihan.blade.php ENDPATH**/ ?>