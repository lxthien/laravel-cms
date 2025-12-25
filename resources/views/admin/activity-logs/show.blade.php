@extends('admin.layouts.app')

@section('title', 'Chi tiết hoạt động')
@section('page-title', 'Chi Tiết Hoạt Động')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        {{-- Back Button --}}
        <a href="{{ route('admin.activity-logs.index') }}"
            class="inline-flex items-center text-blue-600 hover:text-blue-900">
            <i class="fas fa-arrow-left mr-2"></i>Quay lại
        </a>

        {{-- Activity Header --}}
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center">
                    <div
                        class="h-12 w-12 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white text-lg font-bold">
                        {{ strtoupper(substr($activityLog->user->name, 0, 1)) }}
                    </div>
                    <div class="ml-4">
                        <h2 class="text-2xl font-bold text-gray-900">{{ $activityLog->user->name }}</h2>
                        <p class="text-gray-600">{{ $activityLog->user->email }}</p>
                    </div>
                </div>
                @include('admin.activity-logs.partials._log-badge', ['log' => $activityLog, 'size' => 'lg'])
            </div>

            {{-- Description --}}
            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <p class="text-lg text-gray-800">{{ $activityLog->description }}</p>
            </div>

            {{-- Info Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Thời gian</label>
                    <p class="text-lg text-gray-900">{{ $activityLog->created_at->format('d/m/Y H:i:s') }}</p>
                    <p class="text-sm text-gray-600">({{ $activityLog->created_at->diffForHumans() }})</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Địa chỉ IP</label>
                    <p class="text-lg text-gray-900 font-mono">{{ $activityLog->ip_address ?? 'N/A' }}</p>
                </div>

                @if($activityLog->subject_type)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Loại đối tượng</label>
                        <p class="text-lg text-gray-900">{{ class_basename($activityLog->subject_type) }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ID đối tượng</label>
                        <p class="text-lg text-gray-900 font-mono">{{ $activityLog->subject_id }}</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Properties / Details --}}
        @if($activityLog->properties)
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Chi tiết bổ sung</h3>

                @if(isset($activityLog->properties['changes']))
                    <div class="space-y-4">
                        <h4 class="font-medium text-gray-900">Các thay đổi:</h4>
                        @foreach($activityLog->properties['changes'] as $field => $change)
                            @php
                                $oldVal = $change['old'] ?? null;
                                $newVal = $change['new'] ?? null;
                            @endphp
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="font-medium text-gray-900 mb-2">{{ $field }}</div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-sm text-gray-600">Giá trị cũ:</label>
                                        <div class="mt-1 p-2 bg-red-50 rounded border border-red-200">
                                            <code
                                                class="text-sm text-red-900">{{ is_array($oldVal) ? json_encode($oldVal) : ($oldVal ?? '-') }}</code>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="text-sm text-gray-600">Giá trị mới:</label>
                                        <div class="mt-1 p-2 bg-green-50 rounded border border-green-200">
                                            <code
                                                class="text-sm text-green-900">{{ is_array($newVal) ? json_encode($newVal) : ($newVal ?? '-') }}</code>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-gray-50 rounded-lg p-4">
                        <pre
                            class="text-sm font-mono text-gray-900 overflow-x-auto">{{ json_encode($activityLog->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                    </div>
                @endif
            </div>
        @endif

        {{-- User Agent --}}
        @if($activityLog->user_agent)
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Thông tin trình duyệt</h3>
                <div class="bg-gray-50 rounded-lg p-4">
                    <pre
                        class="text-sm font-mono text-gray-900 overflow-x-auto break-words">{{ $activityLog->user_agent }}</pre>
                </div>
            </div>
        @endif

        {{-- Related Activities --}}
        @if($activityLog->subject)
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Các hoạt động liên quan</h3>
                @php
                    $relatedLogs = \App\Models\ActivityLog::where('subject_type', $activityLog->subject_type)
                        ->where('subject_id', $activityLog->subject_id)
                        ->orderBy('created_at', 'desc')
                        ->limit(10)
                        ->get();
                @endphp

                @if($relatedLogs->count() > 0)
                    <div class="space-y-3">
                        @foreach($relatedLogs as $log)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex-1">
                                    <p class="text-sm text-gray-900">{{ $log->description }}</p>
                                    <p class="text-xs text-gray-600">{{ $log->created_at->diffForHumans() }}</p>
                                </div>
                                @php
                                    $badgeColors = [
                                        'create' => 'bg-green-100 text-green-800',
                                        'update' => 'bg-blue-100 text-blue-800',
                                        'delete' => 'bg-red-100 text-red-800',
                                    ];
                                @endphp
                                <span
                                    class="ml-4 px-2 py-1 text-xs font-semibold rounded {{ $badgeColors[$log->log_name] ?? 'bg-gray-100' }}">
                                    {{ $labels[$log->log_name] ?? $log->log_name }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-600">Không có hoạt động nào khác cho đối tượng này</p>
                @endif
            </div>
        @endif
    </div>
@endsection