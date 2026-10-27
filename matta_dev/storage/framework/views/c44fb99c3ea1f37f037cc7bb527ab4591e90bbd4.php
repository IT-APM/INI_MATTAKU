<?php $__env->startSection('title', 'Formulir Penilaian Karyawan'); ?>

<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/sweetalert2.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/select2.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
    <h3>Revisi</h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
    <li class="breadcrumb-item">Penilaian Karyawan</li>
    <li class="breadcrumb-item active">Revisi</li>
<?php $__env->stopSection(); ?>
<!-- https://makitweb.com/auto-populate-dropdown-with-jquery-ajax-in-laravel/ -->
<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Formulir Evaluasi Karyawan</h5>
                    </div>
                    <div class="card-body typography">
                        <table class="table table-striped">
                            <tbody>
                                <tr>
                                    <td width="40%">Nama Karyawan</td>
                                    <td width="60%"><?php echo e($_karyawan['nama']); ?></td>
                                </tr>
                                <tr>
                                    <td>NIP</td>
                                    <td><?php echo e($_karyawan['nik']); ?></td>
                                </tr>
                                <tr>
                                    <td>Jabatan</td>
                                    <td><?php echo e($_karyawan['jabatan']); ?></td>
                                </tr>
                                <tr>
                                    <td>Direktorat / Divisi / Departemen</td>
                                    <td><?php echo e($_karyawan['divisi']); ?> / <?php echo e($_karyawan['dep']); ?> / <?php echo e($_karyawan['sub_dep']); ?>

                                    </td>
                                </tr>
                                <tr>
                                    <td>Perusahaan</td>
                                    <td><?php echo e($_karyawan['perusahaan']); ?></td>
                                </tr>
                                <tr>
                                    <td>Nama Atasan Langsung</td>
                                    <td><?php echo e($penilai1['nama']); ?></td>
                                </tr>
                                <tr>
                                    <td>Tanggal Bergabung</td>
                                    <td>
                                        <?php if(!empty($_karyawan['tanggal_bergabung'])): ?>
                                            <?php echo e(Carbon\Carbon::parse($_karyawan['tanggal_bergabung'])->format('d-m-Y')); ?>

                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Tanggal Berakhir</td>
                                    <td>
                                        <?php if(!empty($_karyawan['tanggal_berakhir'])): ?>
                                            <?php echo e(Carbon\Carbon::parse($_karyawan['tanggal_berakhir'])->format('d-m-Y')); ?>

                                        <?php endif; ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-body">
                        <?php if($penilaian->id_tipe == 6): ?>
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td width="50%"><strong>Obyek Penilaian</strong></td>
                                        <td width="50%">
                                            <div align="center"><strong>Penilai 1</strong></div>
                                        </td>
                                    </tr>
                                    <?php $__currentLoopData = $angka_skor; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($data->id_nilai3); ?>. <?php echo e($data->penilaian_1); ?></td>
                                            <td>
                                                <div align="center"><?php echo e($data->skor_penilai_1); ?></div>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($angka_rata > 0): ?>
                                        <tr>
                                            <td>
                                                <div align="right"><strong>Total Score</strong></div>
                                            </td>
                                            <td>
                                                <div align="center"><strong><?php echo e($angka_rata); ?></div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <?php if(count($angka_skor) == 14): ?>
                                                <td>
                                                    <div align="center"><strong>Result: <?php if($angka_rata > 35): ?>
                                                                Extend
                                                            <?php else: ?>
                                                                Not Extend
                                                            <?php endif; ?>
                                                        </strong>
                                                    </div>
                                                </td>
                                            <?php else: ?>
                                                <td>
                                                    <div align="center"><strong>Result: <?php if($angka_rata > 40): ?>
                                                                Extend
                                                            <?php else: ?>
                                                                Not Extend
                                                            <?php endif; ?>
                                                        </strong></div>
                                                </td>
                                            <?php endif; ?>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td width="70%"><strong>Obyek Penilaian</strong></td>
                                        <td width="15%">
                                            <div align="center"><strong>Karyawan</strong></div>
                                        </td>
                                        <td width="15%">
                                            <div align="center"><strong>Penilai 1</strong></div>
                                        </td>
                                    </tr>
                                    <?php echo $angka_skor; ?>

                                    <?php echo $angka_total; ?>

                                    <?php echo $angka_rata; ?>

                                </tbody>
                            </table>
                        <?php endif; ?>
                    </div>
                    <div class="card-body">
                        <?php echo $isian_kes_rek; ?>

                    </div>
                </div>
            </div>
            <form action="<?php echo e(route('revisi_penilaian_save')); ?>" method="post" enctype="multipart/form-data"
                onsubmit="return confirm('Are you sure want to submit this data?');">
                <?php echo csrf_field(); ?>
                <input type="hidden" id="id" name="id" value="<?php echo e($penilaian->id); ?>">
                <div class="col-sm-12">
                    <div class="card">
                        <!-- penilai1 -->
                        <?php if(!empty($penilaian->nik_penilai_1)): ?>
                            <?php
                                $where = [
                                    'id_trx' => $penilaian->id,
                                    'nik_pic' => $penilaian->nik_penilai_1,
                                ];
                                $trx = App\Models\TrxPenilaianLog::where($where)
                                    ->whereNotNull('is_perpanjang')
                                    ->orderBy('created_at', 'desc')
                                    ->first();
                                $kes_rek = $penilaian->kes_rek_1;
                                $is_perpanjang = 0;
                                $bulan = 0;
                                if (!empty($trx) && $trx->is_perpanjang) {
                                    $is_perpanjang = $trx->is_perpanjang;
                                    $bulan = $trx->bulan;
                                }
                            ?>
                            <?php if(!empty($trx)): ?>
                                <input type="hidden" id="logid_1" name="logid_1" value="<?php echo e($trx->id); ?>">
                                <div class="card-header">
                                    <h5>Revisi Penilai 1</h5>
                                </div>
                                <div class="card-body typography">
                                    <table class="table table-striped">
                                        <tbody>
                                            <tr>
                                                <td width="40%">Nama / NIP</td>
                                                <td width="60%"><?php echo e($penilai1['nama']); ?> / <?php echo e($penilai1['nik']); ?></td>
                                            </tr>
                                            <tr>
                                                <td>Jabatan</td>
                                                <td><?php echo e($penilai1['jabatan']); ?></td>
                                            </tr>
                                            <tr>
                                                <td>Direktorat / Divisi / Departemen</td>
                                                <td><?php echo e($penilai1['divisi']); ?> / <?php echo e($penilai1['dep']); ?> /
                                                    <?php echo e($penilai1['sub_dep']); ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    
                                    <div class="row mt-2 mb-2">
                                        <div class="col-md-8">
                                            <label class="form-label" for="kesrek_1">Kesimpulan dan Rekomendasi</label>
                                            <textarea class="form-control" name="kesrek_1" id="kesrek_1" rows="3"><?php echo e($kes_rek); ?></textarea>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-md-4" id="div_isperpanjang_1">
                                            <label class="form-label" for="isperpanjang_1">Status Perpanjangan</label>
                                            <select class="form-select" id="isperpanjang_1" name="isperpanjang_1"
                                                required="">
                                                <option selected="" disabled="" value="">Choose...</option>
                                                <option value="0" <?php if($is_perpanjang == 0): ?> selected="" <?php endif; ?>>
                                                    Tidak
                                                    Diperpanjang</option>
                                                <option value="1" <?php if($is_perpanjang == 1): ?> selected="" <?php endif; ?>>
                                                    Diperpanjang</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4" id="div_bulan_1">
                                            <label class="form-label" for="bulan_1">&nbsp;</label>
                                            <input id="bulan_1" name="bulan_1" required="" class="form-control numbers"
                                                type="number" min="1" oninput="validity.valid||(value='');"
                                                placeholder="Jumlah Bulan" value="<?php echo e($bulan); ?>">
                                        </div>
                                    </div>
                                    <br />
                                    <h5>History</h5>
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Status Perpanjangan</th>
                                                <th>Kesimpulan dan Rekomendasi</th>
                                                <th>Waktu</th>
                                            </tr>
                                        </thead>
                                        <?php
                                            $where = [
                                                'id_trx' => $penilaian->id,
                                                'nik_pic' => $penilaian->nik_penilai_1,
                                            ];
                                            $trx = App\Models\TrxPenilaianLogHistory::where($where)
                                                ->whereNotNull('is_perpanjang')
                                                ->orderBy('created_at', 'desc')
                                                ->get();
                                        ?>
                                        <tbody>
                                            <?php $__currentLoopData = $trx; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $_trx): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td>
                                                        <?php if($_trx->is_perpanjang == 1): ?>
                                                            Diperpanjang <?php echo e($_trx->bulan); ?> bulan
                                                        <?php else: ?>
                                                            Tidak Diperpanjang
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?php echo e($_trx->keterangan); ?></td>
                                                    <td><?php echo e($_trx->created_at); ?></td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                        <!-- penilai2 -->
                        <?php if(!empty($penilaian->nik_penilai_2)): ?>
                            <?php
                                $where = [
                                    'id_trx' => $penilaian->id,
                                    'nik_pic' => $penilaian->nik_penilai_2,
                                ];
                                $trx = App\Models\TrxPenilaianLog::where($where)
                                    ->whereNotNull('is_perpanjang')
                                    ->orderBy('created_at', 'desc')
                                    ->first();
                                $kes_rek = $penilaian->kes_rek_2;
                                $is_perpanjang = 0;
                                $bulan = 0;
                                if (!empty($trx) && $trx->is_perpanjang) {
                                    $is_perpanjang = $trx->is_perpanjang;
                                    $bulan = $trx->bulan;
                                }
                            ?>
                            <?php if(!empty($trx)): ?>
                                <input type="hidden" id="logid_2" name="logid_2" value="<?php echo e($trx->id); ?>">
                                <div class="card-header">
                                    <h5>Revisi Penilai 2</h5>
                                </div>
                                <div class="card-body typography">
                                    <table class="table table-striped">
                                        <tbody>
                                            <tr>
                                                <td width="40%">Nama / NIP</td>
                                                <td width="60%"><?php echo e($penilai2['nama']); ?> / <?php echo e($penilai2['nik']); ?></td>
                                            </tr>
                                            <tr>
                                                <td>Jabatan</td>
                                                <td><?php echo e($penilai2['jabatan']); ?></td>
                                            </tr>
                                            <tr>
                                                <td>Direktorat / Divisi / Departemen</td>
                                                <td><?php echo e($penilai2['divisi']); ?> / <?php echo e($penilai2['dep']); ?> /
                                                    <?php echo e($penilai2['sub_dep']); ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    
                                    <div class="row mt-2 mb-2">
                                        <div class="col-md-8">
                                            <label class="form-label" for="kesrek_2">Kesimpulan dan Rekomendasi</label>
                                            <textarea class="form-control" name="kesrek_2" id="kesrek_2" rows="3"><?php echo e($kes_rek); ?></textarea>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-md-4" id="div_isperpanjang_2">
                                            <label class="form-label" for="isperpanjang_2">Status Perpanjangan</label>
                                            <select class="form-select" id="isperpanjang_2" name="isperpanjang_2"
                                                required="">
                                                <option selected="" disabled="" value="">Choose...</option>
                                                <option value="0" <?php if($is_perpanjang == 0): ?> selected="" <?php endif; ?>>
                                                    Tidak
                                                    Diperpanjang</option>
                                                <option value="1" <?php if($is_perpanjang == 1): ?> selected="" <?php endif; ?>>
                                                    Diperpanjang</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4" id="div_bulan_2">
                                            <label class="form-label" for="bulan_2">&nbsp;</label>
                                            <input id="bulan_2" name="bulan_2" required="" class="form-control numbers"
                                                type="number" min="1" oninput="validity.valid||(value='');"
                                                placeholder="Jumlah Bulan" value="<?php echo e($bulan); ?>">
                                        </div>
                                    </div>
                                    <br />
                                    <h5>History</h5>
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Status Perpanjangan</th>
                                                <th>Kesimpulan dan Rekomendasi</th>
                                                <th>Waktu</th>
                                            </tr>
                                        </thead>
                                        <?php
                                            $where = [
                                                'id_trx' => $penilaian->id,
                                                'nik_pic' => $penilaian->nik_penilai_2,
                                            ];
                                            $trx = App\Models\TrxPenilaianLogHistory::where($where)
                                                ->whereNotNull('is_perpanjang')
                                                ->orderBy('created_at', 'desc')
                                                ->get();
                                        ?>
                                        <tbody>
                                            <?php $__currentLoopData = $trx; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $_trx): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td>
                                                        <?php if($_trx->is_perpanjang == 1): ?>
                                                            Diperpanjang <?php echo e($_trx->bulan); ?> bulan
                                                        <?php else: ?>
                                                            Tidak Diperpanjang
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?php echo e($_trx->keterangan); ?></td>
                                                    <td><?php echo e($_trx->created_at); ?></td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                        <!-- penilai3 -->
                        <?php if(!empty($penilaian->nik_penilai_3)): ?>
                            <?php
                                $where = [
                                    'id_trx' => $penilaian->id,
                                    'nik_pic' => $penilaian->nik_penilai_3,
                                ];
                                $trx = App\Models\TrxPenilaianLog::where($where)
                                    ->whereNotNull('is_perpanjang')
                                    ->orderBy('created_at', 'desc')
                                    ->first();
                                $kes_rek = $penilaian->kes_rek_3;
                                $is_perpanjang = 0;
                                $bulan = 0;
                                if (!empty($trx) && $trx->is_perpanjang) {
                                    $is_perpanjang = $trx->is_perpanjang;
                                    $bulan = $trx->bulan;
                                }
                            ?>
                            <?php if(!empty($trx)): ?>
                                <input type="hidden" id="logid_3" name="logid_3" value="<?php echo e($trx->id); ?>">
                                <div class="card-header">
                                    <h5>Revisi Penilai 3</h5>
                                </div>
                                <div class="card-body typography">
                                    <table class="table table-striped">
                                        <tbody>
                                            <tr>
                                                <td width="40%">Nama / NIP</td>
                                                <td width="60%"><?php echo e($penilai3['nama']); ?> / <?php echo e($penilai3['nik']); ?></td>
                                            </tr>
                                            <tr>
                                                <td>Jabatan</td>
                                                <td><?php echo e($penilai3['jabatan']); ?></td>
                                            </tr>
                                            <tr>
                                                <td>Direktorat / Divisi / Departemen</td>
                                                <td><?php echo e($penilai3['divisi']); ?> / <?php echo e($penilai3['dep']); ?> /
                                                    <?php echo e($penilai3['sub_dep']); ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    
                                    <div class="row mt-2 mb-2">
                                        <div class="col-md-8">
                                            <label class="form-label" for="kesrek_3">Kesimpulan dan Rekomendasi</label>
                                            <textarea class="form-control" name="kesrek_3" id="kesrek_3" rows="3"><?php echo e($kes_rek); ?></textarea>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-md-4" id="div_isperpanjang_3">
                                            <label class="form-label" for="isperpanjang_3">Status Perpanjangan</label>
                                            <select class="form-select" id="isperpanjang_3" name="isperpanjang_3"
                                                required="">
                                                <option selected="" disabled="" value="">Choose...</option>
                                                <option value="0" <?php if($is_perpanjang == 0): ?> selected="" <?php endif; ?>>
                                                    Tidak
                                                    Diperpanjang</option>
                                                <option value="1" <?php if($is_perpanjang == 1): ?> selected="" <?php endif; ?>>
                                                    Diperpanjang</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4" id="div_bulan_3">
                                            <label class="form-label" for="bulan_3">&nbsp;</label>
                                            <input id="bulan_3" name="bulan_3" required="" class="form-control numbers"
                                                type="number" min="1" oninput="validity.valid||(value='');"
                                                placeholder="Jumlah Bulan" value="<?php echo e($bulan); ?>">
                                        </div>
                                    </div>
                                    <br />
                                    <h5>History</h5>
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Status Perpanjangan</th>
                                                <th>Kesimpulan dan Rekomendasi</th>
                                                <th>Waktu</th>
                                            </tr>
                                        </thead>
                                        <?php
                                            $where = [
                                                'id_trx' => $penilaian->id,
                                                'nik_pic' => $penilaian->nik_penilai_3,
                                            ];
                                            $trx = App\Models\TrxPenilaianLogHistory::where($where)
                                                ->whereNotNull('is_perpanjang')
                                                ->orderBy('created_at', 'desc')
                                                ->get();
                                        ?>
                                        <tbody>
                                            <?php $__currentLoopData = $trx; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $_trx): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td>
                                                        <?php if($_trx->is_perpanjang == 1): ?>
                                                            Diperpanjang <?php echo e($_trx->bulan); ?> bulan
                                                        <?php else: ?>
                                                            Tidak Diperpanjang
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?php echo e($_trx->keterangan); ?></td>
                                                    <td><?php echo e($_trx->created_at); ?></td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                        <!-- hco -->
                        <?php if($penilaian->id_status == 4 || $penilaian->id_status == 8): ?>
                            <?php
                                $kes_rek = $penilaian->kes_rek_final;
                                $is_perpanjang = $penilaian->is_perpanjang;
                                $bulan = $penilaian->bulan;
                                //
                                $where = [
                                    'id_trx' => $penilaian->id,
                                ];
                                $trx = App\Models\TrxPenilaianLog::where($where)
                                    ->whereIn('id_status', [4, 8])
                                    ->orderBy('created_at', 'desc')
                                    ->first();
                            ?>
                            <?php if(!empty($trx)): ?>
                                <input type="hidden" id="logid_hco" name="logid_hco" value="<?php echo e($trx->id); ?>">
                                <div class="card-header">
                                    <h5>Revisi <?php echo e(App\Models\MtrUtility::find(51)->informasi); ?></h5>
                                </div>
                                <div class="card-body typography">
                                    
                                    <div class="row mt-2 mb-2">
                                        <div class="col-md-8">
                                            <label class="form-label" for="kesrek_hco">Kesimpulan dan Rekomendasi</label>
                                            <textarea class="form-control" name="kesrek_hco" id="kesrek_hco" rows="3"><?php echo e($kes_rek); ?></textarea>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-md-4" id="div_isperpanjang_hco">
                                            <label class="form-label" for="isperpanjang_hco">Status Perpanjangan</label>
                                            <select class="form-select" id="isperpanjang_hco" name="isperpanjang_hco"
                                                required="">
                                                <option selected="" disabled="" value="">Choose...</option>
                                                <option value="0" <?php if($is_perpanjang == 2): ?> selected="" <?php endif; ?>>
                                                    Tidak
                                                    Diperpanjang</option>
                                                <option value="1" <?php if($is_perpanjang == 1): ?> selected="" <?php endif; ?>>
                                                    Diperpanjang</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4" id="div_bulan_hco">
                                            <label class="form-label" for="bulan_hco">&nbsp;</label>
                                            <input id="bulan_hco" name="bulan_hco" required=""
                                                class="form-control numbers" type="number" min="1"
                                                oninput="validity.valid||(value='');" placeholder="Jumlah Bulan"
                                                value="<?php echo e($bulan); ?>">
                                        </div>
                                    </div>
                                    <br />
                                    <h5>History</h5>
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Status Perpanjangan</th>
                                                <th>Kesimpulan dan Rekomendasi</th>
                                                <th>Waktu</th>
                                            </tr>
                                        </thead>
                                        <?php
                                            $where = [
                                                'id_trx' => $penilaian->id,
                                            ];
                                            $trx = App\Models\TrxPenilaianLogHistory::where($where)
                                                ->whereIn('id_status', [4, 8])
                                                ->whereNotNull('is_perpanjang')
                                                ->orderBy('created_at', 'desc')
                                                ->get();
                                        ?>
                                        <tbody>
                                            <?php $__currentLoopData = $trx; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $_trx): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td>
                                                        <?php if($_trx->is_perpanjang == 1): ?>
                                                            Diperpanjang <?php echo e($_trx->bulan); ?> bulan
                                                        <?php else: ?>
                                                            Tidak Diperpanjang
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?php echo e($_trx->keterangan); ?></td>
                                                    <td><?php echo e($_trx->created_at); ?></td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('hc')): ?>
                        <div class="card-body typography">
                            <button class="btn btn-success" type="submit">Save</button> | <a href="<?php echo e(route('penilaian.index')); ?>" class="btn btn-warning" type="button">Back</a>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(asset('assets/js/form-validation-custom.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/sweet-alert/sweetalert.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/sweet-alert/app.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/select2/select2.full.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/select2/select2-custom.js')); ?>"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $("#bulan_1").prop('disabled', true);
            $('#div_bulan_1').hide();
            if ($("#isperpanjang_1").val() == 1) {
                $("#bulan_1").prop('disabled', false);
                $('#div_bulan_1').show();
            }
        });
        $("#isperpanjang_1").change(function() {
            var valueStatus = $(this).val();
            if (valueStatus == 1) {
                $("#bulan_1").prop('disabled', false);
                $('#div_bulan_1').show();
            } else {
                $("#bulan_1").prop('disabled', true);
                $('#div_bulan_1').hide();
            }
        });
        // 
        $(document).ready(function() {
            $("#bulan_2").prop('disabled', true);
            $('#div_bulan_2').hide();
            if ($("#isperpanjang_2").val() == 1) {
                $("#bulan_2").prop('disabled', false);
                $('#div_bulan_2').show();
            }
        });
        $("#isperpanjang_2").change(function() {
            var valueStatus = $(this).val();
            if (valueStatus == 1) {
                $("#bulan_2").prop('disabled', false);
                $('#div_bulan_2').show();
            } else {
                $("#bulan_2").prop('disabled', true);
                $('#div_bulan_2').hide();
            }
        });
        // 
        $(document).ready(function() {
            $("#bulan_3").prop('disabled', true);
            $('#div_bulan_3').hide();
            if ($("#isperpanjang_3").val() == 1) {
                $("#bulan_3").prop('disabled', false);
                $('#div_bulan_3').show();
            }
        });
        $("#isperpanjang_3").change(function() {
            var valueStatus = $(this).val();
            if (valueStatus == 1) {
                $("#bulan_3").prop('disabled', false);
                $('#div_bulan_3').show();
            } else {
                $("#bulan_3").prop('disabled', true);
                $('#div_bulan_3').hide();
            }
        });
        // 
        $(document).ready(function() {
            $("#bulan_hco").prop('disabled', true);
            $('#div_bulan_hco').hide();
            if ($("#isperpanjang_hco").val() == 1) {
                $("#bulan_hco").prop('disabled', false);
                $('#div_bulan_hco').show();
            }
        });
        $("#isperpanjang_hco").change(function() {
            var valueStatus = $(this).val();
            if (valueStatus == 1) {
                $("#bulan_hco").prop('disabled', false);
                $('#div_bulan_hco').show();
            } else {
                $("#bulan_hco").prop('disabled', true);
                $('#div_bulan_hco').hide();
            }
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/formulir/penilaian/revisi.blade.php ENDPATH**/ ?>