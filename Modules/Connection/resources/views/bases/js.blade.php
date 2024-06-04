<script>
    document.getElementById('endpoint').addEventListener('input', function() {
        var endpointValue = this.value;
        var otherInputs = document.querySelectorAll('.form-control:not(#endpoint)');

        otherInputs.forEach(function(input) {
            var originalValue = input.getAttribute('data-original-value');
            var newValue = endpointValue + originalValue;
            input.value = newValue;
        });
    });
</script>
