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
        // 檢測移動設備
        $userAgent = $request->userAgent();
        $isMobile = preg_match('/(android|iphone|ipad|mobile)/i', $userAgent);
        
        $currentUser = Auth::user();
        
        // 構建基礎查詢 - User Management 只顯示普通用戶
        $query = User::where('is_admin', 0);
        
        // 移動版使用分頁，桌面版獲取所有數據供 DataTable 使用
        if ($isMobile) {
            $users = $query->orderBy('created_at', 'desc')->paginate(10);
        } else {
            $users = $query->get();
        }
        
        return view('admin.users.index', compact('users', 'isMobile'));
    }

    // 用戶詳情
    public function show(User $user)
    {
        // 檢測設備類型
        $userAgent = request()->header('User-Agent', '');
        $isMobile = preg_match('/(android|iphone|ipad|mobile)/i', $userAgent);
        
        return view('admin.users.show', compact('user', 'isMobile'));
    }

    // 編輯用戶
    public function edit(User $user)
    {
        // 檢測設備類型
        $userAgent = request()->header('User-Agent', '');
        $isMobile = preg_match('/(android|iphone|ipad|mobile)/i', $userAgent);
        
        return view('admin.users.edit', compact('user', 'isMobile'));
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
