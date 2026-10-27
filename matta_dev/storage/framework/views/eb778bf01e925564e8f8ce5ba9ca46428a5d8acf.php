<?php $__env->startSection('title', 'Payroll'); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/datatables.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/datatable-extension.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/select2.css')); ?>">

<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<style>
    button.dt-button {
    float:none;
    text-align:center;
    color: black;
    background: white;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
<h3>Payroll Transaction</h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Payroll</li>
<li class="breadcrumb-item active">Payroll Transaction</li>
<?php $__env->stopSection(); ?>
<?php
$bulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
?>
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
                    <?php if(count($errors) > 0): ?>
                    <div class="alert alert-danger dark alert-dismissible fade show" role="alert">
                        <h6>Upload File is ERROR</h6>
                        <ul>
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php endif; ?>
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <button class="btn btn-warning" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_filter" aria-expanded="false" aria-controls="collapse_filter"><i class="fa fa-search" aria-hidden="true"></i> Filter</button>
                            <?php if(!Gate::allows('hc_nkp')): ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('payroll')): ?> | <a href="<?php echo e(route('payroll.master',[6, 'tipe'=>'trxpayroll'])); ?>" class="btn btn-primary" type="button"><i class="fa fa-plus"></i> Add new</a> | <button class="btn btn-info" type="button" data-bs-toggle="modal" data-original-title="test" data-bs-target="#exampleModal"><i class="fa fa-calendar"></i> Paid Date</button> <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="collapse mb-2" id="collapse_filter">
                        <form action="<?php echo e(route('payroll.index')); ?>" method="get" enctype="multipart/form-data" class="needs-validation" novalidate="">
                            <div class="row">
                                <div class="col-sm-2">
                                    <label class="h6" for="nama">Bulan-Tahun</label>
                                    <div>
                                        <input name="bulan_tahun_dari" id="bulan_tahun_dari" class="form-control digits" type="month" required="" value="<?php echo e($bulan_tahun_dari); ?>">
                                        <div class="invalid-feedback txt-danger">Field is required.</div>
                                    </div>
                                    sampai
                                    <div>
                                        <input name="bulan_tahun_sampai" id="bulan_tahun_sampai" class="form-control digits" type="month" required="" value="<?php echo e($bulan_tahun_sampai); ?>">
                                        <div class="invalid-feedback txt-danger">Field is required.</div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    
                                    <label class="h6 mt-2">Karyawan</label>&nbsp;<input type="checkbox" onClick="toggle_nik(this)" />&nbsp;Check All
                                    <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                        <label class="d-block" for="nik">
                                            <?php $__currentLoopData = $karyawan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <input class="checkbox_animated" id="nik[]" name="nik[]" type="checkbox" value="<?php echo e($data->nik); ?>" <?php if(in_array($data->nik, $_arr_nik)): ?> checked <?php endif; ?>><?php echo e($data->nama); ?> - <?php echo e($data->nik); ?><br/>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <label class="h6 mt-2">Status</label>&nbsp;<input type="checkbox" onClick="toggle_status_karyawan(this)" />&nbsp;Check All
                                    <label class="d-block" for="status_karyawan">
                                        <?php $__currentLoopData = $status_karyawan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <input class="checkbox_animated" id="status_karyawan[]" name="status_karyawan[]" type="checkbox" value="<?php echo e($data->status_karyawan); ?>" <?php if(in_array($data->status_karyawan, $_arr_status_karyawan)): ?> checked <?php endif; ?>> <?php echo e($data->status_karyawan); ?><br/>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </label>
                                </div>
                                <div class="col-sm-4">
                                    <label class="h6 mt-2">Level</label>&nbsp;<input type="checkbox" onClick="toggle_level(this)" />&nbsp;Check All
                                    <label class="d-block" for="level">
                                        <?php $__currentLoopData = $level; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <input class="checkbox_animated" id="level[]" name="level[]" type="checkbox" value="<?php echo e($data->level); ?>" <?php if(in_array($data->level, $_arr_level)): ?> checked <?php endif; ?>> <?php echo e($data->level); ?><br/>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </label>
                                    
                                </div>
                                
                            </div>
                            <div class="row mt-2">
                                <div class="col-sm-12">
                                    <button class="btn btn-warning" id="filter" name="filter" value="true" type="submit"><i class="fa fa-search" aria-hidden="true"></i> Filter</button> | <button class="btn btn-info" id="reset" name="reset" value="true" type="submit"><i class="fa fa-refresh" aria-hidden="true"></i> Reset</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <?php if(!Gate::allows('hc_nkp')): ?>
                    <h4>Alita</h4>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('payroll')): ?>
                    <ul class="nav nav-tabs nav-primary" id="pills-warningtab" role="tablist">
                        <li class="nav-item"><a class="nav-link active" id="pills-semua-tab" data-bs-toggle="pill" href="#pills-semua" role="tab" aria-controls="pills-semua" aria-selected="true">Upah</a></li>
                        <li class="nav-item"><a class="nav-link" id="pills-potongan-tab" data-bs-toggle="pill" href="#pills-potongan" role="tab" aria-controls="pills-potongan" aria-selected="true">Potongan</a></li>
                        <li class="nav-item"><a class="nav-link" id="pills-bpjstk-tab" data-bs-toggle="pill" href="#pills-bpjstk" role="tab" aria-controls="pills-bpjstk" aria-selected="true">BPJS TK</a></li>
                        <li class="nav-item"><a class="nav-link" id="pills-bpjskes-tab" data-bs-toggle="pill" href="#pills-bpjskes" role="tab" aria-controls="pills-bpjskes" aria-selected="true">BPJS Kesehatan</a></li>
                        <li class="nav-item"><a class="nav-link" id="pills-kerja-tab" data-bs-toggle="pill" href="#pills-kerja" role="tab" aria-controls="pills-kerja" aria-selected="true">PPh21</a></li>
                    </ul>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('payroll_fin')): ?>
                    <ul class="nav nav-tabs nav-primary" id="pills-warningtab" role="tablist">
                        <li class="nav-item"><a class="nav-link active" id="pills-kerja-tab" data-bs-toggle="pill" href="#pills-kerja" role="tab" aria-controls="pills-kerja" aria-selected="true">PPh21</a></li>
                    </ul>
                    <?php endif; ?>
                    <div class="tab-content" id="pills-warningtabContent">
                        
                        <div class="tab-pane fade <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('payroll')): ?> show active <?php endif; ?>" id="pills-semua" role="tabpanel" aria-labelledby="pills-semua-tab">
                            <p class="mb-0 mt-2">
                                <div class="row g-3 mb-2">
                                    <div class="col-sm-6">
                                        <h6>Upload Payroll</h6>
                                        <form action="<?php echo e(route('payroll.upload_payroll')); ?>" method="POST" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to upload this data?');">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" id="jenis" name="jenis" value="trx">
                                        <div class="row g-3">
                                            <div class="col-md-8">
                                                <input name="template" id="template" class="form-control" type="file" required="">
                                                <label class="form-label">Maksimal 2 MB dengan format xls/xlsx</label>
                                                <br/><a href="<?php echo e(asset('assets/template/template_import_payroll_alita_v1.xlsx')); ?>" target="_blank"><i class="icon-file"></i>&nbsp;Download template Alita</a>
                                                <br/><a href="<?php echo e(asset('assets/template/template_import_payroll_outsource_v1.xlsx')); ?>" target="_blank"><i class="icon-file"></i>&nbsp;Download template Out Source</a>
                                            </div>
                                            <div class="col-md-4">
                                                <button type="submit" class="btn btn-success"><i class="fa fa-upload"></i> Upload</button>
                                            </div>
                                        </div>
                                        </form>
                                    </div>
                                    <div class="col-sm-6">
                                        <h6>Upload Tunjangan Hari Raya</h6>
                                        <form action="<?php echo e(route('payroll.upload_payroll')); ?>" method="POST" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to upload this data?');">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" id="jenis" name="jenis" value="thr">
                                        <div class="row g-3">
                                            <div class="col-md-8">
                                                <input name="template" id="template" class="form-control" type="file" required="">
                                                <label class="form-label">Maksimal 2 MB dengan format xls/xlsx</label>
                                                <br/><a href="<?php echo e(asset('assets/template/template_upload_thr_v1.xlsx')); ?>" target="_blank"><i class="icon-file"></i>&nbsp;Download template</a>
                                            </div>
                                            <div class="col-md-4">
                                                <button type="submit" class="btn btn-success"><i class="fa fa-upload"></i> Upload</button>
                                            </div>
                                        </div>
                                        </form>
                                    </div>
                                </div>
                                
                                <?php if(count($payroll)>0): ?>
                                <!-- The Modal -->
                                <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Paid Date</h5>
                                            
                                            </div>
                                            <form action="<?php echo e(route('payroll.upload_payroll')); ?>" method="POST" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                                            <?php echo csrf_field(); ?>
                                            <div class="modal-body">
                                                <input type="hidden" id="jenis" name="jenis" value="paid_at">
                                                <input type="hidden" id="id" name="id" value="<?php echo e($payroll[0]->id); ?>">
                                                <input name="paid_at" id="paid_at" class="form-control" type="date" value="<?php if(!empty($payroll[0]->paid_at)): ?><?php echo e($payroll[0]->paid_at); ?>"<?php endif; ?>></div>
                                            <div class="modal-footer">
                                            <button class="btn btn-success mt-2" type="submit">Submit</button>
                                            <button class="btn btn-warning mt-2" type="button" data-bs-dismiss="modal">Cancel</button>
                                            </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>
                                <div class="table-responsive">
                                    <table class="display nowrap" id="_datatable1">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Tahun</th>
                                                <th>Bulan</th>
                                                <th>Nama</th>
                                                <th>NIP</th>
                                                <th>Gaji Pokok</th>
                                                <?php
                                                    $where = ['tipe'=>'add'];
                                                    $arr_id = [1,2,3,4,5];
                                                    $add = App\Models\MtrPayrollAddsub::where($where)->whereIn('id', $arr_id)->orderBy('id', 'asc')->get();
                                                ?>
                                                <?php $__currentLoopData = $add; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <th><?php echo e($data->nama); ?></th>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <th>Total Tunjangan</th>
                                                <th>Upah</th>
                                                <th>Perubahan Upah</th>
                                                <?php
                                                    $where = ['tipe'=>'add'];
                                                    $arr_id = [6,7,8,9,21];
                                                    $add = App\Models\MtrPayrollAddsub::where($where)->whereIn('id', $arr_id)->orderBy('id', 'asc')->get();
                                                ?>
                                                <?php $__currentLoopData = $add; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <th><?php echo e($data->nama); ?></th>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <th>Total Upah</th>
                                                <th>Dibayarkan Tanggal</th>
                                                <th>Slip Gaji</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $no=0;
                                                $hco = explode(',', App\Models\MtrUtility::find(32)->informasi);
                                            ?>
                                            <?php $__currentLoopData = $payroll; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php
                                                $where = ['nik'=>$data->nik, 'tipe'=>'add', 'bulan'=>$data->bulan, 'tahun'=>$data->tahun];
                                                $arr_id = [1,2,3,4,5];
                                                $add = App\Models\TrxPayrollAddsub::where($where)->whereIn('id_addsub', $arr_id)->orderBy('id_addsub', 'asc')->get();
                                                $total_tunjangan = $add->sum('angka');
                                                $arr_id = [6,7,8,9,21];
                                                $_add = App\Models\TrxPayrollAddsub::where($where)->whereIn('id_addsub', $arr_id)->orderBy('id_addsub', 'asc')->get();
                                                $_total_tunjangan = $_add->sum('angka');
                                                // membandingkan data 1 bulan sebelumnya untuk mengecek apakah ada perubahan gapok + total_tunjangan
                                                $submonth = Carbon\Carbon::createFromFormat('Y-m-d', "{$data->tahun}-{$data->bulan}-01")->subMonth();
                                                $where = ['nik'=>$data->nik, 'bulan'=>$submonth->month, 'tahun'=>$submonth->year];
                                                $gapok_submonth = 0;
                                                $gapok_sub = App\Models\TrxPayrollLog::where($where)->first();
                                                if(!empty($gapok_sub)){
                                                    $gapok_submonth = $gapok_sub->gapok;
                                                }
                                                $where = ['nik'=>$data->nik, 'tipe'=>'add', 'bulan'=>$submonth->month, 'tahun'=>$submonth->year];
                                                $arr_id = [1,2,3,4,5];
                                                $add_submonth = App\Models\TrxPayrollAddsubLog::where($where)->whereIn('id_addsub', $arr_id)->orderBy('id_addsub', 'asc')->get();
                                                $total_tunjangan_submonth = 0;
                                                if(!empty($add_submonth)){
                                                    $total_tunjangan_submonth = $add_submonth->sum('angka');
                                                }
                                                $selisih = ($data->gapok + $total_tunjangan) - ($gapok_submonth + $total_tunjangan_submonth);
                                                // 
                                            ?>
                                            <tr>
                                                <td><?php echo e(++$no); ?></td>
                                                <td><?php echo e($data->tahun); ?></td>
                                                <td><?php echo e($bulan[$data->bulan - 1]); ?></td>
                                                <td><?php echo e($data->_nik->nama); ?></td>
                                                <td><?php echo e($data->nik); ?></td>
                                                <td class="text-end"><?php echo e(number_format($data->gapok, 0)); ?></td>
                                                
                                                <?php $__currentLoopData = $add; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $_data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <td class="text-end"><?php echo e(number_format($_data->angka, 0)); ?></td>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                
                                                <td class="text-end"><?php echo e(number_format($total_tunjangan, 0)); ?></td>
                                                
                                                <td class="text-end"><?php echo e(number_format($data->gapok + $total_tunjangan, 0)); ?></td>
                                                
                                                <td class="text-end">
                                                    <?php if($selisih > 0): ?>
                                                        <span class="font-success f-12 f-w-500"><i class="icon-arrow-up"></i></span> <span class="badge badge-success" style="font-size:12px;"><?php echo e(number_format($selisih, 0)); ?></span>
                                                    <?php elseif($selisih < 0): ?>
                                                        <span class="font-danger f-12 f-w-500"><i class="icon-arrow-down"></i></span> <span class="badge badge-danger" style="font-size:12px;"><?php echo e(number_format($selisih, 0)); ?></span>
                                                    <?php else: ?>
                                                        <span class="badge badge-primary" style="font-size:12px;"><?php echo e(number_format($selisih, 0)); ?></span>
                                                    <?php endif; ?>
                                                </td>
                                                
                                                <?php $__currentLoopData = $_add; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $_data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <td class="text-end"><?php echo e(number_format($_data->angka, 0)); ?></td>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                
                                                <td class="text-end"><?php echo e(number_format($data->gapok + $total_tunjangan + $_total_tunjangan, 0)); ?></td>
                                                <td><?php if(!empty($data->paid_at)): ?><?php echo e(Carbon\Carbon::parse($data->paid_at)->format("d-m-Y")); ?><?php endif; ?></td>
                                                <?php if(in_array(auth()->user()->nik, $hco)): ?>
                                                <td><a href="<?php echo e(route('payroll.slip.detail', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id), 'view' => 'pdf'])); ?>" data-toggle="tooltip" title="Slip Gaji" target="_blank"><i class="fa fa-file-pdf-o"></i></a></td>
                                                <?php else: ?>
                                                <td>&nbsp;</td>
                                                <?php endif; ?>
                                                <td>
                                                    <form action="<?php echo e(route('payroll.destroy',[6, 'tipe'=>'del_trxpayroll', 'id'=>(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id)])); ?>" method="POST">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('DELETE'); ?>
                                                        <ul class="action">
                                                            <li class="edit"><a href="<?php echo e(route('payroll.master',[6, 'tipe'=>'edit_trxpayroll', 'id_data'=>(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id)])); ?>" data-toggle="tooltip" title='Edit'><i class="icon-pencil-alt"></i></a></li>
                                                            <li class="delete"><a href="#" id="btn-delete" name="btn-delete" data-toggle="tooltip" title='Delete'><i class="icon-trash"></i></a></li>
                                                            
                                                        </ul>
                                                    </form>
                                                </td>
                                            </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th>Rekapitulasi</th>
                                                <?php for($x = 5; $x <= 12; $x++): ?>
                                                <th id="semua_<?php echo e($x); ?>" class="text-end">0</th>
                                                <?php endfor; ?>
                                                <th>&nbsp;</th>
                                                <?php for($x = 14; $x <= 19; $x++): ?>
                                                <th id="semua_<?php echo e($x); ?>" class="text-end">0</th>
                                                <?php endfor; ?>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </p>
                        </div>
                        
                        <div class="tab-pane fade" id="pills-potongan" role="tabpanel" aria-labelledby="pills-potongan-tab">
                            <p class="mb-0 m-t-10">
                                <div class="table-responsive">
                                    <table class="display nowrap" id="_datatable2">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Tahun</th>
                                                <th>Bulan</th>
                                                <th>Nama</th>
                                                <th>NIP</th>
                                                <th>Total Upah</th>
                                                <?php
                                                    $where = ['tipe'=>'sub'];
                                                    $arr_id = [13,14,15,16,17,18,19,20];
                                                    $sub = App\Models\MtrPayrollAddsub::where($where)->whereIn('id', $arr_id)->orderBy('id', 'asc')->get();
                                                    $where = ['tipe'=>'add'];
                                                    $arr_id = [10,11];
                                                    $add = App\Models\MtrPayrollAddsub::where($where)->whereIn('id', $arr_id)->orderBy('id', 'asc')->get();
                                                ?>
                                                <?php $__currentLoopData = $sub; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <th><?php echo e($data->nama); ?></th>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <th>Jumlah Potongan</th>
                                                <?php $__currentLoopData = $add; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <th><?php echo e($data->nama); ?></th>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <th>THP Ditransfer</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no=0; ?>
                                            <?php $__currentLoopData = $payroll; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php
                                                // 
                                                $where = ['nik'=>$data->nik, 'tipe'=>'add', 'bulan'=>$data->bulan, 'tahun'=>$data->tahun];
                                                $arr_id = [1,2,3,4,5];
                                                $add = App\Models\TrxPayrollAddsub::where($where)->whereIn('id_addsub', $arr_id)->orderBy('id_addsub', 'asc')->get();
                                                $total_tunjangan = $add->sum('angka');
                                                $arr_id = [6,7,8,9,21];
                                                $_add = App\Models\TrxPayrollAddsub::where($where)->whereIn('id_addsub', $arr_id)->orderBy('id_addsub', 'asc')->get();
                                                $_total_tunjangan = $_add->sum('angka');
                                                // 
                                                $where = ['nik'=>$data->nik, 'tipe'=>'sub', 'bulan'=>$data->bulan, 'tahun'=>$data->tahun];
                                                $arr_id = [13,14,15,16,17,18,19,20];
                                                $sub = App\Models\TrxPayrollAddsub::where($where)->whereIn('id_addsub', $arr_id)->orderBy('id_addsub', 'asc')->get();
                                                $total_potongan = $sub->sum('angka');
                                                $where = ['nik'=>$data->nik, 'tipe'=>'add', 'bulan'=>$data->bulan, 'tahun'=>$data->tahun];
                                                $arr_id = [10,11];
                                                $add = App\Models\TrxPayrollAddsub::where($where)->whereIn('id_addsub', $arr_id)->orderBy('id_addsub', 'asc')->get();
                                                $total_add = $add->sum('angka');
                                            ?>
                                            <tr>
                                                <td><?php echo e(++$no); ?></td>
                                                <td><?php echo e($data->tahun); ?></td>
                                                <td><?php echo e($bulan[$data->bulan - 1]); ?></td>
                                                <td><?php echo e($data->_nik->nama); ?></td>
                                                <td><?php echo e($data->nik); ?></td>
                                                
                                                <td class="text-end"><?php echo e(number_format($data->gapok + $total_tunjangan + $_total_tunjangan, 0)); ?></td>
                                                <?php $__currentLoopData = $sub; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $_data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <td class="text-end"><?php echo e(number_format($_data->angka, 0)); ?></td>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                
                                                <td class="text-end"><?php echo e(number_format($total_potongan, 0)); ?></td>
                                                <?php $__currentLoopData = $add; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $_data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <td class="text-end"><?php echo e(number_format($_data->angka, 0)); ?></td>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                
                                                <td class="text-end"><?php echo e(number_format($data->gapok + $total_tunjangan + $_total_tunjangan - $total_potongan + $total_add, 0)); ?></td>
                                            </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th>Rekapitulasi</th>
                                                <?php for($x = 5; $x <= 17; $x++): ?>
                                                <th id="potongan_<?php echo e($x); ?>" class="text-end">0</th>
                                                <?php endfor; ?>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </p>
                        </div>
                        
                        <div class="tab-pane fade" id="pills-bpjstk" role="tabpanel" aria-labelledby="pills-bpjstk-tab">
                            <p class="mb-0 m-t-10">
                                <div class="row g-3 mb-2">
                                    <form action="<?php echo e(route('payroll.upload_payroll')); ?>" method="POST" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to upload this data?');">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" id="jenis" name="jenis" value="bpjstk">
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <input name="template" id="template" class="form-control" type="file" required="">
                                            <label class="form-label">Maksimal 2 MB dengan format xls/xlsx</label>
                                            <br/><a href="<?php echo e(asset('assets/template/template_upload_bpjstk_v1.xlsx')); ?>" target="_blank"><i class="icon-file"></i>&nbsp;Download template</a>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="submit" class="btn btn-success"><i class="fa fa-upload"></i> Upload</button>
                                        </div>
                                    </div>
                                    </form>
                                </div>
                                <div class="table-responsive">
                                    <table class="display nowrap" id="_datatable3">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Tahun</th>
                                                <th>Bulan</th>
                                                <th>Nama</th>
                                                <th>NIP</th>
                                                <th>Upah</th>
                                                <th>Upah Dilaporkan BPJS TK</th>
                                                <th>Upah JP Maksimum</th>
                                                <?php
                                                    $arr_id = [1,2,3,4,5,6];
                                                    $arr = App\Models\MtrPayrollBpjstk::whereIn('id', $arr_id)->orderBy('id', 'asc')->get();
                                                ?>
                                                <?php $__currentLoopData = $arr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $_arr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <th><?php echo e($_arr->nama); ?><br/><?php echo e($_arr->persen); ?>%</th>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <th>Total Iuran</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no=0; ?>
                                            <?php $__currentLoopData = $payroll; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php
                                                $where = ['nik'=>$data->nik, 'bulan'=>$data->bulan, 'tahun'=>$data->tahun];
                                                $bpjstk = App\Models\TrxPayrollBpjstk::where($where)->orderBy('id_bpjstk', 'asc')->get();
                                                $potongan = 0;
                                            ?>
                                            <tr>
                                                <td><?php echo e(++$no); ?></td>
                                                <td><?php echo e($data->tahun); ?></td>
                                                <td><?php echo e($bulan[$data->bulan - 1]); ?></td>
                                                <td><?php echo e($data->_nik->nama); ?></td>
                                                <td><?php echo e($data->nik); ?></td>
                                                <td class="text-end"><?php echo e(number_format($bpjstk[0]->upah, 0)); ?></td>
                                                <td class="text-end"><?php echo e(number_format($bpjstk[0]->thp_dilaporkan, 0)); ?></td>
                                                <td class="text-end"><?php if($bpjstk[0]->thp_dilaporkan > $bpjstk[0]->maksimal): ?><?php echo e(number_format($bpjstk[0]->maksimal, 0)); ?><?php else: ?><?php echo e(number_format($bpjstk[0]->thp_dilaporkan, 0)); ?><?php endif; ?></td>
                                                <?php $__currentLoopData = $bpjstk; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $_data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php
                                                    // tidak digunakan, menjadi upload angka bpjstk
                                                    // $angka = round($_data->angka*$_data->persen/100, 0);
                                                    $angka = $_data->angka;
                                                    $potongan += $angka;
                                                ?>
                                                <td class="text-end"><?php echo e(number_format($angka, 0)); ?></td>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <td class="text-end"><?php echo e(number_format($potongan, 0)); ?></td>
                                            </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th>Rekapitulasi</th>
                                                <?php for($x = 5; $x <= 14; $x++): ?>
                                                <th id="bpjstk_<?php echo e($x); ?>" class="text-end">0</th>
                                                <?php endfor; ?>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </p>
                        </div>
                        
                        <div class="tab-pane fade" id="pills-bpjskes" role="tabpanel" aria-labelledby="pills-bpjskes-tab">
                            <p class="mb-0 m-t-10">
                                <div class="row g-3 mb-2">
                                    <form action="<?php echo e(route('payroll.upload_payroll')); ?>" method="POST" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to upload this data?');">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" id="jenis" name="jenis" value="bpjsks">
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <input name="template" id="template" class="form-control" type="file" required="">
                                            <label class="form-label">Maksimal 2 MB dengan format xls/xlsx</label>
                                            <br/><a href="<?php echo e(asset('assets/template/template_upload_bpjsks_v1.xlsx')); ?>" target="_blank"><i class="icon-file"></i>&nbsp;Download template</a>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="submit" class="btn btn-success"><i class="fa fa-upload"></i> Upload</button>
                                        </div>
                                    </div>
                                    </form>
                                </div>
                                <div class="table-responsive">
                                    <table class="display nowrap" id="_datatable4">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Tahun</th>
                                                <th>Bulan</th>
                                                <th>Nama</th>
                                                <th>NIP</th>
                                                <th>Upah</th>
                                                <th>Upah Dilaporkan BPJS KS</th>
                                                <th>Upah Maksimum</th>
                                                <?php
                                                    $arr_id = [1,2,3];
                                                    $arr = App\Models\MtrPayrollBpjskes::whereIn('id', $arr_id)->orderBy('id', 'asc')->get();
                                                ?>
                                                <?php $__currentLoopData = $arr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $_arr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <th><?php echo e($_arr->nama); ?><br/><?php echo e($_arr->persen); ?>%</th>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <th>Total Iuran</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no=0; ?>
                                            <?php $__currentLoopData = $payroll; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php
                                                $where = ['nik'=>$data->nik, 'bulan'=>$data->bulan, 'tahun'=>$data->tahun];
                                                $bpjskes = App\Models\TrxPayrollBpjskes::where($where)->orderBy('id_bpjskes', 'asc')->get();
                                                $potongan = 0;
                                            ?>
                                            <tr>
                                                <td><?php echo e(++$no); ?></td>
                                                <td><?php echo e($data->tahun); ?></td>
                                                <td><?php echo e($bulan[$data->bulan - 1]); ?></td>
                                                <td><?php echo e($data->_nik->nama); ?></td>
                                                <td><?php echo e($data->nik); ?></td>
                                                <td class="text-end"><?php echo e(number_format($bpjskes[0]->upah, 0)); ?></td>
                                                <td class="text-end"><?php echo e(number_format($bpjskes[0]->thp_dilaporkan, 0)); ?></td>
                                                <td class="text-end"><?php if($bpjskes[0]->thp_dilaporkan > $bpjskes[0]->maksimal): ?><?php echo e(number_format($bpjskes[0]->maksimal, 0)); ?><?php else: ?><?php echo e(number_format($bpjskes[0]->thp_dilaporkan, 0)); ?><?php endif; ?></td>
                                                <?php $__currentLoopData = $bpjskes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $_data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php
                                                    // tidak digunakan, menjadi upload angka bpjsks
                                                    // $angka = round($_data->angka*$_data->persen/100, 0);
                                                    $angka = $_data->angka;
                                                    $potongan += $angka;
                                                ?>
                                                <td class="text-end"><?php echo e(number_format($angka, 0)); ?></td>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <td class="text-end"><?php echo e(number_format($potongan, 0)); ?></td>
                                            </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th>Rekapitulasi</th>
                                                <?php for($x = 5; $x <= 11; $x++): ?>
                                                <th id="bpjsks_<?php echo e($x); ?>" class="text-end">0</th>
                                                <?php endfor; ?>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </p>
                        </div>
                        
                        <div class="tab-pane fade <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('payroll_fin')): ?> show active <?php endif; ?>" id="pills-kerja" role="tabpanel" aria-labelledby="pills-kerja-tab">
                            <p class="mb-0 m-t-10">
                                <div class="row g-3 mb-2">
                                    <form action="<?php echo e(route('payroll.upload_payroll')); ?>" method="POST" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to upload this data?');">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" id="jenis" name="jenis" value="pajak_present">
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <input name="template" id="template" class="form-control" type="file" required="">
                                            <label class="form-label">Maksimal 2 MB dengan format xls/xlsx</label>
                                            <br/><a href="<?php echo e(asset('assets/template/template_upload_pajak_v1.xlsx')); ?>" target="_blank"><i class="icon-file"></i>&nbsp;Download template</a>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="submit" class="btn btn-success"><i class="fa fa-upload"></i> Upload</button>
                                        </div>
                                    </div>
                                    </form>
                                </div>
                                <div class="table-responsive">
                                    <table class="display nowrap" id="_datatable5">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Tahun</th>
                                                <th>Bulan</th>
                                                <th>Nama</th>
                                                <th>NIP</th>
                                                <th>Findim Sub Departemen</th>
                                                <th>Findim Departemen</th>
                                                <th>Findim Divisi</th>
                                                <th>Project</th>
                                                <th>Direktorat</th>
                                                <th>Divisi</th>
                                                <th>Departemen</th>
                                                <th>Status Karyawan</th>
                                                <th>Status Aktif</th>
                                                <th>Jabatan</th>
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('payroll')): ?>
                                                <th>Level</th>
                                                <th>Grade</th>
                                                <th>Gaji Pokok</th>
                                                <?php endif; ?>
                                                <?php
                                                    $where = ['tipe'=>'add'];
                                                    $arr_id = [1,2,3,4,5];
                                                    $add = App\Models\MtrPayrollAddsub::where($where)->whereIn('id', $arr_id)->orderBy('id', 'asc')->get();
                                                ?>
                                                <?php $__currentLoopData = $add; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('payroll')): ?>
                                                <th><?php echo e($data->nama); ?></th>
                                                <?php endif; ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('payroll')): ?>
                                                <th>Total Tunjangan</th>
                                                <?php endif; ?>
                                                <th>Upah</th>
                                                <?php
                                                    $where = ['tipe'=>'add'];
                                                    $arr_id = [6,7,8,9,21];
                                                    $add = App\Models\MtrPayrollAddsub::where($where)->whereIn('id', $arr_id)->orderBy('id', 'asc')->get();
                                                ?>
                                                <?php $__currentLoopData = $add; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <th><?php echo e($data->nama); ?></th>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <th>Total Upah</th>
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('payroll')): ?>
                                                <th>No KPJ (BPJSTK)</th>
                                                <th>No JKN (BPJSKS)</th>
                                                <th>Upah Dilaporkan BPJS TK</th>
                                                <th>Upah Dilaporkan BPJS KS</th>
                                                <th>Iuran BPJS TK</th>
                                                <th>Iuran BPJS KS</th>
                                                <?php endif; ?>
                                                <th>Total Iuran BPJS/ASTEK</th>
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('payroll')): ?>
                                                <th>Kehamilan</th>
                                                <th>Persalinan</th>
                                                <th>Kacamata</th>
                                                <th>Medical Reimbursement</th>
                                                <th>Ekses</th>
                                                <?php endif; ?>
                                                <th>Total Medical Cost</th>
                                                <th>Overtime</th>
                                                <th>PPh21</th>
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('payroll')): ?>
                                                <th>Action</th>
                                                <?php endif; ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no=0; ?>
                                            <?php $__currentLoopData = $payroll; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php
                                                $nik_property = json_decode($data->nik_property, true);
                                                $where = ['nik'=>$data->nik, 'tipe'=>'add', 'bulan'=>$data->bulan, 'tahun'=>$data->tahun];
                                                $arr_id = [1,2,3,4,5];
                                                $add = App\Models\TrxPayrollAddsub::where($where)->whereIn('id_addsub', $arr_id)->orderBy('id_addsub', 'asc')->get();
                                                $total_tunjangan = $add->sum('angka');
                                                $arr_id = [6,7,8,9,21];
                                                $_add = App\Models\TrxPayrollAddsub::where($where)->whereIn('id_addsub', $arr_id)->orderBy('id_addsub', 'asc')->get();
                                                $_total_tunjangan = $_add->sum('angka');
                                                $where = ['nik'=>$data->nik];
                                                $mtr = App\Models\MtrPayroll::where($where)->first();
                                                $where = ['nik'=>$data->nik, 'bulan'=>$data->bulan, 'tahun'=>$data->tahun];
                                                $bpjstk = App\Models\TrxPayrollBpjstk::where($where)->orderBy('id', 'asc')->get();
                                                $bpjsks = App\Models\TrxPayrollBpjskes::where($where)->orderBy('id', 'asc')->get();
                                                $pot_bpjstk = 0;
                                                $pot_bpjsks = 0;
                                                // 
                                                // $date_from = '2024-10-01';
                                                // $date_to = '2024-10-31';
                                                // kesehatan data bulan berjalan
                                                $date_from = Carbon\Carbon::createFromFormat('Y-m-d', "{$data->tahun}-{$data->bulan}-01")->startOfMonth()->format('Y-m-d');
                                                $date_to = Carbon\Carbon::createFromFormat('Y-m-d', "{$data->tahun}-{$data->bulan}-01")->endOfMonth()->format('Y-m-d');
                                                $total_kesehatan = 0;
                                                // 4 = Kehamilan
                                                $kehamilan = DB::select(DB::raw("SELECT CASE WHEN SUM(a.jumlah_klaim) IS NULL THEN 0 ELSE SUM(a.jumlah_klaim) END AS jumlah_klaim FROM trx_tbl_reimburse_detail AS a LEFT JOIN trx_tbl_reimburse_master AS b ON a.id_reimburse=b.id WHERE b.nik_karyawan='".$data->nik."' AND DATE(b.updated_at) BETWEEN '".$date_from."' AND '".$date_to."' AND b.status_pengajuan=2 AND a.id_layanan IN (4)"))[0]->jumlah_klaim;
                                                $total_kesehatan += $kehamilan;
                                                // 5,6 = Persalinan-Normal dan Persalinan-Caesar
                                                $persalinan = DB::select(DB::raw("SELECT CASE WHEN SUM(a.jumlah_klaim) IS NULL THEN 0 ELSE SUM(a.jumlah_klaim) END AS jumlah_klaim FROM trx_tbl_reimburse_detail AS a LEFT JOIN trx_tbl_reimburse_master AS b ON a.id_reimburse=b.id WHERE b.nik_karyawan='".$data->nik."' AND DATE(b.updated_at) BETWEEN '".$date_from."' AND '".$date_to."' AND b.status_pengajuan=2 AND a.id_layanan IN (5,6)"))[0]->jumlah_klaim;
                                                $total_kesehatan += $persalinan;
                                                // 2,3 = Kacamata-Frame dan Kacamata-Lensa
                                                $kacamata = DB::select(DB::raw("SELECT CASE WHEN SUM(a.jumlah_klaim) IS NULL THEN 0 ELSE SUM(a.jumlah_klaim) END AS jumlah_klaim FROM trx_tbl_reimburse_detail AS a LEFT JOIN trx_tbl_reimburse_master AS b ON a.id_reimburse=b.id WHERE b.nik_karyawan='".$data->nik."' AND DATE(b.updated_at) BETWEEN '".$date_from."' AND '".$date_to."' AND b.status_pengajuan=2 AND a.id_layanan IN (2,3)"))[0]->jumlah_klaim;
                                                $total_kesehatan += $kacamata;
                                                // 1 = Kesehatan
                                                $kesehatan = DB::select(DB::raw("SELECT CASE WHEN SUM(a.jumlah_klaim) IS NULL THEN 0 ELSE SUM(a.jumlah_klaim) END AS jumlah_klaim FROM trx_tbl_reimburse_detail AS a LEFT JOIN trx_tbl_reimburse_master AS b ON a.id_reimburse=b.id WHERE b.nik_karyawan='".$data->nik."' AND DATE(b.updated_at) BETWEEN '".$date_from."' AND '".$date_to."' AND b.status_pengajuan=2 AND a.id_layanan IN (1)"))[0]->jumlah_klaim;
                                                $total_kesehatan += $kesehatan;
                                                // 7 = Ekses
                                                $ekses = DB::select(DB::raw("SELECT CASE WHEN SUM(a.jumlah_klaim) IS NULL THEN 0 ELSE SUM(a.jumlah_klaim) END AS jumlah_klaim FROM trx_tbl_reimburse_detail AS a LEFT JOIN trx_tbl_reimburse_master AS b ON a.id_reimburse=b.id WHERE b.nik_karyawan='".$data->nik."' AND DATE(b.updated_at) BETWEEN '".$date_from."' AND '".$date_to."' AND b.status_pengajuan=2 AND a.id_layanan IN (7)"))[0]->jumlah_klaim;
                                                $total_kesehatan += $ekses;
                                                // overtime data bulan lalu
                                                // $date_from = Carbon\Carbon::createFromFormat('Y-m-d', "{$data->tahun}-{$data->bulan}-01")->subMonth()->startOfMonth()->format('Y-m-d');
                                                // $date_to = Carbon\Carbon::createFromFormat('Y-m-d', "{$data->tahun}-{$data->bulan}-01")->subMonth()->endOfMonth()->format('Y-m-d');
                                                // $where = ['ot_maker_by'=>$data->nik, 'ot_approver_status'=>'Approve'];
                                                // $overtime = App\Models\Overtime::select(DB::raw('SUM(ROUND(ot_length_rp_adj,-3)) as ot_length_rp_adj'))->where($where)->whereBetween(DB::raw('DATE(ot_start_at)'), [$date_from, $date_to])->orderBy( 'ot_start_at', 'desc' )->groupBy('ot_maker_by')->get();
                                                // $total_overtime = $overtime->sum('ot_length_rp_adj');
                                                $total_overtime = $data->overtime;
                                                // dd($total_overtime);
                                            ?>
                                            <tr>
                                                <td><?php echo e(++$no); ?></td>
                                                <td><?php echo e($data->tahun); ?></td>
                                                <td><?php echo e($bulan[$data->bulan - 1]); ?></td>
                                                <td><?php echo e($data->_nik->nama); ?></td>
                                                <td><?php echo e($data->nik); ?></td>
                                                <td><?php echo e($data->findim_subdepartemen); ?></td>
                                                <td><?php echo e($data->findim_departemen); ?></td>
                                                <td><?php echo e($data->findim_divisi); ?></td>
                                                <td><?php echo e($data->findim_project); ?></td>
                                                <td><?php echo e($data->divisi); ?></td>
                                                <td><?php echo e($data->departemen); ?></td>
                                                <td><?php echo e($data->subdepartemen); ?></td>
                                                <td><?php echo e($data->status_karyawan); ?></td>
                                                <td><?php echo e($data->status_aktif); ?></td>
                                                <td><?php echo e($data->jabatan); ?></td>
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('payroll')): ?>
                                                <td><?php echo e($data->level); ?></td>
                                                <td><?php echo e($data->grade); ?></td>
                                                <td class="text-end"><?php echo e(number_format($data->gapok, 0)); ?></td>
                                                <?php endif; ?>
                                                
                                                <?php $__currentLoopData = $add; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $_data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('payroll')): ?>
                                                <td class="text-end"><?php echo e(number_format($_data->angka, 0)); ?></td>
                                                <?php endif; ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('payroll')): ?>
                                                
                                                <td class="text-end"><?php echo e(number_format($total_tunjangan, 0)); ?></td>
                                                <?php endif; ?>
                                                
                                                <td class="text-end"><?php echo e(number_format($data->gapok + $total_tunjangan, 0)); ?></td>
                                                
                                                <?php $__currentLoopData = $_add; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $_data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <td class="text-end"><?php echo e(number_format($_data->angka, 0)); ?></td>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                
                                                <td class="text-end"><?php echo e(number_format($data->gapok + $total_tunjangan + $_total_tunjangan, 0)); ?></td>
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('payroll')): ?>
                                                <td><?php if(!empty($mtr->no_kpj)): ?><?php echo e($mtr->no_kpj); ?><?php endif; ?></td>
                                                <td><?php if(!empty($mtr->no_jkn)): ?><?php echo e($mtr->no_jkn); ?><?php endif; ?></td>
                                                
                                                
                                                <td class="text-end"><?php echo e(number_format($bpjstk[0]->thp_dilaporkan, 0)); ?></td>
                                                
                                                
                                                <td class="text-end"><?php echo e(number_format($bpjsks[0]->thp_dilaporkan, 0)); ?></td>
                                                <?php endif; ?>
                                                
                                                <?php $__currentLoopData = $bpjstk; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $_data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php
                                                    // tidak digunakan, menjadi upload angka bpjstk
                                                    // $angka = round($_data->angka*$_data->persen/100, 0);
                                                    $angka = $_data->angka;
                                                    $pot_bpjstk += $angka;
                                                ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('payroll')): ?>
                                                <td class="text-end"><?php echo e(number_format($pot_bpjstk, 0)); ?></td>
                                                <?php endif; ?>
                                                
                                                <?php $__currentLoopData = $bpjsks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $_data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php
                                                    // tidak digunakan, menjadi upload angka bpjsks
                                                    // $angka = round($_data->angka*$_data->persen/100, 0);
                                                    $angka = $_data->angka;
                                                    $pot_bpjsks += $angka;
                                                ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('payroll')): ?>
                                                <td class="text-end"><?php echo e(number_format($pot_bpjsks, 0)); ?></td>
                                                <?php endif; ?>
                                                
                                                <td class="text-end"><?php echo e(number_format($pot_bpjstk + $pot_bpjsks, 0)); ?></td>
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('payroll')): ?>
                                                <td class="text-end"><?php echo e(number_format($kehamilan, 0)); ?></td>
                                                <td class="text-end"><?php echo e(number_format($persalinan, 0)); ?></td>
                                                <td class="text-end"><?php echo e(number_format($kacamata, 0)); ?></td>
                                                <td class="text-end"><?php echo e(number_format($kesehatan, 0)); ?></td>
                                                <td class="text-end"><?php echo e(number_format($ekses, 0)); ?></td>
                                                <?php endif; ?>
                                                <td class="text-end"><?php echo e(number_format($total_kesehatan, 0)); ?></td>
                                                <td class="text-end"><?php echo e(number_format($total_overtime, 0)); ?></td>
                                                <td class="text-end"><?php echo e(number_format($data->pajak, 0)); ?></td>
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('payroll')): ?>
                                                <td><a href="<?php echo e(route('payroll.edit_payroll', [Crypt::encrypt($data->id), 'back_url'=>URL::full(), 'type'=>'overtime_present'])); ?>" data-toggle="tooltip" title='Adjustment'> <i class="icon-pencil-alt"></i></a></td>
                                                <?php endif; ?>
                                            </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                        <tfoot>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('payroll')): ?>
                                            <tr>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th>Rekapitulasi</th>
                                                <?php for($x = 17; $x <= 30; $x++): ?>
                                                <th id="kertas_<?php echo e($x); ?>" class="text-end">0</th>
                                                <?php endfor; ?>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <?php for($x = 33; $x <= 45; $x++): ?>
                                                <th id="kertas_<?php echo e($x); ?>" class="text-end">0</th>
                                                <?php endfor; ?>
                                                <th>&nbsp;</th>
                                            </tr>
                                            <?php endif; ?>
                                            
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('payroll_fin')): ?>
                                            <tr>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th>Rekapitulasi</th>
                                                <?php for($x = 15; $x <= 25; $x++): ?>
                                                <th id="kertas_<?php echo e($x); ?>" class="text-end">0</th>
                                                <?php endfor; ?>
                                            </tr>
                                            <?php endif; ?>
                                        </tfoot>
                                    </table>
                                </div>
                            </p>
                        </div>
                        
                    </div>
                    <hr class="mt-4 mb-2" />
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('payroll')): ?>
                    <h4>Out Source</h4>
                    <?php if(Gate::allows('hc_nkp')): ?>
                    <div class="col-sm-6">
                        <h6>Upload Payroll</h6>
                        <form action="<?php echo e(route('payroll.upload_payroll')); ?>" method="POST" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to upload this data?');">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" id="jenis" name="jenis" value="trx">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <input name="template" id="template" class="form-control" type="file" required="">
                                <label class="form-label">Maksimal 2 MB dengan format xls/xlsx</label>
                                <br/><a href="<?php echo e(asset('assets/template/template_import_payroll_outsource_v1.xlsx')); ?>" target="_blank"><i class="icon-file"></i>&nbsp;Download template Out Source</a>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-success"><i class="fa fa-upload"></i> Upload</button>
                            </div>
                        </div>
                        </form>
                    </div>
                    <?php endif; ?>
                    <p class="mb-0 m-t-10">
                        <div class="table-responsive">
                            <table class="display nowrap" id="_datatable6">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tahun</th>
                                        <th>Bulan</th>
                                        <th>Nama</th>
                                        <th>NIP</th>
                                        <th>Gaji Pokok</th>
                                        
                                        <?php
                                            $add = App\Models\MtrPayrollAddsub::find(5);
                                        ?>
                                        <th><?php echo e($add->nama); ?></th>
                                        <th>Total Upah</th>
                                        
                                        <?php
                                            $add = App\Models\MtrPayrollAddsub::find(22);
                                        ?>
                                        <th><?php echo e($add->nama); ?></th>
                                        
                                        
                                        
                                        <?php
                                            $where = ['tipe'=>'add'];
                                            $arr_id = [6,7,8];
                                            $add = App\Models\MtrPayrollAddsub::where($where)->whereIn('id', $arr_id)->orderBy('id', 'asc')->get();
                                        ?>
                                        <?php $__currentLoopData = $add; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <th><?php echo e($data->nama); ?></th>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        
                                        <?php
                                            $add = App\Models\MtrPayrollAddsub::find(12);
                                        ?>
                                        <th><?php echo e($add->nama); ?></th>
                                        <th>PPh 21</th>
                                        
                                        <?php
                                            $add = App\Models\MtrPayrollBpjskes::find(4);
                                        ?>
                                        <th><?php echo e($add->nama); ?></th>
                                        
                                        <?php
                                            $add = App\Models\MtrPayrollBpjstk::find(7);
                                        ?>
                                        <th><?php echo e($add->nama); ?></th>
                                        <th>Total Biaya Tenaga Kerja</th>
                                        
                                        <?php
                                            $add = App\Models\MtrPayrollBpjsMax::find(4);
                                        ?>
                                        <th><?php echo e($add->nama); ?></th>
                                        <th>Management Fee (Rp)</th>
                                        <th>Total Tagihan Upah Outsource</th>
                                        
                                        <?php
                                            $add = App\Models\MtrPayrollAddsub::find(9);
                                        ?>
                                        <th><?php echo e($add->nama); ?></th>
                                        
                                        <?php
                                            $add = App\Models\MtrPayrollBpjsMax::find(5);
                                        ?>
                                        <th><?php echo e($add->nama); ?></th>
                                        <th>Management Fee Kompensasi KKWT (Rp)</th>
                                        <th>Total Tagihan Kompensasi KKWT Outsource</th>
                                        <th>Total Biaya Outsource</th>
                                        <th>Medical Reimbursement</th>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('payroll')): ?>
                                        <th>Action</th>
                                        <?php endif; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no=0; ?>
                                    <?php $__currentLoopData = $payroll_out; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e(++$no); ?></td>
                                        <td><?php echo e($data->tahun); ?></td>
                                        <td><?php echo e($bulan[$data->bulan - 1]); ?></td>
                                        <td><?php echo e($data->_nik->nama); ?></td>
                                        <td><?php echo e($data->nik); ?></td>
                                        <td class="text-end"><?php echo e(number_format($data->gapok, 0)); ?></td>
                                        
                                        <?php
                                            $where = ['nik'=>$data->nik, 'bulan'=>$data->bulan, 'tahun'=>$data->tahun];
                                            $arr_id = [5];
                                            $add = App\Models\TrxPayrollAddsub::where($where)->whereIn('id_addsub', $arr_id)->orderBy('id_addsub', 'asc')->get();
                                            $total_tunjangan = $add->sum('angka');
                                            $total_upah = $data->gapok + $total_tunjangan;
                                            $total_biaya = $total_upah;
                                        ?>
                                        <td class="text-end"><?php echo e(number_format($total_tunjangan, 0)); ?></td>
                                        <td class="text-end"><?php echo e(number_format($total_upah, 0)); ?></td>
                                        
                                        <?php
                                            $arr_id = [22];
                                            $add = App\Models\TrxPayrollAddsub::where($where)->whereIn('id_addsub', $arr_id)->orderBy('id_addsub', 'asc')->get();
                                            $angka_22 = $add->sum('angka');
                                            $total_biaya += $angka_22;
                                        ?>
                                        <td class="text-end"><?php echo e(number_format($angka_22, 0)); ?></td>
                                        
                                        
                                        
                                        <?php
                                            $arr_id = [6,7,8];
                                            $add = App\Models\TrxPayrollAddsub::where($where)->whereIn('id_addsub', $arr_id)->orderBy('id_addsub', 'asc')->get();
                                            $total_biaya += $add->sum('angka');
                                        ?>
                                        <?php $__currentLoopData = $add; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $_data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <td class="text-end"><?php echo e(number_format($_data->angka, 0)); ?></td>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <td class="text-end"><?php echo e(number_format($data->overtime, 0)); ?></td>
                                        <td class="text-end"><?php echo e(number_format($data->pajak, 0)); ?></td>
                                        
                                        <?php
                                            $arr_id = [4];
                                            $bpjskes = App\Models\TrxPayrollBpjskes::where($where)->whereIn('id_bpjskes', $arr_id)->orderBy('id_bpjskes', 'asc')->get();
                                            $bpjskes = $bpjskes->sum('angka');
                                            $total_biaya += $data->overtime + $data->pajak + $bpjskes;
                                        ?>
                                        <td class="text-end"><?php echo e(number_format($bpjskes, 0)); ?></td>
                                        
                                        <?php
                                            $arr_id = [7];
                                            $bpjstk = App\Models\TrxPayrollBpjstk::where($where)->whereIn('id_bpjstk', $arr_id)->orderBy('id_bpjstk', 'asc')->get();
                                            $bpjstk = $bpjstk->sum('angka');
                                            $total_biaya += $bpjstk;
                                        ?>
                                        <td class="text-end"><?php echo e(number_format($bpjstk, 0)); ?></td>
                                        
                                        <td class="text-end"><?php echo e(number_format($total_biaya, 0)); ?></td>
                                        
                                        <td class="text-end"><?php echo e(number_format($data->mgt_fee, 2)); ?> %</td>
                                        <?php
                                            $mgt_fee = round($total_biaya * $data->mgt_fee / 100, 2);
                                            $total_biaya += $mgt_fee;
                                        ?>
                                        <td class="text-end"><?php echo e(number_format($mgt_fee, 0)); ?></td>
                                        
                                        <td class="text-end"><?php echo e(number_format($total_biaya, 0)); ?></td>
                                        
                                        <?php
                                            $arr_id = [9];
                                            $add = App\Models\TrxPayrollAddsub::where($where)->whereIn('id_addsub', $arr_id)->orderBy('id_addsub', 'asc')->get();
                                            $angka_9 = $add->sum('angka');
                                        ?>
                                        <td class="text-end"><?php echo e(number_format($angka_9, 0)); ?></td>
                                        
                                        <td class="text-end"><?php echo e(number_format($data->mgt_fee_kkwt, 2)); ?> %</td>
                                        <?php
                                            $mgt_fee_kkwt = round($angka_9 * $data->mgt_fee_kkwt / 100, 2);
                                        ?>
                                        <td class="text-end"><?php echo e(number_format($mgt_fee_kkwt, 0)); ?></td>
                                        <?php
                                            $mgt_fee_kkwt += $angka_9;
                                        ?>
                                        <td class="text-end"><?php echo e(number_format($mgt_fee_kkwt, 0)); ?></td>
                                        <?php
                                            $total_biaya += $mgt_fee_kkwt;
                                        ?>
                                        
                                        <td class="text-end"><?php echo e(number_format($total_biaya, 0)); ?></td>
                                        <?php
                                            // kesehatan data bulan berjalan
                                            $date_from = Carbon\Carbon::createFromFormat('Y-m-d', "{$data->tahun}-{$data->bulan}-01")->startOfMonth()->format('Y-m-d');
                                            $date_to = Carbon\Carbon::createFromFormat('Y-m-d', "{$data->tahun}-{$data->bulan}-01")->endOfMonth()->format('Y-m-d');
                                            $kesehatan = DB::select(DB::raw("SELECT CASE WHEN SUM(a.jumlah_klaim) IS NULL THEN 0 ELSE SUM(a.jumlah_klaim) END AS jumlah_klaim FROM trx_tbl_reimburse_detail AS a LEFT JOIN trx_tbl_reimburse_master AS b ON a.id_reimburse=b.id WHERE b.nik_karyawan='".$data->nik."' AND DATE(b.updated_at) BETWEEN '".$date_from."' AND '".$date_to."' AND b.status_pengajuan=2 AND a.id_layanan IN (1)"))[0]->jumlah_klaim;
                                        ?>
                                        <td class="text-end"><?php echo e(number_format($kesehatan, 0)); ?></td>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('payroll')): ?>
                                        <td><a href="<?php echo e(route('payroll.edit_payroll', [Crypt::encrypt($data->id), 'back_url'=>URL::full(), 'type'=>'overtime_present'])); ?>" data-toggle="tooltip" title='Adjustment'> <i class="icon-pencil-alt"></i></a></td>
                                        <?php endif; ?>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>&nbsp;</th>
                                        <th>&nbsp;</th>
                                        <th>&nbsp;</th>
                                        <th>&nbsp;</th>
                                        <th>Rekapitulasi</th>
                                        <?php for($x = 5; $x <= 16; $x++): ?>
                                        <th id="outsource_<?php echo e($x); ?>" class="text-end">0</th>
                                        <?php endfor; ?>
                                        <th>&nbsp;</th>
                                        <?php for($x = 18; $x <= 20; $x++): ?>
                                        <th id="outsource_<?php echo e($x); ?>" class="text-end">0</th>
                                        <?php endfor; ?>
                                        <th>&nbsp;</th>
                                        <?php for($x = 22; $x <= 25; $x++): ?>
                                        <th id="outsource_<?php echo e($x); ?>" class="text-end">0</th>
                                        <?php endfor; ?>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </p>
                    <?php endif; ?>
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
<script src="<?php echo e(asset('assets/js/select2/select2.full.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/select2/select2-custom.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/form-validation-custom.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/sweet-alert/sweetalert.min.js')); ?>"></script>

