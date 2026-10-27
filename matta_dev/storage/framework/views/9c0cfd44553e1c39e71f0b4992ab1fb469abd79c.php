<?php $__env->startSection('title', 'Formulir Reimbursement'); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/sweetalert2.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/select2.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/date-picker.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
<h3>Add</h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Formulir Reimbursement</li>
<li class="breadcrumb-item active">Add</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <!--  -->
        <div class="col-sm-12 col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h5>Pengaju: <?php echo e($pengaju); ?></h5>
                </div>
                
                <div class="modal fade" id="medicalModal" tabindex="-1">
                    <div class="modal-dialog modal-xl modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">
                                    Penggantian Biaya Kesehatan (Medical Reimbursement) dengan ketentuan sebagai berikut:
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <table>
                                    <tr valign="top">
                                        <td width="2%">1.</td>
                                        <td width="98%">Melampirkan copy resep, bukti pembelian/kuitansi dan bukti pembayaran, termasuk print out EDC (jika pembayaran melalui EDC), disertai diagnosa dokter.</td>
                                    </tr>
                                    <tr valign="top">
                                        <td>2.</td>
                                        <td>Bukti pembelian/kuitansi Dokter/Klinik/Rumah Sakit/Apotek asli dengan cap/stample baik Rawat Jalan maupun Rawat Inap dan dilengkapi meterai untuk kuitansi dengan nilai minimum Rp5.000.000,- (Lima Juta Rupiah).</td>
                                    </tr>
                                    <tr valign="top">
                                        <td>3.</td>
                                        <td>Biaya administrasi klinik/rumah sakit/aplikasi berobat online, ongkos kirim dan kantong belanja dapat diberikan jika tertera dalam satu transaksi/kuitansi pembelian yang sama dengan obat-obatan.</td>
                                    </tr>
                                    <tr valign="top">
                                        <td>4.</td>
                                        <td>Masa berlaku kuitansi Dokter/Klinik/Rumah Sakit/Apotek adalah maksimal 3(tiga) bulan dari tanggal yang tertera dalam kuitansi.</td>
                                    </tr>
                                    <tr valign="top">
                                        <td>5.</td>
                                        <td>Pemeriksaan Laboratorium, USG, MRI dan sejenisnya harus disertai dengan surat pengantar dari dokter dan keterangan diagnosa dari dokter.</td>
                                    </tr>
                                    <tr valign="top">
                                        <td>6.</td>
                                        <td>Tidak termasuk penggantian General Check-Up, perawatan estetika, pemeriksaan hormonal/kesuburan, obat-obatan ringan yang dijual bebas, pengobatan/obat-obatan herbal.</td>
                                    </tr>
                                    <tr valign="top">
                                        <td>7.</td>
                                        <td>Tidak termasuk program kehamilan, pemeriksaan kehamilan dan kelahiran.</td>
                                    </tr>
                                    <tr valign="top">
                                        <td>8.</td>
                                        <td>Tidak termasuk pembelian susu, obat-obatan herbal untuk program kehamilan, pemeriksaan kehamilan dan kelahiran.</td>
                                    </tr>
                                    <tr valign="top">
                                        <td>9.</td>
                                        <td>Tertanggung adalah termasuk keluarga inti bagi yang sudah menikah, antara lain:
                                            <br/>- istri dan anak bagi karyawan
                                            <br/>- anak (tidak termasuk suami) bagi karyawati</td>
                                    </tr>
                                    <tr valign="top">
                                        <td>10.</td>
                                        <td>Penggantian maksimum sebesar 1 (satu) bulan Upah per Tahun dan berlaku proporsional sesuai masa perjanjian kerja.</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card-header">
                    <h5>Plafon</h5>
                </div>
                <div class="card-body typography">
                    <?php if(Session::has('error')): ?>
                    <div class="alert alert-danger dark alert-dismissible fade show" role="alert">
                        <?php echo session('error'); ?>

                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php endif; ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-responsive">
                            <thead>
                                <tr>
                                    <th>
                                        <h6>Tahun</h6>
                                    </th>
                                    <th>
                                        <h6>Kesehatan</h6>
                                    </th>
                                    <th>
                                        <h6>Frame</h6>
                                    </th>
                                    <th>
                                        <h6>Lensa</h6>
                                    </th>
                                    <th>
                                        <h6>Kehamilan</h6>
                                    </th>
                                    <th>
                                        <h6>Persalinan Normal</h6>
                                    </th>
                                    <th>
                                        <h6>Persalinan Caesar</h6>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <?php if($plafon->periode == 0): ?>
                                    <td><?php echo e(Carbon\Carbon::now()->year); ?></td>
                                    <td>Rp. <?php echo e(number_format(0, 0)); ?></td>
                                    <?php else: ?>
                                    <td><?php echo e($plafon->periode); ?></td>
                                    <td>Rp. <?php echo e(number_format($plafon->kesehatan, 0)); ?></td>
                                    <?php endif; ?>
                                    <td>Rp. <?php echo e(number_format($plafon->frame, 0)); ?></td>
                                    <td>Rp. <?php echo e(number_format($plafon->lensa, 0)); ?></td>
                                    <td>Rp. <?php echo e(number_format($plafon->kehamilan, 0)); ?></td>
                                    <td>Rp. <?php echo e(number_format($plafon->persalinan_normal, 0)); ?></td>
                                    <td>Rp. <?php echo e(number_format($plafon->persalinan_caesar, 0)); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <form action="<?php echo e(route('reimburse.store')); ?>" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return confirm('Are you sure want to submit this data?');">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" id="nik_karyawan" name="nik_karyawan" value="<?php echo e($nik_karyawan); ?>">
                    <!--  -->
                    <div class="card-header">
                        <h5>Isian Reimbursement</h5>
                    </div>
                    <div class="card-body typography">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table" id="trx_reimburse_detail">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <h6>Hubungan Dengan Karyawan</h6>
                                                </th>
                                                <th>
                                                    <h6>Nama Pasien</h6>
                                                </th>
                                                <th>
                                                    <h6>Layanan</h6>
                                                </th>
                                                <th>
                                                    <h6>Tanggal Layanan</h6>
                                                </th>
                                                <th>
                                                    <h6>Nominal Klaim</h6>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr id="reimburse_detail0">
                                                <td>
                                                    <select class="form-select" id="hubungan[]" name="hubungan[]" required="">
                                                        <option selected="" disabled="" value="">Pilih...</option>
                                                        <?php $__currentLoopData = $hubungan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($data->id); ?>"><?php echo e($data->hubungan); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input name="nama_pasien[]" id="nama_pasien[]" class="form-control" type="text" required="">
                                                </td>
                                                <td>
                                                    <select class="form-select" id="layanan[]" name="layanan[]" required="">
                                                        <option selected="" disabled="" value="">Pilih...</option>
                                                        <?php $__currentLoopData = $layanan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($data->id); ?>"><?php echo e($data->layanan); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input class="form-control" name="tanggal[]" id="tanggal[]" type="date" required="" max="<?php echo e($max_date); ?>">
                                                </td>
                                                <td>
                                                    <input min=1 type="number" oninput="validity.valid||(value='');" onfocus="use_number(this)" onblur="use_text(this)" name="jumlah_klaim[]" id="jumlah_klaim[]" class="form-control numbers" required="">
                                                </td>
                                            </tr>
                                            <?php for($x = 1; $x < 10; $x++): ?>
                                            <tr id="reimburse_detail<?php echo e($x); ?>"></tr>
                                            <?php endfor; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <br />
                        <div class="row g-3">
                            <div class="col-md-12">
                                <button id="add_row" class="btn btn-primary pull-left"><i class="fa fa-plus"></i> Add Row</button>
                                <button id='delete_row' class="pull-right btn btn-danger"><i class="fa fa-minus"></i> Delete Row</button>
                            </div>
                        </div>
                        <br/>Catatan:<br/>
                        1. Jika kolom Hubungan Dengan Karyawan dipilih Karyawan maka kolom Nama Pasien akan otomatis terisi nama karyawan.
                        <br/>2. Jika kolom Layanan dipilih Kehamilan, Persalinan-Normal atau Persalinan-Caesar maka kolom Nominal Klaim akan otomatis terisi plafon.
                    </div>
                    <!-- <hr/> -->
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="card-header">
                                <h5>Isian Lampiran</h5>
                            </div>
                            <div class="card-body typography">
                                <div class="table-responsive">
                                    <table class="table" id="trx_reimburse_lampiran">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <h6>Lampiran Pengajuan</h6>
                                                </th>
                                                <th>
                                                    <h6>File Lampiran</h6>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr id="reimburse_lampiran0">
                                                <td>
                                                    <select class="form-select" id="lampiran[]" name="lampiran[]" required="">
                                                        <option selected="" disabled="" value="">Pilih...</option>
                                                        <?php $__currentLoopData = $lampiran; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($data->id); ?>"><?php echo e($data->lampiran); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input name="file_lampiran[]" id="file_lampiran[]" class="form-control" type="file" required="">
                                                </td>
                                            </tr>
                                            <tr id="reimburse_lampiran1"></tr>
                                        </tbody>
                                    </table>
                                </div>
                                <button id="add_row_lampiran" class="btn btn-primary pull-left"><i class="fa fa-plus"></i> Add Row</button>
                                <button id='delete_row_lampiran' class="pull-right btn btn-danger"><i class="fa fa-minus"></i> Delete Row</button>
                                <br /><br />
                                Note: Lampiran wajib diisi dan dilengkapi.
                            </div>
                        </div>
                        <!--  -->
                        <div class="col-md-6">
                            <div class="card-header">
                                <h5>Keterangan</h5>
                            </div>
                            <div class="card-body typography">
                                <textarea class="form-control" id="catatan" name="catatan" rows="4"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="card-body typography">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <button class="btn btn-success" type="submit">Submit</button> | <a href="<?php echo e(URL::previous()); ?>" class="btn btn-warning" type="button">Back</a>
                            </div>
                        </div>
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
<script src="<?php echo e(asset('assets/js/datepicker/date-picker/datepicker.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datepicker/date-picker/datepicker.en.js')); ?>"></script>
<!--  -->
<!-- source: https://blog.quickadminpanel.com/master-detail-form-in-laravel-jquery-create-order-with-products/ -->
<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function () {
    var myModal = new bootstrap.Modal(document.getElementById('medicalModal'));
    myModal.show();
});
function use_number(node) {
  var empty_val = false;
  const value = node.value;
//   if(value==0){
//     alert('Jumlah tidak boleh 0!');
//   }
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
// 
$(document).ready(function() {
    // 
    let row_number = 1;
    $("#add_row").click(function(e) {
        e.preventDefault();
        let new_row_number = row_number - 1;
        $('#reimburse_detail' + row_number).html($('#reimburse_detail' + new_row_number).html()).find('td:first-child');
        $('#trx_reimburse_detail').append('<tr id="reimburse_detail' + (row_number + 1) + '"></tr>');
        $('#reimburse_detail' + row_number + ' input[id="jumlah_klaim[]"]').attr('readonly', false);
        row_number++;
    });

    $("#delete_row").click(function(e) {
        e.preventDefault();
        if (row_number > 1) {
            $("#reimburse_detail" + (row_number - 1)).html('');
            row_number--;
        }
    });
    // 
    let row_number_lampiran = 1;
    $("#add_row_lampiran").click(function(e) {
        e.preventDefault();
        let new_row_number_lampiran = row_number_lampiran - 1;
        $('#reimburse_lampiran' + row_number_lampiran).html($('#reimburse_lampiran' + new_row_number_lampiran).html()).find('td:first-child');
        $('#trx_reimburse_lampiran').append('<tr id="reimburse_lampiran' + (row_number_lampiran + 1) + '"></tr>');
        row_number_lampiran++;
    });

    $("#delete_row_lampiran").click(function(e) {
        e.preventDefault();
        if (row_number_lampiran > 1) {
            $("#reimburse_lampiran" + (row_number_lampiran - 1)).html('');
            row_number_lampiran--;
        }
    });
    // $("select[id^=hubungan]").change(function() {
    // $('select[id="hubungan[]"]').change(function() {
    //     var selectedValue = $(this).val();
    //     // alert(selectedValue);
    //     if (selectedValue == 1) {
    //         // $("input[id^=nama_pasien]").val('<?php echo e($karyawan->nama); ?>');
    //         $('input[id="nama_pasien[]"]').first().val('<?php echo e($karyawan->nama); ?>');
    //     }
    // });
    <?php for($x = 0; $x < 10; $x++): ?>
    // check jika layanan dipilih
    $('#reimburse_detail<?php echo e($x); ?>').on('change', 'select[id="layanan[]"]', function () {
        var selectedValue = $(this).val();
        // alert('<?php echo e($x); ?>='+selectedValue);
        // 4=Kehamilan
        if (selectedValue == 4) {
            $('#reimburse_detail<?php echo e($x); ?> input[id="jumlah_klaim[]"]').val('<?php echo e($plafon->kehamilan); ?>');
            $('#reimburse_detail<?php echo e($x); ?> input[id="jumlah_klaim[]"]').attr('readonly', true);
        }
        // 5=Persalinan-Normal
        else if (selectedValue == 5) {
            $('#reimburse_detail<?php echo e($x); ?> input[id="jumlah_klaim[]"]').val('<?php echo e($plafon->persalinan_normal); ?>');
            $('#reimburse_detail<?php echo e($x); ?> input[id="jumlah_klaim[]"]').attr('readonly', true);
        }
        // 6=Persalinan-Caesar
        else if (selectedValue == 6) {
            $('#reimburse_detail<?php echo e($x); ?> input[id="jumlah_klaim[]"]').val('<?php echo e($plafon->persalinan_caesar); ?>');
            $('#reimburse_detail<?php echo e($x); ?> input[id="jumlah_klaim[]"]').attr('readonly', true);
        }
        // 
        else{
            $('#reimburse_detail<?php echo e($x); ?> input[id="jumlah_klaim[]"]').attr('readonly', false);
            $('#reimburse_detail<?php echo e($x); ?> input[id="jumlah_klaim[]"]').val('');
        }
    });
    // check jika hubungan dipilih
    $('#reimburse_detail<?php echo e($x); ?>').on('change', 'select[id="hubungan[]"]', function () {
        var selectedValue = $(this).val();
        // alert('<?php echo e($x); ?>='+selectedValue);
        // 1=Karyawan
        if (selectedValue == 1) {
            $('#reimburse_detail<?php echo e($x); ?> input[id="jumlah_klaim[]"]').attr('readonly', false);
            $('#reimburse_detail<?php echo e($x); ?> input[id="jumlah_klaim[]"]').val('');
            // 
            $('#reimburse_detail<?php echo e($x); ?> input[id="nama_pasien[]"]').val('<?php echo e($karyawan->nama); ?>');
            $('#reimburse_detail<?php echo e($x); ?> select[id="layanan[]"]').find('option').not(':first').remove();
            <?php $__currentLoopData = $layanan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            $('#reimburse_detail<?php echo e($x); ?> select[id="layanan[]"]').append('<option value="<?php echo e($data->id); ?>"><?php echo e($data->layanan); ?></option>');
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        }
        // 3=Istri dari Karyawan
        else if (selectedValue == 3) {
            $('#reimburse_detail<?php echo e($x); ?> input[id="jumlah_klaim[]"]').attr('readonly', false);
            $('#reimburse_detail<?php echo e($x); ?> input[id="jumlah_klaim[]"]').val('');
            // 
            $('#reimburse_detail<?php echo e($x); ?> input[id="nama_pasien[]"]').val('');
            $('#reimburse_detail<?php echo e($x); ?> select[id="layanan[]"]').find('option').not(':first').remove();
            <?php $__currentLoopData = $layanan_istri; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            $('#reimburse_detail<?php echo e($x); ?> select[id="layanan[]"]').append('<option value="<?php echo e($data->id); ?>"><?php echo e($data->layanan); ?></option>');
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        }
        // 4=Anak ke-1
        // 5=Anak ke-2
        // 6=Anak ke-3
        else if (selectedValue == 4 || selectedValue == 5 || selectedValue == 6) {
            $('#reimburse_detail<?php echo e($x); ?> input[id="jumlah_klaim[]"]').attr('readonly', false);
            $('#reimburse_detail<?php echo e($x); ?> input[id="jumlah_klaim[]"]').val('');
            // 
            $('#reimburse_detail<?php echo e($x); ?> input[id="nama_pasien[]"]').val('');
            $('#reimburse_detail<?php echo e($x); ?> select[id="layanan[]"]').find('option').not(':first').remove();
            <?php $__currentLoopData = $layanan_anak; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            $('#reimburse_detail<?php echo e($x); ?> select[id="layanan[]"]').append('<option value="<?php echo e($data->id); ?>"><?php echo e($data->layanan); ?></option>');
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        }
        // 
        else{
            // 
        }
    });
    <?php endfor; ?>
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/formulir/reimburse/insert.blade.php ENDPATH**/ ?>