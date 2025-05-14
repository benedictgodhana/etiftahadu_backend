<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Forgot Password | Namib</title>

    <link rel="icon" type="image/png" href="/images/logo.png">

    <!-- Toastr -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
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
            background-color: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(2px);
        }
    </style>
</head>
<body class="futura antialiased">
    <div class="min-h-screen overlay flex items-center justify-center p-4">
        <div class="bg-white/95 backdrop-blur-sm rounded-lg w-full max-w-md p-6 sm:p-8 shadow-xl">

            <!-- Logo -->
            <div class="flex justify-center mb-6">
                <a href="/" class="brand-link">
                    <img src="/images/nch-removebg-preview.png" alt="Namib Logo" class="h-16">
                </a>
            </div>

            <h2 class="text-2xl font-bold text-center text-gray-800 mb-4">Forgot Password</h2>

            <p class="mb-4 text-sm text-gray-600">
                Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.
            </p>

            <!-- Session Status -->
            @if (session('status'))
                <div class="mb-4 text-sm text-green-600">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <!-- Email Address -->
                <div class="space-y-1">
                    <label for="email" class="text-sm font-medium text-gray-700">Email</label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        required
                        autofocus
                        value="{{ old('email') }}"
                        class="w-full rounded-md border border-gray-300 py-2.5 px-3 focus:ring-1 focus:ring-red-500 focus:border-red-500"
                    />
                    @error('email')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mt-6">
                    <button
                        type="submit"
                        class="w-full bg-red-600 hover:bg-red-700 text-white py-2.5 px-4 rounded-md transition-colors flex justify-center items-center"
                    >
                        <i class="fas fa-envelope mr-2"></i>
                        Email Password Reset Link
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        @if (session('status'))
            toastr.success("{{ session('status') }}", 'Success');
        @endif
        @if ($errors->any())
            toastr.error("Please fix the errors in the form.", 'Error');
        @endif
    </script>
</body>
</html>
