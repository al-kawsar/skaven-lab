@extends('layouts.app-layout')
@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-sub-header">
                    <h3 class="page-title">Statistik Siswa</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('student.index') }}">Siswa</a></li>
                        <li class="breadcrumb-item active">Statistik</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row">
        <div class="col-xl-3 col-sm-6 col-12 d-flex">
            <div class="card bg-comman w-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="db-widgets d-flex justify-content-between align-items-center">
                        <div class="db-info">
                            <h6 class="text-muted mb-1">Total Siswa</h6>
                            <h3 id="total-siswa">-</h3>
                        </div>
                        <div class="db-icon">
                            <span class="badge bg-primary p-3 rounded-circle">
                                <i class="fas fa-user-graduate fa-lg"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 col-12 d-flex">
            <div class="card bg-comman w-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="db-widgets d-flex justify-content-between align-items-center">
                        <div class="db-info">
                            <h6 class="text-muted mb-1">Laki-laki</h6>
                            <h3 id="total-laki" class="text-primary">-</h3>
                        </div>
                        <div class="db-icon">
                            <span class="badge bg-primary-soft p-3 rounded-circle">
                                <i class="fas fa-male fa-lg text-primary"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 col-12 d-flex">
            <div class="card bg-comman w-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="db-widgets d-flex justify-content-between align-items-center">
                        <div class="db-info">
                            <h6 class="text-muted mb-1">Perempuan</h6>
                            <h3 id="total-perempuan" class="text-danger">-</h3>
                        </div>
                        <div class="db-icon">
                            <span class="badge bg-danger-soft p-3 rounded-circle">
                                <i class="fas fa-female fa-lg text-danger"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 col-12 d-flex">
            <div class="card bg-comman w-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="db-widgets d-flex justify-content-between align-items-center">
                        <div class="db-info">
                            <h6 class="text-muted mb-1">Bulan Ini</h6>
                            <h3 id="total-bulan-ini" class="text-success">-</h3>
                        </div>
                        <div class="db-icon">
                            <span class="badge bg-success-soft p-3 rounded-circle">
                                <i class="fas fa-calendar-plus fa-lg text-success"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title">Distribusi Jenis Kelamin</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="position: relative; height:300px;">
                        <canvas id="genderChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title">Distribusi Agama</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="position: relative; height:300px;">
                        <canvas id="religionChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title">Tren Pendaftaran Siswa (12 Bulan Terakhir)</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="position: relative; height:250px;">
                        <canvas id="registrationTrendChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title">Kelompok Usia</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="position: relative; height:300px;">
                        <canvas id="ageChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title">Bulan Kelahiran</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="position: relative; height:300px;">
                        <canvas id="birthMonthChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <!-- Include Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <!-- Include Chart.js plugin for gradients and custom tooltips -->
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>

    <style>
        .chart-container {
            position: relative;
            margin: auto;
        }

        .bg-primary-soft {
            background-color: rgba(54, 162, 235, 0.2);
        }

        .bg-danger-soft {
            background-color: rgba(255, 99, 132, 0.2);
        }

        .bg-success-soft {
            background-color: rgba(75, 192, 192, 0.2);
        }
    </style>

    <script>
        // Register Chart.js plugins
        Chart.register(ChartDataLabels);

        // Custom colors
        const colors = {
            primary: '#3B7DDD',
            primaryLight: 'rgba(59, 125, 221, 0.2)',
            secondary: '#6c757d',
            success: '#28a745',
            danger: '#dc3545',
            warning: '#ffc107',
            info: '#17a2b8',
            light: '#f8f9fa',
            dark: '#343a40',
            primaryGradient: createGradient('#3B7DDD', 'rgba(59, 125, 221, 0.05)')
        };

        // Create gradient
        function createGradient(colorStart, colorEnd) {
            return {
                createGradient: function(ctx) {
                    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
                    gradient.addColorStop(0, colorStart);
                    gradient.addColorStop(1, colorEnd);
                    return gradient;
                }
            };
        }

        // Chart defaults
        Chart.defaults.font.family = "'Poppins', sans-serif";
        Chart.defaults.font.size = 12;
        Chart.defaults.plugins.tooltip.backgroundColor = 'rgba(0, 0, 0, 0.8)';
        Chart.defaults.plugins.tooltip.padding = 12;
        Chart.defaults.plugins.tooltip.cornerRadius = 8;
        Chart.defaults.plugins.tooltip.titleFont = {
            weight: 'bold',
            size: 13
        };
        Chart.defaults.plugins.legend.position = 'bottom';

        // Global DataLabels defaults
        Chart.defaults.plugins.datalabels.color = '#fff';
        Chart.defaults.plugins.datalabels.font.weight = 'bold';

        $(document).ready(function() {

            // Fetch statistics data
            $.ajax({
                url: "{{ route('student.statistics') }}",
                type: 'GET',
                success: function(response) {
                    // Update summary cards
                    if (response.summary) {
                        $('#total-siswa').text(response.summary.total);
                        $('#total-laki').text(response.summary.male);
                        $('#total-perempuan').text(response.summary.female);
                        $('#total-bulan-ini').text(response.summary.added_this_month);
                    }

                    // Render all charts
                    renderGenderChart(response.by_gender);
                    renderReligionChart(response.by_religion);
                    renderAgeChart(response.by_age);
                    renderBirthMonthChart(response.by_birth_month);

                    if (response.by_registration_month) {
                        renderRegistrationTrendChart(response.by_registration_month);
                    }


                },
                error: function(xhr) {

                    // Show error message
                    console.error('Error fetching statistics:', xhr);
                    Swal.fire({
                        title: 'Error!',
                        text: 'Gagal memuat data statistik: ' + (xhr.responseJSON ? xhr
                            .responseJSON.message : 'Unknown error'),
                        icon: 'error'
                    });
                }
            });
        });

        function renderGenderChart(data) {
            if (!data || data.length === 0) return;

            const ctx = document.getElementById('genderChart').getContext('2d');

            // Calculate percentage
            const total = data.reduce((sum, item) => sum + item.value, 0);
            const formattedData = data.map(item => ({
                ...item,
                percentage: ((item.value / total) * 100).toFixed(1)
            }));

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: formattedData.map(item => item.label),
                    datasets: [{
                        data: formattedData.map(item => item.value),
                        backgroundColor: ['#3B7DDD', '#dc3545'],
                        borderColor: '#ffffff',
                        borderWidth: 2,
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '65%',
                    layout: {
                        padding: 20
                    },
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                padding: 20,
                                font: {
                                    size: 13
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: (context) => {
                                    const item = formattedData[context.dataIndex];
                                    return `${item.label}: ${item.value} (${item.percentage}%)`;
                                }
                            }
                        },
                        datalabels: {
                            formatter: (value, ctx) => {
                                const item = formattedData[ctx.dataIndex];
                                return `${item.percentage}%`;
                            },
                            color: '#fff',
                            font: {
                                weight: 'bold',
                                size: 14
                            }
                        }
                    }
                }
            });
        }

        function renderReligionChart(data) {
            if (!data || data.length === 0) return;

            const ctx = document.getElementById('religionChart').getContext('2d');
            const colorPalette = [
                '#0088FE', '#00C49F', '#FFBB28', '#FF8042',
                '#A28CFF', '#FF9FB3', '#22CECE', '#9ECA55'
            ];

            // For many religions, we might want to group small ones as "Others"
            let formattedData = [...data];
            if (data.length > 6) {
                // Sort by value descending
                formattedData.sort((a, b) => b.value - a.value);

                // Take top 5 and group the rest
                const top5 = formattedData.slice(0, 5);
                const others = formattedData.slice(5);
                const othersSum = others.reduce((sum, item) => sum + item.value, 0);

                if (othersSum > 0) {
                    formattedData = [
                        ...top5,
                        {
                            label: 'Lainnya',
                            value: othersSum
                        }
                    ];
                } else {
                    formattedData = top5;
                }
            }

            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: formattedData.map(item => item.label),
                    datasets: [{
                        data: formattedData.map(item => item.value),
                        backgroundColor: colorPalette.slice(0, formattedData.length),
                        borderColor: '#ffffff',
                        borderWidth: 2,
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    layout: {
                        padding: 20
                    },
                    plugins: {
                        legend: {
                            position: 'right',
                            align: 'start',
                            labels: {
                                usePointStyle: true,
                                padding: 15,
                                font: {
                                    size: 12
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: (context) => {
                                    const value = context.raw;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = ((value / total) * 100).toFixed(1);
                                    return `${context.label}: ${value} (${percentage}%)`;
                                }
                            }
                        },
                        datalabels: {
                            display: function(context) {
                                return context.dataset.data[context.dataIndex] > 0;
                            },
                            formatter: (value, ctx) => {
                                const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((value / total) * 100).toFixed(0);
                                return percentage > 5 ? `${percentage}%` : '';
                            },
                            font: {
                                weight: 'bold',
                                size: 12
                            }
                        }
                    }
                }
            });
        }

        function renderAgeChart(data) {
            if (!data || data.length === 0) return;

            const ctx = document.getElementById('ageChart').getContext('2d');

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.map(item => item.label),
                    datasets: [{
                        label: 'Jumlah Siswa',
                        data: data.map(item => item.value),
                        backgroundColor: colors.primaryGradient.createGradient(ctx),
                        borderColor: colors.primary,
                        borderWidth: 1,
                        borderRadius: 6,
                        hoverBackgroundColor: colors.primary
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    layout: {
                        padding: {
                            top: 20
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)',
                                drawBorder: false
                            },
                            ticks: {
                                precision: 0
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: (context) => `Jumlah: ${context.raw} siswa`
                            }
                        },
                        datalabels: {
                            anchor: 'end',
                            align: 'top',
                            formatter: (value) => value > 0 ? value : '',
                            color: colors.secondary,
                            font: {
                                weight: 'bold'
                            },
                            offset: 4
                        }
                    }
                }
            });
        }

        function renderBirthMonthChart(data) {
            if (!data || data.length === 0) return;

            const ctx = document.getElementById('birthMonthChart').getContext('2d');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.map(item => item.label),
                    datasets: [{
                        label: 'Jumlah Siswa',
                        data: data.map(item => item.value),
                        borderColor: colors.success,
                        backgroundColor: 'rgba(40, 167, 69, 0.2)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true,
                        pointBackgroundColor: colors.success,
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    layout: {
                        padding: {
                            top: 20
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)',
                                drawBorder: false
                            },
                            ticks: {
                                precision: 0
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: (context) => `Jumlah: ${context.raw} siswa`
                            }
                        },
                        datalabels: {
                            display: false
                        }
                    }
                }
            });
        }

        function renderRegistrationTrendChart(data) {
            if (!data || data.length === 0) return;

            const ctx = document.getElementById('registrationTrendChart').getContext('2d');
            const gradient = ctx.createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, 'rgba(59, 125, 221, 0.6)');
            gradient.addColorStop(1, 'rgba(59, 125, 221, 0.1)');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.map(item => item.label),
                    datasets: [{
                        label: 'Siswa Baru',
                        data: data.map(item => item.value),
                        borderColor: colors.primary,
                        backgroundColor: gradient,
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true,
                        pointBackgroundColor: colors.primary,
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    layout: {
                        padding: {
                            top: 20
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)',
                                drawBorder: false
                            },
                            ticks: {
                                precision: 0
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: (context) => `Siswa baru: ${context.raw}`
                            }
                        },
                        datalabels: {
                            display: false
                        }
                    }
                }
            });
        }
    </script>
@endpush
