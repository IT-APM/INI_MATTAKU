@extends('layouts.simple.master')

@section('title', 'Home')

@section('css')
    {{--  --}}
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
    <h3>Home</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Dashboard</li>
    <li class="breadcrumb-item active">Home</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <h5>Status Karyawan</h5>
                    </div>
                    <div id="chart_status"></div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <h5>Penempatan Perusahaan</h5>
                    </div>
                    <div id="chart_perusahaan"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <h5>Gender</h5>
                    </div>
                    <div id="chart_kelamin"></div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <h5>Level</h5>
                    </div>
                    <div id="chart_level"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        // status
        var options_status = {
            series: [{
                name: 'Percentage: ',
                data: {{ \Illuminate\Support\Js::from($jumlah_status) }}
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
            colors: ['#FFA500'],
            dataLabels: {
                enabled: true,
                offsetY: -20,
                style: {
                    colors: ["#FFA500"]
                },
                formatter: function(val) {
                    return val + "%";
                },
            },
            xaxis: {
                categories: {{ \Illuminate\Support\Js::from($status) }},
                title: {
                    text: 'Status',
                    offsetY: 80,
                }
            },
            yaxis: {
                title: {
                    text: 'Persentase Karyawan',
                },
                min: 0,
                max: 100
            },
        };
        var chart_status = new ApexCharts(document.querySelector("#chart_status"), options_status);
        chart_status.render();
        // perusahaan
        var options_perusahaan = {
            series: {{ \Illuminate\Support\Js::from($jumlah_perusahaan) }},
            chart: {
                width: '100%',
                type: 'donut',
            },
            plotOptions: {
                labels: {
                            show: true,
                        }
            },
            labels: {{ \Illuminate\Support\Js::from($perusahaan) }},
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: 200
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }]
        };
        var chart_perusahaan = new ApexCharts(document.querySelector("#chart_perusahaan"), options_perusahaan);
        chart_perusahaan.render();
        // kelamin
        var options_kelamin = {
            series: {{ \Illuminate\Support\Js::from($jumlah_kelamin) }},
            chart: {
                width: '75%',
                type: 'pie',
            },
            plotOptions: {
                labels: {
                            show: true,
                        }
            },
            labels: {{ \Illuminate\Support\Js::from($kelamin) }},
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: 200
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }]
        };
        var chart_kelamin = new ApexCharts(document.querySelector("#chart_kelamin"), options_kelamin);
        chart_kelamin.render();
        // level
        var options_level = {
            series: {{ \Illuminate\Support\Js::from($jumlah_level) }},
            chart: {
                width: 650,
                type: 'pie',
            },
            plotOptions: {
                labels: {
                            show: true,
                        }
            },
            labels: {{ \Illuminate\Support\Js::from($level) }},
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: 200
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }]
        };
        var chart_level = new ApexCharts(document.querySelector("#chart_level"), options_level);
        chart_level.render();
    </script>
    <script src="{{ asset('assets/js/chart/apex-chart/apex-chart.js') }}"></script>
    <script src="{{ asset('assets/js/chart/apex-chart/stock-prices.js') }}"></script>
    <script src="{{ asset('assets/js/chart/apex-chart/chart-custom.js') }}"></script>
@endsection
