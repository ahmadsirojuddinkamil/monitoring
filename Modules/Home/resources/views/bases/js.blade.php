<script src="{{ asset('assets/home/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/home/js/jquery-migrate-3.0.1.min.js') }}"></script>
<script src="{{ asset('assets/home/js/popper.min.js') }}"></script>
<script src="{{ asset('assets/home/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/home/js/jquery.easing.1.3.js') }}"></script>
<script src="{{ asset('assets/home/js/jquery.waypoints.min.js') }}"></script>
<script src="{{ asset('assets/home/js/jquery.stellar.min.js') }}"></script>
<script src="{{ asset('assets/home/js/owl.carousel.min.js') }}"></script>
<script src="{{ asset('assets/home/js/jquery.magnific-popup.min.js') }}"></script>
<script src="{{ asset('assets/home/js/aos.js') }}"></script>
<script src="{{ asset('assets/home/js/jquery.animateNumber.min.js') }}"></script>
<script src="{{ asset('assets/home/js/bootstrap-datepicker.js') }}"></script>
<script src="{{ asset('assets/home/js/jquery.timepicker.min.js') }}"></script>
<script src="{{ asset('assets/home/js/scrollax.min.js') }}"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&sensor=false"></script>
<script src="{{ asset('assets/home/js/google-map.js') }}"></script>
<script src="{{ asset('assets/home/js/main.js') }}"></script>

{{-- show hide password --}}
<script>
    const passwordInput = document.getElementById('password');
    const showHideButton = document.getElementById('show-hide-password');

    showHideButton.addEventListener('click', function() {
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            showHideButton.textContent = 'Hide';
        } else {
            passwordInput.type = 'password';
            showHideButton.textContent = 'Show';
        }
    });
</script>

{{-- automation scrool navbar --}}
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const navbarLinks = document.querySelectorAll('.navbar-nav a[href^="#"]');

        navbarLinks.forEach(function(navbarLink) {
            navbarLink.addEventListener("click", function(event) {
                event.preventDefault();
                const targetId = this.getAttribute("href").substring(1);
                const targetElement = document.getElementById(targetId);
                if (targetElement) {
                    targetElement.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    });
</script>
