<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Log in | Namib</title>

    <link rel="icon" type="image/png" href="/images/logo.png">

    <!-- Toastr for notifications -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome for the login icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        @font-face {
            font-family: 'Futura LT';
            src: url('/fonts/futura-lt/FuturaLT-Book.ttf') format('woff2'),
                 url('/fonts/futura-lt/FuturaLT.ttf') format('woff'),
                 url('/fonts/futura-lt/FuturaLT-Condensed.ttf') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        body {
            margin: 0;
            font-family: 'Futura LT', sans-serif;
            background-image: url('/images/IMG_0333-scaled.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            height: 100vh;
        }

        .overlay {
            background-color: rgba(0, 0, 0, 0.4); /* Lighter overlay for better background visibility */
            backdrop-filter: blur(2px); /* Lighter blur effect */
        }

        .toggle-password {
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .toggle-password:hover {
            color: #dc2626;
        }
    </style>
</head>
<body class="futura antialiased">
    <div class="min-h-screen overlay flex items-center justify-center p-4">
        <div class="bg-white/95 backdrop-blur-sm rounded-lg w-full max-w-md p-6 sm:p-8 shadow-xl">

            <!-- Maritime Logo -->
            <div class="flex justify-center mb-6">
                <a href="/" class="brand-link">
                    <img src="/images/nch-removebg-preview.png" alt="Maritime Freight Logo" class="h-16">
                </a>
            </div>

            <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Account Login</h2>

            <!-- Status Message Container -->
            <div id="status-message" class="mb-4 text-sm text-green-600 hidden"></div>

            <form id="loginForm" method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Username -->
                <div class="space-y-1">
                    <label for="username" class="text-sm font-medium text-gray-700">Username</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                            <i class="fas fa-user"></i>
                        </span>
                        <input
                            id="username"
                            class="pl-10 w-full rounded-md border border-gray-300 py-2.5 px-3 focus:ring-1 focus:ring-red-500 focus:border-red-500"
                            type="text"
                            name="username"
                            required
                            autofocus />
                        <div class="mt-1 text-red-500 text-sm" id="username-error"></div>
                    </div>
                </div>

                <!-- Password with toggle button -->
                <div class="mt-4 space-y-1">
                    <label for="password" class="text-sm font-medium text-gray-700">Password</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input
                            id="password"
                            class="pl-10 pr-10 w-full rounded-md border border-gray-300 py-2.5 px-3 focus:ring-1 focus:ring-red-500 focus:border-red-500"
                            type="password"
                            name="password"
                            required />
                        <span class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 toggle-password" id="togglePassword">
                            <i class="fas fa-eye" id="passwordIcon"></i>
                        </span>
                        <div class="mt-1 text-red-500 text-sm" id="password-error"></div>
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="mt-4 flex items-center justify-between">
                    <label class="flex items-center text-sm text-gray-600">
                        <input type="checkbox" name="remember" class="mr-2 border-gray-300 rounded">
                        Remember me
                    </label>
                    <a href="{{ route('password.request') }}" class="text-sm text-red-600 hover:text-red-700">
                        Forgot password?
                    </a>
                </div>

                <div class="mt-6">
                    <button id="loginButton" type="submit"
                    class="w-full flex items-center justify-center bg-red-600 hover:bg-red-700 text-white py-2.5 px-4 rounded-md transition-colors">
                    <svg id="loadingSpinner" class="hidden animate-spin h-5 w-5 mr-2 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 0116 0h-2a6 6 0 00-12 0H4z">
                        </path>
                    </svg>
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    <span id="buttonText">Sign In</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Password toggle functionality
        document.getElementById("togglePassword").addEventListener("click", function() {
            const passwordInput = document.getElementById("password");
            const passwordIcon = document.getElementById("passwordIcon");

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                passwordIcon.classList.remove("fa-eye");
                passwordIcon.classList.add("fa-eye-slash");
            } else {
                passwordInput.type = "password";
                passwordIcon.classList.remove("fa-eye-slash");
                passwordIcon.classList.add("fa-eye");
            }
        });

        // Form submission
        document.getElementById("loginForm").addEventListener("submit", async function(event) {
            event.preventDefault(); // Prevent default form submission

            const formData = new FormData(this);
            const loginButton = document.getElementById("loginButton");
            const buttonText = document.getElementById("buttonText");
            const loadingSpinner = document.getElementById("loadingSpinner");

            // Clear previous errors
            document.getElementById('username-error').textContent = "";
            document.getElementById('password-error').textContent = "";

            // Show loading state
            loginButton.disabled = true;
            buttonText.textContent = "Signing In...";
            loadingSpinner.classList.remove("hidden");

            toastr.info('Logging in, please wait...', 'Processing');

            try {
                const response = await fetch("{{ route('login') }}", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('input[name=_token]').value,
                        "Accept": "application/json",
                    },
                    body: formData
                });

                const isJson = response.headers.get("content-type")?.includes("application/json");

                if (!response.ok) {
                    if (isJson) {
                        const data = await response.json();
                        if (data.errors) {
                            if (data.errors.username) {
                                document.getElementById('username-error').textContent = data.errors.username[0];
                            }
                            if (data.errors.password) {
                                document.getElementById('password-error').textContent = data.errors.password[0];
                            }
                            toastr.error('Invalid credentials. Please try again.', 'Login Failed');
                        }
                    } else {
                        toastr.error('Something went wrong. Please try again.', 'Error');
                    }
                    return;
                }

                // If response is successful
                toastr.success('Login successful! Redirecting...', 'Success');
                setTimeout(() => {
                    window.location.href = "{{ route('dashboard') }}";
                }, 2000);

            } catch (error) {
                console.error("Login error:", error);
                toastr.error('Something went wrong. Please try again.', 'Error');
            } finally {
                // Reset button state after request completes
                loginButton.disabled = false;
                buttonText.textContent = "Sign In";
                loadingSpinner.classList.add("hidden");
            }
        });
    </script>
</body>
</html>
