<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Building;
use Illuminate\Http\Request;

class BuildingController extends Controller
{
    public function index()
    {
        $buildings = Building::orderBy('campus')->orderBy('name')->get();
        return view('master.buildings.index', compact('buildings'));
    }

    public function create()
    {
        $campuses = \App\Models\Campus::where('is_active', true)->orderBy('name')->get();
        return view('master.buildings.create', compact('campuses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'campus' => 'required|string|max:255',
            'zone' => 'nullable|string|max:255',
            'is_active' => 'boolean'
        ]);

        Building::create([
            'name' => $request->name,
            'campus' => $request->campus,
            'zone' => $request->zone,
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->route('master.buildings.index')->with('success', 'Building added successfully!');
    }

    public function edit(Building $building)
    {
        $campuses = \App\Models\Campus::where('is_active', true)->orderBy('name')->get();
        return view('master.buildings.edit', compact('building', 'campuses'));
    }

    public function update(Request $request, Building $building)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'campus' => 'required|string|max:255',
            'zone' => 'nullable|string|max:255',
            'is_active' => 'boolean'
        ]);

        $building->update([
            'name' => $request->name,
            'campus' => $request->campus,
            'zone' => $request->zone,
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->route('master.buildings.index')->with('success', 'Building updated successfully!');
    }

    public function destroy(Building $building)
    {
        $building->delete();
        return redirect()->route('master.buildings.index')->with('success', 'Building deleted successfully!');
    }
}
