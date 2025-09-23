<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TripJoin;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        // 檢測是否為移動設備
        $userAgent = $request->header('User-Agent') ?? '';
        $isMobile = preg_match('/Mobile|Android|iPhone|iPad|BlackBerry|Windows Phone/i', $userAgent);
        
        if ($isMobile) {
            // 移動版需要分頁
            $orders = TripJoin::with(['user', 'trip'])
                ->orderByDesc('created_at')
                ->paginate(10);
        } else {
            // 桌面版獲取所有數據給 DataTables
            $orders = TripJoin::with(['user', 'trip'])
                ->orderByDesc('created_at')
                ->get();
        }
        
        return view('admin.orders.index', compact('orders', 'isMobile'));
    }

    public function show($orderKey)
    {
        // 檢測是否為移動設備
        $userAgent = request()->header('User-Agent') ?? '';
        $isMobile = preg_match('/Mobile|Android|iPhone|iPad|BlackBerry|Windows Phone/i', $userAgent);
        
        // 解析複合鍵：trip_id-user_id
        $keys = explode('-', $orderKey);
        if (count($keys) !== 2) {
            abort(404);
        }
        
        $tripId = $keys[0];
        $userId = $keys[1];
        
        $order = TripJoin::with(['user', 'trip'])
            ->where('trip_id', $tripId)
            ->where('user_id', $userId)
            ->firstOrFail();
            
        return view('admin.orders.show', compact('order', 'isMobile'));
    }
}
