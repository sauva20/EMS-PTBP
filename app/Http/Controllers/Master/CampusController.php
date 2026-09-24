<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Campus;
use Illuminate\Http\Request;

class CampusController extends Controller
{
    public function index()
    {
        $campuses = Campus::orderBy('name')->get();
        return view('master.campuses.index', compact('campuses'));
    }

    public function create()
    {
        return view('master.campuses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:campuses,name',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        Campus::create([
            'name' => $request->name,
            'location' => $request->location,
            'description' => $request->description,
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->route('master.campuses.index')->with('success', 'Campus added successfully!');
    }

    public function edit(Campus $campus)
    {
        return view('master.campuses.edit', compact('campus'));
    }

    public function update(Request $request, Campus $campus)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:campuses,name,' . $campus->id,
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $campus->update([
            'name' => $request->name,
            'location' => $request->location,
            'description' => $request->description,
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->route('master.campuses.index')->with('success', 'Campus updated successfully!');
    }

    public function destroy(Campus $campus)
    {
        // Check if there are buildings using this campus
        $hasBuildings = \App\Models\Building::where('campus', $campus->name)->exists();
        if ($hasBuildings) {
            return redirect()->route('master.campuses.index')->with('error', 'Cannot delete campus because it has buildings attached to it.');
        }

        $campus->delete();
        return redirect()->route('master.campuses.index')->with('success', 'Campus deleted successfully!');
    }
}
