<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AdminController extends Controller
{
    /**
     * Display a listing of admins.
     */
    public function index()
    {
        $admins = User::where('is_admin', '>=', User::ROLE_ADMIN)
            ->orderBy('is_admin', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('super-admin.admins.index', compact('admins'));
    }

    /**
     * Show the form for creating a new admin.
     */
    public function create()
    {
        return view('super-admin.admins.create');
    }

    /**
     * Store a newly created admin.
     */
    public function store(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string', 'max:50', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:319', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:1,2'], // Only allow Admin or Super Admin
        ]);

        User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_admin' => $request->role,
            'email_verified_at' => now(), // Auto-verify admin emails
        ]);

        return redirect()->route('super-admin.admins.index')
            ->with('success', 'Admin created successfully!');
    }

    /**
     * Display the specified admin.
     */
    public function show(User $admin)
    {
        // Ensure we're only showing admins
        if (!$admin->isAdmin()) {
            abort(404);
        }

        return view('super-admin.admins.show', compact('admin'));
    }

    /**
     * Show the form for editing the specified admin.
     */
    public function edit(User $admin)
    {
        // Ensure we're only editing admins
        if (!$admin->isAdmin()) {
            abort(404);
        }

        return view('super-admin.admins.edit', compact('admin'));
    }

    /**
     * Update the specified admin.
     */
    public function update(Request $request, User $admin)
    {
        // Ensure we're only updating admins
        if (!$admin->isAdmin()) {
            abort(404);
        }

        $request->validate([
            'username' => ['required', 'string', 'max:50', 'unique:users,username,' . $admin->id],
            'email' => ['required', 'string', 'email', 'max:319', 'unique:users,email,' . $admin->id],
            'role' => ['required', 'in:1,2'], // Only allow Admin or Super Admin
        ]);

        $updateData = [
            'username' => $request->username,
            'email' => $request->email,
            'is_admin' => $request->role,
        ];

        // Only update password if provided
        if ($request->filled('password')) {
            $request->validate([
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);
            $updateData['password'] = Hash::make($request->password);
        }

        $admin->update($updateData);

        return redirect()->route('super-admin.admins.index')
            ->with('success', 'Admin updated successfully!');
    }

    /**
     * Remove the specified admin.
     */
    public function destroy(User $admin)
    {
        // Ensure we're only deleting admins and not the current user
        if (!$admin->isAdmin() || $admin->id === Auth::user()->id) {
            return redirect()->back()->with('error', 'Cannot delete this user.');
        }

        $admin->delete();

        return redirect()->route('super-admin.admins.index')
            ->with('success', 'Admin deleted successfully!');
    }
}