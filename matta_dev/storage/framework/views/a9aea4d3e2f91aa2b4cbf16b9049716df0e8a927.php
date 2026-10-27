
<?php $__env->startSection('title', 'Data Master Karyawan'); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/sweetalert2.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/select2.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/datatables.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
<h3><?php if($status == 'edit'): ?> Edit <?php else: ?> Add <?php endif; ?></h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
    <li class="breadcrumb-item">Data Master</li>
    <li class="breadcrumb-item active">Karyawan</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <div class="row">
            <!-- start -->
            <?php if($status == 'edit'): ?>
            <form id="form" action="<?php echo e(route('karyawan.update', $karyawan->id)); ?>" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return confirm('Are you sure want to submit this data?');">
            <?php echo method_field('PATCH'); ?>
            <?php else: ?>
            <form id="form" action="<?php echo e(route('karyawan.store')); ?>" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return confirm('Are you sure want to submit this data?');">
            <?php endif; ?>
            <?php echo csrf_field(); ?>
            <div class="col-sm-12 col-xxl-12">
                <div class="card">
                    <?php if($message = Session::get('success')): ?>
                        <div class="alert alert-success dark alert-dismissible fade show" role="alert">
                            <h6><?php echo e($message); ?></h6>
                            <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    <?php if(count($errors) > 0): ?>
                        <div class="alert alert-danger dark alert-dismissible fade show" role="alert">
                            <h6>Upload Foto is ERROR</h6>
                            <ul>
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                            <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    <div class="card-body">
                        <ul class="nav nav-tabs nav-primary" id="pills-warningtab" role="tablist">
                            <li class="nav-item"><a class="nav-link active" id="pills-emp_profile-tab" data-bs-toggle="pill"
                                    href="#pills-emp_profile" role="tab" aria-controls="pills-emp_profile" aria-selected="false">Data Pribadi</a></li>
                            <li class="nav-item"><a class="nav-link" id="pills-emp_organization-tab"
                                    data-bs-toggle="pill" href="#pills-emp_organization" role="tab" aria-controls="pills-emp_organization" aria-selected="true">Organisasi</a></li>
                            <li class="nav-item"><a class="nav-link" id="pills-emp_salary-tab" data-bs-toggle="pill"
                                    href="#pills-emp_salary" role="tab" aria-controls="pills-emp_salary" aria-selected="false">Gaji Pokok</a></li>
                            <li class="nav-item"><a class="nav-link" id="pills-emp_level-tab" data-bs-toggle="pill"
                                    href="#pills-emp_level" role="tab" aria-controls="pills-emp_level" aria-selected="false">Level/Jabatan/Tanggal</a></li>
                            <li class="nav-item"><a class="nav-link" id="pills-emp_findim-tab" data-bs-toggle="pill"
                                    href="#pills-emp_findim" role="tab" aria-controls="pills-emp_findim" aria-selected="false">FinDim/Cost Allocation</a></li>
                            <?php if($status == 'edit'): ?>
                            <li class="nav-item"><a class="nav-link" id="pills-emp_history-tab" data-bs-toggle="pill"
                                    href="#pills-emp_history" role="tab" aria-controls="pills-emp_history" aria-selected="false">Perubahan Data</a></li>
                            <?php endif; ?>
                        </ul>
                        <!--  -->
                        <div class="tab-content" id="pills-warningtabContent">
                            <div class="tab-pane fade show active" id="pills-emp_profile" role="tabpanel" aria-labelledby="pills-emp_profile-tab">
                                <p class="mb-0 mt-2">
                                    <div class="row mt-2">
                                        <div class="col-md-4">
                                            <label class="form-label" for="lensa">NIP</label>
                                            <?php if($status=='edit' ): ?>
                                            <input name="nik" id="nik" class="form-control" type="text" <?php if($status=='edit' ): ?> value="<?php echo e($karyawan->nik); ?>" <?php endif; ?> readonly="" required="">
                                            <?php else: ?>
                                            <input name="nik" id="nik" class="form-control" type="text" required="">
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-4">
                                            <label class="form-label" for="nama">Nama</label>
                                            <input name="nama" id="nama" class="form-control" type="text" <?php if($status=='edit' ): ?> value="<?php echo e($karyawan->nama); ?>" <?php endif; ?> required="">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label" for="tempat_lahir">Tempat Lahir</label>
                                            <input name="tempat_lahir" id="tempat_lahir" class="form-control" type="text" <?php if($status=='edit' ): ?> value="<?php echo e($karyawan->tempat_lahir); ?>" <?php endif; ?> >
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label" for="tanggal_lahir">Tanggal Lahir</label>
                                            <input name="tanggal_lahir" id="tanggal_lahir" class="form-control" type="date" <?php if($status=='edit' ): ?> value="<?php echo e($karyawan->tanggal_lahir); ?>" <?php endif; ?> >
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-4">
                                            <label class="form-label" for="kode_kelamin">Jenis Kelamin</label>
                                            <select class="form-select" id="kode_kelamin" name="kode_kelamin" >
                                                <option selected="" disabled="" value="">Pilih...</option>
                                                <?php $__currentLoopData = $kelamin; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($data->id); ?>" <?php if($status=='edit' ): ?> <?php if($data->id == $karyawan->kode_kelamin): ?> selected <?php endif; ?> <?php endif; ?> ><?php echo e($data->kelamin); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label" for="kode_agama">Agama</label>
                                            <select class="form-select" id="kode_agama" name="kode_agama" >
                                                <option selected="" disabled="" value="">Pilih...</option>
                                                <?php $__currentLoopData = $agama; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($data->id); ?>" <?php if($status=='edit' ): ?> <?php if($data->id == $karyawan->kode_agama): ?> selected <?php endif; ?> <?php endif; ?> ><?php echo e($data->agama); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label" for="kode_warga">Kewarganegaraan</label>
                                            <select class="form-select" id="kode_warga" name="kode_warga" >
                                                <option selected="" disabled="" value="">Pilih...</option>
                                                <?php $__currentLoopData = $warga; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($data->id); ?>" <?php if($status=='edit' ): ?> <?php if($data->id == $karyawan->kode_warga): ?> selected <?php endif; ?> <?php endif; ?> ><?php echo e($data->kewarganegaraan); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-4">
                                            <label class="form-label" for="no_ktp">NIK KTP</label>
                                            <input name="no_ktp" id="no_ktp" class="form-control" type="text" <?php if($status=='edit' ): ?> value="<?php echo e($karyawan->no_ktp); ?>" <?php endif; ?> >
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label" for="no_npwp">NPWP</label>
                                            <input name="no_npwp" id="no_npwp" class="form-control" type="text" <?php if($status=='edit' ): ?> value="<?php echo e($karyawan->no_npwp); ?>" <?php endif; ?> >
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label" for="no_bpjs">No BPJS Kesehatan</label>
                                            <input name="no_bpjs" id="no_bpjs" class="form-control" type="text" <?php if($status=='edit' ): ?> value="<?php echo e($karyawan->no_bpjs); ?>" <?php endif; ?> >
                                            <label class="form-label" for="no_bpjstk">No BPJS Ketenagakerjaan</label>
                                            <input name="no_bpjstk" id="no_bpjstk" class="form-control" type="text" <?php if($status=='edit' ): ?> value="<?php echo e($karyawan->no_bpjstk); ?>" <?php endif; ?> >
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-4">
                                            <label class="form-label" for="alamat_domisili">Alamat Domisili</label>
                                            <textarea name="alamat_domisili" id="alamat_domisili" class="form-control" rows="3" ><?php if($status == 'edit'): ?><?php echo e($karyawan->alamat_domisili); ?><?php endif; ?></textarea>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label" for="alamat_ktp">Alamat KTP</label>
                                            <textarea name="alamat_ktp" id="alamat_ktp" class="form-control" rows="3" ><?php if($status == 'edit'): ?><?php echo e($karyawan->alamat_ktp); ?><?php endif; ?></textarea>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label" for="alamat_npwp">Alamat NPWP</label>
                                            <textarea name="alamat_npwp" id="alamat_npwp" class="form-control" rows="3" ><?php if($status == 'edit'): ?><?php echo e($karyawan->alamat_npwp); ?><?php endif; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-4">
                                            <label class="form-label" for="telepon1">Telepon 1</label>
                                            <input name="telepon1" id="telepon1" class="form-control" type="text" <?php if($status=='edit' ): ?> value="<?php echo e($karyawan->telepon1); ?>" <?php endif; ?> >
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label" for="telepon2">Telepon 2</label>
                                            <input name="telepon2" id="telepon2" class="form-control" type="text" <?php if($status=='edit' ): ?> value="<?php echo e($karyawan->telepon2); ?>" <?php endif; ?> >
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label" for="telepon3">Telepon 3</label>
                                            <input name="telepon3" id="telepon3" class="form-control" type="text" <?php if($status=='edit' ): ?> value="<?php echo e($karyawan->telepon3); ?>" <?php endif; ?> >
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-4">
                                            <label class="form-label" for="telepon_darurat">Telepon Darurat</label>
                                            <input name="telepon_darurat" id="telepon_darurat" class="form-control" type="text" <?php if($status=='edit' ): ?> value="<?php echo e($karyawan->telepon_darurat); ?>" <?php endif; ?> >
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label" for="kode_kawin">Status Sipil</label>
                                            <select class="form-select" id="kode_kawin" name="kode_kawin" >
                                                <option selected="" disabled="" value="">Pilih...</option>
                                                <?php $__currentLoopData = $kawin; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($data->id); ?>" <?php if($status=='edit' ): ?> <?php if($data->id == $karyawan->kode_kawin): ?> selected <?php endif; ?> <?php endif; ?> ><?php echo e($data->status_kawin); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label" for="nama_pasangan">Nama Pasangan</label>
                                            <input name="nama_pasangan" id="nama_pasangan" class="form-control" type="text" <?php if($status=='edit' ): ?> value="<?php echo e($karyawan->nama_pasangan); ?>" <?php endif; ?> >
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-4">
                                            <label class="form-label" for="no_rek">Nomor Rekening</label>
                                            <input name="no_rek" id="no_rek" class="form-control" type="text" <?php if($status=='edit' ): ?> value="<?php echo e($karyawan->no_rek); ?>" <?php endif; ?> >
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label" for="nama_bank">Nama Bank</label>
                                            <input name="nama_bank" id="nama_bank" class="form-control" type="text" <?php if($status=='edit' ): ?> value="<?php echo e($karyawan->nama_bank); ?>" <?php endif; ?> >
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label" for="nama_rek">Rekening Atas Nama</label>
                                            <input name="nama_rek" id="nama_rek" class="form-control" type="text" <?php if($status=='edit' ): ?> value="<?php echo e($karyawan->nama_rek); ?>" <?php endif; ?> >
                                        </div>
                                    </div>
                                </p>
                            </div>
                            <!--  -->
                            <div class="tab-pane fade" id="pills-emp_organization" role="tabpanel" aria-labelledby="pills-emp_organization-tab">
                                <p class="mb-0 mt-2">
                                    <div class="row mt-2">
                                        <div class="col-md-4">
                                            <label class="form-label" for="email">Email</label>
                                            <input name="email" id="email" class="form-control" type="text" <?php if($status=='edit' ): ?> value="<?php echo e($karyawan->email); ?>" <?php endif; ?> required="">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label" for="kode_perusahaan">Perusahaan</label>
                                            <select class="form-select" id="kode_perusahaan" name="kode_perusahaan" required="">
                                                <option selected="" value="">Pilih...</option>
                                                <?php $__currentLoopData = $perusahaan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($data->id); ?>" <?php if($status=='edit' ): ?> <?php if($data->id == $karyawan->kode_perusahaan): ?> selected <?php endif; ?> <?php endif; ?> ><?php echo e($data->nama); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label" for="alamat_kantor">Alamat Kantor</label>
                                            <select class="form-select" id="alamat_kantor" name="alamat_kantor" required="">
                                                <option selected="" value="">Pilih...</option>
                                                <?php $__currentLoopData = $alamat_kantor; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($data['key']); ?>" <?php if($status=='edit' ): ?> <?php if($data['key'] == $karyawan->alamat_kantor): ?> selected <?php endif; ?> <?php endif; ?> ><?php echo e($data['value']); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-4">
                                            <label class="form-label" for="kode_divisi">Direktorat</label>
                                            <select class="form-select" id="kode_divisi" name="kode_divisi" required="">
                                                <option selected="" value="">Pilih...</option>
                                                <?php if($status=='edit' ): ?> 
                                                <?php $__currentLoopData = $divisi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($data->id); ?>" <?php if($data->id == $karyawan->kode_divisi): ?> selected <?php endif; ?> ><?php echo e($data->nama); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php endif; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label" for="kode_departemen">Divisi</label>
                                            <select class="form-select" id="kode_departemen" name="kode_departemen" required="">
                                                <option selected="" value="">Pilih...</option>
                                                <?php if($status=='edit' ): ?> 
                                                <?php $__currentLoopData = $dep; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($data->id); ?>" <?php if($data->id == $karyawan->kode_departemen): ?> selected <?php endif; ?> ><?php echo e($data->nama); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php endif; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label" for="kode_sub_departemen">Departemen</label>
                                            <select class="form-select" id="kode_sub_departemen" name="kode_sub_departemen" required="">
                                                <option selected="" value="">Pilih...</option>
                                                <?php if($status=='edit' ): ?> 
                                                <?php $__currentLoopData = $sub_dep; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($data->id); ?>" <?php if($data->id == $karyawan->kode_sub_departemen): ?> selected <?php endif; ?> ><?php echo e($data->nama); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php endif; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-4">
                                            <label class="form-label" for="layer1">Layer 1</label>
                                            <select class="form-select js-example-basic-single col-sm-12" id="layer1" name="layer1" required="">
                                                <option selected="" disabled="" value="">Pilih...</option>
                                                <?php $__currentLoopData = $layer; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($data->nik); ?>" <?php if($status=='edit' ): ?> <?php if($data->nik == $karyawan->layer1): ?> selected <?php endif; ?> <?php endif; ?> ><?php echo e($data->nama); ?> / <?php echo e($data->nik); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                            <div class="invalid-feedback txt-danger">Field is required.</div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label" for="layer2">Layer 2</label>
                                            <select class="form-select js-example-basic-single col-sm-12" id="layer2" name="layer2" required="">
                                                <option selected="" disabled="" value="">Pilih...</option>
                                                <?php $__currentLoopData = $layer; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($data->nik); ?>" <?php if($status=='edit' ): ?> <?php if($data->nik == $karyawan->layer2): ?> selected <?php endif; ?> <?php endif; ?> ><?php echo e($data->nama); ?> / <?php echo e($data->nik); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                            <div class="invalid-feedback txt-danger">Field is required.</div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label" for="layer3">Layer 3</label>
                                            <select class="form-select js-example-basic-single col-sm-12" id="layer3" name="layer3" required="">
                                                <option selected="" disabled="" value="">Pilih...</option>
                                                <?php $__currentLoopData = $layer; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($data->nik); ?>" <?php if($status=='edit' ): ?> <?php if($data->nik == $karyawan->layer3): ?> selected <?php endif; ?> <?php endif; ?> ><?php echo e($data->nama); ?> / <?php echo e($data->nik); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                            <div class="invalid-feedback txt-danger">Field is required.</div>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-4">
                                            <label class="form-label" for="kode_status">Status Karyawan</label>
                                            <select class="form-select id="kode_status" name="kode_status" required="">
                                                <option selected="" disabled="" value="">Pilih...</option>
                                                <?php $__currentLoopData = $kar_status; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($data->id); ?>" <?php if($status=='edit' ): ?> <?php if($data->id == $karyawan->kode_status): ?> selected <?php endif; ?> <?php endif; ?> ><?php echo e($data->status_karyawan); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label" for="wg_id">Grup Kerja</label>
                                            <select class="form-select" id="wg_id" name="wg_id" required="">
                                                <option selected="" disabled="" value="">Pilih...</option>
                                                <?php $__currentLoopData = $wg; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($data->id); ?>" <?php if($status=='edit' ): ?> <?php if($data->id == $karyawan->wg_id): ?> selected <?php endif; ?> <?php endif; ?> ><?php echo e($data->wg_name); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label" for="is_aktif">Aktif/Non Aktif</label>
                                            <select class="form-select" id="is_aktif" name="is_aktif" required="">
                                                <option selected="" disabled="" value="">Pilih...</option>
                                                <?php $__currentLoopData = $is_aktif; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($data['id']); ?>" <?php if($status=='edit' ): ?> <?php if($data['id'] == $karyawan->is_aktif): ?> selected <?php endif; ?> <?php endif; ?> ><?php echo e($data['status']); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-4">
                                            <label class="form-label" for="id_fp">ID Finger Print</label>
                                            <input id="id_fp" name="id_fp" min="0" class="form-control numbers" type="number" <?php if($status=='edit' ): ?> value="<?php echo e($karyawan->id_fp); ?>" <?php endif; ?>>
                                        </div>
                                    </div>
                                </p>
                            </div>
                            <div class="tab-pane fade" id="pills-emp_salary" role="tabpanel" aria-labelledby="pills-emp_salary-tab">
                                <p class="mb-0 mt-2">
                                    <div class="row mt-2">
                                        <div class="col-md-4">
                                            <label class="form-label" for="gaji_pokok">Gaji Pokok</label>
                                            <input id="gaji_pokok" name="gaji_pokok" min="0" class="form-control numbers" type="number" <?php if($status=='edit' ): ?> value="<?php echo e($karyawan->gaji_pokok); ?>" <?php endif; ?> required="">
                                        </div>
                                    </div>
                                </p>
                            </div>
                            <div class="tab-pane fade" id="pills-emp_level" role="tabpanel" aria-labelledby="pills-emp_level-tab">
                                <p class="mb-0 mt-2">
                                    <div class="row mt-2">
                                        <div class="col-md-3">
                                            <label class="form-label" for="kode_level">Level</label>
                                            <select class="form-select" id="kode_level" name="kode_level" required="">
                                                <option selected="" disabled="" value="">Pilih...</option>
                                                <?php $__currentLoopData = $level; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($data->id); ?>" <?php if($status=='edit' ): ?> <?php if($data->id == $karyawan->kode_level): ?> selected <?php endif; ?> <?php endif; ?> ><?php echo e($data->level); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label" for="id_grade">Grade</label>
                                            
                                            <select class="form-select" id="id_grade" name="id_grade" required="">
                                                <option selected="" value="">Pilih...</option>
                                                <?php if($status=='edit' ): ?> 
                                                <?php $__currentLoopData = $id_grade; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($data->id); ?>" <?php if($data->id == $karyawan->id_grade): ?> selected <?php endif; ?> ><?php echo e($data->grade); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php endif; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label" for="kode_jabatan">Jabatan</label>
                                            <select class="form-select js-example-basic-single col-sm-12" id="kode_jabatan" name="kode_jabatan" required="">
                                                <option selected="" disabled="" value="">Pilih...</option>
                                                <?php $__currentLoopData = $jabatan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($data->id); ?>" <?php if($status=='edit' ): ?> <?php if($data->id == $karyawan->kode_jabatan): ?> selected <?php endif; ?> <?php endif; ?> ><?php echo e($data->jabatan); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                            <div class="invalid-feedback txt-danger">Field is required.</div>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label" for="tanggal_mulai">Tanggal Mulai Bekerja</label>
                                            <input name="tanggal_mulai" id="tanggal_mulai" class="form-control" type="date" <?php if($status=='edit' ): ?> value="<?php echo e($karyawan->tanggal_mulai); ?>" <?php endif; ?> >
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-3">
                                            <label class="form-label" for="tanggal_bergabung">Tanggal Bergabung</label>
                                            <input name="tanggal_bergabung" id="tanggal_bergabung" class="form-control" type="date" <?php if($status=='edit' ): ?> value="<?php echo e($karyawan->tanggal_bergabung); ?>" <?php endif; ?> >
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label" for="tanggal_berakhir">Tanggal Berakhir</label>
                                            <input name="tanggal_berakhir" id="tanggal_berakhir" class="form-control" type="date" <?php if($status=='edit' ): ?> value="<?php echo e($karyawan->tanggal_berakhir); ?>" <?php endif; ?> >
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label" for="tanggal_karyawan_tetap">Tanggal Karyawan Tetap</label>
                                            <input name="tanggal_karyawan_tetap" id="tanggal_karyawan_tetap" class="form-control" type="date" <?php if($status=='edit' ): ?> value="<?php echo e($karyawan->tanggal_karyawan_tetap); ?>" <?php endif; ?> >
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label" for="tanggal_tidak_aktif">Tanggal Tidak Aktif</label>
                                            <input name="tanggal_tidak_aktif" id="tanggal_tidak_aktif" class="form-control" type="date" <?php if($status=='edit' ): ?> value="<?php echo e($karyawan->tanggal_tidak_aktif); ?>" <?php endif; ?> >
                                        </div>
                                    </div>
                                </p>
                            </div>
                            
                            <div class="tab-pane fade" id="pills-emp_findim" role="tabpanel" aria-labelledby="pills-emp_findim-tab">
                                <p class="mb-0 mt-2">
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <label class="form-label" for="findim_project">Project</label>
                                            <select class="form-select js-example-basic-single col-sm-12" id="findim_project" name="findim_project" required="">
                                                <option selected="" disabled="" value="">Pilih...</option>
                                                <?php $__currentLoopData = $findim_project; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($data->id); ?>" <?php if($status=='edit' ): ?> <?php if($data->id == $karyawan->findim_project): ?> selected <?php endif; ?> <?php endif; ?>><?php echo e($data->project_id); ?> - <?php echo e($data->project_desc); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                            <div class="invalid-feedback txt-danger">Project is required.</div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label" for="findim_divisi">Divisi</label>
                                            <select class="form-select js-example-basic-single col-sm-12" id="findim_divisi" name="findim_divisi" required="">
                                                <option selected="" disabled="" value="">Pilih...</option>
                                                <?php $__currentLoopData = $findim_divisi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($data->id); ?>" <?php if($status=='edit' ): ?> <?php if($data->id == $karyawan->findim_divisi): ?> selected <?php endif; ?> <?php endif; ?>><?php echo e($data->divisi_id); ?> - <?php echo e($data->divisi_desc); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                            <div class="invalid-feedback txt-danger">Divisi is required.</div>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <label class="form-label" for="findim_departemen">Departemen</label>
                                            <select class="form-select js-example-basic-single col-sm-12" id="findim_departemen" name="findim_departemen" required="">
                                                <option selected="" disabled="" value="">Pilih...</option>
                                                <?php $__currentLoopData = $findim_departemen; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($data->id); ?>" <?php if($status=='edit' ): ?> <?php if($data->id == $karyawan->findim_departemen): ?> selected <?php endif; ?> <?php endif; ?>><?php echo e($data->departemen_id); ?> - <?php echo e($data->departemen_desc); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                            <div class="invalid-feedback txt-danger">Departemen is required.</div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label" for="findim_subdepartemen">Sub Departemen</label>
                                            <select class="form-select js-example-basic-single col-sm-12" id="findim_subdepartemen" name="findim_subdepartemen" required="">
                                                <option selected="" disabled="" value="">Pilih...</option>
                                                <?php $__currentLoopData = $findim_subdepartemen; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($data->id); ?>" <?php if($status=='edit' ): ?> <?php if($data->id == $karyawan->findim_subdepartemen): ?> selected <?php endif; ?> <?php endif; ?>><?php echo e($data->subdepartemen_id); ?> - <?php echo e($data->subdepartemen_desc); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                            <div class="invalid-feedback txt-danger">Sub Departemen is required.</div>
                                        </div>
                                    </div>
                                </p>
                            </div>
                            
                            <?php if($status == 'edit'): ?>
                            <div class="tab-pane fade" id="pills-emp_history" role="tabpanel" aria-labelledby="pills-emp_history-tab">
                                <p class="mb-0 mt-2">
                                    <!-- <div class="row mt-2"> -->
                                    <div class="table-responsive">
                                        <table class="display" id="emp-history">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Karyawan</th>
                                                    <th>Diubah Oleh</th>
                                                    <th>Perubahan Data</th>
                                                    <th>Waktu Perubahan</th>
                                                    <th>Sumber</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $__currentLoopData = $history; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php
                                                    $_history = json_decode($data->perubahan, true);
                                                    $catatan = json_decode($data->catatan, true);
                                                ?>
                                                <tr>
                                                    <td><?php echo e(++$no_history); ?></td>
                                                    <td><?php echo e($data->_nik->nama); ?> / <?php echo e($data->nik); ?></td>
                                                    <td><?php echo e($data->_insert_by->nama); ?> / <?php echo e($data->insert_by); ?></td>
                                                    <td>
                                                        <table class="table table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th>Kolom</th>
                                                                    <th>Sebelum</th>
                                                                    <th>Sesudah</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php $__currentLoopData = $_history; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $_data => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <tr>
                                                                    <td><?php echo e($lbl_history[$_data]); ?></td>
                                                                    <td><?php if(in_array($_data, ['layer1','layer2','layer3'])): ?> <?php echo e(App\Models\Karyawan::where('nik', $val['before'])->first()->nama); ?> / <?php endif; ?> <?php echo e($val['before']); ?></td>
                                                                    <td><?php if(in_array($_data, ['layer1','layer2','layer3'])): ?> <?php echo e(App\Models\Karyawan::where('nik', $val['after'])->first()->nama); ?> / <?php endif; ?> <?php echo e($val['after']); ?></td>
                                                                </tr>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                    <td><?php echo e($data->created_at); ?></td>
                                                    <td><?php echo e($catatan['sumber']); ?></td>
                                                </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- </div> -->
                                </p>
                            </div>
                            <?php endif; ?>
                        </div>
                        <hr/>
                        <button class="btn btn-success" type="submit">Submit</button> | <a href="<?php echo e(route('karyawan.index')); ?>" class="btn btn-warning" type="button">Back</a>
                    </div>
                </div>
            </div>
            </form>
            <!-- finish -->
            <!-- start -->
            <?php if($status=='edit' ): ?>
            <div class="col-sm-12 col-xxl-12">
                <div class="card">
                    <div class="card-body">
                        <ul class="nav nav-tabs nav-primary" id="pills-warningtab" role="tablist">
                            <li class="nav-item"><a class="nav-link active" id="pills-emp_photo-tab" data-bs-toggle="pill"
                                    href="#pills-emp_photo" role="tab" aria-controls="pills-emp_photo" aria-selected="false">Foto</a></li>
                            <li class="nav-item"><a class="nav-link" id="pills-emp_anak-tab" data-bs-toggle="pill"
                                    href="#pills-emp_anak" role="tab" aria-controls="pills-emp_anak" aria-selected="false">Nama Anak</a></li>
                            <li class="nav-item"><a class="nav-link" id="pills-emp_edu-tab" data-bs-toggle="pill"
                                    href="#pills-emp_edu" role="tab" aria-controls="pills-emp_edu" aria-selected="false">Pendidikan</a></li>
                            <li class="nav-item"><a class="nav-link" id="pills-emp_lat-tab" data-bs-toggle="pill"
                                    href="#pills-emp_lat" role="tab" aria-controls="pills-emp_lat" aria-selected="false">Pelatihan / Training</a></li>
                            <li class="nav-item"><a class="nav-link" id="pills-emp_ser-tab" data-bs-toggle="pill"
                                    href="#pills-emp_ser" role="tab" aria-controls="pills-emp_ser" aria-selected="false">Sertifikasi</a></li>
                            <li class="nav-item"><a class="nav-link" id="pills-emp_sem-tab" data-bs-toggle="pill"
                                    href="#pills-emp_sem" role="tab" aria-controls="pills-emp_sem" aria-selected="false">Seminar / Workshop</a></li>
                            <li class="nav-item"><a class="nav-link" id="pills-emp_penilaian-tab" data-bs-toggle="pill"
                                    href="#pills-emp_penilaian" role="tab" aria-controls="pills-emp_penilaian" aria-selected="false">Penilaian</a></li>
                        </ul>
                        <div class="tab-content" id="pills-warningtabContent">
                            <div class="tab-pane fade show active" id="pills-emp_photo" role="tabpanel" aria-labelledby="pills-emp_photo-tab">
                                <p class="mb-0 mt-2">
                                <?php if(file_exists(public_path().'/assets/images/matta/karyawan/'.$karyawan->nik.'/'.$karyawan->nik.'.jpg')): ?>
                                    <a href="<?php echo e(asset('assets/images/matta/karyawan/'.$karyawan->nik.'/'.$karyawan->nik.'.jpg')); ?>" target="_blank">
                                        <img class="rounded" width="300" alt="" src="<?php echo e(asset('assets/images/matta/karyawan/'.$karyawan->nik.'/'.$karyawan->nik.'.jpg')); ?>">
                                    </a>
                                <?php else: ?>
                                    <a href="<?php echo e(asset('assets/images/matta/karyawan/user.png')); ?>" target="_blank">
                                        <img class="rounded" width="300" alt="" src="<?php echo e(asset('assets/images/matta/karyawan/user.png')); ?>">
                                    </a>
                                <?php endif; ?>
                                </p><br/>
                                <form action="<?php echo e(route('upload.image.store' )); ?>" method="POST" enctype="multipart/form-data">
                                <?php echo csrf_field(); ?>
                                    <div class="row"> <br>   
                                        <div class="col-md-4">
                                            <input type="file" name="image" class="form-control">
                                            <input type="hidden" id="nik" name="nik" value="<?php echo e($karyawan->nik); ?>">
                                        </div>     
                                        <div class="col-md-4">
                                            <button type="submit" class="btn btn-success">Upload</button>
                                        </div>     
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="pills-emp_anak" role="tabpanel" aria-labelledby="pills-emp_anak-tab">
                                <p class="mb-0 mt-2">
                                    <a href="<?php echo e(route('karyawananak.create', ['nik'=>(new App\Http\Controllers\OpenDataController)->encryptWithKey($karyawan->nik), 'karyawan_id'=>Crypt::encrypt($karyawan->id)] )); ?>" class="btn btn-primary" type="button"><i class="fa fa-plus"></i> Add new</a><br /><br />
                                    <div class="table-responsive">
                                        <table class="display" id="basic-anak">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Urutan</th>
                                                    <th>Nama</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $__currentLoopData = $anak; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td><?php echo e(++$no_anak); ?></td>
                                                    <td><?php echo e($data->_anakke->anakke); ?></td>
                                                    <td><?php echo e($data->nama); ?></td>
                                                    <td>
                                                        <a href="<?php echo e(route('karyawananak.edit', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id), 'karyawan_id'=>Crypt::encrypt($karyawan->id)])); ?>" data-toggle="tooltip" title='Edit'><i class="icon-pencil-alt"></i></a>
                                                    </td>
                                                </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </p>
                            </div>
                            <div class="tab-pane fade" id="pills-emp_edu" role="tabpanel" aria-labelledby="pills-emp_edu-tab">
                                <p class="mb-0 mt-2">
                                    <a href="<?php echo e(route('karyawanpendidikan.create', ['nik'=>(new App\Http\Controllers\OpenDataController)->encryptWithKey($karyawan->nik), 'karyawan_id'=>Crypt::encrypt($karyawan->id)] )); ?>" class="btn btn-primary" type="button"><i class="fa fa-plus"></i> Add new</a><br /><br />
                                    <div class="table-responsive">
                                        <table class="display" id="basic-pendidikan">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Jenjang Pendidikan</th>
                                                    <th>Sekolah/Universitas</th>
                                                    <th>Jurusan/Prodi</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $no=0; ?>
                                                <?php $__currentLoopData = $pendidikan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td><?php echo e(++$no); ?></td>
                                                    <td><?php echo e($data->_jenjang->jenjang); ?></td>
                                                    <td><?php echo e($data->nama); ?></td>
                                                    <td><?php echo e($data->jurusan_prodi); ?></td>
                                                    <td>
                                                        <a href="<?php echo e(route('karyawanpendidikan.edit', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id), 'karyawan_id'=>Crypt::encrypt($karyawan->id)])); ?>" data-toggle="tooltip" title='Edit'><i class="icon-pencil-alt"></i></a>
                                                    </td>
                                                </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </p>
                            </div>
                            
                            <div class="tab-pane fade" id="pills-emp_lat" role="tabpanel" aria-labelledby="pills-emp_lat-tab">
                            <p class="mb-0 mt-2">
                                    
                                    <div class="table-responsive">
                                        <table class="display" id="basic-pelatihan">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Nama Pelatihan</th>
                                                    <th>Keterangan</th>
                                                    
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $no=0; ?>
                                                <?php $__currentLoopData = $pelatihan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td><?php echo e(++$no); ?></td>
                                                    <td><?php echo e($data->judul); ?></td>
                                                    <td><?php echo e($data->keterangan); ?></td>
                                                    
                                                </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </p>
                            </div>
                            
                            <div class="tab-pane fade" id="pills-emp_ser" role="tabpanel" aria-labelledby="pills-emp_ser-tab">
                            <p class="mb-0 mt-2">
                                    
                                    <div class="table-responsive">
                                        <table class="display" id="basic-sertifikasi">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Nama Sertifikasi</th>
                                                    <th>Keterangan</th>
                                                    
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $no=0; ?>
                                                <?php $__currentLoopData = $sertifikasi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td><?php echo e(++$no); ?></td>
                                                    <td><?php echo e($data->judul); ?></td>
                                                    <td><?php echo e($data->keterangan); ?></td>
                                                    
                                                </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </p>
                            </div>
                            
                            <div class="tab-pane fade" id="pills-emp_sem" role="tabpanel" aria-labelledby="pills-emp_sem-tab">
                            <p class="mb-0 mt-2">
                                    
                                    <div class="table-responsive">
                                        <table class="display" id="basic-seminar">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Nama Seminar</th>
                                                    <th>Keterangan</th>
                                                    
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $no=0; ?>
                                                <?php $__currentLoopData = $seminar; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td><?php echo e(++$no); ?></td>
                                                    <td><?php echo e($data->judul); ?></td>
                                                    <td><?php echo e($data->keterangan); ?></td>
                                                    
                                                </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </p>
                            </div>
                            <div class="tab-pane fade" id="pills-emp_penilaian" role="tabpanel" aria-labelledby="pills-emp_penilaian-tab">
                                <p class="mb-0 mt-2">
                                    <div class="table-responsive">
                                        <table class="display" id="basic-penilaian">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>NIP</th>
                                                    <th>Nama</th>
                                                    <th>Penilaian Ke</th>
                                                    <th>Tanggal Selesai PKWT</th>
                                                    <th>Tanggal Selesai Penilaian</th>
                                                    <th>Nilai Total Karyawan</th>
                                                    <th>Nilai Total Penilai</th>
                                                    <th>Durasi Perpanjangan</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $no = 0; ?>
                                                <?php $__currentLoopData = $penilaian; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php
                                                    $karyawan_property = json_decode($data->karyawan_property, true);
                                                    $urutan = App\Models\TrxPenilaian::where('nik_karyawan', $karyawan_property['nik'])->where('id', '<=', $data->id)->count();
                                                ?>
                                                <tr>
                                                    <td><?php echo e(++$no); ?></td>
                                                    <td><?php echo e($data->nik_karyawan); ?></td>
                                                    <td><?php echo e($karyawan_property['nama']); ?></td>
                                                    <td><?php echo e($urutan); ?></td>
                                                    <td>
                                                        <?php if(!empty($karyawan_property['tanggal_berakhir'])): ?>
                                                            <?php echo e(Carbon\Carbon::parse($karyawan_property['tanggal_berakhir'])->format('d-m-Y')); ?>

                                                        <?php endif; ?>
                                                    </td>
                                                    
                                                    <?php
                                                        $created_at = App\Models\TrxPenilaianLog::where(['id_trx' => $data->id])->whereIn('id_status', [4, 8])->orderBy('created_at', 'desc')->first();
                                                    ?>
                                                    <td>
                                                        <?php if(!empty($created_at)): ?>
                                                            <?php echo e($created_at->created_at); ?>

                                                        <?php endif; ?>
                                                    </td>
                                                    
                                                    <?php
                                                        $skor = App\Models\TrxPenilaianSkor::where(['id_trx' => $data->id])->sum('skor_karyawan');
                                                    ?>
                                                    <td><?php if($skor > 0): ?><?php echo e($skor); ?><?php endif; ?></td>
                                                    
                                                    <?php
                                                        $skor = App\Models\TrxPenilaianSkor::where(['id_trx' => $data->id])->sum('skor_penilai_1');
                                                    ?>
                                                    <td><?php echo e($skor); ?></td>
                                                    <td><?php echo e($data->_perpanjang->informasi); ?> <?php if($data->is_perpanjang == 1): ?><?php echo e($data->bulan); ?> Bulan <?php endif; ?></td>
                                                </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            <!-- finish -->
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="<?php echo e(asset('assets/js/form-validation-custom.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/sweet-alert/sweetalert.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/sweet-alert/app.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/select2/select2.full.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/select2/select2-custom.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatables/jquery.dataTables.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatables/datatable.custom.js')); ?>"></script>
<script type="text/javascript">
// $(document).ready(function() {
//     function validateForm() {
//         var nik = $("#nik").val();
//         var nama = $("#nama").val();
//         var email = $("#email").val();
//         var kode_perusahaan = $("#kode_perusahaan").val();
//         var kode_divisi = $("#kode_divisi").val();
//         var kode_departemen = $("#kode_departemen").val();
//         var kode_sub_departemen = $("#kode_sub_departemen").val();
//         var layer1 = $("#layer1").val();
//         var layer2 = $("#layer2").val();
//         var layer3 = $("#layer3").val();
//         if (nik == "") {
//             alert("Field nik is required!");
//             return false;
//         }
//         else if (nama == "") {
//             alert("Field nama is required!");
//             return false;
//         }
//         else if (email == "") {
//             alert("Field email is required!");
//             return false;
//         }
//         else if (kode_perusahaan == "") {
//             alert("Field perusahaan is required!");
//             return false;
//         }
//         else if (kode_divisi == "") {
//             alert("Field divisi is required!");
//             return false;
//         }
//         else if (kode_departemen == "") {
//             alert("Field departemen is required!");
//             return false;
//         }
//         else if (kode_sub_departemen == "") {
//             alert("Field sub departemen is required!");
//             return false;
//         }
//         else if (layer1 == "") {
//             alert("Field layer 1 is required!");
//             return false;
//         }
//         else if (layer2 == "") {
//             alert("Field layer 2 is required!");
//             return false;
//         }
//         else if (layer3 == "") {
//             alert("Field layer 3 is required!");
//             return false;
//         }
//     }
// });
$(document).ready(function() {
    // 
    $('#basic-pendidikan').DataTable();
    $('#basic-pelatihan').DataTable();
    $('#basic-sertifikasi').DataTable();
    $('#basic-seminar').DataTable();
    $('#basic-penilaian').DataTable();
    $('#basic-anak').DataTable();
    $('#emp-history').DataTable();
    // 
    var _kode_perusahaan = 0;
    var _kode_divisi = 0;
    var _kode_departemen = 0;
    var _kode_sub_departemen = 0;
    // 
    $("#kode_level").change(function () {
        var kode_level = $(this).val();
        $('#id_grade').find('option').not(':first').remove();
        <?php $__currentLoopData = $id_grade; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            var option = "<option value=<?php echo e($data->id); ?>><?php echo e($data->grade); ?></option>";
            if(kode_level == <?php echo e($data->id); ?>){
                option = "<option value=<?php echo e($data->id); ?> selected><?php echo e($data->grade); ?></option>";
            }
            $("#id_grade").append(option);
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    });
    // 
    $("#kode_perusahaan").change(function() {
        var _kode_perusahaan = $(this).val();
        // alert(" Value: " + _kode_perusahaan);
        $('#kode_divisi').find('option').not(':first').remove();
        $('#kode_departemen').find('option').not(':first').remove();
        $('#kode_sub_departemen').find('option').not(':first').remove();
        // AJAX request 
        $.ajax({
            url: "<?php echo e(route('get_divisi')); ?>"+"/"+_kode_perusahaan,
            type: 'get',
            dataType: 'json',
            success: function(response) {
                // alert(" Value: " + response[0]['nama']);
                var len = 0;
                if (response != null) {
                    len = response.length;
                    // alert(" Value: " + len);
                }
                if (len > 0) {
                    // Read data and create <option>
                    for (var i = 0; i < len; i++) {
                        var id = response[i]['id'];
                        var nama = response[i]['nama'];
                        var option = "<option value='" + id + "'>" + nama + "</option>";
                        $("#kode_divisi").append(option);
                    }
                }
            }
        });
    });
    // 
    $("#kode_divisi").change(function() {
        var _kode_divisi = $(this).val();
        $('#kode_departemen').find('option').not(':first').remove();
        $('#kode_sub_departemen').find('option').not(':first').remove();
        $.ajax({
            url: "<?php echo e(route('get_departemen')); ?>"+"/"+_kode_divisi,
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
                        $("#kode_departemen").append(option);
                    }
                }
            }
        });
    });
    // 
    $("#kode_departemen").change(function() {
        var _kode_departemen = $(this).val();
        $('#kode_sub_departemen').find('option').not(':first').remove();
        $.ajax({
            url: "<?php echo e(route('get_sub_departemen')); ?>"+"/"+_kode_departemen,
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
                        $("#kode_sub_departemen").append(option);
                    }
                }
            }
        });
    });
    // 
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/master/karyawan/profile-tab.blade.php ENDPATH**/ ?>