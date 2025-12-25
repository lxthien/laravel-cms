@extends('admin.layouts.app')

@section('title', 'Thống kê hoạt động')
@section('page-title', 'Thống Kê Hoạt Động')

@section('content')
    <div class="space-y-6">
        {{-- Back Button --}}
        <a href="{{ route('admin.activity-logs.index') }}"
            class="inline-flex items-center text-blue-600 hover:text-blue-900">
            <i class="fas fa-arrow-left mr-2"></i>Quay lại
        </a>

        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            {{-- Total Logs --}}
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-500">
                        <i class="fas fa-list text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Tổng hoạt động</p>
                        <p class="text-3xl font-bold text-gray-900">{{ number_format($totalLogs) }}</p>
                    </div>
                </div>
            </div>

            {{-- Today Logs --}}
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-500">
                        <i class="fas fa-calendar-day text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Hôm nay</p>
                        <p class="text-3xl font-bold text-gray-900">{{ number_format($todayLogs) }}</p>
                    </div>
                </div>
            </div>

            {{-- This Week Logs --}}
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-yellow-500">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 text-yellow-500">
                        <i class="fas fa-calendar-week text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Tuần này</p>
                        <p class="text-3xl font-bold text-gray-900">{{ number_format($thisWeekLogs) }}</p>
                    </div>
                </div>
            </div>

            {{-- This Month Logs --}}
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 text-purple-500">
                        <i class="fas fa-calendar-alt text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Tháng này</p>
                        <p class="text-3xl font-bold text-gray-900">{{ number_format($thisMonthLogs) }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Daily Activity Trend Chart --}}
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-6">Xu hướng hoạt động (30 ngày qua)</h3>
            <div class="h-64">
                <canvas id="activityTrendChart"></canvas>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Activities by Type --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-6">Hoạt động theo loại</h3>

                <div class="space-y-4">
                    @php
                        $labels = [
                            'create' => 'Tạo mới',
                            'update' => 'Cập nhật',
                            'delete' => 'Xóa',
                            'soft_delete' => 'Xóa (tạm)',
                            'restore' => 'Khôi phục',
                            'login' => 'Đăng nhập',
                            'logout' => 'Đăng xuất',
                            'setting_change' => 'Thay đổi cài đặt',
                        ];
                        $colors = [
                            'create' => 'bg-green-500',
                            'update' => 'bg-blue-500',
                            'delete' => 'bg-red-500',
                            'soft_delete' => 'bg-yellow-500',
                            'restore' => 'bg-teal-500',
                            'login' => 'bg-purple-500',
                            'logout' => 'bg-gray-500',
                            'setting_change' => 'bg-indigo-500',
                        ];
                        $total = $logsByType->sum('count') ?: 1;
                    @endphp

                    @foreach($logsByType as $log)
                        @php
                            $percentage = ($log->count / $total) * 100;
                        @endphp
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-medium text-gray-900">
                                    {{ $labels[$log->log_name] ?? $log->log_name }}
                                </span>
                                <span class="text-sm font-bold text-gray-900">{{ $log->count }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="{{ $colors[$log->log_name] ?? 'bg-gray-500' }} h-2 rounded-full"
                                    style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Top Users --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-6">Người dùng hoạt động nhất</h3>

                <div class="space-y-4">
                    @foreach($logsByUser as $log)
                        <a href="{{ route('admin.activity-logs.user-timeline', $log->user) }}"
                            class="flex items-center p-3 rounded-lg hover:bg-gray-50 transition">
                            <div
                                class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-bold">
                                {{ strtoupper(substr($log->user->name, 0, 1)) }}
                            </div>
                            <div class="ml-3 flex-1">
                                <p class="text-sm font-medium text-gray-900">{{ $log->user->name }}</p>
                                <p class="text-xs text-gray-600">{{ $log->user->email }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold text-gray-900">{{ $log->count }}</p>
                                <p class="text-xs text-gray-600">hoạt động</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Activity Trend Chart
            const trendData = @json($trendData);
            const ctx = document.getElementById('activityTrendChart').getContext('2d');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: trendData.map(item => item.date),
                    datasets: [{
                        label: 'Hoạt động',
                        data: trendData.map(item => item.count),
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.3,
                        pointRadius: 3,
                        pointHoverRadius: 6,
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
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleFont: { size: 13 },
                            bodyFont: { size: 12 },
                            padding: 10,
                            cornerRadius: 6,
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    }
                }
            });
        </script>
    @endpush
@endsection