@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center px-4 py-8">
    <div class="w-full max-w-2xl">
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8 md:p-12">
            <div id="processing" class="mb-8">
                <div class="w-20 h-20 mx-auto mb-6 relative">
                    <div class="absolute inset-0 border-4 border-gray-200 rounded-full"></div>
                    <div class="absolute inset-0 border-4 border-primary-600 rounded-full border-t-transparent animate-spin"></div>
                </div>
                <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-3 text-center">Processing Your Payment</h2>
                <p class="text-gray-600 text-center mb-2">Please wait while we confirm your payment...</p>
                <p class="text-sm text-gray-500 text-center">Order #: {{ $order->order_number }}</p>
            </div>

            <div id="success" class="hidden">
                <div class="text-green-500 mb-6 flex justify-center">
                    <div class="bg-green-100 rounded-full p-4">
                        <svg class="h-16 w-16" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
                <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-3 text-center">Payment Successful!</h2>
                <p class="text-gray-600 mb-4 text-center">Thank you for your order.</p>
                <p class="text-xl font-semibold mb-8 text-center text-primary-600">Order Total: Rs. {{ number_format($order->total_amount, 2) }}</p>
                
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('user.orders.show', $order) }}" class="inline-flex items-center justify-center bg-gradient-to-r from-primary-600 to-primary-700 text-white px-6 py-3 rounded-lg hover:from-primary-700 hover:to-primary-800 font-medium transition-all duration-200 shadow-lg">
                        View Order Details
                    </a>
                    <a href="{{ route('home') }}" class="inline-flex items-center justify-center bg-gray-100 text-gray-800 px-6 py-3 rounded-lg hover:bg-gray-200 font-medium transition-all duration-200">
                        Continue Shopping
                    </a>
                </div>
            </div>

            <div id="failed" class="hidden">
                <div class="text-red-500 mb-6 flex justify-center">
                    <div class="bg-red-100 rounded-full p-4">
                        <svg class="h-16 w-16" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
                <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-3 text-center">Payment Failed</h2>
                <p class="text-gray-600 mb-8 text-center">We couldn't process your payment. Please try again.</p>
                
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('checkout.index') }}" class="inline-flex items-center justify-center bg-gradient-to-r from-primary-600 to-primary-700 text-white px-6 py-3 rounded-lg hover:from-primary-700 hover:to-primary-800 font-medium transition-all duration-200 shadow-lg">
                        Try Again
                    </a>
                    <a href="{{ route('home') }}" class="inline-flex items-center justify-center bg-gray-100 text-gray-800 px-6 py-3 rounded-lg hover:bg-gray-200 font-medium transition-all duration-200">
                        Back to Shop
                    </a>
                </div>
            </div>
            
            @if(config('app.debug'))
            <!-- Development only: Manual confirmation -->
            <div id="manual-confirm" class="mt-8 p-6 bg-yellow-50 rounded-xl border border-yellow-200">
                <p class="text-sm text-gray-700 mb-3 text-center">Development Mode: If payment was successful in PayHere but not updating here, click below:</p>
                <div class="text-center">
                    <button onclick="manualConfirm()" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 font-medium transition-colors duration-200">
                        Manually Confirm Payment
                    </button>
                </div>
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