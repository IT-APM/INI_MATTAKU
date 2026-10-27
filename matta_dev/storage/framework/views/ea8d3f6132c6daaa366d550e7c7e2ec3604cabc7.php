<p>Semangat Pagi!</p>
<p>Yth. <?php echo e($data['trx']->nama); ?>,<br/>PT. Alita Praya Mitra</p>
<p>Kami ucapkan terimakasih telah membaca dan menyetujui offering letter yang disepakati, mohon untuk membaca dan menyetujui pedoman perilaku terlampir serta melengkapi dokumen yang diperlukan di bawah ini:
<br/>1. CV Terbaru<br/>2. Ijazah<br/>3. KTP<br/>4. Kartu Keluarga<br/>5. NPWP<br/>6. Surat Sehat<br/>7. SKCK<br/>8. BPJS Ketenagakerjaan<br/>9. BPJS Kesehatan<br/>10. Rekening Mandiri<br/>11. Sertifikat Keahlian<br/>12. Sertifikat / Surat Vaksin Covid
<br/>Kemudian <b>UPLOAD</b> pada link berikut ini.</p>
<p><a href="<?php echo e(route('recruitment.upload_dokumen_get', [Crypt::encrypt([$data['trx']->id, 'negotiation'])])); ?>" data-toggle="tooltip" title='Link Penawaran'>UPLOAD</a></p>
<p>Jika ada pertanyaan atau kendala terkait daftar dokumen di atas, silahkan menghubungi tim kami (sesuai yang tercantum dalam email).</p>
<p>Terima kasih atas perhatian dan partisipasi Anda.<br/>Selamat bergabung dan menjadi bagian dari Keluarga Alita.</p>

<?php echo $__env->make('emails.footer0', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/emails/recruitment4.blade.php ENDPATH**/ ?>