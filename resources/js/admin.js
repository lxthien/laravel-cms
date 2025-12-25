/**
 * Admin JavaScript
 * File này dùng cho backend/admin panel
 */

import './bootstrap';

/**
 * ===== POST FORM HANDLERS =====
 * Xử lý Tags và Categories trong form Create/Edit Post
 */
$(document).ready(function () {
    initPostForm();

    // ===== GLOBAL HANDLERS =====
    initGlobalHandlers();

    // ===== LISTING HANDLERS (Pages, Categories, etc.) =====
    initListingHandlers();

    // ===== DASHBOARD HANDLERS =====
    initDashboardChart();

    // ===== MENU BUILDER HANDLERS =====
    initMenuBuilder();

    // ===== NOTIFICATION SYSTEM =====
    initNotifications();
});

/**
 * Initialize Listing Handlers (Order, Status)
 * Các element cần có class:
 * - .order-input: data-url (API endpoint), data-id (optional for row access)
 * - .status-toggle: data-url (API endpoint), data-id
 */
function initListingHandlers() {
    // 1. Order Input Handler
    // Thống nhất: Xử lý khi 'change' (cho input number behavior)
    $('.order-input').off('change').on('change', function () {
        const input = $(this);
        const url = input.data('url'); // Cần update view để có data-url
        const newVal = input.val();

        // Fallback cho legacy code (nếu chưa update view dùng data-url)
        // Check Page logic
        let ajaxUrl = url;
        let ajaxData = { order: newVal, _token: $('meta[name="csrf-token"]').attr('content') };

        if (!ajaxUrl) {
            // Try Page Logic: /admin/pages/{id}/update-order
            const pageId = input.data('page-id');
            if (pageId) {
                ajaxUrl = `/admin/pages/${pageId}/update-order`;
                // method PATCH
                ajaxData['_method'] = 'PATCH';
            }
        }

        if (!ajaxUrl) {
            // Try Category Logic: /admin/categories/{id}/update-order
            const catId = input.data('category-id');
            if (catId) {
                ajaxUrl = `/admin/categories/${catId}/update-order`;
                // method POST (như code cũ)
            }
        }

        if (!ajaxUrl) return;

        $.ajax({
            url: ajaxUrl,
            method: ajaxData['_method'] || 'POST',
            data: ajaxData,
            success: function (response) {
                if (response.success || response.status) {
                    // Visual feedback
                    input.addClass('border-green-500').removeClass('border-gray-300');
                    setTimeout(() => {
                        input.removeClass('border-green-500').addClass('border-gray-300');
                    }, 1000);

                    if (response.message) {
                        showToast(response.message, 'success');
                    }
                } else {
                    showToast(response.message || 'Lỗi cập nhật', 'error');
                }
            },
            error: function () {
                showToast('Lỗi kết nối server', 'error');
            }
        });
    });

    // 2. Status Toggle Handler
    $('.status-toggle').off('change').on('click change', function (e) {
        $(document).off('change').on('change', 'input[type="checkbox"].status-toggle', function (e) {
            const el = $(this);
            const catId = el.data('category-id');
            const newStatus = el.is(':checked') ? 1 : 0;
            const statusLabel = el.next().next(); // span text label

            el.prop('disabled', true);

            $.ajax({
                url: `/admin/categories/${catId}/update-status`,
                method: 'POST',
                data: {
                    status: newStatus,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    if (response.success) {
                        if (statusLabel.length) {
                            statusLabel.text(newStatus ? 'Hoạt động' : 'Ẩn');
                        }
                        showToast(newStatus ? 'Đã kích hoạt danh mục!' : 'Đã ẩn danh mục!', 'success');
                    } else {
                        el.prop('checked', !newStatus);
                        showToast(response.message || 'Lỗi cập nhật', 'error');
                    }
                },
                error: function () {
                    el.prop('checked', !newStatus);
                    showToast('Lỗi hệ thống', 'error');
                },
                complete: function () {
                    el.prop('disabled', false);
                }
            });
        });
    });
}

/**
 * Initialize Global Handlers (Image Preview, Slug, CKEditor)
 */
function initGlobalHandlers() {
    // 1. Image Preview
    // Áp dụng cho input có id="featured_image" và preview container id="image-preview"
    if ($('#featured_image').length > 0) {
        $('#featured_image').on('change', function (e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    $('#image-preview img').attr('src', e.target.result);
                    $('#image-preview').removeClass('hidden');
                }
                reader.readAsDataURL(file);
            }
        });
    }

    // 2. Auto Slug Generation
    // Áp dụng khi input slug chưa có giá trị và title thay đổi
    // Cần có input name="title" (hoặc id="title") và input name="slug" (hoặc id="slug")
    const titleInput = $('input[name="title"], #title');
    const slugInput = $('input[name="slug"], #slug');

    if (titleInput.length > 0 && slugInput.length > 0) {
        titleInput.on('blur change', function () {
            const title = $(this).val();

            // Chỉ tạo slug nếu slug trống hoặc đang ở trạng thái 'auto-generated' (có thể mở rộng sau)
            // Hiện tại logic là: nếu slug rỗng thì mới tạo
            if (!slugInput.val() && title) {
                const slug = toSlug(title);
                slugInput.val(slug);
            }
        });
    }
}

