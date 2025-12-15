import './bootstrap';

/**
 * Search Suggestions - jQuery Autocomplete
 * Gợi ý tìm kiếm khi nhập từ khóa
 */
$(document).ready(function () {
    const $searchInput = $('#header-search-input');
    const $suggestionsContainer = $('#search-suggestions');
    const $suggestionsList = $('#suggestions-list');
    const $suggestionsLoading = $('#suggestions-loading');
    const $suggestionsEmpty = $('#suggestions-empty');

    // Kiểm tra xem elements có tồn tại không
    if ($searchInput.length === 0) {
        return;
    }

    let debounceTimer;
    let currentIndex = -1;

    // Lấy config URLs từ data attributes
    const suggestionsUrl = $searchInput.data('suggestions-url');
    const searchUrl = $searchInput.data('search-url');

    /**
     * Fetch suggestions từ API
     */
    function fetchSuggestions(query) {
        if (query.length < 2) {
            hideSuggestions();
            return;
        }

        showLoading();

        $.ajax({
            url: suggestionsUrl,
            type: 'GET',
            data: { q: query },
            dataType: 'json',
            success: function (suggestions) {
                hideLoading();

                if (suggestions.length === 0) {
                    showEmpty();
                } else {
                    renderSuggestions(suggestions, query);
                }
            },
            error: function (xhr, status, error) {
                console.error('Search error:', error);
                hideLoading();
                hideSuggestions();
            }
        });
    }

    /**
     * Render danh sách suggestions
     */
    function renderSuggestions(suggestions, query) {
        $suggestionsList.empty();
        $suggestionsEmpty.addClass('hidden');

        $.each(suggestions, function (index, item) {
            const highlightedTitle = highlightQuery(item.title, query);

            const imageHtml = item.image
                ? `<img src="${item.image}" alt="" class="w-12 h-12 object-cover rounded flex-shrink-0">`
                : `<div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>`;

            const $suggestionItem = $(`
                <a href="${item.url}" class="flex items-center gap-3 px-4 py-3 hover:bg-blue-50 border-b border-gray-100 last:border-b-0 transition-colors suggestion-item" data-index="${index}">
                    ${imageHtml}
                    <div class="flex-1 min-w-0">
                        <div class="text-gray-800 font-medium truncate">${highlightedTitle}</div>
                    </div>
                    <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            `);

            $suggestionsList.append($suggestionItem);
        });

        // Add "View all results" link
        const $viewAllLink = $(`
            <a href="${searchUrl}?q=${encodeURIComponent(query)}" class="block px-4 py-3 text-center text-blue-600 hover:bg-blue-50 font-medium border-t border-gray-200">
                Xem tất cả kết quả cho "${query}" →
            </a>
        `);
        $suggestionsList.append($viewAllLink);

        $suggestionsContainer.removeClass('hidden');
        currentIndex = -1;
    }

    /**
     * Highlight từ khóa matching trong title
     */
    function highlightQuery(text, query) {
        const escapedQuery = query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
        const regex = new RegExp(`(${escapedQuery})`, 'gi');
        return text.replace(regex, '<mark class="bg-yellow-200 px-0.5 rounded">$1</mark>');
    }

    /**
     * Show/Hide helper functions
     */
    function showLoading() {
        $suggestionsContainer.removeClass('hidden');
        $suggestionsLoading.removeClass('hidden');
        $suggestionsList.empty();
        $suggestionsEmpty.addClass('hidden');
    }

    function hideLoading() {
        $suggestionsLoading.addClass('hidden');
    }

    function showEmpty() {
        $suggestionsEmpty.removeClass('hidden');
        $suggestionsList.empty();
    }

    function hideSuggestions() {
        $suggestionsContainer.addClass('hidden');
        currentIndex = -1;
    }

    /**
     * Keyboard navigation
     */
    function handleKeyboard(e) {
        const $items = $suggestionsList.find('.suggestion-item');

        switch (e.key) {
            case 'ArrowDown':
                e.preventDefault();
                currentIndex = Math.min(currentIndex + 1, $items.length - 1);
                updateActiveItem($items);
                break;

            case 'ArrowUp':
                e.preventDefault();
                currentIndex = Math.max(currentIndex - 1, -1);
                updateActiveItem($items);
                break;

            case 'Enter':
                if (currentIndex >= 0) {
                    e.preventDefault();
                    window.location.href = $items.eq(currentIndex).attr('href');
                }
                break;

            case 'Escape':
                hideSuggestions();
                $searchInput.blur();
                break;
        }
    }

    function updateActiveItem($items) {
        $items.removeClass('bg-blue-50');
        if (currentIndex >= 0) {
            $items.eq(currentIndex).addClass('bg-blue-50');
        }
    }

    /**
     * Debounce function để tránh gọi API quá nhiều
     */
    function debounce(func, delay) {
        return function (...args) {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => func.apply(this, args), delay);
        };
    }

    // Debounced fetch với delay 300ms
    const debouncedFetch = debounce(fetchSuggestions, 300);

    /**
     * Event Listeners
     */

    // Input event - khi user gõ
    $searchInput.on('input', function () {
        debouncedFetch($(this).val().trim());
    });

    // Focus event - hiển thị lại suggestions nếu có text
    $searchInput.on('focus', function () {
        const query = $(this).val().trim();
        if (query.length >= 2) {
            debouncedFetch(query);
        }
    });

    // Keyboard navigation
    $searchInput.on('keydown', handleKeyboard);

    // Click outside để đóng suggestions
    $(document).on('click', function (e) {
        if (!$(e.target).closest('#search-container').length) {
            hideSuggestions();
        }
    });
});
