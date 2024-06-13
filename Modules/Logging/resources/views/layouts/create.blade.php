<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="description" content="POS - Bootstrap Admin Template">
    <meta name="keywords"
        content="admin, estimates, bootstrap, business, corporate, creative, management, minimal, modern,  html5, responsive">
    <meta name="robots" content="noindex, nofollow">
    <title>Loggingpedia | create logging</title>

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
                        <h4>Get Log Data</h4>
                        <h6>Fetch your logging | Don't have a token yet? Login first</h6>
                    </div>

                    <div class="page-btn">
                        <a href="/logging/login" class="btn btn-added">
                            <img src="{{ asset('assets/dashboard/img/icons/plus.svg') }}" alt="img"
                                class="me-1">Login</a>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="requiredfield">
                            <h4>Choose your endpoints carefully! Any form of error whatsoever, is not our
                                responsibility!</h4>
                        </div>

                        <div class="form-group">
                            <label>Endpoint List</label>
                            <div class="input-groupicon">
                                <div class="form-group">
                                    <form action="" method="POST">
                                        <select name="type" class="form-control text-center" name="endpoint"
                                            required>
                                            <option value="get_log">
                                                Get logging
                                            </option>

                                            <option value="get_log_by_type">
                                                Get logging by type
                                            </option>

                                            <option value="get_log_by_time">
                                                Get logging by time
                                            </option>

                                            <option value="delete_log">
                                                Delete logging
                                            </option>

                                            <option value="delete_log_by_type">
                                                Delete logging by type
                                            </option>

                                            <option value="delete_log_by_time">
                                                Delete logging by time
                                            </option>
                                        </select>

                                        <div class="mt-3">
                                            <label for="token" class="form-label">Token</label>
                                            <input type="text" class="form-control" id="token" required>
                                        </div>

                                        <div class="col-lg-12 mt-3">
                                            <a data-bs-toggle="modal" data-bs-target="#exampleModal"
                                                class="btn btn-submit me-2">Submit</a>

                                            <div class="modal fade" id="exampleModal" tabindex="-1"
                                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Are you
                                                                sure?</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>

                                                        <div class="modal-body">
                                                            Choosing the wrong endpoint is not our responsibility!
                                                        </div>

                                                        <div class="modal-footer d-flex justify-content-end">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">No</button>

                                                            <form action="" method="POST" class="action-form">
                                                                <button type="submit"
                                                                    class="btn btn-submit me-2">Yes</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <a href="/logging/{{ Auth::user()->uuid }}"
                                                class="btn btn-cancel">Cancel</a>
                                        </div>
                                    </form>
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
