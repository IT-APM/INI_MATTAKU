

<?php $__env->startSection('title', 'Home'); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/datatables.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/datatable-extension.css')); ?>">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<style>
    button.dt-button {
    float:none;
    text-align:center;
    color: black;
    background: white;
    }
     .datepicker {
        border: 1px solid grey !important;
        /* border-radius: 10px; */
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
    <h3>Dashboard</h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
    <li class="breadcrumb-item">Dashboard</li>
    <li class="breadcrumb-item active">Infografis</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-tabs nav-primary" id="pills-warningtab" role="tablist">
                        <li class="nav-item"><a class="nav-link <?php if($id == 0): ?> active <?php endif; ?>" href="<?php echo e(route('karyawan.dashboard', 0)); ?>">Terkini</a></li>
                        <li class="nav-item"><a class="nav-link <?php if($id == 1): ?> active <?php endif; ?>" href="<?php echo e(route('karyawan.dashboard', 1)); ?>">Histori/Year On Year</a></li>
                    </ul>
                    <?php $type = request()->get('type', 'bar'); ?>
                    <div class="tab-content" id="pills-warningtabContent">
                        
                        <div class="tab-pane fade <?php if($id == 0): ?> show active <?php endif; ?>" id="pills-current" role="tabpanel" aria-labelledby="pills-current-tab">
                            <a href="<?php echo e(request()->fullUrlWithQuery(['type' => 'bar'])); ?>" class="btn btn-outline-success mt-2" type="button"><i class="fa fa-bar-chart" aria-hidden="true"></i></a>&nbsp;&nbsp;<a href="<?php echo e(request()->fullUrlWithQuery(['type' => 'line'])); ?>" class="btn btn-outline-success mt-2" type="button"><i class="fa fa-line-chart" aria-hidden="true"></i></a><br/>
                            <button class="btn btn-warning mt-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_filter_form0" aria-expanded="false" aria-controls="collapse_filter"><i class="fa fa-search" aria-hidden="true"></i> Filter</button>
                            <div class="collapse" id="collapse_filter_form0">
                                <form id="form0" name="form0" class="mt-2" action="<?php echo e(route('karyawan.dashboard', 0)); ?>" method="get" enctype="multipart/form-data">
                                    <div class="row mt-2">
                                        <div class="col-sm-3">
                                            <label class="h6">Jenis Kelamin</label>&nbsp;<input type="checkbox" onClick="toggle_select_kelamin(this)" /> Check All
                                            <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                            <label class="d-block" for="select_kelamin">
                                            
                                                <?php $__currentLoopData = $select_kelamin; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <input class="checkbox_animated" id="select_kelamin[]" name="select_kelamin[]" type="checkbox" value="<?php echo e($data->id); ?>" <?php if(in_array($data->id, $arr_select_kelamin)): ?> checked <?php endif; ?>><?php echo e($data->kelamin); ?><br/>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </label>
                                            </div>
                                        
                                            <label class="h6">Status Karyawan</label>&nbsp;<input type="checkbox" onClick="toggle_select_status(this)" /> Check All
                                            <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                            <label class="d-block" for="select_status">
                                            
                                                <?php $__currentLoopData = $select_status; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <input class="checkbox_animated" id="select_status[]" name="select_status[]" type="checkbox" value="<?php echo e($data->id); ?>" <?php if(in_array($data->id, $arr_select_status)): ?> checked <?php endif; ?>><?php echo e($data->status_karyawan); ?><br/>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </label>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="h6">Level</label>&nbsp;<input type="checkbox" onClick="toggle_select_level(this)" /> Check All
                                            <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                            <label class="d-block" for="select_level">
                                            
                                                <?php $__currentLoopData = $select_level; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <input class="checkbox_animated" id="select_level[]" name="select_level[]" type="checkbox" value="<?php echo e($data->id); ?>" <?php if(in_array($data->id, $arr_select_level)): ?> checked <?php endif; ?>><?php echo e($data->level); ?><br/>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </label>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="h6">Generasi</label>&nbsp;<input type="checkbox" onClick="toggle_select_generasi(this)" /> Check All
                                            <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                            <label class="d-block" for="select_generasi">
                                            
                                                <?php $__currentLoopData = $select_generasi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <input class="checkbox_animated" id="select_generasi[]" name="select_generasi[]" type="checkbox" value="<?php echo e($data['id']); ?>" <?php if(in_array($data['id'], $arr_select_generasi)): ?> checked <?php endif; ?>><?php echo e($data['data']); ?><br/>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </label>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="h6">Pendidikan</label>&nbsp;<input type="checkbox" onClick="toggle_select_pendidikan(this)" /> Check All
                                            <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                            <label class="d-block" for="select_pendidikan">
                                            
                                                <?php $__currentLoopData = $select_pendidikan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <input class="checkbox_animated" id="select_pendidikan[]" name="select_pendidikan[]" type="checkbox" value="<?php echo e($data->id); ?>" <?php if(in_array($data->id, $arr_select_pendidikan)): ?> checked <?php endif; ?>><?php echo e($data->jenjang); ?><br/>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </label>
                                            </div>
                                        </div>
                                        
                                        <div class="col-sm-3">
                                            <label class="h6" style="cursor:pointer;" data-bs-toggle="collapse" data-bs-target="#box_perusahaan_form0">Perusahaan</label>&nbsp;<input type="checkbox" onClick="toggle_perusahaan('form0')" id="all_perusahaan" name="all_perusahaan"/> Check All
                                            <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                            <div class="collapse" id="box_perusahaan_form0">
                                                <?php $__currentLoopData = $perusahaan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <input class="checkbox_animated" id="kode_perusahaan[]" name="kode_perusahaan[]" type="checkbox" onClick="get_kode_perusahaan('form0')" value="<?php echo e($data->id); ?>" <?php if(in_array($data->id, $arr_perusahaan)): ?> checked <?php endif; ?>><?php echo e($data->nama); ?><br/>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="h6" style="cursor:pointer;" data-bs-toggle="collapse" data-bs-target="#div_kode_divisi">Direktorat</label>&nbsp;<input type="checkbox" onClick="toggle_divisi('form0')" id="all_divisi" name="all_divisi"/> Check All
                                            <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                            <div class="collapse" id="div_kode_divisi">
                                                <?php $__currentLoopData = $divisi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <input class="checkbox_animated" id="kode_divisi[]" name="kode_divisi[]" type="checkbox" onClick="get_kode_divisi('form0')" value="<?php echo e($data->id); ?>" <?php if(in_array($data->id, $arr_divisi)): ?> checked <?php endif; ?>><?php echo e($data->nama); ?><br/>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="h6" style="cursor:pointer;" data-bs-toggle="collapse" data-bs-target="#div_kode_departemen">Divisi</label>&nbsp;<input type="checkbox" onClick="toggle_kode_departemen('form0')" id="all_departemen" name="all_departemen"/> Check All
                                            <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                            <div class="collapse" id="div_kode_departemen">
                                                <?php $__currentLoopData = $departemen; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <input class="checkbox_animated" id="kode_departemen[]" name="kode_departemen[]" type="checkbox" onClick="get_kode_departemen('form0')" value="<?php echo e($data->id); ?>" <?php if(in_array($data->id, $arr_departemen)): ?> checked <?php endif; ?>><?php echo e($data->nama); ?><br/>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="h6" style="cursor:pointer;" data-bs-toggle="collapse" data-bs-target="#div_kode_sub_departemen">Departemen</label>&nbsp;<input type="checkbox" onClick="toggle_kode_sub_departemen('form0')" id="all_sub_departemen" name="all_sub_departemen"/> Check All
                                            <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                            <div class="collapse" id="div_kode_sub_departemen">
                                                <?php $__currentLoopData = $sub_departemen; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <input class="checkbox_animated" id="kode_sub_departemen[]" name="kode_sub_departemen[]" type="checkbox" onClick="get_kode_sub_departemen('form0')" value="<?php echo e($data->id); ?>" <?php if(in_array($data->id, $arr_sub_departemen)): ?> checked <?php endif; ?>><?php echo e($data->nama); ?><br/>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-sm-12">
                                            <button class="btn btn-warning" id="filter" name="filter" value="true" type="submit"><i class="fa fa-search" aria-hidden="true"></i> Filter</button> | <a href="<?php echo e(route('karyawan.dashboard', 0)); ?>" class="btn btn-info" type="button"><i class="fa fa-refresh" aria-hidden="true"></i> Reset</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="row mt-2">
                                <?php $i = 0; ?>
                                <div class="col-xl-4 col-sm-4">
                                    <div class="card-body text-center" style="cursor:pointer;" data-key="<?php echo e($lable[$i]); ?>" data-detail='<?php echo json_encode(collect($jumlah_karyawan_kelamin_now)->pluck('detail')->flatten(1), 15, 512) ?>' onclick="showDetail(this)">
                                        <h5 class="card-title"><?php echo e($lable[$i]); ?></h5>
                                        <div class="p-3">
                                            <img src="<?php echo e(asset('assets/images/matta/dashboard/people.png')); ?>" alt="People Icon" style="height:100px; object-fit:cover; border-radius:50%;">
                                        </div>
                                        <h2><?php echo e(array_sum(array_column($jumlah_karyawan_kelamin_now, 'jumlah'))); ?></h2>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-sm-4">
                                    <div class="card-body text-center" style="cursor:pointer;" data-key="<?php echo e($lable[$i]); ?>" <?php if(!empty($jumlah_karyawan_kelamin_now['Pria']['detail'])): ?> data-detail='<?php echo json_encode($jumlah_karyawan_kelamin_now['Pria']['detail'], 15, 512) ?>'  <?php else: ?> [] <?php endif; ?> onclick="showDetail(this)">
                                        <h5 class="card-title"><?php echo e($lable[++$i]); ?></h5>
                                        <div class="p-3">
                                            <img src="<?php echo e(asset('assets/images/matta/dashboard/male-removebg-preview.png')); ?>" alt="People Icon" style="height:100px; object-fit:cover; border-radius:50%;">
                                        </div>
                                        <h2><?php if(!empty($jumlah_karyawan_kelamin_now['Pria']['jumlah'])): ?><?php echo e($jumlah_karyawan_kelamin_now['Pria']['jumlah']); ?> <?php else: ?> 0 <?php endif; ?></h2>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-sm-4">
                                    <div class="card-body text-center" style="cursor:pointer;" data-key="<?php echo e($lable[$i]); ?>" <?php if(!empty($jumlah_karyawan_kelamin_now['Wanita']['detail'])): ?> data-detail='<?php echo json_encode($jumlah_karyawan_kelamin_now['Wanita']['detail'], 15, 512) ?>'  <?php else: ?> [] <?php endif; ?> onclick="showDetail(this)">
                                        <h5 class="card-title"><?php echo e($lable[++$i]); ?></h5>
                                        <div class="p-3">
                                            <img src="<?php echo e(asset('assets/images/matta/dashboard/female-removebg-preview.png')); ?>" alt="People Icon" style="height:100px; object-fit:cover; border-radius:50%;">
                                        </div>
                                        <h2><?php if(!empty($jumlah_karyawan_kelamin_now['Wanita']['jumlah'])): ?><?php echo e($jumlah_karyawan_kelamin_now['Wanita']['jumlah']); ?> <?php else: ?> 0 <?php endif; ?></h2>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-sm-6">
                                    <h5 class="text-center"><?php echo e($lable[++$i]); ?></h5>
                                    <div id="chart_jumlah_karyawan_kelamin_now"></div>
                                </div>
                                <div class="col-xl-6 col-sm-6">
                                    <h5 class="text-center"><?php echo e($lable[++$i]); ?></h5>
                                    <div id="chart_jumlah_karyawan_status_now"></div>
                                </div>
                                <div class="col-xl-6 col-sm-6">
                                    <h5 class="text-center"><?php echo e($lable[++$i]); ?></h5>
                                    <div id="chart_jumlah_karyawan_pendidikan_now"></div>
                                </div>
                                <div class="col-xl-6 col-sm-6">
                                    <h5 class="text-center"><?php echo e($lable[++$i]); ?></h5>
                                    <div id="chart_jumlah_karyawan_kelahiran_now"></div>
                                </div>
                                <div class="col-xl-6 col-sm-6">
                                    <h5 class="text-center"><?php echo e($lable[++$i]); ?></h5>
                                    <div id="chart_jumlah_karyawan_perusahaan_now"></div>
                                </div>
                                <div class="col-xl-6 col-sm-6">
                                    <h5 class="text-center"><?php echo e($lable[++$i]); ?></h5>
                                    <div id="chart_jumlah_karyawan_level_now"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="tab-pane fade <?php if($id == 1): ?> show active <?php endif; ?>" id="pills-history" role="tabpanel" aria-labelledby="pills-history-tab">
                            <a href="<?php echo e(request()->fullUrlWithQuery(['type' => 'bar'])); ?>" class="btn btn-outline-success mt-2" type="button"><i class="fa fa-bar-chart" aria-hidden="true"></i></a>&nbsp;&nbsp;<a href="<?php echo e(request()->fullUrlWithQuery(['type' => 'line'])); ?>" class="btn btn-outline-success mt-2" type="button"><i class="fa fa-line-chart" aria-hidden="true"></i></a><br/>
                            <button class="btn btn-warning mt-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_filter_form1" aria-expanded="false" aria-controls="collapse_filter"><i class="fa fa-search" aria-hidden="true"></i> Filter Histori</button>
                            <div class="collapse" id="collapse_filter_form1">
                                <form id="form1" name="form1" class="mt-2" action="<?php echo e(route('karyawan.dashboard', 1)); ?>" method="get" enctype="multipart/form-data">
                                    <div class="row mt-2">
                                        <div class="col-sm-3 mb-2">
                                            <label class="h6">Bulan-Tahun</label>
                                            <div>
                                                <input name="bulan_tahun_dari" id="bulan_tahun_dari" class="form-control digits" type="month" value="<?php echo e($bulan_tahun_dari); ?>">
                                            </div>
                                            sampai
                                            <div>
                                                <input name="bulan_tahun_sampai" id="bulan_tahun_sampai" class="form-control digits" type="month" value="<?php echo e($bulan_tahun_sampai); ?>">
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="h6">Jenis Kelamin</label>&nbsp;<input type="checkbox" onClick="toggle_select_kelamin(this)" /> Check All
                                            <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                            <label class="d-block" for="select_kelamin">
                                            
                                                <?php $__currentLoopData = $select_kelamin; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <input class="checkbox_animated" id="select_kelamin[]" name="select_kelamin[]" type="checkbox" value="<?php echo e($data->id); ?>" <?php if(in_array($data->id, $arr_select_kelamin)): ?> checked <?php endif; ?>><?php echo e($data->kelamin); ?><br/>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </label>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="h6">Status Karyawan</label>&nbsp;<input type="checkbox" onClick="toggle_select_status(this)" /> Check All
                                            <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                            <label class="d-block" for="select_status">
                                            
                                                <?php $__currentLoopData = $select_status; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <input class="checkbox_animated" id="select_status[]" name="select_status[]" type="checkbox" value="<?php echo e($data->id); ?>" <?php if(in_array($data->id, $arr_select_status)): ?> checked <?php endif; ?>><?php echo e($data->status_karyawan); ?><br/>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                            </label>
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="h6">Level</label>&nbsp;<input type="checkbox" onClick="toggle_select_level(this)" /> Check All
                                            <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                            <label class="d-block" for="select_level">
                                            
                                                <?php $__currentLoopData = $select_level; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <input class="checkbox_animated" id="select_level[]" name="select_level[]" type="checkbox" value="<?php echo e($data->id); ?>" <?php if(in_array($data->id, $arr_select_level)): ?> checked <?php endif; ?>><?php echo e($data->level); ?><br/>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </label>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="h6" style="cursor:pointer;" data-bs-toggle="collapse" data-bs-target="#box_perusahaan_form1">Perusahaan</label>&nbsp;<input type="checkbox" onClick="toggle_perusahaan('form1')" id="all_perusahaan" name="all_perusahaan"/> Check All
                                            <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                            <div class="collapse" id="box_perusahaan_form1">
                                                <?php $__currentLoopData = $perusahaan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <input class="checkbox_animated" id="kode_perusahaan[]" name="kode_perusahaan[]" type="checkbox" onClick="get_kode_perusahaan('form1')" value="<?php echo e($data->id); ?>" <?php if(in_array($data->id, $arr_perusahaan)): ?> checked <?php endif; ?>><?php echo e($data->nama); ?><br/>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="h6" style="cursor:pointer;" data-bs-toggle="collapse" data-bs-target="#div_kode_divisi">Direktorat</label>&nbsp;<input type="checkbox" onClick="toggle_divisi('form1')" id="all_divisi" name="all_divisi"/> Check All
                                            <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                            <div class="collapse" id="div_kode_divisi">
                                                <?php $__currentLoopData = $divisi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <input class="checkbox_animated" id="kode_divisi[]" name="kode_divisi[]" type="checkbox" onClick="get_kode_divisi('form1')" value="<?php echo e($data->id); ?>" <?php if(in_array($data->id, $arr_divisi)): ?> checked <?php endif; ?>><?php echo e($data->nama); ?><br/>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="h6" style="cursor:pointer;" data-bs-toggle="collapse" data-bs-target="#div_kode_departemen">Divisi</label>&nbsp;<input type="checkbox" onClick="toggle_kode_departemen('form1')" id="all_departemen" name="all_departemen"/> Check All
                                            <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                            <div class="collapse" id="div_kode_departemen">
                                                <?php $__currentLoopData = $departemen; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <input class="checkbox_animated" id="kode_departemen[]" name="kode_departemen[]" type="checkbox" onClick="get_kode_departemen('form1')" value="<?php echo e($data->id); ?>" <?php if(in_array($data->id, $arr_departemen)): ?> checked <?php endif; ?>><?php echo e($data->nama); ?><br/>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="h6" style="cursor:pointer;" data-bs-toggle="collapse" data-bs-target="#div_kode_sub_departemen">Departemen</label>&nbsp;<input type="checkbox" onClick="toggle_kode_sub_departemen('form1')" id="all_sub_departemen" name="all_sub_departemen"/> Check All
                                            <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                            <div class="collapse" id="div_kode_sub_departemen">
                                                <?php $__currentLoopData = $sub_departemen; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <input class="checkbox_animated" id="kode_sub_departemen[]" name="kode_sub_departemen[]" type="checkbox" onClick="get_kode_sub_departemen('form1')" value="<?php echo e($data->id); ?>" <?php if(in_array($data->id, $arr_sub_departemen)): ?> checked <?php endif; ?>><?php echo e($data->nama); ?><br/>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-sm-12">
                                            <button class="btn btn-warning" id="filter" name="filter" value="true" type="submit"><i class="fa fa-search" aria-hidden="true"></i> Filter</button> | <a href="<?php echo e(route('karyawan.dashboard', 1)); ?>" class="btn btn-info" type="button"><i class="fa fa-refresh" aria-hidden="true"></i> Reset</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <hr/>
                            <button class="btn btn-warning" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_filter_form2" aria-expanded="false" aria-controls="collapse_filter"><i class="fa fa-search" aria-hidden="true"></i> Filter Year On Year</button>
                            <div class="collapse" id="collapse_filter_form2">
                                <form id="form2" name="form2" class="mt-2" action="<?php echo e(route('karyawan.dashboard', 1)); ?>" method="get" enctype="multipart/form-data">
                                    <div class="row mt-2">
                                        <div class="col-sm-3 mb-2">
                                            <label class="h6">Tahun</label>
                                            <div>
                                                <input min="1" type="number" class="form-control digits" name="tahun_dari" id="tahun_dari" <?php if($tahun_dari>0): ?> value="<?php echo e($tahun_dari); ?>" <?php endif; ?>>
                                            </div>
                                            sampai
                                            <div>
                                                <input min="1" type="number" class="form-control digits" name="tahun_sampai" id="tahun_sampai" <?php if($tahun_sampai>0): ?> value="<?php echo e($tahun_sampai); ?>" <?php endif; ?>>
                                            </div>
                                        
                                            <label class="h6">Periode</label>
                                            <div>
                                                <select class="form-select" name="select_periode" id="select_periode">
                                                    <option value="">Pilih...</option>
                                                    <option value="01" <?php if($select_periode == "01"): ?> selected="" <?php endif; ?>>Januari</option>
                                                    <option value="02" <?php if($select_periode == "02"): ?> selected="" <?php endif; ?>>Februari</option>
                                                    <option value="03" <?php if($select_periode == "03"): ?> selected="" <?php endif; ?>>Maret</option>
                                                    <option value="04" <?php if($select_periode === "04"): ?> selected="" <?php endif; ?>>April</option>
                                                    <option value="05" <?php if($select_periode == "05"): ?> selected="" <?php endif; ?>>Mei</option>
                                                    <option value="06" <?php if($select_periode == "06"): ?> selected="" <?php endif; ?>>Juni</option>
                                                    <option value="07" <?php if($select_periode == "07"): ?> selected="" <?php endif; ?>>Juli</option>
                                                    <option value="08" <?php if($select_periode == "08"): ?> selected="" <?php endif; ?>>Agustus</option>
                                                    <option value="09" <?php if($select_periode == "09"): ?> selected="" <?php endif; ?>>September</option>
                                                    <option value="10" <?php if($select_periode == "10"): ?> selected="" <?php endif; ?>>Oktober</option>
                                                    <option value="11" <?php if($select_periode == "11"): ?> selected="" <?php endif; ?>>November</option>
                                                    <option value="12" <?php if($select_periode == "12"): ?> selected="" <?php endif; ?>>Desember</option>
                                                    <option disabled="" value="">--------</option>
                                                    <option value="01,02,03" <?php if($select_periode == "01,02,03"): ?> selected="" <?php endif; ?>>Kuartal 1</option>
                                                    <option value="04,05,06" <?php if($select_periode == "04,05,06"): ?> selected="" <?php endif; ?>>Kuartal 2</option>
                                                    <option value="07,08,09" <?php if($select_periode == "07,08,09"): ?> selected="" <?php endif; ?>>Kuartal 3</option>
                                                    <option value="10,11,12" <?php if($select_periode == "10,11,12"): ?> selected="" <?php endif; ?>>Kuartal 4</option>
                                                    <option disabled="" value="">--------</option>
                                                    <option value="01,02,03,04,05,06" <?php if($select_periode == "01,02,03,04,05,06"): ?> selected="" <?php endif; ?>>Semester 1</option>
                                                    <option value="07,08,09,10,11,12" <?php if($select_periode == "07,08,09,10,11,12"): ?> selected="" <?php endif; ?>>Semester 2</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="h6">Jenis Kelamin</label>&nbsp;<input type="checkbox" onClick="toggle_select_kelamin(this)" /> Check All
                                            <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                            <label class="d-block" for="select_kelamin">
                                            
                                                <?php $__currentLoopData = $select_kelamin; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <input class="checkbox_animated" id="select_kelamin[]" name="select_kelamin[]" type="checkbox" value="<?php echo e($data->id); ?>" <?php if(in_array($data->id, $arr_select_kelamin)): ?> checked <?php endif; ?>><?php echo e($data->kelamin); ?><br/>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </label>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="h6">Status Karyawan</label>&nbsp;<input type="checkbox" onClick="toggle_select_status(this)" /> Check All
                                            <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                            <label class="d-block" for="select_status">
                                            
                                                <?php $__currentLoopData = $select_status; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <input class="checkbox_animated" id="select_status[]" name="select_status[]" type="checkbox" value="<?php echo e($data->id); ?>" <?php if(in_array($data->id, $arr_select_status)): ?> checked <?php endif; ?>><?php echo e($data->status_karyawan); ?><br/>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </label>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="h6">Level</label>&nbsp;<input type="checkbox" onClick="toggle_select_level(this)" /> Check All
                                            <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                            <label class="d-block" for="select_level">
                                            
                                                <?php $__currentLoopData = $select_level; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <input class="checkbox_animated" id="select_level[]" name="select_level[]" type="checkbox" value="<?php echo e($data->id); ?>" <?php if(in_array($data->id, $arr_select_level)): ?> checked <?php endif; ?>><?php echo e($data->level); ?><br/>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </label>
                                            </div>
                                        </div>
                                        
                                        <div class="col-sm-3">
                                            <label class="h6" style="cursor:pointer;" data-bs-toggle="collapse" data-bs-target="#box_perusahaan_form2">Perusahaan</label>&nbsp;<input type="checkbox" onClick="toggle_perusahaan('form2')" id="all_perusahaan" name="all_perusahaan"/> Check All
                                            <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                            <div class="collapse" id="box_perusahaan_form2">
                                                <?php $__currentLoopData = $perusahaan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <input class="checkbox_animated" id="kode_perusahaan[]" name="kode_perusahaan[]" type="checkbox" onClick="get_kode_perusahaan('form2')" value="<?php echo e($data->id); ?>" <?php if(in_array($data->id, $arr_perusahaan)): ?> checked <?php endif; ?>><?php echo e($data->nama); ?><br/>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="h6" style="cursor:pointer;" data-bs-toggle="collapse" data-bs-target="#div_kode_divisi">Direktorat</label>&nbsp;<input type="checkbox" onClick="toggle_divisi('form2')" id="all_divisi" name="all_divisi"/> Check All
                                            <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                            <div class="collapse" id="div_kode_divisi">
                                                <?php $__currentLoopData = $divisi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <input class="checkbox_animated" id="kode_divisi[]" name="kode_divisi[]" type="checkbox" onClick="get_kode_divisi('form2')" value="<?php echo e($data->id); ?>" <?php if(in_array($data->id, $arr_divisi)): ?> checked <?php endif; ?>><?php echo e($data->nama); ?><br/>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="h6" style="cursor:pointer;" data-bs-toggle="collapse" data-bs-target="#div_kode_departemen">Divisi</label>&nbsp;<input type="checkbox" onClick="toggle_kode_departemen('form2')" id="all_departemen" name="all_departemen"/> Check All
                                            <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                            <div class="collapse" id="div_kode_departemen">
                                                <?php $__currentLoopData = $departemen; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <input class="checkbox_animated" id="kode_departemen[]" name="kode_departemen[]" type="checkbox" onClick="get_kode_departemen('form2')" value="<?php echo e($data->id); ?>" <?php if(in_array($data->id, $arr_departemen)): ?> checked <?php endif; ?>><?php echo e($data->nama); ?><br/>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="h6" style="cursor:pointer;" data-bs-toggle="collapse" data-bs-target="#div_kode_sub_departemen">Departemen</label>&nbsp;<input type="checkbox" onClick="toggle_kode_sub_departemen('form2')" id="all_sub_departemen" name="all_sub_departemen"/> Check All
                                            <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                            <div class="collapse" id="div_kode_sub_departemen">
                                                <?php $__currentLoopData = $sub_departemen; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <input class="checkbox_animated" id="kode_sub_departemen[]" name="kode_sub_departemen[]" type="checkbox" onClick="get_kode_sub_departemen('form2')" value="<?php echo e($data->id); ?>" <?php if(in_array($data->id, $arr_sub_departemen)): ?> checked <?php endif; ?>><?php echo e($data->nama); ?><br/>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-sm-12">
                                            <button class="btn btn-warning" id="filter" name="filter" value="true" type="submit"><i class="fa fa-search" aria-hidden="true"></i> Filter</button> | <a href="<?php echo e(route('karyawan.dashboard', 1)); ?>" class="btn btn-info" type="button"><i class="fa fa-refresh" aria-hidden="true"></i> Reset</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <hr/>
                            <div class="row mt-2">
                                <div class="col-xl-12 col-sm-12">
                                    <h5 class="text-center"><?php echo e($lable[++$i]); ?></h5>
                                    <div id="chart_jumlah_karyawan"></div>
                                </div>
                                <div class="col-xl-12 col-sm-12">
                                    <h5 class="text-center"><?php echo e($lable[++$i]); ?></h5>
                                    <div id="chart_jumlah_karyawan_kelamin"></div>
                                </div>
                                <div class="col-xl-12 col-sm-12">
                                    <h5 class="text-center"><?php echo e($lable[++$i]); ?></h5>
                                    <div id="chart_jumlah_karyawan_status"></div>
                                </div>
                                <div class="col-xl-12 col-sm-12">
                                    <h5 class="text-center"><?php echo e($lable[++$i]); ?></h5>
                                    <div id="chart_jumlah_karyawan_level"></div>
                                </div>
                                <div class="col-xl-12 col-sm-12">
                                    <h5 class="text-center"><?php echo e($lable[++$i]); ?></h5>
                                    <div id="chart_jumlah_karyawan_perusahaan"></div>
                                </div>
                                
                                
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="detailModal" tabindex="-1">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div id="detailContent"></div>
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
<script src="<?php echo e(asset('assets/js/chart/apex-chart/apex-chart.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/chart/apex-chart/stock-prices.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/chart/apex-chart/chart-custom.js')); ?>"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript">
// 
function showDetail(el) {
    const key = el.dataset.key;
    const rawData = JSON.parse(el.dataset.detail);
    const tableId = 'detailTable';
    const allDetail = Object.values(rawData).flatMap(item => item.detail || []);
    document.querySelector('#detailModal .modal-title').innerText = `Detail: ${key}`;
    let html = `
        <table id="${tableId}" class="table table-striped table-bordered w-100">
            <thead>
                <tr>
                    <th>NIP</th>
                    <th>Nama</th>
                </tr>
            </thead>
            <tbody>
    `;
    rawData.forEach(row => {
        html += `
            <tr>
                <td>${row.nik ?? ''}</td>
                <td>${row.nama ?? ''}</td>
            </tr>
        `;
    });
    html += `
            </tbody>
        </table>
    `;
    document.getElementById('detailContent').innerHTML = html;
    if ($.fn.DataTable.isDataTable('#' + tableId)) {
        $('#' + tableId).DataTable().destroy();
    }
    setTimeout(() => {
        $('#' + tableId).DataTable({
            order: [[1, 'asc']],
            dom:"<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
                "<'row'<'col-sm-12'rt>>" +
                "<'row'<'col-sm-5'i><'col-sm-7'p>>",
            buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
            lengthMenu: [[10, 50, 100, 200, 500, -1], [10, 50, 100, 200, 500, "All"]]
        });
    }, 100);
    const modal = new bootstrap.Modal(document.getElementById('detailModal'));
    modal.show();
}
// 
function get_kode_perusahaan(source) {
    getDivisi(source);
}
// 
function get_kode_divisi(source) {
    getDepartemen(source);
}
// 
function get_kode_departemen(source) {
    getSubDepartemen(source);
}
// 
function getDivisi(source) {
    let form = $("#" + source);
    var _kode_perusahaan = [];
    form.find('input[id="kode_perusahaan[]"]:checked').each(function() {
        _kode_perusahaan.push($(this).val());
    });
    form.find('#div_kode_divisi').empty();
    form.find('#div_kode_departemen').empty();
    form.find('#div_kode_sub_departemen').empty();
    // AJAX request 
    $.ajax({
        url: "<?php echo e(route('getDivisi')); ?>" + "/" + _kode_perusahaan,
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
                    var option = "<input class='checkbox_animated' id='kode_divisi[]' name='kode_divisi[]' type='checkbox' onClick='get_kode_divisi()' value='" + id + "'> " + nama + "<br/>";
                    form.find("#div_kode_divisi").append(option);
                }
            }
        }
    });
}
// 
function getDepartemen(source) {
    let form = $("#" + source);
    var _kode_divisi = [];
    form.find('input[id="kode_divisi[]"]:checked').each(function() {
        _kode_divisi.push($(this).val());
    });
    form.find('#div_kode_departemen').empty();
    form.find('#div_kode_sub_departemen').empty();
    $.ajax({
        url: "<?php echo e(route('getDepartemen')); ?>" + "/" + _kode_divisi,
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
                    var option = "<input class='checkbox_animated' id='kode_departemen[]' name='kode_departemen[]' type='checkbox' onClick='get_kode_departemen()' value='" + id + "'> " + nama + "<br/>";
                    form.find("#div_kode_departemen").append(option);
                }
            }
        }
    });
}
// 
function getSubDepartemen(source) {
    let form = $("#" + source);
    var _kode_departemen = [];
    form.find('input[id="kode_departemen[]"]:checked').each(function() {
        _kode_departemen.push($(this).val());
    });
    form.find('#div_kode_sub_departemen').empty();
    $.ajax({
        url: "<?php echo e(route('getSubDepartemen')); ?>" + "/" + _kode_departemen,
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
                    var option = "<input class='checkbox_animated' id='kode_sub_departemen[]' name='kode_sub_departemen[]' type='checkbox' value='" + id + "'> " + nama + "<br/>";
                    form.find("#div_kode_sub_departemen").append(option);
                }
            }
        }
    });
}
// 
$('#tahun_sampai').datepicker({
    format: "yyyy",
    viewMode: "years",
    minViewMode: "years",
    autoclose: true
});
$('#tahun_dari').datepicker({
    format: "yyyy",
    viewMode: "years",
    minViewMode: "years",
    autoclose: true
});
// 
function toggle_select_kelamin(source) {
    // let form = $("#" + source);
    // let isChecked = form.find("input[name='all_kelamin']").prop('checked');
    // form.find('input[name="select_kelamin[]"]').prop('checked', isChecked);
    checkboxes = document.getElementsByName('select_kelamin[]');
    for (var i = 0, n = checkboxes.length; i < n; i++) {
        checkboxes[i].checked = source.checked;
    }
}
function toggle_select_status(source) {
    // let form = $("#" + source);
    // let isChecked = form.find("input[name='all_status']").prop('checked');
    // form.find('input[name="select_status[]"]').prop('checked', isChecked);
    checkboxes = document.getElementsByName('select_status[]');
    for (var i = 0, n = checkboxes.length; i < n; i++) {
        checkboxes[i].checked = source.checked;
    }
}
function toggle_perusahaan(source) {
    let form = $("#" + source);
    let isChecked = form.find("input[name='all_perusahaan']").prop('checked');
    form.find('input[name="kode_perusahaan[]"]').prop('checked', isChecked);
    getDivisi(source);
}
function toggle_divisi(source) {
    let form = $("#" + source);
    let isChecked = form.find("input[name='all_divisi']").prop('checked');
    form.find('input[name="kode_divisi[]"]').prop('checked', isChecked);
    getDepartemen(source);
}
function toggle_kode_departemen(source) {
    let form = $("#" + source);
    let isChecked = form.find("input[name='all_departemen']").prop('checked');
    form.find('input[name="kode_departemen[]"]').prop('checked', isChecked);
    getSubDepartemen(source);
}
function toggle_kode_sub_departemen(source) {
    let form = $("#" + source);
    let isChecked = form.find("input[name='all_sub_departemen']").prop('checked');
    form.find('input[name="kode_sub_departemen[]"]').prop('checked', isChecked);
}
function toggle_select_level(source) {
    // let form = $("#" + source);
    // let isChecked = form.find("input[name='all_level']").prop('checked');
    // form.find('input[name="select_level[]"]').prop('checked', isChecked);
    checkboxes = document.getElementsByName('select_level[]');
    for (var i = 0, n = checkboxes.length; i < n; i++) {
        checkboxes[i].checked = source.checked;
    }
}
function toggle_select_pendidikan(source) {
    // let form = $("#" + source);
    // let isChecked = form.find("input[name='all_pendidikan']").prop('checked');
    // form.find('input[name="select_pendidikan[]"]').prop('checked', isChecked);
    checkboxes = document.getElementsByName('select_pendidikan[]');
    for (var i = 0, n = checkboxes.length; i < n; i++) {
        checkboxes[i].checked = source.checked;
    }
}
function toggle_select_generasi(source) {
    // let form = $("#" + source);
    // let isChecked = form.find("input[name='all_generasi']").prop('checked');
    // form.find('input[name="select_generasi[]"]').prop('checked', isChecked);
    checkboxes = document.getElementsByName('select_generasi[]');
    for (var i = 0, n = checkboxes.length; i < n; i++) {
        checkboxes[i].checked = source.checked;
    }
}
// 
$(document).ready(function() {
    // 
    var id = <?php echo e($id); ?>;
    // var select_periode = <?php echo e($select_periode); ?>;
    var tahun_dari = <?php echo e($tahun_dari); ?>;
    var tahun_sampai = <?php echo e($tahun_sampai); ?>;
    // alert(select_periode);
    if(id == 0){
        $('#form1').find('input, textarea, select').each(function() {
            if ($(this).is(':checkbox') || $(this).is(':radio')) {
                $(this).prop('checked', false);
            } else {
                $(this).val('');
            }
        });
        $('#form2').find('input, textarea, select').each(function() {
            if ($(this).is(':checkbox') || $(this).is(':radio')) {
                $(this).prop('checked', false);
            } else {
                $(this).val('');
            }
        });
        let form = $("#form0");
        let total = form.find("input[name='select_kelamin[]']").length;
        let checked = form.find("input[name='select_kelamin[]']:checked").length;
        form.find("#all_kelamin").prop("checked", total > 0 && total === checked);
        total = form.find("input[name='select_status[]']").length;
        checked = form.find("input[name='select_status[]']:checked").length;
        form.find("#all_status").prop("checked", total > 0 && total === checked);
        total = form.find("input[name='select_level[]']").length;
        checked = form.find("input[name='select_level[]']:checked").length;
        form.find("#all_level").prop("checked", total > 0 && total === checked);
        total = form.find("input[name='select_generasi[]']").length;
        checked = form.find("input[name='select_generasi[]']:checked").length;
        form.find("#all_generasi").prop("checked", total > 0 && total === checked);
        total = form.find("input[name='select_pendidikan[]']").length;
        checked = form.find("input[name='select_pendidikan[]']:checked").length;
        form.find("#all_pendidikan").prop("checked", total > 0 && total === checked);
        total = form.find("input[name='kode_perusahaan[]']").length;
        checked = form.find("input[name='kode_perusahaan[]']:checked").length;
        form.find("#all_perusahaan").prop("checked", total > 0 && total === checked);
        total = form.find("input[name='kode_divisi[]']").length;
        checked = form.find("input[name='kode_divisi[]']:checked").length;
        form.find("#all_divisi").prop("checked", total > 0 && total === checked);
        total = form.find("input[name='kode_departemen[]']").length;
        checked = form.find("input[name='kode_departemen[]']:checked").length;
        form.find("#all_departemen").prop("checked", total > 0 && total === checked);
        total = form.find("input[name='kode_sub_departemen[]']").length;
        checked = form.find("input[name='kode_sub_departemen[]']:checked").length;
        form.find("#all_sub_departemen").prop("checked", total > 0 && total === checked);
    }else if(id == 1 && tahun_dari == 0 && tahun_sampai == 0){
        $('#form0').find('input, textarea, select').each(function() {
            if ($(this).is(':checkbox') || $(this).is(':radio')) {
                $(this).prop('checked', false);
            } else {
                $(this).val('');
            }
        });
        $('#form2').find('input, textarea, select').each(function() {
            if ($(this).is(':checkbox') || $(this).is(':radio')) {
                $(this).prop('checked', false);
            } else {
                $(this).val('');
            }
        });
        let form = $("#form1");
        let total = form.find("input[name='select_kelamin[]']").length;
        let checked = form.find("input[name='select_kelamin[]']:checked").length;
        form.find("#all_kelamin").prop("checked", total > 0 && total === checked);
        total = form.find("input[name='select_status[]']").length;
        checked = form.find("input[name='select_status[]']:checked").length;
        form.find("#all_status").prop("checked", total > 0 && total === checked);
        total = form.find("input[name='select_level[]']").length;
        checked = form.find("input[name='select_level[]']:checked").length;
        form.find("#all_level").prop("checked", total > 0 && total === checked);
        total = form.find("input[name='kode_perusahaan[]']").length;
        checked = form.find("input[name='kode_perusahaan[]']:checked").length;
        form.find("#all_perusahaan").prop("checked", total > 0 && total === checked);
        total = form.find("input[name='kode_divisi[]']").length;
        checked = form.find("input[name='kode_divisi[]']:checked").length;
        form.find("#all_divisi").prop("checked", total > 0 && total === checked);
        total = form.find("input[name='kode_departemen[]']").length;
        checked = form.find("input[name='kode_departemen[]']:checked").length;
        form.find("#all_departemen").prop("checked", total > 0 && total === checked);
        total = form.find("input[name='kode_sub_departemen[]']").length;
        checked = form.find("input[name='kode_sub_departemen[]']:checked").length;
        form.find("#all_sub_departemen").prop("checked", total > 0 && total === checked);
    }else if(id == 1 && tahun_dari > 0 && tahun_sampai > 0){
        $('#form0').find('input, textarea, select').each(function() {
            if ($(this).is(':checkbox') || $(this).is(':radio')) {
                $(this).prop('checked', false);
            } else {
                $(this).val('');
            }
        });
        $('#form1').find('input, textarea, select').each(function() {
            if ($(this).is(':checkbox') || $(this).is(':radio')) {
                $(this).prop('checked', false);
            } else {
                $(this).val('');
            }
        });
        let form = $("#form2");
        let total = form.find("input[name='select_kelamin[]']").length;
        let checked = form.find("input[name='select_kelamin[]']:checked").length;
        form.find("#all_kelamin").prop("checked", total > 0 && total === checked);
        total = form.find("input[name='select_status[]']").length;
        checked = form.find("input[name='select_status[]']:checked").length;
        form.find("#all_status").prop("checked", total > 0 && total === checked);
        total = form.find("input[name='select_level[]']").length;
        checked = form.find("input[name='select_level[]']:checked").length;
        form.find("#all_level").prop("checked", total > 0 && total === checked);
        total = form.find("input[name='kode_perusahaan[]']").length;
        checked = form.find("input[name='kode_perusahaan[]']:checked").length;
        form.find("#all_perusahaan").prop("checked", total > 0 && total === checked);
        total = form.find("input[name='kode_divisi[]']").length;
        checked = form.find("input[name='kode_divisi[]']:checked").length;
        form.find("#all_divisi").prop("checked", total > 0 && total === checked);
        total = form.find("input[name='kode_departemen[]']").length;
        checked = form.find("input[name='kode_departemen[]']:checked").length;
        form.find("#all_departemen").prop("checked", total > 0 && total === checked);
        total = form.find("input[name='kode_sub_departemen[]']").length;
        checked = form.find("input[name='kode_sub_departemen[]']:checked").length;
        form.find("#all_sub_departemen").prop("checked", total > 0 && total === checked);
    }
    // 
    $('#form2').on('submit', function (e) {
        let form = $(this);
        let errors = [];
        let select = form.find($('#tahun_dari')).val();
        let select2 = form.find($('#tahun_sampai')).val();
        if (!select || !select2) {
            errors.push("Tahun is mandatory");
        }
        select = form.find($('#select_periode')).val();
        if (!select) {
            errors.push("Periode is mandatory");
        }
        select = form.find($('input[name="select_kelamin[]"]:checked')).length;
        if (select < 1) {
            errors.push("Jenis Kelamin is mandatory");
        }
        select = form.find($('input[name="select_status[]"]:checked')).length;
        if (select < 1) {
            errors.push("Status Karyawan is mandatory");
        }
        select = form.find($('input[name="select_level[]"]:checked')).length;
        if (select < 1) {
            errors.push("Level is mandatory");
        }
        select = form.find($('input[name="kode_perusahaan[]"]:checked')).length;
        if (select < 1) {
            errors.push("Perusahaan is mandatory");
        }
        select = form.find($('input[name="kode_divisi[]"]:checked')).length;
        if (select < 1) {
            errors.push("Direktorat is mandatory");
        }
        select = form.find($('input[name="kode_departemen[]"]:checked')).length;
        if (select < 1) {
            errors.push("Divisi is mandatory");
        }
        select = form.find($('input[name="kode_sub_departemen[]"]:checked')).length;
        if (select < 1) {
            errors.push("Departemen is mandatory");
        }
        if (errors.length > 0) {
            e.preventDefault();
            alert(errors.join('\n'));
        }
    });
    $('#form1').on('submit', function (e) {
        let form = $(this);
        let errors = [];
        let select = form.find($('#bulan_tahun_dari')).val();
        let select2 = form.find($('#bulan_tahun_sampai')).val();
        if (!select || !select2) {
            errors.push("Bulan Tahun is mandatory");
        }
        select = form.find($('input[name="select_kelamin[]"]:checked')).length;
        if (select < 1) {
            errors.push("Jenis Kelamin is mandatory");
        }
        select = form.find($('input[name="select_status[]"]:checked')).length;
        if (select < 1) {
            errors.push("Status Karyawan is mandatory");
        }
        select = form.find($('input[name="select_level[]"]:checked')).length;
        if (select < 1) {
            errors.push("Level is mandatory");
        }
        select = form.find($('input[name="kode_perusahaan[]"]:checked')).length;
        if (select < 1) {
            errors.push("Perusahaan is mandatory");
        }
        select = form.find($('input[name="kode_divisi[]"]:checked')).length;
        if (select < 1) {
            errors.push("Direktorat is mandatory");
        }
        select = form.find($('input[name="kode_departemen[]"]:checked')).length;
        if (select < 1) {
            errors.push("Divisi is mandatory");
        }
        select = form.find($('input[name="kode_sub_departemen[]"]:checked')).length;
        if (select < 1) {
            errors.push("Departemen is mandatory");
        }
        if (errors.length > 0) {
            e.preventDefault();
            alert(errors.join('\n'));
        }
    });
    $('#form0').on('submit', function (e) {
        let form = $(this);
        let errors = [];
        let select = form.find($('input[name="select_kelamin[]"]:checked')).length;
        if (select < 1) {
            errors.push("Jenis Kelamin is mandatory");
        }
        select = form.find($('input[name="select_status[]"]:checked')).length;
        if (select < 1) {
            errors.push("Status Karyawan is mandatory");
        }
        select = form.find($('input[name="select_level[]"]:checked')).length;
        if (select < 1) {
            errors.push("Level is mandatory");
        }
        select = form.find($('input[name="select_generasi[]"]:checked')).length;
        if (select < 1) {
            errors.push("Generasi is mandatory");
        }
        select = form.find($('input[name="kode_perusahaan[]"]:checked')).length;
        if (select < 1) {
            errors.push("Perusahaan is mandatory");
        }
        select = form.find($('input[name="kode_divisi[]"]:checked')).length;
        if (select < 1) {
            errors.push("Direktorat is mandatory");
        }
        select = form.find($('input[name="kode_departemen[]"]:checked')).length;
        if (select < 1) {
            errors.push("Divisi is mandatory");
        }
        select = form.find($('input[name="kode_sub_departemen[]"]:checked')).length;
        if (select < 1) {
            errors.push("Departemen is mandatory");
        }
        if (errors.length > 0) {
            e.preventDefault();
            alert(errors.join('\n'));
        }
    });
    // 
    function generateColors(count){
        let colors = [];
        for(let i=0;i<count;i++){
            let hue = Math.floor((360 / count) * i);
            colors.push(`hsl(${hue},70%,50%)`);
        }
        return colors;
    }
    // 
    function render_chart_v1(dataObj, containerId, chartType, height) {
        const categories = Object.keys(dataObj);
        const seriesKeys = [...new Set(Object.values(dataObj).flatMap(item => Object.keys(item)))].sort();
        const series = seriesKeys.map(key => ({
            name: key,
            data: Object.values(dataObj).map(item => item[key].jumlah || 0)
        })).filter(s => s.data.some(val => val > 0));
        const maxValue = Math.max(...series.flatMap(s => s.data)) + 5;
        const options = {
            chart: {
                type: chartType,
                height: height,
                zoom: { enabled: false },
                events: chartType === 'bar' ? {
                    dataPointSelection: function(event, chartContext, config) {
                        const index = config.dataPointIndex;
                        const seriesIndex = config.seriesIndex;
                        // nama category
                        const monthKey = Object.keys(dataObj)[index];
                        // nama series
                        const seriesName = config.w.config.series[seriesIndex].name;
                        // ambil object data
                        const rawData = dataObj[monthKey]?.[seriesName];
                        // ambil detail
                        const details = rawData?.detail || [];
                        document.querySelector('#detailModal .modal-title').innerText =`Detail: ${seriesName} - ${monthKey}`;
                        const tableId = 'detailTable';
                        let html = `
                            <table id="${tableId}" class="table table-striped table-bordered w-100">
                                <thead>
                                    <tr>
                                        <th>NIP</th>
                                        <th>Nama</th>
                                    </tr>
                                </thead>
                                <tbody>
                        `;
                        details.forEach(row => {
                            html += `
                                <tr>
                                    <td>${row.nik ?? ''}</td>
                                    <td>${row.nama ?? ''}</td>
                                </tr>
                            `;
                        });
                        html += `
                                </tbody>
                            </table>
                        `;
                        document.getElementById('detailContent').innerHTML = html;
                        // destroy datatable lama jika ada
                        if ($.fn.DataTable.isDataTable('#' + tableId)) {
                            $('#' + tableId).DataTable().destroy();
                        }
                        setTimeout(() => {
                            $('#' + tableId).DataTable({
                                order: [[1, 'asc']],
                                dom:
                                    "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
                                    "<'row'<'col-sm-12'rt>>" +
                                    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                                buttons: [
                                    'copy',
                                    'csv',
                                    'excel',
                                    'pdf',
                                    'print'
                                ],
                                lengthMenu: [
                                    [10, 50, 100, 200, 500, -1],
                                    [10, 50, 100, 200, 500, "All"]
                                ]
                            });
                        }, 100);
                        // tampilkan modal
                        const modal = new bootstrap.Modal(document.getElementById('detailModal'));
                        modal.show();
                    }
                } : {}
            },
            series: series,
            // colors: generateColors(data.length),
            // colors: [
            //     '#198754',
            //     '#dc3545',
            //     '#ffc107',
            // ],
            dataLabels: {
                // enabled: true,
                style: {
                    // fontSize: '10px',
                    colors: ['#000'],
                }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '95%',
                    borderRadius: 3,
                    borderRadiusApplication: 'end',
                }
            },
            xaxis: {
                categories: categories,
                // tickPlacement: chartType === 'line' ? 'between' : 'on',
                tickPlacement: 'between',
            },
            yaxis: {
                min: 0,
                max: maxValue,
                title: { text: 'Jumlah' }
            },
            legend: { position: 'top' },
            fill: { opacity: 0.8 },
        };
        if (chartType === 'line') {
            options.stroke = {
                curve: 'smooth',
                width: 3
            };
            // options.markers = { size: 5 };
        }
        const chart = new ApexCharts(document.querySelector(`#${containerId}`), options);
        chart.render();
    }
    // 
    function render_chart_v2(dataObj, containerId, chartType, height) {
        const categories = Object.keys(dataObj);
        const series = Object.values(dataObj).map(item => item.Aktif);
        const maxValue = Math.max(...series) + 5;
        const options = {
            chart: {
                type: chartType,
                height: height,
                zoom: { enabled: false },
            },
            series: [{
                name: Object.keys(dataObj[categories[0]])[0],
                data: series
            }],
            // colors: [
            //     '#198754',
            //     '#dc3545',
            //     '#ffc107',
            // ],
            dataLabels: {
                // enabled: true,
                style: {
                    fontSize: '10px',
                    colors: ['#000'],
                }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '95%',
                    borderRadius: 3,
                    borderRadiusApplication: 'end',
                }
            },
            xaxis: {
                categories: categories,
                // tickPlacement: chartType === 'line' ? 'between' : 'on',
                tickPlacement: 'between',
            },
            yaxis: {
                min: 0,
                max: maxValue,
                title: { text: 'Jumlah' }
            },
            legend: { position: 'top' },
            fill: { opacity: 0.8 },
            dataLabels: {
                style: { colors: ['#000'] }
            },
        };
        if (chartType === 'line') {
            options.stroke = {
                curve: 'smooth',
                width: 3
            };
            // options.markers = { size: 5 };
        }
        const chart = new ApexCharts(document.querySelector(`#${containerId}`), options);
        chart.render();
    }
    // 
    function render_chart_v3(dataObj, containerId, chartType, height) {
        const labels = Object.keys(dataObj);
        const series = Object.values(dataObj).map(item => item.jumlah);
        const options = {
            chart: {
                type: chartType,
                height: height,
                zoom: { enabled: false },
                events: (chartType === 'pie') ? {
                    dataPointSelection: function(event, chartContext, config) {
                        const index = config.dataPointIndex;
                        let key;
                        key = config.w.globals.labels[index];
                        const rawData = dataObj[key];
                        document.querySelector('#detailModal .modal-title').innerText = `Detail: ${key}`;
                        const tableId = 'detailTable';
                        let html = `
                            <table id="${tableId}" class="table table-striped table-bordered w-100">
                                <thead>
                                    <tr>
                                        <th>NIP</th>
                                        <th>Nama</th>
                                    </tr>
                                </thead>
                                <tbody>
                        `;
                        rawData.detail.forEach(row => {
                            html += `
                                <tr>
                                    <td>${row.nik ?? ''}</td>
                                    <td>${row.nama ?? ''}</td>
                                </tr>
                            `;
                        });
                        html += `
                                </tbody>
                            </table>
                        `;
                        document.getElementById('detailContent').innerHTML = html;
                        if ($.fn.DataTable.isDataTable('#' + tableId)) {
                            $('#' + tableId).DataTable().destroy();
                        }
                        setTimeout(() => {
                            $('#' + tableId).DataTable({
                                order: [[1, 'asc']],
                                dom:"<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
                                    "<'row'<'col-sm-12'rt>>" +
                                    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                                buttons: [
                                    'copy', 'csv', 'excel', 'pdf', 'print'
                                ],
                                lengthMenu: [[10, 50, 100, 200, 500, -1], [10, 50, 100, 200, 500, "All"]]
                            });
                        }, 100);
                        const modal = new bootstrap.Modal(document.getElementById('detailModal'));
                        modal.show();
                    }
                } : {}
            },
            series: series,
            labels: labels,
            // colors: ['#95a5a6', '#3498db', '#e74c3c'],
            legend: {
                position: 'top'
            },
            // dataLabels: {
            //     enabled: true,
            //     formatter: function (val, opts) {
            //         return opts.w.globals.labels[opts.seriesIndex] + ": " + val;
            //     }
            // }
        };
        const chart = new ApexCharts(document.querySelector(`#${containerId}`), options);
        chart.render();
    }
    // 
    function render_chart_v4(dataObj, containerId, chartType, height, colors) {
        const categories = Object.keys(dataObj);
        const series = Object.values(dataObj).map(item => item.jumlah);
        const maxValue = Math.max(...series) + 5;
        const options = {
            chart: {
                type: chartType,
                height: height,
                zoom: { enabled: false },
                events: chartType === 'bar' ? {
                    dataPointSelection: function(event, chartContext, config) {
                        const index = config.dataPointIndex;
                        const key = Object.keys(dataObj)[index];
                        const rawData = dataObj[key];
                        document.querySelector('#detailModal .modal-title').innerText = `Detail: ${key}`;
                        const tableId = 'detailTable';
                        let html = `
                            <table id="${tableId}" class="table table-striped table-bordered w-100">
                                <thead>
                                    <tr>
                                        <th>NIP</th>
                                        <th>Nama</th>
                                    </tr>
                                </thead>
                                <tbody>
                        `;
                        rawData.detail.forEach(row => {
                            html += `
                                <tr>
                                    <td>${row.nik ?? ''}</td>
                                    <td>${row.nama ?? ''}</td>
                                </tr>
                            `;
                        });
                        html += `
                                </tbody>
                            </table>
                        `;
                        document.getElementById('detailContent').innerHTML = html;
                        if ($.fn.DataTable.isDataTable('#' + tableId)) {
                            $('#' + tableId).DataTable().destroy();
                        }
                        setTimeout(() => {
                            $('#' + tableId).DataTable({
                                order: [[1, 'asc']],
                                // dom: 'lBfrtip',
                                dom:"<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
                                "<'row'<'col-sm-12'rt>>" +
                                "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                                buttons: [
                                    'copy', 'csv', 'excel', 'pdf', 'print'
                                ],
                                "lengthMenu": [[10, 50, 100, 200, 500, -1], [10, 50, 100, 200, 500,  "All"]]
                            });
                        }, 100);
                        // show modal
                        const modal = new bootstrap.Modal(document.getElementById('detailModal'));
                        modal.show();
                    }
                } : {}
            },
            series: [{
                name: 'Jumlah',
                data: series
            }],
            // colors: [
            //     '#008FFB', biru
            //     '#00E396', hijau
            //     '#FEB019', orange
            //     '#FF4560', merah
            //     '#775DD0' ungu
            // ],
            colors: colors,
            xaxis: {
                categories: categories,
                // tickPlacement: chartType === 'line' ? 'between' : 'on',
                tickPlacement: 'between',
                labels: {
                    rotate: -30,
                    // offsetY: -5,
                    rotateAlways: true,
                    hideOverlappingLabels: false,
                    trim: false,
                    // offsetY: 50,
                },
            },
            // padding: {
            //     bottom: 500
            // },
            yaxis: {
                min: 0,
                max: maxValue,
                title: { text: 'Jumlah' }
            },
            legend: { position: 'top' },
            fill: { opacity: 0.8 },
            dataLabels: {
                // enabled: true,
                style: { colors: ['#000'] }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '95%',
                    borderRadius: 3,
                    borderRadiusApplication: 'end',
                }
            },
        };
        if (chartType === 'line') {
            options.stroke = {
                curve: 'smooth',
                width: 3
            };
            // options.markers = { size: 5 };
        }
        const chart = new ApexCharts(document.querySelector(`#${containerId}`), options);
        chart.render();
    }
    // now
    render_chart_v3(<?php echo json_encode($jumlah_karyawan_kelamin_now, 15, 512) ?>, 'chart_jumlah_karyawan_kelamin_now', 'pie', 300);
    render_chart_v3(<?php echo json_encode($jumlah_karyawan_status_now, 15, 512) ?>, 'chart_jumlah_karyawan_status_now', 'pie', 300);
    render_chart_v4(<?php echo json_encode($jumlah_karyawan_perusahaan_now, 15, 512) ?>, 'chart_jumlah_karyawan_perusahaan_now', '<?php echo e($type); ?>', 400, ['#008FFB']);
    render_chart_v4(<?php echo json_encode($jumlah_karyawan_level_now, 15, 512) ?>, 'chart_jumlah_karyawan_level_now', '<?php echo e($type); ?>', 400, ['#775DD0']);
    render_chart_v4(<?php echo json_encode($jumlah_karyawan_pendidikan_now, 15, 512) ?>, 'chart_jumlah_karyawan_pendidikan_now', '<?php echo e($type); ?>', 400, ['#00E396']);
    render_chart_v4(<?php echo json_encode($jumlah_karyawan_kelahiran_now, 15, 512) ?>, 'chart_jumlah_karyawan_kelahiran_now', '<?php echo e($type); ?>', 400, ['#FEB019']);
    // history
    render_chart_v1(<?php echo json_encode($jumlah_karyawan, 15, 512) ?>, 'chart_jumlah_karyawan', '<?php echo e($type); ?>', 300);
    render_chart_v1(<?php echo json_encode($jumlah_karyawan_kelamin, 15, 512) ?>, 'chart_jumlah_karyawan_kelamin', '<?php echo e($type); ?>', 300);
    render_chart_v1(<?php echo json_encode($jumlah_karyawan_status, 15, 512) ?>, 'chart_jumlah_karyawan_status', '<?php echo e($type); ?>', 300);
    render_chart_v1(<?php echo json_encode($jumlah_karyawan_level, 15, 512) ?>, 'chart_jumlah_karyawan_level', '<?php echo e($type); ?>', 300);
    render_chart_v1(<?php echo json_encode($jumlah_karyawan_perusahaan, 15, 512) ?>, 'chart_jumlah_karyawan_perusahaan', '<?php echo e($type); ?>', 300);
    // render_chart_v1(<?php echo json_encode($jumlah_karyawan_divisi, 15, 512) ?>, 'chart_jumlah_karyawan_divisi', '<?php echo e($type); ?>', 300);
    // render_chart_v1(<?php echo json_encode($jumlah_karyawan_dep, 15, 512) ?>, 'chart_jumlah_karyawan_dep', '<?php echo e($type); ?>', 300);
    // render_chart_v1(<?php echo json_encode($jumlah_karyawan_subdep, 15, 512) ?>, 'chart_jumlah_karyawan_subdep', '<?php echo e($type); ?>', 700);
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/master/karyawan/dashboard.blade.php ENDPATH**/ ?>