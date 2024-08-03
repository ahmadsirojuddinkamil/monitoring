<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="description" content="POS - Bootstrap Admin Template">
    <meta name="keywords"
        content="admin, estimates, bootstrap, business, corporate, creative, management, minimal, modern,  html5, responsive">
    <meta name="robots" content="noindex, nofollow">
    <title>Loggingpedia | log details</title>

    @include('dashboard::bases.css')
</head>

<body>
    <div id="global-loader">
        <div class="whirly-loader"> </div>
    </div>

    <div class="main-wrapper">

        @include('dashboard::components.header')
        @include('dashboard::components.sidebar')

        <div class="page-wrapper">
            <div class="content">
                <div class="page-header">
                    <div class="page-title">
                        <h4>LOG {{ $result->type_env }}</h4>
                        <h6>Result fetch {{ $result->type_log }}</h6>
                    </div>
                </div>

                @if (session()->has('success'))
                    <div class="alert alert-success d-flex justify-content-center" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session()->has('error'))
                    <div class="alert alert-danger d-flex justify-content-center" role="alert">
                        {{ session('error') }}
                    </div>
                @endif

                <style>
                    .chart-container {
                        position: relative;
                        height: 400px;
                        width: 100%;
                    }
                </style>

                <div class="container mt-5">
                    <div class="row">
                        <div class="col-md-8 offset-md-2">
                            <div class="chart-container">
                                <canvas id="myBarChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

                <script>
                    var ctx = document.getElementById('myBarChart').getContext('2d');
                    var myBarChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: ['info', 'emergency', 'alert', 'critical', 'error', 'warning', 'notice', 'debug', 'other'],
                            datasets: [{
                                label: 'Diagram Bar',
                                data: [
                                    {{ $logCounts['info'] ?? 0 }},
                                    {{ $logCounts['emergency'] ?? 0 }},
                                    {{ $logCounts['alert'] ?? 0 }},
                                    {{ $logCounts['critical'] ?? 0 }},
                                    {{ $logCounts['error'] ?? 0 }},
                                    {{ $logCounts['warning'] ?? 0 }},
                                    {{ $logCounts['notice'] ?? 0 }},
                                    {{ $logCounts['debug'] ?? 0 }},
                                    {{ $logCounts['other'] ?? 0 }}
                                ],
                                backgroundColor: '#FF9F43',
                                borderColor: '#FF9F43',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    min: 0,
                                    suggestedMin: 0,
                                    max: 100,
                                    suggestedMax: 110,
                                    ticks: {
                                        stepSize: 10,
                                        callback: function(value) {
                                            return value;
                                        }
                                    }
                                }
                            }
                        }

                    });
                </script>

                <br>

                <div class="row">
                    @foreach ($logDetails as $key => $logs)
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover mb-0">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        {{ ucfirst($key) }}
                                                        <a href="/logging/{{ $result->uuid }}/{{ strtolower($key) }}/download"
                                                            class="link-with-margin">
                                                            <img src="{{ asset('assets/dashboard/img/icons/downloads.png') }}"
                                                                alt="img" height="20" width="20"
                                                                style="margin-left: 10px;">
                                                        </a>
                                                    </th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @foreach ($logs as $log)
                                                    <tr>
                                                        <td>{{ json_encode($log) }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>

    @include('dashboard::bases.js')
</body>

</html>
