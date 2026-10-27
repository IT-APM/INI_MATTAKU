<!DOCTYPE html>
<html lang="en">

<head>
    <title>Application Form</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="<?php echo e(asset('assets/images/favicon.png')); ?>" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>

<body>
    <section class="p-2 p-md-2 p-xl-2">
        <div class="container">
            <div class="card border-light-subtle shadow-sm">
                <div class="row g-0 text-end">
                    <div class="col-12 col-md-12"><img class="img-fluid rounded-start w-194 h-73 object-fit-cover"
                            loading="lazy" src="<?php echo e(asset('assets/images/matta/alita.png')); ?>" alt="Alita"></div>
                </div>
                <hr class="mt-2 mb-2" />
                <?php if(Session::has('msg')): ?>
                <div class="alert alert-success dark alert-dismissible fade show" role="alert">
                    <h5><?php echo e(session('msg')); ?></h6>
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif; ?>
                <?php if(count($errors) > 0): ?>
                <div class="alert alert-danger dark alert-dismissible fade show" role="alert">
                    <h6>Upload image is ERROR</h6>
                    <ul>
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif; ?>
                <div class="row g-0">
                    <div class="col-9">
                    <h3 class="text-center">
                        <?php if($soal->jenis == 1): ?>
                        EPPS
                        <?php elseif($soal->jenis == 2): ?>
                        PAPI KOSTIC
                        <?php elseif($soal->jenis == 3): ?>
                        DISC
                        <?php elseif($soal->jenis == 4): ?>
                        BAUM
                        <?php elseif($soal->jenis == 5): ?>
                        DAP
                        <?php elseif($soal->jenis == 6): ?>
                        TIU 5
                        <?php endif; ?>
                    </h3>
                </div>
                <div class="col-3">
                <h3 id="div_timer" class="text-center" style="color:red;"></h3>
            </div>
                </div>
                <hr class="mt-2 mb-2" />
                <div class="col-12 col-md-12">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <?php if($soal->jenis == 1 && $soal->nomor > 0): ?>
                                <span class="text-uppercase fw-bold">Petunjuk Pengerjaan</span>
                                <span>
                                    
                                    <br/>Tugas anda adalah memilih pernyataan yang paling menggambarkan dengan diri anda.
                                    <br/>Jika pernyataan A yang paling menggambarkan dengan diri anda silahkan pilih di samping huruf A pada kolom yang tersedia.
                                    <br/>Jika pernyataan B yang paling menggambarkan dengan diri anda silahkan pilih di samping huruf B pada kolom yang tersedia.
                                    <br/>Akan ada pernyataan yang kedua duanya menggambarkan dengan diri anda atau tidak menggambarkan dengan diri anda dan anda tidak diperbolehkan memilih kedua pernyataan.
                                    <br/>Anda hanya diperbolehkan memilih satu pernyataan dalam 1 soal.
                                </span>
                                <?php elseif($soal->jenis == 1 && $soal->nomor == 0): ?>
                                <span>
                                    <p>Semangat Pagi!</p>
                                    <p>Selanjutnya, Anda akan menjalani Tes EPPS dalam proses psikotes. Silahkan bersiap dan mengerjakan dengan sebaik-baiknya. Ikuti arahan atau instruksi dalam tes berikut ini. Psikotes ini dirancang untuk menilai berbagai aspek kemampuan serta kepribadian yang berhubungan dengan posisi yang Anda lamar. Semoga Anda mendapatkan hasil yang terbaik.</p>
                                    <p style="text-align: justify; color:red; text-transform: uppercase; font-weight: bold;">Tes ini akan dimulai setelah anda klik tombol Start</p>
                                    <p>Best Regards,<br/>Human Capital & Organization<br/><a href="https://alita.id/" target="_blank">www.alita.id</a><br/><strong>"Passion - Integrity - Excellent"</strong></p>
                                </span>
                                <?php elseif($soal->jenis == 2 && $soal->nomor == 0): ?>
                                <span>
                                    <p>Semangat Pagi!</p>
                                    <p>Selanjutnya, Anda akan menjalani Tes PAPI KOSTIC dalam proses psikotes. Silahkan bersiap dan mengerjakan dengan sebaik-baiknya. Ikuti arahan atau instruksi dalam tes berikut ini. Psikotes ini dirancang untuk menilai berbagai aspek kemampuan serta kepribadian yang berhubungan dengan posisi yang Anda lamar. Semoga Anda mendapatkan hasil yang terbaik.</p>
                                    <p style="text-align: justify; color:red; text-transform: uppercase; font-weight: bold;">Tes ini akan dimulai setelah anda klik tombol Start</p>
                                    <p>Best Regards,<br/>Human Capital & Organization<br/><a href="https://alita.id/" target="_blank">www.alita.id</a><br/><strong>"Passion - Integrity - Excellent"</strong></p>
                                </span>
                                <?php elseif($soal->jenis == 2 && $soal->nomor > 0): ?>
                                <span class="text-uppercase fw-bold">Petunjuk Pengerjaan</span>
                                <span>
                                    
                                    <br/>Pilihlah satu dari tiap pasangan tersebut yang saudara anggap paling dekat menggambarkan saudara.
                                    <br/>Bila tidak satupun dari sebuah pasangan pernyataan itu cocok, pilihlah yang saudara anggap paling mendekati.
                                    <br/>Kerjakan secepat mungkin dan pilihlah hanya satu pernyataan dari tiap pasang.
                                </span>
                                <?php elseif($soal->jenis == 3 && $soal->nomor == 0): ?>
                                <span>
                                    <p>Semangat Pagi!</p>
                                    <p>Selanjutnya, Anda akan menjalani Tes DISC dalam proses psikotes. Silahkan bersiap dan mengerjakan dengan sebaik-baiknya. Ikuti arahan atau instruksi dalam tes berikut ini. Psikotes ini dirancang untuk menilai berbagai aspek kemampuan serta kepribadian yang berhubungan dengan posisi yang Anda lamar. Semoga Anda mendapatkan hasil yang terbaik.</p>
                                    <p style="text-align: justify; color:red; text-transform: uppercase; font-weight: bold;">Tes ini akan dimulai setelah anda klik tombol Start</p>
                                    <p>Best Regards,<br/>Human Capital & Organization<br/><a href="https://alita.id/" target="_blank">www.alita.id</a><br/><strong>"Passion - Integrity - Excellent"</strong></p>
                                </span>
                                <?php elseif($soal->jenis == 3 && $soal->nomor > 0): ?>
                                <span class="text-uppercase fw-bold">Petunjuk Pengerjaan</span>
                                <span>
                                    
                                    <br/>Setiap nomor di bawah ini memuat 4 (empat) kalimat. Tugas anda adalah:
                                    <br/>1. Memilih pada kolom di bawah huruf  [P] di samping kalimat yang PALING menggambarkan diri anda.
                                    <br/>2. Memilih pada kolom di bawah huruf  [K] di samping kalimat yang PALING TIDAK menggambarkan diri anda.
                                    <br/>PERHATIKAN: Setiap nomor hanya ada 1 yang dipilih di bawah masing-masing kolom P dan K.
                                </span>
                                <?php elseif($soal->jenis == 4 && $soal->nomor == 0): ?>
                                <span>
                                    <p>Semangat Pagi!</p>
                                    <p>Selanjutnya, Anda akan menjalani Tes BAUM dalam proses psikotes. Silahkan bersiap dan mengerjakan dengan sebaik-baiknya. Ikuti arahan atau instruksi dalam tes berikut ini. Psikotes ini dirancang untuk menilai berbagai aspek kemampuan serta kepribadian yang berhubungan dengan posisi yang Anda lamar. Semoga Anda mendapatkan hasil yang terbaik.</p>
                                    <p style="text-align: justify; color:red; text-transform: uppercase; font-weight: bold;">Tes ini akan dimulai setelah anda klik tombol Start</p>
                                    <p>Best Regards,<br/>Human Capital & Organization<br/><a href="https://alita.id/" target="_blank">www.alita.id</a><br/><strong>"Passion - Integrity - Excellent"</strong></p>
                                </span>
                                <?php elseif($soal->jenis == 5 && $soal->nomor == 0): ?>
                                <span>
                                    <p>Semangat Pagi!</p>
                                    <p>Selanjutnya, Anda akan menjalani Tes DAP dalam proses psikotes. Silahkan bersiap dan mengerjakan dengan sebaik-baiknya. Ikuti arahan atau instruksi dalam tes berikut ini. Psikotes ini dirancang untuk menilai berbagai aspek kemampuan serta kepribadian yang berhubungan dengan posisi yang Anda lamar. Semoga Anda mendapatkan hasil yang terbaik.</p>
                                    <p style="text-align: justify; color:red; text-transform: uppercase; font-weight: bold;">Tes ini akan dimulai setelah anda klik tombol Start</p>
                                    <p>Best Regards,<br/>Human Capital & Organization<br/><a href="https://alita.id/" target="_blank">www.alita.id</a><br/><strong>"Passion - Integrity - Excellent"</strong></p>
                                </span>
                                <?php elseif(in_array($soal->jenis, [4,5]) && $soal->nomor > 0): ?>
                                <span class="text-uppercase fw-bold">Petunjuk Pengerjaan</span>
                                <span>
                                    <br/>Mohon Sediakan:
                                    <br/>1. Pensil
                                    <br/>2. Kertas HVS satu lembar
                                    <br/>3. Tidak diperbolehkan menggunakan penghapus
                                    <br/>4. Bila salah dicoret saja
                                    <br/>5. Waktu pengerjaan gambar 10 menit
                                    <br/>6. Jangan jadikan kertas hvs sebagai alas untuk menggambar
                                </span>
                                <?php endif; ?>
                            </div>
                            <?php if($soal->nomor > 0): ?>
                            <hr class="mt-2 mb-2" />
                            <div class="col-12">
                                <span>Nomor <?php echo e($soal->nomor); ?> dari <?php echo e($jumlah); ?></span>
                            </div>
                            <hr class="mt-2 mb-2" />
                            <?php endif; ?>
                            <?php if(in_array($soal->jenis, [1,2])): ?>
                            <form
                                action="<?php echo e(route('recruitment.psychotest', [Crypt::encrypt(array($trx->id, 'psychotest', $soal->id))])); ?>"
                                method="POST" enctype="multipart/form-data"
                                <?php if($soal->nomor == 0): ?> onsubmit="return confirm('Are you sure want to start this test?');" <?php elseif($soal->nomor > 0): ?> onsubmit="return confirm('Are you sure want to submit this data?');" <?php endif; ?>>
                                <?php echo csrf_field(); ?>
                            <?php elseif(in_array($soal->jenis, [3])): ?>
                            <form name="_form" id="_form"
                                action="<?php echo e(route('recruitment.psychotest', [Crypt::encrypt(array($trx->id, 'psychotest', $soal->id))])); ?>"
                                method="POST" enctype="multipart/form-data"
                                <?php if($soal->nomor == 0): ?> onsubmit="return confirm('Are you sure want to start this test?');" <?php elseif($soal->nomor > 0): ?> onsubmit="return validateForm()" <?php endif; ?>>
                                <?php echo csrf_field(); ?>
                            <?php elseif(in_array($soal->jenis, [4,5]) && $soal->nomor == 0): ?>
                            <form name="_form" id="_form"
                                action="<?php echo e(route('recruitment.psychotest', [Crypt::encrypt(array($trx->id, 'psychotest', $soal->id))])); ?>"
                                method="POST" enctype="multipart/form-data"
                                <?php if($soal->nomor == 0): ?> onsubmit="return confirm('Are you sure want to start this test?');" <?php elseif($soal->nomor > 0): ?> onsubmit="return confirm('Are you sure want to submit this data?');" <?php endif; ?>>
                                <?php echo csrf_field(); ?>
                            <?php elseif(in_array($soal->jenis, [4,5]) && $soal->nomor > 0): ?>
                            <form
                                action="<?php echo e(route('recruitment.psychotest_file')); ?>"
                                method="POST" enctype="multipart/form-data"
                                <?php if($soal->nomor == 0): ?> onsubmit="return confirm('Are you sure want to start this test?');" <?php elseif($soal->nomor > 0): ?> onsubmit="return confirm('Are you sure want to submit this data?');" <?php endif; ?>>
                                <?php echo csrf_field(); ?>
                                <input type="hidden" id="id" name="id" value="<?php echo e(Crypt::encrypt(array($trx->id, 'psychotest', $soal->id))); ?>">
                            <?php elseif(in_array($soal->jenis, [6])): ?>
                            <form
                                <?php if($soal->nomor == 0 && $soal->urutan > 0): ?> id="formJawaban" <?php endif; ?>
                                action="<?php echo e(route('recruitment.psychotest', [Crypt::encrypt(array($trx->id, 'psychotest', $soal->id))])); ?>"
                                method="POST" enctype="multipart/form-data"
                                <?php if($soal->nomor == 0 && $soal->urutan == 0): ?> onsubmit="return confirm('Are you sure want to start this test?');" <?php elseif($soal->nomor > 0): ?> onsubmit="return confirm('Are you sure want to submit this data?');" <?php endif; ?>>
                                <?php echo csrf_field(); ?>
                            <?php endif; ?>
                                <input type="hidden" id="je" name="je" value="<?php echo e($soal->jenis); ?>">
                                <input type="hidden" id="no" name="no" value="<?php echo e($soal->nomor); ?>">
                                <?php if(in_array($soal->jenis, [1,2,3]) && $soal->nomor > 0): ?>
                                    <?php if(in_array($soal->jenis, [1,2])): ?>
                                    <div class="col-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th width="10%" class="text-center">Huruf</th>
                                                        <th width="90%">Soal</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td class="text-center">A&nbsp;<input style="width: 20px; height: 20px; border: 1px solid" class="form-check-input" type="radio" name="a" id="a" required="" value="1"></td>
                                                        <td><?php echo e($soal->soal1); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">B&nbsp;<input style="width: 20px; height: 20px; border: 1px solid" class="form-check-input" type="radio" name="a" id="a" required="" value="2"></td>
                                                        <td><?php echo e($soal->soal2); ?></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <?php elseif($soal->jenis == 3): ?>
                                    <div class="col-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th width="10%" class="text-center">Huruf [P]</th>
                                                        <th width="10%" class="text-center">Huruf [K]</th>
                                                        <th width="90%">Soal</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td class="text-center">
                                                            <input style="width: 20px; height: 20px; border: 1px solid" class="form-check-input" type="radio" name="p" id="p" required="" value="1">
                                                        </td>
                                                        <td class="text-center">
                                                            <input style="width: 20px; height: 20px; border: 1px solid" class="form-check-input" type="radio" name="k" id="k" required="" value="1">
                                                        </td>
                                                        <td>
                                                            <?php echo e($soal->soal1); ?>

                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">
                                                            <input style="width: 20px; height: 20px; border: 1px solid" class="form-check-input" type="radio" name="p" id="p" required="" value="2">
                                                        </td>
                                                        <td class="text-center">
                                                            <input style="width: 20px; height: 20px; border: 1px solid" class="form-check-input" type="radio" name="k" id="k" required="" value="2">
                                                        </td>
                                                        <td>
                                                            <?php echo e($soal->soal2); ?>

                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">
                                                            <input style="width: 20px; height: 20px; border: 1px solid" class="form-check-input" type="radio" name="p" id="p" required="" value="3">
                                                        </td>
                                                        <td class="text-center">
                                                            <input style="width: 20px; height: 20px; border: 1px solid" class="form-check-input" type="radio" name="k" id="k" required="" value="3">
                                                        </td>
                                                        <td>
                                                            <?php echo e($soal->soal3); ?>

                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">
                                                            <input style="width: 20px; height: 20px; border: 1px solid" class="form-check-input" type="radio" name="p" id="p" required="" value="4">
                                                        </td>
                                                        <td class="text-center">
                                                            <input style="width: 20px; height: 20px; border: 1px solid" class="form-check-input" type="radio" name="k" id="k" required="" value="4">
                                                        </td>
                                                        <td>
                                                            <?php echo e($soal->soal4); ?>

                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                <?php elseif(in_array($soal->jenis, [4,5]) && $soal->nomor > 0): ?>
                                <div class="row g-0">
                                    <?php if($soal->jenis == 4): ?>
                                    <div class="col-12 col-md-6">
                                        <span>GAMBAR POHON
                                            <br/>1. Gambarlah sebuah pohon yang berbatang keras atau berkambium!
                                            <br/>2. Jangan menggambar pohon berikut:
                                            <br/>&emsp;Palem, Beringin, Cemara, Kelapa, Bunga, Pisang, Umbi-umbian,<br/>&emsp;Padi, Rumput dan Tanaman merambat
                                            <br/>3. Tuliskan nama pohon yang anda gambar 
                                            <br/>4. Setelah selesai, kemudian scan/foto dan upload gambar tersebut!
                                            <p class="mt-3 me-3">
                                            <input name="gambar_pohon" id="gambar_pohon" class="form-control" type="file" required="">
                                            <label class="form-label">*Maksimal 5 MB dengan format pdf atau gambar(jpg, jpeg dan png)</label>
                                            </p>
                                        </span>
                                    </div>
                                    <?php elseif($soal->jenis == 5): ?>
                                    <div class="col-12 col-md-6">
                                        <span>GAMBAR MANUSIA
                                            <br/>1. Gambarlah manusia secara utuh!
                                            <br/>2. Jangan menggambar:
                                            <br/>&emsp;Patung, Karikatur, Stickman, Animasi, Sketsa, Kartun, Tokoh Pahlawan dan Robot
                                            <br/>3. Setelah menggambar manusia silahkan beri keterangan sebagai berikut:
                                            <br/>&emsp;a. Usia
                                            <br/>&emsp;b. Jenis Kelamin
                                            <br/>&emsp;c. Profesi
                                            <br/>&emsp;d. Aktivitas yang sedang dilakukan pada gambar manusia tersebut
                                            <br/>&emsp;e. Beri 5 kelebihan (dalam hal sifat atau kepribadian)
                                            <br/>&emsp;f. Beri 5 kekurangan (dalam hal sifat atau kepribadian)
                                            <br/>4. Setelah selesai, kemudian scan/foto dan upload gambar tersebut!
                                            <p class="mt-3 me-3">
                                            <input name="gambar_manusia" id="gambar_manusia" class="form-control" type="file" required="">
                                            <label class="form-label">*Maksimal 5 MB dengan format pdf atau gambar(jpg, jpeg dan png)</label>
                                            </p>
                                        </span>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <?php elseif(in_array($soal->jenis, [6]) && $soal->nomor == 0): ?>
                                <div class="col-12">
                                    <div class="card-body">
                                        <?php if($soal->urutan == 1): ?>
                                        <input type="hidden" id="kunci_jawaban" name="kunci_jawaban" value="1">
                                        <span>Contoh 1:<br>Bila A dikecilkan diperoleh B. Bila sekarang dengan C dilakukan hal serupa, jadi C dikecilkan.
                                            <br/>Diperoleh gambar 1, maka dari itu silahkan memilih pilihan 1 pada soal yang tersedia</span>
                                        <?php elseif($soal->urutan == 2): ?>
                                        <input type="hidden" id="kunci_jawaban" name="kunci_jawaban" value="4">
                                        <span>Contoh 2:<br>Bila A diputar diperoleh B, Bila C diputar diperoleh gambar...
                                            <br/>Carilah gambar tersebut dan pilihlah jawaban yang benar dari pilihan gambar yang tersedia</span>
                                        <?php elseif($soal->urutan == 3): ?>
                                        <input type="hidden" id="kunci_jawaban" name="kunci_jawaban" value="2">
                                        <span>Latihan 1:<br>Seperti halnya A dirubah menjadi B, demikian pula C dirubah menjadi salah satu dari kelima gambar berikutnya.
                                            <br/>Carilah gambar tersebut dan pilihlah jawaban yang benar dari pilihan gambar yang tersedia</span>
                                        <?php elseif($soal->urutan == 4): ?>
                                        <input type="hidden" id="kunci_jawaban" name="kunci_jawaban" value="5">
                                        <span>Latihan 2:<br>Seperti halnya A dirubah menjadi B, demikian pula C dirubah menjadi salah satu dari kelima gambar berikutnya.
                                            <br/>Carilah gambar tersebut dan pilihlah jawaban yang benar dari pilihan gambar yang tersedia</span>
                                        <?php endif; ?>
                                        
                                        <div class="text-center">
                                            <img src="<?php echo e(asset('assets/images/matta/tiu5/soal0'.$soal->urutan.'.png')); ?>" alt="Gambar" class="img-fluid" style="max-height: 300px;">
                                        </div>
                                        
                                        <div class="text-end">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="a" id="a" required="" value="1">
                                                <label class="form-check-label" for="pilihan1">Pilihan 1</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="a" id="a" required="" value="2">
                                                <label class="form-check-label" for="pilihan2">Pilihan 2</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="a" id="a" required="" value="3">
                                                <label class="form-check-label" for="pilihan3">Pilihan 3</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="a" id="a" required="" value="4">
                                                <label class="form-check-label" for="pilihan4">Pilihan 4</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="a" id="a" required="" value="5">
                                                <label class="form-check-label" for="pilihan5">Pilihan 5</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php elseif(in_array($soal->jenis, [6]) && $soal->nomor > 0): ?>
                                <div class="col-12">
                                    <div class="card-body">
                                        
                                        <div class="text-center">
                                            <img src="<?php echo e(asset('assets/images/matta/tiu5/soal'.$soal->nomor.'.png')); ?>" alt="Gambar" class="img-fluid" style="max-height: 300px;">
                                        </div>
                                        
                                        <div class="text-end">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="a" id="a" required="" value="1">
                                                <label class="form-check-label" for="pilihan1">Pilihan 1</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="a" id="a" required="" value="2">
                                                <label class="form-check-label" for="pilihan2">Pilihan 2</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="a" id="a" required="" value="3">
                                                <label class="form-check-label" for="pilihan3">Pilihan 3</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="a" id="a" required="" value="4">
                                                <label class="form-check-label" for="pilihan4">Pilihan 4</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="a" id="a" required="" value="5">
                                                <label class="form-check-label" for="pilihan5">Pilihan 5</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>
                                <hr class="mt-2 mb-2" />
                                <div class="col-12 text-center">
                                    <?php if($soal->nomor > 0): ?>
                                    <button class="btn btn-primary" type="submit">Submit</button>
                                    <?php elseif($soal->nomor == 0): ?>
                                        <?php if($soal->jenis == 6 && $soal->urutan > 0): ?>
                                        <button class="btn btn-primary" type="submit">Submit</button>
                                        <?php else: ?>
                                        <button class="btn btn-primary" type="submit">Start</button>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>
