<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\ComplaintResponse;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ComplaintController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Display user's complaints
     */
    public function index()
    {
        $complaints = Complaint::where('user_id', Auth::id())
            ->with(['order'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('user.complaints.index', compact('complaints'));
    }

    /**
     * Show complaint form
     */
    public function create()
    {
        // Get user's recent orders (last 6 months)
        $orders = Order::where('user_id', Auth::id())
            ->where('created_at', '>=', now()->subMonths(6))
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.complaints.create', compact('orders'));
    }

    /**
     * Store new complaint
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'nullable|exists:orders,id',
            'complaint_type' => 'required|in:product_not_received,wrong_product,damaged_product,other',
            'subject' => 'required|string|max:255',
            'description' => 'required|string|max:2000',
        ]);

        // Verify order belongs to user if provided
        if ($validated['order_id']) {
            $order = Order::find($validated['order_id']);
            if ($order->user_id !== Auth::id()) {
                abort(403);
            }
        }

        // Generate complaint number
        $complaintNumber = 'CMP-' . date('Y') . '-' . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
        
        // Ensure uniqueness
        while (Complaint::where('complaint_number', $complaintNumber)->exists()) {
            $complaintNumber = 'CMP-' . date('Y') . '-' . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
        }

        // Create complaint
        $complaint = Complaint::create([
            'user_id' => Auth::id(),
            'order_id' => $validated['order_id'],
            'complaint_number' => $complaintNumber,
            'complaint_type' => $validated['complaint_type'],
            'subject' => $validated['subject'],
            'description' => $validated['description'],
            'status' => 'open',
        ]);

        return redirect()->route('user.complaints.show', $complaint)
            ->with('success', 'Your complaint has been submitted successfully. Complaint Number: ' . $complaintNumber);
    }

    /**
     * Show complaint details
     */
    public function show(Complaint $complaint)
    {
        // Ensure user can only view their own complaints
        if ($complaint->user_id !== Auth::id()) {
            abort(403);
        }

        // Load relationships
        $complaint->load(['order', 'responses.admin', 'responses.user']);

        return view('user.complaints.show', compact('complaint'));
    }

    /**
     * Add response to complaint
     */
    public function respond(Request $request, Complaint $complaint)
    {
        // Ensure user can only respond to their own complaints
        if ($complaint->user_id !== Auth::id()) {
            abort(403);
        }

        // Check if complaint is still open
        if ($complaint->status === 'closed') {
            return redirect()->back()
                ->with('error', 'You cannot respond to a closed complaint.');
        }

        $validated = $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        // Create response
        ComplaintResponse::create([
            'complaint_id' => $complaint->id,
            'user_id' => Auth::id(),
            'message' => $validated['message'],
            'is_admin_response' => false,
        ]);

        return redirect()->route('user.complaints.show', $complaint)
            ->with('success', 'Your response has been sent successfully.');
    }
}