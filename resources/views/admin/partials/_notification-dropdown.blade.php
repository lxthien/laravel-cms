<div id="notification-dropdown"
    class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-xl overflow-hidden z-50 hidden border border-gray-100 transform origin-top-right transition-all duration-200">
    <div class="px-4 py-3 bg-gray-50 border-b border-gray-100 flex justify-between items-center">
        <h3 class="text-sm font-bold text-gray-700">Thông báo</h3>
        <button id="mark-all-read" class="text-xs text-blue-600 hover:text-blue-800 font-medium transition-colors">Đánh
            dấu tất cả đã đọc</button>
    </div>

    <div class="max-h-96 overflow-y-auto" id="notification-list">
        <!-- Notifications will be loaded here via JS -->
        <div class="px-4 py-6 text-center text-gray-400 text-sm">
            Đang tải thông báo...
        </div>
    </div>

    <div class="px-4 py-2 border-t border-gray-100 bg-gray-50 text-center">
        <a href="{{ route('admin.notifications.index') }}"
            class="text-xs font-semibold text-gray-600 hover:text-blue-600 transition-colors block py-1">
            Xem tất cả thông báo
        </a>
    </div>
</div>

<template id="notification-item-template">
    <div
        class="notification-item px-4 py-3 border-b border-gray-100 hover:bg-gray-50 transition-colors relative group cursor-pointer">
        <div class="flex gap-3">
            <div class="flex-shrink-0 mt-1">
                <span class="notification-icon-bg w-8 h-8 rounded-full flex items-center justify-center">
                    <i class="notification-icon text-white text-xs"></i>
                </span>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900 truncate notification-title"></p>
                <p class="text-xs text-gray-500 mt-0.5 line-clamp-2 notification-message"></p>
                <p class="text-[10px] text-gray-400 mt-1 notification-time"></p>
            </div>
            <div class="flex-shrink-0 self-center opacity-0 group-hover:opacity-100 transition-opacity">
                <button class="mark-read-btn text-gray-400 hover:text-blue-600 p-1" title="Đánh dấu đã đọc">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z">
                        </path>
                    </svg>
                </button>
            </div>
        </div>
        <span class="unread-indicator absolute top-3 right-3 w-2 h-2 bg-blue-500 rounded-full"></span>
    </div>
</template>