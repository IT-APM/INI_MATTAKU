
<?php $__env->startSection('title', 'Formulir Penilaian Karyawan'); ?>

<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/datatables.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/datatable-extension.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/select2.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
    <style>
        button.dt-button {
            float: none;
            text-align: center;
            color: black;
            background: white;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
    <h3>Formulir Penilaian Karyawan</h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
    <li class="breadcrumb-item">Formulir</li>
    <li class="breadcrumb-item active">Penilaian Karyawan</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <?php if(Session::has('msg')): ?>
                            <!-- <div class="alert alert-success"><?php echo e(Session::get('msg')); ?></div> -->
                            <div class="alert alert-success dark alert-dismissible fade show" role="alert">
                                <h6><?php echo e(session('msg')); ?></h6>
                                <button class="btn-close" type="button" data-bs-dismiss="alert"aria-label="Close"></button>
                            </div>
                        <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('hc')): ?>
                            <a href="<?php echo e(route('penilaian.create')); ?>" class="btn btn-primary" type="button"><i class="fa fa-plus" aria-hidden="true"></i> Add new</a> |
                            <a href="<?php echo e(route('penilaian_auto.index')); ?>" class="btn btn-success" type="button"><i class="fa fa-plus-circle" aria-hidden="true"></i> Auto</a> |
                            <a href="<?php echo e(route('penilaian.kontrak_dok', 0)); ?>" class="btn btn-primary" type="button"><i class="fa fa-file-text-o" aria-hidden="true"></i> Kontrak</a> |
                            <a href="<?php echo e(route('penilaian_reminder.index')); ?>" class="btn btn-info" type="button"><i class="fa fa-repeat" aria-hidden="true"></i> Reminder</a> |
                        <?php endif; ?>
                        <button class="btn btn-warning" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_filter" aria-expanded="false" aria-controls="collapse_filter"><i class="fa fa-search" aria-hidden="true"></i> Filter</button>
                        <div class="collapse" id="collapse_filter">
                            <form class="mt-2" action="<?php echo e(route('penilaian.index')); ?>" method="get" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <label class="h6">Tanggal</label>
                                        <input class="form-control" name="tanggal_mulai" id="tanggal_mulai" type="date" required="" value="<?php echo e($tanggal_mulai); ?>">
                                        sampai
                                        <input class="form-control" name="tanggal_akhir" id="tanggal_akhir" type="date" required="" value="<?php echo e($tanggal_akhir); ?>">
                                    </div>
                                    <div class="col-sm-3">
                                        <label class="h6">Status Pengajuan</label>&nbsp;<input type="checkbox" onClick="toggle_id_status(this)" /> Check All
                                        <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                        <label class="d-block" for="id_status">
                                            <?php $__currentLoopData = $id_status; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <input class="checkbox_animated" id="id_status[]" name="id_status[]" type="checkbox" value="<?php echo e($data->id); ?>" <?php if(in_array($data->id, $arr_id_status)): ?> checked <?php endif; ?>><?php echo e($data->status_penilaian); ?><br/>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </label>
                                        </div>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('hc')): ?>
                                            <label class="h6">Status Pengajuan Lengkap</label>
                                            <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                            <label class="d-block" for="id_status_lengkap">
                                                <?php $__currentLoopData = $id_status_lengkap; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <input class="checkbox_animated" id="id_status_lengkap[]" name="id_status_lengkap[]" type="checkbox" value="<?php echo e($data); ?>" <?php if(in_array($data, $arr_id_status_lengkap)): ?> checked <?php endif; ?>> Done By Reviewer 2 & 3<br/>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </label>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-sm-3">
                                        <label class="h6">Status Perpanjangan</label>&nbsp;<input type="checkbox" onClick="toggle_is_perpanjang(this)" /> Check All
                                        <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                        <label class="d-block" for="is_perpanjang">
                                            <?php $__currentLoopData = $is_perpanjang; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <input class="checkbox_animated" id="is_perpanjang[]" name="is_perpanjang[]" type="checkbox" value="<?php echo e($data->id); ?>" <?php if(in_array($data->id, $arr_is_perpanjang)): ?> checked <?php endif; ?>><?php echo e($data->informasi); ?><br/>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <label class="h6">Perusahaan</label>&nbsp;<input type="checkbox" onClick="toggle_perusahaan(this)" /> Check All
                                        <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                        <label class="d-block" for="perusahaan">
                                            <?php $__currentLoopData = $perusahaan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <input class="checkbox_animated" id="perusahaan[]" name="perusahaan[]" type="checkbox" value="<?php echo e($data->id); ?>" <?php if(in_array($data->id, $arr_perusahaan)): ?> checked <?php endif; ?>><?php echo e($data->nama); ?><br/>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <button class="btn btn-warning" id="filter" name="filter" value="true" type="submit"><i class="fa fa-search" aria-hidden="true"></i> Filter</button> |
                                        <button class="btn btn-info" id="reset" name="reset" value="true" type="submit"><i class="fa fa-refresh" aria-hidden="true"></i>Reset</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <br/><br/>
                        <div class="table-responsive">
                            <table class="display" id="_datatable">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>#ID</th>
                                        <th>Action</th>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('hc')): ?>
                                        <th>Kontrak PKWT</th>
                                        <?php endif; ?>
                                        <th>Karyawan</th>
                                        <th>NIP</th>
                                        <th>Perusahaan</th>
                                        <th>Tipe</th>
                                        <th>Tanggal Selesai PKWT</th>
                                        <th>Penilai 1</th>
                                        <th>Penilai 2</th>
                                        <th>Penilai 3</th>
                                        <th>Penilaian ke</th>
                                        <th>Nilai Total Karyawan</th>
                                        <th>Nilai Total Penilai 1</th>
                                        <th>Status Perpanjangan Penilai 1</th>
                                        <th>Status Perpanjangan Penilai 2</th>
                                        <th>Status Perpanjangan Penilai 3</th>
                                        <th>Created_at</th>
                                        <th>Timestamp Penilai 1</th>
                                        <th>Timestamp Penilai 2</th>
                                        <th>Timestamp Penilai 3</th>
                                        <th>Timestamp <?php echo e(App\Models\MtrUtility::find(51)->informasi); ?></th>
                                        <th>Status</th>
                                        <th>Perpanjang</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $trx; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <?php
                                                $_karyawan_property = json_decode($data->karyawan_property, true);
                                            ?>
                                            <td><?php echo e(++$no); ?></td>
                                            <td><?php echo e($data->id); ?></td>
                                            <td>
                                                <a href="<?php echo e(route('penilaian.show', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id), 'view' => (new App\Http\Controllers\OpenDataController)->encryptWithKey('detail')])); ?>" data-toggle="tooltip" title="Detail"><i class="icon-eye"></i></a>
                                                &nbsp;<a href="<?php echo e(route('penilaian.show', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id), 'view' => (new App\Http\Controllers\OpenDataController)->encryptWithKey('pdf')])); ?>" data-toggle="tooltip" title="PDF" target="_blank"><i class="fa fa-file-pdf-o"></i></a>
                                                <br/><a href="<?php echo e(route('penilaian.show', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id), 'view' => (new App\Http\Controllers\OpenDataController)->encryptWithKey('result')])); ?>" data-toggle="tooltip" title="Result" target="_blank"><i class="fa fa-file-text-o"></i></a>
                                                
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('hc')): ?>
                                                    &nbsp;<a href="<?php echo e(route('penilaian.edit', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id)])); ?>" data-toggle="tooltip" title='Update'><i class="icon-pencil-alt"></i></a>
                                                    
                                                    <br/><a href="<?php echo e(route('revisi_penilaian', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id)])); ?>" data-toggle="tooltip" title='Revision'><i class="icon-pencil-alt" style="color:red"></i></a>
                                                    
                                                <?php endif; ?>
                                            </td>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('hc')): ?>
                                            <td>
                                                <?php if($data->id_perusahaan == 1): ?>
                                                    <a href="<?php echo e(route('penilaian.kontrak', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id), 'header'=>true])); ?>" data-toggle="tooltip" title="Kontrak PKWT-Header" target="_blank"><i class="fa fa-file-pdf-o text-success"></i></a>
                                                    <a href="<?php echo e(route('penilaian.kontrak', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id), 'header'=>false])); ?>" data-toggle="tooltip" title="Kontrak PKWT" target="_blank"><i class="fa fa-file-pdf-o text-warning"></i></a>
                                                    <a class="edit_pkwt" href="#" id="edit_pkwt" name="edit_pkwt"  data-bs-toggle="modal" data-bs-target="#editModal" title="Revision"
                                                        data-id="<?php echo e($data->id); ?>"
                                                        data-no_kontrak="<?php echo e($data->no_kontrak); ?>"
                                                        data-tanggal_kontrak="<?php echo e($data->tanggal_kontrak); ?>"
                                                        data-tanggal_terhitung="<?php echo e($data->tanggal_terhitung); ?>"
                                                        data-id_perusahaan="<?php echo e($data->id_perusahaan); ?>"
                                                        data-id_divisi="<?php echo e($data->id_divisi); ?>"
                                                        data-id_departemen="<?php echo e($data->id_departemen); ?>"
                                                        data-id_sub_departemen="<?php echo e($data->id_sub_departemen); ?>"
                                                        data-id_level="<?php echo e($data->id_level); ?>"
                                                        data-id_jabatan="<?php echo e($data->id_jabatan); ?>"
                                                        data-gapok="<?php echo e($data->gapok); ?>"
                                                        data-t_makan="<?php echo e($data->t_makan); ?>"
                                                        data-t_transportasi="<?php echo e($data->t_transportasi); ?>"
                                                        data-t_jabatan="<?php echo e($data->t_jabatan); ?>"
                                                        data-t_telekomunikasi="<?php echo e($data->t_telekomunikasi); ?>"
                                                        data-t_lain="<?php echo e($data->t_lain); ?>">
                                                        <i class="icon-pencil-alt text-success"></i>
                                                    </a>
                                                    <br/>No. <?php echo e(str_pad($data->no_kontrak, 3, '0', STR_PAD_LEFT)); ?>

                                                <?php endif; ?>
                                            </td>
                                            <?php endif; ?>
                                            <td><?php echo e($_karyawan_property['nama']); ?></td>
                                            <td><?php echo e($_karyawan_property['nik']); ?></td>
                                            <td><?php echo e($_karyawan_property['perusahaan']); ?></td>
                                            <td><?php echo e($data->_tipe->tipe_penilaian); ?></td>
                                            <td>
                                                <?php if(!empty($_karyawan_property['tanggal_berakhir'])): ?>
                                                    <?php echo e(Carbon\Carbon::parse($_karyawan_property['tanggal_berakhir'])->format('d-m-Y')); ?>

                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo e($data->_penilai1->nama); ?></td>
                                            <td>
                                                <?php if(!empty($data->nik_penilai_2)): ?>
                                                    <?php echo e($data->_penilai2->nama); ?>

                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if(!empty($data->nik_penilai_3)): ?>
                                                    <?php echo e($data->_penilai3->nama); ?>

                                                <?php endif; ?>
                                            </td>
                                            <!-- urutan -->
                                            <?php
                                                $urutan = App\Models\TrxPenilaian::where('nik_karyawan', $_karyawan_property['nik'])->where('id', '<=', $data->id)->count();
                                            ?>
                                            <td><?php echo e($urutan); ?></td>
                                            <!-- Nilai Total Karyawan -->
                                            <?php
                                                $_where = ['id_trx' => $data->id];
                                                $skor_karyawan = App\Models\TrxPenilaianSkor::where($_where)->sum('skor_karyawan');
                                            ?>
                                            <td>
                                                <?php if($skor_karyawan > 0): ?>
                                                    <?php echo e($skor_karyawan); ?>

                                                <?php endif; ?>
                                            </td>
                                            <!-- Nilai Total Penilai 1 -->
                                            <?php
                                                $_where = ['id_trx' => $data->id];
                                                $skor_penilai_1 = App\Models\TrxPenilaianSkor::where($_where)->sum('skor_penilai_1');
                                            ?>
                                            <td><?php echo e($skor_penilai_1); ?></td>
                                            <!-- nik_penilai_1 -->
                                            <?php
                                                $_where = ['id_trx' => $data->id, 'nik_pic' => $data->nik_penilai_1];
                                                $trx = App\Models\TrxPenilaianLog::where($_where)->whereNotNull('is_perpanjang')->orderBy('created_at', 'desc')->first();
                                            ?>
                                            <td>
                                                <?php if(!empty($trx)): ?>
                                                    <?php if($trx->is_perpanjang == 1): ?>
                                                        Diperpanjang <?php echo e($trx->bulan); ?> bulan
                                                    <?php else: ?>
                                                        Tidak Diperpanjang
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </td>
                                            <!-- nik_penilai_2 -->
                                            <?php
                                                $_where = ['id_trx' => $data->id, 'nik_pic' => $data->nik_penilai_2];
                                                $trx = App\Models\TrxPenilaianLog::where($_where)->whereNotNull('is_perpanjang')->orderBy('created_at', 'desc')->first();
                                            ?>
                                            <td>
                                                <?php if(!empty($trx)): ?>
                                                    <?php if($trx->is_perpanjang == 1): ?>
                                                        Diperpanjang <?php echo e($trx->bulan); ?> bulan
                                                    <?php else: ?>
                                                        Tidak Diperpanjang
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </td>
                                            <!-- nik_penilai_3 -->
                                            <?php
                                                $_where = ['id_trx' => $data->id, 'nik_pic' => $data->nik_penilai_3];
                                                $trx = App\Models\TrxPenilaianLog::where($_where)->whereNotNull('is_perpanjang')->orderBy('created_at', 'desc')->first();
                                            ?>
                                            <td>
                                                <?php if(!empty($trx)): ?>
                                                    <?php if($trx->is_perpanjang == 1): ?>
                                                        Diperpanjang <?php echo e($trx->bulan); ?> bulan
                                                    <?php else: ?>
                                                        Tidak Diperpanjang
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo e($data->created_at); ?></td>
                                            <!-- timestamp nik_penilai_1 -->
                                            <?php
                                                $_where = ['id_trx' => $data->id, 'nik_pic' => $data->nik_penilai_1];
                                                $_created_at = App\Models\TrxPenilaianLog::where($_where)->orderBy('created_at', 'desc')->first();
                                            ?>
                                            <td>
                                                <?php if(!empty($_created_at)): ?>
                                                    <?php echo e($_created_at->created_at); ?>

                                                <?php endif; ?>
                                            </td>
                                            <!-- timestamp nik_penilai_2 -->
                                            <?php
                                                $_where = ['id_trx' => $data->id, 'nik_pic' => $data->nik_penilai_2];
                                                $_created_at = App\Models\TrxPenilaianLog::where($_where)->orderBy('created_at', 'desc')->first();
                                            ?>
                                            <td>
                                                <?php if(!empty($_created_at)): ?>
                                                    <?php echo e($_created_at->created_at); ?>

                                                <?php endif; ?>
                                            </td>
                                            <!-- timestamp nik_penilai_3 -->
                                            <?php
                                                $_where = ['id_trx' => $data->id, 'nik_pic' => $data->nik_penilai_3];
                                                $_created_at = App\Models\TrxPenilaianLog::where($_where)->orderBy('created_at', 'desc')->first();
                                            ?>
                                            <td>
                                                <?php if(!empty($_created_at)): ?>
                                                    <?php echo e($_created_at->created_at); ?>

                                                <?php endif; ?>
                                            </td>
                                            <!-- timestamp hco -->
                                            <?php
                                                $_where = ['id_trx' => $data->id];
                                                $_created_at = App\Models\TrxPenilaianLog::where($_where)->whereIn('id_status', [4, 8])->orderBy('created_at', 'desc')->first();
                                            ?>
                                            <td>
                                                <?php if(!empty($_created_at)): ?>
                                                    <?php echo e($_created_at->created_at); ?>

                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo e($data->_status->status_penilaian); ?></td>
                                            <td><?php echo e($data->_perpanjang->informasi); ?>

                                                <?php if($data->is_perpanjang == 1): ?>
                                                    <?php echo e($data->bulan); ?> Bulan
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form  id="form_kontrak" name="form_kontrak" action="<?php echo e(route('penilaian.kontrak_update')); ?>" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return confirm('Are you sure want to submit this data?');">
                <?php echo csrf_field(); ?>
                    <div class="modal-body">
                        <input type="hidden" id="id" name="id">
                        <input type="hidden" id="back_url" name="back_url" value="<?php echo e(URL::full()); ?>">
                        <div class="row">
                            <div class="col-md-4">
                                <label class="form-label" for="no_kontrak">No Kontrak</label>
                                <input class="form-control" name="no_kontrak" id="no_kontrak" type="text" required="">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="tanggal_kontrak">Tanggal Kontrak</label>
                                <input class="form-control" name="tanggal_kontrak" id="tanggal_kontrak" type="date" required="">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="tanggal_terhitung">Tanggal Terhitung</label>
                                <input class="form-control" name="tanggal_terhitung" id="tanggal_terhitung" type="date" required="">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label class="form-label" for="id_perusahaan">Perusahaan</label>
                                
                                <select class="form-select js-example-basic-single col-sm-12" id="id_perusahaan" name="id_perusahaan" required="">
                                    <option selected="" value="">Pilih...</option>
                                    <?php $__currentLoopData = $perusahaan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($data->id); ?>"><?php echo e($data->nama); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="id_divisi">Direktorat</label>
                                <select class="form-select js-example-basic-single col-sm-12" id="id_divisi" name="id_divisi" required="">
                                    <option selected="" value="">Pilih...</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="id_departemen">Divisi</label>
                                <select class="form-select js-example-basic-single col-sm-12" id="id_departemen" name="id_departemen" required="">
                                    <option selected="" value="">Pilih...</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-4">
                                <label class="form-label" for="id_sub_departemen">Departemen</label>
                                <select class="form-select js-example-basic-single col-sm-12" id="id_sub_departemen" name="id_sub_departemen" required="">
                                    <option selected="" value="">Pilih...</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="id_level">Level</label>
                                <select class="form-select js-example-basic-single col-sm-12" id="id_level" name="id_level" required="">
                                    <option selected="" value="">Pilih...</option>
                                    <?php $__currentLoopData = $level; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($data->id); ?>"><?php echo e($data->level); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="id_jabatan">Jabatan</label>
                                <select class="form-select js-example-basic-single col-sm-12" id="id_jabatan" name="id_jabatan" required="">
                                    <option selected="" value="">Pilih...</option>
                                    <?php $__currentLoopData = $jabatan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($data->id); ?>"><?php echo e($data->jabatan); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-4">
                                <label class="form-label" for="gapok">Gaji Pokok</label>
                                <input type="number" min="0" name="gapok" id="gapok" class="form-control numbers" required="" oninput="validity.valid||(value='');" onfocus="use_number(this)" onblur="use_text(this)">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="t_makan">Tunjangan Makan</label>
                                <input type="number" min="0" name="t_makan" id="t_makan" class="form-control numbers" required="" oninput="validity.valid||(value='');" onfocus="use_number(this)" onblur="use_text(this)">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="t_transportasi">Tunjangan Transport</label>
                                <input type="number" min="0" name="t_transportasi" id="t_transportasi" class="form-control numbers" required="" oninput="validity.valid||(value='');" onfocus="use_number(this)" onblur="use_text(this)">
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-4">
                                <label class="form-label" for="t_jabatan">Tunjangan Jabatan</label>
                                <input type="number" min="0" name="t_jabatan" id="t_jabatan" class="form-control numbers" required="" oninput="validity.valid||(value='');" onfocus="use_number(this)" onblur="use_text(this)">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="t_telekomunikasi">Tunjangan Telekomunikasi</label>
                                <input type="number" min="0" name="t_telekomunikasi" id="t_telekomunikasi" class="form-control numbers" required="" oninput="validity.valid||(value='');" onfocus="use_number(this)" onblur="use_text(this)">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="t_lain">Tunjangan Lain-lain</label>
                                <input type="number" min="0" name="t_lain" id="t_lain" class="form-control numbers" required="" oninput="validity.valid||(value='');" onfocus="use_number(this)" onblur="use_text(this)">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-start">
                        <button type="submit" class="btn btn-success">Update</button>
                        <button type="button" class="btn btn-warning" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(asset('assets/js/datatable/datatables/jquery.dataTables.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datatable/datatables/datatable.custom.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datatable/datatable-extension/dataTables.buttons.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datatable/datatable-extension/jszip.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datatable/datatable-extension/pdfmake.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datatable/datatable-extension/vfs_fonts.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datatable/datatable-extension/buttons.html5.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datatable/datatable-extension/buttons.print.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/select2/select2.full.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/select2/select2-custom.js')); ?>"></script>
    <script type="text/javascript">
        function use_number(node) {
            var empty_val = false;
            const value = node.value;
            if (node.value == '')
                empty_val = true;
            node.type = 'number';
            if (!empty_val)
                node.value = Number(value.replace(/,/g, '')); // or equivalent per locale
        }
        // 
        function use_text(node) {
            var empty_val = false;
            const value = Number(node.value);
            if (node.value == '')
                empty_val = true;
            node.type = 'text';
            if (!empty_val)
                node.value = value.toLocaleString('en');  // or other formatting
        }
        $(document).ready(function() {
            var _id_perusahaan = 0;
            var _id_divisi = 0;
            var _id_departemen = 0;
            // 
            $("#id_perusahaan").change(function() {
                var _id_perusahaan = $(this).val();
                $('#id_divisi').find('option').not(':first').remove();
                $('#id_departemen').find('option').not(':first').remove();
                $('#id_sub_departemen').find('option').not(':first').remove();
                $.ajax({
                    url: "<?php echo e(route('get_divisi')); ?>"+"/"+_id_perusahaan,
                    type: 'get',
                    dataType: 'json',
                    success: function(response) {
                        var len = 0;
                        if (response != null) {
                            len = response.length;
                        }
                        if (len > 0) {
                            for (var i = 0; i < len; i++) {
                                var id = response[i]['id'];
                                var nama = response[i]['nama'];
                                var option = "<option value='" + id + "'>" + nama + "</option>";
                                $("#id_divisi").append(option);
                            }
                        }
                    }
                });
            });
            // 
            $("#id_divisi").change(function() {
                var _id_divisi = $(this).val();
                $('#id_departemen').find('option').not(':first').remove();
                $('#id_sub_departemen').find('option').not(':first').remove();
                $.ajax({
                    url: "<?php echo e(route('get_departemen')); ?>"+"/"+_id_divisi,
                    type: 'get',
                    dataType: 'json',
                    success: function(response) {
                        var len = 0;
                        if (response != null) {
                            len = response.length;
                        }
                        if (len > 0) {
                            for (var i = 0; i < len; i++) {
                                var id = response[i]['id'];
                                var nama = response[i]['nama'];
                                var option = "<option value='" + id + "'>" + nama + "</option>";
                                $("#id_departemen").append(option);
                            }
                        }
                    }
                });
            });
            // 
            $("#id_departemen").change(function() {
                var _id_departemen = $(this).val();
                $('#id_sub_departemen').find('option').not(':first').remove();
                $.ajax({
                    url: "<?php echo e(route('get_sub_departemen')); ?>"+"/"+_id_departemen,
                    type: 'get',
                    dataType: 'json',
                    success: function(response) {
                        var len = 0;
                        if (response != null) {
                            len = response.length;
                        }
                        if (len > 0) {
                            for (var i = 0; i < len; i++) {
                                var id = response[i]['id'];
                                var nama = response[i]['nama'];
                                var option = "<option value='" + id + "'>" + nama + "</option>";
                                $("#id_sub_departemen").append(option);
                            }
                        }
                    }
                });
            });
            // 
            $(document).on('click', '#edit_pkwt', function(event) {
            // $('.edit_pkwt').on('click', function() {
                $('input[name="id"]').val($(this).data('id'));
                $('input[name="no_kontrak"]').val($(this).data('no_kontrak'));
                $('input[name="tanggal_kontrak"]').val($(this).data('tanggal_kontrak'));
                $('input[name="tanggal_terhitung"]').val($(this).data('tanggal_terhitung'));
                $('select[name="id_perusahaan"]').val($(this).data('id_perusahaan')).trigger('change');
                $('select[name="id_divisi"]').val($(this).data('id_divisi')).trigger('change');
                $('select[name="id_departemen"]').val($(this).data('id_departemen')).trigger('change');
                $('select[name="id_sub_departemen"]').val($(this).data('id_sub_departemen')).trigger('change');
                $('select[name="id_level"]').val($(this).data('id_level')).trigger('change');
                $('select[name="id_jabatan"]').val($(this).data('id_jabatan')).trigger('change');
                $('input[name="gapok"]').val($(this).data('gapok'));
                // $('input[name="gapok"]').val(Number($(this).data('gapok')).toLocaleString('en-US'));
                $('input[name="t_makan"]').val($(this).data('t_makan'));
                $('input[name="t_transportasi"]').val($(this).data('t_transportasi'));
                $('input[name="t_jabatan"]').val($(this).data('t_jabatan'));
                $('input[name="t_telekomunikasi"]').val($(this).data('t_telekomunikasi'));
                $('input[name="t_lain"]').val($(this).data('t_lain'));
                // 
                let id_divisi = $(this).data('id_divisi');
                $('#id_divisi').find('option').not(':first').remove();
                $.ajax({
                    url: "<?php echo e(route('get_divisi')); ?>"+"/"+$(this).data('id_perusahaan'),
                    type: 'get',
                    dataType: 'json',
                    success: function(response) {
                        var len = 0;
                        if (response != null) {
                            len = response.length;
                        }
                        if (len > 0) {
                            for (var i = 0; i < len; i++) {
                                var id = response[i]['id'];
                                var nama = response[i]['nama'];
                                var option = "<option value='" + id + "' " + (String(id) === String(id_divisi) ? "selected" : "") + ">" + nama + "</option>";
                                $("#id_divisi").append(option);
                            }
                        }
                    }
                });
                // 
                let id_departemen = $(this).data('id_departemen');
                $('#id_departemen').find('option').not(':first').remove();
                $.ajax({
                    url: "<?php echo e(route('get_departemen')); ?>"+"/"+$(this).data('id_divisi'),
                    type: 'get',
                    dataType: 'json',
                    success: function(response) {
                        var len = 0;
                        if (response != null) {
                            len = response.length;
                        }
                        if (len > 0) {
                            for (var i = 0; i < len; i++) {
                                var id = response[i]['id'];
                                var nama = response[i]['nama'];
                                var option = "<option value='" + id + "' " + (String(id) === String(id_departemen) ? "selected" : "") + ">" + nama + "</option>";
                                $("#id_departemen").append(option);
                            }
                        }
                    }
                });
                // 
                let id_sub_departemen = $(this).data('id_sub_departemen');
                $('#id_sub_departemen').find('option').not(':first').remove();
                $.ajax({
                    url: "<?php echo e(route('get_sub_departemen')); ?>"+"/"+$(this).data('id_departemen'),
                    type: 'get',
                    dataType: 'json',
                    success: function(response) {
                        var len = 0;
                        if (response != null) {
                            len = response.length;
                        }
                        if (len > 0) {
                            for (var i = 0; i < len; i++) {
                                var id = response[i]['id'];
                                var nama = response[i]['nama'];
                                var option = "<option value='" + id + "' " + (String(id) === String(id_sub_departemen) ? "selected" : "") + ">" + nama + "</option>";
                                $("#id_sub_departemen").append(option);
                            }
                        }
                    }
                });
            });
            // 
            $('#form_kontrak').on('submit', function (e) {
                let no_kontrak = $('input[name="no_kontrak"]').val().trim();
                if (no_kontrak === '') {
                    e.preventDefault();
                    alert("No Kontrak is mandatory");
                }
                let tanggal_kontrak = $('input[name="tanggal_kontrak"]').val().trim();
                if (tanggal_kontrak === '') {
                    e.preventDefault();
                    alert("Tanggal Kontrak is mandatory");
                }
                let tanggal_terhitung = $('input[name="tanggal_terhitung"]').val().trim();
                if (tanggal_terhitung === '') {
                    e.preventDefault();
                    alert("Tanggal Terhitung is mandatory");
                }
                let id_perusahaan = $('select[name="id_perusahaan"]').val();
                if (id_perusahaan === '') {
                    e.preventDefault();
                    alert("Perusahaan is mandatory");
                }
                let id_divisi = $('select[name="id_divisi"]').val();
                if (id_divisi === '') {
                    e.preventDefault();
                    alert("Direktorat is mandatory");
                }
                let id_departemen = $('select[name="id_departemen"]').val();
                if (id_departemen === '') {
                    e.preventDefault();
                    alert("Divisi is mandatory");
                }
                let id_sub_departemen = $('select[name="id_sub_departemen"]').val();
                if (id_sub_departemen === '') {
                    e.preventDefault();
                    alert("Departemen is mandatory");
                }
                let id_level = $('select[name="id_level"]').val();
                if (id_level === '') {
                    e.preventDefault();
                    alert("Level is mandatory");
                }
                let id_jabatan = $('select[name="id_jabatan"]').val();
                if (id_jabatan === '') {
                    e.preventDefault();
                    alert("Jabatan is mandatory");
                }
            });
        });
        function toggle_id_status(source) {
            checkboxes = document.getElementsByName('id_status[]');
            for (var i = 0, n = checkboxes.length; i < n; i++) {
                checkboxes[i].checked = source.checked;
            }
        }

        function toggle_is_perpanjang(source) {
            checkboxes = document.getElementsByName('is_perpanjang[]');
            for (var i = 0, n = checkboxes.length; i < n; i++) {
                checkboxes[i].checked = source.checked;
            }
        }

        function toggle_perusahaan(source) {
            checkboxes = document.getElementsByName('perusahaan[]');
            for (var i = 0, n = checkboxes.length; i < n; i++) {
                checkboxes[i].checked = source.checked;
            }
        }
        // 
        $('#_datatable').DataTable({
            // dom: 'lBfrtip',
            dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
                "<'row'<'col-sm-12'rt>>" +
                "<'row'<'col-sm-5'i><'col-sm-7'p>>",
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ],
            "pageLength": 50,
            "lengthMenu": [
                [10, 50, 100, 200, 500, -1],
                [10, 50, 100, 200, 500, "All"]
            ]
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/formulir/penilaian/index.blade.php ENDPATH**/ ?>