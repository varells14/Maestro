@extends('layouts.app')

@section('extra_css')
    <link rel="stylesheet" href="{{ asset('path/to/your/chart.css') }}">
@endsection

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb my-0">
            <li class="breadcrumb-item active"><span>Home</span></li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="container-lg px-3">
        <div class="row mb-4">
            <!-- Welcome Card -->
            <div class="col-xl-12 col-md-12 mb-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h4>Selamat Datang Di Sistem Permohonan Izin</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-4">
            <!-- New User Card Example: Izin Keluar Kantor -->
            <div class="col-12 col-sm-6 col-xl-4 col-xxl-3 mb-4">
                <div class="card text-white bg-primary">
                    <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                        <div>
                            <div class="fs-4 fw-semibold">Izin Pulang Cepat <span class="fs-6 fw-normal">
                                <i class="fas fa-fw fa-book-open fa-2x text-info" style="margin-top: 18px;"></i>
                            </span></div>
                            <div class="h6 mb-0 mr-3 font-weight-bold text-gray-800"> Pending</div>
                        </div>
                    </div>
                    <div class="c-chart-wrapper mt-3 mx-3" style="height:70px;">
                        <canvas class="chart" id="card-chart1" height="70" width="276"></canvas>
                    </div>
                </div>
            </div>

            <!-- New User Card Example: Izin Pulang Cepat -->
            <div class="col-12 col-sm-6 col-xl-4 col-xxl-3 mb-4">
                <div class="card text-white bg-primary">
                    <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                        <div>
                            <div class="fs-4 fw-semibold">Izin Keluar Kantor <span class="fs-6 fw-normal">
                                <i class="fas fa-fw fa-book-open fa-2x text-info" style="margin-top: 18px;"></i>
                            </span></div>
                            <div class="h6 mb-0 mr-3 font-weight-bold text-gray-800"> Pending</div>
                        </div>
                    </div>
                    <div class="c-chart-wrapper mt-3 mx-3" style="height:70px;">
                        <canvas class="chart" id="card-chart2" height="70" width="276"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('extra_js')
    <script src="{{ asset('path/to/your/chart.js') }}"></script>
    <script>
        var ctx1 = document.getElementById('card-chart1').getContext('2d');
        var izinKeluarChart = new Chart(ctx1, {
            type: 'line', // Example chart type
            data: {
                labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
                datasets: [{
                    label: 'Izin Keluar',
                    data: [10, 20, 30, 40, 50, 60, 70],
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        var ctx2 = document.getElementById('card-chart2').getContext('2d');
        var izinPulangCepatChart = new Chart(ctx2, {
            type: 'line', // Example chart type
            data: {
                labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
                datasets: [{
                    label: 'Izin Pulang Cepat',
                    data: [5, 15, 25, 35, 45, 55, 65],
                    backgroundColor: 'rgba(255, 99, 132, 0.6)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endsection
