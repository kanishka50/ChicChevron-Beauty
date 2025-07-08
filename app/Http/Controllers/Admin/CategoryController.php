<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * Display a listing of the categories.
     */
    public function index()
    {
        $categories = Category::with('parent')
            ->withCount('products')
            ->ordered()
            ->paginate(10);
            
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category.
     */
    public function create()
    {
        $parentCategories = Category::parents()
            ->active()
            ->ordered()
            ->get();
            
        return view('admin.categories.create', compact('parentCategories'));
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(CategoryRequest $request)
    {
        $data = $request->validated();
        
        // Handle image upload
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('categories', 'public');
        }
        
        // Set status
        $data['is_active'] = $request->has('is_active');
        
        // Set sort order (get max + 1)
        $data['sort_order'] = Category::max('sort_order') + 1;
        
        Category::create($data);
        
        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created successfully.');
    }

    /**
     * Display the specified category.
     */
    public function show(Category $category)
    {
        $category->load(['parent', 'children', 'products']);
        
        return view('admin.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit(Category $category)
    {
        $parentCategories = Category::parents()
            ->where('id', '!=', $category->id)
            ->active()
            ->ordered()
            ->get();
            
        return view('admin.categories.edit', compact('category', 'parentCategories'));
    }

    /**
     * Update the specified category in storage.
     */
    public function update(CategoryRequest $request, Category $category)
    {
        $data = $request->validated();
        
        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            $data['image'] = $request->file('image')->store('categories', 'public');
        }
        
        // Set status
        $data['is_active'] = $request->has('is_active');
        
        // Handle sort order if provided
        if ($request->has('sort_order')) {
            $data['sort_order'] = $request->input('sort_order');
        }
        
        $category->update($data);
        
        return redirect()->route('admin.categories.index')
            ->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy(Category $category)
    {
        // Check if category has products
        if ($category->products()->exists()) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Cannot delete category with products.');
        }
        
        // Check if category has sub-categories
        if ($category->has_children) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Cannot delete category with sub-categories.');
        }
        
        // Delete image if exists
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }
        
        $category->delete();
        
        return redirect()->route('admin.categories.index')
            ->with('success', 'Category deleted successfully.');
    }
    
    /**
     * Toggle category status
     */
    public function toggleStatus(Category $category)
    {
        $category->update(['is_active' => !$category->is_active]);
        
        return response()->json([
            'success' => true,
            'is_active' => $category->is_active
        ]);
    }
}