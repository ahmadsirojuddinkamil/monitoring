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

                {{-- <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Bar Chart</div>
                        </div>

                        <div class="card-body">
                            <div>
                                <canvas id="chartBar1" class="h-300"></canvas>
                            </div>
                        </div>
                    </div>
                </div> --}}

            </div>
        </div>

    </div>

    @include('dashboard::bases.js')
</body>

</html>
