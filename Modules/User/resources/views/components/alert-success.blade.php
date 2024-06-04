@if (session()->has('success'))
    <div class="d-flex justify-content-center mt-3">
        <div class="alert alert-primary" role="alert">
            {{ session('success') }}
        </div>
    </div>
@endif
