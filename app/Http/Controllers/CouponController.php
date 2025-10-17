<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CouponController extends Controller
{
    /**
     * 验证优惠券
     */
    public function validate(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50',
            'amount' => 'required|numeric|min:0',
            'trip_id' => 'required|exists:trips,id',
        ]);

        $couponCode = strtoupper(trim($request->input('code')));
        $amount = (float) $request->input('amount');

        // 查找优惠券
        $coupon = Coupon::where('code', $couponCode)->first();

        if (!$coupon) {
            return response()->json([
                'valid' => false,
                'message' => __('Coupon code not found.')
            ]);
        }

        // 检查是否启用
        if (!$coupon->enabled) {
            return response()->json([
                'valid' => false,
                'message' => __('This coupon is no longer available.')
            ]);
        }

        // 检查有效期
        $now = Carbon::now();
        
        if ($coupon->valid_from && $now->lt($coupon->valid_from)) {
            return response()->json([
                'valid' => false,
                'message' => __('This coupon is not yet valid.')
            ]);
        }

        if ($coupon->valid_to && $now->gt($coupon->valid_to)) {
            return response()->json([
                'valid' => false,
                'message' => __('This coupon has expired.')
            ]);
        }

        // 检查使用次数限制
        if ($coupon->usage_limit && $coupon->used_count >= $coupon->usage_limit) {
            return response()->json([
                'valid' => false,
                'message' => __('This coupon has reached its usage limit.')
            ]);
        }

        // 检查每用户使用限制
        $userPhone = auth()->user() ? auth()->user()->phone : null;
        if ($userPhone && !$coupon->canBeUsedByUser($userPhone)) {
            return response()->json([
                'valid' => false,
                'message' => __('You have reached the usage limit for this coupon.')
            ]);
        }

        // 计算折扣金额（不能超过订单金额）
        $discountAmount = min($coupon->discount_amount, $amount);

        return response()->json([
            'valid' => true,
            'coupon' => [
                'id' => $coupon->id,
                'code' => $coupon->code,
                'discount_amount' => $coupon->discount_amount
            ],
            'discount_amount' => $discountAmount,
            'message' => __('Coupon is valid!')
        ]);
    }
}