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
    <title>Loggingpedia | secret generator</title>

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
                        <h4>Secret Generator</h4>
                        <h6>Manage your environment key</h6>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        @if ($secret)
                            @foreach ($secret as $key)
                                <p>{{ $key }}</p>
                            @endforeach
                        @endif

                        <form action="/secret-generator" method="POST">
                            @csrf

                            <button type="submit" class=" btn btn-primary">Generate</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>

    </div>

    @include('dashboard::bases.js')
</body>

</html>
