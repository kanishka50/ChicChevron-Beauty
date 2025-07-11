@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-8 text-center">
            <div id="processing" class="mb-8">
                <div class="animate-spin rounded-full h-16 w-16 border-b-2 border-pink-500 mx-auto mb-4"></div>
                <h2 class="text-2xl font-semibold mb-2">Processing Your Payment</h2>
                <p class="text-gray-600">Please wait while we confirm your payment...</p>
                <p class="text-sm text-gray-500 mt-2">Order #: {{ $order->order_number }}</p>
            </div>

            <div id="success" class="hidden">
                <div class="text-green-500 mb-4">
                    <svg class="h-16 w-16 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <h2 class="text-2xl font-semibold mb-2">Payment Successful!</h2>
                <p class="text-gray-600 mb-4">Thank you for your order.</p>
                <p class="text-lg font-medium mb-6">Order Total: Rs. {{ number_format($order->total_amount, 2) }}</p>
                
                <div class="space-y-4">
                    <a href="{{ route('user.orders.show', $order) }}" class="inline-block bg-pink-500 text-white px-6 py-3 rounded-md hover:bg-pink-600 transition">
                        View Order Details
                    </a>
                    <a href="{{ route('home') }}" class="inline-block bg-gray-200 text-gray-800 px-6 py-3 rounded-md hover:bg-gray-300 transition ml-4">
                        Continue Shopping
                    </a>
                </div>
            </div>

            <div id="failed" class="hidden">
                <div class="text-red-500 mb-4">
                    <svg class="h-16 w-16 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <h2 class="text-2xl font-semibold mb-2">Payment Failed</h2>
                <p class="text-gray-600 mb-6">We couldn't process your payment. Please try again.</p>
                
                <div class="space-y-4">
                    <a href="{{ route('checkout.index') }}" class="inline-block bg-pink-500 text-white px-6 py-3 rounded-md hover:bg-pink-600 transition">
                        Try Again
                    </a>
                    <a href="{{ route('home') }}" class="inline-block bg-gray-200 text-gray-800 px-6 py-3 rounded-md hover:bg-gray-300 transition ml-4">
                        Back to Shop
                    </a>
                </div>
            </div>
            
            @if(config('app.debug'))
            <!-- Development only: Manual confirmation -->
            <div id="manual-confirm" class="mt-8 p-4 bg-yellow-100 rounded-lg">
                <p class="text-sm text-gray-700 mb-2">Development Mode: If payment was successful in PayHere but not updating here, click below:</p>
                <button onclick="manualConfirm()" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    Manually Confirm Payment
                </button>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
let checkCount = 0;
const maxChecks = 30;

function checkPaymentStatus() {
    // Force HTTPS
    const statusUrl = '{{ route("checkout.payment.status", $order) }}'.replace('http://', 'https://');
    
    fetch(statusUrl, {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log('Payment status:', data);
        
        if (data.payment_status === 'completed') {
            document.getElementById('processing').classList.add('hidden');
            document.getElementById('success').classList.remove('hidden');
            if (document.getElementById('manual-confirm')) {
                document.getElementById('manual-confirm').classList.add('hidden');
            }
        } else if (data.payment_status === 'failed' || data.payment_status === 'cancelled') {
            document.getElementById('processing').classList.add('hidden');
            document.getElementById('failed').classList.remove('hidden');
            if (document.getElementById('manual-confirm')) {
                document.getElementById('manual-confirm').classList.add('hidden');
            }
        } else {
            checkCount++;
            if (checkCount < maxChecks) {
                setTimeout(checkPaymentStatus, 2000);
            }
        }
    })
    .catch(error => {
        console.error('Error checking payment status:', error);
        checkCount++;
        if (checkCount < maxChecks) {
            setTimeout(checkPaymentStatus, 2000);
        }
    });
}

// Manual confirmation function
function manualConfirm() {
    if (confirm('Confirm that payment was successful in PayHere?')) {
        // Force HTTPS
        const confirmUrl = '{{ route("checkout.payment.confirm", $order) }}'.replace('http://', 'https://');
        
        fetch(confirmUrl, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                payment_id: 'MANUAL-CONFIRM'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        })
        .catch(error => console.error('Error:', error));
    }
}

// Start checking immediately
checkPaymentStatus();
</script>
@endsection