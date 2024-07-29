<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="description" content="POS - Bootstrap Admin Template">
    <meta name="keywords"
        content="admin, estimates, bootstrap, business, corporate, creative, management, minimal, modern,  html5, responsive">
    <meta name="robots" content="noindex, nofollow">
    <title>Loggingpedia | my logging {{ $user->username }}</title>

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

                    <div class="page-btn">
                        <a href="/logging/{{ $user->uuid }}/create" class="btn btn-added">
                            <img src="{{ asset('assets/dashboard/img/icons/plus.svg') }}" alt="img"
                                class="me-1">Add Logging</a>
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
                                <div class="search-path">
                                    <a class="btn btn-filter" id="filter_search">
                                        <img src="{{ asset('assets/dashboard/img/icons/filter.svg') }}" alt="img">
                                        <span><img src="{{ asset('assets/dashboard/img/icons/closes.svg') }}"
                                                alt="img"></span>
                                    </a>
                                </div>
                            </div>

                            <div class="wordset">
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
                            </div>
                        </div>

                        <div class="card mb-0" id="filter_inputs">
                            <div class="card-body pb-0">
                                <div class="row">
                                    <div class="col-lg-12 col-sm-12">
                                        <form method="GET" action="/logging/{{ $user->uuid }}/search">
                                            <div class="row">
                                                <div class="col-lg col-sm-6 col-12">
                                                    <div class="form-group">
                                                        <label class="form-label text-center">Type</label>
                                                        <select name="type" class="form-control text-center"
                                                            required>
                                                            <option value="" selected>Choose type</option>

                                                            <option value="local"
                                                                {{ request('type') == 'local' ? 'selected' : '' }}>
                                                                Local
                                                            </option>

                                                            <option value="testing"
                                                                {{ request('type') == 'testing' ? 'selected' : '' }}>
                                                                Testing
                                                            </option>

                                                            <option value="production"
                                                                {{ request('type') == 'production' ? 'selected' : '' }}>
                                                                Production
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-lg col-sm-6 col-12">
                                                    <div class="form-group">
                                                        <label class="form-label text-center">Start time</label>
                                                        <input type="datetime-local" name="time-start"
                                                            class="form-control" step="1"
                                                            value="{{ request('time-start') }}" required>
                                                    </div>
                                                </div>

                                                <div class="col-lg col-sm-6 col-12">
                                                    <div class="form-group">
                                                        <label class="form-label text-center">End time</label>
                                                        <input type="datetime-local" name="time-end"
                                                            class="form-control" step="1"
                                                            value="{{ request('time-end') }}" required>
                                                    </div>
                                                </div>

                                                <div
                                                    class="col-lg-1 col-sm-6 col-12 d-flex flex-column align-items-center">
                                                    <div class="form-group w-100">
                                                        <label class="form-label w-100 text-center">Find</label>
                                                        <button class="btn btn-filters w-100" type="submit">
                                                            <img src="{{ asset('assets/dashboard/img/icons/search-whites.svg') }}"
                                                                alt="img">
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table mt-3">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th class=" text-center">Environment</th>
                                        <th class=" text-center">Type Log</th>
                                        <th class=" text-center">Time Created</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($loggings as $logging)
                                        <tr>
                                            <td>
                                                {{ ($loggings->currentPage() - 1) * $loggings->perPage() + $loop->iteration }}
                                            </td>
                                            <td class=" text-center">{{ $logging->type_env }}</td>
                                            <td class=" text-center">{{ $logging->type_log }}</td>
                                            <td class=" text-center">{{ $logging->created_at }}</td>
                                            <td>
                                                <a class="ms-3" href="/logging/{{ $logging->uuid }}/show">
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
