<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="description" content="POS - Bootstrap Admin Template">
    <meta name="keywords"
        content="admin, estimates, bootstrap, business, corporate, creative, management, minimal, modern,  html5, responsive">
    <meta name="author" content="Dreamguys - Bootstrap Admin Template">
    <meta name="robots" content="noindex, nofollow">
    <title>Loggingpedia | my connection {{ Auth::user()->username }}</title>

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
                        <h4>Connection {{ Auth::user()->username }}</h4>
                    </div>

                    <div class="wordset">
                        <ul>
                            @if (!Auth::user()->hasRole('administrator'))
                                @if ($connection)
                                    <a href="/connection/{{ $user->uuid }}/edit" class="link-with-margin">
                                        <img src="{{ asset('assets/dashboard/img/icons/edit.png') }}" alt="img"
                                            height="20" width="20">
                                    </a>
                                @else
                                    <a href="/connection/create" class="link-with-margin">
                                        <img src="{{ asset('assets/dashboard/img/icons/add.png') }}" alt="img"
                                            height="20" width="20">
                                    </a>
                                @endif
                            @endif
                        </ul>
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

                <div class="row">
                    <div class="col-lg-8 col-sm-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="productdetails">
                                    <ul class="product-bar">
                                        <li>
                                            <h4>Endpoint</h4>
                                            @if ($connection)
                                                <h6>{{ $connection->endpoint }}</h6>
                                            @endif
                                        </li>

                                        <li>
                                            <h4>Register</h4>
                                            @if ($connection)
                                                <h6>{{ $connection->register }}</h6>
                                            @endif
                                        </li>

                                        <li>
                                            <h4>Login</h4>
                                            @if ($connection)
                                                <h6>{{ $connection->login }}</h6>
                                            @endif
                                        </li>

                                        <li>
                                            <h4>Get log all</h4>
                                            @if ($connection)
                                                <h6>{{ $connection->get_log }}</h6>
                                            @endif
                                        </li>

                                        <li>
                                            <h4>Get log by type</h4>
                                            @if ($connection)
                                                <h6>{{ $connection->get_log_by_type }}</h6>
                                            @endif
                                        </li>

                                        <li>
                                            <h4>Get log by time</h4>
                                            @if ($connection)
                                                <h6>{{ $connection->get_log_by_time }}</h6>
                                            @endif
                                        </li>

                                        <li>
                                            <h4>Delete log all</h4>
                                            @if ($connection)
                                                <h6>{{ $connection->delete_log }}</h6>
                                            @endif
                                        </li>

                                        <li>
                                            <h4>Delete log by type</h4>
                                            @if ($connection)
                                                <h6>{{ $connection->delete_log_by_type }}</h6>
                                            @endif
                                        </li>

                                        <li>
                                            <h4>Delete log by time</h4>
                                            @if ($connection)
                                                <h6>{{ $connection->delete_log_by_time }}</h6>
                                            @endif
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-sm-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="slider-product-details">
                                    <div class="owl-carousel owl-theme product-slide">
                                        <div class="slider-product">
                                            <img src="{{ asset('assets/dashboard/img/icons/network.png') }}"
                                                alt="img">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

    @include('dashboard::bases.js')
</body>

</html>