<script type="text/javascript">
$(document).ready(function () {
    $('#_form input').on('change', function() {
        $p_val = $('input[name=p]:checked', '#_form ').val();
        $k_val = $('input[name=k]:checked', '#_form ').val();
        if($p_val == $k_val){
            alert('Isian Huruf [P] tidak boleh sama dengan Huruf [K]');
        }
        // alert('p: '+$p_val);
        // alert('k: '+$k_val);
    });
    // 
    $('#formJawaban').on('submit', function(e) {
        // Jika user klik "Cancel" di confirm, hentikan eksekusi
        if (!confirm('Are you sure want to submit this data?')) {
            e.preventDefault(); // batalkan pengiriman form
            return false;
        }
        // Ambil nilai radio yang dipilih
        let pilihan = $('input[name="a"]:checked').val();
        // Ambil nilai dari input hidden
        let kunci_jawaban = $('#kunci_jawaban').val();
        if (pilihan === kunci_jawaban) {
            alert('✅ Pilihan Anda BENAR!');
        } else {
            alert('❌ Pilihan Anda SALAH, yang BENAR adalah Pilihan ' + kunci_jawaban);
        }
        // e.preventDefault();
        return true;
    });
});
// 
function validateForm() {
    $p_val = $('input[name=p]:checked', '#_form ').val();
    $k_val = $('input[name=k]:checked', '#_form ').val();
    if($p_val == $k_val){
        alert('Isian Huruf [P] tidak boleh sama dengan Huruf [K]');
        return false;
    }
    else{
        return confirm('Are you sure want to submit this data?');
    }
}
// 
var countDownDate = new Date().getTime()+<?php echo e($trx->remain); ?>*1000;
// alert(countDownDate);
// Update the count down every 1 second
var x = setInterval(function() {

  // Get today's date and time
  var now = new Date().getTime();
    
  // Find the distance between now and the count down date
  var distance = countDownDate - now;
//   alert(distance);
  // Time calculations for days, hours, minutes and seconds
  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  var seconds = Math.floor((distance % (1000 * 60)) / 1000);

  hours = hours < 10 ? "0" + hours : hours;
  minutes = minutes < 10 ? "0" + minutes : minutes;
  seconds = seconds < 10 ? "0" + seconds : seconds;
    
  // Output the result in an element with id="div_timer"
  document.getElementById("div_timer").innerHTML = "Timer: " + hours + "h " + minutes + "m " + seconds + "s ";
//   menambahkan alert jika tersisa 10 menit
  if(hours == '00' && minutes == '10' && seconds == '00'){
    alert('Waktu anda tersisa 10 menit');
  }
  // If the count down is over, write some text 
  if (distance < 0) {
    clearInterval(x);
    document.getElementById("div_timer").innerHTML = "EXPIRED";
  }
}, 1000);
</script>

</html>
<?php /**PATH C:\xampp\htdocs\matta\resources\views/recruitment/psychotest_exe.blade.php ENDPATH**/ ?>