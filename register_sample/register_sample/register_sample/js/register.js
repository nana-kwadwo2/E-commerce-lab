$(document).ready(function() {
    $('#register-form').submit(function(e) {
        e.preventDefault();

        // Get form values
        const name = $('#name').val().trim();
        const email = $('#email').val().trim();
        const password = $('#password').val();
        const contact = $('#contact').val().trim();
        const country = $('#country').val().trim();
        const city = $('#city').val().trim();
        const role = $('input[name="role"]:checked').val();

        // Basic validation
        if (name === '' || email === '' || password === '' || contact === '' || country === '' || city === '') {
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                text: 'Please fill in all required fields!',
            });
            return;
        }

        // Email validation using regex
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            Swal.fire({
                icon: 'error',
                title: 'Invalid Email',
                text: 'Please enter a valid email address!',
            });
            return;
        }

        // Password validation using regex
        if (password.length < 6 || 
            !password.match(/[a-z]/) || 
            !password.match(/[A-Z]/) || 
            !password.match(/[0-9]/)) {
            Swal.fire({
                icon: 'error',
                title: 'Invalid Password',
                text: 'Password must be at least 6 characters long and contain at least one lowercase letter, one uppercase letter, and one number!',
            });
            return;
        }

        // Contact number validation using regex
        const contactRegex = /^[0-9+\-\s()]+$/;
        if (!contactRegex.test(contact)) {
            Swal.fire({
                icon: 'error',
                title: 'Invalid Contact',
                text: 'Please enter a valid contact number!',
            });
            return;
        }

        // Name validation (letters and spaces only)
        const nameRegex = /^[a-zA-Z\s]+$/;
        if (!nameRegex.test(name)) {
            Swal.fire({
                icon: 'error',
                title: 'Invalid Name',
                text: 'Name should contain only letters and spaces!',
            });
            return;
        }

        // Show loading state
        const submitBtn = $('#register-btn');
        const spinner = $('#loading-spinner');
        submitBtn.prop('disabled', true);
        spinner.removeClass('d-none');

        // AJAX request
        $.ajax({
            url: '../actions/register_customer_action.php',
            type: 'POST',
            dataType: 'json',
            data: {
                name: name,
                email: email,
                password: password,
                contact: contact,
                country: country,
                city: city,
                role: role
            },
            success: function(response) {
                // Hide loading state
                submitBtn.prop('disabled', false);
                spinner.addClass('d-none');

                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Registration Successful!',
                        text: response.message,
                        showConfirmButton: true,
                        confirmButtonText: 'Go to Login'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = 'login.php';
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Registration Failed',
                        text: response.message,
                    });
                }
            },
            error: function(xhr, status, error) {
                // Hide loading state
                submitBtn.prop('disabled', false);
                spinner.addClass('d-none');

                console.error('AJAX Error:', status, error);
                Swal.fire({
                    icon: 'error',
                    title: 'System Error',
                    text: 'An error occurred! Please try again later.',
                });
            }
        });
    });
});