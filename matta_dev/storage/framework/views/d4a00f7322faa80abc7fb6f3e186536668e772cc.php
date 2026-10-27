
<?php $__env->startSection('title', 'Basic DataTables'); ?>

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
/* css untuk table izin */
    .tbl-laporan {
        border-collapse: collapse;
        margin: 5px;
        width: 100%;
    }
    .tbl-laporan th,
    .tbl-laporan td {
        border: 1px solid #dee2e6;
        padding: 6px 10px;
        text-align: center;
        vertical-align: middle;
    }
    .tbl-laporan thead th {
        background-color: #f0f7f3;
        color: #0B3D2E;
        font-weight: 600;
    }
    .tbl-laporan tbody tr:hover {
        background-color: #f0faf4;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
<h3>Pengingat Absensi & Surat Peringatan</h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Data</li>
<li class="breadcrumb-item active">Pengingat Absensi & Surat Peringatan</li>
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
                    <form action="<?php echo e(route('reminder_sp.store')); ?>" method="post" enctype="multipart/form-data"
                        onsubmit="return confirm('Are you sure want to do this?');">
                        <?php echo csrf_field(); ?>
                        <?php if(count($trx) > 0): ?>
                        <button class="btn btn-success" type="submit" name="submit" id="submit" value="execute"><i class="fa fa-plus-circle" aria-hidden="true"></i> Execute</button>
                        | <button class="btn btn-danger" type="submit" name="submit" id="submit" value="delete"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</button> |
                        <?php endif; ?>
                        <a href="<?php echo e(route('reminder_sp.show', 0)); ?>" class="btn btn-primary"><i class="fa fa-history"></i> History</a>
                        <div class="mt-2 table-responsive">
                            <table class="display nowrap" id="_datatable">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" onClick="toggle_status_check(this)" /></th>
                                        <th>No</th>
                                        <th>Ke</th>
                                        <th>Tanggal Awal</th>
                                        <th>Tanggal Akhir</th>
                                        <th>Working Group</th>
                                        <th>NIP</th>
                                        <th>Nama Karyawan</th>
                                        <th>Level</th>
                                        <th>Perusahaan</th>
                                        <th>Direktorat</th>
                                        <th>Divisi</th>
                                        <th>Departemen</th>
                                        <th>Email Karyawan</th>
                                        <th>Nama Layer 1</th>
                                        <th>Email Layer 1</th>
                                        <th>Jumlah Hari Kerja</th>
                                        <th>Jumlah Kehadiran</th>
                                        <th>Persentase Kehadiran</th>
                                        <th>Keterlambatan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 0; ?>
                                    <?php $__currentLoopData = $trx; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $_data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                    $nik_property = json_decode($_data->nik_property, true);
                                    $where = ['nik' => $nik_property['layer1']];
                                    $layer1 = App\Models\Karyawan::where($where)->first();
                                    // check history
                                    $teguran_ke = 'Pertama';
                                    $type = 'Pengingat Absensi';
                                    $_type = 'reminder';
                                    $history_email = '';
                                    // check nik yang sama pada 1 bulan sebelumnya
                                    $where = ['nik' => $_data->nik, 'date_from' => Carbon\Carbon::parse($_data->date_from)->modify('-1 month')->format('Y-m-d')];
                                    $trxSend = App\Models\TrxReminderSpSend::where($where)->whereNull('terlambat')->orderBy( 'date_from', 'desc' )->first();
                                    if(!empty($trxSend)){
                                        $history_email = $trxSend->body_email;
                                        if($trxSend->type == 'reminder'){
                                            if($trxSend->number == 1){
                                                $teguran_ke = 'Kedua';
                                            }elseif($trxSend->number == 2){
                                                $teguran_ke = 'Ketiga';
                                            }elseif($trxSend->number == 3){
                                                $teguran_ke = 'Keempat';
                                            }elseif($trxSend->number == 4){
                                                $teguran_ke = 'Kelima';
                                            }elseif($trxSend->number == 5){
                                                $teguran_ke = 'Pertama';
                                                $type = 'Surat Peringatan';
                                                $_type = 'warning';
                                            }
                                        }elseif($trxSend->type == 'warning'){
                                            $type = 'Surat Peringatan';
                                            $_type = 'warning';
                                            if($trxSend->number == 1){
                                                $teguran_ke = 'Kedua';
                                            }elseif($trxSend->number == 2){
                                                $teguran_ke = 'Ketiga';
                                            }
                                        }
                                    }
                                    // terlambat
                                    if(!empty($_data->terlambat)){
                                        // check history
                                        $type = 'Pengingat Keterlambatan';
                                        $_type = 'late';
                                        $teguran_ke = 'Pertama';
                                        $history_email = '';
                                        // check nik yang sama pada 1 bulan sebelumnya
                                        $where = ['nik' => $_data->nik, 'date_from' => Carbon\Carbon::parse($_data->date_from)->modify('-1 month')->format('Y-m-d')];
                                        $trxSend = App\Models\TrxReminderSpSend::where($where)->whereNotNull('terlambat')->orderBy( 'date_from', 'desc' )->first();
                                        if(!empty($trxSend)){
                                            $history_email = $trxSend->body_email;
                                            if($trxSend->type == 'late'){
                                                $arr = ['Pertama', 'Kedua', 'Ketiga', 'Keempat', 'Kelima', 'Keenam', 'Ketujuh', 'Kedelapan', 'Kesembilan', 'Kesepuluh', 'Kesebelas', 'Keduabelas'];
                                                $teguran_ke = $arr[$trxSend->number];
                                            }
                                        }
                                    }
                                    $mailData['title'] = $type.' '.$teguran_ke;
                                    $mailData['filename'] = 'emails.reminder1';
                                    if($_type == 'warning'){
                                        $mailData['filename'] = 'emails.warning1';
                                    }elseif($_type == 'late'){
                                        $mailData['filename'] = 'emails.late1';
                                    }
                                    $mailData['to'] = $nik_property['email'];
                                    $data = ['trx' => $_data, 'teguran_ke' => $teguran_ke, 'history_email' => $history_email, 'datetime' => Carbon\Carbon::now()];
                                    // $mailData['data'] = $_data;
                                    $body = view($mailData['filename'], compact('data'));
                                    $ccEmails = explode(',', App\Models\MtrUtility::find(11)->informasi);
                                    $where = ['nik' => $nik_property['layer1']];
                                    // cc email ke layer1
                                    $layer1 = App\Models\Karyawan::where($where)->first();
                                    array_push($ccEmails, $layer1->email);
                                    if($_type == 'reminder' && $_type == 'late'){
                                        if(in_array($number, [4, 5, 6, 7, 8, 9, 10, 11, 12])){
                                            // cc email ke layer2
                                            $where = ['nik' => $nik_property['layer2']];
                                            $layer2 = App\Models\Karyawan::where($where)->first();
                                            array_push($ccEmails, $layer2->email);
                                            if(in_array($number, [5, 6, 7, 8, 9, 10, 11, 12])){
                                                // cc email head of cs
                                                array_push($ccEmails, App\Models\MtrReminderSp::find(3)->isi);
                                            }
                                        }
                                    }elseif($_type == 'warning'){
                                        // cc email ke layer2
                                        $where = ['nik' => $nik_property['layer2']];
                                        $layer2 = App\Models\Karyawan::where($where)->first();
                                        array_push($ccEmails, $layer2->email);
                                        // cc email head of cs
                                        array_push($ccEmails, App\Models\MtrReminderSp::find(3)->isi);
                                    }
                                    ?>
                                    <tr <?php if(App\Models\Karyawan::where('nik', $_data->nik)->where('is_aktif', false)->exists()): ?> class="bg-danger" <?php endif; ?>>
                                        <td><input id="reminder_sp[]" name="reminder_sp[]" type="checkbox" value="<?php echo e($_data->id); ?>"></td>
                                        <td><?php echo e(++$no); ?></td>
                                        <td>
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#modalDetail" class="text-primary" onclick="preview_email(this)" data-subject="<?php echo e($mailData['title']); ?>" data-to="<?php echo e($nik_property['email']); ?>" data-cc="<?php echo e(implode(',', $ccEmails)); ?>" data-body="<?php echo e(base64_encode($body)); ?>"><?php echo e($type); ?> <?php echo e($teguran_ke); ?></a>
                                        </td>
                                        <td><?php echo e(Carbon\Carbon::parse($_data->date_from)->format('d-m-Y')); ?></td>
                                        <td><?php echo e(Carbon\Carbon::parse($_data->date_to)->format('d-m-Y')); ?></td>
                                        <td><?php echo e($nik_property['wg_name']); ?></td>
                                        <td><?php echo e($_data->nik); ?></td>
                                        <td><?php echo e($nik_property['nama']); ?></td>
                                        <td><?php echo e($nik_property['level']); ?></td>
                                        <td><?php echo e($nik_property['perusahaan']); ?></td>
                                        <td><?php echo e($nik_property['divisi']); ?></td>
                                        <td><?php echo e($nik_property['departemen']); ?></td>
                                        <td><?php echo e($nik_property['sub_departemen']); ?></td>
                                        <td><?php echo e($nik_property['email']); ?></td>
                                        <td><?php echo e($layer1->nama); ?></td>
                                        <td><?php echo e($layer1->email); ?></td>
                                        <td><?php echo e($_data->arr_base); ?></td>
                                        <td><?php echo e($_data->arr_clock); ?></td>
                                        <td><?php echo e(round($_data->persentase)); ?> %</td>
                                        <td>
                                            <?php
                                            $str_jam_kerja = 'Toleransi Jam kerja';
                                            if($nik_property['wg_id'] > 1){
                                                $str_jam_kerja = 'Jam kerja';
                                            }
                                            if(!empty($_data->terlambat)){
                                                $html = '<table class="tbl-laporan text-dark">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Hari</th>
                                                        <th>Tanggal</th>
                                                        <th>'.$str_jam_kerja.'</th>
                                                        <th>Clock-in</th>
                                                        <th>Keterlambatan</th>
                                                        <th>Izin</th>
                                                    </tr>
                                                </thead>
                                                <tbody>';
                                                $terlambat = json_decode($_data->terlambat, true);
                                                $hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                                                $_no = 0;
                                                foreach ($terlambat as $_terlambat){
                                                    $str_izin = '';
                                                    if(!empty($_terlambat['izin'])){
                                                        $izin = $_terlambat['izin'][0];
                                                        $str_izin .= App\Models\IjinTipe::find($izin['tipe'])->tipe;
                                                        if($izin['tipe'] == 2){
                                                            $str_izin .= ' Jam '.$izin['jam_awal'].' s/d '.$izin['jam_akhir'];
                                                        }
                                                    }
                                                    $clockin = $_terlambat['clockin'];
                                                    $att_property = json_decode(App\Models\Attendance::find($_terlambat['id_attendance'])->att_property, true);
                                                    // if($nik_property['wg_id'] > 1){
                                                    if($att_property['wg_id'] > 1){
                                                        $clockin = Carbon\Carbon::parse($_terlambat['clockin'])->format('H:i:s');
                                                    }
                                                    $html .= '<tr>
                                                        <th>'.++$_no.'</th>
                                                        <td>'.$hari[Carbon\Carbon::parse($_terlambat['tanggal'])->dayOfWeek].'</td>
                                                        <td>'.Carbon\Carbon::parse($_terlambat['tanggal'])->format('d-m-Y').'</td>
                                                        <td>'.$clockin.'</td>
                                                        <td>'.Carbon\Carbon::parse($_terlambat['att_timestamp'])->format('H:i:s').'</td>
                                                        <td>'.$_terlambat['durasi'].'</td>
                                                        <td>'.$str_izin.'</td>
                                                    </tr>';
                                                }
                                                $html .= '</tbody></table>';
                                                echo $html;
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalDetail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Email Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <table class="tbl-laporan text-dark">
                    <tbody>
                        <tr>
                            <td class="text-start align-top">Subject</td>
                            <td class="text-start align-top" id="modal-subject"></td>
                        </tr>
                        <tr>
                            <td class="text-start align-top">To</td>
                            <td class="text-start align-top" id="modal-to"></td>
                        </tr>
                        <tr>
                            <td class="text-start align-top">Cc</td>
                            <td class="text-start align-top" id="modal-cc"></td>
                        </tr>
                        <tr>
                            <td class="text-start align-top">Body</td>
                            <td class="text-start align-top" id="modal-body"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-bs-dismiss="modal">Tutup</button>
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
<script src="<?php echo e(asset('assets/js/sweet-alert/sweetalert.min.js')); ?>"></script>
<script type="text/javascript">
    function preview_email(str) {
        document.getElementById('modal-subject').innerHTML = str.getAttribute('data-subject');
        document.getElementById('modal-to').innerHTML = str.getAttribute('data-to');
        document.getElementById('modal-cc').innerHTML = str.getAttribute('data-cc');
        document.getElementById('modal-body').innerHTML = atob(str.getAttribute('data-body'));
    }
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
    // 
    function toggle_status_check(source) {
        checkboxes = document.getElementsByName('reminder_sp[]');
        for (var i = 0, n = checkboxes.length; i < n; i++) {
            checkboxes[i].checked = source.checked;
        }
    }

</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/reminder_sp/index.blade.php ENDPATH**/ ?>