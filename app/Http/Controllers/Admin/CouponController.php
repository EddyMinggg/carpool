<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    // 優惠碼列表
    public function index()
    {
        $coupons = Coupon::paginate(15);
        return view('admin.coupons.index', compact('coupons'));
    }

    // 優惠碼詳情
    public function show(Coupon $coupon)
    {
        return view('admin.coupons.show', compact('coupon'));
    }

    // 新增優惠碼表單
    public function create()
    {
        return view('admin.coupons.create');
    }

    // 優惠碼新增
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code',
            'discount_amount' => 'required|numeric|min:0',
            'valid_from' => 'nullable|date',
            'valid_to' => 'nullable|date|after_or_equal:valid_from',
                'enabled' => 'boolean',
            'usage_limit' => 'nullable|integer|min:1',
        ]);
            $validated['enabled'] = $request->has('enabled');
            Coupon::create($validated);
        return redirect()->route('admin.coupons.index')
            ->with('success', 'Coupon created successfully.');
    }

    // 編輯優惠碼表單
    public function edit(Coupon $coupon)
    {
        return view('admin.coupons.edit', compact('coupon'));
    }

    // 優惠碼更新
    public function update(Request $request, Coupon $coupon)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code,' . $coupon->id,
            'discount_amount' => 'required|numeric|min:0',
            'valid_from' => 'nullable|date',
            'valid_to' => 'nullable|date|after_or_equal:valid_from',
                'enabled' => 'boolean',
            'usage_limit' => 'nullable|integer|min:1',
        ]);
            $validated['enabled'] = $request->has('enabled');
            $coupon->update($validated);
        return redirect()->route('admin.coupons.show', $coupon->id)
            ->with('success', 'Coupon updated successfully.');
    }

    // 刪除優惠碼
    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return redirect()->route('admin.coupons.index')
            ->with('success', 'Coupon deleted successfully.');
    }
}
