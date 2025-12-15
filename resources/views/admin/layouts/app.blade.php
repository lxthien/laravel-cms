<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - CMS Website</title>

    <!-- Tailwind CSS hoặc Bootstrap -->
    <script src="https://cdn.tailwindcss.com"></script>

    @vite(['resources/css/app.css', 'resources/css/admin.css', 'resources/js/admin.js'])

    @vite(['resources/css/ckeditor.css', 'resources/js/ckeditor.js'])
    @include('ckfinder::setup')
</head>

<body class="bg-gray-100">
    <div class="min-h-screen flex">
        @include('admin.partials.sidebar')

        <!-- Main Content -->
        <div class="flex-1">
            <!-- Header -->
            <header class="bg-white shadow">
                <div class="flex justify-between items-center px-6 py-4">
                    <h1 class="text-2xl font-semibold">@yield('page-title', 'Dashboard')</h1>

                    <div class="flex items-center gap-4">
                        <span>{{ auth()->user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-red-600 hover:text-red-800">
                                Đăng xuất
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="p-6">
                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>

</html>