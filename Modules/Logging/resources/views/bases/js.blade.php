<script>
    function togglePasswordVisibility() {
        var passwordField = document.getElementById("password");
        var togglePassword = document.querySelector(".toggle-password");

        if (passwordField.type === "password") {
            passwordField.type = "text";
            togglePassword.classList.remove("fa-eye-slash");
            togglePassword.classList.add("fa-eye");
        } else {
            passwordField.type = "password";
            togglePassword.classList.remove("fa-eye");
            togglePassword.classList.add("fa-eye-slash");
        }
    }
</script>
