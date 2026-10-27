<?php $__env->startSection('title', 'Formulir Permohonan Cuti'); ?>

<?php $__env->startSection('css'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
<h3>View</h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Formulir</li>
<li class="breadcrumb-item active">Permohonan Cuti</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row project-cards">
        <div class="col-sm-12">
            <div class="row">
                <!--  -->
                <div class="col-xxl-6 col-md-6 appointment-sec box-col-6">
                    <div class="appointment">
                        <div class="card">
                            <div class="card-header">
                                <h5>Detail</h5>
                            </div>
                            <div class="card-body typography">
                                <table class="table table-striped">
                                    <tbody>
                                        <tr>
                                            <td width="40%">Tipe Cuti</td>
                                            <td width="60%"><?php echo e($trxCuti->_tipe->tipe_cuti); ?></td>
                                        </tr>
                                        <tr>
                                            <td>Tahun Periode Cuti</td>
                                            <td><?php echo e($trxCuti->periode_cuti); ?></td>
                                        </tr>
                                        <tr>
                                            <td>Mengajukan cuti pada</td>
                                            <td><?php echo e($arrTanggal['tanggal_mulai']); ?><br />sampai
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
                                            <td><?php echo $trxCuti->alasan; ?></td>
                                        </tr>
                                        <tr>
                                            <td>Alamat</td>
                                            <td><?php echo $trxCuti->alamat; ?></td>
                                        </tr>
                                        <tr>
                                            <td>Nomor telepon</td>
                                            <td><?php echo e($trxCuti->telepon); ?></td>
                                        </tr>
                                        <tr>
                                            <td>Keterangan</td>
                                            <td><?php echo $trxCuti->keterangan; ?></td>
                                        </tr>
                                        <?php
                                        $where = ['id_trx' => $trxCuti->id];
                                        $catatan = App\Models\TrxCutiLog::where($where)->whereIn('id_status', [4,5])->orderBy('id', 'desc')->first();
                                        ?>
                                        <tr>
                                            <td>Catatan Atasan</td>
                                            <td><?php if(!empty($catatan)): ?><?php echo e($catatan->keterangan); ?><?php endif; ?></td>
                                        </tr>
                                        <?php
                                        $where = ['id_trx' => $trxCuti->id];
                                        $catatan = App\Models\TrxCutiLog::where($where)->whereIn('id_status', [8])->orderBy('id', 'desc')->first();
                                        ?>
                                        <?php if(!empty($catatan)): ?>
                                        <tr>
                                            <td>Catatan <?php echo e(App\Models\MtrUtility::find(51)->informasi); ?></td>
                                            <td><?php echo e($catatan->keterangan); ?></td>
                                        </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!--  -->
                <div class="col-xxl-6 notification box-col-6">
                    <div class="card height-equal">
                        <div class="card-header card-no-border">
                            <div class="header-top">
                                <h5 class="m-0">History</h5>
                            </div>
                        </div>
                        <div class="card-body pt-0">
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
                    
                    <?php
                    $where = ['id_trx' => $trxCuti->id];
                    $catatan = App\Models\TrxCutiLog::where($where)->whereIn('id_status', [4,5])->orderBy('id', 'desc')->first();
                    $isPast = Carbon\Carbon::parse($trxCuti->tanggal_mulai)->isPast();
                    ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('hc')): ?>
                    <?php if(!empty($catatan) && !$isPast): ?>
                    <div class="card">
                        <div class="card-body typography">
                            <h5>Catatan</h5>
                            <form id="myform" name="myform" method="post" enctype="multipart/form-data" action="<?php echo e(route('cuti.update', (new App\Http\Controllers\OpenDataController)->encryptWithKey($trxCuti->id))); ?>" target="_self" onsubmit="return confirm('Are you sure want to submit this data?');">
                                <?php echo method_field('PATCH'); ?>
                                <?php echo csrf_field(); ?>
                                <input type="hidden" id="nik" name="nik" value="<?php echo e(auth()->user()->nik); ?>">
                                <input type="hidden" id="pic" name="pic" value="reject_hco">
                                <textarea class="form-control" id="catatan" name="catatan" rows="4" ></textarea>
                                <br/>
                                <button type="submit" name="status_cuti" value="8" class="btn btn-danger">Reject</button>
                            </form>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php endif; ?>
                </div>
                <!--  -->
                <div class="col-xxl-6 col-md-6 appointment-sec box-col-6">
                    <div class="appointment">
                        <div class="card">
                            <div class="card-header">
                                <h5>Para Pihak</h5>
                            </div>
                            <div class="card-body pt-0">
                                <div class="appointment-table table-responsive">
                                    <table class="table table-bordernone">
                                        <tbody>
                                            <tr>
                                                <td><a href="<?php echo e(asset('assets/images/matta/karyawan/'.$trxCuti->nik_karyawan.'/'.$trxCuti->nik_karyawan.'.jpg')); ?>"
                                                        target="_blank"><img class="img-thumbnail"
                                                            src="<?php echo e(asset('assets/images/matta/karyawan/'.$trxCuti->nik_karyawan.'/'.$trxCuti->nik_karyawan.'.jpg')); ?>"
                                                            alt="user"></a></td>
                                                <td class="img-content-box">
                                                    <h6 class="m-10">Karyawan</h6><span
                                                        class="f-light m-10"><?php echo e($karyawan['nama']); ?> / <?php echo e($karyawan['nik']); ?><br /><?php echo e($karyawan['jabatan']); ?>

                                                        <br /><?php echo e($karyawan['divisi']); ?> / <?php echo e($karyawan['dep']); ?> / <?php echo e($karyawan['sub_dep']); ?></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><a href="<?php echo e(asset('assets/images/matta/karyawan/'.$trxCuti->nik_atasan.'/'.$trxCuti->nik_atasan.'.jpg')); ?>"
                                                        target="_blank"><img class="img-thumbnail"
                                                            src="<?php echo e(asset('assets/images/matta/karyawan/'.$trxCuti->nik_atasan.'/'.$trxCuti->nik_atasan.'.jpg')); ?>"
                                                            alt="user"></a></td>
                                                <td class="img-content-box">
                                                    <h6 class="m-10">Atasan</h6><span
                                                        class="f-light m-10"><?php echo e($atasan['nama']); ?> / <?php echo e($atasan['nik']); ?><br /><?php echo e($atasan['jabatan']); ?>

                                                        <br /><?php echo e($atasan['divisi']); ?> / <?php echo e($atasan['dep']); ?> / <?php echo e($atasan['sub_dep']); ?></span>
                                                </td>
                                            </tr>
                                            <?php $__currentLoopData = $arrPicProperty; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><a href="<?php echo e(asset('assets/images/matta/karyawan/'.$value['nik'].'/'.$value['nik'].'.jpg')); ?>"
                                                        target="_blank"><img class="img-thumbnail"
                                                            src="<?php echo e(asset('assets/images/matta/karyawan/'.$value['nik'].'/'.$value['nik'].'.jpg')); ?>"
                                                            alt="user"></a></td>
                                                <td class="img-content-box">
                                                    <h6 class="m-10">PIC Pengganti</h6><span
                                                        class="f-light m-10"><?php echo e($value['nama']); ?> / <?php echo e($value['nik']); ?><br /><?php echo e($value['jabatan']); ?>

                                                        <br /><?php echo e($value['divisi']); ?> / <?php echo e($value['dep']); ?> / <?php echo e($value['sub_dep']); ?></span>
                                                </td>
                                            </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div><a href="<?php echo e(URL::previous()); ?>" class="btn btn-warning" type="button">Back</a>
                        <p>&nbsp;</p>
                    </div>
                </div>
                <!--  -->
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/formulir/cuti/view.blade.php ENDPATH**/ ?>