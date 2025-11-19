<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // List all users
    public function index()
    {
        $users = User::orderByDesc('created_at')->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    // Show create form
    public function create()
    {
        return view('admin.users.create');
    }

    // Store new user
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|max:150',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6',
            'role'     => 'required|in:admin,editor,author,subscriber'
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => $validated['role'],
            'status'   => true,
        ]);
        // Optional: assign role with spatie/laravel-permission
        if (method_exists($user, 'assignRole')) {
            $user->assignRole($validated['role']);
        }
        return redirect()->route('admin.users.index')->with('success', 'User created');
    }

    // Show edit form
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    // Update user
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'   => 'required|max:150',
            'email'  => "required|email|unique:users,email,{$user->id}",
            'role'   => 'required|in:admin,editor,author,subscriber',
            'status' => 'boolean',
        ]);
        $user->update($validated);

        // Optional: update role with spatie/laravel-permission
        if (method_exists($user, 'syncRoles')) {
            $user->syncRoles([$validated['role']]);
        }
        return redirect()->route('admin.users.index')->with('success', 'User updated');
    }

    // Delete user
    public function destroy(User $user)
    {
        if (auth()->id() == $user->id) {
            return back()->with('error', 'You cannot delete your own user.');
        }
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted');
    }
}
