<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="description" content="Lab Management System">
    <meta name="keywords" content="admin, dashboard, school, college, lab, inventory, management">
    <meta name="author" content="Lab Management System">
    <meta name="robots" content="noindex, nofollow">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Security headers - dynamically generated instead of using meta tag to avoid CSP bypass -->
    <title>@yield('title', 'Auth') | Lab Management System</title>

    <link rel="shortcut icon" href="{{ asset('assets/img/favicon.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="/assets/plugins/fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="/assets/plugins/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/plugins/toastr/toastr.css') }}">

</head>

<body>
    <div class="main-wrapper login-body">
        <div class="login-wrapper">
            <div class="container">
                @yield('auth-content')
            </div>
        </div>
    </div>

    <!-- Script files - using CDNs for better performance -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"
        integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
        integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous">
    </script>
    <script src="{{ asset('assets/plugins/toastr/toastr.min.js') }}"></script>

    <!-- Security and validation utilities -->
    <script src="{{ asset('assets/js/security-helper.js') }}"></script>
    <script src="{{ asset('assets/js/form-validation.js') }}"></script>

    @stack('script')

    <script>
        // Initialize security features for Laravel 12
        document.addEventListener('DOMContentLoaded', function() {
            // Check for HTTPS
            if (window.location.protocol !== 'https:' && !['localhost', '127.0.0.1'].includes(window.location
                    .hostname)) {
                console.warn('This site should be accessed over HTTPS for better security.');
            }

            // Add secure attributes to all cookies
            document.cookie = "SameSite=Strict; Secure";

            // Set default security options for AJAX requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                },
                xhrFields: {
                    withCredentials: true
                },
                cache: false
            });

            // Add event listeners to hide alerts
            document.querySelectorAll('.alert .close').forEach(function(element) {
                element.addEventListener('click', function() {
                    this.closest('.alert').remove();
                });
            });

            // Initialize the security helper if available
            if (typeof SecurityHelper !== 'undefined') {
                SecurityHelper.initialize();
            }
        });
    </script>
</body>

</html>
