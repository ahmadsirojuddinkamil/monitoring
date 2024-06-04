@if (session()->has('info'))
    <div class="d-flex justify-content-center mt-3">
        <div class="alert alert-warning" role="alert">
            {{ session('info') }}
        </div>
    </div>
@endif