/**
 * Helper: Convert string to slug
 */
function toSlug(str) {
    return str.toLowerCase()
        .normalize('NFD') // Chuyển sang tổ hợp Unicode (ví dụ: ế -> e + sắc)
        .replace(/[\u0300-\u036f]/g, '') // Xóa các dấu thanh
        .replace(/đ/g, 'd')
        .replace(/[^a-z0-9\s-]/g, '') // Xóa ký tự đặc biệt
        .replace(/\s+/g, '-') // Chuyển khoảng trắng thành -
        .replace(/-+/g, '-') // Xóa các dấu - liên tiếp
        .trim();
}

/**
 * Initialize Post Form (Tags, Categories, Primary Category)
 */
function initPostForm() {
    // Kiểm tra xem đang ở trang post form không
    // ... (rest of the function)
    if ($('#tags').length === 0 && $('#categories').length === 0) {
        return;
    }

    // ===== SELECT2 FOR TAGS =====
    if ($('#tags').length > 0) {
        $('#tags').select2({
            tags: true,
            tokenSeparators: [','],
            placeholder: "Nhập tag và nhấn Enter...",
            allowClear: true,

            // Custom tag creation
            createTag: function (params) {
                var term = $.trim(params.term);

                // Không tạo tag khi đang gõ (chỉ khi nhấn Enter)
                if (params.inputType === 'input') {
                    return null;
                }

                if (term === '') {
                    return null;
                }

                // Validate độ dài (2-50 ký tự)
                if (term.length < 2) {
                    showTagError('Tag phải từ 2 ký tự trở lên!');
                    return null;
                }

                if (term.length > 50) {
                    showTagError('Tag không được quá 50 ký tự!');
                    return null;
                }

                // Validate ký tự đặc biệt
                if (/[<>'"\/\\;`%\[\]{}|]/.test(term)) {
                    showTagError('Tag không được chứa ký tự đặc biệt: < > \' " / \\ ; ` % [ ] { } |');
                    return null;
                }

                return {
                    id: 'new:' + term,
                    text: term + ' (mới)',
                    newTag: true
                };
            },

            // Insert tag khi chọn hoặc nhấn Enter
            insertTag: function (data, tag) {
                var exists = false;
                data.forEach(function (item) {
                    if (item.text.toLowerCase() === tag.text.toLowerCase()) {
                        exists = true;
                    }
                });

                if (!exists) {
                    data.push(tag);
                } else {
                    showTagError('Tag này đã được thêm rồi!');
                }
            }
        });

        // Clear error khi select/unselect tag
        $('#tags').on('select2:select select2:unselect', function () {
            clearTagError();
        });
    }

    // ===== SELECT2 FOR CATEGORIES =====
    if ($('#categories').length > 0) {
        $('#categories').select2({
            placeholder: "Chọn danh mục...",
            allowClear: false
        });

        // Handle Primary Category Selection
        $('#categories').on('change', function () {
            updatePrimaryCategoryRadios();
        });

        // Initial trigger
        $('#categories').trigger('change');
    }
}

/**
 * Update Primary Category Radio Buttons
 * Được gọi khi categories thay đổi
 */
function updatePrimaryCategoryRadios() {
    const selectedCategories = $('#categories').val();
    const container = $('#primary-category-container');
    const radiosContainer = $('#primary-category-radios');

    if (selectedCategories && selectedCategories.length > 0) {
        container.show();
        radiosContainer.empty();

        // Lấy primary category hiện tại từ window (được set bởi blade)
        let primaryToCheck = window.currentPrimaryCategoryId || null;

        // Check if current primary is still in selected categories
        if (primaryToCheck && selectedCategories.includes(String(primaryToCheck))) {
            // Keep current primary
        } else {
            // Default to first selected category
            primaryToCheck = selectedCategories[0];
        }

        // Create radio buttons
        selectedCategories.forEach((catId) => {
            const catText = $(`#categories option[value="${catId}"]`).text();
            const isChecked = (String(catId) === String(primaryToCheck));

            radiosContainer.append(`
                <label class="flex items-center">
                    <input type="radio" name="primary_category" value="${catId}" 
                           class="mr-2" ${isChecked ? 'checked' : ''}>
                    <span>${catText}</span>
                </label>
            `);
        });

        // Update window variable when user changes selection
        $('input[name="primary_category"]').off('change').on('change', function () {
            window.currentPrimaryCategoryId = $(this).val();
        });
    } else {
        container.hide();
    }
}

/**
 * ===== TAG ERROR HANDLING =====
 */
function showTagError(message) {
    clearTagError();

    const errorDiv = $('<div>', {
        id: 'tag-error-message',
        class: 'text-red-500 text-xs italic mt-1',
        text: message
    });

    $('#tags').closest('.mb-4').append(errorDiv);

    // Auto hide after 3 seconds
    setTimeout(clearTagError, 3000);
}

function clearTagError() {
    $('#tag-error-message').remove();
}

/**
 * Toast notification function
 * @param {string} message 
 * @param {string} type 'success' | 'error'
 */
function showToast(message, type = 'success') {
    // Check if toast container exists, if not create it
    let container = $('#toast-container');
    if (container.length === 0) {
        $('body').append('<div id="toast-container" class="fixed bottom-4 right-4 z-50 flex flex-col gap-2"></div>');
        container = $('#toast-container');
    }

    // Create toast element
    const toastClass = type === 'success' ? 'bg-green-500' : 'bg-red-500';
    const toast = $(`
        <div class="px-6 py-3 rounded shadow-lg text-white ${toastClass} transition-opacity duration-300 opacity-0 transform translate-y-2">
            ${message}
        </div>
    `);

    container.append(toast);

    // Animate in
    requestAnimationFrame(() => {
        toast.removeClass('opacity-0 translate-y-2');
    });

    // Auto remove
    setTimeout(() => {
        toast.addClass('opacity-0 translate-y-2');
        setTimeout(() => {
            toast.remove();
            if (container.children().length === 0) {
                container.remove();
            }
        }, 300);
    }, 3000);
}

/**
 * ===== DASHBOARD CHART HANDLER =====
 * Initialize Dashboard Post Growth Chart
 * Chart data should be in data-chart-data attribute of #postGrowthChart canvas
 */
function initDashboardChart() {
    var $canvas = $('#postGrowthChart');

    // Only initialize if canvas exists and Chart.js is loaded
    if ($canvas.length === 0 || typeof Chart === 'undefined') {
        return;
    }

    var chartDataAttr = $canvas.attr('data-chart-data');
    if (!chartDataAttr) {
        return;
    }

    try {
        var chartData = JSON.parse(chartDataAttr);
        var ctx = $canvas[0].getContext('2d');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: 'Số bài viết',
                    data: chartData.data,
                    fill: true,
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderColor: 'rgb(59, 130, 246)',
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: 'rgb(59, 130, 246)',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            precision: 0
                        },
                        grid: {
                            display: true,
                            drawBorder: false,
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    } catch (e) {
        console.error('Dashboard chart initialization error:', e);
    }
}

/**
 * ===== MENU BUILDER HANDLERS =====
 * Initialize Menu Builder (Nestable2 drag/drop)
 * Required: data-menu-id, data-update-url, data-structure-url on #menu-builder-container
 */
function initMenuBuilder() {
    var $container = $('#menu-builder-container');
    var $nestable = $('#nestable-menu');

    if ($nestable.length === 0 || typeof $.fn.nestable === 'undefined') {
        return;
    }

    var menuId = $container.data('menu-id');
    var updateUrl = $container.data('update-url');
    var structureUrl = $container.data('structure-url');
    var csrfToken = $('meta[name="csrf-token"]').attr('content');

    // Initialize Nestable (only if not already initialized)
    if (!$nestable.data('nestable-id')) {
        $nestable.nestable({
            maxDepth: 3,
            callback: function (l, e) {
                // Callback when item moved (optional)
            }
        });
    }

    // Unbind existing events before binding new ones (prevent duplicates)
    $(document).off('click.menuBuilder');
    $(document).off('input.menuBuilder change.menuBuilder');

    // Accordion Toggle Logic
    $(document).on('click.menuBuilder', '.accordion-toggle', function () {
        var target = $(this).data('target');
        $(this).toggleClass('active');
        $(target).slideToggle(200);
    });

    // Add to Menu (from Checkboxes)
    $(document).on('click.menuBuilder', '.add-to-menu', function () {
        var panel = $(this).closest('div');
        var checked = panel.find('.item-checkbox:checked');

        checked.each(function () {
            var data = $(this).data();
            addMenuItemToStructure({
                title: data.title,
                url: null,
                model_type: data.type,
                model_id: data.id,
                target: '_self',
                icon: null,
                css_class: null
            });
            $(this).prop('checked', false);
        });

        $('#empty-state').addClass('hidden');
    });

    // Add Custom Link
    $(document).on('click.menuBuilder', '.add-custom-link', function () {
        var title = $('#custom-title').val();
        var url = $('#custom-url').val();

        if (!title) {
            alert('Vui lòng nhập tên hiển thị');
            return;
        }

        addMenuItemToStructure({
            title: title,
            url: url,
            model_type: null,
            model_id: null,
            target: '_self',
            icon: null,
            css_class: null
        });

        $('#custom-title').val('');
        $('#custom-url').val('http://');
        $('#empty-state').addClass('hidden');
    });

    // Toggle Settings Panel
    $(document).on('click.menuBuilder', '.item-edit-toggle', function () {
        var item = $(this).closest('.dd-item');
        item.find('.item-settings').first().slideToggle(200);
        $(this).toggleClass('rotate-180');
    });

    // Remove Item
    $(document).on('click.menuBuilder', '.item-remove', function () {
        if (confirm('Bạn có chắc muốn xóa mục menu này? All children will also be deleted.')) {
            $(this).closest('.dd-item').remove();
            if ($('#nestable-menu .dd-item').length === 0) {
                $('#empty-state').removeClass('hidden');
            }
        }
    });

    // Sync input values to data attributes
    $(document).on('input.menuBuilder change.menuBuilder', '.item-settings input, .item-settings select', function () {
        var settings = $(this).closest('.item-settings');
        var item = settings.closest('.dd-item');
        var classList = $(this).attr('class') || '';
        var match = classList.match(/edit-(\w+)/);
        if (!match) return;

        var fieldName = match[1];
        var newVal = $(this).val();

        item.attr('data-' + fieldName, newVal);

        // If title changed, update the display text too
        if (fieldName === 'title') {
            item.find('.dd-handle .text-sm').first().text(newVal);
        }
    });

    // Save Menu
    $(document).on('click.menuBuilder', '#save-menu', function () {
        var btn = $(this);
        var originalHtml = btn.html();

        btn.prop('disabled', true).html('<svg class="animate-spin w-4 h-4" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Đang lưu...');

        // 1. First save the main menu metadata (Name, Location)
        $.ajax({
            url: updateUrl,
            method: 'PUT',
            data: {
                name: $('#menu-name').val(),
                location: $('#menu-location').val(),
                _token: csrfToken
            },
            success: function () {
                // 2. Then save structure
                var structure = $nestable.nestable('serialize');

                $.ajax({
                    url: structureUrl,
                    method: 'POST',
                    data: {
                        items: structure,
                        _token: csrfToken
                    },
                    success: function (response) {
                        showToast(response.message, 'success');
                        setTimeout(function () { location.reload(); }, 1000);
                    },
                    error: function (xhr) {
                        showToast('Lỗi khi lưu cấu trúc: ' + (xhr.responseJSON?.message || 'Unknown'), 'error');
                        btn.prop('disabled', false).html(originalHtml);
                    }
                });
            },
            error: function (xhr) {
                showToast('Lỗi khi lưu thông tin menu: ' + (xhr.responseJSON?.message || 'Unknown'), 'error');
                btn.prop('disabled', false).html(originalHtml);
            }
        });
    });
}

/**
 * Add a new menu item to the structure
 */
function addMenuItemToStructure(data) {
    var id = 'new-' + Date.now() + Math.floor(Math.random() * 100);
    var modelTypeLabel = data.model_type ? data.model_type.split('\\').pop() : 'Custom';
    var hasUrl = !data.model_type;

    var urlFieldHtml = hasUrl ?
        '<div class="space-y-1">' +
        '<label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">URL</label>' +
        '<input type="text" class="w-full text-sm border-gray-200 rounded edit-url" value="' + (data.url || '') + '">' +
        '</div>' : '';

    var itemHtml =
        '<li class="dd-item" ' +
        'data-id="' + id + '" ' +
        'data-title="' + data.title + '" ' +
        'data-url="' + (data.url || '') + '" ' +
        'data-target="' + data.target + '" ' +
        'data-icon="' + (data.icon || '') + '" ' +
        'data-css_class="' + (data.css_class || '') + '" ' +
        'data-model_type="' + (data.model_type || '') + '" ' +
        'data-model_id="' + (data.model_id || '') + '">' +
        '<div class="flex items-center bg-white border border-gray-200 rounded-md shadow-sm mb-2 overflow-hidden group hover:border-blue-300 transition-colors">' +
        '<div class="dd-handle h-12 flex-1 flex items-center px-4 cursor-move bg-white">' +
        '<span class="text-gray-400 mr-3 opacity-0 group-hover:opacity-100 transition-opacity">' +
        '<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M10 9h4V6h3l-5-5-5 5h3v3zm-1 1H6V7l-5 5 5 5v-3h3v-4zm14 2l-5-5v3h-3v4h3v3l5-5zm-9 3h-4v3H7l5 5 5-5h-3v-3z"/></svg>' +
        '</span>' +
        '<span class="text-sm font-semibold text-gray-700 truncate">' + data.title + '</span>' +
        '</div>' +
        '<div class="flex items-center px-4 py-2 bg-gray-50 border-l border-gray-100 h-12">' +
        '<span class="text-[10px] uppercase font-bold text-gray-400 mr-4">' + modelTypeLabel + '</span>' +
        '<button type="button" class="text-gray-400 hover:text-blue-600 item-edit-toggle transition-colors p-1">' +
        '<svg class="w-4 h-4 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>' +
        '</button>' +
        '</div>' +
        '</div>' +
        '<div class="item-settings hidden bg-gray-50 border border-gray-100 rounded-md mb-2 p-5 space-y-4 shadow-inner">' +
        '<div class="grid grid-cols-1 md:grid-cols-2 gap-4">' +
        '<div class="space-y-1">' +
        '<label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Tên hiển thị</label>' +
        '<input type="text" class="w-full text-sm border-gray-200 rounded edit-title" value="' + data.title + '">' +
        '</div>' +
        '<div class="space-y-1">' +
        '<label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Icon (Font Awesome)</label>' +
        '<input type="text" class="w-full text-sm border-gray-200 rounded edit-icon" value="' + (data.icon || '') + '" placeholder="fas fa-home">' +
        '</div>' +
        '</div>' +
        urlFieldHtml +
        '<div class="grid grid-cols-1 md:grid-cols-2 gap-4">' +
        '<div class="space-y-1">' +
        '<label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">CSS Classes</label>' +
        '<input type="text" class="w-full text-sm border-gray-200 rounded edit-css_class" value="' + (data.css_class || '') + '" placeholder="custom-class mb-2">' +
        '</div>' +
        '<div class="space-y-1">' +
        '<label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Mở trong tab mới?</label>' +
        '<select class="w-full text-sm border-gray-200 rounded edit-target">' +
        '<option value="_self"' + (data.target === '_self' ? ' selected' : '') + '>Không (Hiện tại)</option>' +
        '<option value="_blank"' + (data.target === '_blank' ? ' selected' : '') + '>Có (Tab mới)</option>' +
        '</select>' +
        '</div>' +
        '</div>' +
        '<div class="flex justify-between items-center pt-2 border-t border-gray-200">' +
        '<button type="button" class="text-red-500 text-xs font-bold uppercase tracking-widest hover:text-red-700 transition-colors item-remove">Xóa mục này</button>' +
        '<span class="text-[10px] text-gray-400">Mới</span>' +
        '</div>' +
        '</div>' +
        '</li>';

    $('#nestable-menu > .dd-list').append(itemHtml);
}

// ===== NOTIFICATION SYSTEM =====
function initNotifications() {
    var $bellBtn = $('#notification-bell');
    var $dropdown = $('#notification-dropdown');
    var $badge = $('#notification-badge');
    var $list = $('#notification-list');
    var $markAllBtn = $('#mark-all-read');

    if ($bellBtn.length === 0) return;

    var isOpen = false;
    var csrfToken = $('meta[name="csrf-token"]').attr('content');

    // Toggle Dropdown
    $bellBtn.click(function (e) {
        e.stopPropagation();
        isOpen = !isOpen;
        if (isOpen) {
            $dropdown.removeClass('hidden');
            loadNotifications();
        } else {
            $dropdown.addClass('hidden');
        }
    });

    // Close when clicking outside
    $(document).click(function (e) {
        if (!$(e.target).closest('#notification-bell, #notification-dropdown').length) {
            isOpen = false;
            $dropdown.addClass('hidden');
        }
    });

    // Poll for unread count every 30 seconds
    setInterval(checkUnreadCount, 30000);
    checkUnreadCount(); // Initial check

    // Check count function
    function checkUnreadCount() {
        $.get('/admin/notifications/count', function (data) {
            updateBadge(data.count);
        });
    }

    function updateBadge(count) {
        if (count > 0) {
            $badge.text(count > 99 ? '99+' : count).removeClass('hidden');
        } else {
            $badge.addClass('hidden');
        }
    }

    // Load notifications for dropdown
    function loadNotifications() {
        $.get('/admin/notifications/unread', function (data) {
            $list.empty();
            updateBadge(data.count);

            if (data.notifications.length === 0) {
                $list.html('<div class="px-4 py-6 text-center text-gray-400 text-sm">Không có thông báo mới</div>');
                return;
            }

            var template = document.getElementById('notification-item-template').content;

            data.notifications.forEach(function (notif) {
                var clone = document.importNode(template, true);
                var $item = $(clone).find('.notification-item');

                $item.find('.notification-title').text(notif.title);
                $item.find('.notification-message').text(notif.message);
                $item.find('.notification-time').text(notif.time_ago);

                // Icon handling
                var iconClass = 'fas fa-info';
                var bgClass = 'bg-blue-500';

                if (notif.type === 'contact') {
                    iconClass = 'fas fa-envelope';
                    bgClass = 'bg-orange-500';
                } else if (notif.type === 'comment') {
                    iconClass = 'fas fa-comment';
                    bgClass = 'bg-blue-500';
                } else if (notif.type === 'user') {
                    iconClass = 'fas fa-user-plus';
                    bgClass = 'bg-green-500';
                }

                $item.find('.notification-icon').addClass(iconClass);
                $item.find('.notification-icon-bg').addClass(bgClass);

                // Click to view (mark read and navigate)
                $item.on('click', function (e) {
                    if (!$(e.target).closest('.mark-read-btn').length) {
                        markAsRead(notif.id, function () {
                            window.location.href = notif.link;
                        });
                    }
                });

                // Mark as read button
                $item.find('.mark-read-btn').click(function (e) {
                    e.stopPropagation();
                    markAsRead(notif.id, function () {
                        $item.fadeOut(200, function () {
                            $(this).remove();
                            // Refresh count
                            checkUnreadCount();
                            // If empty, show empty state
                            if ($list.children().length === 0) {
                                $list.html('<div class="px-4 py-6 text-center text-gray-400 text-sm">Không còn thông báo mới</div>');
                            }
                        });
                    });
                });

                $list.append($item);
            });
        });
    }

    function markAsRead(id, callback) {
        $.post('/admin/notifications/' + id + '/read', { _token: csrfToken }, function () {
            if (callback) callback();
        });
    }

    // Mark all as read
    $markAllBtn.click(function () {
        $.post('/admin/notifications/mark-all-read', { _token: csrfToken }, function () {
            $list.html('<div class="px-4 py-6 text-center text-gray-400 text-sm">Không có thông báo mới</div>');
            updateBadge(0);
        });
    });
}

// ===== SEO SCORE CHECKER =====
function initSeoScoreChecker() {
    if ($('#seo-score-container').length === 0) return;

    var checker = new SeoScoreChecker();
    checker.init();
}

class SeoScoreChecker {
    constructor() {
        this.titleInput = $('#title');
        this.metaTitleInput = $('#meta_title');
        this.descInput = $('#excerpt');
        this.metaDescInput = $('#meta_description');
        this.keywordInput = $('#meta_keywords');
        this.slugInput = $('#slug');
        this.contentEditorId = 'editor';

        // Element cache
        this.scoreBody = $('#seo-score-body');
        this.toggleBtn = '#seo-score-header';

        // Initialize UI structure
        this.buildUI();

        // Tab elements
        this.tabs = $('.seo-tab-btn');
        this.tabContents = $('.seo-tab-content');

        // Preview elements
        this.previewTitle = $('#g-preview-title');
        this.previewDesc = $('#g-preview-desc');
        this.previewUrl = $('#g-preview-url');
        this.previewContainer = $('.google-preview-container');
    }

    buildUI() {
        var html = `
            <div class="seo-score-info">
                <div class="seo-score-circle" id="seoScoreCircle">0</div>
                <div class="seo-score-text">
                    <h4 id="seoScoreLabel">Checking...</h4>
                    <p id="seoScoreMsg">Improve your content to boost ranking</p>
                </div>
            </div>

            <div class="seo-tabs">
                <button type="button" class="seo-tab-btn active" data-tab="check">SEO Check</button>
                <button type="button" class="seo-tab-btn" data-tab="preview">Google Preview</button>
            </div>

            <div id="tab-check" class="seo-tab-content active">
                <div id="seo-content" class="space-y-3"></div>
                <div id="seo-tips" class="seo-tips" style="display:none;"></div>
            </div>

            <div id="tab-preview" class="seo-tab-content">
                <div class="mb-3 flex justify-end">
                     <div class="inline-flex rounded-md shadow-sm" role="group">
                        <button type="button" class="px-3 py-1 text-xs font-medium text-gray-900 bg-white border border-gray-200 rounded-l-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 preview-mode-btn active" data-mode="mobile">
                          <i class="fas fa-mobile-alt mr-1"></i> Mobile
                        </button>
                        <button type="button" class="px-3 py-1 text-xs font-medium text-gray-900 bg-white border border-gray-200 rounded-r-md hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 preview-mode-btn" data-mode="desktop">
                          <i class="fas fa-desktop mr-1"></i> Desktop
                        </button>
                      </div>
                </div>
                <div class="google-preview-container google-preview-mobile mx-auto">
                    <div class="g-link-wrapper">
                        <cite class="g-cite">
                            <span class="rounded-full bg-gray-200 w-4 h-4 mr-1"></span>
                            <span class="g-site-name">{{ config('app.name', 'Site Name') }}</span>
                        </cite>
                        <cite class="g-cite text-gray-500" id="g-preview-url">example.com > post</cite>
                    </div>
                    <h3 class="g-title" id="g-preview-title">Title will appear here</h3>
                    <div class="g-desc" id="g-preview-desc">Description will appear here...</div>
                </div>
            </div>
        `;
        $('#seo-score-body').html(html);
    }

    init() {
        var self = this;

        // Toggle visibility
        $(document).on('click', this.toggleBtn, function () {
            self.scoreBody.slideToggle(200);
            $(this).find('svg').toggleClass('rotate-180');
        });

        // Tab Switching
        $(document).on('click', '.seo-tab-btn', function () {
            var tabId = $(this).data('tab');
            self.tabs.removeClass('active');
            $(this).addClass('active');
            self.tabContents.removeClass('active');
            $('#tab-' + tabId).addClass('active');
        });

        // Preview Mode Switching
        $(document).on('click', '.preview-mode-btn', function () {
            var mode = $(this).data('mode');
            $('.preview-mode-btn').removeClass('active bg-gray-100').addClass('bg-white');
            $(this).addClass('active bg-gray-100');

            if (mode === 'mobile') {
                self.previewContainer.addClass('google-preview-mobile');
            } else {
                self.previewContainer.removeClass('google-preview-mobile');
            }
        });

        // Bind events
        var inputs = [this.titleInput, this.metaTitleInput, this.descInput, this.metaDescInput, this.keywordInput, this.slugInput];
        inputs.forEach(function (input) {
            if (input.length) {
                input.on('input propertychange', function () { self.analyze(); });
            }
        });

        // CKEditor Hook
        if (typeof CKEDITOR !== 'undefined') {
            // Wait for instance to be ready
            CKEDITOR.on('instanceReady', function (evt) {
                if (evt.editor.name === self.contentEditorId) {
                    evt.editor.on('change', function () { self.analyze(); });
                    evt.editor.on('contentDom', function () {
                        evt.editor.editable().attachListener(evt.editor.document, 'keyup', function () {
                            self.analyze();
                        });
                    });
                }
            });

            // Also check if instance already exists
            if (CKEDITOR.instances[self.contentEditorId]) {
                CKEDITOR.instances[self.contentEditorId].on('change', function () { self.analyze(); });
            }
        }

        // Initial analysis
        this.analyze();
    }

    analyze() {
        var results = [];
        var score = 0;
        var maxScore = 100;

        // Data Retrieval
        var title = this.metaTitleInput.val() || this.titleInput.val() || '';
        var desc = this.metaDescInput.val() || this.descInput.val() || '';
        var slug = this.slugInput.val() || '';
        var keywordVal = this.keywordInput.val() || '';
        var keywords = keywordVal.split(',').map(k => k.trim()).filter(k => k);
        var content = '';
        if (typeof CKEDITOR !== 'undefined' && CKEDITOR.instances[this.contentEditorId]) {
            content = CKEDITOR.instances[this.contentEditorId].getData();
        } else if ($('#' + this.contentEditorId).length) {
            content = $('#' + this.contentEditorId).val();
        }
        var plainText = content.replace(/<[^>]*>/g, ' ').toLowerCase();

        // --- 1. Title Analysis (20 pts) ---
        var titleLen = title.length;
        if (titleLen >= 40 && titleLen <= 60) {
            score += 20;
            results.push({ label: 'Title Length', score: 'good', msg: 'Perfect (' + titleLen + '/60)', current: titleLen, max: 60 });
        } else if (titleLen === 0) {
            // score += 0
            results.push({ label: 'Title Length', score: 'bad', msg: 'Missing title', current: 0, max: 60 });
        } else {
            score += 10;
            results.push({ label: 'Title Length', score: 'ok', msg: titleLen < 40 ? 'Too short' : 'Too long', current: titleLen, max: 60 });
        }

        // --- 2. Description Analysis (20 pts) ---
        var descLen = desc.length;
        if (descLen >= 150 && descLen <= 160) {
            score += 20;
            results.push({ label: 'Description', score: 'good', msg: 'Perfect (' + descLen + '/160)', current: descLen, max: 160 });
        } else if (descLen === 0) {
            results.push({ label: 'Description', score: 'bad', msg: 'Missing description', current: 0, max: 160 });
        } else {
            score += 10;
            results.push({ label: 'Description', score: 'ok', msg: descLen < 150 ? 'Too short' : 'Too long', current: descLen, max: 160 });
        }

        // --- 3. Slug Analysis (15 pts) ---
        if (slug.length > 0) {
            if (/^[a-z0-9-]+$/.test(slug)) {
                score += 15;
                results.push({ label: 'URL Slug', score: 'good', msg: 'Optimized', isBoolean: true });
            } else {
                score += 7;
                results.push({ label: 'URL Slug', score: 'ok', msg: 'Use lowercase & hyphens', isBoolean: true });
            }
        } else {
            results.push({ label: 'URL Slug', score: 'bad', msg: 'Missing', isBoolean: true });
        }

        // --- 4. Content Length (15 pts) ---
        var wordCount = plainText.split(/\s+/).filter(w => w.length > 0).length;
        // Reference file uses 300 words min
        if (wordCount >= 300) {
            score += 15;
            results.push({ label: 'Content Length', score: 'good', msg: wordCount + ' words', current: Math.min(wordCount, 600), max: 600 });
        } else if (wordCount === 0) {
            results.push({ label: 'Content Length', score: 'bad', msg: 'Empty content', current: 0, max: 600 });
        } else {
            var partial = Math.floor((wordCount / 300) * 15);
            score += partial;
            results.push({ label: 'Content Length', score: 'ok', msg: 'Recommended min. 300 words', current: wordCount, max: 600 });
        }

        // --- 5. Focus Keyword (15 pts) ---
        var keywordPoints = 0;
        if (keywords.length > 0) {
            var primaryKeyword = keywords[0].toLowerCase();
            var places = [];

            // Check Title
            if (title.toLowerCase().includes(primaryKeyword)) { keywordPoints += 5; places.push('Title'); }
            // Check Desc
            if (desc.toLowerCase().includes(primaryKeyword)) { keywordPoints += 5; places.push('Desc'); }
            // Check Content
            if (plainText.includes(primaryKeyword)) { keywordPoints += 5; places.push('Content'); }

            score += keywordPoints;

            if (keywordPoints === 15) {
                results.push({ label: 'Focus Keyword', score: 'good', msg: 'Found in Title, Desc & Content', isBoolean: true });
            } else if (keywordPoints > 0) {
                results.push({ label: 'Focus Keyword', score: 'ok', msg: 'Found in ' + places.join(', '), isBoolean: true });
            } else {
                results.push({ label: 'Focus Keyword', score: 'bad', msg: 'Not found in main areas', isBoolean: true });
            }

            // Density (Informational)
            var count = (plainText.match(new RegExp(primaryKeyword, 'g')) || []).length;
            var density = wordCount > 0 ? (count / wordCount) * 100 : 0;
            var densityMsg = density >= 0.5 && density <= 2.5 ? 'Good (' + density.toFixed(1) + '%)' : 'Check density';
            results.push({ label: 'Keyword Density', score: density >= 0.5 && density <= 2.5 ? 'good' : 'ok', msg: densityMsg + ' (' + count + ' times)', current: Math.min(density * 20, 100), max: 100 });

        } else {
            results.push({ label: 'Focus Keyword', score: 'bad', msg: 'Not specified', isBoolean: true });
        }

        // --- 6. Advanced/Structure (15 pts) ---
        var structPoints = 0;
        var tips = [];

        if (content) {
            // H-tags (5 pts)
            if (content.match(/<h[23]/)) { structPoints += 5; }
            else { tips.push('Add H2 or H3 subheadings.'); }

            // Links (5 pts)
            if (content.includes('<a href')) { structPoints += 5; }
            else { tips.push('Add internal or external links.'); }

            // Images (5 pts)
            if (content.match(/<img/)) {
                var altMissing = (content.match(/<img[^>]+alt=["']\s*["']/g) || []).length;
                if (altMissing === 0) { structPoints += 5; }
                else { tips.push('Add Alt text to ' + altMissing + ' images.'); }
            } else {
                // If no images, we don't punish but don't give bonus? Or maybe giving points implies images are good.
                // Updated logic: if no images, no points (encourage images).
                tips.push('Add images to article.');
            }
        }
        score += structPoints;
        results.push({ label: 'Structure & Media', score: structPoints >= 10 ? 'good' : 'ok', msg: structPoints + '/15 points', current: structPoints, max: 15 });

        this.render(results, score, tips);
        this.updatePreview(title, desc, slug);
    }

    render(results, score, tips) {
        var html = '';

        // Render Checks
        results.forEach(function (r) {
            var colorClass = 'bg-seo-' + r.score;
            var textClass = 'seo-score-' + r.score;
            var badgeClass = 'status-' + r.score;
            var width = r.max ? Math.min((r.current / r.max) * 100, 100) : 100;

            html += '<div class="analysis-card">';
            html += '<div class="analysis-header">';
            html += '<span>' + r.label + '</span>';
            html += '<span class="status-badge ' + badgeClass + '">' + r.msg + '</span>';
            html += '</div>';

            if (!r.isBoolean) {
                html += '<div class="seo-progress-bar">';
                html += '<div class="seo-progress-fill ' + colorClass + '" style="width: ' + width + '%"></div>';
                html += '</div>';
            }
            html += '</div>';
        });

        $('#seo-content').html(html);

        // Update Score Circle
        var circle = $('#seoScoreCircle');
        var label = $('#seoScoreLabel');
        var msg = $('#seoScoreMsg');

        circle.text(score).removeClass('excellent good moderate poor');

        var scoreClass = 'poor';
        var scoreLabel = 'Poor';
        var scoreMessage = 'Needs serious optimization.';

        if (score >= 80) { scoreClass = 'excellent'; scoreLabel = 'Excellent'; scoreMessage = 'Great job! Ready to rank.'; }
        else if (score >= 60) { scoreClass = 'good'; scoreLabel = 'Good'; scoreMessage = 'Well optimized, minor tweaks possible.'; }
        else if (score >= 40) { scoreClass = 'moderate'; scoreLabel = 'Moderate'; scoreMessage = 'Needs improvement.'; }

        circle.addClass(scoreClass);
        label.text(scoreLabel + ' (' + score + '/100)');
        msg.text(scoreMessage);

        // Update header
        var headerColor = score >= 80 ? 'text-green-500' : (score >= 60 ? 'text-blue-500' : (score >= 40 ? 'text-yellow-500' : 'text-red-500'));
        $('#seo-header-score').removeClass('text-red-500 text-green-500 text-yellow-500 text-blue-500').addClass(headerColor).text(scoreLabel);

        // Render Tips
        if (tips && (tips.length > 0 || score < 100)) {
            var tipsHtml = '<h5><i class="fas fa-lightbulb text-yellow-500"></i> Optimization Tips</h5><ul>';
            if (tips) tips.forEach(function (t) { tipsHtml += '<li>' + t + '</li>'; });
            if (score < 60) tipsHtml += '<li>Check title and description length.</li>';
            if (score < 40) tipsHtml += '<li>Ensure focus keyword is used.</li>';
            tipsHtml += '</ul>';
            $('#seo-tips').html(tipsHtml).show();
        } else {
            $('#seo-tips').hide();
        }
    }

    updatePreview(title, desc, slug) {
        var host = window.location.host;
        this.previewTitle.text(title || 'Post Title');
        this.previewDesc.text(desc || 'Post description will appear here in search results...');
        this.previewUrl.text(host + ' > ' + (slug || 'post-url'));
    }
}

// Initialize functions on document ready
$(function () {
    // ===== MENU BUILDER & OTHER EXISTING FUNCTIONS (omitted for brevity, assuming they are above) ...
    // This replace fixes the bottom part of the file.
    initNotifications();
    initSeoScoreChecker();
});

// Export functions to global window object
window.showTagError = showTagError;
window.clearTagError = clearTagError;
window.updatePrimaryCategoryRadios = updatePrimaryCategoryRadios;
window.showToast = showToast;
window.initDashboardChart = initDashboardChart;
window.initMenuBuilder = initMenuBuilder;
window.addMenuItemToStructure = addMenuItemToStructure;
window.initNotifications = initNotifications;
window.initSeoScoreChecker = initSeoScoreChecker;
