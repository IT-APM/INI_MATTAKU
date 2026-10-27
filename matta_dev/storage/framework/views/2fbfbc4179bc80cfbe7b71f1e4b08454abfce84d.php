<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Formulir Evaluasi Karyawan</title>
  <style type="text/css">
    body {
      margin: 0;
      padding: 0;
      min-width: 100% !important;
    }

    img {
      height: auto;
    }

    .content {
      width: 100%;
      max-width: 1000px;
    }

    .header {
      padding: 40px 30px 20px 30px;
    }

    .innerpadding {
      padding: 30px 30px 30px 30px;
    }

    .borderbottom {
      border-bottom: 1px solid #f2eeed;
    }

    .subhead {
      font-size: 15px;
      color: #ffffff;
      font-family: sans-serif;
      letter-spacing: 10px;
    }

    .h1,
    .h2,
    .bodycopy {
      color: #153643;
      font-family: sans-serif;
    }

    .h1 {
      font-size: 33px;
      line-height: 38px;
      font-weight: bold;
    }

    .h2 {
      padding: 0 0 15px 0;
      font-size: 24px;
      line-height: 28px;
      font-weight: bold;
    }

    .bodycopy {
      font-size: 16px;
      line-height: 22px;
    }

    .button {
      text-align: center;
      font-size: 18px;
      font-family: sans-serif;
      font-weight: bold;
      padding: 0 30px 0 30px;
    }

    .button a {
      color: #ffffff;
      text-decoration: none;
    }

    .footer {
      padding: 20px 30px 15px 30px;
    }

    .footercopy {
      font-family: sans-serif;
      font-size: 14px;
      color: #ffffff;
    }

    .footercopy a {
      color: #ffffff;
      text-decoration: underline;
    }

    .error {
      color: red;
      font-family: verdana, Helvetica;
    }



    /*@media only screen and (min-device-width: 601px) {
    .content {width: 1000px !important;}
    .col425 {width: 425px!important;}
    .col380 {width: 380px!important;}
    }*/
  </style>
</head>

<body yahoo bgcolor="#f6f8f1">
  <table width="100%" bgcolor="#f6f8f1" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td>
        <table bgcolor="#ffffff" class="content" align="center" cellpadding="0" cellspacing="0" border="0">
          <tr>
            <td bgcolor="#c7d8a7" class="header">
              <table width="70" align="left" border="0" cellpadding="0" cellspacing="0">
                <tr>
                  <td height="70" style="padding: 0 20px 20px 0;">
                    <img class="fix" src="<?php echo e(asset('assets/images/matta/icon.gif')); ?>" width="70" height="70" border="0" alt="" />
                  </td>
                </tr>
              </table>
              <table class="col425" align="left" border="0" cellpadding="0" cellspacing="0" style="width: 100%; max-width: 425px;">
                <tr>
                  <td height="70">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td class="h2" style="padding: 5px 0 0 0;">Formulir Evaluasi Karyawan</td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td class="innerpadding borderbottom">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td class="h2">Semangat Pagi,</td>
                </tr>
                <tr>
                  <td class="bodycopy">
                    Berikut ini adalah hasil pengisian Formulir Evaluasi Karyawan. Terima kasih.</td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td class="innerpadding borderbottom">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td class="bodycopy">
                    Nama Karyawan:<br /><?php echo e($karyawan['nama']); ?>

                    <hr />
                    NIP:<br /><?php echo e($karyawan['nik']); ?>

                    <hr />
                    Jabatan:<br /><?php echo e($karyawan['jabatan']); ?>

                    <hr />
                    Direktorat / Divisi / Departemen:<br /><?php echo e($karyawan['divisi']); ?> / <?php echo e($karyawan['dep']); ?> / <?php echo e($karyawan['sub_dep']); ?>

                    <hr />
                    Perusahaan:<br /><?php echo e($karyawan['perusahaan']); ?>

                    <hr />
                    Nama Atasan Langsung:<br /><?php echo e($penilai1['nama']); ?>

                    <hr />
                    Tanggal Bergabung:<br /><?php if(!empty($karyawan['tanggal_bergabung'])): ?> <?php echo e(Carbon\Carbon::parse($karyawan['tanggal_bergabung'])->format('d-m-Y')); ?> <?php endif; ?>
                    <hr />
                    Tanggal Berakhir:<br /><?php if(!empty($karyawan['tanggal_berakhir'])): ?> <?php echo e(Carbon\Carbon::parse($karyawan['tanggal_berakhir'])->format('d-m-Y')); ?> <?php endif; ?>
                    <hr />
                  </td>
                </tr>
              </table>
            </td>
          </tr>
          <!--  -->
          <tr>
            <td class="innerpadding borderbottom">
              <?php if($penilaian->id_tipe == 6): ?>
              <table>
                <tr class="bodycopy">
                  <td width="50%"><strong>Obyek Penilaian</strong></td>
                  <td width="50%"><strong><div align="center">Penilai 1</div></strong></td>
                </tr>
                <?php $__currentLoopData = $angka_skor; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr class="bodycopy">
                  <td><?php echo e($data->id_nilai3); ?>. <?php echo e($data->penilaian_1); ?></td>
                  <td><div align="center"><?php echo e($data->skor_penilai_1); ?></div></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php if($angka_rata>0): ?>
                <tr class="bodycopy">
                  <td><strong><div align="right">Total Score</div></strong></td>
                  <td><strong><div align="center"><?php echo e($angka_rata); ?></div></strong></td>
                </tr>
                <tr class="bodycopy">
                  <td><strong><div align="right">&nbsp;</div></strong></td>
                  <?php if(count($angka_skor)==14): ?>
                    <td><strong><div align="center">Result: <?php if($angka_rata>35): ?> Extend <?php else: ?> Not Extend <?php endif; ?></div></strong></td>
                  <?php else: ?>
                    <td><strong><div align="center">Result: <?php if($angka_rata>40): ?> Extend <?php else: ?> Not Extend <?php endif; ?></div></strong></td>
                  <?php endif; ?>
                </tr>
                <?php endif; ?>
              </table>
              <?php else: ?>
              <table>
                <tr class="bodycopy">
                  <td width="70%"><strong>Obyek Penilaian</strong></td>
                  <td width="15%"><strong><div align="center">Karyawan</div></strong></td>
                  <td width="15%"><strong><div align="center">Penilai 1</div></strong></td>
                </tr>
                <?php echo $angka_skor; ?>

                <?php echo $angka_total; ?>

                <?php echo $angka_rata; ?>

              </table>
              <?php endif; ?>
            </td>
          </tr>
          <!--  -->
          <?php echo $isian_kes_rek; ?>

          <!--  -->
          <tr>
            <td class="innerpadding bodycopy">Terima kasih untuk partisipasi dan kerjasamanya dalam pengisian Formulir Evaluasi Karyawan ini.</td>
          </tr>
          <tr>
            <td class="footer" bgcolor="#FF9033">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td align="center" class="footercopy">&reg; PT Alita Praya Mitra - 2021
                  </td>
                </tr>
                <tr>
                  <td align="center" style="padding: 20px 0 0 0;">
                    <table border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="150" style="text-align: center; padding: 0 10px 0 10px;">
                          <a href="https://alita.id/">
                            <img src="<?php echo e(asset('assets/images/matta/Alita-New-Log.png')); ?>" width="150" height="64" alt="Alita" border="0" />
                          </a>
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>

</html><?php /**PATH C:\xampp\htdocs\matta\resources\views/formulir/penilaian/result.blade.php ENDPATH**/ ?>