<!DOCTYPE html>
<html lang="en">

<head>
    <title>Interpretation</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="<?php echo e(asset('assets/images/favicon.png')); ?>" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
    <section class="p-2 p-md-2 p-xl-2">
        <div class="container">
            <div class="card border-light-subtle shadow-sm">
                <div class="row g-0 text-end">
                    <div class="col-12 col-md-12"><img class="img-fluid rounded-start w-194 h-73 object-fit-cover" loading="lazy" src="<?php echo e(asset('assets/images/matta/alita.png')); ?>" alt="Alita"></div>
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
                    <h6>Upload file is ERROR</h6>
                    <ul>
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif; ?>
                <div class="row g-0 text-center">
                    <h4 class="text-center">RESUME HASIL TES</h4>
                </div>
                <hr class="mt-2 mb-2" />
                <?php if(!empty($resume)): ?>
                <div class="row g-0">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="col-12 mb-2">
                                <label class="form-label" for="nama">Nama</label>
                                <input class="form-control" name="nama" id="nama" type="text" readonly="" value="<?php echo e($_trx->nama); ?>">
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="col-12 mb-2">
                                <label class="form-label" for="tempat_tanggal_lahir">Tempat/Tanggal Lahir</label>
                                <input class="form-control" name="tempat_tanggal_lahir" id="tempat_tanggal_lahir" type="text" readonly="" value="<?php echo e($_trx->tempat_lahir); ?>/<?php echo e(Carbon\Carbon::parse($_trx->tanggal_lahir)->format('d-m-Y')); ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="mt-2 mb-2" />
                <div class="row g-0 text-center">
                    <h5 class="text-center">Rekomendasi dan Kesimpulan</h5>
                </div>
                <hr class="mt-2 mb-2" />
                <div class="row m-2">
                    
                    <?php $x=0 ?>
                    <div class="col-12 col-md-6">
                        <div class="col-12 mb-2">
                            <label class="form-label" for="jawaban_<?php echo e($resume[$x]->id); ?>"><?php echo e($resume[$x]->pernyataan1); ?></label>
                            <select class="form-select col-sm-12" id="jawaban_<?php echo e($resume[$x]->id); ?>" name="jawaban_<?php echo e($resume[$x]->id); ?>" required="">
                                <option selected="" disabled="" value="">Pilih...</option>
                                <option value="1" <?php if($resume[$x]->jawaban == 1): ?> selected <?php endif; ?>>Disarankan</option>
                                <option value="0" <?php if($resume[$x]->jawaban == 0): ?> selected <?php endif; ?>>Tidak Disarankan</option>
                            </select>
                        </div>
                    </div>
                    
                    <?php $x=3 ?>
                    <div class="col-12 col-md-6">
                        <div class="col-12 mb-2">
                            <label class="form-label" for="jawaban_<?php echo e($resume[$x]->id); ?>"><?php echo e($resume[$x]->pernyataan1); ?></label>
                            <textarea class="form-control" id="jawaban_<?php echo e($resume[$x]->id); ?>" name="jawaban_<?php echo e($resume[$x]->id); ?>" rows="4" required=""><?php echo e($resume[$x]->jawaban); ?></textarea>
                        </div>
                    </div>
                </div>
                <hr class="mt-2 mb-2" />
                <div class="row g-0 text-center">
                    <h5 class="text-center">Uraian Perilaku</h5>
                </div>
                <hr class="mt-2 mb-2" />
                <div class="row m-2">
                    <?php $x=1 ?>
                    
                    <div class="col-12 col-md-6">
                        <div class="col-12 mb-2">
                            <label class="form-label" for="jawaban_<?php echo e($resume[$x]->id); ?>"><?php echo e($resume[$x]->pernyataan1); ?></label>
                            <textarea class="form-control" id="jawaban_<?php echo e($resume[$x]->id); ?>" name="jawaban_<?php echo e($resume[$x]->id); ?>" rows="4" required=""><?php echo e($resume[$x]->jawaban); ?></textarea>
                        </div>
                    </div>
                    <?php $x=2 ?>
                    
                    <div class="col-12 col-md-6">
                        <div class="col-12 mb-2">
                            <label class="form-label" for="jawaban_<?php echo e($resume[$x]->id); ?>"><?php echo e($resume[$x]->pernyataan1); ?></label>
                            <textarea class="form-control" id="jawaban_<?php echo e($resume[$x]->id); ?>" name="jawaban_<?php echo e($resume[$x]->id); ?>" rows="4" required=""><?php echo e($resume[$x]->jawaban); ?></textarea>
                        </div>
                    </div>
                </div>
                <hr class="mt-2 mb-2" />
                <?php $x=4 ?>
                <div class="row m-2">
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="text-start">
                                <tr>
                                    <th>Core Competence</th>
                                    <th>Standard Competence</th>
                                    <th>Poin (10-100)</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php for($x = 4; $x < 14; $x++): ?>
                                <tr>
                                    <td><?php if($resume[$x]->urutan == 1): ?><?php echo e($resume[$x]->pernyataan1); ?><?php endif; ?></td>
                                    <td><?php echo e($resume[$x]->pernyataan2); ?></td>
                                    <td><input min=1 type="number" oninput="validity.valid||(value='')" onfocus="use_number(this)" onblur="use_text(this)" class="form-control" name="jawaban_<?php echo e($resume[$x]->id); ?>" id="jawaban_<?php echo e($resume[$x]->id); ?>" required="" value="<?php echo e($resume[$x]->jawaban); ?>"></td>
                                    <td><input class="form-control" name="keterangan_<?php echo e($resume[$x]->id); ?>" id="keterangan_<?php echo e($resume[$x]->id); ?>" type="text" value="<?php echo e($resume[$x]->keterangan); ?>"></td>
                                </tr>
                                <?php endfor; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('hc')): ?>
            <div class="col-12 m-2 text-center">
                <a target="_blank" href="<?php echo e(route('recruitment.download_interpretation', Crypt::encrypt($_trx->id))); ?>" class="btn btn-primary" type="button"><i class="fa fa-download" aria-hidden="true"></i> Download</a>
            </div>
            <?php endif; ?>
        </div>
    </section>
</body>
<script type="text/javascript">
// 
</script>

</html>
<?php /**PATH C:\xampp\htdocs\matta\resources\views/recruitment/get_interpretation.blade.php ENDPATH**/ ?>