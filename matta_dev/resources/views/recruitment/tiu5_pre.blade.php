<!DOCTYPE html>
<html lang="en">

<head>
    <title>PSIKOTES</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{asset('assets/images/favicon.png')}}" type="image/x-icon">
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
                            loading="lazy" src="{{ asset('assets/images/matta/alita.png') }}" alt="Alita"></div>
                </div>
                <hr class="mt-2 mb-2" />
                @if(Session::has('msg'))
                <div class="alert alert-success dark alert-dismissible fade show" role="alert">
                    <h5>{{ session('msg') }}</h6>
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                <div class="row g-0">
                    <h3 class="text-center">PSIKOTES</h3>
                </div>
                <hr class="mt-2 mb-2" />
                {{--  --}}
                <div class="card-body">
                    <div class="row">
                        <!-- Kolom kiri (teks) -->
                        <div class="col-md-6 d-flex align-items-center">
                            <div class="row">
                                <div class="col-12">
                                    <p>Semangat Pagi!</p>
                                    <p>Kepada Yth. {{ $trx->nama }}<br/>Peserta Psikotes - Seleksi Penerimaan Karyawan<br/>PT. Alita Praya Mitra</p>
                                    <p style="text-align: justify">Kami ucapkan selamat datang kepada Anda dalam sesi Psikotes yang diselenggarakan oleh PT Alita Praya Mitra. Kami sangat menghargai waktu dan usaha yang Anda luangkan untuk mengikuti proses Seleksi Penerimaan Karyawan ini. Psikotes ini merupakan bagian penting dari upaya kami untuk mengenal lebih jauh kepribadian, potensi dan kompetensi Anda.</p>
                                    <p style="text-align: justify">Kami berharap Anda dapat mengikuti seluruh rangkaian tes dengan baik dan menunjukkan kemampuan terbaik Anda. Semoga hasil yang diperoleh dapat memberikan gambaran yang jelas mengenai kecocokan Anda dengan posisi yang dilamar.</p>
                                    <p style="text-align: justify">Pastikan Anda berada di tempat yang tenang dan memiliki koneksi internet yang stabil. Luangkan waktu dengan baik dan fokus dalam mengerjakan setiap soal.</p>
                                    <p style="text-align: justify">Proses Psikotes ini terdiri dari beberapa jenis tes dan di setiap jenis tes memiliki petunjuk pengerjaan dan instruksi yang berbeda - beda, mohon untuk membaca dengan baik dan teliti seluruh petunjuk dan instruksi di setiap jenis tes. Seluruh jenis tes memiliki batasan waktu, jadi mohon untuk mengerjakan semaksimal mungkin sesuai dengan batasan waktu yang diberikan.</p>
                                    <p style="text-align: justify; color:red; text-transform: uppercase; font-weight: bold;">Batasan waktu untuk mengerjakan seluruh psikotes ini adalah {{ round($trx->remain/60) }} menit.<br/>Tes ini akan dimulai setelah anda klik tombol Start</p>
                                    <p style="text-align: justify">Jika ada pertanyaan atau kendala selama proses tes berlangsung, jangan ragu untuk segera menghubungi tim kami.<br/>Terima kasih atas perhatian dan partisipasi Anda. Selamat mengerjakan tes.</p>
                                    <p>Best Regards,<br/>Human Capital & Organization<br/><a href="https://alita.id/" target="_blank">www.alita.id</a><br/><strong>"Passion - Integrity - Excellent"</strong></p>
                                </div>
                                <form class="text-end"
                                    action="{{ route('recruitment.psychotest', [Crypt::encrypt(array($trx->id, 'psychotest', $soal->id))]) }}"
                                    method="POST" enctype="multipart/form-data"
                                    onsubmit="return confirm('Are you sure want to start this psychotest?');">
                                    @csrf
                                    <button class="btn btn-primary" type="submit">Start</button>
                                </form>
                            </div>
                        </div>
                        <!-- Kolom kanan (gambar) -->
                        <div class="col-md-6 text-center">
                            {{-- <p style="text-align: justify">Beriku ini adalah petunjuk dan contoh pengerjaan soal TIU 5.</p>
                            <img src="{{ asset('assets/images/matta/tiu5/soal0.png') }}" alt="tiu5" class="img-fluid"> --}}
                            <img src="{{ asset('assets/recruitment/test.jpg') }}" alt="tiu5" class="img-fluid">
                        </div>
                    </div>
                </div>

                {{-- <div class="row g-0">
                    <div class="col-12 col-md-7">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <p>Semangat Pagi!</p>
                                    <p>Kepada Yth. {{ $trx->nama }}<br/>
                                        Peserta Psikotes - Seleksi Penerimaan Karyawan<br/>
                                        PT. Alita Praya Mitra</p>
                                    <p style="text-align: justify">Kami ucapkan selamat datang kepada Anda dalam sesi
                                        Psikotes yang diselenggarakan
                                        oleh PT Alita Praya Mitra. Kami sangat menghargai waktu dan usaha yang Anda
                                        luangkan
                                        untuk mengikuti proses Seleksi Penerimaan Karyawan ini. Psikotes ini merupakan
                                        bagian penting dari upaya kami untuk mengenal lebih jauh kepribadian, potensi
                                        dan
                                        kompetensi Anda.</p>
                                    <p style="text-align: justify">Kami berharap Anda dapat mengikuti seluruh rangkaian
                                        tes dengan baik dan
                                        menunjukkan kemampuan terbaik Anda. Semoga hasil yang diperoleh dapat memberikan
                                        gambaran yang jelas mengenai kecocokan Anda dengan posisi yang dilamar.</p>
                                    <p style="text-align: justify">Harap diingat bahwa tidak ada jawaban benar atau
                                        salah, jadi jawablah dengan jujur
                                        dan sesuai dengan diri Anda.</p>
                                    <p style="text-align: justify">Pastikan Anda berada di tempat yang tenang dan
                                        memiliki koneksi internet yang
                                        stabil. Luangkan waktu dengan baik dan fokus dalam mengerjakan setiap soal.</p>
                                    <p style="text-align: justify">Proses Psikotes ini terdiri dari 4 jenis tes dan
                                        di setiap jenis tes memiliki
                                        petunjuk pengerjaan dan instruksi yang berbeda - beda, mohon untuk membaca
                                        dengan
                                        baik dan teliti seluruh petunjuk dan instruksi di setiap jenis tes. Seluruh
                                        jenis tes memiliki batasan waktu, jadi mohon untuk mengerjakan semaksimal
                                        mungkin
                                        sesuai dengan batasan waktu yang diberikan.</p>
                                    <p style="text-align: justify; color:red; text-transform: uppercase; font-weight: bold;">Batasan waktu untuk mengerjakan seluruh psikotes ini adalah {{ round($trx->remain/60) }} menit
                                        <br/>Tes ini akan dimulai setelah anda klik tombol Start</p>
                                    <p style="text-align: justify">Jika ada pertanyaan atau kendala selama proses tes
                                        berlangsung, jangan ragu untuk segera menghubungi tim kami.
                                        <br/>Terima kasih atas perhatian dan partisipasi Anda. Selamat mengerjakan
                                        tes.</p>
                                        <p>Best Regards,<br/>Human Capital & Organization<br/><a href="https://alita.id/" target="_blank">www.alita.id</a><br/><strong>"Passion - Integrity - Excellent"</strong></p>
                                </div>
                                <form class="text-end"
                                    action="{{ route('recruitment.psychotest', [Crypt::encrypt(array($trx->id, 'psychotest', $soal->id))]) }}"
                                    method="POST" enctype="multipart/form-data"
                                    onsubmit="return confirm('Are you sure want to start this psychotest?');">
                                    @csrf
                                    <button class="btn btn-primary" type="submit">Start</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-5 p-2 p-md-2 p-xl-2">
                        <img class="img-fluid rounded-start w-100 h-100 object-fit-cover" loading="lazy" src="{{ asset('assets/recruitment/test.jpg') }}" alt="Application Form">
                    </div>
                </div> --}}
                {{--  --}}
            </div>
        </div>
    </section>
</body>
<script type="text/javascript">
    // 

</script>

</html>
