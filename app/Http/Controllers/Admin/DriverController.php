<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class DriverController extends Controller
{
    // 用戶列表
    public function index(Request $request)
    {
        // 檢測移動設備
        $userAgent = $request->userAgent();
        $isMobile = preg_match('/(android|iphone|ipad|mobile)/i', $userAgent);

        $currentUser = Auth::user();

        // 構建基礎查詢 - User Management 只顯示普通用戶
        $query = User::where('user_role', 'driver');

        // 移動版使用分頁，桌面版獲取所有數據供 DataTable 使用
        if ($isMobile) {
            $drivers = $query->orderBy('created_at', 'desc')->paginate(10);
        } else {
            $drivers = $query->get();
        }

        return view('admin.drivers.index', compact('drivers', 'isMobile'));
    }

    // 用戶詳情
    public function show(User $driver)
    {
        // 檢測設備類型
        $userAgent = request()->header('User-Agent', '');
        $isMobile = preg_match('/(android|iphone|ipad|mobile)/i', $userAgent);

        return view('admin.drivers.show', compact('driver', 'isMobile'));
    }

    // 編輯用戶
    public function edit(User $driver)
    {
        // 檢測設備類型
        $userAgent = request()->header('User-Agent', '');
        $isMobile = preg_match('/(android|iphone|ipad|mobile)/i', $userAgent);

        return view('admin.drivers.edit', compact('driver', 'isMobile'));
    }

    /**
     * Show the form for creating a new admin.
     */
    public function create(Request $request)
    {
        // 檢測是否為移動設備
        $userAgent = $request->header('User-Agent');
        $isMobile = preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino|playbook|silk/i', $userAgent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($userAgent, 0, 4));

        return view('admin.drivers.create', compact('isMobile'));
    }

    /**
     * Store a newly created driver.
     */
    public function store(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string', 'max:50', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:319', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_role' => 'driver',
            'email_verified_at' => now(), // Auto-verify admin emails
        ]);

        return redirect()->route('admin.drivers.index')
            ->with('success', 'Admin created successfully!');
    }

    // 更新用戶
    public function update(Request $request, User $driver)
    {
        $currentUser = Auth::user();

        // 普通 admin 無法更新 super admin
        if ($currentUser->user_role === User::ROLE_ADMIN && $driver->user_role === User::ROLE_SUPER_ADMIN) {
            abort(404);
        }

        $validated = $request->validate([
            'username' => 'required|string|max:50',
            'email' => 'required|email|max:319',
            'phone' => 'nullable|string|max:20',
            'user_role' => 'required|in:user,driver,admin,super_admin',
        ]);

        // 普通 admin 不能將用戶提升為 super admin
        if ($currentUser->user_role === User::ROLE_ADMIN && isset($validated['user_role']) && $validated['user_role'] == User::ROLE_SUPER_ADMIN) {
            unset($validated['user_role']);
        }

        $driver->update($validated);
        return redirect()->route('admin.drivers.show', $driver->id)
            ->with('success', 'User updated successfully.');
    }

    // 刪除用戶
    public function destroy(User $driver)
    {
        $currentUser = Auth::user();

        // 普通 admin 無法刪除 super admin
        if ($currentUser->user_role === User::ROLE_ADMIN && $driver->user_role === User::ROLE_SUPER_ADMIN) {
            return redirect()->route('admin.drivers.index')
                ->with('error', 'You cannot delete a Super Admin.');
        }

        // 防止刪除自己
        if ($currentUser->id === $driver->id) {
            return redirect()->route('admin.drivers.index')
                ->with('error', 'You cannot delete yourself.');
        }

        $driver->delete();
        return redirect()->route('admin.drivers.index')
            ->with('success', 'User deleted successfully.');
    }
}
