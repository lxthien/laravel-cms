<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Trang Chủ') - {{ config('app.name', 'CMS Website') }}</title>

    <!-- SEO Meta Tags -->
    <meta name="description" content="@yield('meta_description', 'Website tin tức công nghệ')">
    <meta name="keywords" content="@yield('meta_keywords', 'laravel, php, tin tức')">

    <!-- Open Graph -->
    <meta property="og:title" content="@yield('og_title', config('app.name'))">
    <meta property="og:description" content="@yield('og_description', 'Website tin tức')">
    <meta property="og:image" content="@yield('og_image', asset('images/default-og.jpg'))">
    <meta property="og:url" content="{{ url()->current() }}">

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