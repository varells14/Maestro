@extends('layouts.app')

@section('extra_css')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
<style>
    :root {
        --primary: #4361ee;
        --success: #2ec4b6;
        --warning: #ff9f1c;
        --info: #3a86ff;
        --danger: #e71d36;
        --light: #f8f9fa;
        --dark: #212529;
    }

    body {
        background-color: #f8f9fa;
        font-family: 'Poppins', sans-serif;
    }

    .dashboard-container {
        padding: 1.5rem;
    }

    .dashboard-title {
        color: var(--dark);
        font-weight: 700;
        margin-bottom: 1.5rem;
        font-size: 1.75rem;
    }

    .stat-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 10px 20px rgba(0,0,0,0.05);
        overflow: hidden;
        transition: all 0.3s ease;
        height: 100%;
        border: none;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1);
    }

    .stat-card-body {
        padding: 1.5rem;
        position: relative;
    }

    .stat-card-icon {
        position: absolute;
        top: 1.5rem;
        right: 1.5rem;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        color: white;
    }

    .stat-card-value {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        line-height: 1;
    }

    .stat-card-title {
        font-size: 1rem;
        color: #6c757d;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .stat-primary .stat-card-icon {
        background: linear-gradient(135deg, var(--primary), #5e60ce);
    }

    .stat-primary .stat-card-value {
        color: var(--primary);
    }

    .stat-success .stat-card-icon {
        background: linear-gradient(135deg, var(--success), #20a39e);
    }

    .stat-success .stat-card-value {
        color: var(--success);
    }

    .stat-warning .stat-card-icon {
        background: linear-gradient(135deg, var(--warning), #f77f00);
    }

    .stat-warning .stat-card-value {
        color: var(--warning);
    }

    .stat-info .stat-card-icon {
        background: linear-gradient(135deg, var(--info), #4895ef);
    }

    .stat-info .stat-card-value {
        color: var(--info);
    }

    .chart-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 10px 20px rgba(0,0,0,0.05);
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .chart-card:hover {
        box-shadow: 0 15px 30px rgba(0,0,0,0.1);
    }

    .chart-card-header {
        padding: 1.5rem;
        border-bottom: 1px solid rgba(0,0,0,0.05);
        margin-bottom: 1rem; /* Menambahkan margin bottom untuk memberi jarak */
    }

    .chart-card-title {
        margin: 0;
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--dark);
    }

    .chart-card-body {
        padding: 1.5rem;
    }

    .chart-container {
        position: relative;
        height: 380px;
        margin: 0 auto;
    }
    
    /* Custom SVG icons */
    .dashboard-icon {
        width: 24px;
        height: 24px;
        display: inline-block;
        vertical-align: middle;
        margin-right: 8px;
        fill: currentColor;
    }
    
    .dashboard-icon-lg {
        width: 32px;
        height: 32px;
    }
    
    /* Menambahkan styling untuk container utama */
    .container-fluid.my-38 {
        padding-top: 1.5rem;
        padding-bottom: 2rem;
    }
    
    /* Menambahkan jarak antara card header dashboard dan card sections */
    .card-header.bg-primary {
        margin-bottom: 1.5rem;
    }
    
    /* Memberikan padding yang lebih baik untuk konten */
    .card.shadow {
        padding: 1rem;
    }
    
    /* Memperbaiki jarak antar baris */
    .row.mb-4 {
        margin-bottom: 2rem !important;
    }
</style>
@endsection

@section('content')
<div class="container-fluid my-38">
    <div class="card shadow">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3">
            <h3 class="m-0 font-weight-bold">Dashboard</h3>
            <div class="d-flex align-items-center">
            <button class="btn btn-light me-2" data-bs-toggle="modal" data-bs-target="#addProjectModal">
                <i class="fas fa-plus"></i>  New Project
            </button>
           
            </div>
        </div>

        <div class="card-body py-4"> <!-- Menambahkan card-body dengan padding -->
            <!-- Overview Cards -->
            <div class="row mb-4 g-4">
            <a href="{{ route('user.material_request') }}" class="text-decoration-none">
                <div class="col-xl-3 col-md-6">
                    <div class="stat-card stat-info">
                        <div class="stat-card-body">
                            <div class="stat-card-icon">
                                <!-- Custom Clipboard SVG Icon -->
                                <svg viewBox="0 0 24 24" width="32" height="32" fill="currentColor">
                                    <path d="M19,3h-4.18C14.4,1.84,13.3,1,12,1S9.6,1.84,9.18,3H5C3.9,3,3,3.9,3,5v14c0,1.1,0.9,2,2,2h14c1.1,0,2-0.9,2-2V5 C21,3.9,20.1,3,19,3z M12,3c0.55,0,1,0.45,1,1s-0.45,1-1,1s-1-0.45-1-1S11.45,3,12,3z M19,19H5V5h2v2h10V5h2V19z"/>
                                    <path d="M8,10h8v1H8V10z M8,12h8v1H8V12z M8,14h5v1H8V14z"/>
                                </svg>
                            </div>
                            <div class="stat-card-value">{{ $totalMaterialRequests }}</div>
                            <div class="stat-card-title">Material Requests</div>
                        </div>
                    </div>
                    </a>
                </div>


                <div class="col-xl-3 col-md-6">
                    <a href="{{ route('user.purchase_request') }}" class="text-decoration-none">
                        <div class="stat-card stat-primary">
                            <div class="stat-card-body">
                                <div class="stat-card-icon">
                                    <!-- Custom Clipboard SVG Icon -->
                                    <svg viewBox="0 0 24 24" width="32" height="32" fill="currentColor">
                                        <path d="M19,3H5C3.9,3,3,3.9,3,5v14c0,1.1,0.9,2,2,2h14c1.1,0,2-0.9,2-2V5C21,3.9,20.1,3,19,3z M19,19H5V5h14V19z"/>
                                        <path d="M12,12.5c-0.28,0-0.5-0.22-0.5-0.5V8c0-0.28,0.22-0.5,0.5-0.5s0.5,0.22,0.5,0.5v4C12.5,12.28,12.28,12.5,12,12.5z"/>
                                        <path d="M14,14.5H10c-0.28,0-0.5-0.22-0.5-0.5s0.22-0.5,0.5-0.5h4c0.28,0,0.5,0.22,0.5,0.5S14.28,14.5,14,14.5z"/>
                                        <path d="M8,16.5c-0.28,0-0.5-0.22-0.5-0.5v-4c0-0.28,0.22-0.5,0.5-0.5s0.5,0.22,0.5,0.5v4C8.5,16.28,8.28,16.5,8,16.5z"/>
                                        <path d="M16,16.5c-0.28,0-0.5-0.22-0.5-0.5v-4c0-0.28,0.22-0.5,0.5-0.5s0.5,0.22,0.5,0.5v4C16.5,16.28,16.28,16.5,16,16.5z"/>
                                    </svg>
                                </div>
                                <div class="stat-card-value">{{ $totalPurchaseOrders }}</div>
                                <div class="stat-card-title">Purchase Orders</div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-xl-3 col-md-6">
                <a href="{{ route('stock-ins.index') }}" class="text-decoration-none">
                    <div class="stat-card stat-success">
                        <div class="stat-card-body">
                            <div class="stat-card-icon">
                                <!-- Custom Arrow Down SVG Icon -->
                                <svg viewBox="0 0 24 24" width="32" height="32" fill="currentColor">
                                    <path d="M12,4c0.55,0,1,0.45,1,1v12.17l5.59-5.59c0.39-0.39,1.02-0.39,1.41,0c0.39,0.39,0.39,1.02,0,1.41 l-7.29,7.29c-0.39,0.39-1.02,0.39-1.41,0l-7.3-7.29c-0.39-0.39-0.39-1.02,0-1.41c0.39-0.39,1.02-0.39,1.41,0L11,17.17V5 C11,4.45,11.45,4,12,4z"/>
                                </svg>
                            </div>
                            <div class="stat-card-value">{{ $totalStockins }}</div>
                            <div class="stat-card-title">Total Stock Ins</div>
                        </div>
                    </div>
                    </a>
                </div>

                <div class="col-xl-3 col-md-6">
                    <a href="{{ route('stock-outs.index') }}" class="text-decoration-none">
                    <div class="stat-card stat-warning">
                        <div class="stat-card-body">
                            <div class="stat-card-icon">
                                <!-- Custom Arrow Up SVG Icon -->
                                <svg viewBox="0 0 24 24" width="32" height="32" fill="currentColor">
                                    <path d="M12,20c-0.55,0-1-0.45-1-1V6.83l-5.59,5.59c-0.39,0.39-1.02,0.39-1.41,0c-0.39-0.39-0.39-1.02,0-1.41 l7.29-7.29c0.39-0.39,1.02-0.39,1.41,0l7.3,7.29c0.39,0.39,0.39,1.02,0,1.41c-0.39,0.39-1.02,0.39-1.41,0L13,6.83V19 C13,19.55,12.55,20,12,20z"/>
                                </svg>
                            </div>
                            <div class="stat-card-value">{{ $totalStockouts }}</div>
                            <div class="stat-card-title">Total Stock Outs</div>
                        </div>
                    </div>
                </div>
            </a>
            </div>

            <!-- Low Stock Materials Chart -->
            <div class="row">
                <div class="col-12">
                    <div class="chart-card mb-4">
                        <div class="chart-card-header">
                            <h5 class="chart-card-title">
                                <!-- Custom Warning Triangle SVG Icon -->
                                <svg class="dashboard-icon" viewBox="0 0 24 24" fill="var(--warning)">
                                    <path d="M12,1.5 C12.5,1.5 13,1.75 13.26,2.15 L23.39,19.96 C23.92,20.87 23.28,22 22.23,22 L1.77,22 C0.72,22 0.08,20.87 0.61,19.96 L10.74,2.15 C11,1.75 11.5,1.5 12,1.5 z M12,6.6 C11.61,6.6 11.3,6.91 11.3,7.3 L11.3,15.3 C11.3,15.69 11.61,16 12,16 C12.39,16 12.7,15.69 12.7,15.3 L12.7,7.3 C12.7,6.91 12.39,6.6 12,6.6 z M12,19.3 C12.83,19.3 13.5,18.63 13.5,17.8 C13.5,16.97 12.83,16.3 12,16.3 C11.17,16.3 10.5,16.97 10.5,17.8 C10.5,18.63 11.17,19.3 12,19.3 z"/>
                                </svg>
                                Low Stock Materials
                            </h5>
                        </div>
                        <div class="chart-card-body">
                            <div class="chart-container">
                                <canvas id="lowStockChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- Akhir card-body -->
    </div>
</div>

<!-- Add Project Modal -->
<div class="modal fade" id="addProjectModal" tabindex="-1" aria-labelledby="addProductLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form class="modal-content" action="{{ route('user.project.store') }}" method="POST">
            @csrf
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="addProductLabel"><i class="fas fa-plus-circle"></i> Add Project</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{-- Section: List Project --}}
                <h6 class="fw-bold">List of Latest Projects</h6>
                <div class="table-responsive mb-4">
                    <table class="table table-bordered table-striped">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Project</th>
                                <th>Lokasi</th>
                                <th>Start Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($projects as $index => $project)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $project->project }}</td>
                                    <td>{{ $project->lokasi }}</td>
                                    <td>{{ \Carbon\Carbon::parse($project->date_start)->format('d M Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No projects found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Section: Form --}}
                <h6 class="fw-bold">Add New Project</h6>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="project" class="form-label">Project Name</label>
                        <input type="text" name="project" id="project" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label for="lokasi" class="form-label">Location</label>
                        <input type="text" name="lokasi" id="lokasi" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label for="date_start" class="form-label">Start Date</label>
                        <input type="date" name="date_start" id="date_start" class="form-control" required>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('extra_js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Low Stock Materials Chart
        var lowStockCtx = document.getElementById('lowStockChart').getContext('2d');
        
        // Get data from your Laravel variables
        var lowStockLabels = [@foreach($lowStockProducts as $product) '{{ $product->name }}', @endforeach];
        var lowStockData = [@foreach($lowStockProducts as $product) {{ $product->stock }}, @endforeach];
        var lowStockCategories = [@foreach($lowStockProducts as $product) '{{ $product->category->name ?? "Uncategorized" }}', @endforeach];
        
        // Generate background colors based on categories
        var backgroundColors = lowStockCategories.map(function(category) {
            switch(category) {
                case 'Building Materials': return 'rgba(67, 97, 238, 0.8)';
                case 'Tools & Equipment': return 'rgba(46, 196, 182, 0.8)';
                case 'Electrical': return 'rgba(58, 134, 255, 0.8)';
                case 'Plumbing & Sanitary': return 'rgba(255, 159, 28, 0.8)';
                default: return 'rgba(231, 29, 54, 0.8)';
            }
        });
        
        var lowStockChart = new Chart(lowStockCtx, {
            type: 'bar',
            data: {
                labels: lowStockLabels,
                datasets: [{
                    label: 'Current Stock',
                    data: lowStockData,
                    backgroundColor: backgroundColors,
                    borderColor: backgroundColors.map(color => color.replace('0.8', '1')),
                    borderWidth: 1,
                    borderRadius: 6,
                    maxBarThickness: 25
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: "white",
                        titleColor: '#212529',
                        bodyColor: "#6c757d",
                        titleFont: {
                            size: 14,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 13
                        },
                        padding: 12,
                        boxWidth: 10,
                        boxHeight: 10,
                        boxPadding: 3,
                        usePointStyle: true,
                        borderColor: 'rgba(0,0,0,0.1)',
                        borderWidth: 1,
                        callbacks: {
                            title: function(tooltipItems) {
                                return tooltipItems[0].label;
                            },
                            label: function(context) {
                                return 'Stock: ' + context.raw;
                            },
                            afterLabel: function(context) {
                                return 'Category: ' + lowStockCategories[context.dataIndex];
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        grid: {
                            display: true,
                            color: 'rgba(0, 0, 0, 0.05)',
                            drawBorder: false
                        },
                        ticks: {
                            color: '#6c757d'
                        }
                    },
                    y: {
                        grid: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            color: '#6c757d',
                            font: {
                                weight: '500'
                            }
                        }
                    }
                },
                animation: {
                    duration: 2000,
                    easing: 'easeOutQuart'
                }
            }
        });
    });
</script>
@endsection