<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Http\Requests\Admin\BannerRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BannerController extends Controller
{
    /**
     * Display a listing of banners
     */
    public function index()
    {
        $banners = Banner::ordered()->paginate(10);
        return view('admin.banners.index', compact('banners'));
    }

    /**
     * Show the form for creating a new banner
     */
    public function create()
    {
        $maxOrder = Banner::max('sort_order') ?? 0;
        return view('admin.banners.create', compact('maxOrder'));
    }

    /**
     * Store a newly created banner
     */
    public function store(BannerRequest $request)
    {
        DB::beginTransaction();
        
        try {
            $data = $request->validated();
            
            // Handle image upload
            if ($request->hasFile('image')) {
                $data['image_path'] = $request->file('image')->store('banners', 'public');
            }
            
            // Set sort order if not provided
            if (!isset($data['sort_order'])) {
                $data['sort_order'] = Banner::max('sort_order') + 1 ?? 1;
            }
            
            Banner::create($data);
            
            DB::commit();
            
            return redirect()->route('admin.banners.index')
                ->with('success', 'Banner created successfully.');
                
        } catch (\Exception $e) {
            DB::rollback();
            
            // Delete uploaded image if exists
            if (isset($data['image_path'])) {
                Storage::disk('public')->delete($data['image_path']);
            }
            
            Log::error('Banner creation failed: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create banner. Please try again.');
        }
    }

    /**
     * Show the form for editing banner
     */
    public function edit(Banner $banner)
    {
        return view('admin.banners.edit', compact('banner'));
    }

    /**
     * Update the specified banner
     */
    public function update(BannerRequest $request, Banner $banner)
    {
        DB::beginTransaction();
        
        try {
            $data = $request->validated();
            
            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image
                if ($banner->image_path) {
                    Storage::disk('public')->delete($banner->image_path);
                }
                
                $data['image_path'] = $request->file('image')->store('banners', 'public');
            }
            
            $banner->update($data);
            
            DB::commit();
            
            return redirect()->route('admin.banners.index')
                ->with('success', 'Banner updated successfully.');
                
        } catch (\Exception $e) {
            DB::rollback();
            
            // Delete newly uploaded image if exists
            if (isset($data['image_path']) && $data['image_path'] !== $banner->image_path) {
                Storage::disk('public')->delete($data['image_path']);
            }
            
            Log::error('Banner update failed: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update banner. Please try again.');
        }
    }

    /**
     * Remove the specified banner
     */
    public function destroy(Banner $banner)
    {
        try {
            // Delete image file
            if ($banner->image_path) {
                Storage::disk('public')->delete($banner->image_path);
            }
            
            $banner->delete();
            
            return redirect()->route('admin.banners.index')
                ->with('success', 'Banner deleted successfully.');
                
        } catch (\Exception $e) {
            Log::error('Banner deletion failed: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Failed to delete banner. Please try again.');
        }
    }

    /**
     * Toggle banner active status
     */
    public function toggleStatus(Banner $banner)
    {
        $banner->is_active = !$banner->is_active;
        $banner->save();
        
        return response()->json([
            'success' => true,
            'is_active' => $banner->is_active
        ]);
    }

    /**
     * Update banner order
     */
    public function updateOrder(Request $request)
    {
        $request->validate([
            'banners' => 'required|array',
            'banners.*' => 'exists:banners,id'
        ]);
        
        foreach ($request->banners as $index => $bannerId) {
            Banner::where('id', $bannerId)->update(['sort_order' => $index + 1]);
        }
        
        return response()->json(['success' => true]);
    }
}