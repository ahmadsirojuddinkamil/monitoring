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
    <title>Loggingpedia | user list</title>

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
                        <h4>User List</h4>
                        <h6>Manage your User</h6>
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
                            <table class="table  datanew">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Profile</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td class="profile-user">
                                                <a href="javascript:void(0);" class="profile-user">
                                                    <img src="{{ asset($user->profile) }}" alt="profile-user"
                                                        height="50" width="50">
                                                </a>
                                            </td>
                                            <td>{{ $user->username }}</td>
                                            <td>{{ $user->email }}</td>
                                            @if ($user->status == 'bayar')
                                                <td>
                                                    <p class="bg-success d-inline-block px-2 py-1 m-0 text-white">member
                                                    </p>
                                                </td>
                                            @else
                                                <td>
                                                    <p class="bg-warning d-inline-block px-2 py-1 m-0 text-white">non
                                                        member
                                                    </p>
                                                </td>
                                            @endif
                                            <td>
                                                <a class="me-3" href="/user/{{ $user->uuid }}/edit">
                                                    <img src="{{ asset('assets/dashboard/img/icons/edit.svg') }}"
                                                        alt="img">
                                                </a>

                                                <a class="action-button" data-bs-toggle="modal"
                                                    data-bs-target="#exampleModal{{ $user->uuid }}">
                                                    <img src="{{ asset('assets/dashboard/img/icons/delete.svg') }}"
                                                        alt="img">
                                                </a>

                                                <div class="modal fade" id="exampleModal{{ $user->uuid }}"
                                                    tabindex="-1" aria-labelledby="exampleModalLabel"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Are you
                                                                    sure?</h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>

                                                            <div class="modal-body">
                                                                This user account will be deleted!
                                                            </div>

                                                            <div class="modal-footer d-flex justify-content-end">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">No</button>

                                                                <form action="/user/{{ $user->uuid }}" method="POST"
                                                                    class="action-form">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit"
                                                                        class="btn btn-submit me-2">Ya</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

    @include('dashboard::bases.js')
</body>

</html>
