<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->paginate();
        $roles = Role::all();

        return view('users.index', compact('users', 'roles'));
    }

    public function updateRole(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Sync the selected role
        $user->syncRoles([$request->input('role')]);

        return redirect()->route('users.index')->with('success', 'User role updated successfully');
    }

    public function changeRole(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|in:kepala-toko,karyawan',
        ]);

        $user = User::findOrFail($request->user_id);

        // Debugging log
        Log::info('User ditemukan', ['user_id' => $user->id]);

        // Sync roles
        $user->syncRoles($request->role);

        // Debugging log
        Log::info('Role berhasil diubah', ['new_role' => $request->role]);

        if ($user->hasRole($request->role)) {
            return redirect()->back()->with('success', 'Role pengguna sudah diatur.');
        } else {
            return redirect()->back()->with('error', 'Gagal mengubah role pengguna.');
        }
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:kepala-toko,karyawan',
        ]);

        // Create the user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        // Assign the selected role to the user
        $user->assignRole($request->role);

        // Redirect back with a success message
        return redirect()->route('users.index')->with('success', 'User created successfully');
    }

    public function deleteAccount($id)
    {
        $user = User::findOrFail($id);

        // Delete the user
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User account deleted successfully');
    }

}
