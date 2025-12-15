<aside class="w-60 bg-[#1d2327] text-[#c3c4c7] min-h-screen flex-shrink-0">
    <!-- Logo/Site Name -->
    <div class="h-12 bg-[#101517] flex items-center px-4">
        <a href="{{ route('home') }}" class="flex items-center gap-2 text-white hover:text-[#00b9eb] transition-colors"
            target="_blank">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                <path
                    d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z" />
            </svg>
            <span class="font-semibold text-sm">{{ config('app.name', 'CMS') }}</span>
        </a>
    </div>

    <!-- Navigation -->
    <nav class="mt-2">
        <!-- Dashboard -->
        <a href="{{ route('admin.dashboard') }}"
            class="flex items-center px-4 py-2.5 text-sm hover:bg-[#2c3338] hover:text-[#72aee6] transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-[#2271b1] text-white' : '' }}">
            <svg class="w-5 h-5 mr-3 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            Bảng tin
        </a>

        <!-- Separator -->
        <div class="border-t border-[#3c434a] my-2 mx-3"></div>

        <!-- Posts -->
        @can('post-list')
            <a href="{{ route('admin.posts.index') }}"
                class="flex items-center px-4 py-2.5 text-sm hover:bg-[#2c3338] hover:text-[#72aee6] transition-colors {{ request()->routeIs('admin.posts.*') ? 'bg-[#2271b1] text-white' : '' }}">
                <svg class="w-5 h-5 mr-3 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                </svg>
                Bài viết
            </a>
        @endcan

        <!-- Pages -->
        <a href="{{ route('admin.pages.index') }}"
            class="flex items-center px-4 py-2.5 text-sm hover:bg-[#2c3338] hover:text-[#72aee6] transition-colors {{ request()->routeIs('admin.pages.*') ? 'bg-[#2271b1] text-white' : '' }}">
            <svg class="w-5 h-5 mr-3 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
            </svg>
            Trang
        </a>

        <!-- Categories -->
        @can('category-list')
            <a href="{{ route('admin.categories.index') }}"
                class="flex items-center px-4 py-2.5 text-sm hover:bg-[#2c3338] hover:text-[#72aee6] transition-colors {{ request()->routeIs('admin.categories.*') ? 'bg-[#2271b1] text-white' : '' }}">
                <svg class="w-5 h-5 mr-3 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                </svg>
                Danh mục
            </a>
        @endcan

        <!-- Comments -->
        @can('post-list')
            <a href="{{ route('admin.comments.index') }}"
                class="flex items-center px-4 py-2.5 text-sm hover:bg-[#2c3338] hover:text-[#72aee6] transition-colors {{ request()->routeIs('admin.comments.*') ? 'bg-[#2271b1] text-white' : '' }}">
                <svg class="w-5 h-5 mr-3 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
                Bình luận
            </a>
        @endcan

        <!-- Separator -->
        <div class="border-t border-[#3c434a] my-2 mx-3"></div>

        <!-- Appearance Section -->
        @can('post-list')
            <a href="{{ route('admin.menus.index') }}"
                class="flex items-center px-4 py-2.5 text-sm hover:bg-[#2c3338] hover:text-[#72aee6] transition-colors {{ request()->routeIs('admin.menus.*') || request()->routeIs('admin.menu-items.*') ? 'bg-[#2271b1] text-white' : '' }}">
                <svg class="w-5 h-5 mr-3 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                Menu
            </a>
        @endcan

        <!-- Contacts -->
        @can('post-list')
            <a href="{{ route('admin.contacts.index') }}"
                class="flex items-center px-4 py-2.5 text-sm hover:bg-[#2c3338] hover:text-[#72aee6] transition-colors {{ request()->routeIs('admin.contacts.*') ? 'bg-[#2271b1] text-white' : '' }}">
                <svg class="w-5 h-5 mr-3 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                Liên hệ
            </a>
        @endcan

        <!-- Separator - Admin Only -->
        @role('admin')
        <div class="border-t border-[#3c434a] my-2 mx-3"></div>

        <!-- Users -->
        <a href="{{ route('admin.users.index') }}"
            class="flex items-center px-4 py-2.5 text-sm hover:bg-[#2c3338] hover:text-[#72aee6] transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-[#2271b1] text-white' : '' }}">
            <svg class="w-5 h-5 mr-3 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
            </svg>
            Thành viên
        </a>

        <!-- Settings -->
        <a href="{{ route('admin.settings.index') }}"
            class="flex items-center px-4 py-2.5 text-sm hover:bg-[#2c3338] hover:text-[#72aee6] transition-colors {{ request()->routeIs('admin.settings.*') ? 'bg-[#2271b1] text-white' : '' }}">
            <svg class="w-5 h-5 mr-3 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            Cài đặt
        </a>
        @endrole
    </nav>

    <!-- Footer - Collapse Button (optional) -->
    <div class="absolute bottom-0 left-0 w-60 border-t border-[#3c434a] bg-[#1d2327]">
        <a href="{{ route('home') }}" target="_blank"
            class="flex items-center px-4 py-3 text-xs text-[#8c8f94] hover:text-[#72aee6] transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
            </svg>
            Xem trang web
        </a>
    </div>
</aside>