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
});

/**
 * Initialize Post Form (Tags, Categories, Primary Category)
 */
function initPostForm() {
    // Kiểm tra xem đang ở trang post form không
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
