<?php $__env->startSection('title', 'Formulir Kebutuhan Karyawan'); ?>

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
                        <h4><img src="<?php echo e(asset('assets/images/matta/form-icon-25.jpg')); ?>" height="30"
                                alt="Alita" />&nbsp;Formulir Kebutuhan Karyawan</h4>
                    </div>
                </div>
            </div>
        </div>
        <!--  -->
        <div class="row">
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
                                    <td colspan="2">Apakah sudah masuk dalam rencana penambahan pegawai? <?php if($trxM->id_rencana == 0): ?> Belum <?php else: ?> Sudah <?php endif; ?></td>
                                </tr>
                                <tr>
                                    <td>Alasan Permintaan</td>
                                    <td><?php echo e($trxM->penjelasan); ?></td>
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
                        <?php echo e($trxM->keterangan_lain); ?>

                    </div>
                    <!--  -->
                </div>
            </div>
            <!--  -->
            <div class="col-sm-4">
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
                    <div class="card-header">
                        <h5>History</h5>
                    </div>
                    <div class="card-body typography notification">
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
                    <textarea class="form-control" id="catatan" name="catatan" rows="4" ></textarea>
                    <br/>
                    <button type="submit" name="status_pengajuan" value="<?php echo e($approve); ?>" class="btn btn-success">Approve</button>
                    <button type="submit" name="status_pengajuan" value="<?php echo e($reject); ?>" class="btn btn-danger">Reject</button>
                </form>
                </div>
            </div>
            </div>
        </div>
        <!--  -->
        <div class="row g-3">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body typography text-center">
                        <h6>Terima kasih untuk partisipasi dan kerjasamanya untuk melakukan review Formulir Kebutuhan
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
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.errors.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/emails/kebutuhan.blade.php ENDPATH**/ ?>