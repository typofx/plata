function validateInput(input) {
            var regex = /^[a-zA-Z0-9]*$/;
            if (!regex.test(input.value)) {
                alert("Please enter only alphanumeric characters.");
                input.value = '';
            }
        }
