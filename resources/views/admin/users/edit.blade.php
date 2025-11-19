@extends('admin.layouts.app')
@section('title', 'Edit User')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <form method="POST" action="{{ route('admin.users.update', $user) }}">
        @csrf @method('PUT')
        <div class="grid grid-cols-3 gap-6">
            <div class="col-span-2">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                        Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('name') border-red-500 @enderror"
                           required>
                    @error('name')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="email" id="email" value="{{ old('email', $user->email) }}"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('email') border-red-500 @enderror"
                           required>
                    @error('email')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="role">
                        Role <span class="text-red-500">*</span>
                    </label>
                    <select name="role" required>
                        <option value="admin"{{ old('role', $user->role) === 'admin' ? ' selected' : '' }}>Admin</option>
                        <option value="editor"{{ old('role', $user->role) === 'editor' ? ' selected' : '' }}>Editor</option>
                        <option value="author"{{ old('role', $user->role) === 'author' ? ' selected' : '' }}>Author</option>
                        <option value="subscriber"{{ old('role', $user->role) === 'subscriber' ? ' selected' : '' }}>Subscriber</option>
                    </select>
                    @error('role')<div class="text-red-500">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label>
                        <input type="checkbox" name="status" value="1" {{ $user->status ? 'checked' : '' }}> Active
                    </label>
                </div>
                <button type="submit" class="btn btn-blue mt-2">Save</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </div>
    </form>
</div>
@endsection