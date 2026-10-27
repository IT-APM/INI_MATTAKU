<?php $__env->startSection('title', 'Konfirmasi Data Karyawan'); ?>

<?php $__env->startSection('css'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<style>
    .tbl-laporan {
        border-collapse: collapse;
        margin: 5px;
        width: 100%;
    }
    .tbl-laporan th,
    .tbl-laporan td {
        border: 1px solid #dee2e6;
        padding: 6px 10px;
        /* text-align: center;
        vertical-align: middle; */
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

<?php $__env->startSection('content'); ?>
<?php
$layer1_property = json_decode($trx->layer1_property, true);
$html = '';
if(!empty($karyawan)){
    $html .= '<table class="tbl-laporan">
    <thead>
        <tr>
            <th>Action</th>
            <th>No</th>
            <th>NIP</th>
            <th>Nama</th>
            <th>Direktorat</th>
            <th>Divisi</th>
            <th>Departemen</th>
            <th>Level</th>
            <th>Jabatan</th>
            <th>Grup Kerja</th>
            <th>Status Karyawan</th>
        </tr>
    </thead>
    <tbody>';
    $_no = 0;
    foreach ($karyawan as $_karyawan){
        $nik_property = json_decode($_karyawan->nik_property, true);
        $html .= '<tr>';
        if($_karyawan->is_berubah == 1){
            $html .= '<td>
                <a href="#" 
                data-toggle="tooltip" 
                title="Perubahan"
                data-bs-toggle="modal"
                data-bs-target="#modalEdit"
                onclick="bukaModalEdit(this)"
                data-id="'.$_karyawan->id.'"
                data-nama-nik="Perubahan '.$nik_property['nama'].'"
                data-perubahan="'.$_karyawan->perubahan.'">
                    <i class="icon-pencil-alt"></i>
                </a>
            </td>';
        }else{
            $html .= '<td>&nbsp;</td>';
        }
        $html .= '<th>'.++$_no.'</th>
            <td>'.$nik_property['nik'].'</td>
            <td>'.$nik_property['nama'].'</td>
            <td>'.$nik_property['divisi'].'</td>
            <td>'.$nik_property['dep'].'</td>
            <td>'.$nik_property['sub_dep'].'</td>
            <td>'.$nik_property['level'].'</td>
            <td>'.$nik_property['jabatan'].'</td>
            <td>'.$nik_property['wg_name'].'</td>
            <td>'.$nik_property['status_karyawan'].'</td>
        </tr>';
        if(!empty($_karyawan->perubahan)){
            $html .= '<tr style="background-color:#ffc107;">
            <td colspan="2">Perubahan</td>
            <td colspan="9">'.nl2br($_karyawan->perubahan).'</td>
            </tr>';
        }
    }
    $html .= '</tbody></table>';
}else{
    $html .= '<h4 class="text-center bg-danger py-2">No Data</h4>';
}
?>

<div class="container-fluid">
    <div class="row">
        
        <div class="col-sm-12 col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h4><img src="<?php echo e(asset('assets/images/matta/form-icon-25.jpg')); ?>" height="30" alt="Alita" />&nbsp;Konfirmasi Data Karyawan</h4>
                </div>
            </div>
        </div>
        
        <div class="col-sm-12 col-xl-12">
            <div class="card">
                <div class="card-body">
                    
                    <?php if(in_array($trx->is_berubah, [0, 1])): ?>
                    <p>Dear Bapak/Ibu/Sdr/i. <?php echo e($layer1_property['nama']); ?></p>
                    <p>Berikut ini data karyawan Divisi <?php echo e($layer1_property['dep']); ?>, Departement <?php echo e($layer1_property['sub_dep']); ?> periode bulan <?php echo e(Carbon\Carbon::parse($trx->tanggal)->locale('id')->isoFormat('MMMM YYYY')); ?>.
                    <br/>Jika ada perubahan data karyawan, klik icon pencil kemudian inputkan perubahannya dan simpan. Dan jika sudah selesai semua maka klik tombol Submit.
                    <br/>Jika sama sekali tidak ada perubahan maka bisa langsung klik tombol Submit.</p>
                    
                    <?php elseif(in_array($trx->is_berubah, [2])): ?>
                    <p>Dear</p>
                    <p>Berikut Konfirmasi Data Karyawan dari bawahan <?php echo e($layer1_property['nama']); ?> (Layer1), Divisi <?php echo e($layer1_property['dep']); ?>, Departement <?php echo e($layer1_property['sub_dep']); ?> periode bulan <?php echo e(Carbon\Carbon::parse($trx->tanggal)->locale('id')->isoFormat('MMMM YYYY')); ?>.</p>
                    <?php endif; ?>
                    <?php if(in_array($trx->is_berubah, [0, 1])): ?>
                    <p>Mohon konfirmasinya selambat - lambatnya hari ini dengan klik Link Konfirmasi Data Karyawan di bawah guna kebutuhan penggajian periode bulan <?php echo e(Carbon\Carbon::parse($trx->tanggal)->locale('id')->isoFormat('MMMM YYYY')); ?> dan update database kami.</p>
                    <?php elseif(in_array($trx->is_berubah, [2])): ?>
                    <p>Mohon konfirmasinya telah menerima informasi ini dengan klik Link Konfirmasi Data Karyawan di bawah guna kebutuhan penggajian periode bulan <?php echo e(Carbon\Carbon::parse($trx->tanggal)->locale('id')->isoFormat('MMMM YYYY')); ?> dan update database kami.</p>
                    <?php endif; ?>
                    <p><?php echo $html; ?></p>
                    <form method="post" enctype="multipart/form-data" action="<?php echo e(route('karyawanperubahan.update', Crypt::encrypt($trx->is_berubah + 1))); ?>" target="_self" onsubmit="return confirm('Are you sure want to submit this data?');">
                    <?php echo method_field('PATCH'); ?>
                    <?php echo csrf_field(); ?>
                    <input type="hidden" id="layer1" name="layer1" value="<?php echo e($layer1_property['nik']); ?>">
                    <input type="hidden" id="data_id_all" name="data_id_all" value="<?php echo e($trx->id); ?>">
                    <button type="submit" class="btn btn-success"><i class="fa fa-save me-1"></i> Submit</button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-sm-12 col-xl-12">
            <div class="card">
                <div class="card-body typography text-center">
                    <h6>Terima kasih untuk partisipasi dan kerjasamanya untuk melakukan Konfirmasi Data Karyawan ini.</h6>
                    <a href="https://alita.id/"><img src="<?php echo e(asset('assets/images/matta/Alita-New-Log.png')); ?>" height="40" alt="Alita" /></a><br />
                    <h6>&reg; PT Alita Praya Mitra - 2021</h6>
                </div>
            </div>
        </div>
        
    </div>
</div>

<!-- Modal Form Edit -->
<div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="data_nama_nik"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" enctype="multipart/form-data" action="<?php echo e(route('karyawanperubahan.update', Crypt::encrypt(1))); ?>" target="_self" onsubmit="return confirm('Are you sure want to submit this data?');">
            <?php echo method_field('PATCH'); ?>
            <?php echo csrf_field(); ?>
            <div class="modal-body">
                <input type="hidden" id="data_id" name="data_id">
                <input type="hidden" id="back_url" name="back_url" value="<?php echo e(URL::full()); ?>">
                <div class="mb-3">
                    <textarea class="form-control" id="perubahan" name="perubahan" rows="5" required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fa fa-times me-1"></i> Batal</button>
                <button type="submit" class="btn btn-success"><i class="fa fa-save me-1"></i> Simpan</button>
            </div>
            </form>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script type="text/javascript">
function bukaModalEdit(el) {
    const data_id = el.getAttribute('data-id');
    const perubahan = el.getAttribute('data-perubahan');
    const data_nama_nik = el.getAttribute('data-nama-nik');
    document.getElementById('data_id').value = data_id;
    document.getElementById('perubahan').value = perubahan;
    document.getElementById('data_nama_nik').innerHTML = data_nama_nik;
}
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.errors.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/emails/karyawan_bulanan.blade.php ENDPATH**/ ?>