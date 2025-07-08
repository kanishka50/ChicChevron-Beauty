<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Texture;
use Illuminate\Http\Request;

class TextureController extends Controller
{
    /**
     * Display a listing of textures.
     */
    public function index()
    {
        $textures = Texture::withCount('products')->paginate(20);
        return view('admin.textures.index', compact('textures'));
    }

    /**
     * Store a newly created texture.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:textures',
        ]);

        Texture::create([
            'name' => $request->name,
            'is_default' => false,
        ]);

        return redirect()->route('admin.textures.index')
            ->with('success', 'Texture added successfully.');
    }

    /**
     * Update the specified texture.
     */
    public function update(Request $request, Texture $texture)
    {
        // Prevent editing default textures
        if ($texture->is_default) {
            return redirect()->route('admin.textures.index')
                ->with('error', 'Cannot edit default textures.');
        }

        $request->validate([
            'name' => 'required|string|max:100|unique:textures,name,' . $texture->id,
        ]);

        $texture->update(['name' => $request->name]);

        return redirect()->route('admin.textures.index')
            ->with('success', 'Texture updated successfully.');
    }

    /**
     * Remove the specified texture.
     */
    public function destroy(Texture $texture)
    {
        // Prevent deleting default textures
        if ($texture->is_default) {
            return redirect()->route('admin.textures.index')
                ->with('error', 'Cannot delete default textures.');
        }

        // Check if texture is used by products
        if ($texture->products()->exists()) {
            return redirect()->route('admin.textures.index')
                ->with('error', 'Cannot delete texture that is assigned to products.');
        }

        $texture->delete();

        return redirect()->route('admin.textures.index')
            ->with('success', 'Texture deleted successfully.');
    }
}