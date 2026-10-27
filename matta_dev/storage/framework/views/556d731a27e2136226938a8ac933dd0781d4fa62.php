
<?php $__env->startSection('title', 'Biaya Perjalanan Dinas'); ?>

<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/datatables.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/datatable-extension.css')); ?>">
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
    <h3>Biaya Perjalanan Dinas</h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
    <li class="breadcrumb-item">Formulir</li>
    <li class="breadcrumb-item active">Biaya Perjalanan Dinas</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <?php if(Session::has('msg')): ?>
                            <div class="alert alert-success dark alert-dismissible fade show" role="alert">
                                <h6><?php echo e(session('msg')); ?></h6>
                                <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>
                        <?php if(Session::has('error')): ?>
                            <div class="alert alert-danger dark alert-dismissible fade show" role="alert">
                                <h6><?php echo e(session('error')); ?></h6>
                                <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>
                        <form method="POST" action="#">
                            <div class="row">
                                <?php if(count($mgt) > 0): ?>
                                    <div class="col-sm-3">
                                        <label class="form-label">Grup Manajemen</label>
                                        <select class="form-select col-sm-12" id="mgt" name="mgt" required="">
                                            <option selected="" disabled="" value="0">Pilih...</option>
                                            <?php $__currentLoopData = $mgt; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($data->nik); ?>"><?php echo e($data->_nik->nama); ?> / <?php echo e($data->nik); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                <?php endif; ?>
                                <div class="col-sm-3">
                                    <label class="form-label">Jenis Perjalanan Dinas</label>
                                    <select class="form-select col-sm-12" id="jenis" name="jenis" required="">
                                        <option selected="" disabled="" value="">Pilih...</option>
                                        <option value="1">CA Dalam Negeri</option>
                                        <option value="2">CA Luar Negeri</option>
                                        <option value="3">Reimbursement Dalam Negeri</option>
                                        <option value="4">Reimbursement Luar Negeri</option>
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    
                                    <button class="btn btn-primary mt-4" type="button" onclick="PilihJenis()"><i class="fa fa-plus" aria-hidden="true"></i> Add new</button>
                                    
                                </div>
                            </div>
                        </form>
                        <div class="col-sm-12 mt-2">
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('hc')): ?>
                                <a href="<?php echo e(route('bpd.exportexcel', [0, 'pengaju' => Request::get('pengaju'), 'perusahaan' => Request::input('perusahaan', []), 'luar_dalam' => Request::input('luar_dalam', []), 'status_id' => Request::input('status_id', [])])); ?>" class="btn btn-success" type="button"><i class="fa fa-download" aria-hidden="true"></i> BPD Info</a>&nbsp;|&nbsp;<a href="<?php echo e(route('bpd.exportexcel', [1, 'pengaju' => Request::get('pengaju'), 'perusahaan' => Request::input('perusahaan', []), 'luar_dalam' => Request::input('luar_dalam', []), 'status_id' => Request::input('status_id', [])])); ?>" class="btn btn-info" type="button"><i class="fa fa-download" aria-hidden="true"></i> BPD Biaya</a>
                            <?php endif; ?>
                            
                            <button class="btn btn-warning" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_filter" aria-expanded="false" aria-controls="collapse_filter"><i class="fa fa-search" aria-hidden="true"></i>Filter</button>
                            <div class="collapse" id="collapse_filter">
                                <form class="mt-2" action="<?php echo e(route('bpd.index')); ?>" method="get" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <label class="h6" for="tanggal_mulai">Tanggal dari</label>
                                            <input class="form-control" name="tanggal_mulai" id="tanggal_mulai" type="date" required="" value="<?php echo e($tanggal_mulai); ?>">
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="h6" for="tanggal_akhir">Tanggal sampai</label>
                                            <input class="form-control" name="tanggal_akhir" id="tanggal_akhir" type="date" required="" value="<?php echo e($tanggal_akhir); ?>">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <label class="h6" for="pengaju">Karyawan</label>
                                            <input name="pengaju" id="pengaju" class="form-control" type="text" value="<?php echo e($pengaju); ?>">
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="h6 mt-2">Perusahaan</label>&nbsp;<input type="checkbox" onClick="toggle_perusahaan(this)" /> Check All
                                            <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                            <label class="d-block" for="perusahaan"></label>
                                            <?php $__currentLoopData = $perusahaan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <input class="checkbox_animated" id="perusahaan[]" name="perusahaan[]" type="checkbox" value="<?php echo e($data->id); ?>" <?php if(in_array($data->id, $arr_perusahaan)): ?> checked <?php endif; ?>><?php echo e($data->nama); ?><br/>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="h6">Pengajuan</label>&nbsp;<input type="checkbox" onClick="toggle_luar_dalam(this)" /> Check All
                                            <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                            <label class="d-block" for="luar_dalam"></label>
                                            <?php $__currentLoopData = $luar_dalam; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $_data => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <input class="checkbox_animated" id="luar_dalam[]" name="luar_dalam[]" type="checkbox" value="<?php echo e($_data); ?>" <?php if(in_array($_data, $arr_luar_dalam)): ?> checked <?php endif; ?>><?php echo e($data); ?><br/>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="h6">Status</label>&nbsp;<input type="checkbox" onClick="toggle_status_id(this)" /> Check All
                                            <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                            <label class="d-block" for="status_id"></label>
                                            <?php $__currentLoopData = $status_id; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <input class="checkbox_animated" id="status_id[]" name="status_id[]" type="checkbox" value="<?php echo e($data->id); ?>" <?php if(in_array($data->id, $arr_status_id)): ?> checked <?php endif; ?>><?php echo e($data->status); ?><br/>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-sm-12">
                                            <button class="btn btn-warning" id="filter" name="filter" value="true" type="submit"><i class="fa fa-search" aria-hidden="true"></i>Filter</button> | <button class="btn btn-info" id="reset" name="reset" value="true" type="submit"><i class="fa fa-refresh" aria-hidden="true"></i> Reset</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            
                            
                            
                        </div>
                        <div class="table-responsive mt-2">
                            <table class="display" id="_datatable">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>#ID</th>
                                        <th>Nama / NIP</th>
                                        <th>Departemen</th>
                                        <th>Pengajuan</th>
                                        <th>Tahun</th>
                                        <th>Created at</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                        <!-- hco only -->
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('hc')): ?>
                                            <th><?php echo e(App\Models\MtrUtility::find(51)->informasi); ?></th>
                                        <?php endif; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $trx; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $pengaju_property = json_decode($data['pengaju_property'], true);
                                            $nik = auth()->user()->nik;
                                            $hco = App\Models\MtrUtility::find(24)->informasi;
                                        ?>
                                        <tr>
                                            <td><?php echo e(++$no); ?></td>
                                            <td><?php echo e($data->id); ?></td>
                                            <td><?php echo e($pengaju_property['nama']); ?> / <?php echo e($pengaju_property['nik']); ?></td>
                                            <td><?php echo e($pengaju_property['sub_dep']); ?></td>
                                            <td>
                                                <?php if($data->is_ca == 1 && $data->luar_dalam == 'ca_dalam'): ?>
                                                    CA Dalam Negeri
                                                <?php elseif($data->is_ca == 1 && $data->luar_dalam == 'ca_luar'): ?>
                                                    CA Luar Negeri
                                                <?php elseif($data->is_ca == 0 && $data->luar_dalam == 'reimburse'): ?>
                                                    Reimbursement Dalam Negeri
                                                <?php elseif($data->is_ca == 0 && $data->luar_dalam == 'reimburse_luar'): ?>
                                                    Reimbursement Luar Negeri
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo e($data->periode); ?></td>
                                            <td><?php echo e($data->created_at); ?></td>
                                            <td><?php echo e($data->_status->status); ?></td>
                                            <td>
                                                <?php
                                                    $where = [
                                                        'nik' => auth()->user()->nik,
                                                        'filename' => 'emails.bpd0',
                                                        'id_trx' => $data->id,
                                                    ];
                                                    $link = App\Models\ToDoList::where($where)
                                                        ->orderBy('id', 'desc')
                                                        ->first();
                                                    $where = ['filename' => 'emails.bpd0', 'id_trx' => $data->id];
                                                    $link_hco = App\Models\ToDoList::where($where)
                                                        ->orderBy('id', 'desc')
                                                        ->first();
                                                    $where = [
                                                        'nik' => $data->pengaju,
                                                        'id_peran' => 3,
                                                        'is_active' => 1,
                                                    ];
                                                    $is_mgt = App\Models\BpdNikPeran::where($where)->exists();
                                                ?>
                                                <!-- pengajuan CA -->
                                                <?php if(!empty($link)): ?>
                                                    <a href="<?php echo e($link->link); ?>" data-toggle="tooltip" title="Approve/Reject"><i class="icon-eye" style="color:red"></i></a>
                                                <?php endif; ?>
                                                <!-- realisasi CA -->
                                                <?php if($data->status_id == 13 && $nik == $data->pengaju): ?>
                                                    <a href="<?php echo e(route('bpd.show', [Crypt::encrypt($data->id), 'view' => 'realisasi'])); ?>" data-toggle="tooltip" title="Realisasi"><i class="icon-eye" style="color:red"></i></a>
                                                <?php elseif($data->status_id == 13 && $nik == $data->admin && $is_mgt): ?>
                                                    <a href="<?php echo e(route('bpd.show', [Crypt::encrypt($data->id), 'view' => 'realisasi'])); ?>" data-toggle="tooltip" title="Realisasi"><i class="icon-eye" style="color:red"></i></a>
                                                <?php elseif($data->status_id == 20 && $nik == $data->admin && $is_mgt): ?>
                                                    <a href="<?php echo e(route('bpd.show', [Crypt::encrypt($data->id), 'view' => 'reject_realisasi_pa'])); ?>" data-toggle="tooltip" title="Realisasi"><i class="icon-eye" style="color:red"></i></a>
                                                <?php elseif($data->status_id == 13 && $nik == $data->hco && $data->status_id != 48): ?>
                                                    <a href="<?php echo e(route('bpd.show', [Crypt::encrypt($data->id), 'view' => 'approval_hco'])); ?>" data-toggle="tooltip" title="Detail"><i class="icon-eye" style="color:red"></i></a>
                                                <?php elseif($data->status_id == 28 && $nik == $data->hco && $data->status_id != 48): ?>
                                                    <a href="<?php echo e(route('bpd.show', [Crypt::encrypt($data->id), 'view' => 'realisasi_hco'])); ?>" data-toggle="tooltip" title="Detail"><i class="icon-eye" style="color:red"></i></a>
                                                <?php elseif($data->status_id == 44 && $nik == $data->hco && $data->status_id != 48): ?>
                                                    <a href="<?php echo e(route('bpd.show', [Crypt::encrypt($data->id), 'view' => 'reimburse_hco'])); ?>" data-toggle="tooltip" title="Detail"><i class="icon-eye" style="color:red"></i></a>
                                                <?php endif; ?>
                                                <!-- history -->
                                                <a href="<?php echo e(route('bpd.show', [Crypt::encrypt($data->id), 'view' => 'history'])); ?>" data-toggle="tooltip" title="History"><i class="icon-eye"></i></a>
                                                <!-- view CA -->
                                                <?php if($data->status_id >= 4 && $data->status_id <= 31 && $data->status_id != 5 && $data->status_id != 6): ?>
                                                    <a href="<?php echo e(route('bpd.show', [Crypt::encrypt($data->id), 'view' => 'view_ca'])); ?>" data-toggle="tooltip" title="View CA" target="_blank"><i class="fa fa-file-text-o"></i></a>
                                                <?php endif; ?>
                                                <!-- view realisasi -->
                                                <?php if($data->status_id > 13 && $data->status_id <= 31 && $data->status_id != 14 && $data->status_id != 15): ?>
                                                    <a href="<?php echo e(route('bpd.show', [Crypt::encrypt($data->id), 'view' => 'view_realisasi'])); ?>" data-toggle="tooltip" title="View Realisasi" target="_blank"><i class="fa fa-file-text-o" style="color:red"></i></a>
                                                <?php endif; ?>
                                                <!-- view reimburse -->
                                                <?php if($data->status_id > 33 && $data->status_id < 48 && $data->status_id != 36 && $data->status_id != 37): ?>
                                                    <a href="<?php echo e(route('bpd.show', [Crypt::encrypt($data->id), 'view' => 'view_reimburse'])); ?>" data-toggle="tooltip" title="View Reimburse" target="_blank"><i class="fa fa-file-text-o"></i></a>
                                                <?php endif; ?>
                                                <!-- 31 = Selesai Realisasi -->
                                                <!-- 4 = CA Disetujui Admin -->
                                                <!-- 5 = CA Ditolak Admin -->
                                                <!-- 6 = Perbaikan CA oleh Pengaju -->
                                                <?php if($data->status_id >= 4 && $data->status_id <= 31 && $data->status_id != 5 && $data->status_id != 6): ?>
                                                    <a href="<?php echo e(route('bpd.show', [Crypt::encrypt($data->id), 'view' => 'pdf'])); ?>" data-toggle="tooltip" title="PDF CA" target="_blank"><i class="fa fa-file-pdf-o"></i></a>
                                                    <!-- 2 = Pengajuan CA -->
                                                    <!-- 3 = CA Diproses Admin -->
                                                    <!-- 5 = CA Ditolak Admin -->
                                                    <!-- 6 = Perbaikan CA oleh Pengaju -->
                                                <?php elseif($data->status_id == 2 || $data->status_id == 3 || $data->status_id == 5 || $data->status_id == 6): ?>
                                                    <a href="<?php echo e(route('bpd.show', [Crypt::encrypt($data->id), 'view' => 'pdf_draft'])); ?>" data-toggle="tooltip" title="PDF CA" target="_blank"><i class="fa fa-file-pdf-o"></i></a>
                                                <?php endif; ?>
                                                <!-- 13 = CA Disetujui PC -->
                                                <!-- 15 = Perbaikan CA oleh Admin -->
                                                <!-- 31 = Selesai Realisasi -->
                                                <?php if($data->status_id > 13 && $data->status_id <= 31 && $data->status_id != 14 && $data->status_id != 15): ?>
                                                    <a href="<?php echo e(route('bpd.show', [Crypt::encrypt($data->id), 'view' => 'pdf_realisasi'])); ?>" data-toggle="tooltip" title="PDF Realisasi" target="_blank"><i class="fa fa-file-pdf-o" style="color:red"></i></a>
                                                <?php endif; ?>
                                                <!-- 33 = Pengajuan Reimburse -->
                                                <!-- 34 = Reimburse Diproses Admin -->
                                                <!-- 36 = Reimburse Ditolak Admin -->
                                                <!-- 37 = Perbaikan Reimburse oleh Pengaju -->
                                                <?php if($data->status_id == 33 || $data->status_id == 34 || $data->status_id == 36 || $data->status_id == 37): ?>
                                                    <a href="<?php echo e(route('bpd.show', [Crypt::encrypt($data->id), 'view' => 'pdf_draft'])); ?>" data-toggle="tooltip" title="PDF Reimburse" target="_blank"><i class="fa fa-file-pdf-o"></i></a>
                                                <?php elseif($data->status_id > 34 && $data->status_id < 48 && $data->status_id != 36 && $data->status_id != 37): ?>
                                                    <a href="<?php echo e(route('bpd.show', [Crypt::encrypt($data->id), 'view' => 'pdf_reimburse'])); ?>" data-toggle="tooltip" title="PDF Reimburse" target="_blank"><i class="fa fa-file-pdf-o"></i></a>
                                                <?php endif; ?>
                                                <!-- 4=CA Disetujui Admin -->
                                                <!-- 15=Perbaikan CA oleh Admin -->
                                                <!-- 19=Realisasi Disetujui Admin -->
                                                <!-- 30=Perbaikan Realisasi oleh Admin -->
                                                <!-- 35=Reimburse Disetujui Admin -->
                                                <!-- 46=Perbaikan Reimburse oleh Admin -->
                                                <?php if(
                                                    ($data->status_id == 4 ||
                                                        $data->status_id == 15 ||
                                                        $data->status_id == 19 ||
                                                        $data->status_id == 30 ||
                                                        $data->status_id == 35 ||
                                                        $data->status_id == 46) &&
                                                        $nik == $data->admin): ?>
                                                    <a href="<?php echo e(route('bpd.show', [Crypt::encrypt($data->id), 'view' => 'edit'])); ?>" data-toggle="tooltip" title="Edit"><i class="icon-pencil-alt" style="color:green"></i></a>
                                                <?php endif; ?>
                                            </td>
                                            <!-- hco only -->
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('hc')): ?>
                                                <td>
                                                    <!-- start -->
                                                    <!-- realisasi CA -->
                                                    <?php if($data->status_id == 13): ?>
                                                        <a href="<?php echo e(route('bpd.show', [Crypt::encrypt($data->id), 'view' => 'realisasi'])); ?>" data-toggle="tooltip" title="Detail"><i class="icon-eye"></i></a>
                                                    <?php endif; ?>
                                                    <!-- finish -->
                                                    <?php if(!empty($link_hco)): ?>
                                                        <a href="<?php echo e($link_hco->link); ?>" data-toggle="tooltip" title="Approve/Reject"><i class="icon-eye" style="color:red"></i></a>
                                                    <?php endif; ?>
                                                    <!--  -->
                                                    <a href="<?php echo e(route('bpd.show', [Crypt::encrypt($data->id), 'view' => 'history'])); ?>" data-toggle="tooltip" title="History"><i class="icon-eye"></i></a>
                                                    <!-- view CA -->
                                                    <?php if($data->status_id >= 4 && $data->status_id <= 31 && $data->status_id != 5 && $data->status_id != 6): ?>
                                                        <a href="<?php echo e(route('bpd.show', [Crypt::encrypt($data->id), 'view' => 'view_ca'])); ?>" data-toggle="tooltip" title="View CA" target="_blank"><i class="fa fa-file-text-o"></i></a>
                                                    <?php endif; ?>
                                                    <!-- view realisasi -->
                                                    <?php if($data->status_id > 13 && $data->status_id <= 31 && $data->status_id != 14 && $data->status_id != 15): ?>
                                                        <a href="<?php echo e(route('bpd.show', [Crypt::encrypt($data->id), 'view' => 'view_realisasi'])); ?>" data-toggle="tooltip" title="View Realisasi" target="_blank"><i class="fa fa-file-text-o" style="color:red"></i></a>
                                                    <?php endif; ?>
                                                    <!-- view reimburse -->
                                                    <?php if($data->status_id > 33 && $data->status_id < 48 && $data->status_id != 36 && $data->status_id != 37): ?>
                                                        <a href="<?php echo e(route('bpd.show', [Crypt::encrypt($data->id), 'view' => 'view_reimburse'])); ?>" data-toggle="tooltip" title="View Reimburse" target="_blank"><i class="fa fa-file-text-o"></i></a>
                                                    <?php endif; ?>
                                                    <!-- 31 = Selesai Realisasi -->
                                                    <!-- 4 = CA Disetujui Admin -->
                                                    <!-- 5 = CA Ditolak Admin -->
                                                    <!-- 6 = Perbaikan CA oleh Pengaju -->
                                                    <?php if($data->status_id >= 4 && $data->status_id <= 31 && $data->status_id != 5 && $data->status_id != 6): ?>
                                                        <a href="<?php echo e(route('bpd.show', [Crypt::encrypt($data->id), 'view' => 'pdf'])); ?>" data-toggle="tooltip" title="PDF CA" target="_blank"><i class="fa fa-file-pdf-o"></i></a>
                                                        <!-- 2 = Pengajuan CA -->
                                                        <!-- 3 = CA Diproses Admin -->
                                                        <!-- 5 = CA Ditolak Admin -->
                                                        <!-- 6 = Perbaikan CA oleh Pengaju -->
                                                    <?php elseif($data->status_id == 2 || $data->status_id == 3 || $data->status_id == 5 || $data->status_id == 6): ?>
                                                        <a href="<?php echo e(route('bpd.show', [Crypt::encrypt($data->id), 'view' => 'pdf_draft'])); ?>" data-toggle="tooltip" title="PDF CA" target="_blank"><i class="fa fa-file-pdf-o"></i></a>
                                                    <?php endif; ?>
                                                    <!-- 13 = CA Disetujui PC -->
                                                    <!-- 15 = Perbaikan CA oleh Admin -->
                                                    <!-- 31 = Selesai Realisasi -->
                                                    <?php if($data->status_id > 13 && $data->status_id <= 31 && $data->status_id != 14 && $data->status_id != 15): ?>
                                                        <a href="<?php echo e(route('bpd.show', [Crypt::encrypt($data->id), 'view' => 'pdf_realisasi'])); ?>" data-toggle="tooltip" title="PDF Realisasi" target="_blank"><i class="fa fa-file-pdf-o" style="color:red"></i></a>
                                                    <?php endif; ?>
                                                    <!-- 33 = Pengajuan Reimburse -->
                                                    <!-- 34 = Reimburse Diproses Admin -->
                                                    <!-- 36 = Reimburse Ditolak Admin -->
                                                    <!-- 37 = Reimburse Ditolak Admin -->
                                                    <?php if($data->status_id == 33 || $data->status_id == 34 || $data->status_id == 36 || $data->status_id == 37): ?>
                                                        <a href="<?php echo e(route('bpd.show', [Crypt::encrypt($data->id), 'view' => 'pdf_draft'])); ?>" data-toggle="tooltip" title="PDF Reimburse" target="_blank"><i class="fa fa-file-pdf-o"></i></a>
                                                    <?php elseif($data->status_id > 34 && $data->status_id < 48 && $data->status_id != 36 && $data->status_id != 37): ?>
                                                        <a href="<?php echo e(route('bpd.show', [Crypt::encrypt($data->id), 'view' => 'pdf_reimburse'])); ?>" data-toggle="tooltip" title="PDF Reimburse" target="_blank"><i class="fa fa-file-pdf-o"></i></a>
                                                    <?php endif; ?>
                                                    <?php if($data->status_id != 48 && $nik == $hco): ?>
                                                        <a href="<?php echo e(route('bpd.show', [Crypt::encrypt($data->id), 'view' => 'cancel'])); ?>" data-toggle="tooltip" title="Cancel"><i class="fa fa-close" style="font-size:20px;color:red"></i></a>
                                                    <?php endif; ?>
                                                </td>
                                            <?php endif; ?>
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
    <script type="text/javascript">
        function toggle_perusahaan(source) {
            checkboxes = document.getElementsByName('perusahaan[]');
            for (var i = 0, n = checkboxes.length; i < n; i++) {
                checkboxes[i].checked = source.checked;
            }
        }
        function toggle_luar_dalam(source) {
            checkboxes = document.getElementsByName('luar_dalam[]');
            for (var i = 0, n = checkboxes.length; i < n; i++) {
                checkboxes[i].checked = source.checked;
            }
        }
        function toggle_status_id(source) {
            checkboxes = document.getElementsByName('status_id[]');
            for (var i = 0, n = checkboxes.length; i < n; i++) {
                checkboxes[i].checked = source.checked;
            }
        }
        // <?php echo e(route('bpd.create', ['new' => 'ca_luar'])); ?>

        function PilihJenis() {
            var mgt = 0;
            <?php if(count($mgt) > 0): ?>
                mgt = document.getElementById("mgt").value;
            <?php endif; ?>
            if (document.getElementById("jenis").value == 1) {
                location.href = "<?php echo e(route('bpd.create', ['new' => 'ca_dalam'])); ?>" + "&mgt=" + mgt;
            } else if (document.getElementById("jenis").value == 2) {
                location.href = "<?php echo e(route('bpd.create', ['new' => 'ca_luar'])); ?>" + "&mgt=" + mgt;
            } else if (document.getElementById("jenis").value == 3) {
                location.href = "<?php echo e(route('bpd.create', ['new' => 'reimburse'])); ?>" + "&mgt=" + mgt;
            } else if (document.getElementById("jenis").value == 4) {
                location.href = "<?php echo e(route('bpd.create', ['new' => 'reimburse_luar'])); ?>" + "&mgt=" + mgt;
            } else {
                alert('Pilih Jenis Perjalanan Dinas!');
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
            "lengthMenu": [
                [50, 100, 200, 500, -1],
                [50, 100, 200, 500, "All"]
            ]
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/formulir/bpd/index.blade.php ENDPATH**/ ?>