<!DOCTYPE html>
<html lang="en">

<head>
    <title>title</title>
    <link rel="icon" href="<?php echo e(asset('assets/images/favicon.png')); ?>" type="image/x-icon">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
</head>
<?php $layer1_property = json_decode($data['data']->layer1_property, true); ?>

<body>
    <div>
        <div>
            <div>
                <?php
                $html = '';
                if(!empty($data['karyawan'])){
                    $html .= '<table class="tbl-laporan">
                    <thead>
                        <tr>
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
                    foreach ($data['karyawan'] as $karyawan){
                        $nik_property = json_decode($karyawan->nik_property, true);
                        $html .= '<tr>
                            <th>'.++$_no.'</th>
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
                        if(!empty($karyawan->perubahan)){
                            $html .= '<tr style="background-color:#ffc107;">
                            <td colspan="2">Perubahan</td>
                            <td colspan="9">'.nl2br($karyawan->perubahan).'</td>
                            </tr>';
                        }
                    }
                    $html .= '</tbody></table>';
                }else{
                    $html .= '<h4 class="text-center bg-danger py-2">No Data</h4>';
                }
                ?>
                
                <?php if(in_array($data['data']->is_berubah, [0, 1])): ?>
                <p>Dear Bapak/Ibu/Sdr/i. <?php echo e($layer1_property['nama']); ?></p>
                <p>Berikut ini data karyawan Divisi <?php echo e($layer1_property['dep']); ?>, Departement <?php echo e($layer1_property['sub_dep']); ?> periode bulan <?php echo e(Carbon\Carbon::parse($data['data']->tanggal)->locale('id')->isoFormat('MMMM YYYY')); ?>.</p>
                
                <?php elseif(in_array($data['data']->is_berubah, [2])): ?>
                <p>Dear</p>
                <p>Berikut Konfirmasi Data Karyawan dari bawahan <?php echo e($layer1_property['nama']); ?> (Layer1), Divisi <?php echo e($layer1_property['dep']); ?>, Departement <?php echo e($layer1_property['sub_dep']); ?> periode bulan <?php echo e(Carbon\Carbon::parse($data['data']->tanggal)->locale('id')->isoFormat('MMMM YYYY')); ?>.</p>
                <?php endif; ?>
                <?php if(in_array($data['data']->is_berubah, [0, 1])): ?>
                <p>Mohon konfirmasinya selambat - lambatnya hari ini dengan klik Link Konfirmasi Data Karyawan di bawah guna kebutuhan penggajian periode bulan <?php echo e(Carbon\Carbon::parse($data['data']->tanggal)->locale('id')->isoFormat('MMMM YYYY')); ?> dan update database kami.</p>
                <?php elseif(in_array($data['data']->is_berubah, [2])): ?>
                <p>Mohon konfirmasinya telah menerima informasi ini dengan klik Link Konfirmasi Data Karyawan di bawah guna kebutuhan penggajian periode bulan <?php echo e(Carbon\Carbon::parse($data['data']->tanggal)->locale('id')->isoFormat('MMMM YYYY')); ?> dan update database kami.</p>
                <?php endif; ?>
                <?php if(!empty($data['link'])): ?>
                <p><h3><a href="<?php echo e($data['link']); ?>" target="_blank">Link Konfirmasi Data Karyawan</a></h3></p>
                <?php endif; ?>
                <p><?php echo $html; ?></p>
                <p>Demikian Infomasi saya sampaikan. Terimakasih atas perhatian dan kerjasamanya.</p>
                <?php echo $__env->make('emails.footer0', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>
        </div>
    </div>
</body>

</html><?php /**PATH C:\xampp\htdocs\matta\resources\views/emails/karyawan_bulanan0.blade.php ENDPATH**/ ?>