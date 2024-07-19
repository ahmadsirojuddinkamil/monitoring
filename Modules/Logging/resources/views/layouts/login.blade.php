<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="description" content="POS - Bootstrap Admin Template">
    <meta name="keywords"
        content="admin, estimates, bootstrap, business, corporate, creative, management, minimal, modern,  html5, responsive">
    <meta name="robots" content="noindex, nofollow">
    <title>Loggingpedia | login client</title>

    @include('dashboard::bases.css')
    @include('logging::bases.css')
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
                        <h4>Login</h4>
                        <h6>Login immediately to get tokens! Don't have an account register now</h6>
                    </div>

                    <div class="page-btn">
                        <a href="/logging/register" class="btn btn-added">
                            <img src="{{ asset('assets/dashboard/img/icons/plus.svg') }}" alt="img"
                                class="me-1">Register</a>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
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
                            <form action="/logging/login" method="POST">
                                @csrf

                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="email" class="form-control" name="email"
                                            placeholder="domain@email.com" required>
                                    </div>

                                    @error('email')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <label>Password</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" name="password" id="password"
                                                required>
                                            <div class="input-group-append" onclick="togglePasswordVisibility()">
                                                <span class="input-group-text">
                                                    <i class="fas toggle-password fa-eye-slash"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    @error('password')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <div class="col-lg-12">
                                        <button type="button" class="btn btn-submit me-2" data-bs-toggle="modal"
                                            data-bs-target="#exampleModal">
                                            Submit
                                        </button>

                                        <a href="/logging/{{ Auth::user()->uuid }}/create"
                                            class="btn btn-cancel">Cancel</a>

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
                                                        Make sure your login data is correct!
                                                    </div>

                                                    <div class="modal-footer d-flex justify-content-end">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">No</button>
                                                        <button type="submit" class="btn btn-submit me-2">Yes</button>
                                                    </div>
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

    @include('logging::bases.js')
    @include('dashboard::bases.js')

</body>

</html>
