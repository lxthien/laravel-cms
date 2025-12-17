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

    <!-- Styles -->
    <script src="https://cdn.tailwindcss.com"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>

<body class="bg-gray-50">
    <!-- Header -->
    @include('frontend.partials.header')

    <!-- Breadcrumb -->
    @yield('breadcrumb')

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
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