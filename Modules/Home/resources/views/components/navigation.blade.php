<nav class="navbar navbar-expand-lg navbar-dark bg-dark ftco-navbar-light" id="ftco-navbar">
    <div class="container d-flex align-items-center">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav"
            aria-expanded="false" aria-label="Toggle navigation">
            <span class="oi oi-menu"></span> Menu
        </button>

        @guest
            <p class="button-custom order-lg-last mb-0">
                <a href="/login" class="btn btn-secondary py-2 px-3">Login</a>
            </p>
        @endguest

        @auth
            <div class="button-custom order-lg-last mb-0">
                <form action="/logout" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger py-2 px-3">Logout</button>
                </form>
            </div>
        @endauth

        <div class="collapse navbar-collapse" id="ftco-nav">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item {{ Request::is('/') ? 'active' : '' }}">
                    <a href="/" class="nav-link pl-0">Home</a>
                </li>
                <li class="nav-item"><a href="/#about" class="nav-link">About</a></li>
                <li class="nav-item"><a href="/#service" class="nav-link">Service</a></li>
                <li class="nav-item"><a href="/#engineer" class="nav-link">Engineer</a></li>
                <li class="nav-item"><a href="/#testimoni" class="nav-link">Testimoni</a></li>
                <li class="nav-item"><a href="/#comment" class="nav-link">Comment</a></li>
                @auth
                    <li class="nav-item"><a href="/dashboard" class="nav-link">Dashboard</a></li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
