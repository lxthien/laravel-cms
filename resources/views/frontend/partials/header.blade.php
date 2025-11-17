<header class="bg-white shadow-md">
    <div class="container mx-auto px-4">
        <!-- Top Bar -->
        <div class="flex justify-between items-center py-4 border-b">
            <div class="text-2xl font-bold text-blue-600">
                <a href="{{ route('home') }}">{{ config('app.name') }}</a>
            </div>
            
            <div class="flex items-center gap-4">
                <!-- Search Form -->
                <form action="{{ route('search') }}" method="GET" class="flex">
                    <input type="text" name="q" placeholder="Tìm kiếm..." 
                           class="px-4 py-2 border rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           value="{{ request('q') }}">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-r-md hover:bg-blue-700">
                        Tìm
                    </button>
                </form>
                
                @auth
                    <a href="{{ route('admin.dashboard') }}" class="text-gray-700 hover:text-blue-600">
                        Admin Panel
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-700 hover:text-blue-600">
                            Đăng xuất
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600">
                        Đăng nhập
                    </a>
                @endauth
            </div>
        </div>
        
        <!-- Main Navigation -->
        <nav class="py-4">
            <ul class="flex gap-6">
                <li>
                    <a href="{{ route('home') }}" 
                       class="text-gray-700 hover:text-blue-600 font-medium {{ request()->routeIs('home') ? 'text-blue-600' : '' }}">
                        Trang Chủ
                    </a>
                </li>
                
                @foreach(\App\Models\Category::where('status', 1)->whereNull('parent_id')->orderBy('order')->limit(6)->get() as $category)
                <li>
                    <a href="{{ route('category.show', $category->slug) }}" 
                       class="text-gray-700 hover:text-blue-600 font-medium">
                        {{ $category->name }}
                    </a>
                </li>
                @endforeach
            </ul>
        </nav>
    </div>
</header>
