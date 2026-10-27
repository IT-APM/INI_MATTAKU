

<?php $__env->startSection('title', 'Home'); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/datatables.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/datatable-extension.css')); ?>">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<style>
    button.dt-button {
    float:none;
    text-align:center;
    color: black;
    background: white;
    }
     .datepicker {
        border: 1px solid grey !important;
        /* border-radius: 10px; */
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
    <h3>Dashboard</h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
    <li class="breadcrumb-item">Dashboard</li>
    <li class="breadcrumb-item active">Overtime</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-tabs nav-primary" id="pills-warningtab" role="tablist">
                        <li class="nav-item"><a class="nav-link <?php if($id == 1): ?> active <?php endif; ?>" href="<?php echo e(route('overtime.dashboard', 1)); ?>">Overtime</a></li>
                    </ul>
                    <?php
                        $type = request()->get('type', 'bar');
                        $i = 0;
                    ?>
                    <div class="tab-content" id="pills-warningtabContent">
                        
                        <div class="tab-pane fade <?php if($id == 1): ?> show active <?php endif; ?>" id="pills-history" role="tabpanel" aria-labelledby="pills-history-tab">
                            <a href="<?php echo e(request()->fullUrlWithQuery(['type' => 'bar'])); ?>" class="btn btn-outline-success mt-2" type="button"><i class="fa fa-bar-chart" aria-hidden="true"></i></a>&nbsp;&nbsp;<a href="<?php echo e(request()->fullUrlWithQuery(['type' => 'line'])); ?>" class="btn btn-outline-success mt-2" type="button"><i class="fa fa-line-chart" aria-hidden="true"></i></a><br/>
                            <button class="btn btn-warning mt-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_filter_form1" aria-expanded="false" aria-controls="collapse_filter"><i class="fa fa-search" aria-hidden="true"></i> Filter Histori</button>
                            <div class="collapse" id="collapse_filter_form1">
                                <form id="form1" name="form1" class="mt-2" action="<?php echo e(route('overtime.dashboard', 1)); ?>" method="get" enctype="multipart/form-data">
                                    <div class="row mt-2">
                                        <div class="col-sm-3 mb-2">
                                            <label class="h6">Bulan-Tahun</label>
                                            <div>
                                                <input name="bulan_tahun_dari" id="bulan_tahun_dari" class="form-control digits" type="month" value="<?php echo e($bulan_tahun_dari); ?>">
                                            </div>
                                            sampai
                                            <div>
                                                <input name="bulan_tahun_sampai" id="bulan_tahun_sampai" class="form-control digits" type="month" value="<?php echo e($bulan_tahun_sampai); ?>">
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="h6">Perusahaan</label>&nbsp;<input type="checkbox" onClick="toggle_perusahaan(this)" /> Check All
                                            <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                            <label class="d-block" for="perusahaan">
                                                <?php $__currentLoopData = $perusahaan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <input class="checkbox_animated" id="perusahaan[]" name="perusahaan[]" type="checkbox" value="<?php echo e($data); ?>" <?php if(in_array($data, $arr_perusahaan)): ?> checked <?php endif; ?>> <?php echo e($data); ?><br/>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </label>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="h6">Divisi</label>&nbsp;<input type="checkbox" onClick="toggle_divisi(this)" /> Check All
                                            <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                            <label class="d-block" for="divisi">
                                                <?php $__currentLoopData = $divisi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <input class="checkbox_animated" id="divisi[]" name="divisi[]" type="checkbox" value="<?php echo e($data); ?>" <?php if(in_array($data, $arr_divisi)): ?> checked <?php endif; ?>> <?php echo e($data); ?><br/>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </label>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="h6">Status Karyawan</label>&nbsp;<input type="checkbox" onClick="toggle_status_karyawan(this)" /> Check All
                                            <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                            <label class="d-block" for="status_karyawan">
                                                <?php $__currentLoopData = $status_karyawan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <input class="checkbox_animated" id="status_karyawan[]" name="status_karyawan[]" type="checkbox" value="<?php echo e($data); ?>" <?php if(in_array($data, $arr_status_karyawan)): ?> checked <?php endif; ?>> <?php echo e($data); ?><br/>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-sm-12">
                                            <button class="btn btn-warning" id="filter" name="filter" value="true" type="submit"><i class="fa fa-search" aria-hidden="true"></i> Filter</button> | <a href="<?php echo e(route('overtime.dashboard', 1)); ?>" class="btn btn-info" type="button"><i class="fa fa-refresh" aria-hidden="true"></i> Reset</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <hr/>
                            <div class="row mt-2">
                                <div class="col-xl-12 col-sm-12">
                                    <h5 class="text-center"><?php echo e($lable[$i]); ?> <?php echo e(Carbon\Carbon::createFromFormat('Y-m', $bulan_tahun_dari)->format('M Y')); ?> sd  <?php echo e(Carbon\Carbon::createFromFormat('Y-m', $bulan_tahun_sampai)->format('M Y')); ?></h5>
                                    <div id="chart_nominal_perusahaan_tahun"></div>
                                </div>
                                <div class="col-xl-12 col-sm-12">
                                    <h5 class="text-center"><?php echo e($lable[++$i]); ?></h5>
                                    <div id="chart_nominal_perusahaan_bulan"></div>
                                </div>
                                <div class="col-xl-12 col-sm-12">
                                    <h5 class="text-center"><?php echo e($lable[++$i]); ?></h5>
                                    <div id="chart_nominal_status_bulan"></div>
                                </div>
                                <div class="col-xl-12 col-sm-12">
                                    <h5 class="text-center"><?php echo e($lable[++$i]); ?></h5>
                                    <div id="chart_nominal_divisi_bulan"></div>
                                </div>
                                <div class="col-xl-12 col-sm-12">
                                    <h5 class="text-center"><?php echo e($lable[++$i]); ?></h5>
                                    <div id="chart_jumlah_status_bulan"></div>
                                </div>
                                <div class="col-xl-12 col-sm-12">
                                    <h5 class="text-center"><?php echo e($lable[++$i]); ?></h5>
                                    <div id="chart_jumlah_perusahaan_status_bulan"></div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="detailModal" tabindex="-1">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div id="detailContent"></div>
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
<script src="<?php echo e(asset('assets/js/chart/apex-chart/apex-chart.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/chart/apex-chart/stock-prices.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/chart/apex-chart/chart-custom.js')); ?>"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript">
function toggle_perusahaan(source) {
    checkboxes = document.getElementsByName('perusahaan[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
function toggle_divisi(source) {
    checkboxes = document.getElementsByName('divisi[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
function toggle_status_karyawan(source) {
    checkboxes = document.getElementsByName('status_karyawan[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
// 
function showDetail(el) {
    const key = el.dataset.key;
    const rawData = JSON.parse(el.dataset.detail);
    const tableId = 'detailTable';
    const allDetail = Object.values(rawData).flatMap(item => item.detail || []);
    document.querySelector('#detailModal .modal-title').innerText = `Detail: ${key}`;
    let html = `
        <table id="${tableId}" class="table table-striped table-bordered w-100">
            <thead>
                <tr>
                    <th>NIP</th>
                    <th>Nama</th>
                </tr>
            </thead>
            <tbody>
    `;
    rawData.forEach(row => {
        html += `
            <tr>
                <td>${row.nik ?? ''}</td>
                <td>${row.nama ?? ''}</td>
            </tr>
        `;
    });
    html += `
            </tbody>
        </table>
    `;
    document.getElementById('detailContent').innerHTML = html;
    if ($.fn.DataTable.isDataTable('#' + tableId)) {
        $('#' + tableId).DataTable().destroy();
    }
    setTimeout(() => {
        $('#' + tableId).DataTable({
            order: [[1, 'asc']],
            dom:"<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
                "<'row'<'col-sm-12'rt>>" +
                "<'row'<'col-sm-5'i><'col-sm-7'p>>",
            buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
            lengthMenu: [[10, 50, 100, 200, 500, -1], [10, 50, 100, 200, 500, "All"]]
        });
    }, 100);
    const modal = new bootstrap.Modal(document.getElementById('detailModal'));
    modal.show();
}
// 
$('#tahun_sampai').datepicker({
    format: "yyyy",
    viewMode: "years",
    minViewMode: "years",
    autoclose: true
});
$('#tahun_dari').datepicker({
    format: "yyyy",
    viewMode: "years",
    minViewMode: "years",
    autoclose: true
});
// 
$(document).ready(function() {
    // 
    var id = <?php echo e($id); ?>;
    // 
    $('#form1').on('submit', function (e) {
        let form = $(this);
        let errors = [];
        let select = form.find($('#bulan_tahun_dari')).val();
        let select2 = form.find($('#bulan_tahun_sampai')).val();
        if (!select || !select2) {
            errors.push("Bulan Tahun is mandatory");
        }
        if (errors.length > 0) {
            e.preventDefault();
            alert(errors.join('\n'));
        }
    });
    // 
    function generateColors(count){
        let colors = [];
        for(let i=0;i<count;i++){
            let hue = Math.floor((360 / count) * i);
            colors.push(`hsl(${hue},70%,50%)`);
        }
        return colors;
    }
    // 
    function render_chart_v1(dataObj, containerId, chartType, height, satuan) {
        const categories = Object.keys(dataObj);
        const seriesKeys = [...new Set(Object.values(dataObj).flatMap(item => Object.keys(item)))].sort();
        // const series = seriesKeys.map(key => ({
        //     name: key,
        //     data: Object.values(dataObj).map(item => item[key].jumlah || 0)
        // })).filter(s => s.data.some(val => val > 0));
        const series = seriesKeys.map(key => ({
            name: key,
            data: Object.values(dataObj).map(item => item[key]?.jumlah || 0)
        })).filter(s => s.data.some(val => val > 0));
        // var maxValue = Math.max(...series.flatMap(s => s.data)) + 5;
        var maxValue = Math.ceil(Math.max(...series.flatMap(s => s.data)) / 10) * 10;
        if(satuan){
            maxValue = Math.ceil(Math.max(...series.flatMap(s => s.data)) / 10000000) * 10000000;
        }
        const options = {
            chart: {
                type: chartType,
                height: height,
                zoom: { enabled: false },
                events: chartType === 'bar' ? {
                    dataPointSelection: function(event, chartContext, config) {
                        const index = config.dataPointIndex;
                        const seriesIndex = config.seriesIndex;
                        // nama category
                        const monthKey = Object.keys(dataObj)[index];
                        // nama series
                        const seriesName = config.w.config.series[seriesIndex].name;
                        // ambil object data
                        const rawData = dataObj[monthKey]?.[seriesName];
                        // ambil detail
                        const details = rawData?.detail || [];
                        document.querySelector('#detailModal .modal-title').innerText =`Detail: ${seriesName} - ${monthKey}`;
                        const tableId = 'detailTable';
                        let html = `
                            <table id="${tableId}" class="table table-striped table-bordered w-100">
                                <thead>
                                    <tr>
                                        <th>NIP</th>
                                        <th>Nama</th>
                                        <th>Bulan Tahun</th>
                                        <th>Nominal</th>
                                    </tr>
                                </thead>
                                <tbody>
                        `;
                        details.forEach(row => {
                            html += `
                                <tr>
                                    <td>${row.nik ?? ''}</td>
                                    <td>${row.nama ?? ''}</td>
                                    <td>${row.bulan ?? ''}</td>
                                    <td class="text-end">${row.nominal ? new Intl.NumberFormat('en-US').format(row.nominal) : ''}</td>
                                </tr>
                            `;
                        });
                        html += `
                                </tbody>
                            </table>
                        `;
                        document.getElementById('detailContent').innerHTML = html;
                        // destroy datatable lama jika ada
                        if ($.fn.DataTable.isDataTable('#' + tableId)) {
                            $('#' + tableId).DataTable().destroy();
                        }
                        setTimeout(() => {
                            $('#' + tableId).DataTable({
                                order: [[1, 'asc']],
                                dom:
                                    "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
                                    "<'row'<'col-sm-12'rt>>" +
                                    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                                buttons: [
                                    'copy',
                                    'csv',
                                    'excel',
                                    'pdf',
                                    'print'
                                ],
                                lengthMenu: [
                                    [10, 50, 100, 200, 500, -1],
                                    [10, 50, 100, 200, 500, "All"]
                                ]
                            });
                        }, 100);
                        // tampilkan modal
                        const modal = new bootstrap.Modal(document.getElementById('detailModal'));
                        modal.show();
                    }
                } : {}
            },
            series: series,
            // colors: generateColors(data.length),
            // colors: [
            //     '#198754',
            //     '#dc3545',
            //     '#ffc107',
            // ],
            dataLabels: {
                // enabled: true,
                style: {
                    // fontSize: '10px',
                    colors: ['#000'],
                },
                formatter: function (val) {
                    return val > 0 ? new Intl.NumberFormat('en-US').format(val) : '';
                },
                // offsetY: -50,
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '95%',
                    borderRadius: 3,
                    borderRadiusApplication: 'end',
                }
            },
            xaxis: {
                categories: categories,
                // tickPlacement: chartType === 'line' ? 'between' : 'on',
                tickPlacement: 'between',
            },
            yaxis: {
                min: 0,
                max: maxValue,
                // tickAmount: Math.ceil(maxValue / 10000000),
                title: { text: 'Jumlah' },
                labels: {
                    formatter: function (val) {
                        return new Intl.NumberFormat('en-US').format(val);
                    }
                }
            },
            legend: { position: 'top' },
            fill: { opacity: 0.8 },
        };
        if (chartType === 'line') {
            options.stroke = {
                curve: 'smooth',
                width: 3
            };
            // options.markers = { size: 5 };
        }
        const chart = new ApexCharts(document.querySelector(`#${containerId}`), options);
        chart.render();
    }
    // 
    function render_chart_v2(dataObj, containerId, chartType, height, satuan) {
        const categories = Object.keys(dataObj);

        // Buat series keys dari kombinasi perusahaan - status
        const seriesKeys = [...new Set(
            Object.values(dataObj).flatMap(bulan =>
                Object.entries(bulan).flatMap(([perusahaan, statuses]) =>
                    Object.keys(statuses).map(status => `${perusahaan} - ${status}`)
                )
            )
        )].sort();

        const series = seriesKeys.map(key => ({
            name: key,
            data: Object.values(dataObj).map(bulan => {
                const [perusahaan, ...statusParts] = key.split(' - ');
                const status = statusParts.join(' - ');
                return bulan[perusahaan]?.[status]?.jumlah || 0;
            })
        })).filter(s => s.data.some(val => val > 0));

        // var maxValue = Math.max(...series.flatMap(s => s.data)) + 5;
        var maxValue = Math.ceil(Math.max(...series.flatMap(s => s.data)) / 10) * 10;
        if(satuan){
            maxValue = Math.ceil(Math.max(...series.flatMap(s => s.data)) / 10000000) * 10000000;
        }

        const options = {
            chart: {
                type: chartType,
                height: height,
                zoom: { enabled: false },
                events: chartType === 'bar' ? {
                    dataPointSelection: function(event, chartContext, config) {
                        const index       = config.dataPointIndex;
                        const seriesIndex = config.seriesIndex;
                        const monthKey    = Object.keys(dataObj)[index];
                        const seriesName  = config.w.config.series[seriesIndex].name;

                        const [perusahaan, ...statusParts] = seriesName.split(' - ');
                        const status = statusParts.join(' - ');

                        const rawData = dataObj[monthKey]?.[perusahaan]?.[status];
                        const details = rawData?.detail || [];

                        document.querySelector('#detailModal .modal-title').innerText = `Detail: ${seriesName} - ${monthKey}`;
                        const tableId = 'detailTable';
                        let html = `
                            <table id="${tableId}" class="table table-striped table-bordered w-100">
                                <thead>
                                    <tr>
                                        <th>NIP</th>
                                        <th>Nama</th>
                                    </tr>
                                </thead>
                                <tbody>
                        `;
                        details.forEach(row => {
                            html += `
                                <tr>
                                    <td>${row.nik ?? ''}</td>
                                    <td>${row.nama ?? ''}</td>
                                </tr>
                            `;
                        });
                        html += `</tbody></table>`;

                        document.getElementById('detailContent').innerHTML = html;

                        if ($.fn.DataTable.isDataTable('#' + tableId)) {
                            $('#' + tableId).DataTable().destroy();
                        }
                        setTimeout(() => {
                            $('#' + tableId).DataTable({
                                order: [[1, 'asc']],
                                dom:
                                    "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
                                    "<'row'<'col-sm-12'rt>>" +
                                    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                                buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
                                lengthMenu: [
                                    [10, 50, 100, 200, 500, -1],
                                    [10, 50, 100, 200, 500, "All"]
                                ]
                            });
                        }, 100);

                        const modal = new bootstrap.Modal(document.getElementById('detailModal'));
                        modal.show();
                    }
                } : {}
            },
            series: series,
            dataLabels: {
                // enabled: true,
                style: {
                    // fontSize: '10px',
                    colors: ['#000'],
                },
                formatter: function (val) {
                    return val > 0 ? new Intl.NumberFormat('en-US').format(val) : '';
                },
                // offsetY: -50,
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '95%',
                    borderRadius: 3,
                    borderRadiusApplication: 'end',
                }
            },
            xaxis: {
                categories: categories,
                tickPlacement: 'between',
            },
            yaxis: {
                min: 0,
                max: maxValue,
                title: { text: 'Jumlah' },
                labels: {
                    formatter: function (val) {
                        return new Intl.NumberFormat('en-US').format(val);
                    }
                }
            },
            legend: { position: 'top' },
            fill: { opacity: 0.8 },
        };

        if (chartType === 'line') {
            options.stroke = {
                curve: 'smooth',
                width: 3
            };
        }

        const chart = new ApexCharts(document.querySelector(`#${containerId}`), options);
        chart.render();
    }
    // history
    render_chart_v1(<?php echo json_encode($nominal_perusahaan_tahun, 15, 512) ?>, 'chart_nominal_perusahaan_tahun', '<?php echo e($type); ?>', 300, true);
    render_chart_v1(<?php echo json_encode($nominal_perusahaan_bulan, 15, 512) ?>, 'chart_nominal_perusahaan_bulan', '<?php echo e($type); ?>', 300, true);
    render_chart_v1(<?php echo json_encode($nominal_status_bulan, 15, 512) ?>, 'chart_nominal_status_bulan', '<?php echo e($type); ?>', 300, true);
    render_chart_v1(<?php echo json_encode($nominal_divisi_bulan, 15, 512) ?>, 'chart_nominal_divisi_bulan', '<?php echo e($type); ?>', 300, true);
    render_chart_v1(<?php echo json_encode($jumlah_status_bulan, 15, 512) ?>, 'chart_jumlah_status_bulan', '<?php echo e($type); ?>', 300, false);
    render_chart_v2(<?php echo json_encode($jumlah_perusahaan_status_bulan, 15, 512) ?>, 'chart_jumlah_perusahaan_status_bulan', '<?php echo e($type); ?>', 300, false);
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/overtime/dashboard.blade.php ENDPATH**/ ?>