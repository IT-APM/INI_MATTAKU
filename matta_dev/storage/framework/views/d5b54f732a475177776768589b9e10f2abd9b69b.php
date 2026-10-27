<!DOCTYPE html>
<html lang="en">

<head>
    
    <title>Pengingat Absensi</title>
    <link rel="icon" href="<?php echo e(asset('assets/images/favicon.png')); ?>" type="image/x-icon">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<?php $nik_property = json_decode($data['trx']->nik_property, true); ?>

<body>
    <div style="text-align:center;">
        
        <img src="https://matta-reborn.alita.id/matta/assets/images/matta/sudah_absen_hari_ini_v21.png" alt="img" width="720" height="509">
    </div>
    <div style="text-align:center;background-color:red;color:white;">
        
        <p style="padding:5px;">Pengingat Absensi <?php echo e($data['teguran_ke']); ?></p>
    </div>
    <div>
        <div>
            <div>
                <p>Dear Bapak/Ibu/Sdr/i. <?php echo e($nik_property['nama']); ?></p>
                
                <p>Terima kasih telah menggunakan Mesin Fingerprint / Aplikasi Mobile MATTA / Web MATTA untuk kegiatan Absensi Kehadiran. Kami informasikan bahwa Absensi Kehadiran wajib dilakukan sekurang-kurangnya 80% di setiap bulannya.</p>
                <p>Bersama ini Kami sampaikan bahwa absensi kehadiran Bapak/Ibu/Sdr/i. <?php echo e($nik_property['nama']); ?> pada tanggal <?php echo e(\Carbon\Carbon::parse($data['trx']->date_from)->format('d-m-Y')); ?> sd <?php echo e(\Carbon\Carbon::parse($data['trx']->date_to)->format('d-m-Y')); ?> kurang dari 80% dari yang seharusnya terpenuhi (hadir = <?php echo e($data['trx']->arr_clock); ?> dari jumlah hari kerja = <?php echo e($data['trx']->arr_base); ?>).</p>
                <p>Persentase kehadiran anda adalah <?php echo e(round($data['trx']->persentase)); ?> %.</p>
                <div style="text-align:center;background-color:red;color:white;">
                    <?php if($data['trx']->arr_clock == 0): ?>
                    <p style="padding:5px;">Bapak/Ibu/Sdr/i. <?php echo e($nik_property['nama']); ?> tidak melakukan Absensi Kehadiran sama sekali</p>
                    <?php else: ?>
                    <p style="padding:5px;">Mohon untuk dapat lebih ditingkatkan lagi Absensi Kehadirannya</p>
                    <?php endif; ?>
                </div>
                <?php if($data['teguran_ke'] == 'Kelima'): ?>
                
                <p>Informasi ini adalah sebagai Pengingat Absensi <?php echo e($data['teguran_ke']); ?> dan menjadi Pengingat Absensi terakhir untuk Bapak/Ibu/Sdr/i. <?php echo e($nik_property['nama']); ?>. Jika bulan selanjutnya belum menunjukan peningkatan absensi kehadiran, maka akan kami terbitkan Surat Peringatan Pertama (SP 1). Kami harap kedisplinan Absensi Kehadiran dapat menunjukan peningkatan.</p>
                <?php else: ?>
                
                <p>Informasi ini adalah sebagai Pengingat Absensi <?php echo e($data['teguran_ke']); ?> untuk Bapak/Ibu/Sdr/i. <?php echo e($nik_property['nama']); ?>. Kami harap dapat melakukan Absensi Kehadiran setiap hari ke depannya.</p>
                <?php endif; ?>
                
                <p>Demikian kami sampaikan.<br/>Terima kasih atas perhatiannya.</p>
                
                <?php echo $__env->make('emails.footer0', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <p><?php echo e($data['datetime']); ?></p>
            </div>
        </div>
    </div>
</body>

</html>

<?php echo $data['history_email']; ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/emails/reminder1.blade.php ENDPATH**/ ?>