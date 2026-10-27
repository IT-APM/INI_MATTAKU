<?php $__env->startSection('title', 'Formulir Kebutuhan Karyawan'); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/datatables.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
<h3>View</h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Formulir</li>
<li class="breadcrumb-item active">Kebutuhan Karyawan</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row project-cards">
        <div class="col-sm-12">
            <div class="row">
                <!--  -->
                <div class="col-sm-4">
                    <div class="card">
                        <div class="card-header">
                            <h5>Karyawan Pengaju</h5>
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
                                        <td width="40%">Nama</td>
                                        <td width="60%"><?php echo e($pengaju['nama']); ?></td>
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
                                        <td>Direktorat / Divisi / Departemen</td>
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
                            <h5>Para Pihak</h5>
                        </div>
                        <div class="card-body">
                            <div class="appointment-table table-responsive">
                                <table class="table table-bordernone">
                                    <tbody>
                                        <!-- pengaju -->
                                        <tr>
                                            <td><a href="<?php echo e(asset('assets/images/matta/karyawan/'.$pengaju['nik'].'/'.$pengaju['nik'].'.jpg')); ?>"
                                                    target="_blank"><img class="img-thumbnail"
                                                        src="<?php echo e(asset('assets/images/matta/karyawan/'.$pengaju['nik'].'/'.$pengaju['nik'].'.jpg')); ?>"
                                                        alt="user"></a></td>
                                            <td class="img-content-box">
                                                <h6 class="m-10">Pengaju</h6><span
                                                    class="f-light m-10"><?php echo e($pengaju['nama']); ?> / <?php echo e($pengaju['nik']); ?><br /><?php echo e($pengaju['jabatan']); ?>

                                                    <br /><?php echo e($pengaju['divisi']); ?> / <?php echo e($pengaju['dep']); ?> / <?php echo e($pengaju['sub_dep']); ?></span>
                                            </td>
                                        </tr>
                                        <!-- layer1 -->
                                        <tr>
                                            <td><a href="<?php echo e(asset('assets/images/matta/karyawan/'.$layer1['nik'].'/'.$layer1['nik'].'.jpg')); ?>"
                                                    target="_blank"><img class="img-thumbnail"
                                                        src="<?php echo e(asset('assets/images/matta/karyawan/'.$layer1['nik'].'/'.$layer1['nik'].'.jpg')); ?>"
                                                        alt="user"></a></td>
                                            <td class="img-content-box">
                                                <h6 class="m-10">Layer 1</h6><span
                                                    class="f-light m-10"><?php echo e($layer1['nama']); ?> / <?php echo e($layer1['nik']); ?><br /><?php echo e($layer1['jabatan']); ?>

                                                    <br /><?php echo e($layer1['divisi']); ?> / <?php echo e($layer1['dep']); ?> / <?php echo e($layer1['sub_dep']); ?></span>
                                            </td>
                                        </tr>
                                        <!-- layer2 -->
                                        <tr><?php if(!empty($layer2['nik'])): ?>
                                            <td><a href="<?php echo e(asset('assets/images/matta/karyawan/'.$layer2['nik'].'/'.$layer2['nik'].'.jpg')); ?>"
                                                    target="_blank"><img class="img-thumbnail"
                                                        src="<?php echo e(asset('assets/images/matta/karyawan/'.$layer2['nik'].'/'.$layer2['nik'].'.jpg')); ?>"
                                                        alt="user"></a></td>
                                            <td class="img-content-box">
                                                <h6 class="m-10">Layer 2</h6><span
                                                    class="f-light m-10"><?php echo e($layer2['nama']); ?> / <?php echo e($layer2['nik']); ?><br /><?php echo e($layer2['jabatan']); ?>

                                                    <br /><?php echo e($layer2['divisi']); ?> / <?php echo e($layer2['dep']); ?> / <?php echo e($layer2['sub_dep']); ?></span>
                                            </td>
                                        <?php endif; ?></tr>
                                        <!--  -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!--  -->
                <div class="col-sm-4 notification">
                    <div class="card">
                        <div class="card-header">
                            <h5>History</h5>
                        </div>
                        <div class="card-body">
                            <ul>
                                <?php
                                    // 1=Requested by Requestor
                                    // 2=Approved by L1
                                    // 9=Approved by L2 to P&C
                                    // 10=Done by P&C
                                    // $disable=[1,2,9,10];
                                    $disable=[1,2,9];
                                    $remains = array_diff($disable, $history->pluck('id_status')->toArray());
                                    rsort($remains);
                                    // $nik_hco = App\Models\MtrUtility::find(37)->informasi;
                                ?>
                                <?php $__currentLoopData = $remains; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $remain): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="d-flex">
                                    <div class="activity-dot-dark"></div>
                                    <div class="ms-3">
                                        <h6 class="f-light" style="color: #adb5bd;"><?php echo e(App\Models\KebutuhanStatus::find($remain)->status_pengajuan); ?></h6>
                                        
                                        <?php if($remain == 9): ?>
                                        <p class="f-light" style="color: #adb5bd;">Oleh <?php echo e(App\Models\Karyawan::where('nik',$trxM->layer2)->first()->nama); ?> / <?php echo e($trxM->layer2); ?></p>
                                        <?php elseif($remain == 2): ?>
                                        <p class="f-light" style="color: #adb5bd;">Oleh <?php echo e(App\Models\Karyawan::where('nik',$trxM->layer1)->first()->nama); ?> / <?php echo e($trxM->layer1); ?></p>
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
                                        <p class="d-flex justify-content-between mb-2"><span class="date-content light-background"><?php echo e($_history->created_at); ?></span></p>
                                        <h6><?php echo e($_history->_status->status_pengajuan); ?><span class="dot-notification"></span></h6>
                                        <p class="f-light">Oleh <?php echo e($_history->_pic->nama); ?> / <?php echo e($_history->nik_pic); ?></p>
                                    </div>
                                </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <!--  -->
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-header">
                            <h5>Data Pengajuan</h5>
                        </div>
                        <div class="card-body typography">
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <td width="40%">Nama Jabatan</td>
                                        <td width="60%"><?php echo e($trxM->_pekerjaan->pekerjaan); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Tugas</td>
                                        <td><?php echo nl2br($trxM->tugas); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Wewenang</td>
                                        <td><?php echo nl2br($trxM->wewenang); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Tanggung Jawab</td>
                                        <td><?php echo nl2br($trxM->tanggung_jawab); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Level</td>
                                        <td><?php echo e($trxM->_level->level); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Status Kerja</td>
                                        <td><?php echo e($trxM->_status_kerja->status_kerja); ?></td>
                                    </tr>
                                    <?php if(!empty($trxM->waktu_kontrak)): ?>
                                    <tr>
                                        <td>Waktu Kontrak</td>
                                        <td><?php echo e($trxM->waktu_kontrak); ?> (Bulan)</td>
                                    </tr>
                                    <?php endif; ?>
                                    <tr>
                                        <td>Jumlah</td>
                                        <td><?php echo e($trxM->jumlah); ?> (Orang)</td>
                                    </tr>
                                    <tr>
                                        <td>Jenis Kelamin</td>
                                        <td><?php echo e($trxM->_kelamin->kelamin); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Area Kerja</td>
                                        <td><?php echo e($trxM->_area->propinsi); ?></td>
                                    </tr>
                                    <?php if(!empty($trxM->id_kota)): ?>
                                    <tr>
                                        <td>Area Kerja Kota</td>
                                        <td><?php echo e($trxM->_kota->kota); ?></td>
                                    </tr>
                                    <?php endif; ?>
                                    <tr>
                                        <td>Jenis Permintaan</td>
                                        <td><?php echo e($trxM->_jenis_permintaan->jenis); ?></td>
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
                                        <td><?php echo $trxM->penjelasan; ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="card-header">
                            <h5>FinDim / Cost Allocation</h5>
                        </div>
                        <div class="card-body typography">
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <td width="40%">Project</td>
                                        <td width="60%"><?php echo e($trxM->_findim_project->project_id); ?> - <?php echo e($trxM->_findim_project->project_desc); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Divisi</td>
                                        <td><?php echo e($trxM->_findim_divisi->divisi_id); ?> - <?php echo e($trxM->_findim_divisi->divisi_desc); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Departemen</td>
                                        <td><?php echo e($trxM->_findim_departemen->departemen_id); ?> - <?php echo e($trxM->_findim_departemen->departemen_desc); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Sub Departemen</td>
                                        <td><?php echo e($trxM->_findim_subdepartemen->subdepartemen_id); ?> - <?php echo e($trxM->_findim_subdepartemen->subdepartemen_desc); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!--  -->
                        <div class="card-header">
                            <h5>Tanggal</h5>
                        </div>
                        <div class="card-body typography">
                            Tanggal Mulai Kerja <?php echo e(Carbon\Carbon::createFromFormat('Y-m-d', $trxM->tanggal)->format('d-m-Y')); ?>

                        </div>
                        <!--  -->
                        <div class="card-header">
                            <h5>Keterangan Lain</h5>
                        </div>
                        <div class="card-body typography">
                            <?php echo $trxM->keterangan_lain; ?>

                        </div>
                        <!--  -->
                    </div>
                </div>
                <!--  -->
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-header">
                            <h5>Kualifikasi / Spesifikasi Jabatan</h5>
                        </div>
                        <div class="card-body typography">
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <td width="40%">Pendidikan Minimum</td>
                                        <td width="60%"><?php echo e($trxM->_jenjang_pendidikan->jenjang); ?></td>
                                    </tr>
                                    <?php if(!empty($trxM->id_jurusan_pendidikan)): ?>
                                    <tr>
                                        <td>Jurusan</td>
                                        <td>
                                        <?php if($trxM->id_jurusan_pendidikan <> '0'): ?>
                                            <?php $__currentLoopData = explode(',', $trxM->id_jurusan_pendidikan); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php echo e($index+1); ?>. <?php echo e(App\Models\PendidikanJurusan::find($value)->jurusan); ?> <?php if(!$loop->last): ?> <br/> <?php endif; ?> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                        </td>
                                        
                                    </tr>
                                    <?php endif; ?>
                                    <?php if(!empty($trxM->nilai_ipk)): ?>
                                    <tr>
                                        <td>Nilai IPK</td>
                                        <td><?php echo e($trxM->nilai_ipk); ?></td>
                                    </tr>
                                    <?php endif; ?>
                                    <tr>
                                        <td>Usia Minimum</td>
                                        <td><?php echo e($trxM->umur_minimum); ?> (Tahun)</td>
                                    </tr>
                                    <tr>
                                        <td>Usia Maksimum</td>
                                        <td><?php echo e($trxM->umur_maksimum); ?> (Tahun)</td>
                                    </tr>
                                    <tr>
                                        <td>Pengalaman Kerja (Tahun)</td>
                                        <td><?php echo e($trxM->_pengalaman->pengalaman); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Pelatihan</td>
                                        <td><?php if(!empty($trxM->id_pelatihan)): ?> <?php $__currentLoopData = $pelatihan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php echo e($index+1); ?>. <?php echo e($value->sub_pelatihan); ?> <?php if(!$loop->last): ?> <br/> <?php endif; ?> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> <?php endif; ?></td>
                                        
                                    </tr>
                                    <tr>
                                        <td>Sertifikasi</td>
                                        <td><?php if(!empty($trxM->id_sertifikasi)): ?> <?php $__currentLoopData = $sertifikasi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php echo e($index+1); ?>. <?php echo e($value->sub_sertifikasi); ?> <?php if(!$loop->last): ?> <br/> <?php endif; ?> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> <?php endif; ?></td>
                                        
                                    </tr>
                                    <tr>
                                        <td>Keterampilan</td>
                                        <td><?php if(!empty($trxM->id_keterampilan)): ?> <?php $__currentLoopData = $keterampilan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php echo e($index+1); ?>. <?php echo e($value->sub_keterampilan); ?> <?php if(!$loop->last): ?> <br/> <?php endif; ?> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> <?php endif; ?></td>
                                        
                                    </tr>
                                    <tr>
                                        <td>Kepribadian</td>
                                        <td><?php if(!empty($trxM->id_kepribadian)): ?> <?php $__currentLoopData = $kepribadian; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php echo e($index+1); ?>. <?php echo e($value->sub_kepribadian); ?> <?php if(!$loop->last): ?> <br/> <?php endif; ?> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> <?php endif; ?></td>
                                        
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!--  -->
                <div class="col-12">
                    <div class="appointment">
                        <a href="<?php echo e(URL::previous()); ?>" class="btn btn-warning" type="button">Back</a>
                        <p>&nbsp;</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="<?php echo e(asset('assets/js/datatable/datatables/jquery.dataTables.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatables/datatable.custom.js')); ?>"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/formulir/kebutuhan/view.blade.php ENDPATH**/ ?>