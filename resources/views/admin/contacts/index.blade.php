@extends('admin.layouts.app')
@section('title', 'Quản lý liên hệ')
@section('content')
<h2 class="font-bold text-2xl mb-4">Danh sách liên hệ</h2>
<table class="min-w-full">
    <thead>
        <tr>
            <th>ID</th><th>Họ tên</th><th>Email</th><th>Phone</th><th>Gửi lúc</th><th></th>
        </tr>
    </thead>
    <tbody>
        @foreach($contacts as $contact)
        <tr>
            <td>{{ $contact->id }}</td>
            <td>{{ $contact->name }}</td>
            <td><a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a></td>
            <td>{{ $contact->phone }}</td>
            <td>{{ $contact->created_at->format('d/m/Y H:i') }}</td>
            <td>
                <a href="{{ route('admin.contacts.show', $contact) }}" class="text-blue-500">Chi tiết</a>
                <form method="POST" action="{{ route('admin.contacts.destroy', $contact) }}" style="display:inline">
                    @csrf @method('DELETE')
                    <button onclick="return confirm('Xoá liên hệ này?')" class="text-red-600">Xoá</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
{{ $contacts->links() }}
@endsection