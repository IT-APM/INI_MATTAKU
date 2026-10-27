<?php $__env->startSection('title', 'Formulir Penilaian Karyawan'); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/sweetalert2.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/select2.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
<h3>Update</h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Penilaian Karyawan</li>
<li class="breadcrumb-item active">Update</li>
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
                                        <td><?php echo e($_karyawan['divisi']); ?> / <?php echo e($_karyawan['dep']); ?> / <?php echo e($_karyawan['sub_dep']); ?></td>
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
                                        <td><?php if(!empty($_karyawan['tanggal_bergabung'])): ?> <?php echo e(Carbon\Carbon::parse($_karyawan['tanggal_bergabung'])->format('d-m-Y')); ?> <?php endif; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Tanggal Berakhir</td>
                                        <td><?php if(!empty($_karyawan['tanggal_berakhir'])): ?> <?php echo e(Carbon\Carbon::parse($_karyawan['tanggal_berakhir'])->format('d-m-Y')); ?> <?php endif; ?></td>
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
                                        <td width="50%"><div align="center"><strong>Penilai 1</strong></div></td>
                                    </tr>
                                    <?php $__currentLoopData = $angka_skor; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($data->id_nilai3); ?>. <?php echo e($data->penilaian_1); ?></td>
                                        <td><div align="center"><?php echo e($data->skor_penilai_1); ?></div></td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($angka_rata>0): ?>
                                    <tr>
                                        <td><div align="right"><strong>Total Score</strong></div></td>
                                        <td><div align="center"><strong><?php echo e($angka_rata); ?></div></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <?php if(count($angka_skor)==14): ?>
                                        <td><div align="center"><strong>Result: <?php if($angka_rata>35): ?> Extend <?php else: ?> Not Extend <?php endif; ?></strong></div></td>
                                        <?php else: ?>
                                        <td><div align="center"><strong>Result: <?php if($angka_rata>40): ?> Extend <?php else: ?> Not Extend <?php endif; ?></strong></div></td>
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
                                        <td width="15%"><div align="center"><strong>Karyawan</strong></div></td>
                                        <td width="15%"><div align="center"><strong>Penilai 1</strong></div></td>
                                    </tr>
                                    <?php echo $angka_skor; ?>

                                    <?php echo $angka_total; ?>

                                    <?php echo $angka_rata; ?>

                                </tbody>
                            </table>
                            <?php endif; ?>
                            <br/>
                            <?php if(!empty($_karyawan['tanggal_berakhir'])): ?><b>Tanggal Berakhir: <?php echo e(Carbon\Carbon::parse($_karyawan['tanggal_berakhir'])->format('d-m-Y')); ?></b><hr/><?php endif; ?>
                            <?php echo $isian_kes_rek; ?>

                        </div>
                        
            </div>
        </div>
        <div class="col-sm-8">
            <div class="card">
                <div class="card-header">
                    <h5>Para Pihak</h5>
                </div>
                <div class="card-body">
                    <form action="<?php echo e(route('penilaian.update', (new App\Http\Controllers\OpenDataController)->encryptWithKey($penilaian->id))); ?>" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                        <?php echo method_field('PATCH'); ?>
                        <?php echo csrf_field(); ?>
                        <div class="row g-3">
                            <div class="col-md-12">
                                <div class="card-body pt-0">
                                    <div class="appointment-table table-responsive">
                                        <table class="table table-bordernone">
                                            <tbody>
                                                <!-- _karyawan -->
                                                <input type="hidden" id="nik_karyawan" name="nik_karyawan" value="<?php echo e($_karyawan['nik']); ?>">
                                                <tr>
                                                    <td><a href="<?php echo e(asset('assets/images/matta/karyawan/'.$_karyawan['nik'].'/'.$_karyawan['nik'].'.jpg')); ?>"
                                                            target="_blank"><img class="img-thumbnail"
                                                                src="<?php echo e(asset('assets/images/matta/karyawan/'.$_karyawan['nik'].'/'.$_karyawan['nik'].'.jpg')); ?>"
                                                                alt="user"></a></td>
                                                    <td class="img-content-box">
                                                        <h6 class="m-10">Karyawan</h6><span
                                                            class="f-light m-10"><?php echo e($_karyawan['nama']); ?><br /><?php echo e($_karyawan['jabatan']); ?>

                                                            <br /><?php echo e($_karyawan['divisi']); ?> / <?php echo e($_karyawan['dep']); ?> / <?php echo e($_karyawan['sub_dep']); ?></span>
                                                    </td>
                                                </tr>
                                                <!-- penilai1 -->
                                                <tr>
                                                    <td><a href="<?php echo e(asset('assets/images/matta/karyawan/'.$penilai1['nik'].'/'.$penilai1['nik'].'.jpg')); ?>"
                                                            target="_blank"><img class="img-thumbnail"
                                                                src="<?php echo e(asset('assets/images/matta/karyawan/'.$penilai1['nik'].'/'.$penilai1['nik'].'.jpg')); ?>"
                                                                alt="user"></a></td>
                                                    <td class="img-content-box">
                                                        <h6 class="m-10">Penilai 1</h6><span
                                                            class="f-light m-10"><?php echo e($penilai1['nama']); ?><br /><?php echo e($penilai1['jabatan']); ?>

                                                            <br /><?php echo e($penilai1['divisi']); ?> / <?php echo e($penilai1['dep']); ?> / <?php echo e($penilai1['sub_dep']); ?></span>
                                                    </td>
                                                    <input type="hidden" id="nik_atasan" name="nik_atasan" value="<?php echo e($penilai1['nik']); ?>">
                                                </tr>
                                                <!-- penilai2 -->
                                                <?php if(!empty($penilaian->nik_penilai_2)): ?>
                                                <tr>
                                                    <td><a href="<?php echo e(asset('assets/images/matta/karyawan/'.$penilai2['nik'].'/'.$penilai2['nik'].'.jpg')); ?>"
                                                            target="_blank"><img class="img-thumbnail"
                                                                src="<?php echo e(asset('assets/images/matta/karyawan/'.$penilai2['nik'].'/'.$penilai2['nik'].'.jpg')); ?>"
                                                                alt="user"></a></td>
                                                    <td class="img-content-box">
                                                        <h6 class="m-10">Penilai 2</h6><span
                                                            class="f-light m-10"><?php echo e($penilai2['nama']); ?><br /><?php echo e($penilai2['jabatan']); ?>

                                                            <br /><?php echo e($penilai2['divisi']); ?> / <?php echo e($penilai2['dep']); ?> / <?php echo e($penilai2['sub_dep']); ?></span>
                                                    </td>
                                                </tr>
                                                <?php endif; ?>
                                                <!-- penilai3 -->
                                                <?php if(!empty($penilaian->nik_penilai_3)): ?>
                                                <tr>
                                                    <td><a href="<?php echo e(asset('assets/images/matta/karyawan/'.$penilai3['nik'].'/'.$penilai3['nik'].'.jpg')); ?>"
                                                            target="_blank"><img class="img-thumbnail"
                                                                src="<?php echo e(asset('assets/images/matta/karyawan/'.$penilai3['nik'].'/'.$penilai3['nik'].'.jpg')); ?>"
                                                                alt="user"></a></td>
                                                    <td class="img-content-box">
                                                        <h6 class="m-10">Penilai 3</h6><span
                                                            class="f-light m-10"><?php echo e($penilai3['nama']); ?><br /><?php echo e($penilai3['jabatan']); ?>

                                                            <br /><?php echo e($penilai3['divisi']); ?> / <?php echo e($penilai3['dep']); ?> / <?php echo e($penilai3['sub_dep']); ?></span>
                                                    </td>
                                                </tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Karyawan</label>
                                <h6><?php echo e($penilaian->_karyawan->nama); ?> - <?php echo e($penilaian->nik_karyawan); ?></h6>
                                <input type="hidden" id="nik_karyawan" name="nik_karyawan" value="<?php echo e($penilaian->nik_karyawan); ?>">
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Penilai 1</label>
                                <h6><?php echo e($penilaian->_penilai1->nama); ?> - <?php echo e($penilaian->_penilai1->nik); ?></h6>
                            </div>
                        </div>
                        <?php if(!empty($penilaian->nik_penilai_2)): ?>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Penilai 2</label>
                                <h6><?php echo e($penilaian->_penilai2->nama); ?> - <?php echo e($penilaian->_penilai2->nik); ?></h6>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php if(!empty($penilaian->nik_penilai_3)): ?>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Penilai 3</label>
                                <h6><?php echo e($penilaian->_penilai3->nama); ?> - <?php echo e($penilaian->_penilai3->nik); ?></h6>
                            </div>
                        </div>
                        <?php endif; ?> -->
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label" for="id_tipe">Tipe Penilaian</label>
                                <select class="form-select" id="id_tipe" name="id_tipe" required="">
                                    <option selected="" disabled="" value="">Choose...</option>
                                    <?php $__currentLoopData = $tipe; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($data->id); ?>" <?php if($data->id == $penilaian->id_tipe): ?> selected <?php endif; ?> ><?php echo e($data->tipe_penilaian); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <br />
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label" for="id_status">Status Penilaian</label>
                                <select class="form-select" id="id_status" name="id_status" required="">
                                    <option selected="" disabled="" value="">Choose...</option>
                                    <?php $__currentLoopData = $status; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($data->id); ?>" <?php if($data->id == $penilaian->id_status): ?> selected <?php endif; ?> ><?php echo e($data->status_penilaian); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <br />
                        <div class="row g-3">
                            <div class="col-md-4" id="div_penilai_2">
                                <label class="form-label" for="penilai_2">Penilai 2</label>
                                <select class="form-select js-example-basic-single col-sm-12" id="penilai_2" name="penilai_2" required="">
                                    <option selected="" disabled="" value="">Choose...</option>
                                    <!-- <?php $__currentLoopData = $karyawan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($data->nik); ?>" <?php if($status == 'edit'): ?> <?php if($data->nik == $penilaian->nik_penilai_2): ?> selected <?php endif; ?> <?php endif; ?> ><?php echo e($data->nama); ?> - <?php echo e($data->nik); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> -->
                                </select>
                            </div>
                            <div class="col-md-4" id="div_penilai_3">
                                <label class="form-label" for="penilai_3">Penilai 3</label>
                                <select class="form-select js-example-basic-single col-sm-12" id="penilai_3" name="penilai_3" required="">
                                    <option selected="" disabled="" value="">Choose...</option>
                                    <!-- <?php $__currentLoopData = $karyawan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($data->nik); ?>" <?php if($status == 'edit'): ?> <?php if($data->nik == $penilaian->nik_penilai_3): ?> selected <?php endif; ?> <?php endif; ?> ><?php echo e($data->nama); ?> - <?php echo e($data->nik); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> -->
                                </select>
                            </div>
                        </div>
                        <br />
                        <div class="row g-3">
                            <div class="col-md-4" id="div_is_perpanjang">
                                <label class="form-label" for="is_perpanjang">Status Perpanjangan</label>
                                <select class="form-select" id="is_perpanjang" name="is_perpanjang" required="">
                                    <option selected="" disabled="" value="">Choose...</option>
                                    <?php $__currentLoopData = $perpanjang; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($data->id); ?>" <?php if($data->id == $penilaian->is_perpanjang): ?> selected <?php endif; ?> ><?php echo e($data->informasi); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <br />
                        <div class="row g-3">
                            <div class="col-md-4" id="div_bulan">
                                <label class="form-label" for="bulan">&nbsp;</label>
                                <input id="bulan" name="bulan" required="" class="form-control numbers" type="number" min="1" oninput="validity.valid||(value='');" placeholder="Jumlah Bulan">
                            </div>
                            <div class="col-md-8" id="div_kes_rek_final">
                                <label class="form-label" for="kes_rek_final">Kesimpulan dan Rekomendasi <?php echo e(App\Models\MtrUtility::find(51)->informasi); ?></label>
                                <textarea class="form-control" name="kes_rek_final" id="kes_rek_final" rows="3"></textarea>
                            </div>
                        </div>
                        <br />
                        <button class="btn btn-success" type="submit">Update</button> | <a href="<?php echo e(route('penilaian.index')); ?>" class="btn btn-warning" type="button">Back</a>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-sm-4 notification">
            <div class="card">
                <div class="card-header card-no-border">
                    <h5>History</h5>
                </div>
                <div class="card-body pt-0">
                    <ul>
                        <?php
                            // tipe
                            // 1=Penilaian 180
                            // 4=Penilaian 180-Reguler
                            // 5=Penilaian 180-Assesment
                            // 2=Penilaian 360-Reguler
                            // 3=Penilaian 360-Assesment
                            // 6=Penilaian Outsourcing
                            // status
                            // 1=Send to Employee
                            // 2=Done by Employee
                            // 3=Done By Reviewer 1
                            // 4=Done by P&C (180)
                            // 6=Done By Reviewer 2
                            // 7=Done By Reviewer 3
                            // 8=Done by P&C (360)
                            // untuk tipe=[1,4,5] maka status=[1,2,3]
                            $disable=[1,2,3];
                            // untuk tipe=[2,3] maka status=[1,2,3,6,7]
                            if(in_array($penilaian->id_tipe, [2,3])){
                                $disable=[1,2,3,6,7];
                                if(empty($penilaian->nik_penilai_3)){
                                    $disable=[1,2,3,6];
                                }
                            }
                            // untuk tipe=[6] maka status=[3,6,7]
                            elseif(in_array($penilaian->id_tipe, [6])){
                                $disable=[3,6,7];
                                if(empty($penilaian->nik_penilai_3)){
                                    $disable=[3,6];
                                }
                            }
                            $remains = array_diff($disable, $history->pluck('id_status')->toArray());
                            rsort($remains);
                            // $nik_hco = App\Models\MtrUtility::find(37)->informasi;
                        ?>
                        <?php $__currentLoopData = $remains; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $remain): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li class="d-flex">
                            <div class="activity-dot-dark"></div>
                            <div class="ms-3">
                                <h6 class="f-light" style="color: #adb5bd;"><?php echo e(App\Models\MtrPenilaianStatus::find($remain)->status_penilaian); ?></h6>
                                <?php if($remain == 7): ?>
                                <p class="f-light" style="color: #adb5bd;">Oleh <?php echo e(App\Models\Karyawan::where('nik',$penilaian->nik_penilai_3)->first()->nama); ?> / <?php echo e($penilaian->nik_penilai_3); ?></p>
                                <?php elseif($remain == 6): ?>
                                <p class="f-light" style="color: #adb5bd;">Oleh <?php echo e(App\Models\Karyawan::where('nik',$penilaian->nik_penilai_2)->first()->nama); ?> / <?php echo e($penilaian->nik_penilai_2); ?></p>
                                <?php elseif($remain == 3): ?>
                                <p class="f-light" style="color: #adb5bd;">Oleh <?php echo e(App\Models\Karyawan::where('nik',$penilaian->nik_penilai_1)->first()->nama); ?> / <?php echo e($penilaian->nik_penilai_1); ?></p>
                                <?php elseif($remain == 2): ?>
                                <p class="f-light" style="color: #adb5bd;">Oleh <?php echo e(App\Models\Karyawan::where('nik',$penilaian->nik_karyawan)->first()->nama); ?> / <?php echo e($penilaian->nik_karyawan); ?></p>
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
                                <h6><?php echo e($_history->_status->status_penilaian); ?><span class="dot-notification"></span></h6>
                                <p class="f-light">Oleh <?php echo e($_history->_pic->nama); ?> / <?php echo e($_history->nik_pic); ?></p>
                            </div>
                        </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            </div>
        </div>
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
        // var _url = window.location.origin;
        $("#penilai_2").prop('disabled', true);
        $("#penilai_3").prop('disabled', true);
        $("#is_perpanjang").prop('disabled', true);
        $("#kes_rek_final").prop('disabled', true);
        $("#bulan").prop('disabled', true);
        $('#div_penilai_2').hide();
        $('#div_penilai_3').hide();
        $('#div_is_perpanjang').hide();
        $('#div_kes_rek_final').hide();
        $('#div_bulan').hide();
        var valueTipe = $("#id_tipe").val();
        $("#id_tipe").change(function() {
            valueTipe = $("#id_tipe").val();
        });
        $("#id_status").change(function() {
            // var textStatus = $(this).find("option:selected").text();
            var valueStatus = $(this).val();
            var id = $('#nik_karyawan').val();
            // alert("valueTipe: " + valueTipe + " valueStatus: " + valueStatus);
            // Next to Reviewer 2 &/ 3
            if ((valueTipe == 2 || valueTipe == 3) && valueStatus == 5) {
                $("#penilai_2").prop('disabled', false);
                $("#penilai_3").prop('disabled', false);
                $("#is_perpanjang").prop('disabled', true);
                $("#kes_rek_final").prop('disabled', true);
                $("#bulan").prop('disabled', true);
                $('#div_penilai_2').show();
                $('#div_penilai_3').show();
                $('#div_is_perpanjang').hide();
                $('#div_kes_rek_final').hide();
                $('#div_bulan').hide();
                // penilai 2
                $('#penilai_2').find('option').not(':first').remove();
                $.ajax({
                    // url: _url + '/matta/penilaian/layer/' + id,
                    url: "<?php echo e(route('penilaian_layer')); ?>" + "/" + id,
                    type: 'get',
                    dataType: 'json',
                    success: function(response) {
                        var layer = response['layer'].layer2;
                        var len = 0;
                        if (response['karyawan'] != null) {
                            len = response['karyawan'].length;
                        }
                        if (len > 0) {
                            // Read data and create <option >
                            for (var i = 0; i < len; i++) {
                                var nik = response['karyawan'][i].nik;
                                var nama = response['karyawan'][i].nama;
                                if (layer == nik) {
                                    var option = "<option value='" + nik + "' selected>" + nama + " - " + nik + "</option>";
                                } else {
                                    var option = "<option value='" + nik + "'>" + nama + " - " + nik + "</option>";
                                }
                                $("#penilai_2").append(option);
                            }
                        }
                    }
                });
                // penilai 3
                $('#penilai_3').find('option').not(':first').remove();
                $.ajax({
                    // url: _url + '/matta/penilaian/layer/' + id,
                    url: "<?php echo e(route('penilaian_layer')); ?>" + "/" + id,
                    type: 'get',
                    dataType: 'json',
                    success: function(response) {
                        var layer = response['layer'].layer3;
                        var len = 0;
                        if (response['karyawan'] != null) {
                            len = response['karyawan'].length;
                        }
                        if (len > 0) {
                            // Read data and create <option >
                            for (var i = 0; i < len; i++) {
                                var nik = response['karyawan'][i].nik;
                                var nama = response['karyawan'][i].nama;
                                if (layer == nik) {
                                    var option = "<option value='" + nik + "' selected>" + nama + " - " + nik + "</option>";
                                } else {
                                    var option = "<option value='" + nik + "'>" + nama + " - " + nik + "</option>";
                                }
                                $("#penilai_3").append(option);
                            }
                        }
                    }
                });
            }
            // Next to Reviewer 2
            else if ((valueTipe == 2 || valueTipe == 3) && valueStatus == 14) {
                $("#penilai_2").prop('disabled', false);
                $("#penilai_3").prop('disabled', true);
                $("#is_perpanjang").prop('disabled', true);
                $("#kes_rek_final").prop('disabled', true);
                $("#bulan").prop('disabled', true);
                $('#div_penilai_2').show();
                $('#div_penilai_3').hide();
                $('#div_is_perpanjang').hide();
                $('#div_kes_rek_final').hide();
                $('#div_bulan').hide();
                // penilai 2
                $('#penilai_2').find('option').not(':first').remove();
                $.ajax({
                    // url: _url + '/matta/penilaian/layer/' + id,
                    url: "<?php echo e(route('penilaian_layer')); ?>" + "/" + id,
                    type: 'get',
                    dataType: 'json',
                    success: function(response) {
                        var layer = response['layer'].layer2;
                        var len = 0;
                        if (response['karyawan'] != null) {
                            len = response['karyawan'].length;
                        }
                        if (len > 0) {
                            // Read data and create <option >
                            for (var i = 0; i < len; i++) {
                                var nik = response['karyawan'][i].nik;
                                var nama = response['karyawan'][i].nama;
                                if (layer == nik) {
                                    var option = "<option value='" + nik + "' selected>" + nama + " - " + nik + "</option>";
                                } else {
                                    var option = "<option value='" + nik + "'>" + nama + " - " + nik + "</option>";
                                }
                                $("#penilai_2").append(option);
                            }
                        }
                    }
                });
            }
            // Next to Reviewer 3
            else if ((valueTipe == 2 || valueTipe == 3 || valueTipe == 6) && valueStatus == 15) {
                $("#penilai_2").prop('disabled', true);
                $("#penilai_3").prop('disabled', false);
                $("#is_perpanjang").prop('disabled', true);
                $("#kes_rek_final").prop('disabled', true);
                $("#bulan").prop('disabled', true);
                $('#div_penilai_2').hide();
                $('#div_penilai_3').show();
                $('#div_is_perpanjang').hide();
                $('#div_kes_rek_final').hide();
                $('#div_bulan').hide();
                // penilai 3
                $('#penilai_3').find('option').not(':first').remove();
                $.ajax({
                    // url: _url + '/matta/penilaian/layer/' + id,
                    url: "<?php echo e(route('penilaian_layer')); ?>" + "/" + id,
                    type: 'get',
                    dataType: 'json',
                    success: function(response) {
                        var layer = response['layer'].layer3;
                        var len = 0;
                        if (response['karyawan'] != null) {
                            len = response['karyawan'].length;
                        }
                        if (len > 0) {
                            // Read data and create <option >
                            for (var i = 0; i < len; i++) {
                                var nik = response['karyawan'][i].nik;
                                var nama = response['karyawan'][i].nama;
                                if (layer == nik) {
                                    var option = "<option value='" + nik + "' selected>" + nama + " - " + nik + "</option>";
                                } else {
                                    var option = "<option value='" + nik + "'>" + nama + " - " + nik + "</option>";
                                }
                                $("#penilai_3").append(option);
                            }
                        }
                    }
                });
            }
            // Done by HC (180) || Done by HC (360) || Done By GM HC
            else if (valueStatus == 4 || valueStatus == 8 || valueStatus == 13) {
                $("#penilai_2").prop('disabled', true);
                $("#penilai_3").prop('disabled', true);
                $("#is_perpanjang").prop('disabled', false);
                $("#kes_rek_final").prop('disabled', false);
                $('#div_penilai_2').hide();
                $('#div_penilai_3').hide();
                $('#div_is_perpanjang').show();
                $('#div_kes_rek_final').show();
            }
            // else
            else {
                $("#penilai_2").prop('disabled', true);
                $("#penilai_3").prop('disabled', true);
                $("#is_perpanjang").prop('disabled', true);
                $("#kes_rek_final").prop('disabled', true);
                $("#bulan").prop('disabled', true);
                $('#div_penilai_2').hide();
                $('#div_penilai_3').hide();
                $('#div_is_perpanjang').hide();
                $('#div_kes_rek_final').hide();
                $('#div_bulan').hide();
            }
        });
    });
    $("#is_perpanjang").change(function() {
        // var textStatus = $(this).find("option:selected").text();
        var valueStatus = $(this).val();
        if (valueStatus == 1) {
            $("#bulan").prop('disabled', false);
            $('#div_bulan').show();
        } else {
            $("#bulan").prop('disabled', true);
            $('#div_bulan').hide();
        }
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/formulir/penilaian/update.blade.php ENDPATH**/ ?>