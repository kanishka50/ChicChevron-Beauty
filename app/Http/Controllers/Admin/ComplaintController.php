<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\ComplaintResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComplaintController extends Controller
{
    /**
     * Display all complaints
     */
    public function index(Request $request)
    {
        $query = Complaint::with(['user', 'order']);

        // Filter by status if provided
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $complaints = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.complaints.index', compact('complaints'));
    }

    /**
     * Show complaint details
     */
    public function show(Complaint $complaint)
    {
        $complaint->load(['user', 'order', 'responses.admin', 'responses.user']);

        return view('admin.complaints.show', compact('complaint'));
    }

    /**
     * Admin response to complaint
     */
    public function respond(Request $request, Complaint $complaint)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:1000',
            'status' => 'nullable|in:open,in_progress,resolved,closed'
        ]);

        // Create response
        ComplaintResponse::create([
            'complaint_id' => $complaint->id,
            'admin_id' => Auth::guard('admin')->id(),
            'message' => $validated['message'],
            'is_admin_response' => true,
        ]);

        // Update status if provided
        if ($request->filled('status') && $complaint->status !== $validated['status']) {
            $complaint->update(['status' => $validated['status']]);
        }

        return redirect()->route('admin.complaints.show', $complaint)
            ->with('success', 'Response sent successfully.');
    }

    /**
     * Update complaint status
     */
    public function updateStatus(Request $request, Complaint $complaint)
    {
        $validated = $request->validate([
            'status' => 'required|in:open,in_progress,resolved,closed'
        ]);

        $complaint->update(['status' => $validated['status']]);

        return redirect()->back()
            ->with('success', 'Complaint status updated successfully.');
    }
}