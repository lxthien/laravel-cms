<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - CMS Website</title>

    <!-- Tailwind CSS hoặc Bootstrap -->
    <script src="https://cdn.tailwindcss.com"></script>

    @vite(['resources/css/app.css', 'resources/js/admin.js'])

    @vite(['resources/css/ckeditor.css', 'resources/js/ckeditor.js'])
    @include('ckfinder::setup')
</head>

<body class="bg-gray-100">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-800 text-white">
            <div class="p-4">
                <h2 class="text-xl font-bold">CMS Admin</h2>
            </div>
            <nav class="mt-4">
                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 hover:bg-gray-700">
                    Dashboard
                </a>

                @can('category-list')
                    <a href="{{ route('admin.categories.index') }}" class="block px-4 py-2 hover:bg-gray-700">
                        Danh Mục
                    </a>
                @endcan

                @can('post-list')
                    <a href="{{ route('admin.posts.index') }}" class="block px-4 py-2 hover:bg-gray-700">
                        Bài Viết
                    </a>
                @endcan

                {{-- Thêm menu item --}}
                <a href="{{ route('admin.pages.index') }}"
                    class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 {{ request()->routeIs('admin.pages.*') ? 'bg-gray-100 border-r-4 border-blue-500' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                    Trang tĩnh
                </a>

                @can('post-list')
                    <a href="{{ route('admin.comments.index') }}" class="block px-4 py-2 hover:bg-gray-700">
                        Bình Luận
                    </a>
                @endcan

                @can('post-list')
                    <a href="{{ route('admin.contacts.index') }}" class="block px-4 py-2 hover:bg-gray-700">
                        Liên Hệ
                    </a>
                @endcan

                @can('post-list')
                    <a href="{{ route('admin.menus.index') }}" class="block px-4 py-2 hover:bg-gray-700">
                        Menu
                    </a>
                @endcan

                @can('post-list')
                    <a href="{{ route('admin.menu-items.index') }}" class="block px-4 py-2 hover:bg-gray-700">
                        Menu Items
                    </a>
                @endcan

                @role('admin')
                <a href="{{ route('admin.settings.index') }}" class="block px-4 py-2 hover:bg-gray-700">
                    Quản Lý Cài Đặt
                </a>
                @endrole

                @role('admin')
                <a href="{{ route('admin.users.index') }}" class="block px-4 py-2 hover:bg-gray-700">
                    Quản Lý Users
                </a>
                @endrole
            </nav>
        </aside>

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