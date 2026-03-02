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
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&family=Montserrat:wght@600;700&family=Roboto:wght@400;500;700&family=Roboto+Condensed:wght@400;700&display=swap"
        rel="stylesheet">

    <!-- Tailwind Configuration (Removed CDN) -->

    @vite(['resources/sass/frontend.scss', 'resources/js/frontend.js'])

    @stack('styles')
</head>

<body class="bg-white text-slate-800 font-sans @stack('body-class')">
    <!-- Header -->
    @include('frontend.partials.header')

    <!-- Breadcrumb -->
    @yield('breadcrumb')

    <!-- Main Content -->
    @hasSection('full-width')
        @yield('content')
    @else
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
    @endif

    <!-- Footer -->
    @include('frontend.partials.footer')

    <!-- Floating Contacts & Back to Top -->
    @include('frontend.partials._floating_contacts')

    @stack('scripts')
</body>

</html>