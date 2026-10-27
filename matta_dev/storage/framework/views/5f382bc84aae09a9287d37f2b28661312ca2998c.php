<?php $__env->startSection('title', 'Formulir Kebutuhan Karyawan'); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/sweetalert2.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/select2.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
<h3>Add</h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Formulir Kebutuhan Karyawan</li>
<li class="breadcrumb-item active">Add</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <!--  -->
        <div class="col-sm-12 col-xl-12">
            <div class="card">
                <form action="<?php echo e(route('kebutuhan.store')); ?>" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return cekIsian()">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" id="nik_pengaju" name="nik_pengaju" value="<?php echo e($nik_pengaju); ?>">
                    <div class="row">
                        <!-- start -->
                        <div class="col-md-6">
                            
                            <div class="card-body">
                                <h5>Kebutuhan Rekruitmen</h5>
                                <div class="col-md-12">
                                    <label class="form-label" for="pengaju">Pengaju</label>
                                    <input class="form-control" name="pengaju" id="pengaju" type="text" required="" readonly="" value="<?php echo e($pengaju->nama); ?> - <?php echo e($pengaju->nik); ?>">
                                </div>
                                <div class="row mt-2">
                                    <label class="form-label" for="id_pekerjaan">Nama Jabatan*</label>
                                    <div class="col-md-11">
                                        <select class="form-select col-sm-12 js-example-basic-single" id="id_pekerjaan" name="id_pekerjaan" required="">
                                            <option selected="" disabled="" value="">Pilih...</option>
                                            <?php $__currentLoopData = $pekerjaan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($data->id); ?>"><?php echo e($data->pekerjaan); ?> (<?php echo e($data->_bidang->bidang); ?>)</option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <div class="invalid-feedback txt-danger">This field is required.</div>
                                    </div>
                                    <div class="col-md-1 text-start">
                                        <a href="<?php echo e(route('pekerjaan_master.index')); ?>" data-toggle="tooltip" title="Add new" target="_blank"><i class="fa fa-plus"></i></a><br/><a href="#" id="get_pekerjaan" data-toggle="tooltip" title="Refresh"><i class="fa fa-refresh"></i></a>
                                    </div>
                                </div>
                                <div class="col-md-12 mt-2">
                                    <label class="form-label" for="atasan">Atasan Langsung Kandidat*</label>
                                    <select class="form-select js-example-basic-single col-sm-12" id="atasan" name="atasan" required="">
                                        <option selected="" disabled="" value="">Pilih...</option>
                                        <?php $__currentLoopData = $atasan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        
                                        <option value="<?php echo e($data->nik); ?>" <?php if($data->nik == $nik_pengaju): ?> selected <?php endif; ?>><?php echo e($data->nama); ?> - <?php echo e($data->nik); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <div class="invalid-feedback txt-danger">This field is required.</div>
                                </div>
                                <div class="col-md-12 mt-2">
                                    <label class="form-label" for="kode_divisi">Direktorat Kandidat*</label>
                                    <select class="form-select" id="kode_divisi" name="kode_divisi" required="">
                                        <option selected="" value="">Pilih...</option>
                                    </select>
                                </div>
                                <div class="col-md-12 mt-2">
                                    <label class="form-label" for="kode_departemen">Divisi Kandidat*</label>
                                    <select class="form-select" id="kode_departemen" name="kode_departemen" required="">
                                        <option selected="" value="">Pilih...</option>
                                    </select>
                                </div>
                                <div class="col-md-12 mt-2">
                                    <label class="form-label" for="kode_sub_departemen">Departemen Kandidat*</label>
                                    <select class="form-select" id="kode_sub_departemen" name="kode_sub_departemen" required="">
                                        <option selected="" value="">Pilih...</option>
                                    </select>
                                </div>
                                <div class="col-md-12 mt-2">
                                    <label class="form-label" for="tugas">Tugas*</label>
                                    <textarea class="form-control" id="tugas" name="tugas" rows="3" required=""></textarea>
                                </div>
                                <div class="col-md-12 mt-2">
                                    <label class="form-label" for="wewenang">Wewenang*</label>
                                    <textarea class="form-control" id="wewenang" name="wewenang" rows="3" required=""></textarea>
                                </div>
                                <div class="col-md-12 mt-2">
                                    <label class="form-label" for="tanggung_jawab">Tanggung Jawab*</label>
                                    <textarea class="form-control" id="tanggung_jawab" name="tanggung_jawab" rows="3" required=""></textarea>
                                </div>
                                <div class="col-md-12 mt-2">
                                    <label class="form-label" for="id_level">Level*</label>
                                    <select class="form-select col-sm-12" id="id_level" name="id_level" required="">
                                        <option selected="" disabled="" value="">Pilih...</option>
                                        <?php $__currentLoopData = $level; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($data->id); ?>"><?php echo e($data->level); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div class="col-md-12 mt-2">
                                    <label class="form-label" for="id_status_kerja">Status Kerja*</label>
                                    <select class="form-select col-sm-12" id="id_status_kerja" name="id_status_kerja" required="">
                                        <option selected="" disabled="" value="">Pilih...</option>
                                        <?php $__currentLoopData = $status_kerja; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($data->id); ?>"><?php echo e($data->status_kerja); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div class="col-md-12 mt-2" id="div_waktu_kontrak">
                                    <label class="form-label" for="waktu_kontrak">Waktu Kontrak (Bulan)*</label>
                                    <input type="number" min="1" oninput="validity.valid||(value='');" name="waktu_kontrak" id="waktu_kontrak" class="form-control numbers" required="">
                                </div>
                                <div class="col-md-12 mt-2">
                                    <label class="form-label" for="jumlah">Jumlah (Orang)*</label>
                                    <input type="number" min="1" oninput="validity.valid||(value='');" name="jumlah" id="jumlah" class="form-control numbers" required="">
                                </div>
                                <div class="col-md-12 mt-2">
                                    <label class="form-label" for="id_kelamin">Jenis Kelamin*</label>
                                    <select class="form-select col-sm-12" id="id_kelamin" name="id_kelamin" required="">
                                        <option selected="" disabled="" value="">Pilih...</option>
                                        <?php $__currentLoopData = $kelamin; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($data->id); ?>"><?php echo e($data->kelamin); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div class="col-md-12 mt-2">
                                    <label class="form-label" for="id_area">Area Kerja*</label>
                                    <select class="form-select col-sm-12" id="id_area" name="id_area" required="">
                                        <option selected="" disabled="" value="">Pilih...</option>
                                        <?php $__currentLoopData = $area; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($data->id); ?>"><?php echo e($data->propinsi); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div class="col-md-12 mt-2" id="div_kota">
                                    <label class="form-label" for="id_kota">Area Kerja Kota</label>
                                    <select class="form-select col-sm-12" id="id_kota" name="id_kota" ></select>
                                </div>
                                <div class="col-md-12 mt-2">
                                    <label class="form-label" for="id_jenis_permintaan">Jenis Permintaan*</label>
                                    <select class="form-select col-sm-12" id="id_jenis_permintaan" name="id_jenis_permintaan" required="">
                                        <option selected="" disabled="" value="">Pilih...</option>
                                        <?php $__currentLoopData = $jenis_permintaan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($data->id); ?>"><?php echo e($data->jenis); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div class="col-md-12 mt-2" id="div_nik_karyawan">
                                    <input type="hidden" id="_nik_karyawan_" name="_nik_karyawan_" >
                                    <label class="form-label" for="nik_karyawan">Karyawan Penggantian*<br/>(Sesuai jumlah pada isian Jumlah (Orang))</label>
                                    <select class="js-example-basic-multiple col-sm-12" id="nik_karyawan" name="nik_karyawan" required="" multiple="multiple">
                                        <!-- <option selected="" disabled="" value="">Pilih...</option> -->
                                        <?php $__currentLoopData = $karyawan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($data->nik); ?>"><?php echo e($data->nama); ?> - <?php echo e($data->nik); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <div class="invalid-feedback txt-danger">This field is required.</div>
                                </div>
                                <div class="col-md-12 mt-2">
                                    <label class="form-label" for="id_rencana">Apakah sudah masuk dalam rencana <span id="str_jenis_permintaan" name="str_jenis_permintaan">penambahan</span> pegawai?*</label>
                                    <select class="form-select col-sm-12" id="id_rencana" name="id_rencana" required="">
                                        <option selected="" disabled="" value="">Pilih...</option>
                                        <option value="0">Belum</option>
                                        <option value="1">Sudah</option>
                                    </select>
                                </div>
                                <div class="col-md-12 mt-2">
                                    <label class="form-label" for="penjelasan">Alasan Permintaan*</label>
                                    <textarea class="form-control" id="penjelasan" name="penjelasan" rows="4" required=""></textarea>
                                </div>
                            </div>
                        </div>
                        <!-- finish -->
                        <!-- start -->
                        <div class="col-md-6">
                            
                            <div class="card-body">
                                <h5>Kualifikasi / Spesifikasi Jabatan</h5>
                                <div class="col-md-12">
                                    <label class="form-label" for="id_jenjang_pendidikan">Pendidikan Minimum*</label>
                                    <select class="form-select col-sm-12" id="id_jenjang_pendidikan" name="id_jenjang_pendidikan" required="">
                                        <option selected="" disabled="" value="">Pilih...</option>
                                        <?php $__currentLoopData = $jenjang_pendidikan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($data->id); ?>"><?php echo e($data->jenjang); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div class="col-md-12 mt-2" id="div_jurusan_pendidikan">
                                    
                                    <label class="form-label" for="id_jurusan_pendidikan[]">Jurusan</label>
                                    <select class="form-select js-example-basic-multiple col-sm-12" multiple="multiple" id="id_jurusan_pendidikan[]" name="id_jurusan_pendidikan[]" >
                                        <option selected="" disabled="" value="">Pilih...</option>
                                        <?php $__currentLoopData = $jurusan_pendidikan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($data->id); ?>"><?php echo e($data->jurusan); ?> (<?php echo e($data->_kelompok->kelompok); ?>)</option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <label class="form-label" for="nilai_ipk">Nilai IPK</label>
                                    <input type="number" name="nilai_ipk" id="nilai_ipk" class="form-control numbers" placeholder="0.00" step=".01">
                                </div>
                                <div class="col-md-12 mt-2">
                                    <label class="form-label" for="umur_minimum">Usia Minimum (Tahun)*</label>
                                    <input type="number" min="1" oninput="validity.valid||(value='');" name="umur_minimum" id="umur_minimum" class="form-control numbers" required="">
                                </div>
                                <div class="col-md-12 mt-2">
                                    <label class="form-label" for="umur_maksimum">Usia Maksimum (Tahun)*</label>
                                    <input type="number" min="1" oninput="validity.valid||(value='');" name="umur_maksimum" id="umur_maksimum" class="form-control numbers" required="">
                                </div>
                                <div class="col-md-12 mt-2">
                                    <label class="form-label" for="id_pengalaman">Pengalaman Kerja*</label>
                                    <select class="form-select col-sm-12" id="id_pengalaman" name="id_pengalaman" required="">
                                        <option selected="" disabled="" value="">Pilih...</option>
                                        <?php $__currentLoopData = $pengalaman_kerja; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($data->id); ?>"><?php echo e($data->pengalaman); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div class="row mt-2">
                                    <label class="form-label" for="id_pelatihan[]">Pelatihan</label>
                                    <div class="col-md-11">
                                        <select class="form-select js-example-basic-multiple col-sm-12" multiple="multiple" id="id_pelatihan[]" name="id_pelatihan[]" required="">
                                            
                                            <?php $__currentLoopData = $pelatihan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($data->id); ?>"><?php echo e($data->sub_pelatihan); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <div class="invalid-feedback txt-danger">This field is required.</div>
                                    </div>
                                    <div class="col-md-1 text-start">
                                        <a href="<?php echo e(route('_pelatihan_master.index')); ?>" data-toggle="tooltip" title="Add new" target="_blank"><i class="fa fa-plus"></i></a><br/><a href="#" id="get_pelatihan" data-toggle="tooltip" title="Refresh"><i class="fa fa-refresh"></i></a>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <label class="form-label" for="id_sertifikasi[]">Sertifikasi</label>
                                    <div class="col-md-11">
                                        <select class="form-select js-example-basic-multiple col-sm-12" multiple="multiple" id="id_sertifikasi[]" name="id_sertifikasi[]" required="">
                                            
                                            <?php $__currentLoopData = $sertifikasi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($data->id); ?>"><?php echo e($data->sub_sertifikasi); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <div class="invalid-feedback txt-danger">This field is required.</div>
                                    </div>
                                    <div class="col-md-1 text-start">
                                        <a href="<?php echo e(route('_sertifikasi_master.index')); ?>" data-toggle="tooltip" title="Add new" target="_blank"><i class="fa fa-plus"></i></a><br/><a href="#" id="get_sertifikasi" data-toggle="tooltip" title="Refresh"><i class="fa fa-refresh"></i></a>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <label class="form-label" for="id_keterampilan[]">Keterampilan</label>
                                    <div class="col-md-11">
                                        <select class="form-select js-example-basic-multiple col-sm-12" multiple="multiple" id="id_keterampilan[]" name="id_keterampilan[]" required="">
                                            
                                            <?php $__currentLoopData = $keterampilan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($data->id); ?>"><?php echo e($data->sub_keterampilan); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <div class="invalid-feedback txt-danger">This field is required.</div>
                                    </div>
                                    <div class="col-md-1 text-start">
                                        <a href="<?php echo e(route('_keterampilan_master.index')); ?>" data-toggle="tooltip" title="Add new" target="_blank"><i class="fa fa-plus"></i></a><br/><a href="#" id="get_keterampilan" data-toggle="tooltip" title="Refresh"><i class="fa fa-refresh"></i></a>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <label class="form-label" for="id_kepribadian[]">Kepribadian</label>
                                    <div class="col-md-11">
                                        <select class="form-select js-example-basic-multiple col-sm-12" multiple="multiple" id="id_kepribadian[]" name="id_kepribadian[]" required="">
                                            
                                            <?php $__currentLoopData = $kepribadian; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($data->id); ?>"><?php echo e($data->sub_kepribadian); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <div class="invalid-feedback txt-danger">This field is required.</div>
                                    </div>
                                    <div class="col-md-1 text-start">
                                        <a href="<?php echo e(route('_kepribadian_master.index')); ?>" data-toggle="tooltip" title="Add new" target="_blank"><i class="fa fa-plus"></i></a><br/><a href="#" id="get_kepribadian" data-toggle="tooltip" title="Refresh"><i class="fa fa-refresh"></i></a>
                                    </div>
                                </div>
                                <div class="col-md-12 mt-2">
                                    <label class="form-label" for="tanggal">Tanggal Mulai Kerja*</label>
                                    <input class="form-control" name="tanggal" id="tanggal" type="date" required="">
                                </div>
                                <div class="col-md-12 mt-2">
                                    <label class="form-label" for="keterangan_lain">Keterangan Lain</label>
                                    <textarea class="form-control" id="keterangan_lain" name="keterangan_lain" rows="4" ></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-12">
                            
                            <div class="card-body">
                                <h5>FinDim / Cost Allocation</h5>
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <label class="form-label" for="findim_project">Project</label>
                                        <select class="form-select js-example-basic-single col-sm-12" id="findim_project" name="findim_project" required="">
                                            <option selected="" value="">Pilih...</option>
                                            <?php $__currentLoopData = $findim_project; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($data->id); ?>"><?php echo e($data->project_id); ?> - <?php echo e($data->project_desc); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <div class="invalid-feedback txt-danger">This field is required.</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="findim_divisi">Divisi</label>
                                        <select class="form-select js-example-basic-single col-sm-12" id="findim_divisi" name="findim_divisi" required="">
                                            <option selected="" value="">Pilih...</option>
                                            <?php $__currentLoopData = $findim_divisi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($data->id); ?>"><?php echo e($data->divisi_id); ?> - <?php echo e($data->divisi_desc); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <div class="invalid-feedback txt-danger">This field is required.</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="findim_departemen">Departemen</label>
                                        <select class="form-select js-example-basic-single col-sm-12" id="findim_departemen" name="findim_departemen" required="">
                                            <option selected="" value="">Pilih...</option>
                                            <?php $__currentLoopData = $findim_departemen; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($data->id); ?>"><?php echo e($data->departemen_id); ?> - <?php echo e($data->departemen_desc); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <div class="invalid-feedback txt-danger">This field is required.</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="findim_subdepartemen">Sub Departemen</label>
                                        <select class="form-select js-example-basic-single col-sm-12" id="findim_subdepartemen" name="findim_subdepartemen" required="">
                                            <option selected="" value="">Pilih...</option>
                                            <?php $__currentLoopData = $findim_subdepartemen; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($data->id); ?>"><?php echo e($data->subdepartemen_id); ?> - <?php echo e($data->subdepartemen_desc); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <div class="invalid-feedback txt-danger">This field is required.</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- finish -->
                        <div class="col-md-12">
                            
                            <?php
                            $check = App\Models\KebutuhanNikKecuali::where(['karyawan'=>$nik_pengaju])->first();
                            ?>
                            <div class="card-body">
                                <h5>Line Approval Pengajuan</h5>
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <label class="form-label" for="layer1">Layer 1 Pengaju*</label>
                                        <?php if(!empty($check)): ?>
                                            <?php echo e($check->_layer1->nama); ?>&nbsp;<input class="form-control" name="layer1" id="layer1" type="text" required="" readonly="" value="<?php echo e($check->layer1); ?>">
                                        <?php else: ?>
                                            <select class="form-select js-example-basic-single col-sm-12" id="layer1" name="layer1" required="">
                                                <option selected="" disabled="" value="">Pilih...</option>
                                            </select>
                                            <div class="invalid-feedback txt-danger">This field is required.</div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="layer2">Layer 2 Pengaju*</label>
                                        <?php if(!empty($check)): ?>
                                            <?php echo e($check->_layer2->nama); ?>&nbsp;<input class="form-control" name="layer2" id="layer2" type="text" required="" readonly="" value="<?php echo e($check->layer2); ?>">
                                        <?php else: ?>
                                            <select class="form-select js-example-basic-single col-sm-12" id="layer2" name="layer2" required="">
                                                <option selected="" disabled="" value="">Pilih...</option>
                                            </select>
                                            <div class="invalid-feedback txt-danger">This field is required.</div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    <hr/>
                    <div class="card-body">
                        <!-- <div class="row g-2"> -->
                            <div class="col-md-12">
                                <button class="btn btn-success" type="submit">Submit</button> | <a href="<?php echo e(route('kebutuhan.index')); ?>" class="btn btn-warning" type="button">Back</a>
                            </div>
                        <!-- </div> -->
                    </div>
                </form>
            </div>
        </div>
        <!--  -->
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
// 
$(document).ready(function() {
    // 
    $('#layer1').change(function() {
        alert('Apakah Anda Yakin Akan Merubah Line Approval Pengajuan?');
    });
    $('#layer2').change(function() {
        alert('Apakah Anda Yakin Akan Merubah Line Approval Pengajuan?');
    });
    // 
    get_kode_divisi();
    function get_kode_divisi() {
        $('#kode_departemen').find('option').not(':first').remove();
        $('#kode_departemen').find('option').not(':first').remove();
        $('#kode_sub_departemen').find('option').not(':first').remove();
        // kode_divisi
        $.ajax({
            url: "<?php echo e(route('get_divisi')); ?>"+"/1",
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
                        var selected = (<?php echo e($pengaju->kode_divisi); ?> == id) ? 'selected' : '';
                        var option = "<option value='" + id + "' " + selected + ">" + nama + "</option>";
                        $("#kode_divisi").append(option);
                    }
                }
            }
        });
        // kode_departemen
        $.ajax({
            url: "<?php echo e(route('get_departemen')); ?>"+"/"+<?php echo e($pengaju->kode_divisi); ?>,
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
                        var selected = (<?php echo e($pengaju->kode_departemen); ?> == id) ? 'selected' : '';
                        var option = "<option value='" + id + "' " + selected + ">" + nama + "</option>";
                        $("#kode_departemen").append(option);
                    }
                }
            }
        });
        // kode_sub_departemen
        $.ajax({
            url: "<?php echo e(route('get_sub_departemen')); ?>"+"/"+<?php echo e($pengaju->kode_departemen); ?>,
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
                        var selected = (<?php echo e($pengaju->kode_sub_departemen); ?> == id) ? 'selected' : '';
                        var option = "<option value='" + id + "' " + selected + ">" + nama + "</option>";
                        $("#kode_sub_departemen").append(option);
                    }
                }
            }
        });
    }
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
    $('#get_kepribadian').on('click', function(e) {
        e.preventDefault();
        $('#id_kepribadian\\[\\]').find('option').remove();
        $("#id_kepribadian\\[\\]").append('<option selected disabled hidden>Pilih...</option>');
        $.ajax({
            url: "<?php echo e(route('get_kepribadian',0)); ?>",
            type: 'get',
            dataType: 'json',
            success: function(response) {
                var len = 0;
                if (response != null) {
                    len = response.length;
                }
                if (len > 0) {
                    for (var i = 0; i < len; i++) {
                        var id = response[i].id;
                        var sub_kepribadian = response[i].sub_kepribadian;
                        var option = "<option value='" + id + "'>" + sub_kepribadian + "</option>";
                        $("#id_kepribadian\\[\\]").append(option);
                    }
                }
            }
        });
    });
    // 
    $('#get_keterampilan').on('click', function(e) {
        e.preventDefault();
        $('#id_keterampilan\\[\\]').find('option').remove();
        $("#id_keterampilan\\[\\]").append('<option selected disabled hidden>Pilih...</option>');
        $.ajax({
            url: "<?php echo e(route('get_keterampilan',0)); ?>",
            type: 'get',
            dataType: 'json',
            success: function(response) {
                var len = 0;
                if (response != null) {
                    len = response.length;
                }
                if (len > 0) {
                    for (var i = 0; i < len; i++) {
                        var id = response[i].id;
                        var sub_keterampilan = response[i].sub_keterampilan;
                        var option = "<option value='" + id + "'>" + sub_keterampilan + "</option>";
                        $("#id_keterampilan\\[\\]").append(option);
                    }
                }
            }
        });
    });
    // 
    $('#get_sertifikasi').on('click', function(e) {
        e.preventDefault();
        $('#id_sertifikasi\\[\\]').find('option').remove();
        $("#id_sertifikasi\\[\\]").append('<option selected disabled hidden>Pilih...</option>');
        $.ajax({
            url: "<?php echo e(route('get_sertifikasi',0)); ?>",
            type: 'get',
            dataType: 'json',
            success: function(response) {
                var len = 0;
                if (response != null) {
                    len = response.length;
                }
                if (len > 0) {
                    for (var i = 0; i < len; i++) {
                        var id = response[i].id;
                        var sub_sertifikasi = response[i].sub_sertifikasi;
                        var option = "<option value='" + id + "'>" + sub_sertifikasi + "</option>";
                        $("#id_sertifikasi\\[\\]").append(option);
                    }
                }
            }
        });
    });
    // 
    $('#get_pelatihan').on('click', function(e) {
        e.preventDefault();
        $('#id_pelatihan\\[\\]').find('option').remove();
        $("#id_pelatihan\\[\\]").append('<option selected disabled hidden>Pilih...</option>');
        $.ajax({
            url: "<?php echo e(route('get_pelatihan',0)); ?>",
            type: 'get',
            dataType: 'json',
            success: function(response) {
                var len = 0;
                if (response != null) {
                    len = response.length;
                }
                if (len > 0) {
                    for (var i = 0; i < len; i++) {
                        var id = response[i].id;
                        var sub_pelatihan = response[i].sub_pelatihan;
                        var option = "<option value='" + id + "'>" + sub_pelatihan + "</option>";
                        $("#id_pelatihan\\[\\]").append(option);
                    }
                }
            }
        });
        e.preventDefault();
    });
    // 
    $('#get_pekerjaan').on('click', function(e) {
        e.preventDefault();
        $('#id_pekerjaan').find('option').remove();
        $("#id_pekerjaan").append('<option selected="" disabled="" value="">Pilih...</option>');
        e.preventDefault();
        $.ajax({
            url: "<?php echo e(route('get_pekerjaan',0)); ?>",
            type: 'get',
            dataType: 'json',
            success: function(response) {
                var len = 0;
                if (response != null) {
                    len = response.length;
                }
                if (len > 0) {
                    for (var i = 0; i < len; i++) {
                        var id = response[i].id;
                        var bidang = response[i].bidang;
                        var pekerjaan = response[i].pekerjaan;
                        var option = "<option value='" + id + "'>" + pekerjaan + " (" + bidang +")</option>";
                        $("#id_pekerjaan").append(option);
                    }
                }
            }
        });
    });
    // 
    // var _url = window.location.origin;
    // default semua hide dan disable=true
    div_waktu_kontrak(0);
    div_kota(0);
    div_jurusan_pendidikan(0);
    div_nik_karyawan(0);
    // 1 = Karyawan Kontrak Alita
    $("#id_status_kerja").change(function() {
        var selectedValue = $(this).val();
        if (selectedValue == 1 || selectedValue == 9) {
            div_waktu_kontrak(1);
        }else{
            div_waktu_kontrak(0);}
    });
    // 1 = Head Office
    $("#id_area").change(function() {
        var selectedValue = $(this).val();
        if (selectedValue > 1) {
            div_kota(1);
        }else{
            div_kota(0);}
    });
    // 
    $("#id_jenjang_pendidikan").change(function() {
        var selectedValue = $(this).val();
        if (selectedValue > 3 && selectedValue < 11) {
            div_jurusan_pendidikan(1);
        }else{
            div_jurusan_pendidikan(0);}
    });
    // 1 = Penggantian
    $("#id_jenis_permintaan").change(function() {
        var selectedValue = $(this).val();
        if (selectedValue == 1) {
            div_nik_karyawan(1);
            $("#str_jenis_permintaan").html("penggantian");
        }else{
            div_nik_karyawan(0);
            $("#str_jenis_permintaan").html("penambahan");
        }
    });
    // 
    function div_nik_karyawan(cek) {
        // jika 1 maka aktif
        if (cek == 1) {
            $('#div_nik_karyawan').show();
            $("#nik_karyawan").prop('disabled', false);
        }
        // jika 0 maka non aktif
        else {
            $('#div_nik_karyawan').hide();
            $("#nik_karyawan").prop('disabled', true);
        }
    }
    // 
    function div_waktu_kontrak(cek) {
        // jika 1 maka aktif
        if (cek == 1) {
            $('#div_waktu_kontrak').show();
            $("#waktu_kontrak").prop('disabled', false);
        }
        // jika 0 maka non aktif
        else {
            $('#div_waktu_kontrak').hide();
            $("#waktu_kontrak").prop('disabled', true);
        }
    }
    // 
    function div_kota(cek) {
        // jika 1 maka aktif
        if (cek == 1) {
            $('#div_kota').show();
            $("#id_kota").prop('disabled', false);
            $('#id_kota').find('option').remove();
            $("#id_kota").append('<option selected="" disabled="" value="">Pilih...</option>');
            var id = $("#id_area").val();
            $.ajax({
                // url: _url + '/matta/kebutuhan/kota/' + id,
                url: "<?php echo e(route('kebutuhan_kota')); ?>" + "/" + id,
                type: 'get',
                dataType: 'json',
                success: function(response) {
                    // alert(response);
                    var len = 0;
                    if (response['kota'] != null) {
                        len = response['kota'].length;
                    }
                    if (len > 0) {
                        // Read data and create <option >
                        for (var i = 0; i < len; i++) {
                            var id = response['kota'][i].id;
                            var kota = response['kota'][i].kota;
                            var option = "<option value='" + id + "'>" + kota + "</option>";
                            $("#id_kota").append(option);
                        }
                    }
                }
            });
        }
        // jika 0 maka non aktif
        else {
            $('#div_kota').hide();
            $("#id_kota").prop('disabled', true);
        }
    }
    // 
    function div_jurusan_pendidikan(cek) {
        // jika 1 maka aktif
        if (cek == 1) {
            $('#div_jurusan_pendidikan').show();
            $("#id_jurusan_pendidikan").prop('disabled', false);
            $("#nilai_ipk").prop('disabled', false);
        }
        // jika 0 maka non aktif
        else {
            $('#div_jurusan_pendidikan').hide();
            $("#id_jurusan_pendidikan").prop('disabled', true);
            $("#nilai_ipk").prop('disabled', true);
        }
    }
    // 
    // Empty the dropdown
    $('#layer1').find('option').not(':first').remove();
    $('#layer2').find('option').not(':first').remove();
    // karyawan masih di hardcode
    // Andika Cahya Fajar. R
    // var id = '01031406353';
    // AJAX request 
    var _nik = document.getElementById("nik_pengaju").value;
    // alert(_nik);
    // layer1
    $.ajax({
        // url: _url + '/matta/kebutuhan/layer/' + id,
        // url: "<?php echo e(route('kebutuhan_layer')); ?>" + "/" + id,
        url: "<?php echo e(route('kebutuhan_layer')); ?>/"+_nik,
        type: 'get',
        dataType: 'json',
        success: function(response) {
            var layer1 = response['layer'].layer1;
            var len = 0;
            if (response['karyawan'] != null) {
                len = response['karyawan'].length;
            }
            if (len > 0) {
                // Read data and create <option >
                for (var i = 0; i < len; i++) {
                    var nik = response['karyawan'][i].nik;
                    var nama = response['karyawan'][i].nama;
                    if (layer1 == nik) {
                        var option = "<option value='" + nik + "' selected>" + nama + " - " + nik + "</option>";
                    } else {
                        var option = "<option value='" + nik + "'>" + nama + " - " + nik + "</option>";
                    }
                    $("#layer1").append(option);
                }
            }
        }
    });
    // layer2
    $.ajax({
        // url: _url + '/matta/kebutuhan/layer/' + id,
        // url: "<?php echo e(route('kebutuhan_layer')); ?>" + "/" + id,
        url: "<?php echo e(route('kebutuhan_layer')); ?>/"+_nik,
        type: 'get',
        dataType: 'json',
        success: function(response) {
            var layer2 = response['layer'].layer2;
            var len = 0;
            if (response['karyawan'] != null) {
                len = response['karyawan'].length;
            }
            if (len > 0) {
                // Read data and create <option >
                for (var i = 0; i < len; i++) {
                    var nik = response['karyawan'][i].nik;
                    var nama = response['karyawan'][i].nama;
                    if (layer2 == nik) {
                        var option = "<option value='" + nik + "' selected>" + nama + " - " + nik + "</option>";
                    } else {
                        var option = "<option value='" + nik + "'>" + nama + " - " + nik + "</option>";
                    }
                    $("#layer2").append(option);
                }
            }
        }
    });
    // 
});
// 
function cekIsian(){
    // alert($('#jumlah').val());
    // alert($('#nik_karyawan').val());
    if(!document.getElementById('nik_karyawan').disabled){
        var _nik_karyawan = [];
        $("#nik_karyawan :selected").each(function(){
            _nik_karyawan.push($(this).val());
        });
        document.getElementById("_nik_karyawan_").value = _nik_karyawan;
        if($('#jumlah').val() != _nik_karyawan.length){
            alert('Jumlah Karyawan Penggantian harus sesuai dengan isian Jumlah (Orang)');
            return false;
        }else{
            return confirm('Are you sure want to submit this data?');
        }
    }else{
        return confirm('Are you sure want to submit this data?');
    }
}
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/formulir/kebutuhan/insert.blade.php ENDPATH**/ ?>