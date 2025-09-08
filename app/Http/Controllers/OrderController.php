<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trip;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $trips = Trip::paginate(10);

        return view('trips.index', compact('trips'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'creator_id' => 'required|exists:users,id',
            'pickup_location' => 'nullable|string|max:100',
            'dropoff_location' => 'required|string|max:100',
            'planned_departure_time' => 'required|date_format:Y-m-d\TH:i',
            'max_people' => 'required|integer|min:1|max:10'
        ]);

        Trip::create([
            'creator_id' => $request->user()->id,
            'pickup_location' => $request->input('pickup_location'),
            'dropoff_location' => $request->input('dropoff_location'),
            'planned_departure_time' => $request->input('planned_departure_time'),
            'max_people' => $request->input('max_people')
        ]);

        return redirect()->route('trips.index')
            ->with('success', 'Trip template created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
