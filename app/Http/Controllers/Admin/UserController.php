<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // 用戶列表
    public function index()
    {
        $users = User::paginate(15);
        return view('admin.users.index', compact('users'));
    }

    // 用戶詳情
    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    // 編輯用戶
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    // 更新用戶
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:50',
            'email' => 'required|email|max:319',
            'phone' => 'nullable|string|max:20',
            'is_admin' => 'boolean',
        ]);
        $user->update($validated);
        return redirect()->route('admin.users.show', $user->id)
            ->with('success', 'User updated successfully.');
    }

    // 刪除用戶
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }
}
