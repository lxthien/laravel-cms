@extends('admin.layouts.app')

@section('title', 'Lịch sử ' . $modelName)
@section('page-title', 'Lịch Sử Đối Tượng')

@section('content')
<div class="space-y-6">
    {{-- Back Button --}}
    <a href="{{ route('admin.activity-logs.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-900">
        <i class="fas fa-arrow-left mr-2"></i>Quay lại
    </a>

    {{-- Model Header --}}
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="h-12 w-12 rounded-full bg-gradient-to-br from-indigo-400 to-indigo-600 flex items-center justify-center text-white text-lg font-bold">
                    <i class="fas fa-cube"></i>
                </div>
                <div class="ml-4">
                    <h2 class="text-2xl font-bold text-gray-900">{{ $modelName }}</h2>
                    <p class="text-gray-600">ID: {{ $modelId }}</p>
                </div>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500">Tổng số hoạt động</p>
                <p class="text-3xl font-bold text-gray-900">{{ $logs->total() }}</p>
            </div>
        </div>
    </div>

    {{-- Timeline --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h3 class="text-lg font-semibold text-gray-900">Lịch sử hoạt động</h3>
        </div>

        @if($logs->count() > 0)
            <div class="relative">
                {{-- Timeline line --}}
                <div class="absolute left-8 top-0 bottom-0 w-0.5 bg-gray-200"></div>

                <ul class="divide-y divide-gray-200">
                    @foreach($logs as $log)
                        <li class="relative pl-16 pr-6 py-4 hover:bg-gray-50 transition">
                            {{-- Timeline dot --}}
                            <div class="absolute left-6 top-6 w-4 h-4 rounded-full border-2 border-white shadow 
                                @switch($log->log_name)
                                    @case('create') bg-green-500 @break
                                    @case('update') bg-blue-500 @break
                                    @case('delete') bg-red-500 @break
                                    @case('soft_delete') bg-yellow-500 @break
                                    @case('restore') bg-teal-500 @break
                                    @default bg-gray-400
                                @endswitch
                            "></div>

                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        @include('admin.activity-logs.partials._log-badge', ['log' => $log, 'size' => 'sm'])
                                        <span class="text-sm text-gray-500">
                                            {{ $log->created_at->format('d/m/Y H:i:s') }}
                                            <span class="text-gray-400">({{ $log->created_at->diffForHumans() }})</span>
                                        </span>
                                    </div>
                                    <p class="text-gray-900">{{ $log->description }}</p>
                                    @if($log->user)
                                        <p class="text-sm text-gray-600 mt-1">
                                            <i class="fas fa-user mr-1"></i>{{ $log->user->name }}
                                        </p>
                                    @endif
                                </div>
                                <a href="{{ route('admin.activity-logs.show', $log) }}" 
                                   class="ml-4 px-3 py-1 text-sm text-blue-600 hover:text-blue-900 hover:bg-blue-50 rounded">
                                    <i class="fas fa-eye mr-1"></i>Chi tiết
                                </a>
                            </div>

                            {{-- Show changes if available --}}
                            @if(!empty($log->properties['changes']))
                                <div class="mt-3 text-sm">
                                    <button type="button" 
                                            class="text-gray-500 hover:text-gray-700"
                                            onclick="this.nextElementSibling.classList.toggle('hidden')">
                                        <i class="fas fa-chevron-down mr-1"></i>Xem thay đổi
                                    </button>
                                    <div class="hidden mt-2 bg-gray-50 rounded-lg p-3 space-y-2">
                                        @foreach($log->properties['changes'] as $field => $change)
                                            <div class="flex items-start gap-2">
                                                <span class="font-medium text-gray-700 min-w-[100px]">{{ $field }}:</span>
                                                <span class="text-red-600 line-through">{{ is_array($change['old'] ?? null) ? json_encode($change['old']) : ($change['old'] ?? '-') }}</span>
                                                <i class="fas fa-arrow-right text-gray-400 mx-1"></i>
                                                <span class="text-green-600">{{ is_array($change['new'] ?? null) ? json_encode($change['new']) : ($change['new'] ?? '-') }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        @else
            <div class="px-6 py-12 text-center text-gray-500">
                <i class="fas fa-inbox text-4xl text-gray-300 mb-4"></i>
                <p>Không có hoạt động nào cho đối tượng này</p>
            </div>
        @endif
    </div>

    {{-- Pagination --}}
    @if($logs->hasPages())
        <div class="flex justify-center">
            {{ $logs->links() }}
        </div>
    @endif
</div>
@endsection
