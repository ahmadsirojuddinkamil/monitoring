<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="description" content="POS - Bootstrap Admin Template">
    <meta name="keywords"
        content="admin, estimates, bootstrap, business, corporate, creative, management, minimal, modern,  html5, responsive">
    <meta name="robots" content="noindex, nofollow">
    <title>Loggingpedia | fetch log</title>

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

                        <div class="requiredfield">
                            <h4>Choose your endpoints carefully! Any form of error whatsoever, is not our
                                responsibility!</h4>
                        </div>

                        <div class="form-group">
                            <label>Endpoint List</label>
                            <div class="input-groupicon">
                                <div class="form-group">
                                    <form action="/logging/{{ Auth::user()->uuid }}/store" method="POST">
                                        @csrf

                                        <select name="type" id="logTypeSelect" class="form-control text-center mb-3"
                                            required>
                                            <option value="get_log">Get logging</option>
                                            <option value="get_log_by_type">Get logging by type</option>
                                            <option value="get_log_by_time">Get logging by time</option>
                                            <option value="delete_log">Delete logging</option>
                                            <option value="delete_log_by_type">Delete logging by type</option>
                                            <option value="delete_log_by_time">Delete logging by time</option>
                                        </select>

                                        @error('type')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror

                                        <div id="additionalInput" class="mb-3" style="display: none;">
                                            <label for="logType">Log Type:</label>
                                            <select name="type_env" id="logType" class="form-control text-center">
                                                <option value="">Choose type log</option>
                                                <option value="local">Local</option>
                                                <option value="testing">Testing</option>
                                                <option value="production">Production</option>
                                            </select>
                                        </div>

                                        @error('type_env')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror

                                        <div class="d-flex justify-content-between">
                                            <div id="InputTimeStart" class="col-lg col-sm-6 col-12 me-2"
                                                style="display: none;">
                                                <div class="form-group">
                                                    <label class="form-label">Start time</label>
                                                    <input type="datetime-local" name="time_start" class="form-control"
                                                        step="1" value="{{ request('time_start') }}">
                                                </div>

                                                @error('time_start')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div id="InputTimeEnd" class="col-lg col-sm-6 col-12"
                                                style="display: none;">
                                                <div class="form-group">
                                                    <label class="form-label">End time</label>
                                                    <input type="datetime-local" name="time_end" class="form-control"
                                                        step="1" value="{{ request('time_end') }}">
                                                </div>

                                                @error('time_end')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-12 mt-3">
                                            <a data-bs-toggle="modal" data-bs-target="#exampleModal"
                                                class="btn btn-submit me-2">Submit</a>

                                            <div class="modal fade" id="exampleModal" tabindex="-1"
                                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Are you sure?
                                                            </h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>

                                                        <div class="modal-body">
                                                            Choosing the wrong endpoint is not our responsibility!
                                                        </div>

                                                        <div class="modal-footer d-flex justify-content-end">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">No</button>
                                                            <button type="submit"
                                                                class="btn btn-submit me-2">Yes</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <a href="/logging/{{ Auth::user()->uuid }}"
                                                class="btn btn-cancel">Cancel</a>
                                        </div>
                                    </form>

                                    <script>
                                        document.addEventListener('DOMContentLoaded', function() {
                                            const logTypeSelect = document.getElementById('logTypeSelect');
                                            const additionalInput = document.getElementById('additionalInput');
                                            const inputTimeStart = document.getElementById('InputTimeStart');
                                            const inputTimeEnd = document.getElementById('InputTimeEnd');
                                            const timeStartInput = document.querySelector('input[name="time_start"]');
                                            const timeEndInput = document.querySelector('input[name="time_end"]');
                                            const logType = document.getElementById('logType');

                                            logTypeSelect.value = 'get_log';
                                            logType.value = '';
                                            timeStartInput.value = '';
                                            timeEndInput.value = '';

                                            logTypeSelect.addEventListener('change', function() {
                                                const selectedValue = logTypeSelect.value;

                                                if (selectedValue === 'get_log_by_type' || selectedValue === 'delete_log_by_type') {
                                                    additionalInput.style.display = 'block';
                                                    inputTimeStart.style.display = 'none';
                                                    inputTimeEnd.style.display = 'none';
                                                    timeStartInput.value = '';
                                                    timeEndInput.value = '';
                                                    logType.setAttribute('required', 'required');
                                                } else if (selectedValue === 'get_log_by_time' || selectedValue === 'delete_log_by_time') {
                                                    additionalInput.style.display = 'block';
                                                    inputTimeStart.style.display = 'block';
                                                    inputTimeEnd.style.display = 'block';
                                                    logType.setAttribute('required', 'required');
                                                    timeStartInput.setAttribute('required', 'required');
                                                    timeEndInput.setAttribute('required', 'required');
                                                } else {
                                                    logType.value = '';
                                                    timeStartInput.value = '';
                                                    timeEndInput.value = '';
                                                    additionalInput.style.display = 'none';
                                                    inputTimeStart.style.display = 'none';
                                                    inputTimeEnd.style.display = 'none';
                                                    logType.removeAttribute('required');
                                                    timeStartInput.removeAttribute('required');
                                                    timeEndInput.removeAttribute('required');
                                                }
                                            });
                                        });
                                    </script>
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
