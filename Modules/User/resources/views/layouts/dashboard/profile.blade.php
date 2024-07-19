<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="description" content="POS - Bootstrap Admin Template">
    <meta name="keywords"
        content="admin, estimates, bootstrap, business, corporate, creative, management, minimal, modern,  html5, responsive">
    <meta name="robots" content="noindex, nofollow">
    <title>Loggingpedia | profile {{ $user->username }}</title>

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
                        <h4>Profile</h4>
                        <h6>User Profile</h6>
                    </div>
                </div>

                <style>
                    input[type="file"] {
                        display: none;
                    }
                </style>

                <div class="card">
                    <div class="card-body">
                        <div class="profile-set">
                            <div class="profile-head"></div>

                            <div class="profile-top">
                                <div class="profile-content">
                                    <div class="profile-contentimg">
                                        <img src="{{ $user->profile ? asset($user->profile) : asset('assets/dashboard/img/customer/customer5.jpg') }}"
                                            alt="img" id="blah">

                                        <div class="profileupload">
                                            <a href="javascript:void(0);" id="upload-link">
                                                <img src="{{ asset('assets/dashboard/img/icons/edit-set.svg') }}"
                                                    alt="img">
                                            </a>
                                        </div>
                                    </div>

                                    <div class="profile-contentname">
                                        <h2>{{ $user->username }}</h2>
                                        <h4>Update Your Photo and Personal Details.</h4>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @error('new_profile')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror

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
                            <form action="/profile/{{ $user->uuid }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <label>Username</label>
                                        <input type="text" class="form-control" value="{{ $user->username }}"
                                            name="username" required>
                                    </div>

                                    @error('username')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="email" class="form-control" value="{{ $user->email }}"
                                            name="email" required>
                                    </div>

                                    @error('email')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div>
                                    <input type="hidden" name="old_profile" value="{{ $user->profile }}">
                                    <input type="file" name="new_profile" id="profile-input" style="display: none;">
                                </div>

                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <label>Old Password</label>

                                        <div class="pass-group">
                                            <input type="password" id="old-password" name="old_password">
                                            <span class="fas toggle-password fa-eye-slash"
                                                id="toggle-old-password"></span>
                                        </div>
                                    </div>

                                    @error('old_password')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror

                                    @if (session()->has('error_password'))
                                        <div class="alert alert-danger">{{ session('error_password') }}</div>
                                    @endif
                                </div>

                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <label>New Password</label>

                                        <div class="pass-group">
                                            <input type="password" id="new-password" name="new_password">
                                            <span class="fas toggle-password fa-eye-slash"
                                                id="toggle-new-password"></span>
                                        </div>
                                    </div>

                                    @error('new_password')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror

                                    @if (session()->has('error_password'))
                                        <div class="alert alert-danger">{{ session('error_password') }}</div>
                                    @endif
                                </div>

                                <div class="col-12">
                                    <div class="col-lg-12">
                                        <button type="button" class="btn btn-submit me-2" data-bs-toggle="modal"
                                            data-bs-target="#exampleModal">
                                            Submit
                                        </button>

                                        <a href="/dashboard" class="btn btn-cancel">Cancel</a>

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
                                                        Your profile will be updated!
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
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

    @include('dashboard::bases.js')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function togglePasswordVisibility(toggleId, passwordId) {
                const toggle = document.getElementById(toggleId);
                const password = document.getElementById(passwordId);

                toggle.addEventListener('click', function() {
                    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                    password.setAttribute('type', type);
                });
            }

            togglePasswordVisibility('toggle-old-password', 'old-password');
            togglePasswordVisibility('toggle-new-password', 'new-password');
        });

        document.addEventListener('DOMContentLoaded', function() {
            const uploadLink = document.getElementById('upload-link');
            const profileInput = document.getElementById('profile-input');
            const blah = document.getElementById('blah');

            uploadLink.addEventListener('click', function() {
                profileInput.click();
            });

            profileInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        blah.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            });
        });
    </script>

</body>

</html>
