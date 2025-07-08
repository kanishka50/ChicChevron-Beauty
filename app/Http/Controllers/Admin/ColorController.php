<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Color;
use Illuminate\Http\Request;

class ColorController extends Controller
{
    /**
     * Display a listing of colors.
     */
    public function index()
    {
        $colors = Color::withCount('products')->paginate(30);
        return view('admin.colors.index', compact('colors'));
    }

    /**
     * Store a newly created color.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:colors',
            'hex_code' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
        ]);

        Color::create([
            'name' => $request->name,
            'hex_code' => strtoupper($request->hex_code),
            'is_default' => false,
        ]);

        return redirect()->route('admin.colors.index')
            ->with('success', 'Color added successfully.');
    }

    /**
     * Update the specified color.
     */
    public function update(Request $request, Color $color)
    {
        // Prevent editing default colors
        if ($color->is_default) {
            return redirect()->route('admin.colors.index')
                ->with('error', 'Cannot edit default colors.');
        }

        $request->validate([
            'name' => 'required|string|max:100|unique:colors,name,' . $color->id,
            'hex_code' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
        ]);

        $color->update([
            'name' => $request->name,
            'hex_code' => strtoupper($request->hex_code),
        ]);

        return redirect()->route('admin.colors.index')
            ->with('success', 'Color updated successfully.');
    }

    /**
     * Remove the specified color.
     */
    public function destroy(Color $color)
    {
        // Prevent deleting default colors
        if ($color->is_default) {
            return redirect()->route('admin.colors.index')
                ->with('error', 'Cannot delete default colors.');
        }

        // Check if color is used by products
        if ($color->products()->exists()) {
            return redirect()->route('admin.colors.index')
                ->with('error', 'Cannot delete color that is assigned to products.');
        }

        $color->delete();

        return redirect()->route('admin.colors.index')
            ->with('success', 'Color deleted successfully.');
    }
}