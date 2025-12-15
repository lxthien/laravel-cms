<header class="bg-white shadow-md">
    <div class="container mx-auto px-4">
        <!-- Top Bar -->
        <div class="flex justify-between items-center py-4 border-b">
            <div class="text-2xl font-bold text-blue-600">
                <a href="{{ route('home') }}">{{ config('app.name') }}</a>
            </div>

            <div class="flex items-center gap-4">
                <!-- Search Form with Suggestions -->
                <div class="relative" id="search-container">
                    <form action="{{ route('search') }}" method="GET" class="flex" id="header-search-form">
                        <input type="text" name="q" id="header-search-input" placeholder="Tìm kiếm..."
                            class="px-4 py-2 border rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-500 w-64"
                            value="{{ request('q') }}" autocomplete="off"
                            data-suggestions-url="{{ route('search.suggestions') }}"
                            data-search-url="{{ route('search') }}">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-r-md hover:bg-blue-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>
                    </form>

                    <!-- Suggestions Dropdown -->
                    <div id="search-suggestions"
                        class="absolute top-full left-0 right-0 bg-white border border-gray-200 rounded-b-lg shadow-lg z-50 hidden mt-0.5">
                        <div id="suggestions-list" class="max-h-96 overflow-y-auto"></div>
                        <div id="suggestions-loading" class="p-4 text-center text-gray-500 hidden">
                            <svg class="animate-spin h-5 w-5 mx-auto text-blue-600" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                        </div>
                        <div id="suggestions-empty" class="p-4 text-center text-gray-500 hidden">
                            Không tìm thấy kết quả
                        </div>
                    </div>
                </div>

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
            <x-menu :items="$headerMenu ? $headerMenu->items : collect()" />
        </nav>
    </div>
</header>