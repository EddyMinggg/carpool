<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TripJoin;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = TripJoin::with(['user', 'trip'])
            ->orderByDesc('created_at')
            ->paginate(20);
        return view('admin.orders.index', compact('orders'));
    }

    public function show($orderKey)
    {
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
            
        return view('admin.orders.show', compact('order'));
    }
}
