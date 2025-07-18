<footer class="bg-gray-900 text-white mt-auto">

    <!-- Main Footer Content -->
    <div class="container-responsive py-10 md:py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Company Info -->
            <div class="text-center md:text-left">
                <h3 class="text-xl md:text-2xl font-bold mb-4 text-primary-400">ChicChevron Beauty</h3>
                <p class="text-sm md:text-base text-gray-400 mb-4">Your trusted partner for premium beauty products. Quality and authenticity guaranteed.</p>
                <!-- Social Media Links -->
                <div class="flex justify-center md:justify-start space-x-4 mt-4">
                    <a href="#" class="text-gray-400 hover:text-white transition-colors" aria-label="Facebook">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors" aria-label="Instagram">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zM5.838 12a6.162 6.162 0 1112.324 0 6.162 6.162 0 01-12.324 0zM12 16a4 4 0 110-8 4 4 0 010 8zm4.965-10.405a1.44 1.44 0 112.881.001 1.44 1.44 0 01-2.881-.001z"/>
                        </svg>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors" aria-label="Twitter">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="text-center md:text-left">
                <h4 class="text-lg font-semibold mb-4">Quick Links</h4>
                <ul class="space-y-2">
                    <li><a href="{{ route('about') }}" class="text-sm md:text-base text-gray-400 hover:text-white transition-colors">About Us</a></li>
                    <li><a href="{{ route('products.index') }}" class="text-sm md:text-base text-gray-400 hover:text-white transition-colors">Products</a></li>
                    <li><a href="{{ route('categories.index') }}" class="text-sm md:text-base text-gray-400 hover:text-white transition-colors">Categories</a></li>
                    <li><a href="{{ route('contact') }}" class="text-sm md:text-base text-gray-400 hover:text-white transition-colors">Contact Us</a></li>
                    <li><a href="{{ route('faq') }}" class="text-sm md:text-base text-gray-400 hover:text-white transition-colors">FAQ</a></li>
                </ul>
            </div>

            <!-- Customer Service -->
            <div class="text-center md:text-left">
                <h4 class="text-lg font-semibold mb-4">Customer Service</h4>
                <ul class="space-y-2">
                    @auth
                        <li><a href="{{ route('user.orders.index') }}" class="text-sm md:text-base text-gray-400 hover:text-white transition-colors">Track Order</a></li>
                        <li><a href="{{ route('user.account.index') }}" class="text-sm md:text-base text-gray-400 hover:text-white transition-colors">My Account</a></li>
                        <li><a href="{{ route('user.complaints.create') }}" class="text-sm md:text-base text-gray-400 hover:text-white transition-colors">Submit Complaint</a></li>
                    @else
                        <li><a href="{{ route('orders.track-guest') }}" class="text-sm md:text-base text-gray-400 hover:text-white transition-colors">Track Order</a></li>
                    @endauth
                    <li><a href="{{ route('terms') }}" class="text-sm md:text-base text-gray-400 hover:text-white transition-colors">Terms & Conditions</a></li>
                    <li><a href="{{ route('privacy') }}" class="text-sm md:text-base text-gray-400 hover:text-white transition-colors">Privacy Policy</a></li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div class="text-center md:text-left">
                <h4 class="text-lg font-semibold mb-4">Contact Us</h4>
                <ul class="space-y-3 text-gray-400">
                    <li class="flex items-center justify-center md:justify-start">
                        <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <a href="mailto:info@chicchevronbeauty.com" class="text-sm md:text-base hover:text-white transition-colors">
                            info@chicchevronbeauty.com
                        </a>
                    </li>
                    <li class="flex items-center justify-center md:justify-start">
                        <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        <a href="tel:+94112345678" class="text-sm md:text-base hover:text-white transition-colors">
                            +94 11 234 5678
                        </a>
                    </li>
                    <li class="flex items-start justify-center md:justify-start">
                        <svg class="w-5 h-5 mr-2 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span class="text-sm md:text-base">
                            123 Beauty Street,<br>
                            Colombo 03, Sri Lanka
                        </span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Payment Methods & Security -->
        <div class="mt-8 pt-8 border-t border-gray-700">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <!-- Payment Methods -->
                <div class="text-center md:text-left">
                    <p class="text-sm text-gray-400 mb-2">Accepted Payment Methods</p>
                    <div class="flex items-center justify-center md:justify-start space-x-3">
                        <img src="/images/visa.svg" alt="Visa" class="h-8">
                        <img src="/images/mastercard.svg" alt="Mastercard" class="h-8">
                        <img src="/images/payhere.png" alt="PayHere" class="h-8">
                        <span class="text-sm text-gray-400">Cash on Delivery</span>
                    </div>
                </div>

                <!-- Security Badges -->
                <div class="text-center md:text-right">
                    <p class="text-sm text-gray-400 mb-2">Shop with Confidence</p>
                    <div class="flex items-center justify-center md:justify-end space-x-3">
                        <div class="flex items-center text-sm text-gray-400">
                            <svg class="w-5 h-5 mr-1 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            Secure Checkout
                        </div>
                        <div class="flex items-center text-sm text-gray-400">
                            <svg class="w-5 h-5 mr-1 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            100% Authentic
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Copyright -->
        <div class="mt-8 pt-8 border-t border-gray-700 text-center text-sm text-gray-400">
            <p>&copy; {{ date('Y') }} ChicChevron Beauty. All rights reserved.</p>
            <p class="mt-2">
                Made with <span class="text-red-500">❤</span> in Sri Lanka
            </p>
        </div>
    </div>
    
    <!-- Back to Top Button -->
    <button id="back-to-top" 
            class="fixed bottom-4 right-4 z-40 p-3 bg-primary-600 text-white rounded-full shadow-lg hover:bg-primary-700 transition-all duration-300 opacity-0 invisible"
            aria-label="Back to top">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
        </svg>
    </button>
</footer>

<script>
// Back to top button functionality
window.addEventListener('scroll', function() {
    const backToTopButton = document.getElementById('back-to-top');
    if (window.pageYOffset > 300) {
        backToTopButton.style.opacity = '1';
        backToTopButton.style.visibility = 'visible';
    } else {
        backToTopButton.style.opacity = '0';
        backToTopButton.style.visibility = 'hidden';
    }
});

document.getElementById('back-to-top').addEventListener('click', function() {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
});
</script>