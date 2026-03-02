/**
 * Search Suggestions Module (jQuery)
 * Gợi ý tìm kiếm khi nhập từ khóa vào ô search header.
 */

import $ from 'jquery';

$(function () {
    var $input = $('#header-search-input');
    var $container = $('#search-suggestions');
    var $list = $('#suggestions-list');
    var $loading = $('#suggestions-loading');
    var $empty = $('#suggestions-empty');

    if (!$input.length) return;

    var suggestionsUrl = $input.data('suggestions-url');
    var searchUrl = $input.data('search-url');
    var debounceTimer;
    var currentIndex = -1;

    // ── Fetch & Render ────────────────────────────────────────────────────
    function fetchSuggestions(query) {
        if (query.length < 2) { hideSuggestions(); return; }

        showLoading();

        $.getJSON(suggestionsUrl, { q: query })
            .done(function (data) {
                hideLoading();
                data.length === 0 ? showEmpty() : renderSuggestions(data, query);
            })
            .fail(function () {
                hideLoading();
                hideSuggestions();
            });
    }

    function renderSuggestions(items, query) {
        $list.empty();
        $empty.addClass('is-hidden');

        $.each(items, function (i, item) {
            var title = highlightQuery(item.title, query);

            var thumb = item.image
                ? '<img src="' + item.image + '" alt="" class="suggestion-thumb">'
                : '<div class="suggestion-placeholder"><svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></div>';

            $list.append(
                '<a href="' + item.url + '" class="suggestion-item" data-index="' + i + '">' +
                thumb +
                '<div class="flex-1 min-w-0"><div class="suggestion-title">' + title + '</div></div>' +
                '</a>'
            );
        });

        $list.append(
            '<a href="' + searchUrl + '?q=' + encodeURIComponent(query) + '" class="suggestion-viewall">' +
            'Xem tất cả kết quả cho "' + query + '" →' +
            '</a>'
        );

        $container.removeClass('is-hidden');
        currentIndex = -1;
    }

    // ── Helpers ────────────────────────────────────────────────────────────
    function highlightQuery(text, query) {
        var escaped = query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
        return text.replace(new RegExp('(' + escaped + ')', 'gi'), '<mark>$1</mark>');
    }

    function showLoading() {
        $container.removeClass('is-hidden');
        $loading.removeClass('is-hidden');
        $list.empty();
        $empty.addClass('is-hidden');
    }

    function hideLoading() { $loading.addClass('is-hidden'); }

    function showEmpty() {
        $empty.removeClass('is-hidden');
        $list.empty();
    }

    function hideSuggestions() {
        $container.addClass('is-hidden');
        currentIndex = -1;
    }

    // ── Keyboard Navigation ───────────────────────────────────────────────
    function handleKeyboard(e) {
        var $items = $list.find('.suggestion-item');

        switch (e.key) {
            case 'ArrowDown':
                e.preventDefault();
                currentIndex = Math.min(currentIndex + 1, $items.length - 1);
                $items.removeClass('is-active');
                if (currentIndex >= 0) $items.eq(currentIndex).addClass('is-active');
                break;
            case 'ArrowUp':
                e.preventDefault();
                currentIndex = Math.max(currentIndex - 1, -1);
                $items.removeClass('is-active');
                if (currentIndex >= 0) $items.eq(currentIndex).addClass('is-active');
                break;
            case 'Enter':
                if (currentIndex >= 0) {
                    e.preventDefault();
                    window.location.href = $items.eq(currentIndex).attr('href');
                }
                break;
            case 'Escape':
                hideSuggestions();
                $input.blur();
                break;
        }
    }

    // ── Event Binding ─────────────────────────────────────────────────────
    var debouncedFetch = function (query) {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(function () { fetchSuggestions(query); }, 300);
    };

    $input.on('input', function () { debouncedFetch($(this).val().trim()); });

    $input.on('focus', function () {
        var q = $(this).val().trim();
        if (q.length >= 2) debouncedFetch(q);
    });

    $input.on('keydown', handleKeyboard);

    $(document).on('click', function (e) {
        if (!$(e.target).closest('#search-container').length) hideSuggestions();
    });
});
