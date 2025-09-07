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

    public function show(TripJoin $order)
    {
        $order->load(['user', 'trip']);
        return view('admin.orders.show', compact('order'));
    }
}
