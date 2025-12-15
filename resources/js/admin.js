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

// Export functions to global scope for inline scripts if needed
window.showTagError = showTagError;
window.clearTagError = clearTagError;
window.updatePrimaryCategoryRadios = updatePrimaryCategoryRadios;
window.showToast = showToast; // Export showToast

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
