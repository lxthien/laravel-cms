<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    // List all users
    public function index(Request $request)
    {
        $query = User::with('roles');

        // Search
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by Role
        if ($role = $request->input('role')) {
            $query->role($role);
        }

        $users = $query->orderByDesc('created_at')->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    // Show create form
    public function create()
    {
        $roles = Role::pluck('name', 'name')->all();
        return view('admin.users.create', compact('roles'));
    }

    // Store new user
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:150',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6',
            'role' => 'required|exists:roles,name'
        ]);

        $userData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'status' => $request->boolean('status', true),
        ];

        $user = User::create($userData);
        // Optional: assign role with spatie/laravel-permission
        if (method_exists($user, 'assignRole')) {
            $user->assignRole($validated['role']);
        }
        return redirect()->route('admin.users.index')->with('success', 'User created');
    }

    // Show edit form
    public function edit(User $user)
    {
        $roles = Role::pluck('name', 'name')->all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    // Update user
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|max:150',
            'email' => "required|email|unique:users,email,{$user->id}",
            'role' => 'required|exists:roles,name',
            'status' => 'boolean',
        ]);

        // Handle boolean status explicitly
        $validated['status'] = $request->boolean('status');

        // Extract role for separate handling
        $roleName = $validated['role'];
        unset($validated['role']);

        $user->update($validated);

        // Update role with spatie/laravel-permission
        if (method_exists($user, 'syncRoles')) {
            $user->syncRoles([$roleName]);
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
