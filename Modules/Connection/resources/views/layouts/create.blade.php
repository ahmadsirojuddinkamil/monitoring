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
    <title>Loggingpedia | create connection</title>

    @include('dashboard::bases.css')
</head>

<body>
    <div id="global-loader">
        <div class="whirly-loader"> </div>
    </div>

    <div class="main-wrapper">

        @include('dashboard::components.header')
        @include('dashboard::components.sidebar')

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

        <div class="page-wrapper">
            <div class="content">
                <div class="page-header">
                    <div class="page-title">
                        <h4>Create connection</h4>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <form action="/connection/create" method="POST">
                                @csrf

                                <div class="mb-3">
                                    <label for="endpoint" class="form-label">Endpoint</label>
                                    <input type="text" class="form-control" id="endpoint" name="endpoint"
                                        placeholder="https://www.domain.com" required>
                                </div>
                                @error('endpoint')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror

                                <div class="mb-3">
                                    <label for="register" class="form-label">Register</label>
                                    <input type="text" class="form-control" id="register" name="register"
                                        data-original-value="/api/register-monitoring/KEY" required>
                                </div>
                                @error('register')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror

                                <div class="mb-3">
                                    <label for="login" class="form-label">Login</label>
                                    <input type="text" class="form-control" id="login" name="login"
                                        data-original-value="/api/login-monitoring/KEY" required>
                                </div>
                                @error('login')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror

                                <div class="mb-3">
                                    <label for="get_log" class="form-label">Get log</label>
                                    <input type="text" class="form-control" id="get_log" name="get_log"
                                        data-original-value="/api/logging/KEY" required>
                                </div>
                                @error('get_log')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror

                                <div class="mb-3">
                                    <label for="get_log_by_type" class="form-label">Get log by type</label>
                                    <input type="text" class="form-control" id="get_log_by_type"
                                        name="get_log_by_type" data-original-value="/api/logging/KEY/type" required>
                                </div>
                                @error('get_log_by_type')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror

                                <div class="mb-3">
                                    <label for="get_log_by_time" class="form-label">Get log by time</label>
                                    <input type="text" class="form-control" id="get_log_by_time"
                                        name="get_log_by_time" data-original-value="/api/logging/KEY/type/time"
                                        required>
                                </div>
                                @error('get_log_by_time')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror

                                <div class="mb-3">
                                    <label for="delete_log" class="form-label">Delete log</label>
                                    <input type="text" class="form-control" id="delete_log" name="delete_log"
                                        data-original-value="/api/logging/KEY" required>
                                </div>
                                @error('delete_log')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror

                                <div class="mb-3">
                                    <label for="delete_log_by_type" class="form-label">Delete log by type</label>
                                    <input type="text" class="form-control" id="delete_log_by_type"
                                        name="delete_log_by_type" data-original-value="/api/logging/KEY/type" required>
                                </div>
                                @error('delete_log_by_type')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror

                                <div class="mb-3">
                                    <label for="delete_log_by_time" class="form-label">Delete log by time</label>
                                    <input type="text" class="form-control" id="delete_log_by_time"
                                        name="delete_log_by_time" data-original-value="/api/logging/KEY/type/time"
                                        required>
                                </div>
                                @error('delete_log_by_time')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror

                                <div class="mb-3">
                                    <label for="token" class="form-label">Token</label>
                                    <input type="text" class="form-control" id="token" name="token"
                                        required>
                                </div>
                                @error('token')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror

                                <div class="col-lg-12">
                                    <button type="button" class="btn btn-submit me-2" data-bs-toggle="modal"
                                        data-bs-target="#exampleModal">
                                        Submit
                                    </button>

                                    <a href="/connection/{{ Auth::user()->uuid }}" class="btn btn-cancel">Cancel</a>

                                    <div class="modal fade" id="exampleModal" tabindex="-1"
                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Are you sure?
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>

                                                <div class="modal-body">
                                                    This connection data will be created!
                                                </div>

                                                <div class="modal-footer d-flex justify-content-end">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">No</button>
                                                    <button type="submit" class="btn btn-submit me-2">Ya</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

    @include('connection::bases.js')
    @include('dashboard::bases.js')
</body>

</html>
