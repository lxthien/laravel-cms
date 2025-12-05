@extends('admin.layouts.app')

@section('title', 'Quản Lý Danh Mục')
@section('page-title', 'Danh Mục')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold">Danh Sách Danh Mục</h2>
        
        @can('category-create')
        <a href="{{ route('admin.categories.create') }}" 
           class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Tạo Danh Mục Mới
        </a>
        @endcan
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full table-auto">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tên</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Slug</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Danh Mục Cha</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Thứ Tự</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Trạng Thái</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hành Động</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @include('admin.categories._category_tree', ['categories' => $categories, 'level' => 0])
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ==== Order Input Handler ====
    document.querySelectorAll('.order-input').forEach(input => {
        input.addEventListener('blur', function() {
            updateOrder(this);
        });
        
        // Cũng có thể update khi nhấn Enter
        input.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                this.blur(); // Trigger blur event
            }
        });
    });

    // ==== Status Toggle Handler ====
    document.querySelectorAll('.status-toggle').forEach(toggle => {
        toggle.addEventListener('change', function() {
            updateStatus(this);
        });
    });
});

// Update Order Function
function updateOrder(inputElement) {
    const categoryId = inputElement.dataset.categoryId;
    const newOrder = inputElement.value;
    const oldOrder = inputElement.dataset.oldValue;
    
    // Nếu giá trị không thay đổi, không cần update
    if (newOrder === oldOrder) {
        return;
    }
    
    // Hiển thị loading state
    inputElement.disabled = true;
    inputElement.classList.add('opacity-50');
    
    // Gửi AJAX request
    fetch(`/admin/categories/${categoryId}/update-order`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            order: newOrder
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Cập nhật old value
            inputElement.dataset.oldValue = newOrder;
            
            // Hiển thị success feedback
            inputElement.classList.remove('border-gray-300');
            inputElement.classList.add('border-green-500');
            
            setTimeout(() => {
                inputElement.classList.remove('border-green-500');
                inputElement.classList.add('border-gray-300');
            }, 1000);
            
            // Show toast notification (optional)
            showToast('Đã cập nhật thứ tự thành công!', 'success');
        } else {
            // Rollback về giá trị cũ
            inputElement.value = oldOrder;
            showToast('Lỗi: ' + (data.message || 'Không thể cập nhật'), 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        inputElement.value = oldOrder;
        showToast('Lỗi kết nối. Vui lòng thử lại!', 'error');
    })
    .finally(() => {
        inputElement.disabled = false;
        inputElement.classList.remove('opacity-50');
    });
}

// Update Status Function
function updateStatus(toggleElement) {
    const categoryId = toggleElement.dataset.categoryId;
    const newStatus = toggleElement.checked ? 1 : 0;
    const statusLabel = toggleElement.nextElementSibling.nextElementSibling; // Get status text
    
    // Disable toggle during request
    toggleElement.disabled = true;
    
    fetch(`/admin/categories/${categoryId}/update-status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            status: newStatus
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update status label text
            statusLabel.textContent = newStatus ? 'Hoạt động' : 'Ẩn';
            
            showToast(
                newStatus ? 'Đã kích hoạt danh mục!' : 'Đã ẩn danh mục!', 
                'success'
            );
        } else {
            // Rollback toggle state
            toggleElement.checked = !toggleElement.checked;
            showToast('Lỗi: ' + (data.message || 'Không thể cập nhật'), 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        // Rollback toggle state
        toggleElement.checked = !toggleElement.checked;
        showToast('Lỗi kết nối. Vui lòng thử lại!', 'error');
    })
    .finally(() => {
        toggleElement.disabled = false;
    });
}

// Toast notification function (optional)
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `fixed bottom-4 right-4 px-6 py-3 rounded shadow-lg text-white ${type === 'success' ? 'bg-green-500' : 'bg-red-500'} z-50`;
    toast.textContent = message;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.remove();
    }, 3000);
}
</script>
@endpush

@push('styles')
<style>
/* Hide default checkbox */
.status-toggle {
    position: absolute;
    opacity: 0;
    width: 0;
    height: 0;
}

/* Custom toggle switch styling */
.status-toggle:disabled + div {
    opacity: 0.5;
    cursor: not-allowed;
}

/* Smooth transition for toggle */
.status-toggle + div {
    transition: background-color 0.3s ease;
}
</style>
@endpush