<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // 用戶列表
    public function index(Request $request)
    {
        $currentUser = Auth::user();
        
        // 構建基礎查詢
        $query = User::query();
        
        // 如果是普通 admin，過濾掉 super admin
        if ($currentUser->is_admin === User::ROLE_ADMIN) {
            $query->where('is_admin', '<', User::ROLE_SUPER_ADMIN);
        }
        
        // 獲取所有用戶數據供DataTable使用
        $users = $query->get();
        
        return view('admin.users.index', compact('users'));
    }

    // 用戶詳情
    public function show(User $user)
    {
        $currentUser = Auth::user();
        
        // 普通 admin 無法查看 super admin
        if ($currentUser->is_admin === User::ROLE_ADMIN && $user->is_admin === User::ROLE_SUPER_ADMIN) {
            abort(404);
        }
        
        return view('admin.users.show', compact('user'));
    }

    // 編輯用戶
    public function edit(User $user)
    {
        $currentUser = Auth::user();
        
        // 普通 admin 無法編輯 super admin
        if ($currentUser->is_admin === User::ROLE_ADMIN && $user->is_admin === User::ROLE_SUPER_ADMIN) {
            abort(404);
        }
        
        return view('admin.users.edit', compact('user'));
    }

    // 更新用戶
    public function update(Request $request, User $user)
    {
        $currentUser = Auth::user();
        
        // 普通 admin 無法更新 super admin
        if ($currentUser->is_admin === User::ROLE_ADMIN && $user->is_admin === User::ROLE_SUPER_ADMIN) {
            abort(404);
        }
        
        $validated = $request->validate([
            'username' => 'required|string|max:50',
            'email' => 'required|email|max:319',
            'phone' => 'nullable|string|max:20',
            'is_admin' => 'boolean',
        ]);
        
        // 普通 admin 不能將用戶提升為 super admin
        if ($currentUser->is_admin === User::ROLE_ADMIN && isset($validated['is_admin']) && $validated['is_admin'] >= User::ROLE_SUPER_ADMIN) {
            unset($validated['is_admin']);
        }
        
        $user->update($validated);
        return redirect()->route('admin.users.show', $user->id)
            ->with('success', 'User updated successfully.');
    }

    // 刪除用戶
    public function destroy(User $user)
    {
        $currentUser = Auth::user();
        
        // 普通 admin 無法刪除 super admin
        if ($currentUser->is_admin === User::ROLE_ADMIN && $user->is_admin === User::ROLE_SUPER_ADMIN) {
            return redirect()->route('admin.users.index')
                ->with('error', 'You cannot delete a Super Admin.');
        }
        
        // 防止刪除自己
        if ($currentUser->id === $user->id) {
            return redirect()->route('admin.users.index')
                ->with('error', 'You cannot delete yourself.');
        }
        
        $user->delete();
        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }
}