<script type="text/javascript">
// semua
$('#_datatable1').DataTable( {
	// dom: 'lBfrtip',
    dom:"<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
      "<'row'<'col-sm-12'rt>>" +
      "<'row'<'col-sm-5'i><'col-sm-7'p>>",
	buttons: [
		'copy', 'csv', 'excel', 'pdf', 'print'
	],
    "lengthMenu": [[10, 50, 100, 200, 500, -1], [10, 50, 100, 200, 500,  "All"]],
    footerCallback: function (row, data, start, end, display) {
        nfObject = new Intl.NumberFormat('en-US');
        let api = this.api();
        let intVal = function (i) {
            return typeof i === 'string'
                ? i.replace(/[\$,]/g, '') * 1
                : typeof i === 'number'
                ? i
                : 0;
        };
        <?php for($x = 5; $x <= 12; $x++): ?>
        semua_<?php echo e($x); ?> = api.column(<?php echo e($x); ?>).data().reduce((a, b) => intVal(a) + intVal(b), 0);
        page_semua_<?php echo e($x); ?> = api.column(<?php echo e($x); ?>, { page: 'current' }).data().reduce((a, b) => intVal(a) + intVal(b), 0);
        api.column(<?php echo e($x); ?>).footer().innerHTML = nfObject.format(page_semua_<?php echo e($x); ?>) + '<br/>Total: ' + nfObject.format(semua_<?php echo e($x); ?>);
        <?php endfor; ?>
        <?php for($x = 14; $x <= 19; $x++): ?>
        semua_<?php echo e($x); ?> = api.column(<?php echo e($x); ?>).data().reduce((a, b) => intVal(a) + intVal(b), 0);
        page_semua_<?php echo e($x); ?> = api.column(<?php echo e($x); ?>, { page: 'current' }).data().reduce((a, b) => intVal(a) + intVal(b), 0);
        api.column(<?php echo e($x); ?>).footer().innerHTML = nfObject.format(page_semua_<?php echo e($x); ?>) + '<br/>Total: ' + nfObject.format(semua_<?php echo e($x); ?>);
        <?php endfor; ?>
    },
});
// potongan
$('#_datatable2').DataTable( {
	// dom: 'lBfrtip',
    dom:"<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
      "<'row'<'col-sm-12'rt>>" +
      "<'row'<'col-sm-5'i><'col-sm-7'p>>",
	buttons: [
		'copy', 'csv', 'excel', 'pdf', 'print'
	],
    "lengthMenu": [[10, 50, 100, 200, 500, -1], [10, 50, 100, 200, 500,  "All"]],
    footerCallback: function (row, data, start, end, display) {
        nfObject = new Intl.NumberFormat('en-US');
        let api = this.api();
        let intVal = function (i) {
            return typeof i === 'string'
                ? i.replace(/[\$,]/g, '') * 1
                : typeof i === 'number'
                ? i
                : 0;
        };
        <?php for($x = 5; $x <= 17; $x++): ?>
        potongan_<?php echo e($x); ?> = api.column(<?php echo e($x); ?>).data().reduce((a, b) => intVal(a) + intVal(b), 0);
        page_potongan_<?php echo e($x); ?> = api.column(<?php echo e($x); ?>, { page: 'current' }).data().reduce((a, b) => intVal(a) + intVal(b), 0);
        api.column(<?php echo e($x); ?>).footer().innerHTML = nfObject.format(page_potongan_<?php echo e($x); ?>) + '<br/>Total: ' + nfObject.format(potongan_<?php echo e($x); ?>);
        <?php endfor; ?>
    },
});
// bpjstk
$('#_datatable3').DataTable( {
	// dom: 'lBfrtip',
    dom:"<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
      "<'row'<'col-sm-12'rt>>" +
      "<'row'<'col-sm-5'i><'col-sm-7'p>>",
	buttons: [
		'copy', 'csv', 'excel', 'pdf', 'print'
	],
    "lengthMenu": [[10, 50, 100, 200, 500, -1], [10, 50, 100, 200, 500,  "All"]],
    footerCallback: function (row, data, start, end, display) {
        nfObject = new Intl.NumberFormat('en-US');
        let api = this.api();
        let intVal = function (i) {
            return typeof i === 'string'
                ? i.replace(/[\$,]/g, '') * 1
                : typeof i === 'number'
                ? i
                : 0;
        };
        <?php for($x = 5; $x <= 14; $x++): ?>
        bpjstk_<?php echo e($x); ?> = api.column(<?php echo e($x); ?>).data().reduce((a, b) => intVal(a) + intVal(b), 0);
        page_bpjstk_<?php echo e($x); ?> = api.column(<?php echo e($x); ?>, { page: 'current' }).data().reduce((a, b) => intVal(a) + intVal(b), 0);
        api.column(<?php echo e($x); ?>).footer().innerHTML = nfObject.format(page_bpjstk_<?php echo e($x); ?>) + '<br/>Total: ' + nfObject.format(bpjstk_<?php echo e($x); ?>);
        <?php endfor; ?>
    },
});
// bpjsks
$('#_datatable4').DataTable( {
	// dom: 'lBfrtip',
    dom:"<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
      "<'row'<'col-sm-12'rt>>" +
      "<'row'<'col-sm-5'i><'col-sm-7'p>>",
	buttons: [
		'copy', 'csv', 'excel', 'pdf', 'print'
	],
    "lengthMenu": [[10, 50, 100, 200, 500, -1], [10, 50, 100, 200, 500,  "All"]],
    footerCallback: function (row, data, start, end, display) {
        nfObject = new Intl.NumberFormat('en-US');
        let api = this.api();
        let intVal = function (i) {
            return typeof i === 'string'
                ? i.replace(/[\$,]/g, '') * 1
                : typeof i === 'number'
                ? i
                : 0;
        };
        <?php for($x = 5; $x <= 11; $x++): ?>
        bpjsks_<?php echo e($x); ?> = api.column(<?php echo e($x); ?>).data().reduce((a, b) => intVal(a) + intVal(b), 0);
        page_bpjsks_<?php echo e($x); ?> = api.column(<?php echo e($x); ?>, { page: 'current' }).data().reduce((a, b) => intVal(a) + intVal(b), 0);
        api.column(<?php echo e($x); ?>).footer().innerHTML = nfObject.format(page_bpjsks_<?php echo e($x); ?>) + '<br/>Total: ' + nfObject.format(bpjsks_<?php echo e($x); ?>);
        <?php endfor; ?>
    },
});
// PPh21
$('#_datatable5').DataTable( {
	// dom: 'lBfrtip',
    dom:"<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
      "<'row'<'col-sm-12'rt>>" +
      "<'row'<'col-sm-5'i><'col-sm-7'p>>",
	buttons: [
		'copy', 'csv', 'excel', 'pdf', 'print'
	],
    "lengthMenu": [[10, 50, 100, 200, 500, -1], [10, 50, 100, 200, 500,  "All"]],
    footerCallback: function (row, data, start, end, display) {
        nfObject = new Intl.NumberFormat('en-US');
        let api = this.api();
        let intVal = function (i) {
            return typeof i === 'string'
                ? i.replace(/[\$,]/g, '') * 1
                : typeof i === 'number'
                ? i
                : 0;
        };
        // 
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('payroll')): ?>
        <?php for($x = 17; $x <= 30; $x++): ?>
        kertas_<?php echo e($x); ?> = api.column(<?php echo e($x); ?>).data().reduce((a, b) => intVal(a) + intVal(b), 0);
        page_kertas_<?php echo e($x); ?> = api.column(<?php echo e($x); ?>, { page: 'current' }).data().reduce((a, b) => intVal(a) + intVal(b), 0);
        api.column(<?php echo e($x); ?>).footer().innerHTML = nfObject.format(page_kertas_<?php echo e($x); ?>) + '<br/>Total: ' + nfObject.format(kertas_<?php echo e($x); ?>);
        <?php endfor; ?>
        // 
        <?php for($x = 33; $x <= 45; $x++): ?>
        kertas_<?php echo e($x); ?> = api.column(<?php echo e($x); ?>).data().reduce((a, b) => intVal(a) + intVal(b), 0);
        page_kertas_<?php echo e($x); ?> = api.column(<?php echo e($x); ?>, { page: 'current' }).data().reduce((a, b) => intVal(a) + intVal(b), 0);
        api.column(<?php echo e($x); ?>).footer().innerHTML = nfObject.format(page_kertas_<?php echo e($x); ?>) + '<br/>Total: ' + nfObject.format(kertas_<?php echo e($x); ?>);
        <?php endfor; ?>
        <?php endif; ?>
        // 
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('payroll_fin')): ?>
        <?php for($x = 15; $x <= 25; $x++): ?>
        kertas_<?php echo e($x); ?> = api.column(<?php echo e($x); ?>).data().reduce((a, b) => intVal(a) + intVal(b), 0);
        page_kertas_<?php echo e($x); ?> = api.column(<?php echo e($x); ?>, { page: 'current' }).data().reduce((a, b) => intVal(a) + intVal(b), 0);
        api.column(<?php echo e($x); ?>).footer().innerHTML = nfObject.format(page_kertas_<?php echo e($x); ?>) + '<br/>Total: ' + nfObject.format(kertas_<?php echo e($x); ?>);
        <?php endfor; ?>
        <?php endif; ?>
    },
});
// outsource
$('#_datatable6').DataTable( {
	// dom: 'lBfrtip',
    dom:"<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
      "<'row'<'col-sm-12'rt>>" +
      "<'row'<'col-sm-5'i><'col-sm-7'p>>",
	buttons: [
		'copy', 'csv', 'excel', 'pdf', 'print'
	],
    "lengthMenu": [[10, 50, 100, 200, 500, -1], [10, 50, 100, 200, 500,  "All"]],
    footerCallback: function (row, data, start, end, display) {
        nfObject = new Intl.NumberFormat('en-US');
        let api = this.api();
        let intVal = function (i) {
            return typeof i === 'string'
                ? i.replace(/[\$,]/g, '') * 1
                : typeof i === 'number'
                ? i
                : 0;
        };
        <?php for($x = 5; $x <= 16; $x++): ?>
        outsource_<?php echo e($x); ?> = api.column(<?php echo e($x); ?>).data().reduce((a, b) => intVal(a) + intVal(b), 0);
        page_outsource_<?php echo e($x); ?> = api.column(<?php echo e($x); ?>, { page: 'current' }).data().reduce((a, b) => intVal(a) + intVal(b), 0);
        api.column(<?php echo e($x); ?>).footer().innerHTML = nfObject.format(page_outsource_<?php echo e($x); ?>) + '<br/>Total: ' + nfObject.format(outsource_<?php echo e($x); ?>);
        <?php endfor; ?>
        // 
        <?php for($x = 18; $x <= 20; $x++): ?>
        outsource_<?php echo e($x); ?> = api.column(<?php echo e($x); ?>).data().reduce((a, b) => intVal(a) + intVal(b), 0);
        page_outsource_<?php echo e($x); ?> = api.column(<?php echo e($x); ?>, { page: 'current' }).data().reduce((a, b) => intVal(a) + intVal(b), 0);
        api.column(<?php echo e($x); ?>).footer().innerHTML = nfObject.format(page_outsource_<?php echo e($x); ?>) + '<br/>Total: ' + nfObject.format(outsource_<?php echo e($x); ?>);
        <?php endfor; ?>
        // 
        <?php for($x = 22; $x <= 25; $x++): ?>
        outsource_<?php echo e($x); ?> = api.column(<?php echo e($x); ?>).data().reduce((a, b) => intVal(a) + intVal(b), 0);
        page_outsource_<?php echo e($x); ?> = api.column(<?php echo e($x); ?>, { page: 'current' }).data().reduce((a, b) => intVal(a) + intVal(b), 0);
        api.column(<?php echo e($x); ?>).footer().innerHTML = nfObject.format(page_outsource_<?php echo e($x); ?>) + '<br/>Total: ' + nfObject.format(outsource_<?php echo e($x); ?>);
        <?php endfor; ?>
    },
});
// 
$(document).on('click', '#btn-delete', function(event) {
    var form = $(this).closest("form");
    event.preventDefault();
    swal({
            title: "Are you sure to delete this?",
            text: "If you delete this, it will be gone forever.",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                form.submit();
            }
        });
});
function toggle_findim_subdepartemen(source) {
    checkboxes = document.getElementsByName('findim_subdepartemen[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
function toggle_findim_departemen(source) {
    checkboxes = document.getElementsByName('findim_departemen[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
function toggle_findim_divisi(source) {
    checkboxes = document.getElementsByName('findim_divisi[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
function toggle_findim_project(source) {
    checkboxes = document.getElementsByName('findim_project[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
function toggle_divisi(source) {
    checkboxes = document.getElementsByName('divisi[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
function toggle_departemen(source) {
    checkboxes = document.getElementsByName('departemen[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
function toggle_subdepartemen(source) {
    checkboxes = document.getElementsByName('subdepartemen[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
function toggle_status_karyawan(source) {
    checkboxes = document.getElementsByName('status_karyawan[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
function toggle_jabatan(source) {
    checkboxes = document.getElementsByName('jabatan[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
function toggle_level(source) {
    checkboxes = document.getElementsByName('level[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
function toggle_nik(source) {
    checkboxes = document.getElementsByName('nik[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/payroll/index.blade.php ENDPATH**/ ?>