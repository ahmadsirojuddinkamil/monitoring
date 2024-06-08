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
    <title>Loggingpedia | my logging {{ Auth::user()->username }}</title>

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
                        <h4>LOG List</h4>
                        <h6>Manage your Logging</h6>
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

                <div class="card">
                    <div class="card-body">
                        <div class="table-top">
                            <div class="search-set">
                                <div class="search-input">
                                    <a class="btn btn-searchset">
                                        <img src="{{ asset('assets/dashboard/img/icons/search-white.svg') }}"
                                            alt="img">
                                    </a>
                                </div>
                            </div>

                            {{-- <div class="wordset">
                                <ul>
                                    <li>
                                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="pdf"><img
                                                src="{{ asset('assets/dashboard/img/icons/pdf.svg') }}"
                                                alt="img"></a>
                                    </li>

                                    <li>
                                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="excel"><img
                                                src="{{ asset('assets/dashboard/img/icons/excel.svg') }}"
                                                alt="img"></a>
                                    </li>
                                </ul>
                            </div> --}}
                        </div>

                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Endpoint</th>
                                        <th>Type</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($loggings as $logging)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $endpoint }}</td>
                                            <td>{{ $logging->type }}</td>
                                            <td>{{ $logging->created_at }}</td>
                                            <td>
                                                <a class="me-3" href="">
                                                    <img src="{{ asset('assets/dashboard/img/icons/show.png') }}"
                                                        height="25" width="25" alt="img">
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <style>
                                .pagination .page-item.active .page-link {
                                    background-color: #1B2850;
                                    border-color: #1B2850;
                                    color: #ffffff;
                                    border-radius: 3px;
                                }
                            </style>

                            <div class="d-flex justify-content-center mt-3">
                                {{ $loggings->links() }}
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
