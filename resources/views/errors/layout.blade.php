<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - {{ config('app.name') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Styles -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-20px)' },
                        }
                    }
                }
            }
        }
    </script>

    <style>
        body {
            background: radial-gradient(circle at top right, #f8fafc 0%, #f1f5f9 50%, #e2e8f0 100%);
            min-height: 100vh;
        }

        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.07);
        }
    </style>
</head>

<body class="flex items-center justify-center p-6 antialiased">
    <div class="max-w-xl w-full text-center">
        <!-- Error Code Decoration -->
        <div class="relative inline-block mb-12">
            <div class="absolute -inset-4 bg-blue-100 rounded-full blur-2xl opacity-50 animate-pulse-slow"></div>
            <h1
                class="relative text-9xl font-extrabold text-transparent bg-clip-text bg-gradient-to-br from-blue-600 to-indigo-800 drop-shadow-sm">
                @yield('code')
            </h1>
        </div>

        <!-- Content Card -->
        <div class="glass rounded-3xl p-8 md:p-12 transition-all duration-300 hover:shadow-xl">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">
                @yield('message')
            </h2>
            <p class="text-lg text-gray-600 mb-10 leading-relaxed">
                @yield('description')
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ url('/') }}"
                    class="w-full sm:w-auto px-8 py-4 bg-blue-600 text-white font-semibold rounded-2xl hover:bg-blue-700 transition-all duration-300 shadow-lg shadow-blue-200 hover:scale-105">
                    Về Trang Chủ
                </a>
                <button onclick="window.history.back()"
                    class="w-full sm:w-auto px-8 py-4 bg-white text-gray-700 font-semibold rounded-2xl border border-gray-200 hover:bg-gray-50 transition-all duration-300 hover:scale-105">
                    Quay Lại
                </button>
            </div>
        </div>

        <!-- Decoration elements -->
        <div class="mt-12 text-gray-400 text-sm font-medium tracking-widest uppercase">
            {{ config('app.name') }} &bull; System Status
        </div>
    </div>

    <!-- Floating bubbles background decoration -->
    <div class="fixed inset-0 -z-10 overflow-hidden pointer-events-none">
        <div class="absolute top-[10%] left-[15%] w-64 h-64 bg-blue-100 rounded-full blur-3xl opacity-30 animate-float"
            style="animation-delay: 0s;"></div>
        <div class="absolute bottom-[20%] right-[10%] w-96 h-96 bg-indigo-100 rounded-full blur-3xl opacity-30 animate-float"
            style="animation-delay: -2s;"></div>
        <div class="absolute bottom-[40%] left-[5%] w-48 h-48 bg-purple-100 rounded-full blur-3xl opacity-20 animate-float"
            style="animation-delay: -4s;"></div>
    </div>
</body>

</html>