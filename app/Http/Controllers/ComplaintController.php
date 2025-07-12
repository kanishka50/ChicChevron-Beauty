<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\ComplaintResponse;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller as BaseController;

class ComplaintController extends BaseController
{
    use AuthorizesRequests;

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display user's complaints
     */
    public function index(Request $request)
    {
        $query = Complaint::where('user_id', Auth::id())
            ->with(['order', 'responses' => function($q) {
                $q->latest();
            }])
            ->orderBy('created_at', 'desc');

        // Filter by status if provided
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $complaints = $query->paginate(10);

        return view('user.complaints.index', compact('complaints'));
    }

    /**
     * Show complaint form
     */
    public function create()
    {
        // Get user's recent orders for the dropdown
        $recentOrders = Order::where('user_id', Auth::id())
            ->where('created_at', '>=', now()->subMonths(6))
            ->orderBy('created_at', 'desc')
            ->select('id', 'order_number', 'created_at', 'total_amount')
            ->get();

        return view('user.complaints.create', compact('recentOrders'));
    }

    /**
     * Store new complaint
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category' => 'required|in:product,delivery,payment,service,other',
            'order_id' => 'nullable|exists:orders,id',
            'subject' => 'required|string|max:200',
            'description' => 'required|string|max:2000',
            'priority' => 'required|in:low,medium,high',
            'contact_methods' => 'nullable|array',
            'contact_methods.*' => 'in:email,phone',
            'attachments' => 'nullable|array|max:5',
            'attachments.*' => 'file|mimes:jpg,jpeg,png,pdf|max:10240', // 10MB max
        ]);

        // Verify order belongs to user if provided
        if ($validated['order_id']) {
            $order = Order::find($validated['order_id']);
            if ($order->user_id !== Auth::id()) {
                abort(403, 'Unauthorized access to this order.');
            }
        }

        DB::beginTransaction();

        try {
            // Generate unique complaint number
            $complaintNumber = $this->generateComplaintNumber();

            // Create complaint
            $complaint = Complaint::create([
                'user_id' => Auth::id(),
                'order_id' => $validated['order_id'],
                'complaint_number' => $complaintNumber,
                'category' => $validated['category'],
                'subject' => $validated['subject'],
                'description' => $validated['description'],
                'priority' => $validated['priority'],
                'status' => 'pending',
                'contact_methods' => $validated['contact_methods'] ?? ['email'],
            ]);

            // Handle attachments
            if ($request->hasFile('attachments')) {
                $attachmentPaths = [];
                
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('complaints/' . $complaint->id, 'public');
                    $attachmentPaths[] = $path;
                }
                
                $complaint->update(['attachments' => $attachmentPaths]);
            }

            // Send notification email to support team
            $this->sendNewComplaintNotification($complaint);

            // Send confirmation email to user
            $this->sendComplaintConfirmation($complaint);

            DB::commit();

            return redirect()->route('user.complaints.show', $complaint)
                ->with('success', 'Your complaint has been submitted successfully. We will respond within 24-48 hours.');

        } catch (\Exception $e) {
            DB::rollback();
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'There was an error submitting your complaint. Please try again.');
        }
    }

    /**
     * Show complaint details
     */
    public function show(Complaint $complaint)
    {
        // Ensure user can only view their own complaints
        if (Auth::id() !== $complaint->user_id) {
            abort(403, 'Unauthorized access to this complaint.');
        }

        // Load relationships
        $complaint->load(['order', 'responses' => function($query) {
            $query->orderBy('created_at', 'asc');
        }]);

        return view('user.complaints.show', compact('complaint'));
    }

