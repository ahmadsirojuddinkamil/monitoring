<div class="p-5 d-flex justify-content-center">
    <form action="/register" method="POST">
        @csrf

        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" required>

            @error('username')
                <div class="alert alert-danger mt-2">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input type="email" class="form-control" id="email" name="email" required>
            <div id="email" class="form-text">We'll never share your email with anyone else.</div>

            @error('email')
                <div class="alert alert-danger mt-2">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <div class="input-group">
                <input type="password" class="form-control mr-2" id="password" name="password" required>
                <button type="button" class="btn btn-outline-secondary" id="show-hide-password">Show</button>
            </div>
            <div id="password" class="form-text">Use a secure and strong password.</div>

            @error('password')
                <div class="alert alert-danger mt-2">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="flex-container">
            <button type="submit" class="btn btn-primary mr-2">Register</button>
            <div>Already have an account? <a href="/login">Login now!</a></div>
        </div>
    </form>
</div>
