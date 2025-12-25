@php
    $badgeColors = [
        'create' => 'bg-green-100 text-green-800',
        'update' => 'bg-blue-100 text-blue-800',
        'delete' => 'bg-red-100 text-red-800',
        'soft_delete' => 'bg-yellow-100 text-yellow-800',
        'restore' => 'bg-teal-100 text-teal-800',
        'login' => 'bg-purple-100 text-purple-800',
        'logout' => 'bg-gray-100 text-gray-800',
        'setting_change' => 'bg-indigo-100 text-indigo-800',
    ];
    $labels = [
        'create' => 'Tạo mới',
        'update' => 'Cập nhật',
        'delete' => 'Xóa',
        'soft_delete' => 'Xóa (tạm)',
        'restore' => 'Khôi phục',
        'login' => 'Đăng nhập',
        'logout' => 'Đăng xuất',
        'setting_change' => 'Cài đặt',
    ];
    $logName = $log->log_name ?? $logName ?? 'activity';
    $size = $size ?? 'md';
    $sizeClasses = [
        'sm' => 'px-2 py-0.5 text-xs',
        'md' => 'px-3 py-1 text-sm',
        'lg' => 'px-4 py-2 text-lg',
    ];
@endphp

<span
    class="{{ $sizeClasses[$size] ?? $sizeClasses['md'] }} inline-flex leading-5 font-semibold rounded-full {{ $badgeColors[$logName] ?? 'bg-gray-100 text-gray-800' }}">
    {{ $labels[$logName] ?? $logName }}
</span>