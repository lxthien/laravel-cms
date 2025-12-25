@extends('admin.layouts.app')

@section('title', 'Timeline người dùng')
@section('page-title', 'Timeline')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow p-6 flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold">Lịch sử hoạt động: {{ $user->name }}</h3>
            <p class="text-sm text-gray-500">Email: {{ $user->email }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.activity-logs.index') }}" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Quay lại</a>
            <a href="{{ route('admin.activity-logs.export', array_merge(request()->query(), ['user_id' => $user->id])) }}" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">Xuất CSV</a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <ul class="divide-y">
            @forelse($logs as $log)
                <li class="p-4">
                    <div class="flex justify-between">
                        <div>
                            <div class="text-sm text-gray-600">{{ $log->log_name }} · <span class="text-xs text-gray-400">{{ $log->created_at->toDayDateTimeString() }}</span></div>
                            <div class="mt-1 text-gray-900">{{ $log->description }}</div>

                            @if(!empty($log->properties))
                                <div class="mt-3 text-sm text-gray-700">
                                    @foreach($log->properties as $key => $value)
                                        <div class="mb-1"><strong class="text-gray-600">{{ $key }}:</strong> {{ is_array($value) ? json_encode($value) : $value }}</div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <div class="text-right text-sm text-gray-500">
                            @if($log->subject_type)
                                <div>{{ class_basename($log->subject_type) }}</div>
                                <div class="text-xs text-gray-400">ID: {{ $log->subject_id }}</div>
                            @endif
                            <div class="mt-2">
                                <a href="{{ route('admin.activity-logs.show', $log) }}" class="text-blue-600 hover:underline">Chi tiết</a>
                            </div>
                        </div>
                    </div>
                </li>
            @empty
                <li class="p-6 text-center text-gray-500">Không có hoạt động nào cho người dùng này.</li>
            @endforelse
        </ul>
    </div>

    <div class="flex justify-center">{{ $logs->links() }}</div>
</div>
@endsection
