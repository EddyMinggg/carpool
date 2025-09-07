<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Http\Request;

class TripController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $trips = Trip::paginate(10);

        return view('admin.trips.index', compact('trips'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::select('id', 'username','email')->get();
        $statuses = Trip::getStatusOptions();
        return view('admin.trips.create', compact('users', 'statuses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. 验证请求数据
        $validated = $request->validate([
            'creator_id' => 'required|exists:users,id',
            'end_place' => 'required|string|max:100',
            'plan_departure_time' => 'required|date_format:Y-m-d\TH:i',
            'max_people' => 'required|integer|min:1|max:10',
            'trip_status' => 'required|in:pending,voting,departed,completed,cancelled',
            'base_price' => 'required|integer|min:0',
            'is_private' => 'nullable|boolean',
            'start_place' => 'nullable|string|max:100'
        ]);

        // 2. 关键：主动给 start_place 赋值 null
        $validated['start_place'] = null;

        // 3. 处理 checkbox
        $validated['is_private'] = $request->has('is_private');

        // 4. 创建行程
        try {
            Trip::create($validated);
            return redirect()->route('admin.trips.index')
                ->with('success', 'Trip template created successfully!');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Failed to create trip: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Trip $trip)
    {
        // Eager-load relationships to avoid "N+1" query problem
        $trip->load([
            'creator',           // Load the trip's creator (User)
            'joins.user',        // Load all participants + their User info
        ]);

        return view('admin.trips.show', compact('trip'));
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Trip $trip)
    {
        $users = User::all(); 
        $statuses = Trip::getStatusOptions();
        return view('admin.trips.edit', compact('trip', 'users', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Trip $trip)
    {
        $validated = $request->validate([
            'creator_id' => 'required|exists:users,id',
            'start_place' => 'required|string|max:100',
            'end_place' => 'required|string|max:100',
            'plan_departure_time' => 'required|date_format:H:i',
            'max_people' => 'required|integer|between:1,4',
            'is_private' => 'boolean',
            'trip_status' => 'required|in:'.implode(',', array_keys(Trip::getStatusOptions())),
            'base_price' => 'required|numeric|min:0',
        ]);

        $trip->update($validated);

        return redirect()->route('admin.trips.index')->with('success', 'Trip updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Trip $trip)
    {
        $trip->delete();
        return redirect()->route('admin.trips.index')->with('success', 'Trip deleted successfully.');
    }
}