    /**
     * Add response to complaint
     */
    public function respond(Request $request, Complaint $complaint)
    {
        // Ensure user can only respond to their own complaints
        if (Auth::id() !== $complaint->user_id) {
            abort(403, 'Unauthorized access to this complaint.');
        }

        // Check if complaint is still open
        if (in_array($complaint->status, ['resolved', 'closed'])) {
            return redirect()->back()
                ->with('error', 'You cannot respond to a resolved or closed complaint.');
        }

        $validated = $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        // Create response
        ComplaintResponse::create([
            'complaint_id' => $complaint->id,
            'responder_id' => Auth::id(),
            'responder_type' => 'user',
            'message' => $validated['message'],
            'is_read' => false,
        ]);

        // Update complaint status if it was waiting for user response
        if ($complaint->status === 'waiting_user_response') {
            $complaint->update(['status' => 'in_progress']);
        }

        // Send notification to support team
        $this->sendResponseNotification($complaint, 'user');

        return redirect()->route('user.complaints.show', $complaint)
            ->with('success', 'Your response has been sent successfully.');
    }

    /**
     * Mark complaint as resolved
     */
    public function close(Complaint $complaint)
    {
        // Ensure user can only close their own complaints
        if (Auth::id() !== $complaint->user_id) {
            abort(403, 'Unauthorized access to this complaint.');
        }

        // Check if complaint can be closed
        if ($complaint->status !== 'resolved') {
            return redirect()->back()
                ->with('error', 'Only resolved complaints can be closed.');
        }

        $complaint->update([
            'status' => 'closed',
            'closed_at' => now(),
            'closed_by' => 'user',
        ]);

        return redirect()->route('user.complaints.show', $complaint)
            ->with('success', 'Complaint has been closed successfully.');
    }

    /**
     * Reopen a resolved complaint
     */
    public function reopen(Complaint $complaint)
    {
        // Ensure user can only reopen their own complaints
        if (Auth::id() !== $complaint->user_id) {
            abort(403, 'Unauthorized access to this complaint.');
        }

        // Check if complaint can be reopened
        if (!in_array($complaint->status, ['resolved', 'closed'])) {
            return redirect()->back()
                ->with('error', 'This complaint is already active.');
        }

        // Check if complaint was closed within last 30 days
        if ($complaint->closed_at && $complaint->closed_at->lt(now()->subDays(30))) {
            return redirect()->back()
                ->with('error', 'Complaints closed more than 30 days ago cannot be reopened.');
        }

        $complaint->update([
            'status' => 'in_progress',
            'closed_at' => null,
            'closed_by' => null,
        ]);

        // Add system response
        ComplaintResponse::create([
            'complaint_id' => $complaint->id,
            'responder_id' => null,
            'responder_type' => 'system',
            'message' => 'Complaint reopened by customer.',
            'is_read' => false,
        ]);

        return redirect()->route('user.complaints.show', $complaint)
            ->with('success', 'Complaint has been reopened successfully.');
    }

    /**
     * Mark responses as read
     */
    public function markResponsesRead(Complaint $complaint)
    {
        // Ensure user can only mark their own complaint responses as read
        if (Auth::id() !== $complaint->user_id) {
            return response()->json(['success' => false], 403);
        }

        // Mark admin responses as read
        $complaint->responses()
            ->where('responder_type', 'admin')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }

    /**
     * Generate unique complaint number
     */
    private function generateComplaintNumber()
    {
        do {
            $number = 'CMP-' . date('Y') . '-' . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
        } while (Complaint::where('complaint_number', $number)->exists());

        return $number;
    }

    /**
     * Send new complaint notification to support team
     */
    private function sendNewComplaintNotification($complaint)
    {
        // Implementation depends on your notification system
        // Example:
        // Mail::to(config('mail.support_email'))->send(new NewComplaintNotification($complaint));
    }

    /**
     * Send complaint confirmation to user
     */
    private function sendComplaintConfirmation($complaint)
    {
        // Implementation depends on your email system
        // Example:
        // Mail::to($complaint->user)->send(new ComplaintConfirmation($complaint));
    }

    /**
     * Send response notification
     */
    private function sendResponseNotification($complaint, $responderType)
    {
        // Implementation depends on your notification system
        // Notify support team when user responds
        // Notify user when support responds
    }
}