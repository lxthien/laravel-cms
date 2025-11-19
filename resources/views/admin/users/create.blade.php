@extends('admin.layouts.app')
@section('title', 'Create User')

@section('content')
<form method="POST" action="{{ route('admin.users.store') }}">
    @csrf
    <div>
        <label>Name</label>
        <input name="name" value="{{ old('name') }}" required>
        @error('name')<div class="text-red-500">{{ $message }}</div>@enderror
    </div>
    <div>
        <label>Email</label>
        <input name="email" type="email" value="{{ old('email') }}" required>
        @error('email')<div class="text-red-500">{{ $message }}</div>@enderror
    </div>
    <div>
        <label>Password</label>
        <input name="password" type="password" required>
        @error('password')<div class="text-red-500">{{ $message }}</div>@enderror
    </div>
    <div>
        <label>Confirm Password</label>
        <input name="password_confirmation" type="password" required>
    </div>
    <div>
        <label>Role</label>
        <select name="role" required>
            <option value="admin">Admin</option>
            <option value="editor">Editor</option>
            <option value="author">Author</option>
            <option value="subscriber">Subscriber</option>
        </select>
        @error('role')<div class="text-red-500">{{ $message }}</div>@enderror
    </div>
    <button type="submit" class="btn btn-blue mt-2">Create</button>
    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancel</a>
</form>
@endsection
