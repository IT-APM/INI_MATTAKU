@extends('layouts.simple.master')

@section('title', 'Home')

@section('css')
    {{--  --}}
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
    <h3>Dashboard</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Home</li>
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <h5>Jumlah Karyawan</h5>
                        <button class="btn btn-warning" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_filter" aria-expanded="false" aria-controls="collapse_filter"><i class="fa fa-search" aria-hidden="true"></i> Filter</button>
                        <div class="collapse" id="collapse_filter">
                        <div class="row">
                            <div class="col-sm-6">
                                <label class="h6">Perusahaan</label>
                                <label class="d-block" for="perusahaan">
                                    @foreach ($perusahaan as $data)
                                        <input class="checkbox_animated" id="perusahaan" name="perusahaan" type="checkbox" value="{{ $data->id }}"> {{ $data->nama }}<br />
                                    @endforeach
                                </label>
                            </div>
                            <div class="col-sm-6">
                                <label class="h6">Aktif</label>
                                <label class="d-block" for="aktif">
                                    <input class="checkbox_animated" id="aktif" name="aktif" type="checkbox" value="0"> Non Aktif<br />
                                    <input class="checkbox_animated" id="aktif" name="aktif" type="checkbox" value="1"> Aktif<br />
                                </label>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-sm-12">
                                <button class="btn btn-warning" id="getKaryawanPerusahaan" name="getKaryawanPerusahaan"><i class="fa fa-search" aria-hidden="true"></i> Filter</button> | <button class="btn btn-info" id="reset" name="reset" value="true"><i class="fa fa-refresh" aria-hidden="true"></i> Reset</button>
                            </div>
                        </div>
                        {{-- </div> --}}
                        {{-- <h5>Karyawan Aktif: {{ $total_aktif }}</h5> --}}
                    </div>
                    <div id="karyawan_aktif" style="height:400px;"></div>
                </div>
            </div>
            {{-- <div class="col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <h5>Karyawan Non Aktif: {{ $total_nonaktif }}</h5>
                    </div>
                    <div id="karyawan_nonaktif" style="height:400px;"></div>
                </div>
            </div> --}}
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <h5>Pelatihan, Seminar, Assesment, Sertifikasi: {{ $total_pelatihan }}</h5>
                    </div>
                    <div id="chart_pelatihan" style="height:400px;"></div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <h5>Jenis Kelamin Karyawan Aktif: {{ $total_kelamin }}</h5>
                    </div>
                    <div id="chart_kelamin" style="height:400px;"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <h5>Karyawan Non Aktif Tahun {{ date('Y') - 1 }}: {{ $total_karyawannonaktif_1 }}</h5>
                    </div>
                    <div id="chart_karyawannonaktif_1" style="height:400px;"></div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <h5>Karyawan Non Aktif Tahun {{ date('Y') }}: {{ $total_karyawannonaktif }}</h5>
                    </div>
                    <div id="chart_karyawannonaktif" style="height:400px;"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#getKaryawanPerusahaan').click(function() {
                $("#karyawan_aktif").empty();
                event.preventDefault();
                var perusahaan = [];
                $('input[id="perusahaan"]:checked').each(function() {
                    perusahaan.push(this.value);
                });
                var aktif = [];
                $('input[id="aktif"]:checked').each(function() {
                    aktif.push(this.value);
                });
                $.ajax({
                    url: "{{ route('getKaryawanPerusahaan','perusahaan="+perusahaan+"&aktif="+aktif"') }}",
                    type: 'get',
                    dataType: 'json',
                    success: function(response) {
                        var perusahaan = response['perusahaan'];
                        // karyawan_aktif
                        var karyawan_aktif = {
                            series: [{
                                name: 'Jumlah: ',
                                data: {{ \Illuminate\Support\Js::from($karyawan_aktif) }}
                            }],
                            chart: {
                                type: 'bar',
                                height: 350,
                            },
                            plotOptions: {
                                bar: {
                                    borderRadius: 4,
                                    columnWidth: '50%',
                                    borderRadiusApplication: 'end',
                                    horizontal: false,
                                    dataLabels: {
                                        position: 'top', // top, center, bottom
                                    },
                                }
                            },
                            colors: ['#f57b20'],
                            dataLabels: {
                                enabled: true,
                                offsetY: -20,
                                style: {
                                    colors: ["#f57b20"]
                                },
                                formatter: function(val) {
                                    return val;
                                },
                            },
                            xaxis: {
                                categories: {{ \Illuminate\Support\Js::from($perusahaan_aktif) }},
                                // title: {
                                //     text: 'Perusahaan',
                                //     offsetY: 0,
                                // }
                            },
                            yaxis: {
                                title: {
                                    text: 'Jumlah',
                                },
                                min: 0,
                                max: 200
                            },
                        };
                        new ApexCharts(document.querySelector("#karyawan_aktif"),
                            karyawan_aktif).render();
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            });
        });
        // karyawan_aktif
        var karyawan_aktif = {
            series: [{
                name: 'Jumlah: ',
                data: {{ \Illuminate\Support\Js::from($karyawan_aktif) }}
            }],
            chart: {
                type: 'bar',
                height: 350,
            },
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    columnWidth: '50%',
                    borderRadiusApplication: 'end',
                    horizontal: false,
                    dataLabels: {
                        position: 'top', // top, center, bottom
                    },
                }
            },
            colors: ['#f57b20'],
            dataLabels: {
                enabled: true,
                offsetY: -20,
                style: {
                    colors: ["#f57b20"]
                },
                formatter: function(val) {
                    return val;
                },
            },
            xaxis: {
                categories: {{ \Illuminate\Support\Js::from($perusahaan_aktif) }},
                // title: {
                //     text: 'Perusahaan',
                //     offsetY: 0,
                // }
            },
            yaxis: {
                title: {
                    text: 'Jumlah',
                },
                min: 0,
                max: 200
            },
        };
        new ApexCharts(document.querySelector("#karyawan_aktif"), karyawan_aktif).render();
        // karyawan_nonaktif
        var karyawan_nonaktif = {
            series: [{
                name: 'Jumlah: ',
                data: {{ \Illuminate\Support\Js::from($karyawan_nonaktif) }}
            }],
            chart: {
                type: 'bar',
                height: 350,
            },
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    columnWidth: '50%',
                    borderRadiusApplication: 'end',
                    horizontal: false,
                    dataLabels: {
                        position: 'top', // top, center, bottom
                    },
                }
            },
            colors: ['#f57b20'],
            dataLabels: {
                enabled: true,
                offsetY: -20,
                style: {
                    colors: ["#f57b20"]
                },
                formatter: function(val) {
                    return val;
                },
            },
            xaxis: {
                categories: {{ \Illuminate\Support\Js::from($perusahaan_nonaktif) }},
                // title: {
                //     text: 'Perusahaan',
                //     offsetY: 0,
                // }
            },
            yaxis: {
                title: {
                    text: 'Jumlah',
                },
                min: 0,
                max: 400
            },
        };
        new ApexCharts(document.querySelector("#karyawan_nonaktif"), karyawan_nonaktif).render();
        // pelatihan
        var pelatihan = {
            series: [{
                name: 'Jumlah: ',
                data: {{ \Illuminate\Support\Js::from($jumlah_pelatihan) }}
            }],
            chart: {
                type: 'bar',
                height: 350,
            },
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    columnWidth: '50%',
                    borderRadiusApplication: 'end',
                    horizontal: false,
                    dataLabels: {
                        position: 'top', // top, center, bottom
                    },
                }
            },
            colors: ['#f57b20'],
            dataLabels: {
                enabled: true,
                offsetY: -20,
                style: {
                    colors: ["#f57b20"]
                },
                formatter: function(val) {
                    return val;
                },
            },
            xaxis: {
                categories: {{ \Illuminate\Support\Js::from($tahun_pelatihan) }},
                // title: {
                //     text: 'Tahun',
                //     offsetY: 0,
                // }
            },
            yaxis: {
                title: {
                    text: 'Jumlah',
                },
                min: 0,
                max: 50
            },
        };
        new ApexCharts(document.querySelector("#chart_pelatihan"), pelatihan).render();
        // kelamin
        var kelamin = {
            series: [{
                name: 'Jumlah: ',
                data: {{ \Illuminate\Support\Js::from($jumlah_kelamin) }}
            }],
            chart: {
                type: 'bar',
                height: 350,
            },
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    columnWidth: '50%',
                    borderRadiusApplication: 'end',
                    horizontal: false,
                    dataLabels: {
                        position: 'top', // top, center, bottom
                    },
                }
            },
            colors: ['#f57b20'],
            dataLabels: {
                enabled: true,
                offsetY: -20,
                style: {
                    colors: ["#f57b20"]
                },
                formatter: function(val) {
                    return val;
                },
            },
            xaxis: {
                categories: {{ \Illuminate\Support\Js::from($kelamin) }},
                // title: {
                //     text: 'Tahun',
                //     offsetY: 0,
                // }
            },
            yaxis: {
                title: {
                    text: 'Jumlah',
                },
                min: 0,
                max: 400
            },
        };
        new ApexCharts(document.querySelector("#chart_kelamin"), kelamin).render();
        // karyawannonaktif
        var karyawannonaktif = {
            series: [{
                name: 'Jumlah: ',
                data: {{ \Illuminate\Support\Js::from($jumlah_karyawannonaktif) }}
            }],
            chart: {
                type: 'bar',
                height: 350,
            },
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    columnWidth: '50%',
                    borderRadiusApplication: 'end',
                    horizontal: false,
                    dataLabels: {
                        position: 'top', // top, center, bottom
                    },
                }
            },
            colors: ['#f57b20'],
            dataLabels: {
                enabled: true,
                offsetY: -20,
                style: {
                    colors: ["#f57b20"]
                },
                formatter: function(val) {
                    return val;
                },
            },
            xaxis: {
                categories: {{ \Illuminate\Support\Js::from($bulan_karyawannonaktif) }},
                // title: {
                //     text: 'Tahun',
                //     offsetY: 0,
                // }
            },
            yaxis: {
                title: {
                    text: 'Jumlah',
                },
                min: 0,
                max: 20
            },
        };
        new ApexCharts(document.querySelector("#chart_karyawannonaktif"), karyawannonaktif).render();
        // karyawannonaktif_1
        var karyawannonaktif_1 = {
            series: [{
                name: 'Jumlah: ',
                data: {{ \Illuminate\Support\Js::from($jumlah_karyawannonaktif_1) }},
            }],
            chart: {
                type: 'bar',
                height: 350,
            },
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    columnWidth: '50%',
                    borderRadiusApplication: 'end',
                    horizontal: false,
                    dataLabels: {
                        position: 'top', // top, center, bottom
                    },
                }
            },
            colors: ['#f57b20'],
            dataLabels: {
                enabled: true,
                offsetY: -20,
                style: {
                    colors: ["#f57b20"]
                },
                formatter: function(val) {
                    return val;
                },
            },
            xaxis: {
                categories: {{ \Illuminate\Support\Js::from($bulan_karyawannonaktif_1) }},
                // title: {
                //     text: 'Tahun',
                //     offsetY: 0,
                // }
            },
            yaxis: {
                title: {
                    text: 'Jumlah',
                },
                min: 0,
                max: 60
            },
        };
        new ApexCharts(document.querySelector("#chart_karyawannonaktif_1"), karyawannonaktif_1).render();
        // 
    </script>
    <script src="{{ asset('assets/js/chart/apex-chart/apex-chart.js') }}"></script>
    <script src="{{ asset('assets/js/chart/apex-chart/stock-prices.js') }}"></script>
    <script src="{{ asset('assets/js/chart/apex-chart/chart-custom.js') }}"></script>
@endsection
