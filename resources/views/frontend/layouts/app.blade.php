<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- SEO Meta được inject từ child views --}}
    @stack('seo-meta')

    {{-- Schema.org JSON-LD --}}
    @hasSection('schema')
        @yield('schema')
    @endif

    <!-- Fonts & Preconnect -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&family=Montserrat:wght@600;700&display=swap"
        rel="stylesheet">

    <!-- Tailwind CDN with custom theme (quick prototype) -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            DEFAULT: '#0f172a', /* slate-900 */
                            light: '#334155'
                        },
                        accent: '#f59e0b' /* amber-500 - construction accent */
                    },
                    fontFamily: {
                        sans: ['Inter', 'ui-sans-serif', 'system-ui'],
                        heading: ['Montserrat', 'Inter', 'sans-serif']
                    }
                }
            }
        }
    </script>
    <script src="https://cdn.tailwindcss.com"></script>

    @vite(['resources/sass/frontend.scss', 'resources/js/frontend.js'])

    @stack('styles')
</head>

<body class="bg-white text-slate-800 font-sans">
    <!-- Header -->
    @include('frontend.partials.header')

    <!-- Breadcrumb -->
    @yield('breadcrumb')

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 py-8">
        <div class="grid grid-cols-12 gap-6">
            <!-- Content Area -->
            <div class="col-span-12 lg:col-span-8">
                @yield('content')
            </div>

            <!-- Sidebar -->
            <aside class="col-span-12 lg:col-span-4">
                @include('frontend.partials.sidebar')
            </aside>
        </div>
    </main>

    <!-- Footer -->
    @include('frontend.partials.footer')

    @stack('scripts')
</body>

</html>